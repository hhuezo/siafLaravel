<?php

use App\Http\Controllers\activo\EquipoController;
use App\Http\Controllers\activo\VehiculoController;
use App\Http\Controllers\catalogo\AmbienteController;
use App\Http\Controllers\catalogo\ClaseController;
use App\Http\Controllers\catalogo\ColorController;
use App\Http\Controllers\catalogo\CuentaContableController;
use App\Http\Controllers\catalogo\DepartamentoController;
use App\Http\Controllers\catalogo\EstadoFisicoController;
use App\Http\Controllers\catalogo\FuenteController;
use App\Http\Controllers\catalogo\GerenciaController;
use App\Http\Controllers\catalogo\GrupoController;
use App\Http\Controllers\catalogo\MarcaController;
use App\Http\Controllers\catalogo\MaterialController;
use App\Http\Controllers\catalogo\ProcedenciaController;
use App\Http\Controllers\catalogo\SubClaseController;
use App\Http\Controllers\catalogo\TraccionController;
use App\Http\Controllers\catalogo\UnidadController;
use App\Http\Controllers\MigracionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\seguridad\PermissionController;
use App\Http\Controllers\seguridad\PermissionTypeController;
use App\Http\Controllers\seguridad\RoleController;
use App\Http\Controllers\seguridad\UserController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('/change-password', [HomeController::class, 'changePassword'])->name('password.change');


    //seguridad
    Route::resource('seguridad/permission_type', PermissionTypeController::class);
    Route::resource('seguridad/permission', PermissionController::class);
    Route::post('seguridad/role/update_permission', [RoleController::class, 'updatePermission']);
    Route::resource('seguridad/role', RoleController::class);
    Route::post('seguridad/user/update_password/{id}', [UserController::class, 'updatePassword']);
    Route::put('seguridad/user/sync_rol/{user_id}/{rol_id}', [UserController::class, 'sync_rol']);
    Route::resource('seguridad/user', UserController::class);

    Route::get('/migracion', [MigracionController::class, 'index']);
    Route::get('/equipo/get_data', [EquipoController::class, 'data'])->name('equipos.data');

    Route::get('/reportes/inventario_equipo', [EquipoController::class, 'vistaReporteInventario'])->name('reportes.inventario_equipo');
    Route::post('/equipo/reporte_inventario', [EquipoController::class, 'reporteInventario'])->name('equipo.reporteInventario');
    Route::post('/vehiculo/reporte_inventario', [EquipoController::class, 'reporteInventario'])->name('vehiculo.reporteInventario');
    Route::get('/equipo/load_ambientes/{id}', [EquipoController::class, 'loadAmbientes'])->name('equipo.loadAmbientes');
    Route::get('/equipo/load_empleados/{id}', [EquipoController::class, 'loadEmpleados'])->name('equipo.loadEmpleados');
    Route::get('/equipo/load_subclases/{id}', [EquipoController::class, 'loadSubclases'])->name('equipo.loadSubclases');
    Route::get('/equipo/generar_codigo/{subclaseId}', [EquipoController::class, 'generarCodigoActivo'])->name('equipo.generarCodigo');

    Route::get('/vehiculo/get_data', [VehiculoController::class, 'data'])->name('vehiculos.data');
    Route::get('/vehiculo/load_ambientes/{id}', [VehiculoController::class, 'loadAmbientes'])->name('vehiculo.loadAmbientes');
    Route::get('/vehiculo/load_empleados/{id}', [VehiculoController::class, 'loadEmpleados'])->name('vehiculo.loadEmpleados');
    Route::get('/vehiculo/load_subclases/{id}', [VehiculoController::class, 'loadSubclases'])->name('vehiculo.loadSubclases');
    Route::get('/vehiculo/generar_codigo/{subclaseId}', [VehiculoController::class, 'generarCodigoActivo'])->name('vehiculo.generarCodigo');

    Route::resource('/color', ColorController::class);
    Route::resource('/clase', ClaseController::class);
    Route::resource('/ambiente', AmbienteController::class);
    Route::resource('/estado_fisico', EstadoFisicoController::class);
    Route::resource('/fuente', FuenteController::class);
    Route::resource('/marca', MarcaController::class);
    Route::resource('/material', MaterialController::class);
    Route::resource('/procedencia', ProcedenciaController::class);
    Route::resource('/subclase', SubClaseController::class);
    Route::resource('/traccion', TraccionController::class);
    Route::resource('/unidad', UnidadController::class);
    Route::resource('/cuenta_contable', CuentaContableController::class);
    Route::resource('/departamento', DepartamentoController::class);
    Route::resource('/gerencia', GerenciaController::class);
    Route::resource('/grupo', GrupoController::class);

    Route::resource('/equipo', EquipoController::class);
    Route::resource('/vehiculo', VehiculoController::class);
});
