<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\TrabajadorController;
use App\Http\Controllers\BibliotecarioController;
use App\Http\Controllers\ReporteController;

// Rutas públicas
Route::get('/', [UsuarioController::class, 'showLogin'])->name('login');
Route::get('/login', [UsuarioController::class, 'showLogin'])->name('login.form');
Route::post('/login', [UsuarioController::class, 'login'])->name('login.enviar');
Route::get('/registro', [UsuarioController::class, 'showRegistro'])->name('registro.form');
Route::post('/registro', [UsuarioController::class, 'store'])->name('registro.enviar');
Route::post('/logout', [UsuarioController::class, 'logout'])->name('logout');

// Servicios y contacto (públicos)
Route::view('/servicios', 'servicios')->name('servicios');
Route::view('/contacto', 'contacto')->name('contacto');

// Rutas para usuarios autenticados
Route::get('/inicio', [LibroController::class, 'inicio'])->name('inicio');
Route::get('/catalogo', [LibroController::class, 'index'])->name('catalogo');
Route::get('/libros/{id}', [LibroController::class, 'show'])->name('libros.show');
Route::get('/usuario/historial', [UsuarioController::class, 'historial'])->name('usuario.historial');
Route::resource('prestamos', PrestamoController::class)->except(['edit', 'update', 'destroy']);

// Rutas para bibliotecarios
Route::prefix('bibliotecario')->group(function () {
    Route::get('/dashboard', [BibliotecarioController::class, 'dashboard'])->name('bibliotecario.dashboard');
    Route::post('/prestamo/{id}/confirmar', [BibliotecarioController::class, 'confirmarPrestamo'])->name('bibliotecario.confirmarPrestamo');
    Route::post('/devolucion', [BibliotecarioController::class, 'registrarDevolucion'])->name('bibliotecario.registrarDevolucion');
    Route::post('/sancion', [BibliotecarioController::class, 'aplicarSancion'])->name('bibliotecario.aplicarSancion');
    Route::post('/sancion/completar', [BibliotecarioController::class, 'completarSancion'])->name('bibliotecario.completarSancion');
    Route::delete('/sancion/eliminar', [BibliotecarioController::class, 'eliminarSancion'])->name('bibliotecario.eliminarSancion');
    Route::get('/usuario/{id}/sanciones', [BibliotecarioController::class, 'getSancionesUsuario'])->name('bibliotecario.getSancionesUsuario');
    Route::get('/usuario/{id}/prestamos', [BibliotecarioController::class, 'getPrestamosUsuario'])->name('bibliotecario.getPrestamosUsuario');
    Route::post('/libro/{codigo}/disponibilidad', [BibliotecarioController::class, 'modificarDisponibilidad'])->name('bibliotecario.modificarDisponibilidad');
    Route::post('/prestamo/{id}/denegar', [BibliotecarioController::class, 'denegarPrestamo'])->name('bibliotecario.denegarPrestamo');
});

// Rutas para administradores
Route::prefix('admin')->group(function () {
    Route::get('/panel', [AdministradorController::class, 'panel'])->name('admin.panel');
    Route::get('/reportes/grafico', [AdministradorController::class, 'grafico'])->name('admin.reportes.grafico');
    Route::get('/reportes/tabla', [AdministradorController::class, 'tabla'])->name('admin.reportes.tabla');

    // Rutas para gestión de sanciones
    Route::post('/sanciones/{accion}/{id?}', [AdministradorController::class, 'gestionarSancion'])->name('admin.sanciones.gestionar');
    Route::get('/sanciones/{accion}', [AdministradorController::class, 'gestionarSancion'])->name('admin.sanciones.gestionar');

    Route::resource('usuarios', UsuarioController::class);
    Route::resource('trabajadores', TrabajadorController::class);
    Route::resource('libros', LibroController::class);
    Route::resource('categorias', CategoriaController::class);
    Route::resource('administradores', AdministradorController::class);
});

// Rutas para reportes (solo admin y bibliotecario)
Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
Route::get('/reportes/prestamos', [ReporteController::class, 'prestamos'])->name('reportes.prestamos');
Route::get('/reportes/sanciones', [ReporteController::class, 'sanciones'])->name('reportes.sanciones');

// Rutas para trabajadores (solo creación, edición, eliminación, etc. fuera del index)
Route::resource('trabajadores', TrabajadorController::class)->parameters([
    'trabajadores' => 'trabajador'
])->except(['index']);

// Ruta para el dashboard del trabajador
Route::get('/trabajador/dashboard', [BibliotecarioController::class, 'dashboard'])->name('trabajador.dashboard');
