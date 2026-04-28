@extends('layouts.admin')
@section('title', 'Ver actividad cliente')
@section('content')

    <div class="form-group">
        <a class="btn btn-back  border btn-radius px-4" href="{{ route('admin.actividad_cliente.index') }}">
            <i class="fas fa-arrow-circle-left"></i> Atrás
        </a>

        @if (
            $actividadCliente->reporte_actividades->estado_actividad_id == 4 ||
                $actividadCliente->reporte_actividades->estado_actividad_id == 7 ||
                $actividadCliente->reporte_actividades->estado_actividad_id == 8)
        @else
            @if ($actividadCliente->usuario_id == Auth::user()->id)
                <a class="btn btn-back  border btn-radius px-4"
                    href="{{ route('admin.reporte.index', $actividadCliente->id) }}">
                    <i class="fas fa-file-alt"></i> Reportar avance
                </a>
            @elseif(Auth::user()->role_id == 1)
                <a class="btn btn-back  border btn-radius px-4"
                    href="{{ route('admin.reporte.index', $actividadCliente->id) }}">
                    <i class="fas fa-file-alt"></i> Reportar avance
                </a>
            @endif
        @endif
    </div>


    <div class="row mb-2">
        <div class="col-12 col-md-5">
            <div class="card">
                <div class="card-header bg-transparent">
                    <div class="row d-flex justify-content-between">
                        <div class="col">
                            <span class="text-dark fs-5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-journal-text mb-1 fw-bold" viewBox="0 0 16 16">
                                    <path
                                        d="M5 10.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5m0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5" />
                                    <path
                                        d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2" />
                                    <path
                                        d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z" />
                                </svg>
                                Resumen de la actividad
                            </span>
                        </div>
                        <div class="col text-end">
                            <span class="text-dark badge fw-normal rounded-pill px-3 py-2 "
                                style="font-size: 13px; background-color:rgb(255, 249, 180)">
                                Vencimiento:
                                &nbsp;{{ Carbon\Carbon::parse($actividadCliente->fecha_vencimiento)->isoFormat('DD/MM/YYYY') }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <span class="p-0">
                        {{ $actividadCliente->nombre }}
                    </span><br>

                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-12 col-md-7">
            <div class="card pb-4" style="height: 300px">
                <div class="card-header fs-5 bg-transparent border-0 text-dark">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-menu-up" viewBox="0 0 16 16">
                        <path
                            d="M7.646 15.854a.5.5 0 0 0 .708 0L10.207 14H14a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h3.793zM1 9V6h14v3zm14 1v2a1 1 0 0 1-1 1h-3.793a1 1 0 0 0-.707.293l-1.5 1.5-1.5-1.5A1 1 0 0 0 5.793 13H2a1 1 0 0 1-1-1v-2zm0-5H1V3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1zM2 11.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 0-1h-8a.5.5 0 0 0-.5.5m0-4a.5.5 0 0 0 .5.5h11a.5.5 0 0 0 0-1h-11a.5.5 0 0 0-.5.5m0-4a.5.5 0 0 0 .5.5h6a.5.5 0 0 0 0-1h-6a.5.5 0 0 0-.5.5" />
                    </svg>
                    Detalles de la actividad
                </div>
                <style>
                    .max-text-resume {
                        height: 280px;
                        overflow-y: auto;
                        overflow-x: hidden;
                        scroll-behavior: smooth;
                        /* cambiar color scroll bar a azul */
                        scrollbar-width: thin;
                    }
                </style>

                <table class="table table-sm max-text-resume">
                    <tbody>
                        <tr>
                            <td class="pl-3" style="width:230px">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                    class="bi bi-building mb-1 text-secondary" viewBox="0 0 16 16">
                                    <path
                                        d="M4 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zM4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM7.5 5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM4.5 8a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5z" />
                                    <path
                                        d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1zm11 0H3v14h3v-2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V15h3z" />
                                </svg>
                                Empresa
                            </td>
                            <td>{{ $actividadCliente->cliente->razon_social }}</td>
                        </tr>
                        @if ($actividadCliente->empresa_asociada_id)
                            <tr>
                                <td class="pl-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        fill="currentColor" class="bi bi-building-add mb-1 text-secondary"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0" />
                                        <path
                                            d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6.5a.5.5 0 0 1-1 0V1H3v14h3v-2.5a.5.5 0 0 1 .5-.5H8v4H3a1 1 0 0 1-1-1z" />
                                        <path
                                            d="M4.5 2a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm-6 3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm-6 3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5z" />
                                    </svg>
                                    Cliente
                                </td>
                                <td>{{ $actividadCliente->empresa_asociada->razon_social }} </td>
                            </tr>
                        @endif
                        <tr>
                            <td class="pl-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                    class="bi bi-person-bounding-box mb-1 text-secondary" viewBox="0 0 16 16">
                                    <path
                                        d="M9.05.435c-.58-.58-1.52-.58-2.1 0L.436 6.95c-.58.58-.58 1.519 0 2.098l6.516 6.516c.58.58 1.519.58 2.098 0l6.516-6.516c.58-.58.58-1.519 0-2.098zM8 .989c.127 0 .253.049.35.145l6.516 6.516a.495.495 0 0 1 0 .7L8.35 14.866a.5.5 0 0 1-.35.145z" />
                                </svg>
                                Tipo
                            </td>
                            <td>
                                {{ $actividadCliente->responsable->nombre }}
                            </td>
                        </tr>
                        <tr>
                            <td class="pl-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                    class="bi bi-person-bounding-box mb-1 text-secondary" viewBox="0 0 16 16">
                                    <path
                                        d="M1.5 1a.5.5 0 0 0-.5.5v3a.5.5 0 0 1-1 0v-3A1.5 1.5 0 0 1 1.5 0h3a.5.5 0 0 1 0 1zM11 .5a.5.5 0 0 1 .5-.5h3A1.5 1.5 0 0 1 16 1.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 1-.5-.5M.5 11a.5.5 0 0 1 .5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 1 0 1h-3A1.5 1.5 0 0 1 0 14.5v-3a.5.5 0 0 1 .5-.5m15 0a.5.5 0 0 1 .5.5v3a1.5 1.5 0 0 1-1.5 1.5h-3a.5.5 0 0 1 0-1h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 1 .5-.5" />
                                    <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm8-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                </svg>
                                Creador
                            </td>
                            <td>
                                {{ $actividadCliente->usuario_crea_act->nombres . ' ' . $actividadCliente->usuario_crea_act->apellidos }}
                            </td>
                        </tr>
                        <tr>
                            <td class="pl-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                    class="bi bi-person-bounding-box mb-1 text-secondary" viewBox="0 0 16 16">
                                    <path
                                        d="M1.5 1a.5.5 0 0 0-.5.5v3a.5.5 0 0 1-1 0v-3A1.5 1.5 0 0 1 1.5 0h3a.5.5 0 0 1 0 1zM11 .5a.5.5 0 0 1 .5-.5h3A1.5 1.5 0 0 1 16 1.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 1-.5-.5M.5 11a.5.5 0 0 1 .5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 1 0 1h-3A1.5 1.5 0 0 1 0 14.5v-3a.5.5 0 0 1 .5-.5m15 0a.5.5 0 0 1 .5.5v3a1.5 1.5 0 0 1-1.5 1.5h-3a.5.5 0 0 1 0-1h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 1 .5-.5" />
                                    <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm8-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                </svg>
                                Responsable
                            </td>
                            <td>
                                {{ $actividadCliente->usuario->nombres . ' ' . $actividadCliente->usuario->apellidos }}
                            </td>
                        </tr>
                        <tr>
                            <td class="pl-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                    class="bi bi-megaphone mb-1 text-secondary" viewBox="0 0 16 16">
                                    <path
                                        d="M13 2.5a1.5 1.5 0 0 1 3 0v11a1.5 1.5 0 0 1-3 0v-.214c-2.162-1.241-4.49-1.843-6.912-2.083l.405 2.712A1 1 0 0 1 5.51 15.1h-.548a1 1 0 0 1-.916-.599l-1.85-3.49-.202-.003A2.014 2.014 0 0 1 0 9V7a2.02 2.02 0 0 1 1.992-2.013 75 75 0 0 0 2.483-.075c3.043-.154 6.148-.849 8.525-2.199zm1 0v11a.5.5 0 0 0 1 0v-11a.5.5 0 0 0-1 0m-1 1.35c-2.344 1.205-5.209 1.842-8 2.033v4.233q.27.015.537.036c2.568.189 5.093.744 7.463 1.993zm-9 6.215v-4.13a95 95 0 0 1-1.992.052A1.02 1.02 0 0 0 1 7v2c0 .55.448 1.002 1.006 1.009A61 61 0 0 1 4 10.065m-.657.975 1.609 3.037.01.024h.548l-.002-.014-.443-2.966a68 68 0 0 0-1.722-.082z" />
                                </svg>
                                Reporte actividad
                            </td>
                            <td>
                                {{ $reporteActividad->estado_actividades->nombre }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- NOTA --}}
        <div class="col-12 col-md-5 mb-4" style="height: 300px">
            <style>
                .max-text-tab {
                    height: 225px;
                    overflow-y: auto;
                    overflow-x: hidden;
                    scroll-behavior: smooth;
                    /* cambiar color scroll bar a azul */
                    scrollbar-width: thin;
                }
            </style>

            <div style="height: 100%">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="videocalls">
                        <a class="nav-link active" id="nota-tab" data-bs-toggle="tab" href="#nota" role="tab"
                            aria-controls="nota" aria-selected="true"><i class="far fa-sticky-note"></i> Nota</a>
                    </li>
                    <li class="nav-item" role="videocalls">
                        <a class="nav-link" id="justificacion-tab" data-bs-toggle="tab" href="#justificacion"
                            role="tab" aria-controls="justificacion" aria-selected="false"><i
                                class="fas fa-stream"></i> Justificación</a>
                    </li>
                </ul>

                <div class="tab-content bg-white px-3 pt-3 border border-top-0 border-bottom-0" id="myTabContent">

                    <div class="tab-pane fade show active max-text-tab" id="nota" role="tabpanel"
                        aria-labelledby="nota-tab">
                        {!! nl2br($actividadCliente->nota) != ''
                            ? nl2br($actividadCliente->nota)
                            : '<i class="fas fa-info-circle text-secondary"></i> <i class="text-secondary text-sm">No hay ninguna nota disponible.</i>' !!}
                    </div>

                    <div class="tab-pane fade max-text-tab" id="justificacion" role="tabpanel"
                        aria-labelledby="justificacion-tab">
                        @foreach ($seguimientos as $seguimiento)
                            <span class="text-secondary">{{ $seguimiento->time . ' - ' . $seguimiento->estado }}</span>
                            <span class="text-bold">{{ $seguimiento->user }}:</span>
                            {!! $seguimiento != ''
                                ? nl2br($seguimiento->descripcion)
                                : '<i class="fas fa-info-circle text-secondary"></i> <i class="text-secondary text-sm">No hay ninguna justificación disponible.</i>' !!} <br>
                        @endforeach
                    </div>
                </div>

                <div class="card-footer bg-white border border-top-0 " style="border-radius: 0px 0px 15px 15px"></div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Progreso chart --}}
        <div class="col-12 col-md-5">
            <div class="card" style="height:300px">
                <div class="card-body d-flex justify-content-center">
                    <canvas id="progesoChart"></canvas>
                </div>
                <div class="card-footer bg-transparent text-center fs-6" style="border-radius: 0px 0px 15px 15px">
                    <i class="fas fa-forward text-success"></i>
                    Progreso
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <table class="table table-sm">
                <tbody>
                    <tr>
                        <td class="border-0" style="width:50%;">
                            <div class="card" style="height:135px">
                                <div class="card-body text-center fs-2 text-secondary" style="font-weight:400">
                                    {{ $actividadCliente->prioridad }}
                                </div>
                                <div class="card-footer bg-transparent text-center"
                                    style="border-radius: 0px 0px 15px 15px">
                                    <i class="fas fa-exclamation-triangle text-danger"></i> Prioridad
                                </div>
                            </div>
                        </td>
                        <td class="border-0" style="width:50%;">
                            <div class="card" style="height:135px">
                                <div class="card-body text-center fs-2 text-secondary" style="font-weight:400">
                                    {!! $actividadCliente->periodicidad != ''
                                        ? $actividadCliente->periodicidad
                                        : '<small class="text-secondary">―</small>' !!}
                                </div>
                                <div class="card-footer bg-transparent text-center"
                                    style="border-radius: 0px 0px 15px 15px">
                                    <i class="far fa-calendar-alt text-info"></i> Periodicidad
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="border-0" style="width:50%;">
                            <div class="card" style="height:135px">
                                <div class="card-body text-center fs-2 text-secondary" style="font-weight:400">
                                    {{ $actividadCliente->recordatorio }}
                                </div>
                                <div class="card-footer bg-transparent text-center"
                                    style="border-radius: 0px 0px 15px 15px">
                                    <i class="far fa-bell text-primary"></i> Cantidad de recordatorios
                                </div>
                            </div>
                        </td>
                        <td class="border-0" style="width:50%;">
                            <div class="card" style="height:135px">
                                <div class="card-body text-center fs-2 text-secondary" style="font-weight:400">
                                    {{ $actividadCliente->recordatorio_distancia }}
                                </div>
                                <div class="card-footer bg-transparent text-center"
                                    style="border-radius: 0px 0px 15px 15px">
                                    <i class="fas fa-sun text-warning"></i> Días entre recordatorios
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        {{-- Documentos adjuntos --}}
        @if ($docList)
            <div class="col-12 col-md-5 mb-5">
                <div class="card">
                    <div class="card-header text-center bg-transparent text-dark fs-6">
                        <i class="fas fa-paperclip"></i> Documentos adjuntos
                    </div>
                    <table class="table table-hover table-sm">
                        <tbody>
                            @foreach ($docList as $key => $docName)
                                <tr>
                                    <td class="pl-4 border border-top-0 border-left-0 border-right-0">
                                        {{ basename($docName) }}
                                    </td>
                                    <td class=" border border-top-0 border-left-0 border-right-0 text-center">

                                        {{-- Descarga directa si la extensión es zip o rar --}}
                                        @if (pathinfo($docName, PATHINFO_EXTENSION) == 'zip' ||
                                                pathinfo($docName, PATHINFO_EXTENSION) == 'rar' ||
                                                pathinfo($docName, PATHINFO_EXTENSION) == 'xlsx' ||
                                                pathinfo($docName, PATHINFO_EXTENSION) == 'xls')
                                            <a type="button" class="btn-ver px-3" href="{{ asset($docName) }}"
                                                download>
                                                <i class="fas fa-download"></i>
                                            </a>
                                        @else
                                            <!-- Button trigger modal -->
                                            <a type="button" class="btn-ver px-3" data-bs-toggle="modal"
                                                data-bs-target="#exampleModal{{ $key }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endif


                                    </td>
                                </tr>
                                <!-- Modal -->
                                <div class="modal fade" id="exampleModal{{ $key }}" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Documento
                                                    {{ basename($docName) }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-0 d-flex justify-content-center">

                                                @if (pathinfo($docName, PATHINFO_EXTENSION) == 'pdf')
                                                    <iframe src="{{ asset($docName) }}" style="width:100%;height:637px;"
                                                        frameborder="0">
                                                    </iframe>
                                                @else
                                                    <img src="{{ asset($docName) }}" alt="" width="100%"
                                                        style="max-width: 750px;"">
                                                @endif

                                            </div>
                                            <div class="modal-footer border-0">
                                                <a class="btn btn-save text-white shadow-none mx-auto"
                                                    href="{{ asset($docName) }}" download>
                                                    <i class="fas fa-arrow-alt-circle-down"></i>
                                                    Descargar
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="card-footer bg-transparent border-0">

                    </div>
                </div>
            </div>
        @endif
    </div>








@endsection
@section('scripts')
    @parent
    <script>
        function toggleDiv(divId) {
            var div = document.getElementById(divId);

            if (div.style.display === "none") {
                div.style.display = "block";
            } else {
                div.style.display = "none";
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let progreso = {{ $actividadCliente->progreso }};
        let restante = 100 - progreso;

        const ctx = document.getElementById('progesoChart');
        const data = {
            labels: [
                'Progreso' + ' ' + progreso + '%',
                'Restante' + ' ' + restante + '%',
            ],
            datasets: [{
                data: [progreso, restante],
                backgroundColor: [
                    'rgb(26 219 154 / 70%)',
                    'rgba(195, 64, 64, 0.3  )',
                ],
                hoverOffset: 2
                // borderWidth: 0,
            }]
        };

        new Chart(ctx, {
            type: 'doughnut',
            data: data,
            options: {
                //modificar altura y ancho del grafico
                responsive: true,
                maintainAspectRatio: false,
                elements: {
                    arc: {
                        borderWidth: 0
                    }
                },
                aspectRatio: 2.8,
            }
        });
    </script>
@endsection
