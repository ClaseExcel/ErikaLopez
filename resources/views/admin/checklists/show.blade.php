@extends('layouts.admin')
@section('title', 'Info: ' . $checklist->empresa->razon_social)
@section('library')
    @include('cdn.datatables-head')
@endsection
@section('content')
    <style>
        .maxt-text-resume {
            max-height: 585px;
            overflow-y: auto;
            overflow-x: auto;
            scroll-behavior: smooth;
            scrollbar-width: thin;
            /* Separar el scroll a la izquierda */
            padding-right: 16px;
        }
    </style>

    <div class="form-group">
        <a class="btn  btn-back border btn-radius px-4" href="{{ route('admin.checklist_empresas.index') }}">
            <i class="fas fa-arrow-circle-left"></i> Atrás
        </a>

        <a class="btn  btn-back border btn-radius px-4" data-bs-toggle="modal" data-bs-target="#modalFiltro"
            title="Filtrar actividades"><i class="fa-solid fa-filter"></i> Filtrar actividades</a>
    </div>

    @include('admin.checklists.modal-filtro')

    <div class="row">
        <div class="col-xl-7">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <span class="fs-4">{{ $checklist->empresa->razon_social }}</span><br>
                            <small
                                class="fst-italic text-help">{{ $checklist->user_act->nombres . ' ' . $checklist->user_act->apellidos }}</small>
                        </div>
                        <div class="col-4 text-end">
                            <span class=" badge rounded-pill alert alert-info" role="alert">{{ $checklist->año }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table" id="example">
                        <thead>
                            <tr>
                                <th class="text-help" width="70%">Actividades a realizar</th>
                                <th class="text-help"> Estado </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($actividades as $actividad)
                                <tr>
                                    <td><small>{{ $actividad->nombre }}</small></td>
                                    <td class="align-middle">
                                        <div class="progress rounded" role="progressbar"
                                            aria-label="Default striped example"
                                            aria-valuenow="{{ $porcentaje[$actividad->id] }}" aria-valuemin="0"
                                            aria-valuemax="100">
                                            <div class="progress-bar progress-bar-striped  bg-success"
                                                style="width:{{ $porcentaje[$actividad->id] }}%">
                                                <small>{{ $porcentaje[$actividad->id] }}%</small>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-5">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="text-uppercase fw-bold"><i class="fa-solid fa-file-lines"></i> Seguimientos</span>
                </div>
                @if ($seguimientos->isEmpty())
                    <div class="card-body">
                        <p class="text-center text-secondary fst-italic">No hay seguimientos registrados para este
                            checklist.</p>
                    </div>
                @else
                    <div class="card-body maxt-text-resume">
                        @foreach ($seguimientos as $seguimiento)
                            <div class="col-12 mb-3 mt-2">

                                @if (request()->routeIs('admin.filtro-actividades'))
                                    @foreach ($actividades as $presentado)
                                        @if (in_array($presentado->id, json_decode($seguimiento->actividades_presentadas, true)))
                                            <div class="mb-2">
                                                <span class="text-help text-uppercase fw-semibold">Mes realizado</span>
                                                &nbsp; <span
                                                    class="fst-italic text-uppercase">{{ \Carbon\Carbon::parse($seguimiento->mes)->locale('es')->isoFormat('MMMM') }}
                                                </span>
                                                <a class="btn-editar px-2 py-0  "
                                                    href="{{ route('admin.seguimiento_checklist.edit', [$seguimiento->id]) }}"
                                                    title="Editar registro"><i class="fas fa-pencil-alt"></i></a>
                                            </div>
                                            @break
                                        @endif
                                    @endforeach
                                @else
                                    <div class="mb-2">
                                        <span class="text-help text-uppercase fw-semibold">Mes realizado</span> &nbsp; <span
                                            class="fst-italic text-uppercase">{{ \Carbon\Carbon::parse($seguimiento->mes)->locale('es')->isoFormat('MMMM') }}
                                        </span>
                                        <a class="btn-editar px-2 py-0  "
                                            href="{{ route('admin.seguimiento_checklist.edit', [$seguimiento->id]) }}"
                                            title="Editar registro"><i class="fas fa-pencil-alt"></i></a>
                                    </div>
                                @endif


                                <table class="table border-none">
                                    <tbody>
                                        @foreach ($actividades as $presentado)
                                            @if (
                                                $seguimiento->actividades_presentadas &&
                                                    $seguimiento->actividades_presentadas != 'null' &&
                                                    in_array($presentado->id, json_decode($seguimiento->actividades_presentadas, true)))
                                                <tr>
                                                    <th class="table-primary" style="border-top: 1px solid #ffffff;">
                                                        <span
                                                            class="fw-semibold fs-6 text-help">{{ $presentado->nombre }}</span>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    @foreach (json_decode($seguimiento->observaciones) as $key => $value)
                                                        @if ($key == $presentado->id)
                                                            @if ($value)
                                                                <td style="border-bottom: 1px solid #bfbfbf;">
                                                                    {{ $value }}
                                                                </td>
                                                            @endif
                                                        @endif
                                                    @endforeach

                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let table;
        // Initialize DataTable with Spanish language support and other configurations
        document.addEventListener("DOMContentLoaded", function() {
            table = new DataTable('#example', {
                language: {
                    url: "{{ asset('/js/datatable/Spanish.json') }}",
                },
                scrollY: 440,
                ordering: false,
                paging: false,
                searching: false,
                info: false,
                responsive: true,
            });

        });
    </script>
@endsection
