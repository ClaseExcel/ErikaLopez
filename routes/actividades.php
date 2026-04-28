<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    //Actividad cliente
    Route::get('actividad_cliente/reporte/{id}', [ActividadClienteController::class, 'reporteIndex'])->name('reporte.index');
    Route::get('actividad_cliente/reasignar-actividad/{id}', [ActividadClienteController::class, 'reasignarActividad'])->name('reporte.reasignar');
    Route::put('actividad_cliente/reporte-edit/{id}', [ActividadClienteController::class, 'reporteEdit'])->name('reporte.update');
    Route::get('actividad_cliente/cliente_id/{id}', [ActividadClienteController::class, 'showEmpresa']);
    Route::get('actividad_cliente/usuario_id/{id?}', [ActividadClienteController::class, 'showResponsable'])->name('actividad_cliente.responsable');
    Route::get('actividad_cliente/reporte/usuario_id/{id}', [ActividadClienteController::class, 'showResponsable']);
    Route::get('actividad_cliente/plantilla', [ActividadClienteController::class, 'masivoactividades'])->name('actividad_cliente.masivoactividades');
    Route::post('actividad_cliente/importExcel', [ActividadClienteController::class, 'importExcel'])->name('actividad_cliente.importExcel');
    Route::post('actividad_cliente/descargarExcel', [ActividadClienteController::class, 'descargarExcel'])->name('actividad_cliente.descargarExcel');
    Route::resource('actividad_cliente', ActividadClienteController::class)->except(['destroy']);
});
