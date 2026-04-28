<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    //Cotizaciones
    Route::resource('cotizaciones', CotizacionController::class)->names('cotizaciones');
    Route::get('cotizaciones/informacion-empresa/{cliente?}', [CotizacionController::class, 'showInformacionEmpresa'])->name('informacion-empresa');
    Route::get('cotizaciones/seguimiento-cotizacion/{id}', [CotizacionController::class, 'showSeguimiento'])->name('cotizacion-seguimiento.index');
    Route::post('cotizaciones/store-seguimiento/{id}', [CotizacionController::class, 'storeSeguimiento'])->name('cotizacion-seguimiento.store');
    Route::get('cotizaciones/edit-seguimiento/{id}/{cotizacion}', [CotizacionController::class, 'editSeguimiento'])->name('cotizacion-seguimiento.edit');
    Route::put('cotizaciones/update-seguimiento/{id}', [CotizacionController::class, 'updateSeguimiento'])->name('cotizacion-seguimiento.update');
});
