<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Mail;
use App\Models\Contrato;
use App\Models\Formulario;
use App\Models\Proceso;
use App\Models\Requerimiento;
use App\Models\TipoRequerimiento;

class RequerimimientoController extends Controller
{
    public function view_list(){
        return view('gestion_requerimientos.listar_requerimientos');
    }

    public function view_create(){
        $tipos_requerimientos = TipoRequerimiento::all();
        $procesos = Proceso::all();
        $formularios = Formulario::all();
        return view("gestion_requerimientos.crear_requerimiento", compact("tipos_requerimientos", "procesos", "formularios"));
    }

    public function view_edit($id){
        $requerimiento = Requerimiento::find($id);
        return view("gestion_requerimientos.editar_requerimiento", compact("requerimiento"));
    }

    public function list(){
        $requerimientos = Requerimiento::join('procesos', 'procesos.id_proceso', '=', 'requerimientos.id_proceso')
        ->join('tipos_requerimientos', 'tipos_requerimientos.id_tipo_requerimiento', '=', 'requerimientos.id_tipo_requerimiento')
        ->select('requerimientos.*', 'procesos.nombre as nombre_proceso', 'tipos_requerimientos.nombre as tipo_requerimiento')->get();
        return DataTables::of($requerimientos)
        ->editColumn('estado', function($requerimiento){
            if ($requerimiento->estado == 1) {
                return '<div class="badge badge-pill badge-success">Activo</div>';
            }
            else{
                return '<div class="badge badge-pill badge-danger">Inactivo</div>';
            }
        })
        ->addColumn('Opciones', function ($requerimiento) {
            $btn_editar = '<a href="/requerimientos/editar/'.$requerimiento->id_requerimiento.'" class="btn btn-versatile_reports">Editar</a>';
            if ($requerimiento->estado == 1) {
                $btn_estado = '<a href="/requerimientos/cambiar/estado/'.$requerimiento->id_requerimiento.'/0" class="btn btn-danger btn-estados">Inactivar</a>';
            }else{
                $btn_estado = '<a href="/requerimientos/cambiar/estado/'.$requerimiento->id_requerimiento.'/1" class="btn btn-success btn-estados">Activar</a>';
            }
            return $btn_editar . ' ' . $btn_estado;
        })
        ->rawColumns(['Opciones', 'estado'])
        ->make(true);
    }

    public function save(Request $request){
        $this->validations($request);
        try {
            $fecha_creacion = Carbon::now()->toDateString();
            if($fecha_creacion > $request->fecha_finalizacion){
                return redirect()->route('listar_requerimientos')->withErrors('No se pudo crear el requerimiento, la fecha de finalizacion introducida era menor a la fecha actual, intenta de nuevo.');
            }
            $requerimiento = Requerimiento::create([
                'nombre' => $request->nombre,
                'detalle' => $request->detalle,
                'fecha_creacion' => $fecha_creacion,
                'fecha_finalizacion' => $request->fecha_finalizacion,
                'id_proceso' => $request->id_proceso,
                'id_tipo_requerimiento' => $request->id_tipo_requerimiento
            ]);
            if (isset($request->id_formulario)) {
                $requerimiento->update([
                    'id_formulario' => $request->id_formulario
                ]);
            }
            $input = $request->all();
            $personas = Contrato::join('personas', 'personas.id_persona', '=', 'contratos.id_persona')
                ->select('personas.correo')
                ->where('contratos.id_proceso', '=', ''.$requerimiento->id_proceso.'')
                ->where('contratos.estado', '=', '1')->get();
            Mail::send('gestion_requerimientos.email.notificar_contratistas', compact("input"), function ($mail) use ($personas){
                foreach ($personas as $item) {
                    $mail->to($item->correo);
                }
            });
            return redirect()->route('listar_requerimientos')->withSuccess('Se creo con exito');
        } catch (Exception $e) {
            return redirect()->route('listar_requerimientos')->withErrors('Ocurrio un error: '.$e->getMessage());
        }
    }

    public function update(Request $request){
        $request->validate([
            'nombre' => 'required|string|min:3|max:100',
            'detalle' => 'required|string|min:3|max:255',
            'fecha_finalizacion' => 'required|date'
        ]);
        try {
            $requerimiento = Requerimiento::find($request->id_requerimiento);
            if($requerimiento->fecha_creacion > $request->fecha_finalizacion){
                return redirect()->route('listar_requerimientos')->withErrors('No se pudo crear el requerimiento, la fecha de finalizacion introducida era menor a la fecha actual, intenta de nuevo.');
            }
            $requerimiento->update([
                'nombre' => $request->nombre,
                'detalle' => $request->detalle,
                'fecha_finalizacion' => $request->fecha_finalizacion,
            ]);
            return redirect()->route('listar_requerimientos')->withSuccess('Se modifico con exito');
        } catch (Exception $e) {
            return redirect()->route('listar_requerimientos')->withErrors('Ocurrio un error: '.$e->getMessage());
        }
    }

    public function state_update($id, $estado){
        $requerimiento = Requerimiento::findOrFail($id);
        if ($requerimiento == null) {
            return redirect()->route('listar_requerimientos')->withErrors('No se encontro el requerimiento');
        }
        try {
            $requerimiento->update([
                'estado' => $estado
            ]);
            return redirect()->route('listar_requerimientos')->with('success', 'Se cambio el estado con éxito');
        } catch (Exception $e) {
            return redirect()->route('listar_requerimientos')->withErrors('Ocurrio un error: '.$e->getMessage());
        }
    }

    private function validations(Request $request){
        if (isset($request->id_formulario)) {
            $request->validate([
                'nombre' => 'required|string|min:3|max:100',
                'detalle' => 'required|string|min:3|max:255',
                'fecha_finalizacion' => 'required|date',
                'id_proceso' => 'required|numeric|exists:procesos,id_proceso',
                'id_tipo_requerimiento' => 'required|numeric|exists:tipos_requerimientos,id_tipo_requerimiento',
                'id_formulario' => 'required|numeric|exists:formularios,id_formulario'
            ]);
        }else{
            $request->validate([
                'nombre' => 'required|string|min:3|max:100',
                'detalle' => 'required|string|min:3|max:255',
                'fecha_finalizacion' => 'required|date',
                'id_proceso' => 'required|numeric|exists:procesos,id_proceso',
                'id_tipo_requerimiento' => 'required|numeric|exists:tipos_requerimientos,id_tipo_requerimiento'
            ]);
        }
    }
}
