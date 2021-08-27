<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInformesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('informes', function (Blueprint $table) {
            $table->id('id_informe');
            $table->dateTime('fecha_carga');
            $table->string('numero_planilla')->nullable();
            $table->boolean('estado_uno')->default(0);
            $table->boolean('estado_dos')->default(0);
            $table->text('observacion', 2000)->nullable();
            $table->foreignId('id_contrato')->references('id_contrato')->on('contratos')->onUpdate('Cascade');
            $table->foreignId('id_requerimiento')->references('id_requerimiento')->on('requerimientos')->onUpdate('Cascade');
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
        Schema::dropIfExists('informes');
    }
}
