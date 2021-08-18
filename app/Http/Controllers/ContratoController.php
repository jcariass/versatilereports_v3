<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use Exception;
use App\Models\Centro;
use App\Models\Contrato;
use App\Models\Departamento;
use App\Models\Municipio;
use App\Models\Objeto;
use App\Models\Persona;
use App\Models\Proceso;
use App\Models\Supervisor;

class ContratoController extends Controller
{
    public function view_list($id){
        $persona = Persona::findOrFail($id);
        return view('gestion_contratistas.gestion_contratos.listar_contratos', compact("persona"));
    }

    public function view_create($id){
        $persona = Persona::findOrFail($id);
        $procesos = Proceso::all();
        $objetos = Objeto::all();
        $supervisores = Supervisor::join('personas', 'personas.id_persona', '=', 'supervisores.id_persona')
                ->select('personas.*', 'supervisores.id_supervisor')
                ->where('supervisores.estado', '=', '1')->get();
        $centros = Centro::all();
        $departamentos = Departamento::all();
        return view('gestion_contratistas.gestion_contratos.crear_contratos', compact("persona", "procesos", "objetos", "supervisores", "centros", "departamentos"));
    }

    public function view_edit($id){
        $procesos = Proceso::all();
        $objetos = Objeto::all();
        $supervisores = Supervisor::join('personas', 'personas.id_persona', '=', 'supervisores.id_persona')
                ->select('personas.*', 'supervisores.id_supervisor')
                ->where('supervisores.estado', '=', '1')->get();
        $centros = Centro::all();
        $departamentos = Departamento::all();
        $municipios = Municipio::all();
        $contrato = Contrato::join('municipios', 'municipios.id_municipio', '=', 'contratos.id_municipio')
            ->select('contratos.*', 'municipios.id_departamento')
            ->where('contratos.id_contrato', '=', $id)->first();
        $persona = Persona::where('id_persona', '=', $contrato->id_persona)->first();
        return view('gestion_contratistas.gestion_contratos.editar_contratos', compact("persona", "contrato","procesos", "objetos", "supervisores", "centros", "municipios", "departamentos"));
    }

    public function view_more($id){
        $contrato = Contrato::join('personas', 'personas.id_persona', '=', 'contratos.id_persona')
        ->join('objetos', 'objetos.id_objeto', '=', 'contratos.id_objeto')
        ->join('supervisores', 'supervisores.id_supervisor', '=', 'contratos.id_supervisor')
        ->join('personas as personas_supervisores', 'supervisores.id_persona', '=', 'personas_supervisores.id_persona')
        ->join('procesos', 'procesos.id_proceso', '=', 'contratos.id_proceso')
        ->join('centros', 'centros.id_centro', '=', 'contratos.id_centro')
        ->join('municipios', 'municipios.id_municipio', '=', 'contratos.id_municipio')
        ->join('departamentos', 'departamentos.id_departamento', '=', 'municipios.id_departamento')
        ->select(
            'contratos.*', 
            'personas.nombre as nombre_persona', 
            'personas.primer_apellido as primer_apellido_persona',
            'personas.segundo_apellido as segundo_apellido_persona',
            'personas.documento as documento_persona',
            'personas.tipo_documento as tipo_documento_persona',
            'objetos.nombre as nombre_objeto',  
            'personas_supervisores.nombre as nombre_supervisor',
            'personas_supervisores.primer_apellido as primer_apellido_supervisor',
            'personas_supervisores.segundo_apellido as segundo_apellido_supervisor',
            'procesos.nombre as nombre_proceso',
            'centros.nombre as nombre_centro',
            'municipios.nombre as nombre_municipio',
            'departamentos.nombre as nombre_departamento'
        )->where('contratos.id_contrato', '=', ''.$id.'')->first();
        return view('gestion_contratistas.gestion_contratos.ver_contrato', compact("contrato"));
    }

    public function list($id){
        if(request()->ajax()){
            $contratos = Contrato::join('personas', 'personas.id_persona', '=', 'contratos.id_persona')
            ->select(
                'contratos.numero_contrato', 'contratos.fecha_inicio',
                'contratos.fecha_fin', 'contratos.valor', 'contratos.estado',
                'contratos.id_contrato'
                )
            ->where('contratos.id_persona', '=', ''.$id.'')->get();
            return DataTables::of($contratos)
                ->editColumn('fecha_fin', function($contrato){
                    return date("d-m-Y", strtotime($contrato->fecha_fin));
                })
                ->editColumn('fecha_inicio', function($contrato){
                    return date("d-m-Y", strtotime($contrato->fecha_inicio));
                })
                ->editColumn('estado', function($contrato){
                    if ($contrato->estado == 0) {
                        return '<div style="padding: 6px; font-size: 13px;" class="badge badge-warning">Contrato sin asignar</div>';
                    }elseif ($contrato->estado == 1){
                        return '<div style="padding: 6px; font-size: 13px;" class="badge badge-success">Contrato asignado</div>';
                    }
                    return '<div style="padding: 6px; font-size: 13px;" class="badge badge-danger">Contrato vencido</div>';
                })
                ->addColumn('Opciones', function ($contrato) {
                    if ($contrato->estado == 0) {
                        $btn_detalles = '<a href="/contratistas/ver/contrato/'.$contrato->id_contrato.'" class="btn btn-gris">Ver</a>';
                        $btn_editar = '<a href="/contratistas/contratos/editar/'.$contrato->id_contrato.'" class="btn btn-versatile_reports">Editar</a>';
                        $btn_estado = '<a href="/contratistas/contratos/cambiar/estado/'.$contrato->id_contrato.'/1" class="btn btn-success btn-estados">Asignar</a>';
                        return $btn_editar . ' ' . $btn_detalles . ' ' . $btn_estado;
                    }elseif($contrato->estado == 1){
                        $btn_detalles = '<a href="/contratistas/ver/contrato/'.$contrato->id_contrato.'" class="btn btn-gris">Ver</a>';
                        $btn_estado = '<a href="/contratistas/contratos/cambiar/estado/'.$contrato->id_contrato.'/2" class="btn btn-danger btn-estados">Finalizar</a>';
                        return $btn_detalles . ' ' . $btn_estado;
                    }else{
                        $btn_detalles = '<a href="/contratistas/ver/contrato/'.$contrato->id_contrato.'" class="btn btn-gris">Ver</a>';
                        return $btn_detalles;
                    }
                })
                ->rawColumns(['Opciones', 'estado', 'fecha_inicio', 'fecha_fin'])
                ->make(true);
        }
        return redirect()->route('dashboard');
    }

