
@extends('layouts.admin')
@section('title', 'Estado Resultados')
@section('content')
    <div class="form-group">
        <a class="btn btn-light border btn-radius px-4" href="{{ URL::previous() }}">
            <i class="fas fa-arrow-circle-left"></i> Atrás
        </a>
    </div>
    <div class="card">
        <div class="card-header border-0 pb-0 pb-0 mt-1 py-2 d-flex justify-content-between align-items-center">
            <h5><i class="fa-solid fa-table-columns"></i> Estado de resultados comparativo mes a mes</h5>
            <div class="d-flex ml-auto">

                @include('admin.estadosfinancieros.ia-modal')   <!-- Incluir modal de IA -->
                 <!-- Exportar a excel -->
                <div id="exportExcel" >
                    <form id="tableForm"  action="{{ route('admin.estadosfinancieros.pdfgeneral') }}" method="POST" >
                        @csrf
                        <input type="hidden" name="tipopdf" id="tipopdf" value='3'>
                        <input type="hidden" name="compania" value="{{$compania}}">
                        <input type="hidden" name="fecha" value="{{$fecha}}">
                        <input type="hidden" name="tableData" id="tableData" value="{{ json_encode($informePorMes) }}">
                        <input type="hidden" name="totales" value="{{json_encode($totales)}}">
                        <button type="submit" class="btn btn-sm btn-save btn-radius px-3"><i class="fa-solid fa-file-excel"></i></button>
                    </form>
                </div>
                <div id="download-pdf" >
                    <form id="tableForm"  action="{{ route('admin.estadosfinancieros.pdfgeneral') }}" method="POST" target="_blank">
                        @csrf
                        <input type="hidden" name="tipopdf" id="tipopdf" value='1'>
                        <input type="hidden" name="compania" value="{{$compania}}">
                        <input type="hidden" name="fecha" value="{{$fecha}}">
                        <input type="hidden" name="tableData" id="tableData" value="{{ json_encode($informePorMes) }}">
                        <input type="hidden" name="totales" value="{{json_encode($totales)}}">
                        <button type="submit" class="btn btn-sm btn-save btn-radius px-3"><i class="fa-solid fa-file-pdf fa-lg"></i></button>
                    </form>
                </div>

            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table border-0" style="width: 100%;">
                        <tr>
                            <td class="align-middle border-0">
                                <h4 class="title mb-0">
                                    {{ $compania }}
                                </h4>
                                @if ($centrocostov)
                                    <h5 class="mb-0">
                                        Centro de costo: {{ $centrocostov->codigo. '-'. $centrocostov->nombre }}
                                    </h5>
                                @endif
                                <h5 class="mb-0">
                                    Periodo: {{ $fecha }}
                                </h5>
                            </td>
                            <td class="text-end align-middle border-0" style="white-space: nowrap;">
                                <!-- Botón 1 -->
                                @include('admin.estadosfinancieros.modal', ['nit' => $nit, 'fecha_inicio' => $fecha_inicio])
                                <button type="button" 
                                        class="btn btn-light border btn-radius px-4 mb-2" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#exampleModal" 
                                        data-bs-size="xl">
                                    <i class="fa-solid fa-magnifying-glass"></i> Buscar detalles por cuenta
                                </button>
                                <br>
                                <!-- Botón 2 -->
                                @include('modal.validadorERF-modal')

                                {{-- boton 3 --}}
                                @include('modal.metas-empresa-modal')
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row table-responsive">
                @php
                    if (!function_exists('parse_number')) {
                        function parse_number($number) {
                            // Reemplaza los puntos de miles por nada
                            $number = str_replace('.', '', $number);
                            // Reemplaza la coma decimal por un punto
                            $number = str_replace(',', '.', $number);
                            return floatval($number);
                        }
                    }
                @endphp
                <table class="table table-sm table-striped table-bordered   datatable-informe w-100" id="tabla-informe">
                    <thead class="card-header">
                        <tr>
                            <th class="text-center">DESCRIPCIÓN</th>
                            @php $prevMes = null; @endphp
                            @foreach (array_keys($informePorMes) as $mes)
                                @if ($mes !== 'descripcionct'  && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                    <th class="text-center">{{ $mes }}</th>
                                @endif
                            @endforeach
                            <th class="text-center">TOTAL</th>
                            <th class="text-center">% VENTAS</th>
                        </tr>
                    </thead>
                    @php
                        $totalVentasNetasGeneral = 0;

                        foreach (array_keys($informePorMes) as $mes) {
                            if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta') {
                                $valor = !empty($totales[$mes]['Total ventas netas'])
                                    ? parse_number($totales[$mes]['Total ventas netas'])
                                    : 0;

                                $totalVentasNetasGeneral += $valor;
                            }
                        }
                        @endphp

                    <tbody>
                            @php
                            $descripcionesMostradas = [];
                            $totalMes = false;
                            @endphp

                            @php
                            // Define las cuentas que deseas excluir
                            // $excluirCuentas = ['Gastos', 'Costos de venta','Gastos de personal','Gastos de Personal'];
                            $excluirCuentas = ['Ingresos','Devoluciones en ventas','Total Ventas Netas'];
                             // Encuentra la posición de la cuenta 6 en el array Cuenta
                             $posicionCuenta6 = array_search(6, $informePorMes['Cuenta']);
                             $posicionCuenta42 = array_search(42, $informePorMes['Cuenta']);
                             $posicionCuenta4210 = array_search(4210, $informePorMes['Cuenta']);
                             $posicionCuenta53 = array_search(53, $informePorMes['Cuenta']);
                             $posicionCuenta5305 = array_search(5305, $informePorMes['Cuenta']);
                            @endphp
                            @foreach ($informePorMes['descripcionct'] as $index => $descripcionct)
                            @if ($descripcionct !== 'Total Mes' && $descripcionct !== 'Cuenta' && !in_array($descripcionct, $descripcionesMostradas))
                                @if (in_array($descripcionct, $excluirCuentas))
                                    <tr>
                                        @php
                                            $totalFila = 0;
                                        @endphp
                                        <td><h6>{{ $descripcionct }}</h6></td>
                                        @foreach (array_keys($informePorMes) as $mes)
                                            @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                                @php
                                                    // Usa la función parse_number para convertir el valor
                                                    $valor = !empty($informePorMes[$mes][$index]) ? parse_number($informePorMes[$mes][$index]) : 0;
                                                    $totalFila += $valor;
                                                @endphp
                                                <td class="text-end">{{ !empty($informePorMes[$mes][$index]) ? ltrim(number_format($valor, 0, '', '.'), '-') : '-' }}</td>
                                            @endif
                                        @endforeach
                                        <td class="text-end"><b>{{ $totalFila != 0 ? number_format(abs($totalFila), 0, '', '.') : '-' }}</b></td>
                                        <td></td>
                                    </tr>
                                @endif
                                @php
                                    $descripcionesMostradas[] = $descripcionct;
                                @endphp
                            @endif
                            @endforeach
                            @php
                            // Contar el número de columnas de datos
                            $numeroDeColumnas = count(array_keys($informePorMes)); // Restamos 'descripcionct', 'Total Mes', y 'Cuenta'
                            @endphp
                            <tr>
                                <td><h6><b>Total Ventas Netas</b></h6></td>
                                @php
                                    $totalFila = 0;
                                    $prevValor = null;
                                @endphp
                                @foreach (array_keys($informePorMes) as $mes)
                                    @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                        @php
                                            // Usa la función parse_number para convertir el valor
                                            $valor = !empty($totales[$mes]['Total ventas netas']) ? parse_number($totales[$mes]['Total ventas netas']) : 0;
                                            $totalFila += $valor;
                                        @endphp
                                        <td class="text-end"><b>${{ !empty($totales[$mes]['Total ventas netas']) ? number_format($valor, 0, '', '.') : '-' }}</b></td>
                                    @endif
                                @endforeach
                                <td class="text-end"><b>${{ $totalFila != 0 ? number_format($totalFila, 0, '', '.') : '-' }}</b></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                @for ($i = 0; $i < $numeroDeColumnas; $i++)
                                    <td></td>
                                @endfor
                            </tr>
                            <tr>
                                <td><b>COSTO MERCANCIA VENDIDA</b></td>
                                @for ($i = 0; $i < $numeroDeColumnas; $i++)
                                    <td></td>
                                @endfor
                            </tr>
                            <tr>
                                <td><h6>Costo de ventas</h6></td>
                                @php
                                    $totalFila = 0;
                                @endphp
                                @foreach (array_keys($informePorMes) as $mes)
                                    @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                        @php
                                            // Usa la función parse_number para convertir el valor
                                            $valor = !empty($posicionCuenta6) ? parse_number($informePorMes[$mes][$posicionCuenta6]) : 0;
                                            $totalFila += $valor;
                                        @endphp
                                        <td class="text-end">{{ !empty($informePorMes[$mes][$posicionCuenta6]) ? number_format($valor, 0, '', '.') : '-' }}</td>
                                    @endif
                                @endforeach
                                <td class="text-end"><b>{{ $totalFila != 0 ? number_format($totalFila, 0, '', '.') : '-' }}</b></td>
                                @php
                                    $porcentajeTotal = $totalVentasNetasGeneral != 0
                                        ? ($totalFila / $totalVentasNetasGeneral) * 100
                                        : 0;
                                @endphp

                                <td class="text-end">
                                    <b>{{ $totalVentasNetasGeneral != 0 ? number_format($porcentajeTotal, 1) . '%' : '-' }}</b>
                                </td>
                            </tr>
                            <!-- Agregar una fila adicional para mostrar los totales del grupo Ventas -->
                            <tr>
                                <td><h6><b>Margen Bruto ventas</b></h6></td>
                                @php
                                    $totalFila = 0;
                                    $prevValor = null;
                                @endphp
                                @foreach (array_keys($informePorMes) as $mes)
                                    @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                        @php
                                            // Usa la función parse_number para convertir el valor
                                            $valor = !empty($totales[$mes]['Utilidad bruta ventas']) ? parse_number($totales[$mes]['Utilidad bruta ventas']) : 0;
                                            $totalFila += $valor;
                                        @endphp
                                        <td class="text-end"><b>${{ !empty($totales[$mes]['Utilidad bruta ventas']) ? number_format($valor, 0, '', '.') : '-' }}</b></td>
                                    @endif
                                @endforeach
                                <td class="text-end"><b>${{ $totalFila != 0 ? number_format($totalFila, 0, '', '.') : '-' }}</b></td>
                                @php
                                    $porcentajeTotal = $totalVentasNetasGeneral != 0
                                        ? ($totalFila / $totalVentasNetasGeneral) * 100
                                        : 0;
                                @endphp

                                <td class="text-end">
                                    <b>{{ $totalVentasNetasGeneral != 0 ? number_format($porcentajeTotal, 1) . '%' : '-' }}</b>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                @for ($i = 0; $i < $numeroDeColumnas; $i++)
                                    <td></td>
                                @endfor
                            </tr>
                            <tr>
                                <td><b>GASTOS</b></td>
                                @for ($i = 0; $i < $numeroDeColumnas; $i++)
                                    <td></td>
                                @endfor
                            </tr>
                           <!-- Agregar una fila adicional para mostrar los totales del grupo Gastos de Venta -->
                           <tr>
                            <td><h6><b>Gastos Operacionales</b></h6></td>
                            @php
                                $totalFila = 0;
                            @endphp
                            @foreach (array_keys($informePorMes) as $mes)
                                @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                    @php
                                        $valor = !empty($totales[$mes]['Total gastos admón y ventas']) ? parse_number($totales[$mes]['Total gastos admón y ventas']) : 0;
                                        $totalFila += $valor;
                                    @endphp
                                    <td class="text-end"><b>${{ !empty($totales[$mes]['Total gastos admón y ventas']) ? number_format($valor, 0, '', '.') : '-' }}</b></td>
                                @endif
                            @endforeach
                            <td class="text-end"><b>{{ $totalFila != 0 ? number_format($totalFila, 0, '', '.') : '-' }}</b></td>
                            @php
                                $porcentajeTotal = $totalVentasNetasGeneral != 0
                                    ? ($totalFila / $totalVentasNetasGeneral) * 100
                                    : 0;
                            @endphp

                            <td class="text-end">
                                <b>{{ $totalVentasNetasGeneral != 0 ? number_format($porcentajeTotal, 1) . '%' : '-' }}</b>
                            </td>
                        </tr>
                        @php
                            $filasNormales = [];
                            $filasGastosOperacionales = [];
                        @endphp

                        @foreach ($informePorMes['descripcionct'] as $index => $descripcionct)
                            @if (isset($informePorMes['Cuenta'][$index]) && substr($informePorMes['Cuenta'][$index], 0, 1) === '5' && substr($informePorMes['Cuenta'][$index], 0, 2) !== '53' && substr($informePorMes['Cuenta'][$index], 0, 2) !== '54'  && !in_array($descripcionct, ['OTROS INGRESOS']) && !in_array($descripcionct, ['OTROS EGRESOS']))
                                @php
                                    $totalFila = 0;
                                    $descripcionctMostrar = ($descripcionct === 'Gastos operacionales') ? 'Gastos de ventas' : $descripcionct;
                                    $fila = "<tr>";
                                    $fila .= "<td><h6>$descripcionctMostrar</h6></td>";
                                    foreach (array_keys($informePorMes) as $mes) {
                                        if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta') {
                                            $valor = !empty($informePorMes[$mes][$index]) ? parse_number($informePorMes[$mes][$index]) : 0;
                                            $totalFila += $valor;
                                            $fila .= "<td class=\"text-end\">" . (!empty($informePorMes[$mes][$index]) ? ltrim(number_format($valor, 0, '', '.'), '-')  : '-') . "</td>";
                                        }
                                    }
                                    // Columna TOTAL
                                    $fila .= "<td class=\"text-end\"><b>" 
                                            . ($totalFila != 0 ? number_format($totalFila, 0, '', '.') : '-') 
                                            . "</b></td>";

                                    // Calcular % Ventas (solo contra TOTAL ventas netas general)
                                    $porcentajeTotal = $totalVentasNetasGeneral != 0
                                        ? ($totalFila / $totalVentasNetasGeneral) * 100
                                        : 0;

                                    // Columna % Ventas
                                    $fila .= "<td class=\"text-end\"><b>"
                                            . ($totalVentasNetasGeneral != 0 ? number_format($porcentajeTotal, 1) . "%" : "-")
                                            . "</b></td>";

                                    $fila .= "</tr>";


                                    if ($descripcionct === 'Gastos operacionales') {
                                        $filasGastosOperacionales[] = $fila;
                                    } else {
                                        $filasNormales[] = $fila;
                                    }
                                @endphp
                            @endif
                        @endforeach

                        {{-- Imprimir filas normales --}}
                        @foreach ($filasNormales as $fila)
                            {!! $fila !!}
                        @endforeach

                        {{-- Imprimir filas de Gastos operacionales --}}
                        @foreach ($filasGastosOperacionales as $fila)
                            {!! $fila !!}
                        @endforeach

                        

                            

                            <!-- Agregar una fila adicional para mostrar los totales de Utilidad Operacional -->
                            <tr>
                                <td><h6><b>Utilidad Operacional</b></h6></td>
                                @php
                                    $totalFila = 0;
                                @endphp
                                @foreach (array_keys($informePorMes) as $mes)
                                    @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                        @php
                                            $valor = !empty($totales[$mes]['Utilidad operacional']) ? parse_number($totales[$mes]['Utilidad operacional']) : 0;
                                            $totalFila += $valor;
                                        @endphp
                                        <td class="text-end"><b>${{ !empty($totales[$mes]['Utilidad operacional']) ? number_format($valor, 0, '', '.') : '-' }}</b></td>
                                    @endif
                                @endforeach
                                <td class="text-end"><b>{{ $totalFila != 0 ? number_format($totalFila, 0, '', '.') : '-' }}</b></td>
                                @php
                                    $porcentajeTotal = $totalVentasNetasGeneral != 0
                                        ? ($totalFila / $totalVentasNetasGeneral) * 100
                                        : 0;
                                @endphp

                                <td class="text-end">
                                    <b>{{ $totalVentasNetasGeneral != 0 ? number_format($porcentajeTotal, 1) . '%' : '-' }}</b>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                @for ($i = 0; $i < $numeroDeColumnas; $i++)
                                    <td></td>
                                @endfor
                            </tr>
                            <!-- Agregar una fila adicional para mostrar los totales de grupo otros ingresos -->
                        <tr>
                            <td>
                                <h6><b><a href="#" id="otros-ingresos-link">Otros Ingresos</a></b></h6>
                            </td>
                            @include('admin.estadosfinancieros.otros-ingresos', [
                                'nit' => $nit,
                                'fecha' => $fecha_inicio,
                            ])
                            {{-- @include('admin.estadosfinancieros.otros-ingresos',['nit' => $nit, 'fecha' => $fecha_inicio]) --}}
                            @php
                                $totalFila = 0;
                            @endphp
                            @foreach (array_keys($informePorMes) as $mes)
                                @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                    @php
                                        $valor = !empty($informePorMes[$mes][$posicionCuenta42]) ? parse_number($informePorMes[$mes][$posicionCuenta42]) : 0;
                                        $totalFila += $valor;
                                    @endphp
                                    <td class="text-end"><b>${{ !empty($informePorMes[$mes][$posicionCuenta42]) ? number_format($valor, 0, '', '.') : '-' }}</b></td>
                                @endif
                            @endforeach
                            <td class="text-end"><b>{{ $totalFila != 0 ? number_format(abs($totalFila), 0, '', '.') : '-' }}</b></td>
                            @php
                                $porcentajeTotal = $totalVentasNetasGeneral != 0
                                    ? ($totalFila / $totalVentasNetasGeneral) * 100
                                    : 0;
                            @endphp

                            <td class="text-end">
                                <b>{{ $totalVentasNetasGeneral != 0 ? number_format($porcentajeTotal, 1) . '%' : '-' }}</b>
                            </td>
                        </tr>
                        <tr>
                            <td><h6>Ingresos financieros</h6></td>
                            @php
                                $totalFila = 0;
                            @endphp
                            @foreach (array_keys($informePorMes) as $mes)
                                @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                    @php
                                        $valor = !empty($informePorMes[$mes][$posicionCuenta4210]) ? parse_number($informePorMes[$mes][$posicionCuenta4210]) : 0;
                                        $totalFila += $valor;
                                    @endphp
                                    <td class="text-end">${{ !empty($informePorMes[$mes][$posicionCuenta4210]) ? number_format($valor, 0, '', '.') : '-' }}</td>
                                @endif
                            @endforeach
                            <td class="text-end">{{ $totalFila != 0 ? number_format($totalFila,0, '', '.') : '-' }}</td>
                            @php
                                $porcentajeTotal = $totalVentasNetasGeneral != 0
                                    ? ($totalFila / $totalVentasNetasGeneral) * 100
                                    : 0;
                            @endphp

                            <td class="text-end">
                                <b>{{ $totalVentasNetasGeneral != 0 ? number_format($porcentajeTotal, 1) . '%' : '-' }}</b>
                            </td>
                        </tr>
                            <tr>
                                <td>
                                    <h6><b><a href="#" id="otros-egresos-link">Otros Egresos</a></b></h6>
                                </td>
                                @php
                                    $totalFila = 0;
                                @endphp
                                @foreach (array_keys($informePorMes) as $mes)
                                    @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                        @php
                                            $valor = !empty($informePorMes[$mes][$posicionCuenta53]) ? parse_number($informePorMes[$mes][$posicionCuenta53]) : 0;
                                            $totalFila += $valor;
                                        @endphp
                                        <td class="text-end"><b>${{ !empty($informePorMes[$mes][$posicionCuenta53]) ? number_format($valor, 0, '', '.') : '-' }}</b></td>
                                        
                                    @endif
                                @endforeach
                                <td class="text-end"><b>{{ $totalFila != 0 ? number_format($totalFila, 0, '', '.') : '-' }}</b></td>
                                @php
                                    $porcentajeTotal = $totalVentasNetasGeneral != 0
                                        ? ($totalFila / $totalVentasNetasGeneral) * 100
                                        : 0;
                                @endphp

                                <td class="text-end">
                                    <b>{{ $totalVentasNetasGeneral != 0 ? number_format($porcentajeTotal, 1) . '%' : '-' }}</b>
                                </td>
                            </tr> 
                            <tr>
                                <td><h6>Gastos financieros</h6></td>
                                @php
                                    $totalFila = 0;
                                @endphp
                                @foreach (array_keys($informePorMes) as $mes)
                                    @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                        @php
                                            $valor = !empty($informePorMes[$mes][$posicionCuenta5305]) ? parse_number($informePorMes[$mes][$posicionCuenta5305]) : 0;
                                            $totalFila += $valor;
                                        @endphp
                                        <td class="text-end">${{ !empty($informePorMes[$mes][$posicionCuenta5305]) ? number_format($valor, 0, '', '.') : '-' }}</td>
                                        
                                    @endif
                                @endforeach
                                <td class="text-end">{{ $totalFila != 0 ? number_format($totalFila, 0, '', '.') : '-' }}</td>
                                @php
                                    $porcentajeTotal = $totalVentasNetasGeneral != 0
                                        ? ($totalFila / $totalVentasNetasGeneral) * 100
                                        : 0;
                                @endphp

                                <td class="text-end">
                                    <b>{{ $totalVentasNetasGeneral != 0 ? number_format($porcentajeTotal, 1) . '%' : '-' }}</b>
                                </td>
                            </tr> 
                                <!-- Agregar una fila adicional para mostrar los totales de Utilidad antes de Impuestos -->
                                 <tr>
                                    <td><h6><b>Utilidad antes de Impuestos</b></h6></td>
                                    @php
                                        $totalFila = 0;
                                    @endphp
                                    @foreach (array_keys($informePorMes) as $mes)
                                        @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                            @php
                                                $valor = !empty($totales[$mes]['Utilidad antes de imptos']) ? parse_number($totales[$mes]['Utilidad antes de imptos']) : 0;
                                                $totalFila += $valor;
                                            @endphp
                                            <td class="text-end"><b>${{ !empty($totales[$mes]['Utilidad antes de imptos']) ? number_format($valor, 0, '', '.') : '-' }}</b></td>
                                        @endif
                                    @endforeach
                                    <td class="text-end"><b>{{ $totalFila != 0 ? number_format($totalFila, 0, '', '.') : '-' }}</b></td>
                                    @php
                                        $porcentajeTotal = $totalVentasNetasGeneral != 0
                                            ? ($totalFila / $totalVentasNetasGeneral) * 100
                                            : 0;
                                    @endphp

                                    <td class="text-end">
                                        <b>{{ $totalVentasNetasGeneral != 0 ? number_format($porcentajeTotal, 1) . '%' : '-' }}</b>
                                    </td>
                                </tr> 
                                <tr>
                                    <td><h6><b>Provision impuesto de renta</b></h6></td>
                                    @php
                                        $totalFila = 0;
                                    @endphp
                                    @foreach (array_keys($informePorMes) as $mes)
                                        @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                            @php
                                                $valor = !empty($totales[$mes]['Provision impuesto renta']) ? parse_number($totales[$mes]['Provision impuesto renta']) : 0;
                                                $totalFila += $valor;
                                            @endphp
                                            <td class="text-end"><b>{{ !empty($totales[$mes]['Provision impuesto renta']) ? '$'.number_format($valor, 0, '', '.') : '-' }}</b></td>
                                        @endif
                                    @endforeach
                                    <td class="text-end"><b>{{ $totalFila != 0 ? number_format($totalFila, 0, '', '.') : '-' }}</b></td>
                                    @php
                                        $porcentajeTotal = $totalVentasNetasGeneral != 0
                                            ? ($totalFila / $totalVentasNetasGeneral) * 100
                                            : 0;
                                    @endphp

                                    <td class="text-end">
                                        <b>{{ $totalVentasNetasGeneral != 0 ? number_format($porcentajeTotal, 1) . '%' : '-' }}</b>
                                    </td>
                                </tr> 
                                
                                <!-- Agregar una fila adicional para mostrar los totales de Utilidad neta -->
                                <tr>
                                    <td><h6><b>Utilidad/perdida neta</b></h6></td>
                                    @php
                                        $totalFila = 0;
                                    @endphp
                                    @foreach (array_keys($informePorMes) as $mes)
                                        @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                            @php
                                                $valor = !empty($totales[$mes]['Utilidad neta']) ? parse_number($totales[$mes]['Utilidad neta']) : 0;
                                                $totalFila += $valor;
                                            @endphp
                                            <td class="text-end"><b>${{ !empty($totales[$mes]['Utilidad neta']) ? number_format($valor, 0, '', '.') : '-' }}</b></td>
                                        @endif
                                    @endforeach
                                    <td class="text-end"><b>{{ $totalFila != 0 ? number_format($totalFila, 0, '', '.') : '-' }}</b></td>
                                    @php
                                        $porcentajeTotal = $totalVentasNetasGeneral != 0
                                            ? ($totalFila / $totalVentasNetasGeneral) * 100
                                            : 0;
                                    @endphp

                                    <td class="text-end">
                                        <b>{{ $totalVentasNetasGeneral != 0 ? number_format($porcentajeTotal, 1) . '%' : '-' }}</b>
                                    </td>
                                </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        function parseFormattedNumber(text) {
            if (!text) return 0;

            // quitar símbolos comunes y letras
            text = text.replace(/\s+/g, '')          // quitar espacios
                    .replace(/\$/g, '')           // quitar signo peso
                    .replace(/▲|▼/g, '')         // quitar flechas
                    .replace(/%/g, '')           // quitar porcentaje si aparece
                    .replace(/―|—/g, '-')        // guiones largos a menos
                    .trim();

            // detectar negativo por paréntesis (ej "(1.234)" => -1.234)
            let isNegative = false;
            if (/^\(.+\)$/.test(text)) {
                isNegative = true;
                text = text.replace(/^\(|\)$/g, '');
            }

            // también detectar signo menos al principio
            if (/^[-−]/.test(text)) {
                isNegative = true;
                text = text.replace(/^[-−]/, '');
            }

            // si quedó vacío
            if (text === '' || text === '-' ) return 0;

            // mantener sólo dígitos, puntos y comas
            text = text.replace(/[^0-9.,]/g, '');

            // Si no hay separadores, convertir directo
            if (text.indexOf('.') === -1 && text.indexOf(',') === -1) {
                let n = parseFloat(text) || 0;
                return isNegative ? -n : n;
            }

            // Si existen ambos separadores, decidir cuál es decimal:
            // regla: el separador más a la derecha es el separador decimal
            let lastDot = text.lastIndexOf('.');
            let lastComma = text.lastIndexOf(',');
            if (lastDot !== -1 && lastComma !== -1) {
                if (lastComma > lastDot) {
                    // coma es decimal, punto miles -> eliminar puntos y reemplazar coma por punto
                    text = text.replace(/\./g, '').replace(/,/g, '.');
                } else {
                    // punto es decimal, coma miles -> eliminar comas
                    text = text.replace(/,/g, '');
                }
            } else if (text.indexOf(',') !== -1) {
                // solo coma presente -> podría ser decimal o miles.
                // si hay más de una coma, asumimos que coma es miles y no decimal
                if ((text.match(/,/g) || []).length > 1) {
                    text = text.replace(/,/g, '');
                } else {
                    // single comma: interpretarla como decimal si la parte derecha tiene 1-2 dígitos (centavos),
                    // sino como miles.
                    let parts = text.split(',');
                    if (parts[1].length <= 2) {
                        text = text.replace(/,/g, '.'); // coma decimal
                    } else {
                        text = text.replace(/,/g, ''); // coma miles
                    }
                }
            } else if (text.indexOf('.') !== -1) {
                // solo punto presente -> similar al caso coma
                if ((text.match(/\./g) || []).length > 1) {
                    text = text.replace(/\./g, '');
                } else {
                    let parts = text.split('.');
                    if (parts[1].length <= 2) {
                        // punto decimal
                        // dejar como está (ya es punto decimal)
                    } else {
                        // punto como miles
                        text = text.replace(/\./g, '');
                    }
                }
            }

            let num = parseFloat(text);
            if (isNaN(num)) num = 0;
            return isNegative ? -num : num;
        }

        document.getElementById("btnValidar").addEventListener("click", function () {
            let datosTabla = [];

            document.querySelectorAll("#tabla-informe tbody tr").forEach(tr => {
                let descripcion = tr.querySelector("td:first-child")?.innerText.trim();
                // Si el total está en la última columna visible, OK; si no, ajusta el selector.
                let totalCelda = tr.querySelector("td:nth-last-child(2)")?.innerText || "0";

                // parsear con la función robusta
                let total = parseFormattedNumber(totalCelda);

                if (descripcion) {
                    datosTabla.push({
                        descripcion: descripcion,
                        total: total
                    });
                }
            });

            if (!datosTabla.length) {
                alert("No se encontraron datos en la tabla. Revisa que tenga el id correcto.");
                return;
            }

            let payload = {
                informeMes: datosTabla,
                fechaInicio: "{{ $fecha_inicio }}",
                nit: "{{ $nit }}",
                siigo: "{{ $siigo }}",
                centro_costo: "{{ $centrocostov }}"
            };

            fetch("{{ route('admin.estadosfinancieros.comparacion') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(respuesta => {
                renderTabla(respuesta.comparacion, "resumido"); // inicio resumido
                new bootstrap.Modal(document.getElementById("modalValidacion")).show();

                // eventos toggle dentro del modal
                document.getElementById("btnResumido").addEventListener("click", function() {
                    renderTabla(respuesta.comparacion, "resumido");
                    this.classList.add("active");
                    document.getElementById("btnDetallado").classList.remove("active");
                });

                document.getElementById("btnDetallado").addEventListener("click", function() {
                    renderTabla(respuesta.comparacion, "detallado");
                    this.classList.add("active");
                    document.getElementById("btnResumido").classList.remove("active");
                });
            })
            .catch(err => {
                console.error("Error en validación:", err);
            });
        });

        function renderTabla(data, modo) {
            let html = `
                <table class="table table-hover table-bordered align-middle">
                    <thead class="card-header">
                        <tr>
                            <th class="text-center">Concepto</th>`;
            if (modo === "detallado") {
                html += `<th>Estado de resultados PDF</th><th>Estado de resultados cargado</th>`;
            }
            html += `<th class="text-center">Estado</th></tr></thead><tbody>`;

            data.forEach(item => {
                let estadoClass = item.coincide ? "table-success" : "table-danger";
                let badge = item.coincide 
                    ? '<span class="badge bg-success">✔️ Cuadrado</span>' 
                    : '<span class="badge bg-danger">❌ Descuadrado</span>';

                html += `<tr>
                    <td class="text-center">${item.concepto}</td>`;
                if (modo === "detallado") {
                    // formatear con toLocaleString para mostrar miles y decimales locales
                    html += `<td class="text-end">${item.calculado.toLocaleString()}</td>
                            <td class="text-end">${item.esperado ? (typeof item.esperado === 'number' ? item.esperado.toLocaleString() : item.esperado) : 0}</td>`;
                }
                html += `<td class="text-center">${badge}</td></tr>`;
            });

            html += '</tbody></table>';
            document.getElementById("resultadoValidacion").innerHTML = html;
        }
    });
