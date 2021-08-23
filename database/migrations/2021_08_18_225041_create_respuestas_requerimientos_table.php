<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRespuestasRequerimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('respuestas_requerimientos', function (Blueprint $table) {
            $table->id('id_respuesta_requerimiento');
            $table->string('nombre');
            $table->datetime('fecha_carga');
            $table->boolean('estado')->default(0);
            $table->string('observacion')->nullable();
            $table->foreignId('id_requerimiento')->references('id_requerimiento')->on('requerimientos')->onUpdate('cascade');
            $table->foreignId('id_contrato')->references('id_contrato')->on('contratos')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('respuestas_requerimientos');
    }
}
