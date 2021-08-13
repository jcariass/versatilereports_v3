<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePermisosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permisos', function (Blueprint $table) {
            $table->id('id_permiso');
            $table->string('nombre');
            $table->string('url')->unique();
            $table->string('method', 10);
            $table->boolean('url_identica');
        });

        DB::table('permisos')->insert([
            //Rutas dashboard
            [   'nombre' => 'Dashboard',    'url' => '/principal',  'method' => 'GET',  'url_identica' => 1 ],
            //Fin rutas dashboard - 1 ruta
            
            //Rutas gestión de usuarios
            [   'nombre' => 'Modulo gestión de usuarios',  'url' => '/usuarios',   'method' => 'GET',  'url_identica' => 1 ],
            [   'nombre' => 'Listar usuarios',  'url' => '/usuarios/listar',   'method' => 'GET',  'url_identica' => 0 ],
            [   'nombre' => 'Ver formulario crear usuarios',   'url' => '/usuarios/crear', 'method' => 'GET',  'url_identica' => 1 ],
            [   'nombre' => 'Ver formulario editar usuarios',   'url' => '/usuarios/editar/', 'method' => 'GET',  'url_identica' => 0 ],
            [   'nombre' => 'Listar municipios y departamentos',   'url' => '/usuarios/listar/municipios', 'method' => 'GET',  'url_identica' => 1 ],
            [   'nombre' => 'Registrar usuarios',   'url' => '/usuarios/registrar', 'method' => 'POST',  'url_identica' => 1 ],
            [   'nombre' => 'Cambiar estado de usuarios',   'url' => '/usuarios/cambiar/estado/', 'method' => 'GET',  'url_identica' => 0 ],
            [   'nombre' => 'Actualizar usuarios',   'url' => '/usuarios/actualizar', 'method' => 'PUT',  'url_identica' => 1],
            //Fin rutas gestión de usuarios  -- 8 rutas

            //Rutas gestión de roles
            [   'nombre' => 'Modulo gestión de roles',   'url' => '/roles', 'method' => 'GET',  'url_identica' => 1],
            [   'nombre' => 'Listar roles',   'url' => '/roles/listar', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Ver formulario crear roles',   'url' => '/roles/crear', 'method' => 'GET',  'url_identica' => 1],
            [   'nombre' => 'Registrar rol',   'url' => '/roles/registrar', 'method' => 'POST',  'url_identica' => 1],
            [   'nombre' => 'Ver formulario editar roles',   'url' => '/roles/editar/', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Actualizar rol',   'url' => '/roles/actualizar', 'method' => 'PUT',  'url_identica' => 1],
            //Fin rutas gestión de roles -- 6 rutas
            
            //Rutas gestión de formularios
            [   'nombre' => 'Modulo gestión de formularios',   'url' => '/formularios', 'method' => 'GET',  'url_identica' => 1],
            [   'nombre' => 'Listar formularios',   'url' => '/formularios/listar', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Ver formulario crear formularios',   'url' => '/formularios/crear', 'method' => 'GET',  'url_identica' => 1],
            [   'nombre' => 'Registrar formulario',   'url' => '/formularios/registrar', 'method' => 'POST',  'url_identica' => 1],
            [   'nombre' => 'Ver formulario editar formularios',   'url' => '/formularios/editar/', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Actualizar formulario',   'url' => '/formularios/actualizar', 'method' => 'PUT',  'url_identica' => 1],
            /* 6 rutas */
            /* ------------------------------------------------------------- */
            /* ------------------------------------------------------------- */
            [   'nombre' => 'Ver preguntas del formulario',   'url' => '/formularios/preguntas/', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Listar preguntas de un formulario',   'url' => '/formularios/listar/preguntas/', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Ver formulario de crear preguntas de un formulario',   'url' => '/formularios/crear/preguntas/', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Crear preguntas a un formulario',   'url' => '/formularios/registrar/preguntas', 'method' => 'POST',  'url_identica' => 1],
            //Fin rutas gestión de formularios
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permisos');
    }
}
