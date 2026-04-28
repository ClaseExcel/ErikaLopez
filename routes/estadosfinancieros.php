<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::post('guardar-orden/guardarorden', 'EstadosFinancierosController@guardarOrden')->name('estadosfinancieros.guardar_orden');
    Route::get('estadosfinancieros/obtener-orden/{compania}', 'EstadosFinancierosController@obtenerOrdenPersonalizado');
    Route::post('estadosfinancieros/fgeneral', 'EstadosFinancierosController@fgeneral')->name('estadosfinancieros.fgeneral');
    Route::get('estadosfinancieros/centro-costo/{compania}', 'EstadosFinancierosController@findCentroCosto')->name('estadosfinancieros.findCentroCosto');
    Route::get('estadosfinancieros/impuesto', 'EstadosFinancierosController@impuesto')->name('estadosfinancieros.impuesto');
    Route::get('estadosfinancieros/createimpuesto', 'EstadosFinancierosController@createimpuesto')->name('estadosfinancieros.createimpuesto');
    Route::post('estadosfinancieros/guardarimpuesto', 'EstadosFinancierosController@guardarimpuesto')->name('estadosfinancieros.guardarimpuesto');
    Route::get('impuesto/{id}/edit', 'EstadosFinancierosController@editarimpuesto')->name('estadosfinancieros.editarimpuesto');
    Route::put('impuesto/{id}', 'EstadosFinancierosController@updateimpuesto')->name('estadosfinancieros.updateimpuesto');
    Route::delete('impuesto/{id}', 'EstadosFinancierosController@impuestodestroy')->name('estadosfinancieros.impuestodestroy');
    Route::post('estadosfinancieros/pdf', 'EstadosFinancierosController@pdfestadoresultado')->name('estadosfinancieros.pdfestadoresultado');
    Route::post('estadosfinancieros/pdfgeneral', 'EstadosFinancierosController@pdfgeneral')->name('estadosfinancieros.pdfgeneral');
    Route::post('estadosfinancieros/pdfmesames', 'EstadosFinancierosController@pdfmesames')->name('estadosfinancieros.pdfmesames');
    Route::resource('estadosfinancieros', 'EstadosFinancierosController');
    Route::post('estadosfinancieros/grafico', 'EstadosFinancierosController@graficoEstadosFinancieros')->name('estadosfinancieros.grafico');
    Route::post('estadosfinancieros/consultaporcuenta', 'EstadosFinancierosController@consultaporcuenta')->name('estadosfinancieros.consultaporcuenta');
    Route::get('estadosfinancieros/otros-ingresos/{nit}/{fecha}', 'EstadosFinancierosController@consultarOtrosIngresos')->name('otros-ingresos');
    Route::get('estadosfinancieros/otros-egresos/{nit}/{fecha}', 'EstadosFinancierosController@consultarOtrosEgresos')->name('otros-egresos');
    Route::post('estadosfinancieros/pdfcambiopatrimonio', 'EstadosFinancierosController@pdfestadocambiopatrimonio')->name('estadosfinancieros.pdfcambiopatrimonio');
    Route::post('estadosfinancieros/validar/{nit}/{fecha}', 'EstadosFinancierosController@validarTodasNotas')
    ->name('estadosfinancieros.validar');
    Route::post('estadosfinancieros/validar/comparacion', 'EstadosFinancierosController@validarComparacion')
    ->name('estadosfinancieros.comparacion');
    ///excel estado de resultados
    Route::post('/exportar-estado-resultados','EstadosFinancierosController@exportEstadoResultados')->name('export.estado.resultados');

    // Exportar metas a Excel
    Route::post('estadosfinancieros/export-metas', 'EstadosFinancierosController@exportMetas')
    ->name('estadosfinancieros.exportMetas');
    // Exportar metas a PDF
    Route::post('estadosfinancieros/export-metas-pdf', 'EstadosFinancierosController@exportMetasPdf')
    ->name('estadosfinancieros.exportMetasPdf');
});