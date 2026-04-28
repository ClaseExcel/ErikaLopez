<table>
    <tr>
        <td class="align-middle border-0">
            <h4 class="title mb-0">
                {{ $compania }}
            </h4>
            <h5 class="mb-0">
                Periodo: {{ $mes.'-'.$anio .'-' . $anioAnterior }}
            </h5>
        </td>
    </tr>
</table>
@if($tipo==1)
    <table>
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
                        <td @if ($key == 'Utilidad Bruta') style="background-color: #919396; color: white; font-weight: bold;"@endif>{{ $informeData[$key]['descripcionct'] }}</td>
                        <td @if ($key == 'Utilidad Bruta') style="background-color: #dcd7d7; font-weight: bold;" @else style="text-align: right" @endif>{{ abs($anioActual) }}</td>
                        <td @if ($key == 'Utilidad Bruta') style="background-color: #dcd7d7; font-weight: bold;" @else style="text-align: right" @endif>{{ abs($anioPrevio) }}</td>
                        <td @if ($key == 'Utilidad Bruta') style="background-color: #dcd7d7; font-weight: bold;" @else style="text-align: right" @endif>{{ number_format(abs($informeData[$key]['var%']* 100), 0, ',', '.')}}%</td>
                        <td @if ($key == 'Utilidad Bruta') style="background-color: #dcd7d7; font-weight: bold;" @else style="text-align: right" @endif>{{ abs($informeData[$key]['variacion$']) }}</td>
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
                    <td style="background-color: #919396; color: white; font-weight: bold;">{{ $informeData[$key]['descripcionct'] }}</td>
                    <td style="text-align: right">{{ abs($informeData[$key][$anio]) }}</td>
                    <td style="text-align: right">{{ abs($informeData[$key][$anioAnterior]) }}</td>
                    <td style="text-align: right">{{ number_format(abs($informeData[$key]['var%']* 100), 0, ',', '.')}}%</td>
                    <td style="text-align: right">{{ abs($informeData[$key]['variacion$']) }}</td>
                </tr>
                @endif
            @endforeach
            {{-- Fila vacía --}}
            <tr><td colspan="5">&nbsp;</td></tr>
            {{-- Mostrar Utilidad (Pérdida) operativa --}}
            <tr>
                <td class="titulos" style="background-color: #919396; color: white; font-weight: bold;">Utilidad (Pérdida) operativa</td>
                <td class="totales" style="background-color: #dcd7d7; font-weight: bold;">{{ $informeData['Utilidad (Pérdida) operativa'][$anio] }}</td>
                <td class="totales" style="background-color: #dcd7d7; font-weight: bold;">{{ $informeData['Utilidad (Pérdida) operativa'][$anioAnterior] }}</td>
                <td class="totales" style="background-color: #dcd7d7; font-weight: bold;">{{ number_format($informeData['Utilidad (Pérdida) operativa']['var%']* 100, 0, ',', '.') }}%</td>
                <td class="totales" style="background-color: #dcd7d7; font-weight: bold;">{{ $informeData['Utilidad (Pérdida) operativa']['variacion$'] }}</td>
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
                        <td style="text-align: right">{{ abs($informeData[$key][$anio]) }}</td>
                        <td style="text-align: right">{{ abs($informeData[$key][$anioAnterior]) }}</td>
                        <td style="text-align: right">{{ number_format(abs($informeData[$key]['var%']* 100), 0, ',', '.')}}%</td>
                        <td style="text-align: right">{{ abs($informeData[$key]['variacion$']) }}</td>
                    </tr>
                @endif
            @endforeach
            
            {{-- Mostrar (Pérdida) Utilidad antes de impuestos de renta --}}
            <tr>
                <td class="titulos" style="background-color: #919396; color: white; font-weight: bold;">Utilidad (Pérdida) antes de impuestos de renta</td>
                <td class="totales" style="background-color: #dcd7d7; font-weight: bold;">{{ $informeData['Utilidad (Pérdida) antes de impuestos de renta'][$anio] }}</td>
                <td class="totales" style="background-color: #dcd7d7; font-weight: bold;">{{ $informeData['Utilidad (Pérdida) antes de impuestos de renta'][$anioAnterior] }}</td>
                <td class="totales" style="background-color: #dcd7d7; font-weight: bold;">{{ number_format($informeData['Utilidad (Pérdida) antes de impuestos de renta']['var%']* 100, 0, ',', '.')}}%</td>
                <td class="totales" style="background-color: #dcd7d7; font-weight: bold;">{{ $informeData['Utilidad (Pérdida) antes de impuestos de renta']['variacion$'] }}</td>
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
                    <td style="text-align: right">{{ $informeData[$key][$anio] }}</td>
                    <td style="text-align: right">{{ $informeData[$key][$anioAnterior] }}</td>
                    <td style="text-align: right">{{ number_format($informeData[$key]['var%']* 100, 0, ',', '.')}}%</td>
                    <td style="text-align: right">{{ $informeData[$key]['variacion$'] }}</td>
                </tr>
            @endif
            @endforeach
            <tr><td colspan="5">&nbsp;</td></tr>
            {{-- Mostrar (Perdida) Utilidad Neta del periodo --}}
            <tr>
                <td class="titulos" style="background-color: #919396; color: white; font-weight: bold;">Utilidad (Perdida) Neta del periodo</td>
                <td class="totales" style="background-color: #dcd7d7; font-weight: bold;">{{ $informeData['Utilidad (Perdida) Neta del periodo'][$anio] }}</td>
                <td class="totales" style="background-color: #dcd7d7; font-weight: bold;">{{ $informeData['Utilidad (Perdida) Neta del periodo'][$anioAnterior] }}</td>
                <td class="totales" style="background-color: #dcd7d7; font-weight: bold;">{{ number_format($informeData['Utilidad (Perdida) Neta del periodo']['var%']* 100, 0, ',', '.')}}%</td>
                <td class="totales" style="background-color: #dcd7d7; font-weight: bold;">{{ $informeData['Utilidad (Perdida) Neta del periodo']['variacion$'] }}</td>
            </tr>
            <tr><td colspan="5">&nbsp;</td></tr>
            <tr>
                <td class="titulos" style="background-color: #919396; color: white; font-weight: bold;">Resultado del ejercicio</td>
                <td class="totales" style="background-color: #dcd7d7; font-weight: bold;">{{ $informeData['Utilidad (Perdida) Neta del periodo'][$anio] }}</td>
                <td class="totales" style="background-color: #dcd7d7; font-weight: bold;">{{ $informeData['Utilidad (Perdida) Neta del periodo'][$anioAnterior] }}</td>
                <td class="totales" style="background-color: #dcd7d7; font-weight: bold;">{{ number_format($informeData['Utilidad (Perdida) Neta del periodo']['var%']* 100, 0, ',', '.')}}%</td>
                <td class="totales" style="background-color: #dcd7d7; font-weight: bold;">{{ $informeData['Utilidad (Perdida) Neta del periodo']['variacion$'] }}</td>
            </tr>
        </tbody>
    </table>
