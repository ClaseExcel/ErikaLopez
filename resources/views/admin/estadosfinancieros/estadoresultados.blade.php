@extends('layouts.admin')
@section('title', 'Estado Resultados')
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
        background-color: #919396!important;
        color: white !important;
        font-weight: bold;
    }
    .totales{
        background-color: #dcd7d7 !important;
        font-weight: bold;
        text-align: right;
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
    .text-right {
        text-align: right !important;
    }
    
    .option-card{
    border:2px solid #e4e7ec;
    border-radius:18px;
    padding:30px;
    display:block;
    cursor:pointer;
    transition:.25s;
    background:white;
    text-align:center;
    min-height:280px;
    }

    .option-card:hover{
    transform:translateY(-4px);
    box-shadow:0 12px 30px rgba(0,0,0,.08);
    border-color:#43bff2;
    }

    .option-card.selected{
    border-color:#43bff2;
    background:#f5fcf7;
    }

    .option-icon{
    width:70px;
    height:70px;
    margin:auto auto 20px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:28px;
    background:#eef8f1;
    color:#43bff2;
    }

    .option-card h5{
    font-weight:700;
    margin-bottom:15px;
    }

    .option-card p{
    color:#6c757d;
    font-size:15px;
    min-height:55px;
    }

    .check-badge{
    display:inline-block;
    margin-top:15px;
    padding:8px 16px;
    border-radius:30px;
    background:#edf2f7;
    font-size:13px;
    font-weight:600;
    }

    .option-card.selected .check-badge{
    background:#43bff2;
    color:white;
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
            Estado de Resultado Integral
        </h5>
        <div class="d-flex ml-auto">
            <style>
                .modal-content, .modal-content label, .modal-content textarea {
                   color: black;
               }
           </style>

            @include('admin.estadosfinancieros.ia-modal')   <!-- Incluir modal de IA -->
            <form id="exportForm" method="POST" action="{{ route('admin.export.estado.resultados') }}">
                @csrf
                <input type="hidden" name="informeData" value='@json($informeData)'>
                <input type="hidden" name="anio" value="{{ $anio }}">
                <input type="hidden" name="anioAnterior" value="{{ $anioAnterior }}">
                <input type="hidden" name="mes" value="{{ $mes }}">
                <input type="hidden" name="compania" value="{{ $compania }}">
                <input type="hidden" name="tipo" value="1">
                <button type="submit" class="btn btn-sm btn-save btn-radius px-3">
                    <i class="fa-solid fa-file-excel"></i>
                </button>
            </form>

           <button id="openModalButton" class="btn btn-sm btn-save btn-radius px-3"><i class="fa-solid fa-file-pdf fa-lg"></i></button>
            <div class="modal fade" id="messagesModal" tabindex="-1">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content rounded-4 shadow">

                        <div class="modal-header bg-light">
                            <h5 class="modal-title">
                                Configuración del Informe
                            </h5>

                            <!-- Indicador pasos -->
                            <div class="ml-auto font-weight-bold">
                                <span id="stepIndicator">Paso 1 de 2</span>
                            </div>

                            <button type="button" id="closeModal" class="close" aria-label="Close"> 
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">

                            <!-- PASO 1 -->
                            <div id="step1">

                                <div class="text-center mb-4">
                                    <h3 class="font-weight-bold mb-2">
                                        Configure su Informe Financiero
                                    </h3>

                                    <p class="text-muted mb-0">
                                        Seleccione los anexos que desea incluir en la generación del PDF.
                                    </p>
                                </div>


                                <div class="row">

                                    <!-- opcion 1 -->
                                    <div class="col-md-6 mb-4">
                                        <label class="option-card selected w-100" for="flujoEfectivo">

                                            <input
                                                type="checkbox"
                                                id="flujoEfectivo"
                                                checked
                                                hidden
                                            >

                                            <div class="option-icon">
                                                <i class="fa-solid fa-chart-line"></i>
                                            </div>

                                            <h5>
                                                Estados Complementarios
                                            </h5>

                                            <p>
                                                Incluye Flujo de Efectivo y Cambios en el Patrimonio.
                                            </p>

                                            <span class="check-badge">
                                                ✓ Seleccionado
                                            </span>

                                        </label>
                                    </div>



                                    <!-- opcion 2 -->
                                    <div class="col-md-6 mb-4">
                                        <label class="option-card w-100" for="dictamenFiscal">

                                            <input
                                                type="checkbox"
                                                id="dictamenFiscal"
                                                hidden
                                            >

                                            <div class="option-icon">
                                                <i class="fa-solid fa-scale-balanced"></i>
                                            </div>

                                            <h5>
                                                Dictamen Revisor Fiscal
                                            </h5>

                                            <p>
                                                Agregar opinión y dictamen para el informe.
                                            </p>

                                            <span class="check-badge">
                                                Seleccionar
                                            </span>

                                        </label>
                                    </div>

                                </div>

                            </div>


                            <!-- PASO 2 -->
                            <div id="step2" style="display:none">

                                <h4 class="mb-3">
                                    Personalizar mensajes por cuentas
                                </h4>
                                    
                                <!-- navegación mejor que carrusel -->
                                <ul class="nav nav-pills mb-4 flex-wrap">
                                    @foreach($agrupaciones as $rango=>$data)
                                    <li class="nav-item mr-2 mb-2">
                                        <a class="nav-link {{ $loop->first ? 'active':'' }}"
                                        data-toggle="pill"
                                        href="#tab{{$rango}}">
                                            Grupo {{$loop->iteration}}
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>

                                <div class="tab-content">

                                    @foreach($agrupaciones as $rango=>$data)

                                    <div class="tab-pane fade {{ $loop->first?'show active':'' }}"
                                        id="tab{{$rango}}">

                                        <div class="card shadow-sm">
                                            <div class="card-body">

                                                <h5>
                                                {{
                                                is_array($data['descripcion'])
                                                ? implode(', ', $data['descripcion'])
                                                : $data['descripcion']
                                                }}
                                                </h5>

                                                <textarea
                                                    class="form-control mt-3"
                                                    id="message_{{$rango}}"
                                                    rows="8"
                                                >{{ $mensajesPredeterminados[$rango] ?? '' }}</textarea>

                                            </div>
                                        </div>

                                    </div>

                                    @endforeach

                                </div>

                            </div>

                        </div>

                        <div class="modal-footer">

                            <button
                                type="button"
                                class="btn btn-sm btn-save btn-radius px-3"
                                id="backStep"
                                style="display:none;">
                                Anterior
                            </button>

                            <button
                                type="button"
                                class="btn btn-sm btn-save btn-radius px-3"
                                id="nextStep">
                                Continuar
                            </button>

                            <button
                                type="button"
                                class="btn btn-sm btn-save btn-radius px-3"
                                id="saveMessages"
                                style="display:none;">
                                Generar PDF
                            </button>

                        </div>

                    </div>
                </div>
            </div>
           <!-- Formulario oculto -->
           <form id="messagesForm" action="{{route('admin.estadosfinancieros.pdfestadoresultado')}}" method="POST" target="_blank" style="display: none;">
               @csrf
               <input type="hidden" name="nit" id="nit" value="{{ $nit }}">
               <input type="hidden" name="siigo" id="siigo" value="{{ $siigo }}">
               <input type="hidden" name="centro_costo" id="centro_costo" value="{{ $centro_costo }}">
               <input type="hidden" name="tipoinforme" id="tipoinforme" value="{{ $tipoinforme }}">
               <input type="hidden" name="fechaInicio" id="fechaInicio" value="{{ $fecha_inicio }}">
               <input type="hidden" name="fechareal" id="fechareal" value="{{ $fecha_real }}">
               <input type="hidden" name="tipoinforme2" id="tipoinforme2" value="{{ $tipoinformeresultados }}">
               <input type="hidden" id="messagesInput" name="messages">
           </form>
            
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="vertical-align: middle; padding-right: 10px;">
                            <h4 class="subtitle" style="margin-bottom: 0;">{{ $compania }}</h4>

                            @if ($centro_costo)
                                <h5 style="margin-top: 0;">
                                    Centro de costo: {{ $centro_costo->codigo . '-' . $centro_costo->nombre }}
                                </h5>
                            @endif

                            @if($mes)
                                <h5 style="margin-top: 0;">
                                    Periodo {{ $textoMes }}: {{ 'Corte Mes: ' . $mes.'-'.$anio. ' - ' .$mes.'-'.$anioAnterior }}
                                </h5>
                            @else
                                <h5 style="margin-top: 0;">
                                    Periodo {{ 'Corte Mes: ' . $textoMes.'-'. $anio. ' - ' .$anioAnterior }}
                                </h5>
                            @endif
                        </td>

                        <td style="width: 1%; vertical-align: middle; text-align: center;">
                            @include('modal.validadorinformecontable-modal')
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row table-responsive">
            <table class="table table-sm table-striped table-bordered table-striped datatable-informe w-100">
                <thead>
                    @if($mes)
                    <tr>
                        <th>DESCRIPCIÓN</th>
                        <th>{{ $mes.'-'.$anio }}</th>
                        <th>{{ $mes.'-'. $anioAnterior }}</th>
                        <th>VAR%</th>
                        <th>VARIACIÓN $</th>
                    </tr>
                    @else
                    <tr>
                        <th>DESCRIPCIÓN</th>
                        <th>AÑO {{ $anio }}</th>
                        <th>AÑO {{ $anioAnterior }}</th>
                        <th>VAR%</th>
                        <th>VARIACIÓN $</th>
                    </tr>
                    @endif
                </thead>
                <tbody>
                    {{-- Mostrar las filas principales --}}
                    @foreach (['Ingresos de actividades ordinarias', 'Costos de venta', 'Utilidad Bruta'] as $key)
                        @php
                            $anioActual = floatval($informeData[$key][$anio] ?? 0);
                            $anioPrevio = floatval($informeData[$key][$anioAnterior] ?? 0);
                        @endphp

                        @if ($anioActual != 0 || $anioPrevio != 0)
                            <tr>
                                <td @if ($key == 'Utilidad Bruta') class="titulos" @endif>{{ $informeData[$key]['descripcionct'] }}</td>
                                <td @if ($key == 'Utilidad Bruta') class="totales" @else style="text-align: right" @endif>{{ number_format(abs($anioActual), 0, '', '.') }}</td>
                                <td @if ($key == 'Utilidad Bruta') class="totales" @else style="text-align: right" @endif>{{ number_format(abs($anioPrevio), 0, '', '.') }}</td>
                                <td @if ($key == 'Utilidad Bruta') class="totales" @else style="text-align: right" @endif>{{ number_format(abs($informeData[$key]['var%']) * 100, 0, '', '.') }}%</td>
                                <td @if ($key == 'Utilidad Bruta') class="totales" @else style="text-align: right" @endif>{{ number_format(abs($informeData[$key]['variacion$']), 0, '', '.') }}</td>
                            </tr>
                        @endif
                    @endforeach

            
                    {{-- Fila vacía --}}
                    <tr><td colspan="5">&nbsp;</td></tr>
            
                    {{-- Mostrar los datos adicionales --}}
                    @foreach ([ 'Gastos de administración', 'Gastos de ventas'] as $key)
                        @php
                            $anioActual = floatval($informeData[$key][$anio] ?? 0);
                            $anioPrevio = floatval($informeData[$key][$anioAnterior] ?? 0);
                        @endphp
                        @if ($anioActual != 0 || $anioPrevio != 0)
                        <tr>
                            <td>{{ $informeData[$key]['descripcionct'] }}</td>
                            <td style="text-align: right">{{ number_format(abs($informeData[$key][$anio]), 0, '', '.') }}</td>
                            <td style="text-align: right">{{ number_format(abs($informeData[$key][$anioAnterior]), 0, '', '.') }}</td>
                            <td style="text-align: right">{{ number_format(abs($informeData[$key]['var%']) * 100, 0, '', '.') }}%</td>
                            <td style="text-align: right">{{ number_format(abs($informeData[$key]['variacion$']), 0, '', '.') }}</td>
                        </tr>
                        @endif
                    @endforeach
                    {{-- Fila vacía --}}
                    <tr><td colspan="5">&nbsp;</td></tr>
                    {{-- Mostrar Utilidad (Pérdida) operativa --}}
                    <tr>
                        <td class="titulos" style="background-color: #002060; color: white; font-weight: bold;">Utilidad (Pérdida) operativa</td>
                        <td class="totales">{{ number_format($informeData['Utilidad (Pérdida) operativa'][$anio], 0, '', '.') }}</td>
                        <td class="totales">{{ number_format($informeData['Utilidad (Pérdida) operativa'][$anioAnterior], 0, '', '.') }}</td>
                        <td class="totales">{{ number_format($informeData['Utilidad (Pérdida) operativa']['var%'] * 100, 0, '', '.') }}%</td>
                        <td class="totales">{{ number_format($informeData['Utilidad (Pérdida) operativa']['variacion$'], 0, '', '.') }}</td>
                    </tr>
                    <tr><td colspan="5">&nbsp;</td></tr>
                    {{-- Mostrar las filas restantes --}}
                    @foreach (['Otros ingresos','Ingresos financieros','Otros gastos','Gastos financieros'] as $key)
                        @php
                            $anioActual = floatval($informeData[$key][$anio] ?? 0);
                            $anioPrevio = floatval($informeData[$key][$anioAnterior] ?? 0);
                        @endphp
                        @if ($anioActual != 0 || $anioPrevio != 0)
                            <tr>
                                <td>{{ $informeData[$key]['descripcionct'] }}</td>
                                <td style="text-align: right">{{ number_format(abs($informeData[$key][$anio]), 0, '', '.') }}</td>
                                <td style="text-align: right">{{ number_format(abs($informeData[$key][$anioAnterior]), 0, '', '.') }}</td>
                                <td style="text-align: right">{{ number_format(abs($informeData[$key]['var%']) * 100, 0, '', '.') }}%</td>
                                <td style="text-align: right">{{ number_format(abs($informeData[$key]['variacion$']), 0, '', '.') }}</td>
                            </tr>
                        @endif
                    @endforeach
                    
                    {{-- Mostrar (Pérdida) Utilidad antes de impuestos de renta --}}
                    <tr>
                        <td class="titulos" style="background-color: #002060; color: white; font-weight: bold;">Utilidad (Pérdida) antes de impuestos de renta</td>
                        <td class="totales">{{ number_format($informeData['Utilidad (Pérdida) antes de impuestos de renta'][$anio], 0, '', '.') }}</td>
                        <td class="totales">{{ number_format($informeData['Utilidad (Pérdida) antes de impuestos de renta'][$anioAnterior], 0, '', '.') }}</td>
                        <td class="totales">{{ number_format($informeData['Utilidad (Pérdida) antes de impuestos de renta']['var%'] * 100, 0, '', '.') }}%</td>
                        <td class="totales">{{ number_format($informeData['Utilidad (Pérdida) antes de impuestos de renta']['variacion$'], 0, '', '.') }}</td>
                    </tr>
                    <tr><td colspan="5">&nbsp;</td></tr>
                     {{-- Mostrar los datos adicionales --}}
                     @foreach (['Gastos impuesto de renta y cree'] as $key)
                        @php
                            $anioActual = floatval($informeData[$key][$anio] ?? 0);
                            $anioPrevio = floatval($informeData[$key][$anioAnterior] ?? 0);
                        @endphp
                    @if ($anioActual != 0 || $anioPrevio != 0)
                     <tr>
                         <td>Gastos impuesto a las ganancias</td>
                         <td style="text-align: right">{{ number_format($informeData[$key][$anio], 0, '', '.') }}</td>
                         <td style="text-align: right">{{ number_format($informeData[$key][$anioAnterior], 0, '', '.') }}</td>
                         <td style="text-align: right">{{ number_format($informeData[$key]['var%'] * 100, 0, '', '.') }}%</td>
                         <td style="text-align: right">{{ number_format($informeData[$key]['variacion$'], 0, '', '.') }}</td>
                     </tr>
                    @endif
                    @endforeach
                    <tr><td colspan="5">&nbsp;</td></tr>
                    {{-- Mostrar (Perdida) Utilidad Neta del periodo --}}
                    <tr>
                        <td class="titulos" style="background-color: #002060; color: white; font-weight: bold;">Utilidad (Perdida) Neta del periodo</td>
                        <td class="totales">{{ number_format($informeData['Utilidad (Perdida) Neta del periodo'][$anio], 0, '', '.') }}</td>
                        <td class="totales">{{ number_format($informeData['Utilidad (Perdida) Neta del periodo'][$anioAnterior], 0, '', '.') }}</td>
                        <td class="totales">{{ number_format($informeData['Utilidad (Perdida) Neta del periodo']['var%'] * 100, 0, '', '.') }}%</td>
                        <td class="totales">{{ number_format($informeData['Utilidad (Perdida) Neta del periodo']['variacion$'], 0, '', '.') }}</td>
                    </tr>
                    <tr><td colspan="5">&nbsp;</td></tr>
                    <tr>
                        <td class="titulos" style="background-color: #002060; color: white; font-weight: bold;">Resultado del ejercicio</td>
                        <td class="totales">{{ number_format($informeData['Utilidad (Perdida) Neta del periodo'][$anio], 0, '', '.') }}</td>
                        <td class="totales">{{ number_format($informeData['Utilidad (Perdida) Neta del periodo'][$anioAnterior], 0, '', '.') }}</td>
                        <td class="totales">{{ number_format($informeData['Utilidad (Perdida) Neta del periodo']['var%'] * 100, 0, '', '.') }}%</td>
                        <td class="totales">{{ number_format($informeData['Utilidad (Perdida) Neta del periodo']['variacion$'], 0, '', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
<script>
    
    $(function(){
        $('#step1 input[type=checkbox]').change(function(){

            let card=$(this).closest('.option-card');

            if($(this).is(':checked')){
                card.addClass('selected');
                card.find('.check-badge').text('✓ Seleccionado');
            }else{
                card.removeClass('selected');
                card.find('.check-badge').text('Seleccionar');
            }

    });
    //cerrar modal 
    $('#closeModal').on('click', function(){

        $('#messagesModal').modal('hide');

        // opcional reset al paso 1
        $('#step1').show();
        $('#step2').hide();

        $('#nextStep').show();
        $('#backStep').hide();
        $('#saveMessages').hide();

        $('#stepIndicator').text('Paso 1 de 2');

        });
    // abrir modal
    $('#openModalButton').on('click',function(){

        // resetear siempre en paso 1
        $('#step1').show();
        $('#step2').hide();

        $('#nextStep').show();
        $('#backStep').hide();
        $('#saveMessages').hide();

        $('#stepIndicator').text('Paso 1 de 2');

        $('#messagesModal').modal('show');
    });


    // ir paso 2
    $('#nextStep').on('click',function(){

        $('#step1').hide();
        $('#step2').fadeIn();

        $('#backStep').show();
        $('#saveMessages').show();

        $(this).hide();

        $('#stepIndicator').text('Paso 2 de 2');
    });

    // volver paso 1
    $('#backStep').on('click',function(){

        $('#step2').hide();
        $('#step1').fadeIn();

        $('#backStep').hide();
        $('#saveMessages').hide();

        $('#nextStep').show();

        $('#stepIndicator').text('Paso 1 de 2');
    });

    // generar pdf
    $('#saveMessages').on('click',function(){

        let messages = {};

        @foreach($agrupaciones as $rango=>$data)
            messages["{{$rango}}"] =
                $("#message_{{$rango}}").val();
        @endforeach


        let payload = {
            mensajes: messages,
            flujo_efectivo:
                $('#flujoEfectivo').is(':checked'),
            dictamen_fiscal:
                $('#dictamenFiscal').is(':checked')
        };

        $('#messagesInput').val(
            JSON.stringify(payload)
        );

        $('#messagesModal').modal('hide');

        $('#messagesForm').submit();

    });

});
</script>
@endsection

