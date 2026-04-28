<?php

use App\Http\Controllers\Admin\CalendarioTributarioController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {

Route::get('calendariotributario/table', [CalendarioTributarioController::class, 'table'])->name('calendario.table');
Route::get('calendariotributario/index', [CalendarioTributarioController::class, 'index'])->name('calendario.index');
Route::get('calendariotributario/create', [CalendarioTributarioController::class, 'create'])->name('calendario.create');
Route::post('calendariotributario/masivo', [CalendarioTributarioController::class, 'masivo'])->name('calendario.masivo');
Route::post('calendariotributario/calendarioobligaciones', [CalendarioTributarioController::class, 'calendarioobligaciones'])->name('calendario.calendarioobligaciones');
Route::post('calendariotributario/exportExcelActualizarcalendario', [CalendarioTributarioController::class, 'exportExcelActualizarcalendario'])->name('calendario.exportExcelActualizarcalendario');
Route::post('calendariotributario/marcar-revisado', [CalendarioTributarioController::class, 'marcarRevisado'])->name('marcar.revisado');
Route::post('calendariotributario/marcar-revisado2', [CalendarioTributarioController::class, 'marcarRevisado2'])->name('marcar2.revisado2');
Route::post('calendariotributario/marcar-revisado3', [CalendarioTributarioController::class, 'marcarRevisado3'])->name('marcar3.revisado3');
Route::post('calendariotributario/correo', [CalendarioTributarioController::class, 'Correonotificaciontb'])->name('calendariotb.correo');
Route::get('calendariotributario/notificacion', [CalendarioTributarioController::class, 'Notificaciontable'])->name('calendario.notificacion');
Route::post('calendariotributario/notificacionwhatsapp', [CalendarioTributarioController::class, 'Notificacionwhatsapp'])->name('calendario.notificacionwhatsapp');
Route::get('calendariotributario/notificacionrevisoria/{nombre}/{id}/{empresa}/{fecha}', [CalendarioTributarioController::class, 'Notificacionrevisoria'])->name('calendario.notificacionrevisoria');
Route::post('calendario/descargarPDF', [CalendarioTributarioController::class, 'descargarPDF'])->name('calendario.descargarPDF');
Route::post('calendario/correofechas', [CalendarioTributarioController::class, 'correofechas'])->name('calendario.correofechas');
Route::post('calendario/descargarPDFCompleto', [CalendarioTributarioController::class, 'descargarPDFCompleto'])->name('calendario.descargarPDFCompleto');
Route::post('calendario/descargarExcel', [CalendarioTributarioController::class, 'descargarExcel'])->name('calendario.descargarExcel');
});