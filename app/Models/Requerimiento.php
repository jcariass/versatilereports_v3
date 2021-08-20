<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requerimiento extends Model
{
    use HasFactory;
    protected $primaryKey = "id_requerimiento";
    protected $fillable = [
        'nombre',
        'detalle',
        'fecha_creacion',
        'fecha_finalizacion',
        'estado',
        'id_proceso',
        'id_tipo_requerimiento',
        'id_formulario'
    ];
    public $timestamps = false;
}
