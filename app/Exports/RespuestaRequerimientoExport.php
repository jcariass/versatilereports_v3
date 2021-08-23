<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RespuestaRequerimientoExport implements FromView
{
    protected $respuestas_requerimientos;

    public function __construct($respuestas_requerimientos)
    {
        $this->respuestas_requerimientos = $respuestas_requerimientos;
    }

    public function view(): View
    {
        return view('revision_requerimientos.reporte.excel', [
            'respuestas_requerimientos' => $this->respuestas_requerimientos
        ]);
    }
}