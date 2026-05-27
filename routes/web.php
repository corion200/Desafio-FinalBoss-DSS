<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\Coach\ClienteController as CoachClienteController;
use App\Http\Controllers\Coach\RutinaController;
use App\Http\Controllers\Coach\ReporteEquipoController as CoachReporteController;
use App\Http\Controllers\Admin\ReporteEquipoController as AdminReporteController;
use App\Http\Controllers\Admin\BalanceController;
use App\Http\Controllers\Admin\MembresiaController;

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| RUTAS AUTENTICADAS - DASHBOARD
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| CLIENTES - CRUD COMPLETO (Resource) - Recepcionista y Admin
|--------------------------------------------------------------------------
*/
Route::resource('clientes', ClienteController::class)->except(['show']);

Route::get('/clientes/{cliente}', [ClienteController::class, 'show'])->name('clientes.show');

Route::post('/clientes/{cliente}/vender-membresia', [ClienteController::class, 'venderMembresia'])
    ->name('clientes.vender-membresia');

Route::post('/clientes/{cliente}/cancelar-membresia', [ClienteController::class, 'cancelarMembresia'])
    ->name('clientes.cancelar-membresia');

Route::post('/clientes/{cliente}/asignar-coach', [ClienteController::class, 'asignarCoach'])
    ->name('clientes.asignar-coach');

Route::post('/clientes/{cliente}/quitar-coach', [ClienteController::class, 'quitarCoach'])
    ->name('clientes.quitar-coach');

/*
|--------------------------------------------------------------------------
| VENTAS DE PRODUCTOS - Recepcionista y Admin
|--------------------------------------------------------------------------
*/
Route::get('/ventas/crear', [VentaController::class, 'create'])->name('ventas.create');
Route::post('/ventas', [VentaController::class, 'store'])->name('ventas.store');
Route::get('/ventas', [VentaController::class, 'index'])->name('ventas.index');
Route::get('/ventas/{venta}', [VentaController::class, 'show'])->name('ventas.show');

/*
|--------------------------------------------------------------------------
| ADMIN - PRODUCTOS (Resource)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('productos', ProductoController::class);

    Route::post('/productos/{producto}/reponer-stock', [ProductoController::class, 'reponerStock'])
        ->name('productos.reponer-stock');

    // Membresías (gestión)
    Route::get('/membresias', [MembresiaController::class, 'index'])->name('membresias.index');
    Route::post('/membresias', [MembresiaController::class, 'store'])->name('membresias.store');
    Route::put('/membresias/{membresia}', [MembresiaController::class, 'update'])->name('membresias.update');

    // Balance
    Route::get('/balance', [BalanceController::class, 'index'])->name('balance.index');

    // Reportes de equipo
    Route::get('/reportes', [AdminReporteController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/{reporteEquipo}', [AdminReporteController::class, 'show'])->name('reportes.show');
    Route::put('/reportes/{reporteEquipo}/estado', [AdminReporteController::class, 'updateEstado'])->name('reportes.update-estado');
});

/*
|--------------------------------------------------------------------------
| COACH - MIS CLIENTES
|--------------------------------------------------------------------------
*/
Route::prefix('coach')->name('coach.')->middleware('role:coach')->group(function () {
    Route::get('/clientes', [CoachClienteController::class, 'index'])->name('clientes.index');
    Route::get('/clientes/{cliente}', [CoachClienteController::class, 'show'])->name('clientes.show');

    // Rutinas
    Route::get('/clientes/{cliente}/rutinas/crear', [RutinaController::class, 'create'])->name('rutinas.create');
    Route::post('/clientes/{cliente}/rutinas', [RutinaController::class, 'store'])->name('rutinas.store');
    Route::get('/rutinas/{rutina}/editar', [RutinaController::class, 'edit'])->name('rutinas.edit');
    Route::put('/rutinas/{rutina}', [RutinaController::class, 'update'])->name('rutinas.update');

    // Reportes de equipo
    Route::get('/reportes/crear', [CoachReporteController::class, 'create'])->name('reportes.create');
    Route::post('/reportes', [CoachReporteController::class, 'store'])->name('reportes.store');
    Route::get('/reportes', [CoachReporteController::class, 'index'])->name('reportes.index');
});

/*
|--------------------------------------------------------------------------
| RUTA POR DEFECTO
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});