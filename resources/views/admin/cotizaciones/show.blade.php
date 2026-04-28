@extends('layouts.admin')
@section('title', 'Ver detalle cotización')
@section('content')

    <div class="form-group">
        <a class="btn btn-back  border btn-radius px-4" href="{{ route('admin.cotizaciones.index') }}">
            <i class="fas fa-arrow-circle-left"></i> Atrás
        </a>

        <a class="btn btn-back  border btn-radius px-4"
            href="{{ route('admin.cotizacion-seguimiento.index', $cotizacion->id) }}">
            <i class="fas fa-file-alt"></i> Reportar seguimiento
        </a>

    </div>

    <div class="row mt-4">
        <div class="col-xl-8">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <span class="fs-6"><i class="fa-solid fa-building-user"></i> Información de la cotización</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <span class="fs-5">{{ $cotizacion->servicio_cotizado }} -
                                    #{{ $cotizacion->numero_cotizacion }}</span>
                            </div>
                            <div class="col-6 text-end">
                                <span class="badge  rounded-pill bg-info text-uppercase">
                                    {{ $cotizacion->estado_cotizacion }}</span> <br>

                            </div>
                        </div>
                        <div class="row py-2">
                            <style>
                                .max-text-tab {
                                    height: 115px;
                                    overflow-y: auto;
                                    overflow-x: hidden;
                                    scroll-behavior: smooth;
                                    scrollbar-width: thin;
                                    margin-left: 5px;
                                }
                            </style>

                            <div class="col-xl-4">
                                <span class="fw-bold">Fecha de envío</span><br>
                                <span
                                    class="fst-italic">{{ \Carbon\Carbon::parse($cotizacion->fecha_envio)->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}</span><br>
                            </div>
                            {{-- <div class="col-xl-4">
                                <span class="fw-bold">Fecha de vigencia</span><br>
                                <span
                                    class="fst-italic">{{ \Carbon\Carbon::parse($cotizacion->fecha_vigencia)->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}</span><br>
                            </div> --}}
                            <div class="col-xl-4">
                                <span class="fw-bold">Seguimiento</span><br>
                                @if ($fecha_proximo_seguimiento)
                                    <span
                                        class="fst-italic">{{ \Carbon\Carbon::parse($fecha_proximo_seguimiento->fecha_proximo_seguimiento)->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}</span><br>
                                @else
                                    <span
                                        class="fst-italic">{{ \Carbon\Carbon::parse($cotizacion->fecha_primer_seguimiento)->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}</span><br>
                                @endif
                            </div>
                            <div class="col-xl-4">
                                <span class="fw-bold">Línea de negocio</span><br>
                                <span class="fst-italic">{{ $cotizacion->linea_negocio }}</span><br>
                            </div>
                        </div>
                        <div class="row py-3">
                            <nav>
                                <div class="nav nav-tabs nav-cotizacion" id="nav-tab" role="tablist">
                                    <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home"
                                        aria-selected="true">Observación de la cotización</button>
                                    <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-profile" type="button" role="tab"
                                        aria-controls="nav-profile" aria-selected="false">Observación del primer
                                        seguimiento</button>
                                </div>
                            </nav>
                            <div class="tab-content mt-3" id="nav-tabContent">
                                <div class="tab-pane fade show active max-text-tab" id="nav-home" role="tabpanel"
                                    aria-labelledby="nav-home-tab">{!! $cotizacion->observaciones != ' '
                                        ? nl2br($cotizacion->observaciones)
                                        : '<i class="fas fa-info-circle text-secondary"></i> <i class="text-secondary text-sm">No hay ninguna observación disponible.</i>' !!}</div>
                                <div class="tab-pane fade max-text-tab" id="nav-profile" role="tabpanel"
                                    aria-labelledby="nav-profile-tab">
                                    {!! $cotizacion->observacion_primer_seguimiento != ' '
                                        ? nl2br($cotizacion->observacion_primer_seguimiento)
                                        : '<i class="fas fa-info-circle text-secondary"></i> <i class="text-secondary text-sm">No hay ninguna observación disponible.</i>' !!}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row px-0 mx-1">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <span class="fs-6"><i class="fa-solid fa-building-user"></i> {{ $cotizacion->prospecto_cliente }}</span>
                        </div>
                        <div class="card-body">

                            <div class="row">
                                <div class="col auto">
                                    <b>Razón social </b>
                                </div>
                                <div class="col auto text-end">
                                    {{ $cotizacion->cliente }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col auto">
                                    <b>Nombre del contacto </b>
                                </div>
                                <div class="col auto text-end">
                                    {{ $cotizacion->nombre_contacto }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col auto">
                                    <b>Número del contacto </b>
                                </div>
                                <div class="col auto text-end">
                                    {{ $cotizacion->telefono_contacto }}
                                </div>
                            </div>

                            <div class="row">
                                <div class="col auto">
                                    <b> Correo eléctronico </b>
                                </div>
                                <div class="col auto text-end">
                                    {{ $cotizacion->correo_contacto }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <span class="fs-6"><i class="fa-solid fa-user-tie"></i> Responsable comercial</span>
                        </div>
                        <div class="card-body">

                            <div class="row">
                                <div class="col auto">
                                    <b>Cédula </b>
                                </div>
                                <div class="col auto text-end">
                                    {{ $cotizacion->responsable->cedula }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col auto">
                                    <b>Nombres </b>
                                </div>
                                <div class="col auto text-end">
                                    {{ $cotizacion->responsable->nombres }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col auto">
                                    <b>Apellidos </b>
                                </div>
                                <div class="col auto text-end">
                                    {{ $cotizacion->responsable->apellidos }}
                                </div>
                            </div>

                            <div class="row">
                                <div class="col auto">
                                    <b> Correo eléctronico </b>
                                </div>
                                <div class="col auto text-end">
                                    {{ $cotizacion->responsable->email }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row px-0 mx-1">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa-solid fa-circle-dollar-to-slot"></i> Precios
                        </div>
                        <div class="card-body" style="height:339px">
                            <div id="chart-container"></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    {{-- Documentos adjuntos --}}
                    @if ($docList)
                        <div class="card" style="height: 190px">
                            <div class="card-header bg-transparent text-dark text-center fs-6">
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
                                            <div class="modal-dialog  modal-dialog-centered modal-xl">
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
                                                            <img src="{{ $docName }}" alt="" width="100%"
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

                    @endif
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <style>
                .max-text-seguimiento {
                    height: 550px;
                    overflow-y: auto;
                    overflow-x: hidden;
                    scroll-behavior: smooth;
                    scrollbar-width: thin;
                    margin-left: 5px;
                }

                p {
                    display: inline;
                }
            </style>
            <div class="card">
                <div class="card-header">
                    Información de seguimiento
                </div>
                <div class="card-body max-text-seguimiento" style="height:530px;">
                    <table class="table-responsive table-sm">
                        <tbody>
                            @if (count($seguimientos) > 0)
                                @foreach ($seguimientos as $index => $seguimiento)
                                    <tr>
                                        <td class="text-primary fw-bold"> Seguimiento #{{ $index + 1 }}</td>
                                    </tr>
                                    <tr class="d-flex justify-content-between">
                                        <td>
                                            <span class="text-secondary">
                                                {{ \Carbon\Carbon::parse($seguimiento->seguimiento->fecha_seguimiento)->format('d-m-Y h:i:s A') }}
                                                - </span>
                                            <span class="fw-bold">
                                                {{ $cotizacion->responsable->nombres . ' ' . $cotizacion->responsable->apellidos }}
                                            </span>
                                        </td>
                                        <td>
                                            <a class="btn-editar px-2 py-0  "
                                                href="{{ route('admin.cotizacion-seguimiento.edit', [$seguimiento->seguimiento->id, $seguimiento->cotizacion_id]) }}"
                                                title="Editar registro"><i class="fas fa-pencil-alt"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {!! nl2br($seguimiento->seguimiento->observaciones) !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="text-secondary">
                                                {{ \Carbon\Carbon::parse($seguimiento->proximo_seguimiento)->format('d-m-Y h:i:s A') }}
                                                -
                                            </span>
                                            <span class="fw-bold">Observación próximo seguimiento</span>
                                        </td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <td>
                                            {!! nl2br(
                                                $seguimiento->observacion_proximo_seguimiento
                                                    ? $seguimiento->observacion_proximo_seguimiento
                                                    : '<i class="fas fa-info-circle text-secondary"></i> <i class="text-secondary text-sm">No hay ninguna observación disponible.</i>',
                                            ) !!}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <i class="fas fa-info-circle text-secondary"></i> <i class="text-secondary text-sm">No hay
                                    ningún seguimiento disponible.</i>
                            @endif
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>



@endsection
@section('scripts')
    @parent
    {{-- // Include FusionCharts core file --}}
    <script type="text/javascript" src="https://cdn.fusioncharts.com/fusioncharts/latest/fusioncharts.js"></script>
    {{-- // Include FusionCharts Theme File --}}
    <script type="text/javascript"
        src="https://cdn.fusioncharts.com/fusioncharts/latest/themes/fusioncharts.theme.fusion.js"></script>

    <script>
        var valor = {{ $cotizacion->precio_venta }};
        var precio_format = "$ " + "{{ number_format($cotizacion->precio_venta, 0, ',', '.') }}";
        FusionCharts.ready(function() {
            var deforestationChart = new FusionCharts({
                type: 'column3d',
                renderAt: 'chart-container',
                width: '100%',
                height: '300',
                dataFormat: 'json',
                dataSource: {
                    "chart": {
                        "caption": "Precio de la venta",
                        "subCaption": precio_format,
                        "yAxisName": "Valor",
                        "numDivLines": "5",
                        "showValues": "0",
                        "rotateLabels": "1",
                        "slantLabels": "1",
                        "plotSpacePercent": "40",
                        "paletteColors": "#efcd5b",
                        "columnHoverAlpha": "80",
                        "canvasBaseDepth": "5",
                        "showBorder": "0",
                        "showYAxisLine": "0",
                        "canvasBorderAlpha": "100",
                        "yAxisValuesPadding": "10",
                        "showLimits": "0",
                        "divlineColor": "#cecece",
                        "divLineIsDashed": "1",
                        "divLineDashLen": "5",
                        "numberPrefix": "$",
                        "theme": "fusion"
                    },
                    "data": [{
                        "value": valor
                    }, ]
                }
            }).render();
        });
    </script>

@endsection
