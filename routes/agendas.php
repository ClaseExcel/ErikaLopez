<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    //Agenda
    Route::get('agenda/EmpleadoCliente/{id}/{agenda}', [AgendaController::class, 'empleadoCliente'])->name('empleadoCliente');
    Route::get('agenda/citas/{id}', [AgendaController::class, 'cancelarCita'])->name('cancelarCita');
    Route::get('agenda/estados/{id}', [AgendaController::class, 'estadoCitas'])->name('estadocitas');
    Route::put('agenda/estadosupdate/{id}', [AgendaController::class, 'estadosUpdate'])->name('agenda.estadosupdate');
    Route::resource('agenda', AgendaController::class)->names('agendas');
    Route::resource('citas-agenda', CitasAgendaController::class)->names('citas.agenda')->except('destroy', 'index', 'create', 'store', 'edit', 'update');
    Route::post('citas-agenda/filtro-agenda', [CitasAgendaController::class, 'filtroAgenda'])->name('filtroAgenda');

    //Citas
    Route::resource('citas', CitaController::class)->names('citas')->except('create', 'show');
});