</script>
<script>
 // Manejar el clic en el enlace
 $('#otros-ingresos-link').click(function(e) {
    e.preventDefault(); // Prevenir el comportamiento por defecto del enlace
    // Mostrar SweetAlert
    Swal.fire({
      title: 'Cargando...',
      allowOutsideClick: false,
      showConfirmButton: false,
      onBeforeOpen: () => {
        Swal.showLoading();
      }
    });

    // Hacer una solicitud AJAX para obtener los resultados
    $.ajax({
      url: '{{ route("admin.otros-ingresos", ["nit" => $nit, "fecha" => $fecha_inicio]) }}',
      method: 'GET',
      success: function(response) {
        // Crear la estructura HTML de la tabla
        var html = '<table class="table-sm  table table-striped table-bordered table-striped  datatable-informe w-100">';
        html += '<thead class="thead-dark"><tr><th>Cuenta</th><th>Nombre Específico</th><th>Saldo Total</th></tr></thead>';
        html += '<tbody>';
        // Iterar sobre los resultados y construir las filas de la tabla
        response.forEach(function(resultado) {
            html += '<tr>';
            html += '<td>' + resultado.grupo_cuenta + '</td>';
            html += '<td>' + resultado.nombre_especifico + '</td>';
            html += '<td class="text-end"> ' + resultado.saldo_total + '</td>';
            html += '</tr>';
        });
        
        html += '</tbody></table>';
        // Colocar los resultados en el cuerpo del modal
        $('#otrosIngresosModalBody').html(html);
        // Mostrar el modal
        $('#otrosIngresosModal').modal('show');
      },
      error: function() {
        // Manejar errores de la solicitud AJAX
        console.error('Hubo un error en la solicitud.');
      }
    });
  });

  // Manejar el evento shown.bs.modal
  $('#otrosIngresosModal').on('shown.bs.modal', function (e) {
    // Cerrar SweetAlert una vez que el modal se haya abierto completamente
    Swal.close();
  });
  // Manejar el clic en el enlace para "Otros Egresos"