@elseif($tipo==2)
    <table class="table table-sm table-bordered  datatable-informe w-100">
        <thead>
            @if($mes)
            <tr>
                <th>DESCRIPCIÓN</th>
                <th class="text-titulos">{{ $mes.'-'.$anio }}</th>
                <th class="text-titulos">{{ $mes.'-'. $anioAnterior }}</th>
                <th class="text-titulos">VAR%</th>
                <th class="text-titulos">VARIACIÓN $</th>
            </tr>
            @else
            <tr>
                <th>DESCRIPCIÓN</th>
                <th class="text-titulos">AÑO {{ $anio }}</th>
                <th class="text-titulos">AÑO {{ $anioAnterior }}</th>
                <th class="text-titulos">VAR%</th>
                <th class="text-titulos">VARIACIÓN $</th>
            </tr>
            @endif
        </thead>
        <tbody>
            @php
                function invertirSigno($valor)
                {
                    $valor = trim($valor);

                    if (Str::startsWith($valor, '-')) {
                        return ltrim($valor, '-');
                    }

                    return '-' . $valor;
                }
                $secciones = [
                    'Activo Corriente' => ['Efectivo y equivalentes al efectivo','Inversiones','Cuentas comerciales y otras cuentas por cobrar','Activos por impuestos corrientes','Inventarios','Anticipos y avances','Otros activos','Total activo corriente'],
                    'Activo No Corriente' => ['Inversiones no corriente','Propiedades planta y equipos','Activos Intangibles','Impuesto diferido','Total activo no corriente','Total activo'],
                    'Pasivo Corriente' => ['Obligaciones financieras','Cuentas comerciales y otras cuentas por pagar','Cuentas por pagar','Pasivos por Impuestos Corrientes','Beneficios a empleados', 'Anticipos y avances recibidos', 'Otros Pasivos','Total pasivos corrientes'],
                    'Pasivo No Corriente' => ['Obligaciones Financieras','Cuentas por pagar comerciales y otras cuentas por pagar','Pasivos Contingentes','Pasivo por impuesto diferido','Total pasivos no corrientes','Total Pasivo'],
                    'Patrimonio' => ['Capital social','Superavit de capital','Reservas','Utilidad y/o perdidas del ejercicio','Resultado del ejercicio','Utilidad y/o perdidas acumuladas','Ganancias acumuladas - Adopcion por primera vez','Dividendos o participacion','Superavit de Capital Valorizacion','Total patrimonio','Total Pasivo & Patrimonio'],
                ];
            @endphp

            @foreach ($secciones as $titulo => $cuentas)
                <tr><td colspan="5" style="background-color: #919396; color: white; font-weight: bold;">{{ $titulo }}</td></tr>

                @foreach ($cuentas as $cuenta)
                    @foreach ($informeData as $item)
                        @if ($item['descripcion'] === $cuenta)
                            @php
                                $valor1 = floatval($item['totalaño1']);
                                $valor2 = floatval($item['totalaño2']);
                                $isUtilidadAcumulada = str_starts_with($item['descripcion'], 'Utilidad y/o perdidas acumuladas');
                                $isUtilidadEjercicio = str_starts_with($item['descripcion'], 'Utilidad y/o perdidas del ejercicio');
                                $isResultadoEjercicio = str_starts_with($item['descripcion'], 'Resultado del ejercicio');
                                // Omitir si ambos son cero
                                if ($valor1 == 0 && $valor2 == 0) continue;
                                if($titulo == 'Activo Corriente' || $titulo == 'Activo No Corriente') {
                                    $isTotal = str_starts_with($item['descripcion'], 'Total');
                                    $totalAño1  = $item['totalaño1'];
                                    $totalAño2  = $item['totalaño2'];
                                    $var        = $item['VAR'];
                                    $variacion = $item['VARIACION'];
                                }else{
                                    $isTotal = str_starts_with($item['descripcion'], 'Total');
                                    $totalAño1  = $item['totalaño1'];
                                    $totalAño2  = $item['totalaño2'];
                                    $var        = $item['VAR'];
                                    $variacion  = $item['VARIACION'];
                                    $totalAño1 = invertirSigno($totalAño1);
                                    $totalAño2 = invertirSigno($totalAño2);
                                    $var       = invertirSigno($var);
                                    $variacion = invertirSigno($variacion);
                                    if($isTotal){
                                        $totalAño1 = invertirSigno($totalAño1);
                                        $totalAño2 = invertirSigno($totalAño2);
                                        $var       = invertirSigno($var);
                                        $variacion = invertirSigno($variacion);
                                    }
                                }
                                
                                if ($isUtilidadEjercicio || $isResultadoEjercicio || $isUtilidadAcumulada) {
                                    $totalAño1 = $item['totalaño1'];
                                    $totalAño2 = $item['totalaño2'];
                                    $var       = $item['VAR'];
                                    $variacion = $item['VARIACION'];

                                    if ($isUtilidadAcumulada || $isResultadoEjercicio) {
                                        $totalAño1 = invertirSigno($totalAño1);
                                        $totalAño2 = invertirSigno($totalAño2);
                                        $var       = invertirSigno($var);
                                        $variacion = invertirSigno($variacion);
                                    }
                                }


                                // Reemplazar nombre
                                $descripcion = $item['descripcion'] === 'Total Pasivo & Patrimonio' ? 'Total Pasivo + Patrimonio' : $item['descripcion'];
                            @endphp

                            <tr>
                                <td @if ($isTotal) style="background-color: #919396; color: white; font-weight: bold;" @endif>{{ $descripcion }}</td>
                                <td @if ($isTotal) style="background-color: #919396; color: white; font-weight: bold;" @endif class="text-number">{{ $totalAño1 }}</td>
                                <td @if ($isTotal) style="background-color: #919396; color: white; font-weight: bold;" @endif class="text-number">{{ $totalAño2 }}</td>
                                <td @if ($isTotal) style="background-color: #919396; color: white; font-weight: bold;" @endif class="text-number">{{ $var }}</td>
                                <td @if ($isTotal) style="background-color: #919396; color: white; font-weight: bold;" @endif class="text-number">{{ $variacion }}</td>
                            </tr>
                        @endif
                    @endforeach
                @endforeach

                {{-- Fila vacía --}}
                <tr><td colspan="5">&nbsp;</td></tr>
            @endforeach
        </tbody>

        
        
        
    </table>
@else
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
                    @if(isset($informeData[0]["mes$i"]))
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
                <tr><td colspan="{{ count($informeData[0]) }}" class="titulos" style="background-color: #919396; color: white; font-weight: bold;">{{ $titulo }}</td></tr>

                @foreach ($cuentas as $cuenta)
                    @foreach ($informeData as $item)
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

                            <tr>
                                <td @if ($isTotal) style="background-color: #919396; color: white; font-weight: bold;" @endif>{{ $item['descripcion'] }}</td>
                                @foreach(range(1, 12) as $i)
                                    @if(isset($item["mes$i"]))
                                        <td class="texto"  style="text-align: right; {{ $isTotal ? 'background-color: #919396; color: white; font-weight: bold;' : '' }}">
                                            @php
                                                $valor = (float) str_replace(',', '', $signAdjustment($item["mes$i"]));
                                            @endphp
                                            {{ number_format($valor, 0, '', '.') }}
                                        </td>
                                    @endif
                                @endforeach
                            </tr>
                        @endif
                    @endforeach
                @endforeach

                {{-- Fila vacía --}}
                <tr><td colspan="{{ count($informeData[0]) }}">&nbsp;</td></tr>
            @endforeach
        </tbody>
    </table>
@endif