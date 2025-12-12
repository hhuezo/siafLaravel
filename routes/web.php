<?php

use App\Http\Controllers\activo\EquipoController;
use App\Http\Controllers\activo\VehiculoController;
use App\Http\Controllers\catalogo\AmbienteController;
use App\Http\Controllers\catalogo\ClaseController;
use App\Http\Controllers\catalogo\ColorController;
use App\Http\Controllers\catalogo\EstadoFisicoController;
use App\Http\Controllers\MigracionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('/migracion', [MigracionController::class, 'index']);
Route::get('/equipo/get_data', [EquipoController::class, 'data'])->name('equipos.data');

Route::post('/vehiculo/reporte_inventario', [EquipoController::class, 'reporteInventario'])->name('vehiculo.reporteInventario');
Route::post('/equipo/reporte_inventario', [EquipoController::class, 'reporteInventario'])->name('equipo.reporteInventario');
Route::get('/equipo/load_empleados/{id}', [EquipoController::class, 'loadEmpleados'])->name('equipo.loadEmpleados');
Route::get('/equipo/load_subclases/{id}', [EquipoController::class, 'loadSubclases'])->name('equipo.loadSubclases');


Route::resource('/color', ColorController::class);
Route::resource('/clase', ClaseController::class);
Route::resource('/ambiente', AmbienteController::class);
Route::resource('/estado_fisico', EstadoFisicoController::class);


Route::resource('/equipo', EquipoController::class);
Route::resource('/vehiculo', VehiculoController::class);
