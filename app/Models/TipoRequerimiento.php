<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoRequerimiento extends Model
{
    use HasFactory;
    protected $table = "tipos_requerimientos";
    protected $primaryKey = "id_tipo_requerimiento";
    protected $fillable = [
        'nombre'
    ];
    public $timestamps = false;
}
