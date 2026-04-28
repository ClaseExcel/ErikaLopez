<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {

    Route::get('informesgerenciales', 'InformesGerencialesController@index')->name('informesgerenciales.index');

    //generar-informe
    Route::get('informesgerenciales/generar-informe', 'InformesGerencialesController@generarInforme')->name('informesgerenciales.generar-informe');

    //mejorar texto IA
    Route::post('imformesgerenciales/enhancetext', 'InformesGerencialesController@enhanceText')->name('informesgerenciales.enhanceText');
    //spellingChecker
    Route::post('imformesgerenciales/spellingchecker', 'InformesGerencialesController@spellingChecker')->name('informesgerenciales.spellingChecker');

    //guardar-historial-informe
    Route::post('informesgerenciales/guardar-historial', 'InformesGerencialesController@guardarHistorialInforme')->name('informesgerenciales.guardar-historial');

    //cargar-historial-informe
    Route::post('informesgerenciales/cargar-historial', 'InformesGerencialesController@cargarHistorialInforme')->name('informesgerenciales.cargar-historial');

});
