<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desplazamiento extends Model
{
    use HasFactory;

    protected $primaryKey = "id_desplazamiento";

    protected $fillable = [
        'numero_orden', 'lugar', 'fecha_inicio', 'fecha_fin', 'id_informe'
    ];
}
