<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RespuestaRequerimientoExport;
use App\Models\Informe;
use App\Models\RespuestaRequerimiento;
use App\Models\Requerimiento;

class RevisionRequerimientoController extends Controller
{
    public function view_list(){
        return view('revision_requerimientos.listar_rev_requerimientos');
    }

    public function list(){
        if(request()->ajax()){
            $requerimientos = Requerimiento::where('estado', '=', '1')
                ->where('fecha_finalizacion', '>', Carbon::now()->toDateString())
                ->orderBy('fecha_finalizacion', 'asc')->get();
            return DataTables::of($requerimientos)
                ->addColumn('Opciones', function($requerimiento){
                    $opcion = '<a href="/revision/requerimientos/detalles/'.$requerimiento->id_requerimiento.'" class="btn btn-versatile_reports"><i class="ft-eye"></i></a>';
                    return $opcion;
                })
                ->rawColumns(['Opciones'])
                ->make(true);
        }
        return redirect()->route('dashboard');
    }

    public function view_list_details($id){
        $requerimiento = Requerimiento::findOrFail($id);
        return view('revision_requerimientos.listar_rev_detalles', compact("requerimiento"));
    }

    public function list_details($id, $tipo){
        if (request()->ajax()) {
            if ($tipo == 1) {
                return $this->list_details_informes($id);
            }elseif($tipo == 2){
                return $this->list_details_archivos($id);
            }else{
                return redirect()->route('dashboard');
            }
        }
        return redirect()->route('dashboard');
    }
    
    private function list_details_informes($id){
        $informes = Informe::join('contratos', 'contratos.id_contrato', '=', 'informes.id_contrato')
            ->join('personas', 'personas.id_persona', '=', 'contratos.id_persona')
            ->select('informes.*', 'personas.documento')
            ->where('id_requerimiento', '=', ''.$id.'')->get();
            return DataTables::of($informes)
                ->editColumn('estado', function($informes){
                    if ($informes->estado_uno == 0) {
                        $btn_estado_uno = '<div class="badge badge-danger">No aprobado</div>';
                    }else{
                        $btn_estado_uno = '<div class="badge badge-success">Aprobado</div>';
                    }
                    if ($informes->estado_dos == 0) {
                        $btn_estado_dos = '<div class="badge badge-danger">No aprobado</div>';
                    }else{
                        $btn_estado_dos = '<div class="badge badge-success">Aprobado</div>';
                    }
                    return $btn_estado_uno . ' ' . $btn_estado_dos;
                })
                ->addColumn('Opciones', function($informes){
                    $ver = '<a href="#" class="btn btn-versatile_reports">Ver</a>';
                    $informe = '<a href="#" class="btn btn-gris">Generar informe</a>';
                    if ($informes->estado_uno == 0) {
                        $estado_uno = '<a href="#" class="btn btn-estados btn-success"><i class="ft-check"></i></a>';
                    }else{
                        $estado_uno = '<a href="#" class="btn btn-estados btn-danger"><i class="ft-trash"></i></a>';
                    }
                    if ($informes->estado_dos == 0) {
                        $estado_dos = '<a href="#" class="btn btn-estados btn-success"><i class="ft-check"></i></a>';
                    }else{
                        $estado_dos = '<a href="#" class="btn btn-estados btn-danger"><i class="ft-trash"></i></a>';
                    }
                    return $ver . ' ' . $informe. ' ' . $estado_uno . ' ' . $estado_dos;
                })
                ->rawColumns(['Opciones', 'estado'])
                ->make(true);
    }   

