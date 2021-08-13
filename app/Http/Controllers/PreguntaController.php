<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Models\Formulario;
use App\Models\Obligacion;
use App\Models\Pregunta;

class PreguntaController extends Controller
{
    private function getFormulario($id){
        $formulario = Formulario::findOrFail($id);
        return $formulario;
    }

    public function view_list($id){
        $formulario = $this->getFormulario($id);
        return view('parametrizaciones.gestion_formularios.gestion_preguntas.listar_preguntas', compact('formulario'));
    }

    public function view_store($id){
        $formulario = $this->getFormulario($id);
        $obligaciones = Obligacion::all();
        return view('parametrizaciones.gestion_formularios.gestion_preguntas.añadir_preguntas', compact("formulario", "obligaciones")); 
    }

    public function list($id){
        $formulario = Formulario::findOrFail($id);
        $preguntas = Pregunta::select('preguntas.*')
                ->join('formulario_pregunta', 'formulario_pregunta.id_pregunta', '=', 'preguntas.id_pregunta')
                ->where('formulario_pregunta.id_formulario', '=', $formulario->id_formulario)->get();
        return DataTables::of($preguntas)
            ->editColumn('Opciones', function($pregunta){
                return '<a href="#" class="btn btn-versatile_reports">Editar</a>';;
            })
            ->rawColumns(['Opciones'])
            ->make(true);
    }

    public function save(Request $request){
        $formulario = Formulario::findOrFail($request->id_formulario);
        if($formulario == null) return redirect()->route('listar_formularios')->withErrors('No se encontro el formulario, por lo tanto no se pudieron asignar las preguntas.');
        try {
            foreach($request->informacion as $item){
                
            }
            for($i = 0; $i < count($request->informacion); $i++){
                var_dump($request->informacion[$i]);
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
