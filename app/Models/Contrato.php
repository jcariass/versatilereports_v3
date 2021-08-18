<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    use HasFactory;

    protected $primaryKey = "id_contrato";
    protected $fillable = [
        'numero_contrato',
        'fecha_inicio',
        'fecha_fin',
        'valor',
        'forma_pago',
        'estado',
        'id_persona',
        'id_proceso',
        'id_objeto',
        'id_supervisor',
        'id_centro',
        'id_municipio'
    ];
}
