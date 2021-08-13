<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Models\Formulario;
use Exception;

class FormularioController extends Controller
{
    public function view_list(){
        return view('parametrizaciones.gestion_formularios.listar_formularios');
    }

    public function view_create(){
        return view('parametrizaciones.gestion_formularios.crear_formulario');
    }

    public function view_edit($id){
        $formulario = Formulario::findOrFail($id);
        return view('parametrizaciones.gestion_formularios.editar_formulario', compact("formulario"));
    }

    public function list(){
        if(!request()->ajax()){
            // abort('403', 'NO AUTORIZADO');
            return redirect()->route('dashboard');
        }

        $formularios = Formulario::all();
        return DataTables::of($formularios)
                ->editColumn('Opciones', function($formulario){
                    $btn_editar = '<a href="/formularios/editar/'.$formulario->id_formulario.'" class="btn btn-versatile_reports">Editar</a>';
                    $btn_ver = '<a href="/formularios/preguntas/'.$formulario->id_formulario.'" class="btn btn-gris">Ver</a>';
                    return $btn_editar . ' ' . $btn_ver;
                })
                ->rawColumns(['Opciones'])
                ->make(true);
    }

    public function save(Request $request){
        $request->validate([
            'nombre' => 'required|string|min:3|max:100'
        ]);
        try {
            Formulario::create([
                'nombre' => $request->nombre
            ]);
            return redirect()->route('listar_formularios')->with("success", "Se creo con éxito");
        } catch (Exception $e) {
            return redirect()->route('listar_formularios')->withErrors('Ocurrio un error inesperado: '.$e->getMessage());
        }  
    }

    public function update(Request $request){
        $request->validate([
            'nombre' => 'required|string|min:3|max:100'
        ]);
        $formulario = Formulario::findOrFail($request->id_formulario);
        try {
            $formulario->update([
                'nombre' => $request->nombre
            ]);
            return redirect()->route('listar_formularios')->with("success", "Se modifico con éxito");
        } catch (Exception $e) {
            return redirect()->route('listar_formularios')->withErrors('Ocurrio un error inesperado: '.$e->getMessage());
        }  
    }
}
