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
            ['id_permiso' => 26, 'id_rol' => 1],
            ['id_permiso' => 27, 'id_rol' => 1],
            ['id_permiso' => 28, 'id_rol' => 1],
            ['id_permiso' => 29, 'id_rol' => 1],
            ['id_permiso' => 30, 'id_rol' => 1],
            ['id_permiso' => 31, 'id_rol' => 1],
            ['id_permiso' => 32, 'id_rol' => 1],
            ['id_permiso' => 33, 'id_rol' => 1],
            ['id_permiso' => 34, 'id_rol' => 1],
            ['id_permiso' => 35, 'id_rol' => 1],
            ['id_permiso' => 36, 'id_rol' => 1],
            ['id_permiso' => 37, 'id_rol' => 1],
            ['id_permiso' => 38, 'id_rol' => 1],
            ['id_permiso' => 39, 'id_rol' => 1],
            ['id_permiso' => 40, 'id_rol' => 1],
            ['id_permiso' => 41, 'id_rol' => 1],
            ['id_permiso' => 42, 'id_rol' => 1],
            ['id_permiso' => 43, 'id_rol' => 1],
            ['id_permiso' => 44, 'id_rol' => 1],
            ['id_permiso' => 45, 'id_rol' => 1],
            ['id_permiso' => 46, 'id_rol' => 1],
            ['id_permiso' => 47, 'id_rol' => 1],
            ['id_permiso' => 48, 'id_rol' => 1],
            ['id_permiso' => 49, 'id_rol' => 1],
            ['id_permiso' => 50, 'id_rol' => 1],
            ['id_permiso' => 51, 'id_rol' => 1],
            ['id_permiso' => 52, 'id_rol' => 1],
            ['id_permiso' => 53, 'id_rol' => 1],
            ['id_permiso' => 54, 'id_rol' => 1],
            ['id_permiso' => 55, 'id_rol' => 1],
            ['id_permiso' => 56, 'id_rol' => 1],
            ['id_permiso' => 57, 'id_rol' => 1],
            ['id_permiso' => 58, 'id_rol' => 1],
            ['id_permiso' => 59, 'id_rol' => 1],
            ['id_permiso' => 60, 'id_rol' => 1],
            ['id_permiso' => 61, 'id_rol' => 1],
            ['id_permiso' => 62, 'id_rol' => 1],
            ['id_permiso' => 63, 'id_rol' => 1],
            ['id_permiso' => 64, 'id_rol' => 1],
            ['id_permiso' => 65, 'id_rol' => 1],
            ['id_permiso' => 66, 'id_rol' => 1],
            ['id_permiso' => 67, 'id_rol' => 1],
            ['id_permiso' => 68, 'id_rol' => 1],
            ['id_permiso' => 69, 'id_rol' => 1],
            ['id_permiso' => 70, 'id_rol' => 1],
            ['id_permiso' => 71, 'id_rol' => 1],
            ['id_permiso' => 72, 'id_rol' => 1],
            ['id_permiso' => 73, 'id_rol' => 1],
            ['id_permiso' => 74, 'id_rol' => 1],
            ['id_permiso' => 75, 'id_rol' => 1],
            ['id_permiso' => 76, 'id_rol' => 1],
            ['id_permiso' => 77, 'id_rol' => 1],
            ['id_permiso' => 78, 'id_rol' => 1],
            ['id_permiso' => 79, 'id_rol' => 1],
            ['id_permiso' => 80, 'id_rol' => 1],
            ['id_permiso' => 81, 'id_rol' => 1],
            ['id_permiso' => 82, 'id_rol' => 1],
            ['id_permiso' => 83, 'id_rol' => 1],
            ['id_permiso' => 84, 'id_rol' => 1],
            ['id_permiso' => 85, 'id_rol' => 1],
            ['id_permiso' => 86, 'id_rol' => 1],
            ['id_permiso' => 87, 'id_rol' => 1],
            ['id_permiso' => 88, 'id_rol' => 1],
            ['id_permiso' => 89, 'id_rol' => 1],
            ['id_permiso' => 90, 'id_rol' => 1],
            ['id_permiso' => 91, 'id_rol' => 1],
            ['id_permiso' => 92, 'id_rol' => 1],
            ['id_permiso' => 93, 'id_rol' => 1],
            ['id_permiso' => 94, 'id_rol' => 1],
            ['id_permiso' => 95, 'id_rol' => 1],
            ['id_permiso' => 96, 'id_rol' => 1],
            ['id_permiso' => 97, 'id_rol' => 1],
            ['id_permiso' => 98, 'id_rol' => 1],
            ['id_permiso' => 99, 'id_rol' => 1],
            ['id_permiso' => 100, 'id_rol' => 1],
            ['id_permiso' => 101, 'id_rol' => 1],
            ['id_permiso' => 102, 'id_rol' => 1],
            ['id_permiso' => 103, 'id_rol' => 1],
            //Fin rol administrador -- Permisos
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
