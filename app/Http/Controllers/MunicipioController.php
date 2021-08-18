<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Municipio;

class MunicipioController extends Controller
{
    public function get(Request $request){
        if ($request->ajax()) {
            $municipios = Municipio::where('id_departamento', '=', $request->id_departamento)->get();
            foreach($municipios as $mun){
                $municipiosArray[$mun->id_municipio] = $mun->nombre;
            }
            return response()->json($municipiosArray);
        }
        return redirect()->route('dashboard');
    }
}
