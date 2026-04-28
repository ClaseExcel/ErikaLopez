<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    // *** Cuenta contable
    Route::post('get_movimientos', 'ModificacionesController@getMovimientos');
    // *** Modificaciones
    Route::resource('modificaciones', 'ModificacionesController');
});
