<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RevisionRequerimientoController;
use App\Http\Controllers\EntregaRequerimientoController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\RequerimimientoController;
use App\Http\Controllers\ContratistaController;
use App\Http\Controllers\FormularioController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ObligacionController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MunicipioController;
use App\Http\Controllers\PlantillaController;
use App\Http\Controllers\PreguntaController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\ParrafoController;
use App\Http\Controllers\ProcesoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CentroController;
use App\Http\Controllers\ObjetoController;
use App\Http\Controllers\RolController;

Route::middleware(['guest'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });
    // Rutas de autenticacion
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    // Rutas de restablecer contraseña
    Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'ValidarPermisos'])->group(function(){
    Route::get('/principal', [DashboardController::class, 'view_dashboard'])->name('dashboard');
    
    /* Ruta obtener municipios */
    Route::get('/municipios', [MunicipioController::class, 'get']);
    /* Fin ruta obtener municipios */

    /* Inicio rutas gestión de usuarios */
    Route::get('/usuarios', [UsuarioController::class, 'view_list'])->name('listar_usuarios');
    Route::get('/usuarios/listar', [UsuarioController::class, 'list']);
    Route::get('/usuarios/crear', [UsuarioController::class, 'view_create'])->name('crear_usuario');
    Route::get('/usuarios/editar/{id}', [UsuarioController::class, 'view_edit'])->name('editar_usuario');
    Route::post('/usuarios/registrar', [UsuarioController::class, 'save'])->name('registrar_usuario');
    Route::get('/usuarios/cambiar/estado/{id}/{estado}', [UsuarioController::class, 'cambiar_estado']);
    Route::put('/usuarios/actualizar', [UsuarioController::class, 'update'])->name('actualizar_usuario');
    /* Fin rutas gestión de usuarios */

    /* Inicio rutas gestión de roles */
    Route::get('/roles', [RolController::class, 'view_list'])->name('listar_roles');
    Route::get('/roles/listar', [RolController::class, 'list']);
    Route::get('/roles/crear', [RolController::class, 'view_create'])->name('crear_roles');
    Route::post('/roles/registrar', [RolController::class, 'save'])->name('registrar_rol');
    Route::get('/roles/editar/{id}', [RolController::class, 'view_edit'])->name('editar_rol');
    Route::put('/roles/actualizar', [RolController::class, 'update'])->name('actualizar_rol');
    /* Fin rutas gestión de roles */
    
    /* Inicio rutas gestión de formularios */
    Route::get('/formularios', [FormularioController::class, 'view_list'])->name('listar_formularios');
    Route::get('/formularios/listar', [FormularioController::class, 'list']);
    Route::get('/formularios/crear', [FormularioController::class, 'view_create'])->name('crear_formularios');
    Route::post('/formularios/registrar', [FormularioController::class, 'save'])->name('registrar_formulario');
    Route::get('/formularios/editar/{id}', [FormularioController::class, 'view_edit'])->name('editar_formulario');
    Route::put('/formularios/actualizar', [FormularioController::class, 'update'])->name('actualizar_formulario');
    Route::get('/formularios/duplicar/{id}', [FormularioController::class, 'duplicar'])->name('duplicar_formulario');
    Route::get('/formularios/preguntas/{id}', [PreguntaController::class, 'view_list'])->name('preguntas_formulario');
    Route::get('/formularios/listar/preguntas/{id}', [PreguntaController::class, 'list']);
    Route::get('/formularios/crear/preguntas/{id}', [PreguntaController::class, 'view_store'])->name('añadir_preguntas');
    Route::get('/formularios/editar/pregunta/{id}', [PreguntaController::class,'view_edit'])->name('editar_pregunta');
    Route::put('/formularios/actualizar/pregunta', [PreguntaController::class, 'update'])->name('actualizar_pregunta');
    Route::get('/formularios/eliminar/pregunta/{id_pregunta}/{id_formulario}', [PreguntaController::class,'state_update'])->name('eliminar_pregunta');
    Route::post('/formularios/registrar/preguntas', [PreguntaController::class, 'save'])->name('registrar_preguntas');
    /* Fin rutas gestión de formularios */
    
    /* Inicio rutas gestión de objetos de contrato */
    Route::get('/objetos/contratos', [ObjetoController::class, 'view_list'])->name('listar_objetos_contratos');
    Route::get('/objetos/contratos/listar', [ObjetoController::class, 'list']);
    Route::get('/objetos/contratos/editar/{id}', [ObjetoController::class, 'view_edit']);
    Route::put('/objetos/contratos/editar', [ObjetoController::class, 'update'])->name('editar_objeto_contrato');
    Route::get('/objetos/contratos/crear', [ObjetoController::class, 'view_create'])->name('view_crear_objeto_contrato');
    Route::post('/objetos/contratos/crear/guardar', [ObjetoController::class, 'save'])->name('crear_objeto_contrato');
    /* Fin rutas gestión de objetos de contrato */

    /* Inicio rutas gestión de procesos */
    Route::get('/procesos', [ProcesoController::class, 'view_list'])->name('listar_procesos');
    Route::get('/procesos/listar', [ProcesoController::class, 'list']);
    Route::get('/procesos/editar/{id}', [ProcesoController::class, 'view_edit']);
    Route::put('/procesos/editar', [ProcesoController::class, 'update'])->name('editar_procesos');
    Route::get('/procesos/crear', [ProcesoController::class, 'view_create'])->name('view_crear_procesos');
    Route::post('/procesos/crear/guardar', [ProcesoController::class, 'save'])->name('crear_procesos');
    /* Fin rutas gestión de procesos */

    /* Inicio rutas gestión de centros */
    Route::get('/centros', [CentroController::class, 'view_list'])->name('listar_centros');
    Route::get('/centros/listar', [CentroController::class, 'list']);
    Route::get('/centros/editar/{id}', [CentroController::class, 'view_edit']);
    Route::put('/centros/editar', [CentroController::class, 'update'])->name('editar_centros');
    Route::get('/centros/crear', [CentroController::class, 'view_create'])->name('view_crear_centros');
    Route::post('/centros/crear/guardar', [CentroController::class, 'save'])->name('crear_centros');
    /* Fin rutas gestión de centros */

    /* Inicio rutas gestión de obligaciones */
    Route::get('/obligaciones', [ObligacionController::class, 'view_list'])->name('listar_obligaciones');
    Route::get('/obligaciones/listar', [ObligacionController::class, 'list']);
    Route::get('/obligaciones/editar/{id}', [ObligacionController::class, 'view_edit']);
    Route::put('/obligaciones/editar', [ObligacionController::class, 'update'])->name('editar_obligaciones');
    Route::get('/obligaciones/crear', [ObligacionController::class, 'view_create'])->name('view_crear_obligaciones');
    Route::post('/obligaciones/crear/guardar', [ObligacionController::class, 'save'])->name('crear_obligaciones');
    /* Fin rutas gestión de obligaciones */

    /* Inicio rutas gestión de supervisores */
    Route::get('/supervisores', [SupervisorController::class, 'view_list'])->name('listar_supervisores');
    Route::get('/supervisores/listar', [SupervisorController::class, 'list']);
    Route::get('/supervisores/ver/{id}', [SupervisorController::class, 'view_more']);
    /* Fin rutas gestión de supervisores */

    /* Inicio rutas gestión de plantillas y parrafos */
    Route::get('/plantillas', [PlantillaController::class, 'view_list'])->name('listar_plantillas');
    Route::get('/plantillas/listar', [PlantillaController::class, 'list']);
    Route::get('/plantillas/crear', [PlantillaController::class, 'view_create'])->name('crear_plantillas');
    Route::post('/plantillas/guardar', [PlantillaController::class, 'save'])->name('registrar_plantilla');
    Route::get('/plantillas/editar/{id}', [PlantillaController::class, 'view_edit']);
    Route::put('/plantillas/actualizar', [PlantillaController::class, 'update'])->name('editar_plantilla');
    Route::get('/plantillas/duplicar/{id}', [PlantillaController::class, 'duplicar']);
    Route::get('/plantillas/parrafos/{id}', [ParrafoController::class, 'view_list'])->name('listar_parrafos');
    Route::get('/plantillas/parrafos/listar/{id}', [ParrafoController::class, 'list']);
    Route::get('/plantillas/parrafos/crear/{id}', [ParrafoController::class, 'view_create'])->name('añadir_parrafos');
    Route::post('/plantillas/parrafos/guardar', [ParrafoController::class, 'save'])->name('guardar_parrafos');
    Route::get('/plantillas/parrafos/editar/{id}', [ParrafoController::class, 'view_edit']);
    Route::put('/plantillas/parrafos/actualizar', [ParrafoController::class, 'update'])->name('editar_parrafo');
    Route::get('/plantillas/parrafos/eliminar/{id}', [ParrafoController::class, 'update_state']);
    /* Fin rutas gestión de plantillas y parrafos */

    /* Inicio rutas gestión de contratistas y contratos */
    //Contratistas
    Route::get('/contratistas', [ContratistaController::class, 'view_list'])->name('listar_contratistas');
    Route::get('/contratistas/listar', [ContratistaController::class, 'list']);
    Route::get('/contratistas/reporte', [ContratistaController::class, 'view_reporte'])->name('view_reporte');
    Route::post('/contratistas/generar/reporte', [ContratistaController::class, 'generar_excel'])->name('generar_reporte_contratistas');
    //Fin contratistas
    //Contratos
    Route::get('/contratistas/contratos/{id}', [ContratoController::class, 'view_list'])->name('listar_contratos');
    Route::get('/contratistas/contratos/listar/{id}', [ContratoController::class, 'list']);
    Route::get('/contratistas/contratos/crear/{id}', [ContratoController::class, 'view_create'])->name('crear_contratos');
    Route::post('/contratistas/contratos/registrar', [ContratoController::class, 'save'])->name('guardar_contrato');
    Route::get('/contratistas/contratos/editar/{id}', [ContratoController::class, 'view_edit']);
    Route::put('/contratistas/contratos/actualizar', [ContratoController::class, 'update'])->name('editar_contrato');
    Route::get('/contratistas/ver/contrato/{id}', [ContratoController::class, 'view_more']);
    Route::get('/contratistas/contratos/cambiar/estado/{id}/{estado}', [ContratoController::class, 'state_update']);
    //Fin contratos
    /* Fin rutas gestión de contratos */

    /* Inicio rutas gestión de requerimientos */
    Route::get('/requerimientos', [RequerimimientoController::class, 'view_list'])->name('listar_requerimientos');
    Route::get('/requerimientos/listar', [RequerimimientoController::class, 'list']);
    Route::get('/requerimientos/crear', [RequerimimientoController::class, 'view_create'])->name('view_crear_requerimientos');
    Route::get('/requerimientos/editar/{id}', [RequerimimientoController::class, 'view_edit']);
    Route::put('/requerimientos/actualizar', [RequerimimientoController::class, 'update'])->name('editar_requerimientos');
    Route::post('/requerimientos/guardar', [RequerimimientoController::class, 'save'])->name('crear_requerimientos');
    Route::get('/requerimientos/cambiar/estado/{id}/{estado}', [RequerimimientoController::class, 'state_update']);
    /* Fin rutas gestión de requerimientos */

    /* Inicio rutas revisión de requerimientos */
    Route::get('/revision/requerimientos', [RevisionRequerimientoController::class, 'view_list'])->name('listar_rev_requerimientos');
    Route::post('/revision/requerimientos/generar/reporte', [RevisionRequerimientoController::class, 'generar_reporte'])->name('reporte_requerimientos');
    Route::get('/revision/requerimientos/listar', [RevisionRequerimientoController::class, 'list']);
    Route::get('/revision/requerimientos/detalles/{id}', [RevisionRequerimientoController::class, 'view_list_details']);
    Route::get('/revision/requerimientos/detalles/listar/{id}/{tipo}', [RevisionRequerimientoController::class, 'list_details']);
    Route::get('/revision/requerimientos/descargar/archivo/{nombre}', [RevisionRequerimientoController::class, 'download_archive']);
    /* Fin rutas revisión de requerimientos */

    /* Inicio rutas entrega de requerimientos */
    Route::get('/entrega/requerimientos', [EntregaRequerimientoController::class, 'view_list'])->name('listar_ent_requerimientos');
    Route::get('/entrega/requerimientos/listar', [EntregaRequerimientoController::class, 'list']);
    Route::get('/entrega/requerimientos/cargar/archivo/{id}', [EntregaRequerimientoController::class, 'view_insert_archive']);
    Route::get('/entrega/requerimientos/editar/archivo/{id}', [EntregaRequerimientoController::class, 'view_edit_archive']);
    Route::put('/entrega/requerimientos/actualizar/archivo', [EntregaRequerimientoController::class, 'update_archive'])->name('update_archive');
    Route::get('/entrega/requerimientos/descargar/archivo/{nombre}', [EntregaRequerimientoController::class, 'download_archive']);
    Route::post('/entrega/requerimientos/guardar/archivo', [EntregaRequerimientoController::class, 'insert_archive'])->name('insertar_archivo');
    Route::get('/entrega/requerimientos/informe/contractual/{id}', [EntregaRequerimientoController::class, 'view_insert_informe']);
    Route::post('/entrega/requerimientos/guardar/informe', [EntregaRequerimientoController::class, 'save'])->name('guardar_informe');
    /* Fin rutas entrega de requerimientos */
}); 

