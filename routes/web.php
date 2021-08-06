<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DashboardController;
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

Route::middleware(['auth'])->group(function(){
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/principal', [DashboardController::class, 'view_dashboard'])->name('dashboard');
    Route::get('/usuarios', [UsuarioController::class, 'view_list'])->name('listar_usuarios');
    Route::get('/usuarios/listar', [UsuarioController::class, 'list']);
    Route::get('/usuarios/crear', [UsuarioController::class, 'view_create'])->name('crear_usuario');
    Route::get('/usuarios/editar/{id}', [UsuarioController::class, 'view_edit'])->name('editar_usuario');
    Route::get('/usuarios/listar/municipios', [UsuarioController::class, 'get_municipios']);
    Route::post('/usuarios/registrar', [UsuarioController::class, 'save'])->name('registrar_usuario');
    Route::get('/usuarios/cambiar/estado/{id}/{estado}', [UsuarioController::class, 'cambiar_estado']);
    Route::put('/usuarios/actualizar', [UsuarioController::class, 'update'])->name('actualizar_usuario');
});

