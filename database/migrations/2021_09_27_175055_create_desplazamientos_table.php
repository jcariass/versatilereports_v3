<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDesplazamientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('desplazamientos', function (Blueprint $table) {
            $table->id('id_desplazamiento');
            $table->string('numero_orden', 100);
            $table->string('lugar', 100);
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
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
        Schema::dropIfExists('desplazamientos');
    }
}
