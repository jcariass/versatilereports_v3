<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class actividad_evidencia extends Model
{
    use HasFactory;
    protected $primaryKey = "id_actividad_evidencia";
    protected $table = "actividad_evidencia";
    protected $fillable = [
        'id_pregunta', 'id_informe', 'id_obligacion','respuesta_actividad', 'respuesta_evidencia'
    ];
}
