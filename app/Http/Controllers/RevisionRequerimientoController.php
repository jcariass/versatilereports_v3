<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use PDF;
use App\Exports\RespuestaRequerimientoExport;
use App\Models\actividad_evidencia;
use App\Models\Contrato;
use App\Models\Desplazamiento;
use App\Models\formulario_pregunta;
use App\Models\Informe;
use App\Models\Obligacion;
use App\Models\RespuestaRequerimiento;
use App\Models\Requerimiento;
use Illuminate\Support\Facades\DB;

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
            ->select('informes.*', 'personas.documento', 'personas.nombre', 'personas.primer_apellido', 'personas.segundo_apellido')
            ->where('id_requerimiento', '=', ''.$id.'')->get();
            return DataTables::of($informes)
                ->editColumn('nombre', function($informes){
                    return $informes->nombre . ' ' . $informes->primer_apellido . ' ' . $informes->segundo_apellido;
                })
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
                    $ver = '<a href="/revision/requerimientos/ver/informe/'.$informes->id_informe.'" class="btn btn-versatile_reports">Editar</a>';
                    $informe = '<a href="/revision/requerimientos/generar/informe/'.$informes->id_informe.'" class="btn btn-gris">Generar informe</a>';
                    $observacion = '<a href="/revision/requerimientos/agregar/observacion/'.$informes->id_informe.'/1" class="btn btn-info btn-estados">Observación</a>';
                    if ($informes->estado_uno == 0) {
                        $estado_uno = '<button type="button" class="btn btn-success btn-estados" onclick="confirm_dos('.$informes->id_informe.', 1)"><i class="ft-check"></i></button>';
                        // $estado_uno = '<a href="/revision/requerimientos/estado/uno/informe/'.$informes->id_informe.'/1" class="btn btn-estados btn-success"><i class="ft-check"></i></a>';
                    }else{
                        $estado_uno = '<button type="button" class="btn btn-danger btn-estados" onclick="confirm_dos('.$informes->id_informe.', 0)"><i class="ft-trash"></i></button>';
                        // $estado_uno = '<a href="/revision/requerimientos/estado/uno/informe/'.$informes->id_informe.'/0" class="btn btn-estados btn-danger"><i class="ft-trash"></i></a>';
                    }
                    if ($informes->estado_dos == 0) {
                        $estado_dos = '<button type="button" class="btn btn-success btn-estados" onclick="confirm_tres('.$informes->id_informe.', 1)"><i class="ft-check"></i></button>';
                        // $estado_dos = '<a href="/revision/requerimientos/estado/dos/informe/'.$informes->id_informe.'/1" class="btn btn-estados btn-success"><i class="ft-check"></i></a>';
                    }else{
                        $estado_dos = '<button type="button" class="btn btn-danger btn-estados" onclick="confirm_tres('.$informes->id_informe.', 0)"><i class="ft-trash"></i></button>';
                        // $estado_dos = '<a href="/revision/requerimientos/estado/dos/informe/'.$informes->id_informe.'/0" class="btn btn-estados btn-danger"><i class="ft-trash"></i></a>';
                    }
                    return $ver . ' ' . $informe. ' ' . $estado_uno . ' ' . $estado_dos . ' ' . $observacion;
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
                    $observacion = '<a href="/revision/requerimientos/agregar/observacion/'.$respuesta_requerimiento->id_respuesta_requerimiento.'/2" class="btn btn-info btn-estados">Observación</a>';
                    $opcion = '<a href="/revision/requerimientos/descargar/archivo/'.$respuesta_requerimiento->nombre.'" class="btn btn-versatile_reports"><i class="ft-download"></i></a>';
                    if ($respuesta_requerimiento->estado == 0)
                        $estado = '<button type="button" class="btn btn-success btn-estados" onclick="confirm('.$respuesta_requerimiento->id_respuesta_requerimiento.', 1)"><i class="ft-check"></i></button>';
                        // $estado = '<a href="/revision/requerimientos/estado/archivo/'.$respuesta_requerimiento->id_respuesta_requerimiento.'/1" class="btn btn-estados btn-success"><i class="ft-check"></i></a>';

                    else
                        $estado = '<button type="button" class="btn btn-danger btn-estados" onclick="confirm('.$respuesta_requerimiento->id_respuesta_requerimiento.', 0)"><i class="ft-trash"></i></button>';
                        // $estado = '<a href="/revision/requerimientos/estado/archivo/'.$respuesta_requerimiento->id_respuesta_requerimiento.'/0" class="btn btn-estados btn-danger"><i class="ft-trash"></i></a>';
                    return $opcion . ' ' . $estado . ' ' . $observacion;
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
        return redirect('/revision/requerimientos/detalles/'.$request->id_requerimiento.'')->withErrors('No se encontraron respuestas en este requerimiento');
    }  

    public function estado_archivo($id, $estado){
        $respuesta_requerimiento = RespuestaRequerimiento::findOrFail($id);
        if ($respuesta_requerimiento == null)
            return back()->withErrors('La respuesta al requerimiento no existe');
        else{
            try {
                $respuesta_requerimiento->update([
                    'estado' => $estado
                ]);
                if ($estado == 0) 
                    return back()->withSuccess('La respuesta fue desaprobada');
                else
                    return back()->withSuccess('Se aprobó la respuesta');
                
            } catch (Exception $e) {
                return back()->withSuccess('Ocurrió un error: '.$e->getMessage());
            }
        }
    }

    public function estado_uno_informe($id, $estado){
        $informe = Informe::findOrFail($id);
        if ($informe == null)
            return back()->withErrors('La respuesta al requerimiento no existe');
        else{
            try {
                $informe->update([
                    'estado_uno' => $estado
                ]);
                if ($estado == 0) 
                    return back()->withSuccess('Se desaprobó el estado uno del informe');
                else
                    return back()->withSuccess('Se aprobó el estado uno del informe');
                
            } catch (Exception $e) {
                return back()->withSuccess('Ocurrió un error: '.$e->getMessage());
            }
        }
    }

    public function estado_dos_informe($id, $estado){
        $informe = Informe::findOrFail($id);
        if ($informe == null)
            return back()->withErrors('La respuesta al requerimiento no existe');
        else{
            try {
                $informe->update([
                    'estado_dos' => $estado
                ]);
                if ($estado == 0) 
                    return back()->withSuccess('Se desaprobó el estado dos del informe');
                else
                    return back()->withSuccess('Se aprobó el estado dos del informe');
                
            } catch (Exception $e) {
                return back()->withSuccess('Ocurrió un error: '.$e->getMessage());
            }
        }
    }

    public function view_observacion($id, $tipo){
        if($tipo == 1){
            $informe = Informe::find($id);
            if($informe == null)
                return $this->getBack();
            else
                return view('revision_requerimientos.añadir_observacion', compact('informe'));
        }elseif($tipo == 2){
            $archivo = RespuestaRequerimiento::find($id);
            if($archivo == null)
                return $this->getBack();
            else
                return view('revision_requerimientos.añadir_observacion', compact('archivo'));
        }else
            return $this->getBack();
    }

    private function getBack(){
        return back()->withErrors('Ocurrió un error, intente de nuevo.');
    }

    public function guardar_observacion(Request $request){
        if($request->tipo_requerimiento == 1){
            $informe = Informe::find($request->id_respuesta);
            if($informe == null)
                return $this->getObservacion();
            else{
                $informe->update([
                    'observacion' => $request->observacion
                ]);
                return $this->getSuccess($request->id_requerimiento);
            }
        }elseif($request->tipo_requerimiento == 2){
            $archivo = RespuestaRequerimiento::find($request->id_respuesta);
            if($archivo == null)
                return $this->getObservacion();
            else{
                $archivo->update([
                    'observacion' => $request->observacion
                ]);
                return $this->getSuccess($request->id_requerimiento);
            }
        }else
            return $this->getObservacion();
    }

    private function getSuccess($id){
        return redirect()->route('view_detalles_requerimientos', ['id' => $id])->with('success', 'Se agrego la observación');
    }

    private function getObservacion(){
        return redirect()->route('listar_rev_requerimientos')->withErrors('No se pudo agregar la observación');
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
            $desplazamientos = Desplazamiento::select('*')->where('id_informe', '=', $informe->id_informe)->get();
            setlocale(LC_TIME, "spanish");
            $fecha_generacion = strftime("%d de %B de %Y");
            $pdf = PDF::loadView('revision_requerimientos.generar_informe', compact("informe", "obligaciones", "informacion", "fecha_generacion", "desplazamientos"));
            return $pdf->stream('archivo.pdf');
        }
    }

    public static function getRespuesta($id_informe, $id_obligacion){
        $respuestas = actividad_evidencia::select('*')
            ->where('id_informe', '=', $id_informe)
            ->where('id_obligacion', '=', $id_obligacion)
            ->get();
        return $respuestas;
    }

    public function view_ver_informe($id){
        $informe = Informe::findOrFail($id);
        $requerimiento = Requerimiento::findOrFail($informe->id_requerimiento);
        $obligaciones = Obligacion::select('id_obligacion', 'detalle')
                ->where('fecha_vencimiento', '>', Carbon::now()->toDateString())
                ->where('id_proceso', '=', $requerimiento->id_proceso)->get();
        $desplazamientos = Desplazamiento::select('*')
                ->where('id_informe', '=', $informe->id_informe)->get();
        return view('revision_requerimientos.editar_informe', compact('informe', 'requerimiento', 'obligaciones', 'desplazamientos'));
    }

    public static function preguntas_informe($id_obligacion, $id_formulario){
        $preguntas = formulario_pregunta::join('preguntas', 'preguntas.id_pregunta', '=', 'formulario_pregunta.id_pregunta')
            ->select('preguntas.*')->where('formulario_pregunta.id_obligacion', '=', $id_obligacion)
            ->where('formulario_pregunta.id_formulario', '=', $id_formulario)
            ->where('preguntas.estado', '=', 1)->get()->toArray();
        return $preguntas;
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
        $desplazamientos = Desplazamiento::select('*')->where('id_informe', '=', $informe->id_informe)->get();
        if($informe == null)
            return redirect()->route('listar_rev_requerimientos')->withErrors('No se pudo actualizar el informe.');
        else{
            try {
                DB::beginTransaction();
                if(count($preguntas) > 0){
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
                }
                if(count($desplazamientos) > 0){
                    foreach($desplazamientos as $desplazamiento){
                        $desplazamiento->delete();
                    }
                }
                if(isset($request->numeros_orden)){
                    if(count($request->numeros_orden) > 0){
                        foreach ($request->numeros_orden as $key => $value) {
                            Desplazamiento::create([
                                'numero_orden' => $value,
                                'lugar' => $request->lugares[$key],
                                'fecha_inicio' => $request->fechas_inicio[$key],
                                'fecha_fin' => $request->fechas_fin[$key],
                                'id_informe' => $informe->id_informe
                            ]);
                        }
                    }
                }
                DB::commit();
                return redirect()->route('view_detalles_requerimientos', ['id' => $informe->id_requerimiento])->with('success', 'Se actualizaron las respuestas del informe.');
            } catch (Exception $e) {
                DB::rollBack();
                return redirect()->route('view_detalles_requerimientos', ['id' => $informe->id_requerimiento])->withErrors('Ocurrio un error: '.$e->getMessage());
            }
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
}
