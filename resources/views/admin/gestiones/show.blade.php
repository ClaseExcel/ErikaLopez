@extends('layouts.admin')
@section('title', 'Ver gestión')
@section('content')

    <style>
        .accordion-button:not(.collapsed) {
            color: #ffffff !important;
            background-color: #232222 !important;
        }

        .max-resume {
            max-height: 300px;
            overflow-y: auto;
            scroll-behavior: smooth;
        }
    </style>

    <div class="form-group">
        <a class="btn btn-back  border btn-radius px-4" href="{{ route('admin.gestiones.index') }}">
            <i class="fas fa-arrow-circle-left"></i> Atrás
        </a>
    </div>

    <div class="row mb-3">
        <div class="col-xl-3">
            <div class="card text-center">
                <div class="card-body">
                    <span class="fs-6"> <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1" stroke="currentColor" class="mb-1" width="30" style="color: #232222;">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6.75 2.994v2.25m10.5-2.25v2.25m-14.252 13.5V7.491a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v11.251m-18 0a2.25 2.25 0 0 0 2.25 2.25h13.5a2.25 2.25 0 0 0 2.25-2.25m-18 0v-7.5a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v7.5m-6.75-6h2.25m-9 2.25h4.5m.002-2.25h.005v.006H12v-.006Zm-.001 4.5h.006v.006h-.006v-.005Zm-2.25.001h.005v.006H9.75v-.006Zm-2.25 0h.005v.005h-.006v-.005Zm6.75-2.247h.005v.005h-.005v-.005Zm0 2.247h.006v.006h-.006v-.006Zm2.25-2.248h.006V15H16.5v-.005Z" />
                        </svg>
                        {{ \Carbon\Carbon::parse($gestion->updated_at)->format('d-m-Y') }}</span>
                </div>
                <div class="card-footer bg-help" style="border-radius: 0px 0px 20px 20px;">
                    Última actualización
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card text-center">
                <div class="card-body">
                    <span class="fs-6">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                            stroke="currentColor" class="mb-1" width="25" style="color: #232222;">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>

                        {{ $gestion->usuario_create->nombres . ' ' . $gestion->usuario_create->apellidos }}</span>
                </div>
                <div class="card-footer bg-help" style="border-radius: 0px 0px 20px 20px;">
                    Persona quién creo la gestión
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-10">
            <div class="card">
                <div class="card-body">
                    <span class="fs-5 text-help">Resumen de la Gestión #{{ $gestion->id }}</span><br><br>

                    <table style="width: 100%">
                        <tr>
                            <th> Fecha de la gestión</th>
                            <td class="text-end">{{ $gestion->fecha_visita }}</td>
                        </tr>
                        <tr>
                            <th> Cliente</th>
                            <td class="text-end">{{ $gestion->cliente->razon_social }}</td>
                        </tr>
                        <tr>
                            <th>Tipo de gestión</th>
                            <td class="text-end">{{ $gestion->tipo_visita }}</td>
                        </tr>
                        <tr>
                            <th> Asistentes Asercont</th>
                            <td class="text-end">{{ $gestion->asistentes_help }}</td>
                        </tr>
                        <tr>
                            <th> Asistentes por parte del cliente</th>
                            <td class="text-end">{{ $gestion->asistentes_cliente }}</td>
                        </tr>
                    </table>
                </div>

                <div class="card-body">
                    <div class="accordion" id="accordionDetalle">
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header border-bottom" id="headingOneDetalle">
                                <button class="accordion-button collapsed shadow-none fw-bold"
                                    style="background-color:#00205C85;color:#232222;" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseOneDetalle" aria-expanded="false"
                                    style="font-size: 17px" aria-controls="collapseOneDetalle">
                                    Detalle de la gestión
                                </button>
                            </h2>

                            <div id="collapseOneDetalle" class="accordion-collapse collapse py-3 max-resume"
                                aria-labelledby="headingOneDetalle" data-bs-parent="#accordionDetalle">
                                <span class="px-3">
                                    {!! $gestion->detalle_visita !!}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="accordion" id="accordionObservaciones">
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header border-bottom" id="headingOneObservaciones">
                                <button class="accordion-button collapsed shadow-none fw-bold"
                                    style="background-color:#00205C85;color:#232222;" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseOneObservaciones"
                                    aria-expanded="false" style="font-size: 17px" aria-controls="collapseOneObservaciones">
                                    Observaciones de la gestión
                                </button>
                            </h2>

                            <div id="collapseOneObservaciones" class="accordion-collapse collapse py-3 max-resume"
                                aria-labelledby="headingOneObservaciones" data-bs-parent="#accordionObservaciones">
                                <span class="px-3">
                                    {!! $gestion->hallazgos !!}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="accordion" id="accordionMundial">
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header border-bottom" id="headingOneMundial">
                                <button class="accordion-button collapsed shadow-none fw-bold"
                                    style="background-color:#00205C85;color:#232222;" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseOneMundial" aria-expanded="false"
                                    style="font-size: 17px" aria-controls="collapseOneMundial">
                                    Compromisos de Erika Lopez para la próxima gestión
                                </button>
                            </h2>

                            <div id="collapseOneMundial" class="accordion-collapse collapse py-3 max-resume"
                                aria-labelledby="headingOneMundial" data-bs-parent="#accordionMundial">
                                <span class="px-3">
                                    {!! $gestion->compromisos !!}
                                    <span class="px-3">
                            </div>
                        </div>
                    </div>

                    <div class="accordion" id="accordionCliente">
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header border-bottom" id="headingOneCliente">
                                <button class="accordion-button collapsed shadow-none fw-bold"
                                    style="background-color:#00205C85;color:#232222;" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseOneCliente" aria-expanded="false"
                                    style="font-size: 17px" aria-controls="collapseOneCliente">
                                    Compromisos por parte del cliente para la próxima gestión
                                </button>
                            </h2>

                            <div id="collapseOneCliente" class="accordion-collapse collapse py-3 max-resume"
                                aria-labelledby="headingOneCliente" data-bs-parent="#accordionCliente">
                                <span class="px-3">
                                    {!! $gestion->compromisos_cliente !!}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="col">
                        {{-- Documentos --}}
                        @if ($gestion->documento_uno != '')
                            <div class="card">
                                @if (pathinfo($gestion->documento_uno, PATHINFO_EXTENSION) == 'pdf')
                                    <button onclick="toggleDiv('documentoDiv1')"
                                        class="btn btn-outline-secondary w-100 border-0 shadow-none pt-2"
                                        style ="border-radius: 20px">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2.1" stroke="currentColor" class="mb-1" width="25">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                        {{ $gestion->documento_uno }}
                                    </button>
                                    <div id="documentoDiv1" style="display:none;">
                                        <div class="card-body">
                                            <iframe
                                                src="{{ asset('storage/gestion/documento_uno/' . $gestion->documento_uno) }}"
                                                style="width:100%; height:475px;" frameborder="0"></iframe>
                                        </div>
                                    </div>
                                @else
                                    <a class="btn btn-outline-secondary w-100 border-0 shadow-none pt-2"
                                        style ="border-radius: 20px"
                                        href="{{ asset('storage/gestion/documento_uno/' . $gestion->documento_uno) }}"
                                        download>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2.1" stroke="currentColor" class="mb-1" width="25">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                        {{ $gestion->documento_uno }}</a>
                                @endif
                            </div>
                        @endif

                        @if ($gestion->documento_dos != '')
                            <div class="card">
                                @if (pathinfo($gestion->documento_dos, PATHINFO_EXTENSION) == 'pdf')
                                    <button onclick="toggleDiv('documentoDiv2')"
                                        class="btn btn-outline-secondary w-100 border-0 shadow-none pt-2"
                                        style ="border-radius: 20px">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2.1" stroke="currentColor" class="mb-1" width="25">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                        {{ $gestion->documento_dos }}
                                    </button>
                                    <div id="documentoDiv2" style="display: none;">
                                        <div class="card-body">
                                            <iframe
                                                src="{{ asset('storage/gestion/documento_dos/' . $gestion->documento_dos) }}"
                                                style="width:100%; height:475px;" frameborder="0"></iframe>
                                        </div>
                                    </div>
                                @else
                                    <a class="btn btn-outline-secondary w-100 border-0 shadow-none pt-2"
                                        style ="border-radius: 20px"
                                        href="{{ asset('storage/gestion/documento_dos/' . $gestion->documento_dos) }}"
                                        download>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2.1" stroke="currentColor" class="mb-1" width="25">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                        {{ $gestion->documento_dos }}</a>
                                @endif
                            </div>
                        @endif


                        @if ($gestion->documento_tres != '')
                            <div class="card">
                                @if (pathinfo($gestion->documento_tres, PATHINFO_EXTENSION) == 'pdf')
                                    <button onclick="toggleDiv('documentoDiv3')"
                                        class="btn btn-outline-secondary w-100 border-0 shadow-none pt-2"
                                        style ="border-radius: 20px">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2.1" stroke="currentColor" class="mb-1" width="25">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                        {{ $gestion->documento_tres }}
                                    </button>
                                    <div id="documentoDiv3" style="display: none;">
                                        <div class="card-body">
                                            <iframe
                                                src="{{ asset('storage/gestion/documento_tres/' . $gestion->documento_tres) }}"
                                                style="width:100%; height:475px;" frameborder="0"></iframe>
                                        </div>
                                    </div>
                                @else
                                    <a class="btn btn-outline-secondary w-100 border-0 shadow-none pt-2"
                                        style ="border-radius: 20px"
                                        href="{{ asset('storage/gestion/documento_tres/' . $gestion->documento_tres) }}"
                                        download>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2.1" stroke="currentColor" class="mb-1" width="25">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                        {{ $gestion->documento_tres }}</a>
                                @endif
                            </div>
                        @endif

                        @if ($gestion->documento_cuatro != '')
                            <div class="card">
                                @if (pathinfo($gestion->documento_cuatro, PATHINFO_EXTENSION) == 'pdf')
                                    <button onclick="toggleDiv('documentoDiv4')"
                                        class="btn btn-outline-secondary w-100 border-0 shadow-none pt-2"
                                        style ="border-radius: 20px">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2.1" stroke="currentColor" class="mb-1" width="25">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                        {{ $gestion->documento_cuatro }}
                                    </button>
                                    <div id="documentoDiv4" style="display: none;">
                                        <div class="card-body">
                                            <iframe
                                                src="{{ asset('storage/gestion/documento_cuatro/' . $gestion->documento_cuatro) }}"
                                                style="width:100%; height:475px;" frameborder="0"></iframe>
                                        </div>
                                    </div>
                                @else
                                    <a class="btn btn-outline-secondary w-100 border-0 shadow-none pt-2"
                                        style ="border-radius: 20px"
                                        href="{{ asset('storage/gestion/documento_cuatro/' . $gestion->documento_cuatro) }}"
                                        download>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2.1" stroke="currentColor" class="mb-1" width="25">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                        {{ $gestion->documento_cuatro }}</a>
                                @endif
                            </div>
                        @endif

                        @if ($gestion->documento_cinco != '')
                            <div class="card">
                                @if (pathinfo($gestion->documento_cinco, PATHINFO_EXTENSION) == 'pdf')
                                    <button onclick="toggleDiv('documentoDiv5')"
                                        class="btn btn-outline-secondary w-100 border-0 shadow-none pt-2"
                                        style ="border-radius: 20px">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2.1" stroke="currentColor" class="mb-1" width="25">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                        {{ $gestion->documento_cinco }}
                                    </button>
                                    <div id="documentoDiv5" style="display: none;">
                                        <div class="card-body">
                                            <iframe
                                                src="{{ asset('storage/gestion/documento_cinco/' . $gestion->documento_cinco) }}"
                                                style="width:100%; height:475px;" frameborder="0"></iframe>
                                        </div>
                                    </div>
                                @else
                                    <a class="btn btn-outline-secondary w-100 border-0 shadow-none pt-2"
                                        style ="border-radius: 20px"
                                        href="{{ asset('storage/gestion/documento_cinco/' . $gestion->documento_cinco) }}"
                                        download>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2.1" stroke="currentColor" class="mb-1" width="25">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                        {{ $gestion->documento_cinco }}</a>
                                @endif
                            </div>
                        @endif

                        @if ($gestion->documento_seis != '')
                            <div class="card">
                                @if (pathinfo($gestion->documento_seis, PATHINFO_EXTENSION) == 'pdf')
                                    <button onclick="toggleDiv('documentoDiv6')"
                                        class="btn btn-outline-secondary w-100 border-0 shadow-none pt-2"
                                        style="border-radius: 20px">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2.1" stroke="currentColor" class="mb-1" width="25">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                        {{ $gestion->documento_seis }}
                                    </button>
                                    <div id="documentoDiv6" style="display: none;">
                                        <div class="card-body">
                                            <iframe
                                                src="{{ asset('storage/gestion/documento_seis/' . $gestion->documento_seis) }}"
                                                style="width:100%; height:475px;" frameborder="0"></iframe>
                                        </div>
                                    </div>
                                @else
                                    <a class="btn btn-outline-secondary w-100 border-0 shadow-none pt-2"
                                        style ="border-radius: 20px"
                                        href="{{ asset('storage/gestion/documento_seis/' . $gestion->documento_seis) }}"
                                        download>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2.1" stroke="currentColor" class="mb-1" width="25">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                        {{ $gestion->documento_seis }}</a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
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
@endsection