    public function save(Request $request){
        $request->validate([
            'numero_contrato' => 'required|string|max:200|unique:contratos,numero_contrato',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date',
            'valor_contrato' => 'required|numeric',
            'forma_pago_contrato' => 'required|string|max:2000',
            'id_persona' => 'required|exists:personas,id_persona',
            'id_proceso' => 'required|exists:procesos,id_proceso',
            'id_objeto' => 'required|exists:objetos,id_objeto',
            'id_supervisor' => 'required|exists:supervisores,id_supervisor',
            'id_centro' => 'required|exists:centros,id_centro',
            'id_municipio' => 'required|exists:municipios,id_municipio'
        ]);
        if($request->fecha_inicio >= $request->fecha_fin){
            return back()->withErrors('La fecha de inicio debe ser mayor a la fecha de finalizacion');
        }
        try {
            $contrato = Contrato::create([
                'numero_contrato' => $request->numero_contrato,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'valor' => $request->valor_contrato,
                'forma_pago' => $request->forma_pago_contrato,
                'id_persona' => $request->id_persona,
                'id_proceso' => $request->id_proceso,
                'id_objeto' => $request->id_objeto,
                'id_supervisor' => $request->id_supervisor,
                'id_centro' => $request->id_centro,
                'id_municipio' => $request->id_municipio
            ]);

            return redirect('/contratistas/contratos/'.$contrato->id_persona.'')->withSuccess('Se creo con éxito');
        } catch (Exception $e) {
            return redirect('/contratistas')->withErrors('Ocurrio un error. Error: '.$e->getMessage());
        }
    }

    public function update(Request $request){
        $request->validate([
            'numero_contrato' => 'required|string|max:30|unique:contratos,numero_contrato,'.$request->id_contrato.',id_contrato',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date',
            'valor_contrato' => 'required|numeric',
            'forma_pago_contrato' => 'required|string|max:2000',
            'id_proceso' => 'required|exists:procesos,id_proceso',
            'id_objeto' => 'required|exists:objetos,id_objeto',
            'id_supervisor' => 'required|exists:supervisores,id_supervisor',
            'id_centro' => 'required|exists:centros,id_centro',
            'id_municipio' => 'required|exists:municipios,id_municipio'
        ]);
        if($request->fecha_inicio >= $request->fecha_fin){
            return back()->withErrors('La fecha de inicio debe ser mayor a la fecha de finalizacion');
        }
        $contrato = Contrato::findOrFail($request->id_contrato);
        if($contrato == null) return redirect()->route('listar_contratos')->withErrors('El contrato no se pudo actualizar, no fue encontrado');
        try {
            $contrato->update([
                'numero_contrato' => $request->numero_contrato,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'valor' => $request->valor_contrato,
                'forma_pago' => $request->forma_pago_contrato,
                'id_proceso' => $request->id_proceso,
                'id_objeto' => $request->id_objeto,
                'id_supervisor' => $request->id_supervisor,
                'id_centro' => $request->id_centro,
                'id_municipio' => $request->id_municipio
            ]);
            
            return redirect('/contratistas/contratos/'.$contrato->id_persona.'')->withSuccess('Se modifico con éxito');
        } catch (Exception $e) {
            return redirect('/contratistas/contratos/'.$contrato->id_persona.'')->withErrors('Ocurrio un error. Error: '.$e->getMessage());
        }
    }

    public function state_update($id, $estado){
        $contrato = Contrato::findOrFail($id);
        if ($contrato == null) {
            return back()->withErrors('No se encontro el contrato');
        }
        try {
            if ($estado == 1) {
                $contrato_viejo = Contrato::select('*')->where('id_persona', '=', ''.$contrato->id_persona.'')->where('estado', '=', '1')->first();
                if ($contrato_viejo != null) {
                    $contrato_viejo->update([
                        'estado' => 2
                    ]);
                }
                $contrato->update([
                    'estado' => $estado
                ]);
                return back()->withSuccess('Contrato asignado');
            }elseif ($estado == 2) {
                $contrato->update([
                    'estado' => $estado
                ]);
                return back()->withSuccess('Contrato finalizado');
            }
        } catch (Exception $e) {
            return back()->withErrors('Ocurrio un error: '.$e->getMessage());
        }
    }
}
