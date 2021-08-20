<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateRequerimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requerimientos', function (Blueprint $table) {
            $table->id('id_requerimiento');
            $table->string('nombre', 100);
            $table->text('detalle');
            $table->date('fecha_creacion');
            $table->date('fecha_finalizacion');
            $table->boolean('estado')->default(1);
            $table->foreignId('id_proceso')->references('id_proceso')->on('procesos')->onUpdate('cascade');
            $table->foreignId('id_tipo_requerimiento')->references('id_tipo_requerimiento')->on('tipos_requerimientos')->onUpdate('cascade');
            $table->foreignId('id_formulario')->nullable()->references('id_formulario')->on('formularios')->onUpdate('cascade');
        });

        DB::table('requerimientos')->insert([
            'nombre' => 'Requerimiento de prueba 2',
            'detalle' => 'Requerimiento de prueba XD 2 XD 2',
            'fecha_creacion' => '2021-10-10',
            'fecha_finalizacion' => '2030-10-10',
            'id_proceso' => 1,
            'id_tipo_requerimiento' => 1,
            'id_formulario' => 1
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requerimientos');
    }
}
