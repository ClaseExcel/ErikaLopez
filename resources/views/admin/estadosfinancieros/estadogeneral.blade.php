@extends('layouts.admin')
@section('title', 'Estado Resultados')
@section('content')
    <div class="form-group">
        <a class="btn btn-back border btn-radius px-4" href="{{ URL::previous() }}">
            <i class="fas fa-arrow-circle-left"></i> Atrás
        </a>
    </div>

    <div class="card">
        <div class="card-header border-0 pb-0 pb-0 mt-1 py-2 d-flex justify-content-between align-items-center">
            <h5><i class="fa-solid fa-table-columns"></i> Informe Estado de resultados acumulado</h5>
            <div class="d-flex ml-auto">
                
                @include('admin.estadosfinancieros.ia-modal')   <!-- Incluir modal de IA -->

                <div id="download-pdf" >
                    <form id="tableForm"  action="{{ route('admin.estadosfinancieros.pdfgeneral') }}" method="POST" target="_blank">
                        @csrf
                        <input type="hidden" name="tipopdf" id="tipopdf" value='2'>
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
                    <h4 class="subtitle">
                        {{ $compania }}

                        <span class="float-right">
                            <!-- Botón para descargar Excel -->
                            <button type="button" class="btn btn-back border btn-radius px-4" onclick="setContext('descargar');" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                <i class="fa-solid fa-download"></i> Descargar detalles en Excel
                            </button>
                        </span>
                    </h4>
                    @include('admin.estadosfinancieros.modal', [
                        'nit' => $nit,
                        'fecha_inicio' => $fecha_inicio,
                    ])
                    @if ($centrocostov)
                        <h5>
                            Centro de costo: {{ $centrocostov->codigo . '-' . $centrocostov->nombre }}
                        </h5>
                    @endif
                    <div>
                        <h5>
                            Periodo: {{ $fecha }}
                        </h5>
                    </div>
                </div>
            </div>
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
                    <thead style="background: #919396; color:white">
                        <tr>
                            <th class="text-center">DESCRIPCIÓN</th>
                            @php $prevMes = null; @endphp
                            @foreach (array_keys($informePorMes) as $mes)
                                @if ($mes !== 'descripcionct'  && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                    <th class="text-center">{{ $mes }}</th>
                                @endif
                            @endforeach
                            
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $descripcionesMostradas = [];
                            $totalMes = false;
                        @endphp

                        @foreach ($informePorMes['descripcionct'] as $index => $descripcionct)
                            @if ($descripcionct !== 'Total Mes' && $descripcionct !== 'Cuenta' && !in_array($descripcionct, $descripcionesMostradas))
                                @if (in_array($descripcionct, ['VENTAS', 'Devoluciones en ventas', 'Total Ventas Netas']))
                                    <tr>
                                        @php
                                            $totalFila = 0;
                                        @endphp
                                        <td >
                                            <h6>{{ $descripcionct }}</h6>
                                        </td>
                                        @foreach (array_keys($informePorMes) as $mes)
                                            @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                                <td class="text-end">
                                                    {{ !empty($informePorMes[$mes][$index]) ? ltrim($informePorMes[$mes][$index], '-')  : '-' }}
                                                </td>
                                            @endif
                                        @endforeach
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
                            @foreach (array_keys($informePorMes) as $mes)
                                @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                   
                                    <td class="text-end">
                                        <b>${{ !empty($totales[$mes]['Total ventas netas']) ? $totales[$mes]['Total ventas netas'] : '-' }}</b>
                                    </td>
                                @endif
                            @endforeach
                            
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                        @php
                            // Contar el número de columnas de datos
                            $numeroDeColumnas = count(array_keys($informePorMes))-2; // Restamos 'descripcionct', 'Total Mes', y 'Cuenta'
                        @endphp
                        <tr>
                            <td><b>COSTO MERCANCIA VENDIDA</b></td>
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
                            @foreach (array_keys($informePorMes) as $mes)
                                @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                    
                                    <td class="text-end">
                                        {{ !empty($informePorMes[$mes][11]) ? $informePorMes[$mes][11] : '-' }}</td>
                                @endif
                            @endforeach
                            
                        </tr>
                        <!-- Agregar una fila adicional para mostrar los totales de Utilidad Bruta Ventas -->
                        <tr>
                            <td>
                                <h6><b>Utilidad Bruta Ventas</b></h6>
                            </td>
                            @php
                                $totalFila = 0;
                            @endphp
                            @foreach (array_keys($informePorMes) as $mes)
                                @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                    
                                    <td class="text-end">
                                        <b>${{ !empty($totales[$mes]['Utilidad bruta ventas']) ? $totales[$mes]['Utilidad bruta ventas'] : '-' }}</b>
                                    </td>
                                @endif
                            @endforeach
                            
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                        <tr>
                            <td><b>GASTOS DE VENTA</b></td>
                            @for ($i = 0; $i < $numeroDeColumnas; $i++)
                                <td></td>
                            @endfor
                        </tr>

                        @foreach ($informePorMes['descripcionct'] as $index => $descripcionct)
                            @if (isset($informePorMes['Cuenta'][$index]) &&
                                    substr($informePorMes['Cuenta'][$index], 0, 1) === '5' &&
                                    substr($informePorMes['Cuenta'][$index], 0, 2) !== '54' &&
                                    !in_array($descripcionct, ['COSTO  MERCANCIA VENDIDA']) &&
                                    !in_array($descripcionct, ['Otros Egresos']))
                                <tr>
                                    @php
                                        $totalFila = 0;
                                    @endphp
                                    <td>
                                        <h6>{{ $descripcionct }}</h6>
                                    </td>
                                    @foreach (array_keys($informePorMes) as $mes)
                                        @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                            
                                            <td class="text-end">
                                                {{ !empty($informePorMes[$mes][$index]) ? $informePorMes[$mes][$index] : '-' }}
                                            </td>
                                        @endif
                                    @endforeach
                                    
                                    </td>
                                </tr>
                            @endif
                        @endforeach



                        <!-- Agregar una fila adicional para mostrar los totales del grupo Gastos de Venta -->
                        <tr>
                            <td>
                                <h6><b>Total Gastos de Venta</b></h6>
                            </td>
                            @php
                                $totalFila = 0;
                            @endphp
                            @foreach (array_keys($informePorMes) as $mes)
                                @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                    
                                    <td class="text-end">
                                        <b>${{ !empty($totales[$mes]['Total gastos admón y ventas']) ? $totales[$mes]['Total gastos admón y ventas'] : '-' }}</b>
                                    </td>
                                @endif
                            @endforeach
                            
                        </tr>

                        <!-- Agregar una fila adicional para mostrar los totales de Utilidad Operacional -->
                        <tr>
                            <td>
                                <h6><b>Utilidad Operacional</b></h6>
                            </td>
                            @php
                                $totalFila = 0;
                            @endphp
                            @foreach (array_keys($informePorMes) as $mes)
                                @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                    
                                    <td class="text-end">
                                        <b>${{ !empty($totales[$mes]['Utilidad operacional']) ? $totales[$mes]['Utilidad operacional'] : '-' }}</b>
                                    </td>
                                @endif
                            @endforeach
                            
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
                                <h6><b><a href="#" id="otros-ingresos-link">Otros Ingresos</a></b></h6>
                            </td>
                            @include('admin.estadosfinancieros.otros-ingresos', [
                                'nit' => $nit,
                                'fecha' => $fecha_inicio,
                            ])
                            @php
                                $totalFila = 0;
                            @endphp
                            @foreach (array_keys($informePorMes) as $mes)
                                @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                    
                                    <td class="text-end">
                                        <b>${{ !empty($informePorMes[$mes][2]) ? ltrim($informePorMes[$mes][2], '-')  : '-' }}</b>
                                    </td>
                                @endif
                            @endforeach
                            
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
                                    
                                    <td class="text-end">
                                        <b>${{ !empty($informePorMes[$mes][14]) ? ltrim($informePorMes[$mes][14], '-') : '-' }}</b>
                                    </td>
                                @endif
                            @endforeach
                            
                        </tr>
                        <!-- Agregar una fila adicional para mostrar los totales de Utilidad antes de Impuestos -->
                        <tr>
                            <td>
                                <h6><b>Utilidad antes de Impuestos</b></h6>
                            </td>
                            @php
                                $totalFila = 0;
                            @endphp
                            @foreach (array_keys($informePorMes) as $mes)
                                @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                    <td class="text-end">
                                        <b>${{ !empty($totales[$mes]['Utilidad antes de imptos']) ? $totales[$mes]['Utilidad antes de imptos'] : '-' }}</b>
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                        <!-- agregar fila impuestos renta-->
                        <tr>
                            @foreach ($informePorMes['descripcionct'] as $index => $descripcionct)
                                @if (isset($informePorMes['Cuenta'][$index]) &&
                                        substr($informePorMes['Cuenta'][$index], 0, 2) === '54' &&
                                        !in_array($descripcionct, ['COSTO  MERCANCIA VENDIDA']) &&
                                        !in_array($descripcionct, ['Otros Egresos']))
                        <tr>
                            <td>
                                <h6>{{ $descripcionct }}</h6>
                            </td>
                            @php
                                $totalFila = 0;
                            @endphp
                            @foreach (array_keys($informePorMes) as $mes)
                                @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                    <td class="text-end">
                                        {{ !empty($informePorMes[$mes][$index]) ? $informePorMes[$mes][$index] : '-' }}
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                        @endif
                        @endforeach
                        </tr>
                        <!-- Agregar una fila adicional para mostrar los totales de Utilidad neta -->
                        <tr>
                            <td>
                                <h6><b>Utilidad neta</b></h6>
                            </td>
                            @php
                                $totalFila = 0;
                            @endphp
                            @foreach (array_keys($informePorMes) as $mes)
                                @if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta')
                                    <td class="text-end">
                                        <b>${{ !empty($totales[$mes]['Utilidad neta']) ? $totales[$mes]['Utilidad neta'] : '-' }}</b>
                                    </td>
                                @endif
                            @endforeach
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>

@endsection
@section('scripts')
    @parent
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
                url: '{{ route('admin.otros-ingresos', ['nit' => $nit, 'fecha' => $fecha_inicio]) }}',
                method: 'GET',
                success: function(response) {
                    // Crear la estructura HTML de la tabla
                    var html =
                        '<table class="table-sm  table table-striped table-bordered table-striped  datatable-informe w-100">';
                    html +=
                        '<thead class="thead-dark"><tr><th>Cuenta</th><th>Nombre Específico</th><th>Saldo Total</th></tr></thead>';
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
        $('#otrosIngresosModal').on('shown.bs.modal', function(e) {
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
                url: '{{ route('admin.otros-egresos', ['nit' => $nit, 'fecha' => $fecha_inicio]) }}',
                method: 'GET',
                success: function(response) {
                    // Crear la estructura HTML de la tabla
                    var html =
                        '<table class="table-sm  table table-striped table-bordered table-striped  datatable-informe w-100">';
                    html +=
                        '<thead class="thead-dark"><tr><th>Cuenta</th><th>Nombre Específico</th><th>Saldo Total</th></tr></thead>';
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
