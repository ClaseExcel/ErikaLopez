<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {

    Route::get('calendario-index', 'CalendarioActividadesController@index')->name('calendario-actividades');
    Route::get('calendario-index/events', 'CalendarioActividadesController@events')->name('events');
    Route::post('calendario-index/filtro-cliente', [CalendarioActividadesController::class, 'filtroCliente'])->name('filtro.cliente');
    Route::post('calendario-index/correo-notificacion', [CalendarioActividadesController::class, 'notificarCorreo'])->name('calendario-actividades.correo');
});
