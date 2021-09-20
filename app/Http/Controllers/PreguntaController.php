<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\Formulario;
use App\Models\formulario_pregunta;
use App\Models\Obligacion;
use App\Models\Pregunta;

class PreguntaController extends Controller
{
    private function getFormulario($id){
        $formulario = Formulario::findOrFail($id);
        return $formulario;
    }

    public function view_list_obligaciones($id){
        $formulario= $this->getFormulario($id);
        return view('parametrizaciones.gestion_formularios.gestion_preguntas.listar_obligaciones_preguntas', compact('formulario'));
    }

    public function list_obligaciones($id){
        $formulario = Formulario::findOrFail($id);
        $obligaciones = array();
        $id_obligaciones = array();
        $results = Obligacion::select('obligaciones.id_obligacion')
                    ->join('formulario_pregunta', 'formulario_pregunta.id_obligacion', '=', 'obligaciones.id_obligacion')
                    ->where('formulario_pregunta.id_formulario', '=', $formulario->id_formulario)->get();
        foreach ($results as $value) {
            if(!in_array($value['id_obligacion'], $id_obligaciones)){
                $obligacion = Obligacion::find($value['id_obligacion']);
                array_push($obligaciones, $obligacion);
                array_push($id_obligaciones, $value['id_obligacion']);
            }
        }
        return DataTables::of($obligaciones)
            ->editColumn('Opciones', function($obligacion) use ($formulario){
                return '<a href="/formularios/preguntas/'.$obligacion->id_obligacion.'/'.$formulario->id_formulario.'" class="btn btn-versatile_reports">Ver preguntas</a>'; 
            })
            ->rawColumns(['Opciones'])
            ->make(true);
    }

    public function view_list($id_obligacion, $id_formulario){
        $obligacion = Obligacion::findOrFail($id_obligacion);
        $formulario = $this->getFormulario($id_formulario);
        return view('parametrizaciones.gestion_formularios.gestion_preguntas.listar_preguntas', compact('obligacion', 'formulario'));
    }

    public function view_store($id){
        $formulario = $this->getFormulario($id);
        $obligaciones = Obligacion::all();
        return view('parametrizaciones.gestion_formularios.gestion_preguntas.añadir_preguntas', compact("formulario", "obligaciones")); 
    }

    public function view_edit($id){ 
        $pregunta = Pregunta::join('formulario_pregunta', 'formulario_pregunta.id_pregunta', '=', 'preguntas.id_pregunta')
                ->select('formulario_pregunta.id_formulario', 'formulario_pregunta.id_obligacion', 'preguntas.*')
                ->where('formulario_pregunta.id_pregunta', '=', $id)->get();
        $obligaciones = Obligacion::all();
        return view('parametrizaciones.gestion_formularios.gestion_preguntas.editar_pregunta', compact("pregunta", "obligaciones"));
    }   

    public function list($id_obligacion, $id_formulario){
        $obligacion = Obligacion::findOrFail($id_obligacion);
        $formulario = Formulario::findOrFail($id_formulario);
        if($formulario == null || $obligacion == null)
            $preguntas = array();
        else{
            $preguntas = Pregunta::select('preguntas.*')
                ->join('formulario_pregunta', 'formulario_pregunta.id_pregunta', '=', 'preguntas.id_pregunta')
                ->where('formulario_pregunta.id_obligacion', '=', $obligacion->id_obligacion)
                ->where('formulario_pregunta.id_formulario', '=', $formulario->id_formulario)
                ->where('preguntas.estado', '=', '1')->get();
        }
        return DataTables::of($preguntas)
            ->editColumn('Opciones', function($pregunta) use ($obligacion){
                $btn_eliminar = '<a href="/formularios/eliminar/pregunta/'.$pregunta->id_pregunta.'/'.$obligacion->id_obligacion.'" class="btn btn-gris">Eliminar</a>';
                $btn_editar = '<a href="/formularios/editar/pregunta/'.$pregunta->id_pregunta.'" class="btn btn-versatile_reports">Editar</a>';
                return $btn_editar . ' ' . $btn_eliminar; 
            })
            ->rawColumns(['Opciones'])
            ->make(true);
    }

    public function save(Request $request){
        $formulario = Formulario::findOrFail($request->id_formulario);
        if($formulario == null) 
            return redirect()->route('listar_formularios')->withErrors('No se encontro el formulario, por lo tanto no se pudieron asignar las preguntas.');
        else{
            if(count($request->identificaciones_obligacion) < 1 || count($request->preguntas_actividad) < 1 || count($request->preguntas_evidencia) < 1)
                return redirect()->route('listar_formularios')->withErrors('Ocurrio un error, intente de nuevo.');
            else{
                try {
                    DB::beginTransaction();
                    foreach($request->identificaciones_obligacion as $key => $value){
                        $pregunta = Pregunta::create([
                            'pregunta_actividad' => $request->preguntas_actividad[$key],
                            'pregunta_evidencia' => $request->preguntas_evidencia[$key]
                        ]);
                        formulario_pregunta::create([
                            'id_obligacion' => $value,
                            'id_pregunta' => $pregunta->id_pregunta,
                            'id_formulario' => $formulario->id_formulario
                        ]);
                    }
                    DB::commit();
                    return redirect()->route('obligaciones_formulario', ['id' => $formulario->id_formulario])->with('success', 'Se añadieron las preguntas.');
                } catch (Exception $e) {
                    DB::rollBack();
                    return redirect()->route('obligaciones_formulario', ['id' => $formulario->id_formulario])->withErrors('Ocurrio un error: '.$e->getMessage());
                }
            }
        }
    }

    public function update(Request $request){
        $pregunta = Pregunta::findOrFail($request->id_pregunta);
        $obligacion = Pregunta::join('formulario_pregunta', 'formulario_pregunta.id_pregunta', '=', 'preguntas.id_pregunta')
                ->select('formulario_pregunta.id_obligacion')
                ->where('formulario_pregunta.id_pregunta', '=', $pregunta->id_pregunta)->first();
        if ($pregunta == null) if($pregunta == null) return redirect()->route('preguntas_formulario', ['id' => $obligacion->id_obligacion])->withErrors('Ocurrio un error al eliminar la pregunta');
        try {
            $pregunta->update([
                'id_obligacion' => $request->id_obligacion,
                'pregunta_actividad' => $request->pregunta_actividad,
                'pregunta_evidencia' => $request->pregunta_evidencia
            ]);
            return redirect()->route('preguntas_formulario', ['id' => $obligacion->id_obligacion])->with('success', 'Se modifico con exito.');
        } catch (Exception $e) {
            return redirect()->route('listar_formularios')->withErrors('Ocurrio un error: '.$e->getMessage());
        }
    }

    public function state_update($id_pregunta, $id_obligacion){
        $obligacion = Obligacion::findOrFail($id_obligacion);
        if($obligacion == null) return redirect()->route('listar_formularios')->withErrors('Ocurrio un error al eliminar la pregunta');
        
        $pregunta = Pregunta::findOrFail($id_pregunta);
        if($pregunta == null) return redirect()->route('preguntas_formulario', ['id' => $obligacion->id_obligacion])->withErrors('Ocurrio un error al eliminar la pregunta');

        try {
            $pregunta->update([
                'estado' => '0'
            ]);
            return redirect()->route('preguntas_formulario', ['id' => $obligacion->id_obligacion])->with('success', 'Se elimino con exito.');
        } catch (Exception $e) {
            return redirect()->route('listar_formularios')->withErrors('Ocurrio un error: '.$e->getMessage());
        }
    }
}
