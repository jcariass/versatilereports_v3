<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DataTables;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\actividad_evidencia;
use App\Models\formulario_pregunta;
use App\Models\Requerimiento;
use App\Models\Obligacion;
use App\Models\Contrato;
use App\Models\Desplazamiento;
use App\Models\Informe;
use App\Models\RespuestaRequerimiento;
use PDF;
use PhpParser\Node\Expr\FuncCall;

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
            return redirect()->route('listar_ent_requerimientos')->withErrors('Ocurrió un error inesperado');
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
                    }elseif($tipo_informe == 'Aprobado'){
                        return '<div class="alert alert-success" role="alert">Requerimiento aprobado</div>';
                    }else{
                        $opcion1 = '<a href="/entrega/requerimientos/editar/informe/contractual/'.$tipo_informe->id_informe.'" class="btn btn-versatile_reports">Editar</a>';
                        $opcion2 = '<a href="/entrega/requerimientos/generar/informe/'.$tipo_informe->id_informe.'" class="btn btn-gris">Reporte</a>';
                        return $opcion1 . ' ' . $opcion2;
                    }
                }
                elseif($requerimiento->id_tipo_requerimiento == 2){
                    $tipo_archivo = $this->requerimiento_archivo($requerimiento->id_requerimiento, $contrato->id_contrato);
                    if ($tipo_archivo == null) {
                        return '<a href="/entrega/requerimientos/cargar/archivo/'.$requerimiento->id_requerimiento.'" class="btn btn-versatile_reports">Responder</a>';
                    }elseif($tipo_archivo == 'Aprobado'){
                        return '<div class="alert alert-success" role="alert">Requerimiento aprobado</div>';
                    }else{
                        $opcion1 = '<a href="/entrega/requerimientos/editar/archivo/'.$tipo_archivo->id_respuesta_requerimiento.'" class="btn btn-versatile_reports">Editar</a>';
                        $opcion2 = '<a href="/entrega/requerimientos/descargar/archivo/'.$tipo_archivo->nombre.'" class="btn btn-gris">Descargar</a>';
                        return $opcion1 . ' ' . $opcion2;
                    }
                }
            })
            ->addColumn('observacion', function($requerimiento) use ($contrato){
                if ($requerimiento->id_tipo_requerimiento == 1) {
                    $informe = Requerimiento::join('informes', 'informes.id_requerimiento', '=', 'requerimientos.id_requerimiento')
                            ->where('informes.id_requerimiento', '=', $requerimiento->id_requerimiento)
                            ->where('informes.id_contrato', '=', $contrato->id_contrato)
                            ->first();
                    if($informe == null)
                        return 'Sin observación';
                    else
                        return $informe->observacion != null ? $informe->observacion : 'Sin observación'; 
                }
                elseif($requerimiento->id_tipo_requerimiento == 2){
                    $archivo = RespuestaRequerimiento::select('*')->where('id_requerimiento', '=', $requerimiento->id_requerimiento)
                        ->where('id_contrato', '=', $contrato->id_contrato)->first();
                    if($archivo == null)
                        return 'Sin observación';
                    else
                        return $archivo->observacion != null ? $archivo->observacion : 'Sin observación'; 
                }
            })
            ->rawColumns(['Opciones', 'estado', 'observacion'])
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
        if($informe != null){
            if($informe->estado_uno == 1 && $informe->estado_dos == 1){
                return $informe = 'Aprobado';
            }
        }
        return $informe;
    }

    private function requerimiento_archivo($id_requerimiento, $id_contrato){
        $archivo = RespuestaRequerimiento::select('*')->where('id_requerimiento', '=', $id_requerimiento)
                    ->where('id_contrato', '=', $id_contrato)->first();
        if($archivo != null){
            if($archivo->estado == 1){
                return $archivo = 'Aprobado';
            }
        }
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
            return redirect()->route('listar_ent_requerimientos')->withSuccess('Se envió el archivo');
        } catch (Exception $e) {
            return redirect()->route('listar_ent_requerimientos')->withErrors('Ocurrió un error: '.$e->getMessage());
        }
    }

    public function save(Request $request){
        $contrato = Contrato::select('*')
                ->where('id_persona', '=', ''.Auth::user()->id_persona.'')
                ->where('estado', '=', '1')
                ->first();
        if ($contrato == null)
            return redirect()->route('listar_ent_requerimientos')->withErrors('Usted no cuenta con un contrato disponible, por lo tanto no puedes presentar este informe');
        else{
            try {
                DB::beginTransaction();
                $informe = Informe::create([
                    'fecha_carga' => Carbon::now()->toDateTimeString('minute'),
                    'id_contrato' => $contrato->id_contrato,
                    'id_requerimiento' => $request->id_requerimiento,
                ]);
                foreach ($request->preguntas as $key => $value) {
                    actividad_evidencia::create([
                        'id_pregunta' => $value,
                        'id_informe' => $informe->id_informe,
                        'respuesta_actividad' => $request->actividades[$key],
                        'respuesta_evidencia' => $request->evidencias[$key],
                        'id_obligacion' => $request->obligaciones[$key],
                    ]);
                }
                if(count($request->numeros_orden) > 0){
                    foreach ($request->numero_orden as $key => $value) {
                        Desplazamiento::create([
                            'numero_orden' => $value,
                            'lugar' => $request->lugar[$key],
                            'fecha_inicio' => $request->fecha_inicio[$key],
                            'fecha_fin' => $request->fecha_fin[$key],
                            'id_informe' => $informe->id_informe
                        ]);
                    }
                }
                DB::commit();
                return redirect()->route('listar_ent_requerimientos')->with('success', 'Se envió el informe con éxito');
            } catch (Exception $e) {
                DB::rollBack();
                return redirect()->route('listar_ent_requerimientos')->withErrors('Ocurrió un error: '.$e->getMessage());
            }
        }
    }

    public function update_archive(Request $request){
        $this->validations($request);
        $respuesta = RespuestaRequerimiento::findOrFail($request->id_respuesta_requerimiento);
        if ($respuesta == -null) return redirect()->route('listar_ent_requerimientos')->withErrors('No se pudo editar la respuesta');
        try {
            $fecha_archivo = Carbon::now()->format('d-m-Y-H-i-s');
            $archivo = $fecha_archivo.'-'.time().'.'.$request->archivo->extension();
            $request->archivo->move(public_path('uploads/archivos'), $archivo);
            unlink(public_path('uploads/archivos/'.$respuesta->nombre.''));
            $respuesta->update([
                'nombre' => $archivo,
            ]);
            return redirect()->route('listar_ent_requerimientos')->with('success', 'Se modificó con éxito');
        } catch (Exception $e) {
            return redirect()->route('listar_ent_requerimientos')->withErrors('Ocurrió un error: '.$e->getMessage());
        }
    }

    public function view_actualizar_informe($id){
        $informe = Informe::findOrFail($id);
        $requerimiento = Requerimiento::findOrFail($informe->id_requerimiento);
        $obligaciones = Obligacion::select('id_obligacion', 'detalle')
                ->where('fecha_vencimiento', '>', Carbon::now()->toDateString())
                ->where('id_proceso', '=', $requerimiento->id_proceso)->get();
        return view('entrega_requerimientos.editar_informe', compact('informe', 'requerimiento', 'obligaciones'));
    }

    public static function buscar_respuesta($id_pregunta, $id_informe){
        $respuesta = actividad_evidencia::select('id_actividad_evidencia', 'respuesta_actividad', 'respuesta_evidencia')
                ->where('id_pregunta', '=', $id_pregunta)
                ->where('id_informe', '=', $id_informe)->first();
        return $respuesta;
    }

    public function update_informe(Request $request){
        $informe = Informe::findOrFail($request->id_informe);
        $preguntas = $request->preguntas;
        $id_respuestas = $request->id_respuestas;
        $actividades = $request->actividades;
        $evidencias = $request->evidencias;
        if($informe == null)
            return redirect()->route('listar_ent_requerimientos')->withErrors('No se pudo actualizar el informe.');
        else{
            try {
                DB::beginTransaction();
                foreach ($preguntas as $key => $value) {
                    $validar_respuesta = $this->validar_respuesta($id_respuestas[$key], $actividades[$key], $evidencias[$key]);
                    if($validar_respuesta == null){
                        actividad_evidencia::create([
                            'id_pregunta' => $value,
                            'id_informe' => $informe->id_informe, 
                            'respuesta_actividad' => $actividades[$key], 
                            'respuesta_evidencia' => $evidencias[$key],
                            'id_obligacion' => $request->obligaciones[$key],
                        ]);
                    }else if($validar_respuesta != false){
                        $validar_respuesta->update([
                            'respuesta_actividad' => $actividades[$key], 
                            'respuesta_evidencia' => $evidencias[$key]
                        ]);
                    }
                }
                DB::commit();
                return redirect()->route('listar_ent_requerimientos')->with('success', 'Se actualizo su respuesta.');
            } catch (Exception $e) {
                DB::rollBack();
                return redirect()->route('listar_ent_requerimientos')->withErrors('Ocurrio un error: '.$e->getMessage());
            }
        }
    }

    public function generar_informe($id){
        $informe = Informe::find($id);
        if($informe == null)
            return redirect()->route('listar_ent_requerimientos')->withErrors('No se encontro el informe');
        else{
            $requerimiento = Requerimiento::select('id_proceso')->where('id_requerimiento', '=', $informe->id_requerimiento)->first();
            $obligaciones = Obligacion::select('id_obligacion', 'detalle')->where('id_proceso', '=', $requerimiento->id_proceso)->get();
            $informacion = Contrato::join('informes', 'informes.id_contrato', '=', 'contratos.id_contrato')
                ->join('objetos', 'objetos.id_objeto', '=', 'contratos.id_objeto')
                ->join('supervisores', 'supervisores.id_supervisor', '=', 'contratos.id_supervisor')
                ->join('personas as p2', 'p2.id_persona', '=', 'supervisores.id_persona')
                ->join('personas as p1', 'p1.id_persona', '=', 'contratos.id_persona')
                ->join('centros', 'centros.id_centro', '=', 'contratos.id_centro')
                ->select(
                    'objetos.nombre as nombre_objeto', 'objetos.detalle as detalle_objeto',
                    'p2.nombre as nombre_supervisor', 'p2.primer_apellido as primer_apellido_supervisor', 'p2.segundo_apellido as segundo_apellido_supervisor',
                    'p1.nombre as nombre_contratista', 'p1.primer_apellido as primer_apellido_contratista', 'p1.tipo_documento as tipo_documento_contratista',
                    'p1.segundo_apellido as segundo_apellido_contratista', 'p1.documento as documento_contratista',
                    'centros.nombre as nombre_centro', 'contratos.forma_pago as forma_pago_contrato',
                    'contratos.numero_contrato'
                )->where('contratos.id_contrato', '=', $informe->id_contrato)->first();
            setlocale(LC_TIME, "spanish");
            $fecha_generacion = strftime("%d de %B de %Y");
            $pdf = PDF::loadView('entrega_requerimientos.generar_informe', compact("informe", "obligaciones", "informacion", "fecha_generacion"));
            return $pdf->stream('archivo.pdf');
        }
    }

    private function validar_respuesta($id_actividad_evidencia, $actividad, $evidencia){
        $respuesta = actividad_evidencia::find($id_actividad_evidencia);
        if($respuesta == null)
            return $respuesta;
        else{
            if($respuesta->respuesta_actividad == $actividad && $respuesta->respuesta_evidencia == $evidencia)
                return false;
            else
                return $respuesta;
        }
    }

    public static function getRespuesta($id_informe, $id_obligacion){
        $respuestas = actividad_evidencia::select('*')
            ->where('id_informe', '=', $id_informe)
            ->where('id_obligacion', '=', $id_obligacion)
            ->get();
        return $respuestas;
    }

    private function validations(Request $request){
        $request->validate([
            'archivo' => 'required|file|mimes:pdf|max:10240',
        ]);
    }
}