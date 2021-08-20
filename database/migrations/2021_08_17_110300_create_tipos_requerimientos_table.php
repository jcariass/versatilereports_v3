<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTiposRequerimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipos_requerimientos', function (Blueprint $table) {
            $table->id('id_tipo_requerimiento');
            $table->string('nombre', 30);
        });

        DB::table('tipos_requerimientos')->insert([
            ['nombre' => 'Informe ejecucción contractual'],
            ['nombre' => 'Carga de archivo']
        ]); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipos_requerimientos');
    }
}