$('#otros-egresos-link').click(function(e) {
    e.preventDefault(); // Prevenir el comportamiento por defecto del enlace

    // Mostrar SweetAlert
    Swal.fire({
      title: 'Cargando...',
      allowOutsideClick: false,
      showConfirmButton: false,
      onBeforeOpen: () => {
        Swal.showLoading();
      }
    });

    // Hacer una solicitud AJAX para obtener los resultados de "Otros Egresos"
    $.ajax({
      url: '{{ route("admin.otros-egresos", ["nit" => $nit, "fecha" => $fecha_inicio]) }}',
      method: 'GET',
      success: function(response) {
        // Crear la estructura HTML de la tabla
        var html = '<table class="table-sm  table table-striped table-bordered table-striped  datatable-informe w-100">';
        html += '<thead class="thead-dark"><tr><th>Cuenta</th><th>Nombre Específico</th><th>Saldo Total</th></tr></thead>';
        html += '<tbody>';
        // Iterar sobre los resultados y construir las filas de la tabla
        response.forEach(function(resultado) {
            html += '<tr>';
            html += '<td>' + resultado.grupo_cuenta + '</td>';
            html += '<td>' + resultado.nombre_especifico + '</td>';
            html += '<td class="text-end"> ' + resultado.saldo_total + '</td>';
            html += '</tr>';
        });
        
        html += '</tbody></table>';
        // Colocar los resultados en el cuerpo del modal
        $('#otrosIngresosModalBody').html(html);
        // Mostrar el modal
        $('#otrosIngresosModal').modal('show');
      },
      error: function() {
        // Manejar errores de la solicitud AJAX
        console.error('Hubo un error en la solicitud.');
      }
    });
  });
</script>
@endsection
