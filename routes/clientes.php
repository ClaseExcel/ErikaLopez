<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::post('/clientes/delete', 'ClientesController@delete')->name('clientes.delete');
    Route::get('clientes/siigoweb', 'ClientesController@siigoweb')->name('clientes.siigoweb');
    Route::get('clientes/fechas-existentes', 'ClientesController@fechasExistentes')->name('clientes.existentes');
    Route::get('clientes/fechas-existentes/data', 'ClientesController@fechasExistentesData')->name('clientes.existentes.data');
    // Route::get('clientes/fechasexistentes', 'ClientesController@showFechasExistentes')->name('clientes.fechasexistentes');
    Route::resource('clientes', 'ClientesController');

    //movimientos
    Route::post('/movimientos/delete', 'ClientesMovimientosController@delete')->name('movimientos.delete');
    Route::get('movimientos/msiigoweb', 'ClientesMovimientosController@msiigoweb')->name('movimientos.msiigoweb');
    // Route::get('movimientos/fechasexistentes', 'ClientesMovimientosController@showFechasExistentes')->name('movimientos.fechasexistentes');
    Route::get('movimientos/descargar-formato-balance', 'ClientesMovimientosController@descargarFormato')->name('movimientos.balance');
    Route::resource('movimientos', 'ClientesMovimientosController');
});