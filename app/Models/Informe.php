<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Informe extends Model
{
    use HasFactory;
    protected $primaryKey = "id_informe";
    
    protected $fillable = [
        'fecha_carga', 'id_contrato', 'id_requerimiento', 'numero_planilla',
        'estado_uno', 'estado_dos', 'observacion'
    ];
}
