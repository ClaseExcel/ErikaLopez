<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('gestiones/notificaciones', [GestionController::class, 'historialNotificaciones'])->name('gestiones.notificaciones');
    Route::get('gestiones/pdf/{id}', [GestionController::class, 'generarPdf'])->name('pdf.gestion');
    Route::post('gestiones/enviar-correo', [GestionController::class, 'enviarCorreo'])->name('gestiones.enviarCorreo');
    //Visitas/Gestion
    Route::resource('gestiones', GestionController::class)->names('gestiones');
    Route::get('gestiones/pdf/{id}', [GestionController::class, 'generarPdf'])->name('pdf.gestion');
    Route::post('gestiones/enviar-whatsapp', [GestionController::class, 'enviarWhatsapp'])->name('gestiones.enviarWhatsapp');
});
