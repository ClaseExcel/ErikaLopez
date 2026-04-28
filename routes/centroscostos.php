<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    // *** Centros de costos
    Route::resource('centros_costos', 'CentrosCostosController');
});
