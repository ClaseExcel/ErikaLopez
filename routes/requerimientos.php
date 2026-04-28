<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    //Requerimientos empleado
    Route::resource('requerimientos-empleado', RequerimientoEmpleadoController::class)->names('requerimientos.empleado')->except('destroy', 'show', 'create', 'store');
    Route::get('admin/requerimientos/export', [RequerimientoEmpleadoController::class, 'requerimientoExport'])->name('requerimientos.export');

    //Seguimiento requerimientos
    Route::resource('seguimiento-cliente', SeguimientoRequerimientoController::class)->names('seguimientos.cliente')->except('destroy', 'show', 'create', 'store');

    //Requerimientos cliente
    Route::get('requerimientos-cliente/download/{id}', [RequerimientoClienteController::class, 'download'])->name('requerimientos.cliente.download');
    Route::post('requerimientos-cliente/desistir/{id}', [RequerimientoClienteController::class, 'desistir'])->name('requerimientos.cliente.desistir');
    Route::get('requerimientos-cliente/empleados/{id}', [RequerimientoClienteController::class, 'findEmpleados']);
    Route::resource('requerimientos-cliente', RequerimientoClienteController::class)->names('requerimientos.cliente')->except('edit', 'update', 'destroy');
});
