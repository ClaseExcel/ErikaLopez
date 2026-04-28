<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    
    Route::resource('informe-empresa', InformeEmpresaController::class)->names('informe-empresa')->only('index');
    Route::resource('informe-usuario', InformeUsuarioController::class)->names('informe-usuario')->only('index');
    Route::resource('informe-empresa-usuario', InformeEmpresaUsuarioController::class)->names('informe-empresa-usuario')->only('index');
    Route::get('informe-actividades', [InformeUsuarioController::class, 'indexActividad'])->name('informe-actividades.index');

    Route::get('informe-empresa/informe', [InformeEmpresaController::class, 'getInformeEmpresa'])->name('excel-empresa');
    Route::get('informe-usuario/informe', [InformeUsuarioController::class, 'getInformeUsuario'])->name('excel-usuario');
    Route::get('informe-empresa-usuario/informe', [InformeEmpresaUsuarioController::class, 'getInformeEmpresaUsuario'])->name('excel-empresa-usuario');
    Route::get('informe-actividades/informe/tipoActividad', [InformeUsuarioController::class, 'getInformeActividad'])->name('excel-actividades');
    Route::get('informe-actividades/informe/estado', [InformeUsuarioController::class, 'getInformeEstado'])->name('excel-actividades-estado');
    Route::get('informe-empresa/usuario/{id}', [InformeUsuarioController::class, 'showUsuario']);

    Route::get('informe-actividades/pdf', [InformeUsuarioController::class, 'getPdfActividad'])->name('pdf-actividades');

    Route::resource('informe-cotizaciones', InformeCRMController::class)->names('informe_cotizaciones')->only('index');
    Route::get('informe-cotizaciones/informe', [InformeCRMController::class, 'getCotizaciones'])->name('excel-cotizacion');

    Route::get('informe-actividades-empresas', [InformeEspecificoEmpresaController::class, 'index'])->name('informe-actividades-empresas.index');
    Route::get('informe-actividades-empresas/informe/tipoActividad', [InformeEspecificoEmpresaController::class, 'getInformeActividad'])->name('excel-actividades-empresas');
    Route::get('informe-actividades-empresas/informe/estado', [InformeEspecificoEmpresaController::class, 'getInformeEstado'])->name('excel-actividades-empresas-estado');
});
