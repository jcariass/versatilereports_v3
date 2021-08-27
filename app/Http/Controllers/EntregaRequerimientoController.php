<?php

namespace App\Http\Controllers;

use App\Models\actividad_evidencia;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DataTables;
use App\Models\formulario_pregunta;
use App\Models\Requerimiento;
use App\Models\Obligacion;
use App\Models\Contrato;
use App\Models\Informe;
use App\Models\RespuestaRequerimiento;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntregaRequerimientoController extends Controller
{
    public function view_list(){
        return view('entrega_requerimientos.listar_ent_requerimientos');
    }   

    public function view_insert_archive($id){   
        $requerimiento = Requerimiento::findOrFail($id);
        return view('entrega_requerimientos.insertar_archivo', compact("requerimiento"));
    }

    public function view_edit_archive($id){   
        $respuesta = RespuestaRequerimiento::findOrFail($id);
        return view('entrega_requerimientos.editar_archivo', compact("respuesta"));
    }
    
    public function view_insert_informe($id){
        $requerimiento = Requerimiento::findOrFail($id);
        if ($requerimiento->id_formulario ==  null) {
            return redirect()->route('listar_ent_requerimientos')->withErrors('Ocurrio un error inesperado.');
        }
        $obligaciones = Obligacion::select('id_obligacion', 'detalle')
                ->where('fecha_vencimiento', '>', Carbon::now()->toDateString())
                ->where('id_proceso', '=', $requerimiento->id_proceso)->get();
        return view('entrega_requerimientos.insertar_informe', compact("requerimiento", "obligaciones"));
    }

    public static function preguntas_informe($id_obligacion, $id_formulario){
        $preguntas = formulario_pregunta::join('preguntas', 'preguntas.id_pregunta', '=', 'formulario_pregunta.id_pregunta')
                ->select('preguntas.*')->where('formulario_pregunta.id_obligacion', '=', $id_obligacion)
                ->where('formulario_pregunta.id_formulario', '=', $id_formulario)
                ->where('preguntas.estado', '=', 1)->get()->toArray();
        return $preguntas;
    }

    public function list(){
        if (request()->ajax()) {
            $contrato = Contrato::select('*')
                ->where('id_persona', '=', ''.Auth::user()->id_persona.'')
                ->where('estado', '=', '1')
                ->first();
            if ($contrato == null) {
                $requerimientos = array();
            }else{
                $requerimientos = Requerimiento::where('fecha_finalizacion', '>', Carbon::now()->toDateString())
                        ->where('id_proceso', '=', $contrato->id_proceso)
                        ->where('estado', '=', 1)
                        ->orderBy('fecha_finalizacion', 'asc')->get();
            }
            return DataTables::of($requerimientos)
            ->editColumn('tipo_requerimiento', function($requerimiento){
                return $requerimiento->id_tipo_requerimiento == 1 ? 'Informe' : 'Cargar archivo';
            })
            ->addColumn('Opciones', function($requerimiento) use ($contrato){
                if ($requerimiento->id_tipo_requerimiento == 1) {
                    $tipo_informe = $this->requerimiento_informe($requerimiento->id_requerimiento, $contrato->id_contrato);
                    if ($tipo_informe == null) {
                        return '<a href="/entrega/requerimientos/informe/contractual/'.$requerimiento->id_requerimiento.'" class="btn btn-versatile_reports">Responder</a>';
                    }else{
                        $opcion1 = '<a href="#" class="btn btn-versatile_reports">Editar</a>';
                        $opcion2 = '<a href="#" class="btn btn-gris">Generar informe</a>';
                        return $opcion1 . ' ' . $opcion2;
                    }
                }
                elseif($requerimiento->id_tipo_requerimiento == 2){
                    $tipo_archivo = $this->requerimiento_archivo($requerimiento->id_requerimiento, $contrato->id_contrato);
                    if ($tipo_archivo == null) {
                        return '<a href="/entrega/requerimientos/cargar/archivo/'.$requerimiento->id_requerimiento.'" class="btn btn-versatile_reports">Responder</a>';
                    }else{
                        $opcion1 = '<a href="/entrega/requerimientos/editar/archivo/'.$tipo_archivo->id_respuesta_requerimiento.'" class="btn btn-versatile_reports">Editar</a>';
                        $opcion2 = '<a href="/entrega/requerimientos/descargar/archivo/'.$tipo_archivo->nombre.'" class="btn btn-gris">Descargar</a>';
                        return $opcion1 . ' ' . $opcion2;
                    }
                }
            })
            ->rawColumns(['Opciones', 'estado'])
            ->make(true);
        }
        return redirect()->route('dashboard');
    }

    public function download_archive($nombre){
        $archivo = public_path('uploads/archivos/'.$nombre.'');
        return response()->download($archivo);
    }

    private function requerimiento_informe($id_requerimiento, $id_contrato){
        $informe = Requerimiento::join('informes', 'informes.id_requerimiento', '=', 'requerimientos.id_requerimiento')
                    ->where('informes.id_requerimiento', '=', $id_requerimiento)
                    ->where('informes.id_contrato', '=', $id_contrato)
                    ->first();
        return $informe;
    }
    
    private function requerimiento_archivo($id_requerimiento, $id_contrato){
        $archivo = RespuestaRequerimiento::where('id_requerimiento', '=', $id_requerimiento)
                    ->where('id_contrato', '=', $id_contrato)->first();
        return $archivo;
    }

    public function insert_archive(Request $request){
        $this->validations($request);
        try {
            $contrato = Contrato::select('*')
                ->where('id_persona', '=', ''.Auth::user()->id_persona.'')
                ->where('estado', '=', '1')
                ->first();
            if ($contrato == null) {
                return redirect()->route('listar_ent_requerimientos')->withErrors('No se pudo encontrar un contrato activo para este usuario');
            }else{
                $requerimiento = Requerimiento::findOrFail($request->id_requerimiento);
                $fecha_carga = Carbon::now()->toDateTimeString();
                $fecha_archivo = Carbon::now()->format('d-m-Y-H-i-s');
                $archivo = $fecha_archivo.'-'.time().'.'.$request->archivo->extension();
                $request->archivo->move(public_path('uploads/archivos'), $archivo);
                RespuestaRequerimiento::create([
                    'nombre' => $archivo,
                    'fecha_carga' => $fecha_carga,
                    'id_contrato' => $contrato->id_contrato,
                    'id_requerimiento' => $requerimiento->id_requerimiento
                ]);
            }
            return redirect()->route('listar_ent_requerimientos')->withSuccess('Se envio el archivo');
        } catch (Exception $e) {
            return redirect()->route('listar_ent_requerimientos')->withErrors('Ocurrio un error: '.$e->getMessage());
        }
    }

    public function save(Request $request){
        $contrato = Contrato::select('*')
                ->where('id_persona', '=', ''.Auth::user()->id_persona.'')
                ->where('estado', '=', '1')
                ->first();
        if ($contrato == null) {
            return redirect()->route('listar_ent_requerimientos')->withErrors('Usted no cuenta con un contrato disponible, por lo tanto no puedes presentar este informe.');
        }
        try {
            DB::beginTransaction();
            $informe = Informe::create([
                'fecha_carga' => Carbon::now()->toDateTimeString('minute'),
                'id_contrato' => $contrato->id_contrato,
                'id_requerimiento' => $request->id_requerimiento,
            ]);
            foreach ($request->obligaciones as $key => $value) {
                actividad_evidencia::create([
                    'id_obligacion' => $value,
                    'id_informe' => $informe->id_informe,
                    'respuesta_actividad' => $request->actividades[$key],
                    'respuesta_evidencia' => $request->evidencias[$key],
                ]);
            }
            DB::commit();
            return redirect()->route('listar_ent_requerimientos')->with('success', 'Se envio el informe con éxito.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('listar_ent_requerimientos')->withErrors('Ocurrio un error: '.$e->getMessage());
        }
        dd($request->all(), $contrato);
    }

    public function update_archive(Request $request){
        $this->validations($request);
        $respuesta = RespuestaRequerimiento::findOrFail($request->id_respuesta_requerimiento);
        if ($respuesta == null) return redirect()->route('listar_ent_requerimientos')->withErrors('No se pudo editar la respuesta.');
        try {
            $fecha_archivo = Carbon::now()->format('d-m-Y-H-i-s');
            $archivo = $fecha_archivo.'-'.time().'.'.$request->archivo->extension();
            $request->archivo->move(public_path('uploads/archivos'), $archivo);
            unlink(public_path('uploads/archivos/'.$respuesta->nombre.''));
            $respuesta->update([
                'nombre' => $archivo,
            ]);
            return redirect()->route('listar_ent_requerimientos')->with('success', 'Se modifico con éxito.');
        } catch (Exception $e) {
            return redirect()->route('listar_ent_requerimientos')->withErrors('Ocurrio un error: '.$e->getMessage());
        }
    }

    private function validations(Request $request){
        $request->validate([
            'archivo' => 'required|file|mimes:pdf|max:10240',
        ]);
    }
}
 