<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DataTables;
use Exception;
use App\Models\Contratista;
use App\Models\Contrato;
use App\Models\Requerimiento;
use App\Models\RespuestaRequerimiento;

class EntregaRequerimientoController extends Controller
{
    public function view_list(){
        return view('entrega_requerimientos.listar_ent_requerimientos');
    }   

    /* public function view_insert_archive($id){   
        $requerimiento = Requerimiento::find($id);
        return view('entrega_requerimientos.insertar_archivo', compact("requerimiento"));
    } */
    
    /* public function view_insert_informe($id){
        $requerimiento = Requerimiento::find($id);
        $preguntas = Obligacion::join('procesos', 'procesos.id_proceso', '=', 'obligaciones.id_proceso')
            ->join('actividades', 'actividades.id_obligacion', '=', 'obligaciones.id_obligacion')
            ->join('evidencias', 'evidencias.id_actividad', '=', 'actividades.id_actividad')
            ->select(
                'obligaciones.id_obligacion',
                'obligaciones.detalle as detalle_obligacion',
                'actividades.id_actividad',
                'actividades.detalle as detalle_actividad',
                'evidencias.id_evidencia',
                'evidencias.detalle as detalle_evidencia'
                )
            ->where('obligaciones.id_proceso', '=', ''.$requerimiento->id_proceso.'')->get();
        // $preguntas_actividades = Actividad::join('obligaciones', 'obligaciones.id_obligacion', '=', 'actividades.id_obligaciones')
        //     ->select('actividades.id_obligacion','actividades.id_actividad', 'actividades.detalle as detalle_actividad')
        //     ->where('actividades.id_obligacion', '=', );
        return view('entrega_requerimientos.insertar_informe', compact("requerimiento", "preguntas"));
    } */

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
            ->addColumn('Opciones', function($requerimiento){
                if ($requerimiento->id_tipo_requerimiento == 1) {
                    $opcion1 = '<a href="/entrega/requerimiento/informe/contractual/'.$requerimiento->id_requerimiento.'" class="btn btn-versatile_reports"><i class="ft-file-text"></i></a>';
                    $opcion2 = '<a href="#" class="btn btn-gris"><i class="ft-download"></i></a>';
                    return $opcion1 . ' ' . $opcion2;
                }
                elseif($requerimiento->id_tipo_requerimiento == 2){
                    $opcion1 = '<a href="/entrega/requerimiento/cargar/archivo/'.$requerimiento->id_requerimiento.'" class="btn btn-versatile_reports"><i class="ft-file-plus"></i></a>';
                    $opcion2 = '<a href="#" class="btn btn-gris"><i class="ft-download"></i></a>';
                    return $opcion1 . ' ' . $opcion2;
                }
            })
            ->rawColumns(['Opciones', 'estado'])
            ->make(true);
        }
        return redirect()->route('dashboard');
    }

    public function insert_archive(Request $request){
        $request->validate([
            'archivo' => 'required|file|mimes:pdf|max:10240',
        ]);
        try {
            $contratista = Contratista::select('*')->where('documento', '=', ''.Auth::user()->documento.'')->take(1)->get();
            $contrato = Contrato::select('*')
                ->where('id_contratista', '=', ''.$contratista[0]->id_contratista.'')
                ->where('estado', '=', '1')
                ->take(1)->get();
            if (count($contrato) == 0) {
                return redirect()->route('listar_ent_requerimientos')->withErrors('No se pudo encontrar un contrato activo para este usuario');
            }else{
                $requerimiento = Requerimiento::find($request->id_requerimiento);
                $fecha = Carbon::now();
                $fecha_carga = $fecha->format('Y-m-d H:i:s');
                $fecha_archivo = $fecha->format('d-m-Y-H-i-s');
                $archivo = $fecha_archivo.'-'.time().'.'.$request->archivo->extension();
                $request->archivo->move(public_path('uploads/archivos'), $archivo);
                RespuestaRequerimiento::create([
                    'nombre' => $archivo,
                    'fecha_carga' => $fecha_carga,
                    'id_contrato' => $contrato[0]->id_contrato,
                    'id_requerimiento' => $requerimiento->id_requerimiento
                ]);
            }
            return redirect()->route('listar_ent_requerimientos')->withSuccess('Se envio el archivo');
        } catch (Exception $e) {
            return redirect()->route('listar_ent_requerimientos')->withErrors('Ocurrio un error: '.$e->getMessage());
        }
    }
}
 