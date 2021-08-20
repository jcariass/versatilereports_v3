<?php

namespace App\Http\Controllers;

use App\Models\Parrafo;
use Illuminate\Http\Request;
use DataTables;
use App\Models\Plantilla;
use App\Models\Proceso;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class PlantillaController extends Controller
{
    public function view_list(){
        return view('parametrizaciones.gestion_plantillas.listar_plantillas');
    }

    public function view_create(){
        $procesos = Proceso::all();
        return view('parametrizaciones.gestion_plantillas.crear_plantilla', compact("procesos"));
    }

    public function view_edit($id){
        $plantilla = Plantilla::findOrFail($id);
        $procesos = Proceso::all();
        return view('parametrizaciones.gestion_plantillas.editar_plantilla', compact("plantilla", "procesos"));
    }

    public function list(){
        if(request()->ajax()){
            $plantillas = Plantilla::join('procesos', 'procesos.id_proceso', '=', 'plantillas.id_proceso')
                ->select('plantillas.*', 'procesos.nombre as nombre_proceso')->get();
            return DataTables::of($plantillas)
                ->editColumn('Opciones', function($plantilla){
                    $btn_editar = '<a href="/plantillas/editar/'.$plantilla->id_plantilla.'" class="btn btn-versatile_reports">Editar</a>';
                    $btn_ver = '<a href="/plantillas/parrafos/'.$plantilla->id_plantilla.'" class="btn btn-gris">Ver</a>';
                    $btn_duplicar = '<a href="/plantillas/duplicar/'.$plantilla->id_plantilla.'" class="btn btn-secondary btn-estados">Duplicar</a>';
                    return $btn_editar . ' ' . $btn_ver . ' ' . $btn_duplicar;
                })
                ->rawColumns(['Opciones'])
                ->make(true);
        }
        return redirect()->route('dashboard');
    }

    public function save(Request $request){
        $this->validations($request);   

        try {
            $fecha_creacion = Carbon::now()->toDateString();
            Plantilla::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'fecha_creacion' => $fecha_creacion,
                'fecha_finalizacion' => $request->fecha_finalizacion,
                'ciudad' => $request->ciudad,
                'id_proceso' => $request->id_proceso
            ]);
            return redirect()->route('listar_plantillas')->with('success', 'Se creo la plantilla');
        } catch (Exception $e) {
            return redirect()->route('listar_plantillas')->withErrors('Ocurrio un error: '.$e->getMessage());
        }
    }

    public function update(Request $request){
        $this->validations($request);
        $plantilla = Plantilla::findOrFail($request->id_plantilla);
        if ($plantilla == null) return redirect()->route('listar_plantillas')->withErrors('No se encontro la plantilla');

        try {
            $plantilla->update([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'fecha_finalizacion' => $request->fecha_finalizacion,
                'ciudad' => $request->ciudad,
                'id_proceso' => $request->id_proceso
            ]);
            return redirect()->route('listar_plantillas')->with('success', 'Se modifico la plantilla');
        } catch (Exception $e) {
            return redirect()->route('listar_plantillas')->withErrors('Ocurrio un error: '.$e->getMessage());
        }
    }

    public function duplicar($id){
        $plantilla = Plantilla::findOrFail($id);
        if($plantilla == null) return redirect()->route('listar_plantillas')->withErrors('No se encontro la plantilla');
        $parrafos = Parrafo::where('id_plantilla', '=', $plantilla->id_plantilla)->get();
        try {
            DB::beginTransaction();
            $fecha_actual = Carbon::now()->toDateString();
            $plantilla_nueva = Plantilla::create([
                'nombre' => $plantilla->nombre,
                'descripcion' => $plantilla->descripcion,
                'fecha_creacion' => $fecha_actual,
                'fecha_finalizacion' => $plantilla->fecha_finalizacion,
                'ciudad' => $plantilla->ciudad,
                'id_proceso' => $plantilla->id_proceso
            ]);

            if (count($parrafos) > 0) {
                foreach($parrafos as $parrafo){
                    Parrafo::create([
                        'texto' => $parrafo->texto,
                        'numero_parrafo' => $parrafo->numero_parrafo,
                        'id_plantilla' => $plantilla_nueva->id_plantilla
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('listar_plantillas')->with('success', 'Se duplico con éxito la plantilla');
        } catch (Exception $th) {
            DB::rollBack();
            return redirect()->route('listar_plantillas')->withErrors('Ocurrio un error: '.$e->getMessage());
        }
    }

    private function validations(Request $request){
        $request->validate([
            'nombre' => 'required|string|min:3|max:30',
            'descripcion' => 'required|min:20|max:800',
            'fecha_finalizacion' => 'required|date',
            'ciudad' => 'required|string|min:3|max:50',
            'id_proceso' => 'required|exists:procesos,id_proceso'
        ]);
    }
}
