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

            //Ruta municipios
            [   'nombre' => 'Listar municipios y departamentos',   'url' => '/municipios', 'method' => 'GET',  'url_identica' => 1 ],
            //Fin ruta municipios
            
            //Rutas gestión de usuarios
            [   'nombre' => 'Modulo gestión de usuarios',  'url' => '/usuarios',   'method' => 'GET',  'url_identica' => 1 ],
            [   'nombre' => 'Listar usuarios',  'url' => '/usuarios/listar',   'method' => 'GET',  'url_identica' => 0 ],
            [   'nombre' => 'Ver formulario crear usuarios',   'url' => '/usuarios/crear', 'method' => 'GET',  'url_identica' => 1 ],
            [   'nombre' => 'Ver formulario editar usuarios',   'url' => '/usuarios/editar/', 'method' => 'GET',  'url_identica' => 0 ],
            [   'nombre' => 'Registrar usuarios',   'url' => '/usuarios/registrar', 'method' => 'POST',  'url_identica' => 1 ],
            [   'nombre' => 'Cambiar estado de usuarios',   'url' => '/usuarios/cambiar/estado/', 'method' => 'GET',  'url_identica' => 0 ],
            [   'nombre' => 'Actualizar usuarios',   'url' => '/usuarios/actualizar', 'method' => 'PUT',  'url_identica' => 1],
            //Fin rutas gestión de usuarios  -- 7 rutas

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
            [   'nombre' => 'Duplicar formularios',   'url' => '/formularios/duplicar/', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Ver preguntas del formulario',   'url' => '/formularios/preguntas/', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Listar preguntas de un formulario',   'url' => '/formularios/listar/preguntas/', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Ver formulario de crear preguntas de un formulario',   'url' => '/formularios/crear/preguntas/', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Crear preguntas a un formulario',   'url' => '/formularios/registrar/preguntas', 'method' => 'POST',  'url_identica' => 1],
            [   'nombre' => 'Ver formulario de editar preguntas a un formulario',   'url' => '/formularios/editar/pregunta/', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Actualizar preguntas a un formulario',   'url' => '/formularios/actualizar/pregunta', 'method' => 'PUT',  'url_identica' => 1],
            [   'nombre' => 'Eliminar preguntas a un formulario',   'url' => '/formularios/eliminar/pregunta/', 'method' => 'GET',  'url_identica' => 0],
            //Fin rutas gestión de formularios -- 14 rutas

            //Rutas gestión de objetos de contrato
            [   'nombre' => 'Modulo gestión de objetos de contrato',   'url' => '/objetos/contratos', 'method' => 'GET',  'url_identica' => 1],
            [   'nombre' => 'Listar objetos de contrato',   'url' => '/objetos/contratos/listar', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Ver formulario de editar objeto de contrato',   'url' => '/objetos/contratos/editar/', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Actualizar objetos de contrato',   'url' => '/objetos/contratos/editar', 'method' => 'PUT',  'url_identica' => 1],
            [   'nombre' => 'Ver formulario de crear objeto de contrato',   'url' => '/objetos/contratos/crear', 'method' => 'GET',  'url_identica' => 1],
            [   'nombre' => 'Registrar objetos de contrato',   'url' => '/objetos/contratos/crear/guardar', 'method' => 'POST',  'url_identica' => 1],
            //Fin rutas gestión de objetos de contrato -- 6 rutas

            //Rutas gestión de procesos
            [   'nombre' => 'Modulo gestión de procesos',   'url' => '/procesos', 'method' => 'GET',  'url_identica' => 1],
            [   'nombre' => 'Listar procesos',   'url' => '/procesos/listar', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Ver formulario de editar proceso',   'url' => '/procesos/editar/', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Actualizar procesos',   'url' => '/procesos/editar', 'method' => 'PUT',  'url_identica' => 1],
            [   'nombre' => 'Ver formulario de crear proceso',   'url' => '/procesos/crear', 'method' => 'GET',  'url_identica' => 1],
            [   'nombre' => 'Registrar procesos',   'url' => '/procesos/crear/guardar', 'method' => 'POST',  'url_identica' => 1],
            //Fin rutas gestión de procesos -- 6 rutas

            //Rutas gestión de centros
            [   'nombre' => 'Modulo gestión de centros',   'url' => '/centros', 'method' => 'GET',  'url_identica' => 1],
            [   'nombre' => 'Listar centros',   'url' => '/centros/listar', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Ver formulario de editar proceso',   'url' => '/centros/editar/', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Actualizar centros',   'url' => '/centros/editar', 'method' => 'PUT',  'url_identica' => 1],
            [   'nombre' => 'Ver formulario de crear proceso',   'url' => '/centros/crear', 'method' => 'GET',  'url_identica' => 1],
            [   'nombre' => 'Registrar centros',   'url' => '/centros/crear/guardar', 'method' => 'POST',  'url_identica' => 1],
            //Fin rutas gestión de centros -- 6 rutas

            //Rutas gestión de obligaciones
            [   'nombre' => 'Modulo gestión de obligaciones',   'url' => '/obligaciones', 'method' => 'GET',  'url_identica' => 1],
            [   'nombre' => 'Listar obligaciones',   'url' => '/obligaciones/listar', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Ver formulario de editar proceso',   'url' => '/obligaciones/editar/', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Actualizar obligaciones',   'url' => '/obligaciones/editar', 'method' => 'PUT',  'url_identica' => 1],
            [   'nombre' => 'Ver formulario de crear proceso',   'url' => '/obligaciones/crear', 'method' => 'GET',  'url_identica' => 1],
            [   'nombre' => 'Registrar obligaciones',   'url' => '/obligaciones/crear/guardar', 'method' => 'POST',  'url_identica' => 1],
            //Fin rutas gestión de obligaciones -- 6 rutas

            //Rutas gestión de supervisores
            [   'nombre' => 'Modulo gestión de supervisores',   'url' => '/supervisores', 'method' => 'GET',  'url_identica' => 1],
            [   'nombre' => 'Listar supervisores',   'url' => '/supervisores/listar', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Ver detalles de un supervisor',   'url' => '/supervisores/ver/', 'method' => 'GET',  'url_identica' => 0],
            //Fin rutas gestión de supervisores -- 3 rutas

            //Rutas gestión de plantillas
            [   'nombre' => 'Modulo gestión de plantillas',   'url' => '/plantillas', 'method' => 'GET',  'url_identica' => 1],
            [   'nombre' => 'Listar plantillas',   'url' => '/plantillas/listar', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Ver formulario de crear plantilla',   'url' => '/plantillas/crear', 'method' => 'GET',  'url_identica' => 1],
            [   'nombre' => 'Registrar una plantilla',   'url' => '/plantillas/guardar', 'method' => 'POST',  'url_identica' => 1],
            [   'nombre' => 'Ver formulario de editar plantilla',   'url' => '/plantillas/editar/', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Actualizar una plantilla',   'url' => '/plantillas/actualizar', 'method' => 'PUT',  'url_identica' => 1],
            [   'nombre' => 'Ver una plantilla',   'url' => '/plantillas/parrafos/', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Duplicar una plantilla',   'url' => '/plantillas/duplicar/', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Listar preguntas de una plantilla',   'url' => '/plantillas/parrafos/listar/', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Ver formulario de añadir preguntas a una plantilla',   'url' => '/plantillas/parrafos/crear/', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Registrar preguntas a una plantilla',   'url' => '/plantillas/parrafos/guardar', 'method' => 'POST',  'url_identica' => 1],
            [   'nombre' => 'Ver formulario de editar preguntas de una plantilla',   'url' => '/plantillas/parrafos/editar/', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Actualizar preguntas a una plantilla',   'url' => '/plantillas/parrafos/actualizar', 'method' => 'PUT',  'url_identica' => 1],
            [   'nombre' => 'Eliminar preguntas a una plantilla',   'url' => '/plantillas/parrafos/eliminar/', 'method' => 'GET',  'url_identica' => 0],
            //Fin rutas gestión de plantillas -- 10 rutas
            
            //Rutas gestión de contratistas y contratos
            [   'nombre' => 'Modulo gestión de contratistas',   'url' => '/contratistas', 'method' => 'GET',  'url_identica' => 1],
            [   'nombre' => 'Listar contratistas',   'url' => '/contratistas/listar', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Ver vista de contratos por contratista',   'url' => '/contratistas/contratos/', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Listar contratos por contratista',   'url' => '/contratistas/contratos/listar/', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Ver formulario de crear contrato',   'url' => '/contratistas/contratos/crear/', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Registrar un contrato',   'url' => '/contratistas/contratos/registrar', 'method' => 'POST',  'url_identica' => 1],
            [   'nombre' => 'Ver formulario de editar contrato',   'url' => '/contratistas/contratos/editar/', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Actualizar un contrato',   'url' => '/contratistas/contratos/actualizar', 'method' => 'PUT',  'url_identica' => 1],
            [   'nombre' => 'Ver detalles de un contrato',   'url' => '/contratistas/ver/contrato/', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Cambiar estados de un contrato',   'url' => '/contratistas/contratos/cambiar/estado/', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Ver formulario de generar reporte de contratistas',   'url' => '/contratistas/reporte', 'method' => 'GET',  'url_identica' => 1],
            [   'nombre' => 'Generar reporte de contratistas',   'url' => '/contratistas/generar/reporte', 'method' => 'POST',  'url_identica' => 1],
            //Fin rutas gestión de contratistas y contratos -- 12 rutas

            //Rutas gestión de requerimientos
            [   'nombre' => 'Modulo gestión de requerimientos',   'url' => '/requerimientos', 'method' => 'GET',  'url_identica' => 1],
            [   'nombre' => 'Listar requerimientos',   'url' => '/requerimientos/listar', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Ver formulario de crear requerimiento',   'url' => '/requerimientos/crear', 'method' => 'GET',  'url_identica' => 1],
            [   'nombre' => 'Registrar un requerimiento',   'url' => '/requerimientos/guardar', 'method' => 'POST',  'url_identica' => 1],
            [   'nombre' => 'Ver formulario de editar requerimiento',   'url' => '/requerimientos/editar/', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Actualizar un requerimiento',   'url' => '/requerimientos/actualizar', 'method' => 'PUT',  'url_identica' => 1],
            [   'nombre' => 'Cambiar estados a un requerimiento',   'url' => '/requerimientos/cambiar/estado/', 'method' => 'GET',  'url_identica' => 0],
            //Fin rutas gestión de requerimientos -- 6 rutas

            //Rutas revisión de requerimientos
            [   'nombre' => 'Modulo revisión de requerimientos',   'url' => '/revision/requerimientos', 'method' => 'GET',  'url_identica' => 1],
            [   'nombre' => 'Listar requerimientos para revisión',   'url' => '/revision/requerimientos/listar', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Ver detalles de un requerimiento',   'url' => '/revision/requerimientos/detalles/', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Listar respuestas de un requerimiento',   'url' => '/revision/requerimientos/detalles/listar/', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Generar reporte de respuestas a un requerimiento',   'url' => '/revision/requerimientos/generar/reporte', 'method' => 'POST',  'url_identica' => 1],
            [   'nombre' => 'Descargar archivo de una respuesta a un requerimiento',   'url' => '/revision/requerimientos/descargar/archivo/', 'method' => 'GET',  'url_identica' => 0],
            //Fin rutas revisión de requerimientos -- 5 rutas

            //Rutas entrega de requerimientos
            [   'nombre' => 'Modulo entrega de requerimientos',   'url' => '/entrega/requerimientos', 'method' => 'GET',  'url_identica' => 1],
            [   'nombre' => 'Listar requerimientos para entrega',   'url' => '/entrega/requerimientos/listar', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Ver formulario de requerimientos de tipo archivo',   'url' => '/entrega/requerimientos/cargar/archivo/', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Enviar requerimientos de tipo archivo',   'url' => '/entrega/requerimientos/guardar/archivo', 'method' => 'POST',  'url_identica' => 1],
            [   'nombre' => 'Ver formulario de editar requerimientos de tipo archivo',   'url' => '/entrega/requerimientos/editar/archivo/', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Actualizar requerimientos de tipo archivo',   'url' => '/entrega/requerimientos/actualizar/archivo', 'method' => 'PUT',  'url_identica' => 1],
            [   'nombre' => 'Descargar archivos enviados de un requerimiento',   'url' => '/entrega/requerimientos/descargar/archivo/', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Ver formulario de requerimientos de tipo informe',   'url' => '/entrega/requerimientos/informe/contractual/', 'method' => 'GET',  'url_identica' => 0],
            [   'nombre' => 'Enviar requerimientos de tipo formulario',   'url' => '/entrega/requerimientos/guardar/informe', 'method' => 'POST',  'url_identica' => 1],
            //Fin rutas entrega de requerimientos -- 5 rutas
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
