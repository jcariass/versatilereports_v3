<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FormularioController;
use App\Http\Controllers\PreguntaController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UsuarioController;

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
    
    /* Inicio rutas gestión de usuarios */
    Route::get('/usuarios', [UsuarioController::class, 'view_list'])->name('listar_usuarios');
    Route::get('/usuarios/listar', [UsuarioController::class, 'list']);
    Route::get('/usuarios/crear', [UsuarioController::class, 'view_create'])->name('crear_usuario');
    Route::get('/usuarios/editar/{id}', [UsuarioController::class, 'view_edit'])->name('editar_usuario');
    Route::get('/usuarios/listar/municipios', [UsuarioController::class, 'get_municipios']);
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
    /* ------------------------------------------------- */
    /* ------------------------------------------------- */
    Route::get('/formularios/preguntas/{id}', [PreguntaController::class, 'view_list'])->name('preguntas_formulario');
    Route::get('/formularios/listar/preguntas/{id}', [PreguntaController::class, 'list']);
    Route::get('/formularios/crear/preguntas/{id}', [PreguntaController::class, 'view_store'])->name('añadir_preguntas');
    Route::get('/formularios/editar/pregunta/{id}', [PreguntaController::class,'view_edit'])->name('editar_pregunta');
    Route::put('/formularios/actualizar/pregunta', [PreguntaController::class, 'update'])->name('actualizar_pregunta');
    Route::get('/formularios/eliminar/pregunta/{id_pregunta}/{id_formulario}', [PreguntaController::class,'state_update'])->name('eliminar_pregunta');
    Route::post('/formularios/registrar/preguntas', [PreguntaController::class, 'save'])->name('registrar_preguntas');
    /* Fin rutas gestión de formularios */

});

