
@extends('layouts.admin')
@section('title', 'ESTADO DE FLUJOS DE EFECTIVOS')
@section('content')
    <div class="form-group">
        <a class="btn btn-back border btn-radius px-4" href="{{ URL::previous() }}">
            <i class="fas fa-arrow-circle-left"></i> Atrás
        </a>
    </div>
    <div class="card">
        <div class="card-header border-0 pb-0 pb-0 mt-1 py-2 d-flex justify-content-between align-items-center">
            <h5><i class="fa-solid fa-table-columns"></i> ESTADO DE FLUJOS DE EFECTIVOS</h5>
            <div class="d-flex ml-auto">

                {{-- @include('admin.estadosfinancieros.ia-modal')   <!-- Incluir modal de IA --> --}}
                <div id="download-excel" >
                    <form id="exportForm" method="POST" action="{{ route('admin.export.estado.resultados') }}">
                        @csrf
                        <input type="hidden" name="informeData" value='@json($informes)'>
                        <input type="hidden" name="anio" value="{{ \Carbon\Carbon::parse($fecha_real)->year  }}">
                        <input type="hidden" name="anioAnterior" value="{{ \Carbon\Carbon::parse($fecha_real)->subYear()->year  }}">
                        <input type="hidden" name="mes" value="{{ \Carbon\Carbon::parse($fecha_real)->month }}">
                        <input type="hidden" name="tipo" value="5">
                        <input type="hidden" name="compania" value="{{ $compania }}">
                        <button type="submit"  class="btn btn-sm btn-save btn-radius px-3">
                            <i class="fa-solid fa-file-excel"></i>
                        </button>
                    </form>
                 </div>
                <div id="download-pdf" >
                    <form id="tableForm"  action="{{ route('admin.estadosfinancieros.pdfcambiopatrimonio') }}" method="POST" target="_blank">
                        @csrf
                        <input type="hidden" name="compania" value="{{$compania}}">
                        <input type="hidden" name="fecha" value="{{$fecha_real}}">
                        <input type="hidden" name="titulo" value="ESTADO DE FLUJOS DE EFECTIVOS">
                        <input type="hidden" name="tipoinforme" value="2">
                        <button type="submit" class="btn btn-sm btn-save btn-radius px-3"><i class="fa-solid fa-file-pdf fa-lg"></i></button>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col-12">
                    <!-- Nombre de la Compañía -->
                    <h2 class="fw-bold" style="color: #919396;">
                        {{ $compania }}
                    </h2>
                    
                    <!-- Estado de Cambio de Patrimonio -->
                    <h4 class="fw-bold">
                        ESTADO DE FLUJOS DE EFECTIVOS
                    </h4>
        
                    <!-- Fecha Formateada -->
                    <h5>
                        A {{ \Carbon\Carbon::parse($fecha_real)->locale('es')->translatedFormat('d \\de F \\de Y') }}
                    </h5>
                </div>
            </div>
            <div class="row table-responsive">
                <style>
                    .total-patrimonio {
                        background-color: #D9D9D9 !important;
                        color:#919396!important;
                        font-weight: bold;
                    }
                </style>
            <table class="table table-sm table-bordered datatable-informe w-100">
                <thead class="card-header">
                    <tr>
                        <th>CONCEPTO</th>
                        <th>VALOR ACTUAL {{$mes}} {{$anio}}</th>
                        <th>VALOR ANTERIOR {{$mes}} {{$anio-1}}</th>
                        <th>VARIACIÓN $</th>
                        <th>VARIACIÓN %</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- 1. Ciclo principal de secciones --}}
                    @if(is_array($informes))
                    @php 
                        $sumaTotalAct_Actual = 0; 
                        $sumaTotalAct_Anterior = 0; 
                        $objVariacion_Actual = 0;
                        $objVariacion_Anterior = 0;

                        $formatNumber = function($n) {
                            if (round($n, 0) == 0) return '-';
                            $abs = number_format(abs($n), 0, ',', '.');
                            return $n < 0 ? "({$abs})" : $abs;
                        };
                    @endphp

                    @foreach ($informes as $titulo => $filas)
                        <tr>
                            <td colspan="5" style="font-weight: bold; text-align: left; background-color: #f3f3f3;">
                                {{ strtoupper($titulo) }}
                            </td>
                        </tr>

                        @if(is_array($filas))
                            @php
                                // REORDENAMIENTO: Si es Variación Efectivo, forzamos el orden pedido
                                if ($titulo === 'VARIACION EFECTIVO') {
                                    $ordenDeseado = ['Efectivo Inicial', 'Efectivo Final', 'Incremento Neto'];
                                    usort($filas, function($a, $b) use ($ordenDeseado) {
                                        return array_search($a['descripcion'], $ordenDeseado) - array_search($b['descripcion'], $ordenDeseado);
                                    });
                                }
                            @endphp

                            @foreach ($filas as $fila)
                                @php
                                    $descripcion = trim($fila['descripcion'] ?? '');
                                    $keys = array_keys($fila);
                                    $aniosKeys = array_values(array_filter($keys, fn($k) => str_starts_with($k, 'año')));
                                    sort($aniosKeys);

                                    if (count($aniosKeys) < 2) continue;

                                    $actual = floatval($fila[$aniosKeys[1]] ?? 0);
                                    $anterior = floatval($fila[$aniosKeys[0]] ?? 0);
                                    $variacionValor = $actual - $anterior;

                                    // Capturamos el Incremento Neto para validar contra la suma de actividades
                                    if ($descripcion == 'Incremento Neto') {
                                        $objVariacion_Actual = $actual;
                                        $objVariacion_Anterior = $anterior;
                                    }
                                    
                                    // Acumulamos sumas de las secciones de actividades para ambos años
                                    if (in_array($titulo, ['ACTIVIDADES DE OPERACION', 'ACTIVIDADES DE INVERSION', 'ACTIVIDADES DE FINANCIACION'])) {
                                        $sumaTotalAct_Actual += $actual;
                                        $sumaTotalAct_Anterior += $anterior;
                                    }
                                    
                                    // Cálculo de la variación porcentual
                                    $variacionPorcentaje = 0;
                                    if ($anterior != 0) {
                                        $variacionPorcentaje = ($variacionValor / abs($anterior)) * 100;
                                    }
                                @endphp

                                <tr>
                                    <td style="{{ !str_contains($descripcion, '(+') ? 'font-weight:bold;' : '' }}">
                                        {{ $descripcion }}
                                    </td>
                                    <td style="text-align:right;">{{ $formatNumber($actual) }}</td>
                                    <td style="text-align:right;">{{ $formatNumber($anterior) }}</td>
                                    <td style="text-align:right;">
                                        @if (round($variacionValor, 0) > 0)
                                            <span style="color:green">▲</span> {{ number_format($variacionValor, 0, ',', '.') }}
                                        @elseif (round($variacionValor, 0) < 0)
                                            <span style="color:red">▼</span> ({{ number_format(abs($variacionValor), 0, ',', '.') }})
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td style="text-align:right; font-weight:bold;">
                                        @if ($anterior != 0 && round($variacionValor, 0) != 0)
                                            {{ number_format($variacionPorcentaje, 2, ',', '.') }}%
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach

                    {{-- 2. BLOQUE FINAL DE VALIDACIÓN --}}
                    @php
                        $diffActual = round($objVariacion_Actual - $sumaTotalAct_Actual, 0);
                        $diffAnterior = round($objVariacion_Anterior - $sumaTotalAct_Anterior, 0);
                    @endphp

                    <tr style="background-color: #e9ecef; font-weight: bold;">
                        <td style="text-align: left;">Validador (Suma Actividades)</td>
                        <td style="text-align: right;">{{ $formatNumber($sumaTotalAct_Actual) }}</td>
                        <td style="text-align: right;">{{ $formatNumber($sumaTotalAct_Anterior) }}</td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr style="border-top: 2px solid #000; font-weight: bold;">
                        <td style="text-align: left;">Diferencia</td>
                        <td style="text-align: right;">
                            {{ abs($diffActual) <= 1 ? '-' : $formatNumber($diffActual) }}
                        </td>
                        <td style="text-align: right;">
                            {{ abs($diffAnterior) <= 1 ? '-' : $formatNumber($diffAnterior) }}
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    @else
                        <tr><td colspan="5" class="text-center">No se pudo cargar la estructura del informe.</td></tr>
                    @endif
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')

@endsection
