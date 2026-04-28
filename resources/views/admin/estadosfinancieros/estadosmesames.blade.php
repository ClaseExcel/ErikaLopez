@extends('layouts.admin')
@section('title', 'Situacion financiera')
@section('library')
    <!-- Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
@endsection
@section('content')

<style>
    .titulos {
        background-color: #919396 !important;
        color: white !important;
        font-weight: bold;
    }
    .totales{
        background-color: #D9D9D9 !important;
        color:#919396;
        font-weight: bold;
    }
    .carousel-inner .carousel-item {
        text-align: center;
    }
    .carousel-control-prev, .carousel-control-next {
        width: 5%;
    }
    .carousel-control-prev, .carousel-control-next {
        filter: grayscale(100%); /* Esto convierte los íconos a gris */
    }

    .carousel-control-prev-icon, .carousel-control-next-icon {
        background-color: #6c757d; /* Color gris oscuro */
        border-radius: 50%; /* Para hacerlo circular */
    }
</style>
    <div class="form-group">
        <a class="btn btn-light border btn-radius px-4" href="{{ URL::previous() }}">
            <i class="fas fa-arrow-circle-left"></i> Atrás
        </a>
    </div>

    <div class="card">
        <div class="card-header border-0 pb-0 pb-0 mt-1 py-2 d-flex justify-content-between align-items-center">
            <h5 class="text-dyd">
                <i class="fa-solid fa-table-columns"></i>
                Informe estado situacion financiera  mes a mes 
            </h5>
            <div class="d-flex ml-auto">
                <style>
                     .modal-content, .modal-content label, .modal-content textarea {
                        color: black;
                    }
                    .texto{
                        text-align: end;
                    }
                </style>

                
                @include('admin.estadosfinancieros.ia-modal')   <!-- Incluir modal de IA -->

                <!-- Exportar a excel -->
                <form id="exportForm" method="POST" action="{{ route('admin.export.estado.resultados') }}">
                    @csrf
                    <input type="hidden" name="informeData" value='@json($informeData2)'>
                    <input type="hidden" name="anio" value="{{ $anio }}">
                    <input type="hidden" name="anioAnterior" value="{{ $anioAnterior }}">
                    <input type="hidden" name="mes" value="{{ $mes }}">
                    <input type="hidden" name="compania" value="{{ $compania }}">
                    <input type="hidden" name="tipo" value="4">
                    <button type="submit" class="btn btn-sm btn-save btn-radius px-3">
                        <i class="fa-solid fa-file-excel"></i>
                    </button>
                </form>
                <div class="ml-auto">
                    <div id="download-pdf" >
                        <form id="tableForm"  action="{{ route('admin.estadosfinancieros.pdfmesames') }}" method="POST" target="_blank">
                            @csrf
                            <input type="hidden" name="nit" id="nit" value="{{ $nit }}">
                            <input type="hidden" name="siigo" id="siigo" value="{{ $siigo }}">
                            <input type="hidden" name="centro_costo" id="centro_costo" value="{{ $centro_costo }}">
                            <input type="hidden" name="tipoinforme" id="tipoinforme" value="{{ $tipoinforme }}">
                            <input type="hidden" name="fechaInicio" id="fechaInicio" value="{{ $fecha_inicio }}">
                            <input type="hidden" name="fechareal" id="fechareal" value="{{ $fecha_real }}">
                            <input type="hidden" name="tipoinforme2" id="tipoinforme2" value="{{ $tipoinformeresultados }}">
                            <button type="submit" class="btn btn-sm btn-save btn-radius"><i class="fa-solid fa-file-pdf fa-lg"></i></button>
                        </form>
                    </div>
                </div>


                
                
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <h4 class="subtitle">
                        {{ $compania }}
                    </h4>
                    @if ($centro_costo)
                        <h5>
                            Centro de costo: {{ $centro_costo->codigo . '-' . $centro_costo->nombre }}
                        </h5>
                    @endif
                    <div>
                        @if($textoMes)
                        @php
                            $textoMes=$textoMes;
                        @endphp
                        @else
                        @php
                            $textoMes='';
                            $meses = [
                                1 => 'Enero',
                                2 => 'Febrero',
                                3 => 'Marzo',
                                4 => 'Abril',
                                5 => 'Mayo',
                                6 => 'Junio',
                                7 => 'Julio',
                                8 => 'Agosto',
                                9 => 'Septiembre',
                                10 => 'Octubre',
                                11 => 'Noviembre',
                                12 => 'Diciembre',
                            ];

                            $nombreMes = $meses[$mes]; // Obtener el nombre del mes en español

                        @endphp
                        @endif
                        @if($mes)
                        <h5>
                            Periodo {{$textoMes}}: {{'Corte Mes: '. $nombreMes.' - Año: '.$anio }}
                        </h5>
                        @else
                        <h5>
                            Periodo: {{'Corte Mes:'. $nombreMes .' - Año:'. $anio }}
                        </h5>
                        @endif
                        
                    </div>
                </div>
            </div>
            <div class="row table-responsive">
                <table class="table table-sm table-bordered datatable-informe w-100">
                    @php
                        $meses = [
                            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                        ];
                    @endphp

                    <thead>
                        <tr>
                            <th>DESCRIPCIÓN</th>
                            @foreach(range(1, 12) as $i)
                                @if(isset($informeData2[0]["mes$i"]))
                                    <th style="text-align: center;">{{ $meses[$i] }}</th>
                                @endif
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                        @php
                            $secciones = [
                                'Activo Corriente' => ['Efectivo y equivalentes al efectivo', 'Inversiones', 'Cuentas comerciales y otras cuentas por cobrar', 'Activos por impuestos corrientes', 'Inventarios', 'Anticipos y avances', 'Otros activos', 'Total activo corriente'],
                                'Activo No Corriente' => ['Inversiones no corriente', 'Propiedades planta y equipos', 'Activos Intangibles', 'Impuesto diferido', 'Total activo no corriente', 'Total activo'],
                                'Pasivo Corriente' => ['Obligaciones financieras', 'Cuentas comerciales y otras cuentas por pagar', 'Pasivos por Impuestos Corrientes', 'Beneficios a empleados', 'Anticipos y avances recibidos', 'Otros Pasivos', 'Total pasivos corrientes'],
                                'Pasivo No Corriente' => ['Obligaciones Financieras', 'Cuentas por pagar comerciales y otras cuentas por pagar', 'Pasivos Contingentes', 'Pasivo por impuesto diferido', 'Total pasivos no corrientes', 'Total Pasivo'],
                                'Patrimonio' => ['Capital social', 'Superavit de capital', 'Reservas', 'Utilidad y/o perdidas del ejercicio', 'Utilidad y/o perdidas acumuladas', 'Ganancias acumuladas - Adopcion por primera vez', 'Dividendos o participacion', 'Superavit de Capital Valorizacion', 'Total patrimonio', 'Total Pasivo & Patrimonio'],
                            ];
                        @endphp

                        @foreach ($secciones as $titulo => $cuentas)
                            <tr><td colspan="{{ count($informeData2[0]) }}" class="titulos">{{ $titulo }}</td></tr>

                            @foreach ($cuentas as $cuenta)
                                @foreach ($informeData2 as $item)
                                    @if($item['descripcion'] === $cuenta)
                                        @php
                                            // Verificar si todos los meses están en 0
                                            $todosCeros = true;
                                            foreach (range(1, 12) as $i) {
                                                if (isset($item["mes$i"]) && (float) str_replace(',', '', $item["mes$i"]) != 0) {
                                                    $todosCeros = false;
                                                    break;
                                                }
                                            }

                                            // Si todos los meses están en cero, no mostramos la fila
                                            if ($todosCeros) continue;

                                            $isTotal = str_starts_with($item['descripcion'], 'Total');
                                            $isUtilidadAcumulada = $item['descripcion'] === 'Utilidad y/o perdidas acumuladas';
                                            $isUtilidadEjercicio = $item['descripcion'] === 'Utilidad y/o perdidas del ejercicio';

                                            // Ajuste de signo
                                            $signAdjustment = function ($value) use ($isUtilidadAcumulada, $isUtilidadEjercicio) {
                                                if ($isUtilidadAcumulada || $isUtilidadEjercicio) {
                                                    return str_starts_with($value, '-') ? ltrim($value, '-') : '-' . $value;
                                                }
                                                return ltrim($value, '-');
                                            };
                                        @endphp

                                        <tr @if ($isTotal) style="background-color: #cecece; color: #919396; font-weight: bold;" @endif>
                                            <td>{{ $item['descripcion'] }}</td>
                                            @foreach(range(1, 12) as $i)
                                                @if(isset($item["mes$i"]))
                                                    <td class="texto" style="text-align: right;">
                                                        @php
                                                            $valor = (float) str_replace(',', '', $signAdjustment($item["mes$i"]));
                                                        @endphp
                                                        {{ number_format($valor, 0, '', ',') }}
                                                    </td>
                                                @endif
                                            @endforeach
                                        </tr>
                                    @endif
                                @endforeach
                            @endforeach

                            {{-- Fila vacía --}}
                            <tr><td colspan="{{ count($informeData2[0]) }}">&nbsp;</td></tr>
                        @endforeach
                    </tbody>
                </table>
                
                
            </div>
            
            
        </div>
    </div>
    </div>
