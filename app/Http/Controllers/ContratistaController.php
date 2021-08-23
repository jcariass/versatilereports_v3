<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ContratistaExport;
use App\Models\Persona;

class ContratistaController extends Controller
{
    /* Gestion Contratistas */
    public function view_list(){
        return view('gestion_contratistas.listar_contratistas');
    }

    public function list(){
        if(request()->ajax()){
            $contratistas = Persona::join('usuarios', 'usuarios.id_persona', '=', 'personas.id_persona')
                    ->select('personas.*')->where('usuarios.id_rol', '=', '3')->get();
    
            return DataTables::of($contratistas)
                ->editColumn('estado', function($contratista){
                    if ($contratista->estado == 1) {
                        return '<div style="padding: 6px; font-size: 13px;" class="badge badge-success">Activo</div>';
                    }
                    return '<div style="padding: 6px; font-size: 13px;" class="badge badge-danger">Inactivo</div>';
                })
                ->addColumn('Opciones', function ($contratista) {
                    return '<a href="/contratistas/contratos/'.$contratista->id_persona.'" class="btn btn-versatile_reports">Ver contratos</a>';
                })
                ->rawColumns(['Opciones', 'estado'])
                ->make(true);
        }
        return redirect()->route('dashboard');
    }

    public function view_reporte(){
        return view('gestion_contratistas.reportes.crear_reporte');
    }

    public function generar_excel(Request $request){
        $contratistas = Persona::join('municipios', 'municipios.id_municipio', '=', 'personas.id_municipio')
            ->join('departamentos', 'municipios.id_departamento', '=', 'departamentos.id_departamento')
            ->join('contratos', 'contratos.id_persona', '=', 'personas.id_persona')
            ->join('objetos', 'objetos.id_objeto', '=', 'contratos.id_objeto')
            ->join('procesos', 'procesos.id_proceso', '=', 'contratos.id_proceso')
            ->select(
                    'personas.*', 
                    'municipios.nombre as nombre_municipio',
                    'contratos.numero_contrato',
                    'contratos.forma_pago',
                    'contratos.fecha_inicio',
                    'contratos.fecha_fin',
                    'contratos.valor',
                    'objetos.nombre as nombre_objeto',
                    'procesos.nombre as nombre_proceso'
                )
            ->where('contratos.estado', '=', '1')
            ->whereBetween($request->criterio, [$request->fecha_inicio, $request->fecha_fin])->get();
        if (count($contratistas) > 0) {
            $contratistas = new ContratistaExport($contratistas);
            $fecha_reporte = Carbon::now()->toDateTimeString();
            return Excel::download($contratistas, 'reporte_contratistas_'.$fecha_reporte.'.xlsx');
        }else{
            return redirect()->route('listar_contratistas')->withErrors('No se encontraron contratistas');
        }
    }
}
