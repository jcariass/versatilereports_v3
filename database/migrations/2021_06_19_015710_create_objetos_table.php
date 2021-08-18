<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB; //Eliminar solo para pruebas
use Illuminate\Support\Facades\Schema;

class CreateObjetosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('objetos', function (Blueprint $table) {
            $table->id('id_objeto');
            $table->string('nombre', 50);
            $table->text('detalle', 700);
            $table->timestamps();
        });

        DB::table('objetos')->insert([
            'nombre' => 'objeto de prueba',
            'detalle' => 'dsadadadjasdoiajsodiajidsoasjodiajod'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('objetos');
    }
}