<script>
    $(document).ready(function() {
        $('#messagesCarousel').carousel({
            interval: false // Desactiva el intervalo de deslizamiento automático
        });
        $('#messagesModal').on('show.bs.modal', function () {
            // Guardar los mensajes iniciales cuando el modal se muestra
            initialMessages = {};
        $('#messagesCarousel .carousel-item').each(function() {
                const id = $(this).find('textarea').attr('id');
                const value = $(this).find('textarea').val();
                initialMessages[id] = value;
            });
        });
    });
    document.getElementById('openModalButton').addEventListener('click', function () {
        // Asegúrate de que siempre abra el carousel en el primer mensaje
        $('#messagesCarousel').carousel(0);
        $('#messagesModal').modal('show');
    });

            // Cerrar el modal con el botón de cierre (botón de "Cerrar")
    document.getElementById('closeModalButton').addEventListener('click', function () {
          // Restaura los mensajes cuando se cierra el modal
        $('#messagesModal').on('hidden.bs.modal', function () {
            $('#messagesCarousel .carousel-item').each(function() {
                const id = $(this).find('textarea').attr('id');
                if (initialMessages[id]) {
                    $(this).find('textarea').val(initialMessages[id]);
                }
            });
        });
        $('#messagesModal').modal('hide');   // Cerrar el modal
    });

    document.addEventListener('DOMContentLoaded', function() {
        var saveMessagesButton = document.getElementById('saveMessages');
        if (saveMessagesButton) {
            saveMessagesButton.addEventListener('click', function() {
                var messages = {};
                
                // Recorre cada campo de mensaje y guarda su valor
                @foreach($agrupaciones as $rango => $descripcion)
                    var messageElement = document.getElementById('message_{{ $rango }}');
                    if (messageElement) {
                        messages['{{ $rango }}'] = messageElement.value;
                    }
                @endforeach

                // Guarda los mensajes en el campo oculto
                document.getElementById('messagesInput').value = JSON.stringify(messages);

                // Cierra el modal
                $('#messagesModal').modal('hide');
                
                // Envía el formulario
                document.getElementById('messagesForm').submit();
            });
        }
    });
</script>
@endsection