    private function list_details_archivos($id){
        $respuestas_requerimientos = RespuestaRequerimiento::join('contratos', 'contratos.id_contrato', '=', 'respuestas_requerimientos.id_contrato')
            ->join('personas', 'personas.id_persona', '=', 'contratos.id_persona')
            ->select('respuestas_requerimientos.*', 'personas.documento')
            ->where('id_requerimiento', '=', ''.$id.'')->get();
            return DataTables::of($respuestas_requerimientos)
                ->editColumn('estado', function($respuesta_requerimiento){
                    if ($respuesta_requerimiento->estado == 0) {
                        return '<div class="badge badge-danger">No aprobado</div>';
                    }else{
                        return '<div class="badge badge-success">Aprobado</div>';
                    }
                })
                ->addColumn('Opciones', function($respuesta_requerimiento){
                    $opcion = '<a href="/revision/requerimientos/descargar/archivo/'.$respuesta_requerimiento->nombre.'" class="btn btn-versatile_reports"><i class="ft-download"></i></a>';
                    if ($respuesta_requerimiento->estado == 0) {
                        $estado = '<a href="#" class="btn btn-estados btn-success"><i class="ft-check"></i></a>';
                    }else{
                        $estado = '<a href="#" class="btn btn-estados btn-danger"><i class="ft-trash"></i></a>';
                    }
                    return $opcion . ' ' . $estado;
                })
                ->rawColumns(['Opciones', 'estado'])
                ->make(true);
    }       

    public function download_archive($nombre){
        $archivo = public_path('uploads/archivos/'.$nombre.'');
        return response()->download($archivo);
    }

    public function generar_reporte(Request $request){
        if ($request->id_tipo_requerimiento == 1) {
            $respuestas_requerimientos = Informe::join('requerimientos', 'requerimientos.id_requerimiento', '=', 'informes.id_requerimiento')
            ->join('contratos', 'contratos.id_contrato', '=', 'informes.id_contrato')
            ->join('personas', 'personas.id_persona', '=', 'contratos.id_persona')
            ->select(
                'requerimientos.nombre as nombre_requerimiento', 
                'requerimientos.fecha_creacion',
                'requerimientos.fecha_finalizacion',
                'personas.nombre as nombre_contratista',
                'personas.primer_apellido',
                'personas.segundo_apellido',
                'personas.documento',
                'informes.fecha_carga'
                )
            ->where('informes.id_requerimiento', '=', ''.$request->id_requerimiento.'')->get();
        }elseif($request->id_tipo_requerimiento== 2){
            $respuestas_requerimientos = RespuestaRequerimiento::join('requerimientos', 'requerimientos.id_requerimiento', '=', 'respuestas_requerimientos.id_requerimiento')
            ->join('contratos', 'contratos.id_contrato', '=', 'respuestas_requerimientos.id_contrato')
            ->join('personas', 'personas.id_persona', '=', 'contratos.id_persona')
            ->select(
                'requerimientos.nombre as nombre_requerimiento', 
                'requerimientos.fecha_creacion',
                'requerimientos.fecha_finalizacion',
                'personas.nombre as nombre_contratista',
                'personas.primer_apellido',
                'personas.segundo_apellido',
                'personas.documento',
                'respuestas_requerimientos.fecha_carga'
                )
            ->where('respuestas_requerimientos.id_requerimiento', '=', ''.$request->id_requerimiento.'')->get();
        }
        if (count($respuestas_requerimientos) > 0) {
            return Excel::download(new RespuestaRequerimientoExport($respuestas_requerimientos), 'requerimiento.xlsx');
        }
        return redirect('/revision/requerimientos/detalles/'.$request->id_requerimiento.'')->withErrors('No se encontraron respuestas en este requerimiento.');
    }  

    /* public function update_state($id, $estado){
        $respuesta_requerimiento = RespuestaRequerimiento::find($id);
        if ($respuesta_requerimiento == null) {
            return back()->withErrors('La respuesta al requerimiento no existe');
        }
        try {
            $respuesta_requerimiento->update([
                'estado' => $estado
            ]);
            if ($estado == 0) {
                return back()->withSuccess('La respuesta fue desaprobada');
            }else{
                return back()->withSuccess('Se aprobo la respuesta');
            }
        } catch (Exception $e) {
            return back()->withSuccess('Ocurrio un error: '.$e->getMessage());
        }
    } */
}
