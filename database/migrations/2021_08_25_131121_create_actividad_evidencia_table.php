<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActividadEvidenciaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actividad_evidencia', function (Blueprint $table) {
            $table->id('id_actividad_evidencia');
            $table->text('respuesta_actividad', 2000);
            $table->text('respuesta_evidencia', 2000);
            $table->foreignId('id_obligacion')->references('id_obligacion')->on('obligaciones')->onUpdate('cascade');
            $table->foreignId('id_informe')->references('id_informe')->on('informes')->onUpdate('cascade');
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
        Schema::dropIfExists('actividad_evidencia');
    }
}
