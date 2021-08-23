<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespuestaRequerimiento extends Model
{
    use HasFactory;
    protected $table = "respuestas_requerimientos";
    protected $primaryKey = "id_respuesta_requerimiento";
    protected $fillable = [
        'nombre',
        'fecha_carga',
        'estado',
        'observacion',
        'id_requerimiento',
        'id_contrato'
    ];
}
