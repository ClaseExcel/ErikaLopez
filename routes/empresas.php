<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Route;

// rutas de clientes
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {

    Route::post('empresas/municipio', [EmpresaController::class, 'municipio'])->name('municipios');
    Route::resource('empresas', EmpresaController::class)->names('empresas');
    Route::get('empresas/uvt/{anio?}', [EmpresaController::class, 'findUVT'])->name('empresas.uvt');
    Route::get('admin/empresas/export', [EmpresaController::class, 'empresasExport'])->name('empresas.export');

    // Masivo
    Route::get('metas_empresas/masiva', 'MetasEmpresaController@masiva')->name('metas_empresas.masiva');
    Route::get('metas_empresas/masiva/export', 'MetasEmpresaController@exportMasiva')->name('metas_empresas.masiva.export');
    Route::post('metas_empresas/masiva/import', 'MetasEmpresaController@importMasiva')->name('metas_empresas.masiva.import');
    // Metas empresas
    Route::get('metas_empresas/cuentas/{empresa}', 'MetasEmpresaController@empresaCuentas')->name('admin.metas_empresas.cuentas');
    Route::resource('metas_empresas', 'MetasEmpresaController')->where(['metas_empresa' => '[0-9]+'])->only(['index', 'store', 'show', 'update']);
});
