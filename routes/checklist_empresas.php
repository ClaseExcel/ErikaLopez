<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::resource('checklist_empresas', ChecklistEmpresaController::class)->except(['destroy'])->names('checklist_empresas');
    Route::get('seguimiento_checklist/create/{id}', [SeguimientoChecklistController::class, 'create'])->name('seguimiento_checklist.create');
    Route::post('seguimiento_checklist/store', [SeguimientoChecklistController::class, 'store'])->name('seguimiento_checklist.store');
    Route::get('seguimiento_checklist/edit/{id}', [SeguimientoChecklistController::class, 'edit'])->name('seguimiento_checklist.edit');
    Route::put('seguimiento_checklist/update/{id}', [SeguimientoChecklistController::class, 'update'])->name('seguimiento_checklist.update');
    Route::get('seguimiento_checklist/mes-existente/{checklist_id?}/{mes?}', [SeguimientoChecklistController::class, 'mesExistente'])->name('seguimiento_checklist.mes_existente');

    Route::post('checklist_empresas/filtro-actividades', [ChecklistEmpresaController::class, 'FiltroActividades'])->name('filtro-actividades');
    Route::post('checklist_empresas/actividades-realizadas', [ChecklistEmpresaController::class, 'indexActividadesRealizadas'])->name('actividades-realizadas.index');

});