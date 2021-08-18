<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Exception;
use DataTables;
use PDF;
use App\Exports\ContratistaExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Centro;
use App\Models\Contrato;
use App\Models\Contratista;
use App\Models\Departamento;
use App\Models\Municipio;
use App\Models\Objeto;
use App\Models\Persona;
use App\Models\Proceso;
use App\Models\Supervisor;
use App\Models\User;

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

    // public function view_reporte(){
    //     return view('modulos.gestion_contratistas.reporte_pdf.crear_reporte');
    // }

    // private function generar_excel($contratistas){
    //     $contratistas = new ContratistaExport($contratistas);
    //     return Excel::download($contratistas, 'contratistas.xlsx');
    // }
}
