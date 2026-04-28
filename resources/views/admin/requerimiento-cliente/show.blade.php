@extends('layouts.admin')
@section('title', 'Ver empleado cliente')
@section('content')

    <div class="form-group">
        <a class="btn btn-back  border btn-radius px-4" href="{{ url()->previous() }}">
            <i class="fas fa-arrow-circle-left"></i> Atrás
        </a>
    </div>

    <div class="row">
        <div class="col-12 col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-6">
                            <span class="fs-4" style="color:#232222;"># {{ $requerimiento->consecutivo }}</span>
                        </div>
                        <div class="col-6 text-end mb-3">
                             @if ($documento != null && dirname($documento->documentos) != 'storage/seguimiento_requerimiento')
                                <button
                                    onclick="location.href='{{ route('admin.requerimientos.cliente.download', ['id' => $requerimiento->id]) }}'"
                                    class="btn btn-outline-primary btn-radius px-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="mb-2" width="20">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M7.5 7.5h-.75A2.25 2.25 0 004.5 9.75v7.5a2.25 2.25 0 002.25 2.25h7.5a2.25 2.25 0 002.25-2.25v-7.5a2.25 2.25 0 00-2.25-2.25h-.75m-6 3.75l3 3m0 0l3-3m-3 3V1.5m6 9h.75a2.25 2.25 0 012.25 2.25v7.5a2.25 2.25 0 01-2.25 2.25h-7.5a2.25 2.25 0 01-2.25-2.25v-.75" />
                                    </svg>
                                    Descargar archivos
                                </button>
                            @endif
                        </div>
                    </div>
                    <div class="col-12 mb-2">
                        <hr style="height: 0 !important; width: 100%; border-top: 1px dashed rgb(215 215 215);">
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <span class="fw-normal text-secondary">Tipo de requerimiento</span><br>
                            {{ $requerimiento->tipo_requerimientos->nombre }}
                        </div>
                        <div class="col-6 text-end mb-3">
                            <span class="fw-normal text-secondary">Empresa</span><br>
                            {{ $seguimiento_requerimiento->empresa->razon_social }} <br>
                        </div>
                        <div class="col-12 mb-3">
                            <span class="fw-normal text-secondary">Persona quién solicita</span><br>
                            {{ $requerimiento->empleado_clientes->nombres . ' ' . $requerimiento->empleado_clientes->apellidos }}
                        </div>
                        <div class="col-12 mb-3">
                            <span class="fw-bold">Descripción</span><br>
                            <span>{{ $seguimiento_requerimiento->requerimientos->descripcion }}</span>
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <div class="col-12 col-xl-6">
            <div class="card">
                <div class="card-header">
                    <b>SEGUIMIENTO</b>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <span class="fw-normal text-secondary">Responsable</span><br>
                            <span class="fst-italic">
                                {{ $seguimiento_requerimiento->user_id
                                    ? $seguimiento_requerimiento->usuario_responsable->nombres .
                                        ' ' .
                                        $seguimiento_requerimiento->usuario_responsable->apellidos
                                    : 'Sin responsable' }}</span>
                        </div>
                        <div class="col-6 text-end mb-3">
                            @if ($seguimiento_requerimiento->estado_requerimiento_id == 1)
                                <span class="badge rounded-pill text-uppercase p-2 bg-success">{{ $seguimiento_requerimiento->estado_requerimientos->nombre }}</span>
                            @elseif($seguimiento_requerimiento->estado_requerimiento_id == 2) 
                                <span class="badge rounded-pill text-uppercase p-2 bg-primary">{{ $seguimiento_requerimiento->estado_requerimientos->nombre }}</span>
                            @elseif ($seguimiento_requerimiento->estado_requerimiento_id == 3) 
                                <span class="badge rounded-pill text-uppercase p-2 bg-danger">{{ $seguimiento_requerimiento->estado_requerimientos->nombre }}</span>
                            @elseif ($seguimiento_requerimiento->estado_requerimiento_id == 4) 
                                <span class="badge rounded-pill text-uppercase p-2 bg-warning">{{ $seguimiento_requerimiento->estado_requerimientos->nombre }}</span>
                            @elseif ($seguimiento_requerimiento->estado_requerimiento_id == 5) 
                                <span class="badge rounded-pill text-uppercase p-2 bg-info">{{ $seguimiento_requerimiento->estado_requerimientos->nombre }}</span>
                            @elseif ($seguimiento_requerimiento->estado_requerimiento_id == 6) 
                                <span class="badge rounded-pill text-uppercase p-2 bg-secondary">{{ $seguimiento_requerimiento->estado_requerimientos->nombre }}</span>
                            @endif
                        </div>
                        <div class="col-12 mb-3">
                            <span class="fw-normal text-secondary"> Fecha vencimiento </span>
                            <span class="fst-italic fw-bold">
                                {{ $seguimiento_requerimiento->fecha_vencimiento ? $seguimiento_requerimiento->fecha_vencimiento : '00/00/0000' }}
                            </span>
                        </div>
                        <div class="col-12">
                            <span class="fw-bold">Observación</span><br>
                            <span>{{ $seguimiento_requerimiento->observacion
                                    ? $seguimiento_requerimiento->observacion
                                    : 'Aún tu requerimiento no ha sido asignado' }}</span>
                        </div>
                    </div>
                       <div class="row mt-3">
                        @if ($docList)
                            <div class="col-12 col-md-12 mb-5">
                                <p class="text-center"> <i class="fas fa-paperclip"></i> Documentos adjuntos</p>
                                <table class="table table-sm">
                                    <tbody>
                                        @foreach ($docList as $key => $docName)
                                            <tr>
                                                <td class="border-top-0 border-left-0 border-right-0">
                                                    {{ basename($docName) }}
                                                </td>
                                                <td class="border-top-0 border-left-0 border-right-0 text-end">

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
                                                                <iframe src="{{ asset($docName) }}"
                                                                    style="width:100%;height:637px;" frameborder="0">
                                                                </iframe>
                                                            @else
                                                                <img src="{{ asset($docName) }}" alt=""
                                                                    width="100%" style="max-width: 750px;"">
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
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
@section('scripts')
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
