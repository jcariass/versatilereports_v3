-<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePermisoRolTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permiso_rol', function (Blueprint $table) {
            $table->id('id_permiso_rol');
            $table->foreignId('id_permiso')->references('id_permiso')->on('permisos');
            $table->foreignId('id_rol')->references('id_rol')->on('roles');
        });

        DB::table('permiso_rol')->insert([
            //Inicio rol administrador -- Permisos
            ['id_permiso' => 1, 'id_rol' => 1],
            ['id_permiso' => 2, 'id_rol' => 1],
            ['id_permiso' => 3, 'id_rol' => 1],
            ['id_permiso' => 4, 'id_rol' => 1],
            ['id_permiso' => 5, 'id_rol' => 1],
            ['id_permiso' => 6, 'id_rol' => 1],
            ['id_permiso' => 7, 'id_rol' => 1],
            ['id_permiso' => 8, 'id_rol' => 1],
            ['id_permiso' => 9, 'id_rol' => 1],
            ['id_permiso' => 10, 'id_rol' => 1],
            ['id_permiso' => 11, 'id_rol' => 1],
            ['id_permiso' => 12, 'id_rol' => 1],
            ['id_permiso' => 13, 'id_rol' => 1],
            ['id_permiso' => 14, 'id_rol' => 1],
            ['id_permiso' => 15, 'id_rol' => 1],
            ['id_permiso' => 16, 'id_rol' => 1],
            ['id_permiso' => 17, 'id_rol' => 1],
            ['id_permiso' => 18, 'id_rol' => 1],
            ['id_permiso' => 19, 'id_rol' => 1],
            ['id_permiso' => 20, 'id_rol' => 1],
            ['id_permiso' => 21, 'id_rol' => 1],
            ['id_permiso' => 22, 'id_rol' => 1],
            ['id_permiso' => 23, 'id_rol' => 1],
            ['id_permiso' => 24, 'id_rol' => 1],
            ['id_permiso' => 25, 'id_rol' => 1],
            //Fin rol administrador -- Permisos

            //Inicio rol contratistas -- Permisos
            ['id_permiso' => 1, 'id_rol' => 3],
            ['id_permiso' => 2, 'id_rol' => 3],
            ['id_permiso' => 3, 'id_rol' => 3],
            //Fin rol contratistas -- Permisos
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permiso_rol');
    }
}
