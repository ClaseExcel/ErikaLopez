<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Informe</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            /* Reducir tamaño de fuente */
            margin: 0;
            /* Ajustar márgenes */
            padding: 0;
            /* Ajustar padding */
            background-color: #ffffff;
        }

        .container {
            width: 100%;
            padding: 10px;
            /* Ajustar padding */
            box-sizing: border-box;
            margin: 0 auto;
            /* Centrar el contenedor */
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            page-break-inside: auto;
        }

        th,
        td {
            border: 1px solid #dddddd;
            padding: 2px;
            /* Reducir padding en celdas */
            text-align: left;
            font-size: 13px;
            /* Reducir tamaño de fuente en celdas */
        }

        th {
            background-color: #919396;
            color: white;
            text-transform: uppercase;
            font-size: 15px;
            /* Reducir tamaño de fuente en encabezados */
        }

        th:nth-child(1),
        td:nth-child(1) {
            width: 15%;
            /* Ajusta el ancho de la primera columna */
        }

        th:nth-child(2),
        td:nth-child(2) {
            width: 25%;
            /* Ajusta el ancho de la segunda columna */
        }

        @page {
            margin: 10mm;
            /* Ajustar márgenes para la impresión */
        }

        td {
            font-size: 15px;
        }

        td.numeric {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        thead {
            display: table-header-group;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        img {
            width: 20%;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .table-title {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            margin: 20px 0;
            color: #333333;
        }

        .section-title {
            text-align: center;
            font-size: 17px;
            font-weight: bold;
            color: #333333;
            margin-top: 20px;
        }

        .section-subtitle {
            text-align: center;
            font-size: 15px;
            font-weight: normal;
            color: #666666;
            margin-bottom: 20px;
        }

        /* Estilos específicos solo para la tabla con clase 'datatable-informe' */
        table.datatable-informe {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        table.datatable-informe th,
        table.datatable-informe td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }

        table.datatable-informe th {
            background-color: #919396;
            color: #fff;
        }

        table.datatable-informe td {
            background-color: #f8f9fa;
        }

        table.datatable-informe h6 {
            margin: 0;

        }

        /* Ajusta el ancho de las columnas de manera proporcional */
        table.datatable-informe td.text-end {
            text-align: right;
        }

        /* Evita que el texto de las celdas se corte o salga de la tabla */
        table.datatable-informe {
            table-layout: fixed;
            word-wrap: break-word;
        }

        .datatable-informe {
            width: 100%;
            border-collapse: collapse;
        }

        .datatable-informe th,
        .datatable-informe td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ccc;
        }

        /* Alternar color de filas */
        .datatable-informe tr:nth-child(even) {
            background-color: #e0d6d6;
        }

        .datatable-informe tr:nth-child(odd) {
            background-color: #ffffff;
        }

        /* Estilo solo para la primera columna en la tabla datatable-informe */
        .datatable-informe td:first-child {
            font-size: 20px;
            white-space: nowrap;
        }

        /* Ajuste de bordes y eliminación de espaciado */
        .datatable-informe td,
        .datatable-informe th {
            border: 1px solid #ccc;
            margin: 0;
            padding: 8px;
            font-size: 12px ;
        }

        h6 {
            font-weight: normal !important;
            text-align: left;
        }
        .datatable-informe th,
        .datatable-informe td {
            width: 150px; /* Ajusta el ancho según tus necesidades */
        }
        .datatable-informe td {
            width: 150px; /* Ajusta el ancho según tus necesidades */
        }

        /* Primera columna (descripción) del body */
        datatable-informe td:first-child {
            white-space: pre-line !important; /* Permite saltos en espacios y saltos de línea reales */
            word-break: break-word;
            overflow-wrap: break-word;
            vertical-align: top;
            font-size: 13px;
            line-height: 1.2;
            padding: 6px;
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="container">
         <table style="width: 100%; border-collapse: collapse; border: none; margin-top: -30px;">
            <tr>
                <td style="width: 20%; border: none;"></td>

                <td style="width: 60%; text-align: center; vertical-align: top; border: none;">
                    <div style="line-height: 1.2; margin-top: 0;">
                        <h2 style="margin: 0;">{{ $compania }}</h2>
                        <h3 style="margin: 0;">NIT - {{$nit}}</h3>
                        <h3 style="margin: 0;">{{ $informe }}</h3>
                        <p style="margin: 0;"><b>{{ $fecha }}</b></p>
                    </div>
                </td>

                <td rowspan="1" style="width: 20%; text-align: center; vertical-align: middle; border: none;">
                    <img id="logo" src="data:image/jpeg;base64,{{ $base64ImageLogo }}" alt="Logo" style="width: 150px; height: auto; display: block; margin: 0 auto;">
                </td>
            </tr>
        </table>
        <br>
        @if ($tipoinforme == 0)
            <div class="table-responsive">
                <table class="table table-sm table-bordered  w-100" id="tableingresos">
                    <thead class="card-header">
                        <tr>
                            <th class="text-center">Cuenta</th>
                            <th class="text-center">Nombre Cuenta</th>
                            <th class="text-center">Saldo Inicial</th>
                            <th class="text-center">Débito</th>
                            <th class="text-center">Crédito</th>
                            <th class="text-center">Saldo final</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tableData as $item)
                            <tr>
                                <td>
                                    @if ($item['nombre_orden_informes'] === 'ACTIVOS DE MENOR CUANTIA')
                                        5395
                                    @else
                                        {{ $item['cuenta'] }}
                                    @endif
                                </td>
                                <td class="text-left">{{ $item['nombre_orden_informes'] }}</td>
                                <td class="sumable">${{ $item['saldoinicial'] }}</td>
                                <td class="sumable">${{ $item['debitos'] }}</td>
                                <td class="sumable">${{ $item['creditos'] }}</td>
                                <td class="sumable">${{ $item['saldo_mov'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @elseif($tipoinforme == 1)
   
            <div class="row table-responsive">
                @php
                    if (!function_exists('parse_number')) {
                        function parse_number($number) {
                            // Reemplaza los puntos de miles por nada
                            $number = str_replace('.', '', $number);
                            // Reemplaza la coma decimal por un punto
                            $number = str_replace(',', '.', $number);
                            return round(floatval($number));
                        }
                    }
                @endphp
                <table class="table table-sm table-striped table-bordered   datatable-informe w-100">
                    <thead class="card-header">
                        <tr>
                            <th class="text-center">DESCRIPCIÓN</th>
                            @php $prevMes = null; @endphp
                            @foreach (array_keys($tableData) as $mes)
                                @if ($mes !== 'descripcionct'  && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                    <th class="text-center">{{ $mes }}</th>
                                @endif
                            @endforeach
                            <th class="text-center">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                            @php
                            $descripcionesMostradas = [];
                            @endphp

                            @php
                            // Define las cuentas que deseas excluir
                            // $excluirCuentas = ['Gastos', 'Costos de venta','Gastos de personal','Gastos de Personal'];
                            $excluirCuentas = ['Ingresos','Devoluciones en ventas','Total Ventas Netas'];
                             // Encuentra la posición de la cuenta 6 en el array Cuenta
                             $posicionCuenta6 = array_search(6, $tableData['Cuenta']);
                             $posicionCuenta42 = array_search(42, $tableData['Cuenta']);
                             $posicionCuenta4210 = array_search(4210, $tableData['Cuenta']);
                             $posicionCuenta53 = array_search(53, $tableData['Cuenta']);
                             $posicionCuenta5305 = array_search(5305, $tableData['Cuenta']);
                            @endphp
                            @foreach ($tableData['descripcionct'] as $index => $descripcionct)
                            @php
                            // Contar el número de columnas de datos
                            $numeroDeColumnas = count(array_keys($tableData))-2; // Restamos 'descripcionct', 'Total Mes', y 'Cuenta'
                            @endphp
                            @if ($descripcionct !== 'Total Mes' && $descripcionct !== 'Cuenta' && !in_array($descripcionct, $descripcionesMostradas))
                                @if (in_array($descripcionct, $excluirCuentas))
                                    <tr>
                                        @php
                                            $totalFila = 0;
                                        @endphp
                                        <td>
                                            <h6 style="margin: 0;">
                                                @if($numeroDeColumnas >= 4)
                                                    {!! implode('<br>', explode(' ', $descripcionct)) !!}
                                                @else
                                                    {{ $descripcionct }}
                                                @endif
                                            </h6>
                                        </td>

                                        @foreach (array_keys($tableData) as $mes)
                                            @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                                 @php
                                                    // Usa la función parse_number para convertir el valor
                                                    $valor = !empty($tableData[$mes][$index]) ? parse_number($tableData[$mes][$index]) : 0;
                                                    $totalFila += $valor;
                                                @endphp
                                                <td class="text-end">
                                                    {{
                                                        !empty($tableData[$mes][$index])
                                                            ? number_format(
                                                                round((float) str_replace(',', '.', str_replace('.', '', ltrim($tableData[$mes][$index], '-')))),
                                                                0,
                                                                ',',
                                                                '.'
                                                            )
                                                            : '-'
                                                    }}
                                                </td>
                                            @endif
                                        @endforeach
                                         <td class="text-end"><b>{{ $totalFila != 0 ? number_format(abs($totalFila), 0) : '-' }}</b></td>
                                    </tr>
                                @endif
                                @php
                                    $descripcionesMostradas[] = $descripcionct;
                                @endphp
                            @endif
                            @endforeach
                           
                            <tr>
                                @if($numeroDeColumnas >= 4)
                                <td><h6><b>Ventas Netas</b></h6></td>
                                @else
                                <td><h6><b>Total Ventas Netas</b></h6></td>
                                @endif
                                 @php
                                    $totalFila = 0;
                                    $prevValor = null;
                                @endphp
                                @foreach (array_keys($tableData) as $mes)
                                    @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                        @php
                                            // Usa la función parse_number para convertir el valor
                                            $valor = !empty($totales[$mes]['Total ventas netas']) ? parse_number($totales[$mes]['Total ventas netas']) : 0;
                                            $totalFila += $valor;
                                        @endphp
                                        <td class="text-end">
                                            <b>
                                                {{
                                                    !empty($totales[$mes]['Total ventas netas'])
                                                        ? '$' . number_format(
                                                            round((float) str_replace(',', '.', str_replace('.', '', ltrim($totales[$mes]['Total ventas netas'], '-')))),
                                                            0,
                                                            ',',
                                                            '.'
                                                        )
                                                        : '-'
                                                }}
                                            </b>
                                        </td>

                                    @endif
                                @endforeach
                                <td class="text-end"><b>${{ $totalFila != 0 ? number_format($totalFila, 0) : '-' }}</b></td>
                            </tr>
                            <tr>
                                @if($numeroDeColumnas >= 4)
                                <td><h6><b>COSTO<br> MERCANCIA</b></h6></td>
                                @else
                                <td><h6><b>COSTO MERCANCIA VENDIDA</b></h6></td>
                                @endif
                                @for ($i = 0; $i <= $numeroDeColumnas; $i++)
                                    <td></td>
                                @endfor
                            </tr>
                            <tr>
                                @if($numeroDeColumnas >= 4)
                                <td><h6>Costo<br>ventas</h6></td>
                                @else
                                <td><h6>Costo de ventas</h6></td>
                                @endif
                               @php
                                    $totalFila = 0;
                                @endphp
                                @foreach (array_keys($tableData) as $mes)
                                    @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                        @php
                                            // Usa la función parse_number para convertir el valor
                                            $valor = !empty($posicionCuenta6) ? parse_number($tableData[$mes][$posicionCuenta6]) : 0;
                                            $totalFila += $valor;
                                        @endphp    
                                    <td class="text-end">
                                            <b>
                                                {{
                                                    !empty($tableData[$mes][$posicionCuenta6])
                                                        ? '$' . number_format(
                                                            round((float) str_replace(',', '.', str_replace('.', '', ltrim($tableData[$mes][$posicionCuenta6], '-')))),
                                                            0,
                                                            ',',
                                                            '.'
                                                        )
                                                        : '-'
                                                }}
                                            </b>
                                        </td>

                                    @endif
                                @endforeach
                                <td class="text-end"><b>{{ $totalFila != 0 ? number_format($totalFila, 0) : '-' }}</b></td>
                            </tr>
                            <!-- Agregar una fila adicional para mostrar los totales del grupo Ventas -->
                            <tr>
                                @if($numeroDeColumnas >= 4)
                                <td><h6><b>Margen<br> Bruto <br> ventas</b></h6></td>
                                @else
                                <td><h6><b>Margen Bruto ventas</b></h6></td>
                                @endif
                                @php
                                    $totalFila = 0;
                                    $prevValor = null;
                                @endphp
                                @foreach (array_keys($tableData) as $mes)
                                    @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                    @php
                                            // Usa la función parse_number para convertir el valor
                                            $valor = !empty($totales[$mes]['Utilidad bruta ventas']) ? parse_number($totales[$mes]['Utilidad bruta ventas']) : 0;
                                            $totalFila += $valor;
                                        @endphp    
                                        <td class="text-end">
                                            <b>
                                                {{
                                                    !empty($totales[$mes]['Utilidad bruta ventas'])
                                                        ? '$' . number_format(
                                                            round((float) str_replace(',', '.', str_replace('.', '', ltrim($totales[$mes]['Utilidad bruta ventas'], '-')))),
                                                            0,
                                                            ',',
                                                            '.'
                                                        )
                                                        : '-'
                                                }}
                                            </b>
                                        </td>
                                    @endif
                                @endforeach
                                <td class="text-end"><b>${{ $totalFila != 0 ? number_format($totalFila, 0) : '-' }}</b></td>
                            </tr>
                            <tr>
                                <td><h6><b>GASTOS</b></h6></td>
                                @for ($i = 0; $i <= $numeroDeColumnas; $i++)
                                    <td></td>
                                @endfor
                            </tr>
                           <!-- Agregar una fila adicional para mostrar los totales del grupo Gastos de Venta -->
                           <tr>
                            
                            @if($numeroDeColumnas >= 4)
                                <td><h6><b>Gastos<br> Operacionales</b></h6></td>
                                @else
                                <td><h6><b>Gastos Operacionales</b></h6></td>
                            @endif
                            @php
                                $totalFila = 0;
                            @endphp
                            @foreach (array_keys($tableData) as $mes)
                                @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                    @php
                                        $valor = !empty($totales[$mes]['Total gastos admón y ventas']) ? parse_number($totales[$mes]['Total gastos admón y ventas']) : 0;
                                        $totalFila += $valor;
                                    @endphp    
                                    <td class="text-end">
                                        <b>
                                            {{
                                                !empty($totales[$mes]['Total gastos admón y ventas'])
                                                    ? '$' . number_format(
                                                        round((float) str_replace(',', '.', str_replace('.', '', ltrim($totales[$mes]['Total gastos admón y ventas'], '-')))),
                                                        0,
                                                        ',',
                                                        '.'
                                                    )
                                                    : '-'
                                            }}
                                        </b>
                                    </td>
                                @endif
                            @endforeach
                             <td class="text-end"><b>{{ $totalFila != 0 ? number_format($totalFila, 0) : '-' }}</b></td>
                        </tr>
                        @php
                            $filasNormales = [];
                            $filasGastosOperacionales = [];
                        @endphp

                        @foreach ($tableData['descripcionct'] as $index => $descripcionct)
                            @if (isset($tableData['Cuenta'][$index]) && substr($tableData['Cuenta'][$index], 0, 1) === '5' && substr($tableData['Cuenta'][$index], 0, 2) !== '53' && substr($tableData['Cuenta'][$index], 0, 2) !== '54'  && !in_array($descripcionct, ['OTROS INGRESOS']) && !in_array($descripcionct, ['OTROS EGRESOS']))
                                @php
                                    $totalFila = 0;
                                    $descripcionctMostrar = ($descripcionct === 'Gastos operacionales') ? 'Gastos de ventas' : $descripcionct;
                                    
                                    
                                    if($numeroDeColumnas >= 4){
                                        $palabras = preg_split('/\s+/', trim($descripcionctMostrar)); // divide por 1 o más espacios
                                        $descripcionFormateada = implode('<br>', $palabras);
                                    }else{
                                        $descripcionFormateada = $descripcionctMostrar;
                                    }
                                    $fila = "<tr>";
                                    $fila .= "<td><h6 style='margin: 0;'>$descripcionFormateada</h6></td>";
                                    foreach (array_keys($tableData) as $mes) {
                                        if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta') {
                                            $valor = !empty($tableData[$mes][$index]) ? parse_number($tableData[$mes][$index]) : 0;
                                            $totalFila += $valor;
                                            $fila .= "<td class=\"text-end\">" . (!empty($tableData[$mes][$index])
                                                ? number_format(
                                                    round((float) str_replace(',', '.', str_replace('.', '', ltrim($tableData[$mes][$index], '-')))),
                                                    0,
                                                    ',',
                                                    '.'
                                                )
                                                : '-') . "</td>";
                                        }
                                    }
                                    $fila .= "<td class=\"text-end\"><b>" . ($totalFila != 0 ? number_format($totalFila, 0) : '-') . "</b></td>";
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
                                @if($numeroDeColumnas >= 4)
                                <td><h6><b>Utilidad<br> Operacional <br></b></h6></td>
                                @else
                                <td><h6><b>Utilidad Operacional</b></h6></td>
                                @endif
                                @php
                                    $totalFila = 0;
                                @endphp
                                @foreach (array_keys($tableData) as $mes)
                                    @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                        @php
                                            $valor = !empty($totales[$mes]['Utilidad operacional']) ? parse_number($totales[$mes]['Utilidad operacional']) : 0;
                                            $totalFila += $valor;
                                        @endphp
                                        <td class="text-end">
                                            <b>
                                                {{
                                                    !empty($totales[$mes]['Utilidad operacional'])
                                                        ? '$' . number_format(
                                                            round((float) str_replace(',', '.', str_replace('.', '', ltrim($totales[$mes]['Utilidad operacional'], '-')))),
                                                            0,
                                                            ',',
                                                            '.'
                                                        )
                                                        : '-'
                                                }}
                                            </b>
                                        </td>

                                    @endif
                                @endforeach
                                <td class="text-end"><b>{{ $totalFila != 0 ? number_format($totalFila, 0) : '-' }}</b></td>
                            </tr>
                            <!-- Agregar una fila adicional para mostrar los totales de grupo otros ingresos -->
                        <tr>
                            
                                @if($numeroDeColumnas >= 4)
                                <td><h6><b>Otros<br> Ingresos</b></h6></td>
                                @else
                                <td><h6><b>Otros Ingresos</b></h6></td>
                                @endif
                            
                            {{-- @include('admin.estadosfinancieros.otros-ingresos',['nit' => $nit, 'fecha' => $fecha_inicio]) --}}
                            @php
                                $totalFila = 0;
                            @endphp
                            @foreach (array_keys($tableData) as $mes)
                                @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                    @php
                                        $valor = !empty($tableData[$mes][$posicionCuenta42]) ? parse_number($tableData[$mes][$posicionCuenta42]) : 0;
                                        $totalFila += $valor;
                                    @endphp
                                    <td class="text-end">
                                        {{
                                            !empty($tableData[$mes][$posicionCuenta42])
                                                ? number_format(
                                                    round((float) str_replace(',', '.', str_replace('.', '', ltrim($tableData[$mes][$posicionCuenta42], '-')))),
                                                    0,
                                                    ',',
                                                    '.'
                                                )
                                                : '-'
                                        }}
                                    </td>
                                @endif
                            @endforeach
                           <td class="text-end"><b>{{ $totalFila != 0 ? number_format(abs($totalFila), 0) : '-' }}</b></td>
                        </tr>
                        <tr>
                            @if($numeroDeColumnas >= 4)
                                <td><h6>Ingresos<br>Financieros</h6></td>
                                @else
                                <td><h6>Ingresos Financieros</h6></td>
                                @endif
                            @php
                                $totalFila = 0;
                            @endphp
                            @foreach (array_keys($tableData) as $mes)
                                @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                    @php
                                        $valor = !empty($tableData[$mes][$posicionCuenta4210]) ? parse_number($tableData[$mes][$posicionCuenta4210]) : 0;
                                        $totalFila += $valor;
                                    @endphp
                                    <td class="text-end">
                                        {{
                                            !empty($tableData[$mes][$posicionCuenta4210])
                                                ? number_format(
                                                    round((float) str_replace(',', '.', str_replace('.', '', ltrim($tableData[$mes][$posicionCuenta4210], '-')))),
                                                    0,
                                                    ',',
                                                    '.'
                                                )
                                                : '-'
                                        }}
                                    </td>
                                @endif
                            @endforeach
                           <td class="text-end">{{ $totalFila != 0 ? number_format($totalFila) : '-' }}</td>
                        </tr>
                            <tr>
                                    @if($numeroDeColumnas >= 4)
                                    <td><h6><b>Otros<br> Egresos</b></h6></td>
                                    @else
                                    <td><h6><b>Otros Egresos</b></h6></td>
                                    @endif
                                    @php
                                    $totalFila = 0;
                                @endphp
                                @foreach (array_keys($tableData) as $mes)
                                    @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                        @php
                                            $valor = !empty($tableData[$mes][$posicionCuenta53]) ? parse_number($tableData[$mes][$posicionCuenta53]) : 0;
                                            $totalFila += $valor;
                                        @endphp
                                        <td class="text-end">
                                            {{
                                                !empty($tableData[$mes][$posicionCuenta53])
                                                    ? number_format(
                                                        round((float) str_replace(',', '.', str_replace('.', '', ltrim($tableData[$mes][$posicionCuenta53], '-')))),
                                                        0,
                                                        ',',
                                                        '.'
                                                    )
                                                    : '-'
                                            }}
                                        </td>
                                    @endif
                                @endforeach
                                <td class="text-end"><b>{{ $totalFila != 0 ? number_format($totalFila, 0) : '-' }}</b></td>
                            </tr> 
                            <tr>
                                
                                @if($numeroDeColumnas >= 4)
                                <td><h6>Gastos<br>Financieros</h6></td>
                                @else
                                <td><h6>Gastos Financieros</h6></td>
                                @endif
                                @php
                                    $totalFila = 0;
                                @endphp
                                @foreach (array_keys($tableData) as $mes)
                                    @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                        @php
                                            $valor = !empty($tableData[$mes][$posicionCuenta5305]) ? parse_number($tableData[$mes][$posicionCuenta5305]) : 0;
                                            $totalFila += $valor;
                                        @endphp
                                        <td class="text-end">
                                            {{
                                                !empty($tableData[$mes][$posicionCuenta5305])
                                                    ? number_format(
                                                        round((float) str_replace(',', '.', str_replace('.', '', ltrim($tableData[$mes][$posicionCuenta5305], '-')))),
                                                        0,
                                                        ',',
                                                        '.'
                                                    )
                                                    : '-'
                                            }}
                                        </td>  
                                    @endif
                                @endforeach
                                <td class="text-end">{{ $totalFila != 0 ? number_format($totalFila, 0) : '-' }}</td>
                            </tr> 
                                <!-- Agregar una fila adicional para mostrar los totales de Utilidad antes de Impuestos -->
                                 <tr>
                                    @if($numeroDeColumnas >= 4)
                                        <td><h6><b>Utilidad<br>antes<br> impuestos</b></h6></td>
                                    @else
                                        <td><h6>Utilidad antes de Impuestos</h6></td>
                                    @endif
                                    @php
                                        $totalFila = 0;
                                    @endphp
                                    @foreach (array_keys($tableData) as $mes)
                                        @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                            @php
                                                $valor = !empty($totales[$mes]['Utilidad antes de imptos']) ? parse_number($totales[$mes]['Utilidad antes de imptos']) : 0;
                                                $totalFila += $valor;
                                            @endphp
                                            <td class="text-end">
                                                <b>
                                                    {{
                                                        !empty($totales[$mes]['Utilidad antes de imptos'])
                                                            ? '$' . number_format(
                                                                round((float) str_replace(',', '.', str_replace('.', '', ltrim($totales[$mes]['Utilidad antes de imptos'], '-')))),
                                                                0,
                                                                ',',
                                                                '.'
                                                            )
                                                            : '-'
                                                    }}
                                                </b>
                                            </td>
                                        @endif
                                    @endforeach
                                    <td class="text-end"><b>{{ $totalFila != 0 ? number_format($totalFila, 0) : '-' }}</b></td>
                                </tr> 
                                <tr>
                                    @if($numeroDeColumnas >= 4)
                                        <td><h6><b>Provision<br>impuesto<br>renta</b></h6></td>
                                    @else
                                        <td><h6>Provision impuesto de renta</h6></td>
                                    @endif
                                    @php
                                        $totalFila = 0;
                                    @endphp
                                    @foreach (array_keys($tableData) as $mes)
                                        @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                            @php
                                                $valor = !empty($totales[$mes]['Provision impuesto renta']) ? parse_number($totales[$mes]['Provision impuesto renta']) : 0;
                                                $totalFila += $valor;   
                                            @endphp
                                            <td class="text-end">
                                                <b>
                                                    {{
                                                        !empty($totales[$mes]['Provision impuesto renta'])
                                                            ? '$' . number_format(
                                                                round((float) str_replace(',', '.', str_replace('.', '', ltrim($totales[$mes]['Provision impuesto renta'], '-')))),
                                                                0,
                                                                ',',
                                                                '.'
                                                            )
                                                            : '-'
                                                    }}
                                                </b>
                                            </td>
                                        @endif
                                    @endforeach
                                    <td class="text-end"><b>{{ $totalFila != 0 ? number_format($totalFila, 0) : '-' }}</b></td>
                                </tr> 
                                
                                <!-- Agregar una fila adicional para mostrar los totales de Utilidad neta -->
                                <tr>
                                    
                                    @if($numeroDeColumnas >= 4)
                                        <td><h6><b>Utilidad<br>Perdida<br> neta</b></h6></td>
                                    @else
                                        <td><h6>Utilidad/Perdida neta</h6></td>
                                    @endif
                                    @php
                                        $totalFila = 0;
                                    @endphp
                                    @foreach (array_keys($tableData) as $mes)
                                        @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                            @php
                                                $valor = !empty($totales[$mes]['Utilidad neta']) ? parse_number($totales[$mes]['Utilidad neta']) : 0;
                                                $totalFila += $valor;
                                            @endphp
                                            <td class="text-end">
                                                <b>
                                                    {{
                                                        !empty($totales[$mes]['Utilidad neta'])
                                                            ? '$' . number_format(
                                                                round((float) str_replace(',', '.', str_replace('.', '', ltrim($totales[$mes]['Utilidad neta'], '-')))),
                                                                0,
                                                                ',',
                                                                '.'
                                                            )
                                                            : '-'
                                                    }}
                                                </b>
                                            </td>
                                        @endif
                                    @endforeach
                                    <td class="text-end"><b>{{ $totalFila != 0 ? number_format($totalFila, 0) : '-' }}</b></td>
                                </tr>

                    </tbody>
                </table>
            </div>
        @elseif($tipoinforme == 4)
        @php
        
            $fechaInicio = \Carbon\Carbon::parse($fecha)->firstOfMonth();
                $mesLimite = $fechaInicio->month; // 1 a 12
                $meses = [
                    1 => 'enero',
                    2 => 'febrero',
                    3 => 'marzo',
                    4 => 'abril',
                    5 => 'mayo',
                    6 => 'junio',
                    7 => 'julio',
                    8 => 'agosto',
                    9 => 'septiembre',
                    10 => 'octubre',
                    11 => 'noviembre',
                    12 => 'diciembre',
                ];

                $mesesMostrar = array_slice($meses, 0, $mesLimite, true);
        @endphp
        <style>
            /* 🔥 TU FIX AQUÍ */
            .datatable-informe th:first-child,
            .datatable-informe td:first-child {
                width: 4% !important;
                font-size: 10px;
            }
            .datatable-informe th:nth-child(2),
            .datatable-informe td:nth-child(2) {
                width: 13% !important;
                font-size: 12px;
            }
            .datatable-informe th:nth-child(n+3),
            .datatable-informe td:nth-child(n+3) {
                width: 80px !important;
                font-size: 10px;
                text-align: right;
            }
            .titulos{
                background:#d0ebfb !important; 
                font-weight:bold !important; 
                font-size: 10px !important;
                text-align: start !important;
            }
        </style>
            <div class="row table-responsive">
               <div class="table-responsive">
                    <table class="table table-sm table-striped table-bordered datatable-informe w100">
                        
                        <thead sclass="card-header" >
                            <tr>
                                <th>Cuenta</th>
                                <th>Descripción</th>

                                @foreach($mesesMostrar as $mes)
                                    <th class="text-end">{{ ucfirst($mes) }}</th>
                                @endforeach

                                <th class="text-end">Acumulado</th>
                            </tr>
                        </thead>
                        @php

                            $filtroMovimiento = function($fila) use ($mesesMostrar){

                                foreach($mesesMostrar as $mes){
                                    if(($fila->$mes ?? 0) != 0){
                                        return true;
                                    }
                                }

                                if(($fila->total_acumulado ?? 0) != 0){
                                    return true;
                                }

                                return false;
                            };

                            $grupo4 = $tableData
                                        ->filter(fn($f) => str_starts_with($f->cuenta,'4'))
                                        ->filter($filtroMovimiento);

                            $grupo5 = $tableData
                                        ->filter(fn($f) => str_starts_with($f->cuenta,'5'))
                                        ->filter($filtroMovimiento);

                            $grupo6 = $tableData
                                        ->filter(fn($f) => str_starts_with($f->cuenta,'6'))
                                        ->filter($filtroMovimiento);

                            $grupo7 = $tableData
                                        ->filter(fn($f) => str_starts_with($f->cuenta,'7'))
                                        ->filter($filtroMovimiento);
                            
                            function money($valor){
                                return $valor < 0
                                    ? '-$' . number_format(abs($valor), 0, ',', '.')
                                    : '$' . number_format($valor, 0, ',', '.');
                            }

                            @endphp
                        <tbody>
                        {{-- ===== Ingresos (6) ===== --}}
                        <tr>
                            <td class="titulos" colspan="{{ 2 + count($mesesMostrar) + 1 }}">
                                Ingresos 
                            </td>
                        </tr>

                        @foreach($grupo4 as $fila)
                            <tr>
                            <td>{{ $fila->cuenta }}</td>
                            <td>{{ $fila->descripcion }}</td>

                            @foreach($mesesMostrar as $mes)
                                <td class="text-end">
                                    {{ money($fila->$mes ?? 0) }}
                                </td>
                            @endforeach

                            <td class="text-end">
                                {{ money($fila->$mes ?? 0) }}
                            </td>
                        </tr>

                        @endforeach

                        {{-- Subtotal grupo 6 --}}
                        <tr>
                            <td class="titulos" colspan="2">Subtotal Ingresos</td>

                            @foreach($mesesMostrar as $mes)
                                <td class="titulos text-end">
                                    {{ money($grupo4->sum($mes) ?? 0) }}
                                </td>
                            @endforeach

                            <td class="titulos text-end">
                                {{ money($grupo4->sum('total_acumulado') ?? 0) }}
                            </td>
                        </tr>


                        {{-- Espacio coqueto --}}
                        <tr>
                            <td class="titulos" colspan="{{ 2 + count($mesesMostrar) + 1 }}" style="height:15px;"></td>
                        </tr>
                        {{-- ===== Gastos (6) ===== --}}
                        <tr style="background:#f4f9fc; font-weight:bold;">
                            <td class="titulos" colspan="{{ 2 + count($mesesMostrar) + 1 }}">
                                Gastos 
                            </td>
                        </tr>

                        @foreach($grupo5 as $fila)
                            <tr>
                            <td>{{ $fila->cuenta }}</td>
                            <td>{{ $fila->descripcion }}</td>

                            @foreach($mesesMostrar as $mes)
                                <td class="text-end">
                                    {{ money($fila->$mes ?? 0) }}
                                </td>
                            @endforeach

                            <td class="text-end">
                                {{ money($fila->total_acumulado ?? 0) }}
                            </td>
                        </tr>

                        @endforeach

                        {{-- Subtotal grupo 4 --}}
                        <tr style="background:#e8f4fb; font-weight:bold;">
                            <td class="titulos" colspan="2">Subtotal Gastos</td>

                            @foreach($mesesMostrar as $mes)
                                <td class="titulos text-end">
                                    {{ money($grupo5->sum($mes) ?? 0) }}
                                </td>
                            @endforeach

                            <td class="titulos text-end">
                                {{ money($grupo5->sum('total_acumulado') ?? 0) }}
                            </td>
                        </tr>


                        {{-- Espacio coqueto --}}
                        <tr>
                            <td class="titulos" colspan="{{ 2 + count($mesesMostrar) + 1 }}" style="height:15px;"></td>
                        </tr>

                        {{-- ===== COSTOS (6) ===== --}}
                        <tr style="background:#f4f9fc; font-weight:bold;">
                            <td class="titulos" colspan="{{ 2 + count($mesesMostrar) + 1 }}">
                                Costos 
                            </td>
                        </tr>

                        @foreach($grupo6 as $fila)
                            <tr>
                            <td>{{ $fila->cuenta }}</td>
                            <td>{{ $fila->descripcion }}</td>

                            @foreach($mesesMostrar as $mes)
                                <td class="text-end">
                                    {{ money($fila->$mes ?? 0) }}
                                </td>
                            @endforeach

                            <td class="text-end">
                                {{ money($fila->total_acumulado ?? 0) }}
                            </td>
                        </tr>

                        @endforeach

                        {{-- Subtotal grupo 6 --}}
                        <tr style="background:#e8f4fb; font-weight:bold;">
                            <td class="titulos" colspan="2">Subtotal Costos</td>

                            @foreach($mesesMostrar as $mes)
                                <td class="titulos text-end">
                                    {{ money($grupo6->sum($mes) ?? 0) }}
                                </td>
                            @endforeach

                            <td class="titulos text-end">
                                {{ money($grupo6->sum('total_acumulado') ?? 0) }}
                            </td>
                        </tr>


                        {{-- Espacio coqueto --}}
                        <tr>
                            <td class="titulos" colspan="{{ 2 + count($mesesMostrar) + 1 }}" style="height:15px;"></td>
                        </tr>


                        {{-- ===== GASTOS (7) ===== --}}
                        <tr style="background:#f4f9fc; font-weight:bold;">
                            <td class="titulos" colspan="{{ 2 + count($mesesMostrar) + 1 }}">
                                Costos de producción 
                            </td>
                        </tr>

                        @foreach($grupo7 as $fila)
                            <tr>
                            <td>{{ $fila->cuenta }}</td>
                            <td>{{ $fila->descripcion }}</td>

                            @foreach($mesesMostrar as $mes)
                                <td class="text-end">
                                    {{ money($fila->$mes ?? 0) }}
                                </td>
                            @endforeach

                            <td class="text-end">
                                {{ money($fila->total_acumulado ?? 0) }}
                            </td>
                        </tr>

                        @endforeach

                        {{-- Subtotal grupo 7 --}}
                        <tr style="background:#e8f4fb; font-weight:bold;">
                            <td class="titulos" colspan="2">Subtotal Costos</td>

                            @foreach($mesesMostrar as $mes)
                                <td class="titulos text-end">
                                    {{ money($grupo7->sum($mes) ?? 0) }}
                                </td>
                            @endforeach

                            <td class="titulos text-end">
                                {{ money($grupo7->sum('total_acumulado') ?? 0) }}
                            </td>
                        </tr>


                        {{-- ===== TOTAL GENERAL ===== --}}
                        <tr class="total-general">
                                <td  style="background:#3fbdee; color:white; font-weight:bold;" colspan="2">UTILIDAD DEL EJERCICIO</td>

                                @foreach($mesesMostrar as $mes)
                                    <td style="background:#3fbdee; color:white; font-weight:bold;" class="text-end">
                                        {{ number_format(
                                            $grupo4->sum($mes) 
                                            - $grupo5->sum($mes) 
                                            - $grupo6->sum($mes) 
                                            - $grupo7->sum($mes),
                                        0, ',', '.') }}
                                    </td>
                                @endforeach

                                <td style="background:#3fbdee; color:white; font-weight:bold;" class="text-end">
                                    {{ number_format(
                                        $grupo4->sum('total_acumulado') 
                                        - $grupo5->sum('total_acumulado') 
                                        - $grupo6->sum('total_acumulado') 
                                        - $grupo7->sum('total_acumulado'),
                                    0, ',', '.') }}
                                </td>
                            </tr>

                        </tbody>

                    </table>
                </div>
            </div>
        @else
            <div class="row table-responsive">
                @php
                    function parse_number($number)
                    {
                        // Reemplaza los puntos de miles por nada
                        $number = str_replace('.', '', $number);
                        // Reemplaza la coma decimal por un punto
                        $number = str_replace(',', '.', $number);
                        return floatval($number);
                    }
                @endphp
                <table class="table table-sm table-striped table-bordered table-striped  datatable-informe w-100">
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-center">DESCRIPCIÓN</th>
                            @php $prevMes = null; @endphp
                            @foreach (array_keys($tableData) as $mes)
                                @if ($mes !== 'descripcionct'  && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                    <th class="text-center">{{ $mes }}</th>
                                @endif
                            @endforeach
                            <th class="text-center">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $descripcionesMostradas = [];
                            $totalMes = false;
                        @endphp

                        @foreach ($tableData['descripcionct'] as $index => $descripcionct)
                            @if ($descripcionct !== 'Total Mes' && $descripcionct !== 'Cuenta' && !in_array($descripcionct, $descripcionesMostradas))
                                @if (in_array($descripcionct, ['VENTAS', 'Devoluciones en ventas', 'Total Ventas Netas']))
                                    <tr>
                                        @php
                                            $totalFila = 0;
                                        @endphp
                                        <td >
                                            <h6>{{ $descripcionct }}</h6>
                                        </td>
                                        @foreach (array_keys($tableData) as $mes)
                                            @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                                @php
                                                    // Usa la función parse_number para convertir el valor
                                                    $valor = !empty($tableData[$mes][$index])
                                                        ? parse_number($tableData[$mes][$index])
                                                        : 0;
                                                    $totalFila += $valor;
                                                @endphp
                                                <td class="text-end">
                                                    {{ !empty($tableData[$mes][$index]) ? $tableData[$mes][$index] : '-' }}
                                                </td>
                                            @endif
                                        @endforeach
                                        <td class="text-end">
                                            <b>{{ $totalFila != 0 ? number_format($totalFila, 0) : '-' }}</b>
                                        </td>
                                    </tr>
                                @endif
                                @php
                                    $descripcionesMostradas[] = $descripcionct;
                                @endphp
                            @endif
                        @endforeach

                        <!-- Agregar una fila adicional para mostrar los totales del grupo Ventas -->
                        <tr>
                            <td>
                                <h6><b>Total Ventas Netas</b></h6>
                            </td>
                            @php
                                $totalFila = 0;
                            @endphp
                            @foreach (array_keys($tableData) as $mes)
                                @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                    @php
                                        // Usa la función parse_number para convertir el valor
                                        $valor = !empty($totales[$mes]['Total ventas netas'])
                                            ? parse_number($totales[$mes]['Total ventas netas'])
                                            : 0;
                                        $totalFila += $valor;
                                    @endphp
                                    <td class="text-end">
                                        <b>${{ !empty($totales[$mes]['Total ventas netas']) ? $totales[$mes]['Total ventas netas'] : '-' }}</b>
                                    </td>
                                @endif
                            @endforeach
                            <td class="text-end"><b>${{ $totalFila != 0 ? number_format($totalFila, 0) : '-' }}</b></td>
                        </tr>
                        <!-- Agregar una fila adicional para mostrar los totales de grupo Costo Mercancía Vendida -->
                        @php
                            // Contar el número de columnas de datos
                            $numeroDeColumnas = count(array_keys($tableData)) - 1; // Restamos 'descripcionct', 'Total Mes', y 'Cuenta'
                        @endphp
                        <tr>
                            <td><h6><b>COSTO MERCANCIA VENDIDA</b></h6></td>
                            @for ($i = 0; $i < $numeroDeColumnas; $i++)
                                <td></td>
                            @endfor
                        </tr>
                        <tr>

                            <td>
                                <h6>Costo de ventas</h6>
                            </td>
                            @php
                                $totalFila = 0;
                            @endphp
                            @foreach (array_keys($tableData) as $mes)
                                @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                    @php
                                        // Usa la función parse_number para convertir el valor
                                        $valor = !empty($tableData[$mes][11])
                                            ? parse_number($tableData[$mes][11])
                                            : 0;
                                        $totalFila += $valor;
                                    @endphp
                                    <td class="text-end">
                                        {{ !empty($tableData[$mes][11]) ? $tableData[$mes][11] : '-' }}</td>
                                @endif
                            @endforeach
                            <td class="text-end"><b>{{ $totalFila != 0 ? number_format($totalFila, 0) : '-' }}</b></td>
                        </tr>
                        <!-- Agregar una fila adicional para mostrar los totales de Utilidad Bruta Ventas -->
                        <tr>
                            <td>
                                <h6><b>Margen Bruto Ventas</b></h6>
                            </td>
                            @php
                                $totalFila = 0;
                            @endphp
                            @foreach (array_keys($tableData) as $mes)
                                @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                    @php
                                        $valor = !empty($totales[$mes]['Utilidad bruta ventas'])
                                            ? parse_number($totales[$mes]['Utilidad bruta ventas'])
                                            : 0;
                                        $totalFila += $valor;
                                    @endphp
                                    <td class="text-end">
                                        <b>${{ !empty($totales[$mes]['Utilidad bruta ventas']) ? $totales[$mes]['Utilidad bruta ventas'] : '-' }}</b>
                                    </td>
                                @endif
                            @endforeach
                            <td class="text-end"><b>{{ $totalFila != 0 ? number_format($totalFila, 0) : '-' }}</b></td>
                        </tr>
                        <tr>
                            <td><h6><b>GASTOS</b></h6></td>
                            @for ($i = 0; $i < $numeroDeColumnas; $i++)
                                <td></td>
                            @endfor
                        </tr>
                        <tr>
                            <td>
                                <h6><b>Gastos operacionales</b></h6>
                            </td>
                            @php
                                $totalFila = 0;
                            @endphp
                            @foreach (array_keys($tableData) as $mes)
                                @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                    @php
                                        $valor = !empty($totales[$mes]['Total gastos admón y ventas'])
                                            ? parse_number($totales[$mes]['Total gastos admón y ventas'])
                                            : 0;
                                        $totalFila += $valor;
                                    @endphp
                                    <td class="text-end">
                                        <b>${{ !empty($totales[$mes]['Total gastos admón y ventas']) ? $totales[$mes]['Total gastos admón y ventas'] : '-' }}</b>
                                    </td>
                                @endif
                            @endforeach
                            <td class="text-end"><b>{{ $totalFila != 0 ? number_format($totalFila, 0) : '-' }}</b></td>
                        </tr>
                        @foreach ($tableData['descripcionct'] as $index => $descripcionct)
                            @if (isset($tableData['Cuenta'][$index]) &&
                                    substr($tableData['Cuenta'][$index], 0, 1) === '5' &&
                                    substr($tableData['Cuenta'][$index], 0, 2) !== '54' &&
                                    !in_array($descripcionct, ['COSTO  MERCANCIA VENDIDA']) &&
                                    !in_array($descripcionct, ['Gastos financieros']) &&
                                    !in_array($descripcionct, ['Otros Egresos']))
                                <tr>
                                    @php
                                        $totalFila = 0;
                                    @endphp
                                    <td>
                                        <h6>{{ $descripcionct }}</h6>
                                    </td>
                                    @foreach (array_keys($tableData) as $mes)
                                        @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                            @php
                                                $valor = !empty($tableData[$mes][$index])
                                                    ? parse_number($tableData[$mes][$index])
                                                    : 0;
                                                $totalFila += $valor;
                                            @endphp
                                            <td class="text-end">
                                                {{ !empty($tableData[$mes][$index]) ? $tableData[$mes][$index] : '-' }}
                                            </td>
                                        @endif
                                    @endforeach
                                    <td class="text-end"><b>{{ $totalFila != 0 ? number_format($totalFila, 0) : '-' }}</b>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        
                        <!-- Agregar una fila adicional para mostrar los totales de Utilidad Operacional -->
                        <tr>
                            <td>
                                <h6><b>Utilidad Operacional</b></h6>
                            </td>
                            @php
                                $totalFila = 0;
                            @endphp
                            @foreach (array_keys($tableData) as $mes)
                                @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                    @php
                                        $valor = !empty($totales[$mes]['Utilidad operacional'])
                                            ? parse_number($totales[$mes]['Utilidad operacional'])
                                            : 0;
                                        $totalFila += $valor;
                                    @endphp
                                    <td class="text-end">
                                        <b>${{ !empty($totales[$mes]['Utilidad operacional']) ? $totales[$mes]['Utilidad operacional'] : '-' }}</b>
                                    </td>
                                @endif
                            @endforeach
                            <td class="text-end"><b>{{ $totalFila != 0 ? number_format($totalFila, 0) : '-' }}</b></td>
                        </tr>
                        <tr>
                            <td></td>
                            @for ($i = 0; $i < $numeroDeColumnas; $i++)
                                <td></td>
                            @endfor
                        </tr>
                        <!-- Agregar una fila adicional para mostrar los totales de grupo Costo Mercancía Vendida -->
                        <tr>
                            <td>
                                <h6><b>Otros Ingresos</b></h6>
                            </td>
                            @php
                                $totalFila = 0;
                            @endphp
                            @foreach (array_keys($tableData) as $mes)
                                @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                    @php
                                        $valor = !empty($tableData[$mes][2])
                                            ? parse_number($tableData[$mes][2])
                                            : 0;
                                        $totalFila += $valor;
                                    @endphp
                                    <td class="text-end">
                                        <b>${{ !empty($tableData[$mes][2]) ? $tableData[$mes][2] : '-' }}</b>
                                    </td>
                                @endif
                            @endforeach
                            <td class="text-end"><b>{{ $totalFila != 0 ? number_format($totalFila, 0) : '-' }}</b></td>
                        </tr>
                        <tr>
                            <td>
                                <h6><b>Ingresos financieros</b></h6>
                            </td>
                            @php
                                $totalFila = 0;
                            @endphp
                            @foreach (array_keys($tableData) as $mes)
                                @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                    @php
                                        $valor = !empty($tableData[$mes][18])
                                            ? parse_number($tableData[$mes][18])
                                            : 0;
                                        $totalFila += $valor;
                                    @endphp
                                    <td class="text-end">
                                        <b>${{ !empty($tableData[$mes][18]) ? $tableData[$mes][18] : '-' }}</b>
                                    </td>
                                @endif
                            @endforeach
                            <td class="text-end"><b>{{ $totalFila != 0 ? number_format($totalFila, 0) : '-' }}</b></td>
                        </tr>
                        <tr>
                            <td>
                                <h6><b>Otros Egresos</b></h6>
                            </td>
                            @php
                                $totalFila = 0;
                            @endphp
                            @foreach (array_keys($tableData) as $mes)
                                @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                    @php
                                        $valor = !empty($tableData[$mes][14])
                                            ? parse_number($tableData[$mes][14])
                                            : 0;
                                        $totalFila += $valor;
                                    @endphp
                                    <td class="text-end">
                                        <b>${{ !empty($tableData[$mes][14]) ? $tableData[$mes][14] : '-' }}</b>
                                    </td>
                                @endif
                            @endforeach
                            <td class="text-end"><b>{{ $totalFila != 0 ? number_format($totalFila, 0) : '-' }}</b></td>
                        </tr>
                        <tr>
                            <td>
                                <h6>Gastos financieros</h6>
                            </td>
                            @php
                                $totalFila = 0;
                            @endphp
                            @foreach (array_keys($tableData) as $mes)
                                @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                    @php
                                        $valor = !empty($tableData[$mes][19])
                                            ? parse_number($tableData[$mes][19])
                                            : 0;
                                        $totalFila += $valor;
                                    @endphp
                                    <td class="text-end">
                                        ${{ !empty($tableData[$mes][19]) ? $tableData[$mes][19] : '-' }}
                                    </td>
                                @endif
                            @endforeach
                            <td class="text-end"><b>{{ $totalFila != 0 ? number_format($totalFila, 0) : '-' }}</b></td>
                        </tr>
                        <!-- Agregar una fila adicional para mostrar los totales de Utilidad antes de Impuestos -->
                        <tr>
                            <td>
                                <h6><b>Utilidad antes de Impuestos</b></h6>
                            </td>
                            @php
                                $totalFila = 0;
                            @endphp
                            @foreach (array_keys($tableData) as $mes)
                                @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                    @php
                                        $valor = !empty($totales[$mes]['Utilidad antes de imptos'])
                                            ? parse_number($totales[$mes]['Utilidad antes de imptos'])
                                            : 0;
                                        $totalFila += $valor;
                                    @endphp
                                    <td class="text-end">
                                        <b>${{ !empty($totales[$mes]['Utilidad antes de imptos']) ? $totales[$mes]['Utilidad antes de imptos'] : '-' }}</b>
                                    </td>
                                @endif
                            @endforeach
                            <td class="text-end"><b>{{ $totalFila != 0 ? number_format($totalFila, 0) : '-' }}</b></td>
                        </tr>
                        <!-- agregar fila impuestos renta-->
                        <tr>
                            @foreach ($tableData['descripcionct'] as $index => $descripcionct)
                                @if (isset($tableData['Cuenta'][$index]) &&
                                        substr($tableData['Cuenta'][$index], 0, 2) === '54' &&
                                        !in_array($descripcionct, ['COSTO  MERCANCIA VENDIDA']) &&
                                        !in_array($descripcionct, ['Otros Egresos']))
                        <tr>
                            <td>
                                <h6><b>Provision impuesto de renta</b></h6>
                            </td>
                            @php
                                $totalFila = 0;
                            @endphp
                            @foreach (array_keys($tableData) as $mes)
                                @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                    @php
                                        $valor = !empty($tableData[$mes][$index])
                                            ? parse_number($tableData[$mes][$index])
                                            : 0;
                                        $totalFila += $valor;
                                    @endphp
                                    <td class="text-end"><b>
                                        {{ !empty($tableData[$mes][$index]) ? $tableData[$mes][$index] : '-' }}</b>
                                    </td>
                                @endif
                            @endforeach
                            <td class="text-end"><b>{{ $totalFila != 0 ? number_format($totalFila, 0) : '-' }}</b></td>
                        </tr>
                        @endif
                        @endforeach
                        </tr>
                        <!-- Agregar una fila adicional para mostrar los totales de Utilidad neta -->
                        <tr>
                            <td>
                                <h6><b>Utilidad/perdida neta</b></h6>
                            </td>
                            @php
                                $totalFila = 0;
                            @endphp
                            @foreach (array_keys($tableData) as $mes)
                                @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                    @php
                                        $valor = !empty($totales[$mes]['Utilidad neta'])
                                            ? parse_number($totales[$mes]['Utilidad neta'])
                                            : 0;
                                        $totalFila += $valor;
                                    @endphp
                                    <td class="text-end">
                                        <b>${{ !empty($totales[$mes]['Utilidad neta']) ? $totales[$mes]['Utilidad neta'] : '-' }}</b>
                                    </td>
                                @endif
                            @endforeach
                            <td class="text-end"><b>{{ $totalFila != 0 ? number_format($totalFila, 0) : '-' }}</b></td>
                        </tr>

                    </tbody>
                </table>
            </div>
        @endif
    </div>
</body>

</html>
