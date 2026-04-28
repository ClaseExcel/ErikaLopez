<?php

namespace App\Http\Controllers\Admin;

use App\Models\GestionHumana;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {

    Route::resource('gestion-humana', GestionHumanaController::class)->names('gestion-humana');
    Route::post('gestion-humana/exportar', [GestionHumanaController::class, 'exportarGestion'])->name('gestion-humana.export');
    Route::get('gestion-humana/generar/{gestiones}', [GestionHumanaController::class, 'generarDocumento'])->name('gestion-humana.generar');
    Route::resource('gestion-humana-eventos', GestionHumanaEventoController::class)->names('gestion-humana-eventos');
    Route::get('gestion-humana-eventos/create/{id}', [GestionHumanaEventoController::class, 'create'])->name('gestion-humana-eventos.create');
    Route::get('exportar-eventos', [GestionHumanaEventoController::class, 'exportarEventos'])->name('gestion-humana-eventos.exportar');
});
