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
            <th class="align-middle border-0">
                <h4 class="title mb-0">
                    {{ $compania }}
                </h4>
                <h5 class="mb-0">
                    Periodo: {{ $fecha }}
                </h5>
                <h6 class="mb-0">Estado de Resultados Acumulado</h6>
            </th>
        </tr>
        <tr>
            <th >DESCRIPCIÓN</th>
            @php $prevMes = null; @endphp
            @foreach (array_keys($tableData) as $mes)
                @if ($mes !== 'descripcionct'  && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                    <th >{{ $mes }}</th>
                @endif
            @endforeach
            <th >TOTAL</th>
            <th>% VENTAS</th>
        </tr>
    </thead>
     @php
        $totalVentasNetasGeneral = 0;

        foreach (array_keys($tableData) as $mes) {
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
                                <td>
                                    {{ (float)$valor }}
                                </td>
                            @endif
                        @endforeach
                            <td class="text-end"><b>{{ $totalFila != 0 ? $totalFila : '-' }}</b></td>
                            <td></td>
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
                        <td>
                            <b>{{ (float)$valor }}</b>
                        </td>

                    @endif
                @endforeach
                <td class="text-end"><b>{{ $totalFila != 0 ? $totalFila : '-' }}</b></td>
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
                     <td>
                        <b>{{ (float)$valor }}</b>
                    </td>

                    @endif
                @endforeach
                <td class="text-end"><b>{{ $totalFila != 0 ? $totalFila : '-' }}</b></td>
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
                         <td>
                            <b>{{ (float)$valor }}</b>
                        </td>
                    @endif
                @endforeach
                <td class="text-end"><b>{{ $totalFila != 0 ? $totalFila : '-' }}</b></td>
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
                     <td>
                        <b>{{ (float)$valor }}</b>
                    </td>
                @endif
            @endforeach
                <td class="text-end"><b>{{ $totalFila != 0 ? $totalFila : '-' }}</b></td>
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
                            $fila .= "<td class=\"text-end\">" . (float)$valor . "</td>";
                        }
                    }
                    // Columna TOTAL
                    $fila .= "<td class=\"text-end\"><b>" 
                            . ($totalFila != 0 ? $totalFila: '-') 
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
                         <td>
                            <b>{{ (float)$valor }}</b>
                        </td>

                    @endif
                @endforeach
                <td class="text-end"><b>{{ $totalFila != 0 ? $totalFila : '-' }}</b></td>
                 @php
                    $porcentajeTotal = $totalVentasNetasGeneral != 0
                        ? ($totalFila / $totalVentasNetasGeneral) * 100
                        : 0;
                @endphp

                <td class="text-end">
                    <b>{{ $totalVentasNetasGeneral != 0 ? number_format($porcentajeTotal, 1) . '%' : '-' }}</b>
                </td>
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
                     <td>
                        {{ (float)$valor }}
                    </td>
                @endif
            @endforeach
            <td class="text-end"><b>{{ $totalFila != 0 ? $totalFila : '-' }}</b></td>
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
                    <td>
                        {{ (float)$valor }}
                    </td>
                @endif
            @endforeach
            <td class="text-end">{{ $totalFila != 0 ? $totalFila : '-' }}</td>
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
                         <td>
                            {{ (float)$valor }}
                        </td>
                    @endif
                @endforeach
                <td class="text-end"><b>{{ $totalFila != 0 ? $totalFila : '-' }}</b></td>
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
                         <td>
                            {{ (float)$valor }}
                        </td>  
                    @endif
                @endforeach
                <td class="text-end">{{ $totalFila != 0 ? $totalFila : '-' }}</td>
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
                             <td>
                            <b>{{ (float)$valor }}</b>
                        </td>
                        @endif
                    @endforeach
                    <td class="text-end"><b>{{ $totalFila != 0 ? $totalFila : '-' }}</b></td>
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
                            <td>
                                <b>{{ (float)$valor }}</b>
                            </td>
                        @endif
                    @endforeach
                    <td class="text-end"><b>{{ $totalFila != 0 ? $totalFila : '-' }}</b></td>
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
                             <td>
                                <b>{{ (float)$valor }}</b>
                            </td>
                        @endif
                    @endforeach
                    <td class="text-end"><b>{{ $totalFila != 0 ? $totalFila : '-' }}</b></td>
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