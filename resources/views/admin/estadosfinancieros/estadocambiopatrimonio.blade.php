
@extends('layouts.admin')
@section('title', 'Estado Cambio Patrimonio')
@section('content')
    <div class="form-group">
        <a class="btn btn-back border btn-radius px-4" href="{{ URL::previous() }}">
            <i class="fas fa-arrow-circle-left"></i> Atrás
        </a>
    </div>
    <div class="card">
        <div class="card-header border-0 pb-0 pb-0 mt-1 py-2 d-flex justify-content-between align-items-center">
            <h5><i class="fa-solid fa-table-columns"></i> Estado de cambio de patrimonio</h5>
            <div class="d-flex ml-auto">

                {{-- @include('admin.estadosfinancieros.ia-modal')   <!-- Incluir modal de IA --> --}}
                 <!-- Exportar a excel -->
                <div id="download-excel" >
                    <form id="exportForm" method="POST" action="{{ route('admin.export.estado.resultados') }}">
                        @csrf
                        <input type="hidden" name="informeData" value='@json($informe)'>
                        <input type="hidden" name="anio" value="{{ \Carbon\Carbon::parse($fecha_real)->year  }}">
                        <input type="hidden" name="anioAnterior" value="{{ \Carbon\Carbon::parse($fecha_real)->subYear()->year  }}">
                        <input type="hidden" name="mes" value="{{ \Carbon\Carbon::parse($fecha_real)->month }}">
                        <input type="hidden" name="tipo" value="3">
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
                        <input type="hidden" name="titulo" value="ESTADO DE CAMBIO DE PATRIMONIO">
                        <input type="hidden" name="tipoinforme" value="1">
                        <button type="submit" class="btn btn-sm btn-save btn-radius px-3"><i class="fa-solid fa-file-pdf fa-lg"></i></button>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-11 text-center">
                    <!-- Nombre de la Compañía -->
                    <h2 class="fw-bold" style="color: #919396;">
                        {{ $compania }}
                    </h2>
                    
                    <!-- Estado de Cambio de Patrimonio -->
                    <h4 class="fw-bold">
                        ESTADO DE CAMBIO DE PATRIMONIO
                    </h4>
        
                    <!-- Fecha Formateada -->
                    <h5>
                        A {{ \Carbon\Carbon::parse($fecha_real)->locale('es')->translatedFormat('d \\de F \\de Y') }}
                    </h5>
                </div>
                <div class="col-1 d-flex justify-content-end align-items-start">
                    <!-- Botón del modal -->
                    @include('modal.validadorpatrimonio-modal')
                </div>
               
                    
            </div>
            <div class="row text-center">
                <table class="table table-sm table-striped table-bordered datatable-informe w-100">
                    <thead class="card-header">
                        <tr>
                            <th rowspan="2" class="text-center">Concepto</th>
                            <th colspan="4" class="text-center">Año {{ array_key_first($informe) }}</th>
                            <th colspan="4" class="text-center">Año {{ array_key_last($informe) }}</th>
                        </tr>
                        <tr>
                            <th>Saldo Anterior</th>
                            <th>Aumento</th>
                            <th>Disminución</th>
                            <th>Saldo Actual</th>
                            <th>Saldo Anterior</th>
                            <th>Aumento</th>
                            <th>Disminución</th>
                            <th>Saldo Actual</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $nombresCuentas = [
                                '31' => 'CAPITAL SOCIAL',
                                '33' => 'RESERVAS',
                                '32' => 'SUPERÁVIT DE CAPITAL',
                                '34' => 'GANANCIAS ACUMULADAS',
                                '35' => 'DIVIDENDOS O PARTICIPACIÓN',
                                '36' => 'RESULTADOS DEL EJERCICIO',
                                '37' => 'RESULTADOS DE EJERCICIOS ANTERIORES',
                                '362' => 'RESULTADOS DEL ACUMULADOS',
                                'Total Patrimonio' => 'TOTAL PATRIMONIO'
                            ];
                            $anioActual = array_key_first($informe);
                            $anioAnterior = array_key_last($informe);
                        @endphp
                        @foreach ($informe[$anioActual] as $codigo => $itemActual)
                            @php
                                $itemAnterior = $informe[$anioAnterior][$codigo] ?? null;

                                // Normalizar valores a números
                                $valoresActual = [
                                    (float) str_replace(',', '', $itemActual->saldo_anterior ?? 0),
                                    (float) str_replace(',', '', $itemActual->aumento ?? 0),
                                    (float) str_replace(',', '', $itemActual->disminucion ?? 0),
                                    (float) str_replace(',', '', $itemActual->saldo_actual ?? 0),
                                ];

                                $valoresAnterior = $itemAnterior ? [
                                    (float) str_replace(',', '', $itemAnterior->saldo_anterior ?? 0),
                                    (float) str_replace(',', '', $itemAnterior->aumento ?? 0),
                                    (float) str_replace(',', '', $itemAnterior->disminucion ?? 0),
                                    (float) str_replace(',', '', $itemAnterior->saldo_actual ?? 0),
                                ] : [0,0,0,0];

                                // Verificar si todos son 0
                                $todosCeros = collect(array_merge($valoresActual, $valoresAnterior))->every(fn($v) => $v == 0);
                            @endphp

                            @if (!$todosCeros)
                                <tr class="{{ $itemActual->cuenta === 'Total Patrimonio' ? 'total-patrimonio' : '' }}">
                                    <td>
                                        {{ $nombresCuentas[$itemActual->cuenta] ?? $itemActual->cuenta }}
                                    </td>

                                    {{-- Año actual --}}
                                    <td class="text-end">{{ number_format($valoresActual[0], 0) }}</td>
                                    <td class="text-end">{{ number_format($valoresActual[2], 0) }}</td>
                                    <td class="text-end">{{ number_format($valoresActual[1], 0) }}</td>
                                    <td class="text-end">{{ number_format($valoresActual[3], 0) }}</td>

                                    {{-- Año anterior --}}
                                    @if ($itemAnterior)
                                        <td class="text-end">{{ number_format($valoresAnterior[0], 0) }}</td>
                                        <td class="text-end">{{ number_format($valoresAnterior[2], 0) }}</td>
                                        <td class="text-end">{{ number_format($valoresAnterior[1], 0) }}</td>
                                        <td class="text-end">{{ number_format($valoresAnterior[3], 0) }}</td>
                                    @else
                                        <td colspan="4" class="text-center">-</td>
                                    @endif
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')

@endsection
