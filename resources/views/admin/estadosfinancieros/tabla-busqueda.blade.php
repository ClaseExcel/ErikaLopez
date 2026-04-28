@if (count($movimientos)>=1)
<div class="form-group">
    <button id="toggleButton" class="btn btn-light border btn-radius px-4"><i id="eyeIcon" class="fas fa-eye"></i>Ver/Ocultar modificaciones</button>
    <div class="row table-responsive" id="tabla">  
        <table class="table-sm  table table-striped table-bordered table-striped  datatable-requerimiento  w-100">
            <thead style="background-color: #919396; color:white">
                <tr>
                    <th>Periodo</th>
                    <th>Cuenta</th>
                    <th>Valor ajustado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($movimientos as $movimiento)
                    <tr>
                        <td>{{ $movimiento->periodo }}</td>
                        <td>{{ $movimiento->movimiento }}</td>
                        <td>{{ $movimiento->valor_ajustado }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
<div class="row table-responsive">
    <table class="table-sm  table table-striped table-bordered table-striped  datatable-requerimiento  w-100">
        <thead style="background-color: #919396; color:white">
            <tr>
                <th><b>NIT</b></th>
                <th><b>Cuenta descripción</b></th>
                <th><b>Cuenta</b></th>
                <th><b>Saldo Mov.</b></th>
                <th><b>Descripción</b></th>
                <th><b>Saldo inicial</b></th>
                <th><b>Comprobante</b></th>
                <th><b>Fecha</b></th>
                <th><b>Nit</b></th>
                <th><b>Nombre</b></th>
                <th><b>Descripción</b></th>
                <th><b>CC SCC</b></th>
                <th><b>Débito</b></th>
                <th><b>Crédito</b></th>
                <!-- Añade más columnas según tu estructura de datos -->
            </tr>
        </thead>
        <tbody>
                @foreach ($resultados as $resultado)
                    <tr>
                        <td>{{ $resultado->Nit }}</td>
                        <td>{{ $resultado->cuenta_contable_sw }}</td>
                        <td>{{ $resultado->codigo_contable_sw }}</td>
                        <td>${{ number_format($resultado->saldo_movimiento_sw, 2, ',', '.') }}</td>
                        <td>{{ $resultado->descripcion_sw }}</td>
                        <td>${{ number_format($resultado->saldo_inicial_sw, 2, ',', '.') }}</td>
                        <td>{{ $resultado->comprobante_sw }}</td>
                        <td>{{ $resultado->fecha_elaboracion_sw }}</td>
                        <td>{{ $resultado->identificacion_sw }}</td>
                        <td>{{ $resultado->nombre_tercero_sw }}</td>
                        <td>{{ $resultado->detalle_sw }}</td>
                        <td>{{ $resultado->centro_costo_sw }}</td>
                        <td>{{ $resultado->debito_sw }}</td>
                        <td>{{ $resultado->credito_sw }}</td>
                    </tr>
                @endforeach
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function() {
        // Oculta la tabla al cargar la página
        $('#tabla').hide();

        // Maneja el clic en el botón para mostrar/ocultar la tabla
        $('#toggleButton').on('click', function() {
            $('#tabla').toggle();
             // Cambia el ícono del ojo al alternar la visibilidad
             $('#eyeIcon').toggleClass('fa-eye-slash').toggleClass('fa-eye');
        });
    });
    $(function() {

        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);

        $.extend(true, $.fn.dataTable.defaults, {
            responsive: true,
            orderCellsTop: true,
            order: [
                [1, 'asc']
            ],
            pageLength: 10,
        });

        let table = $('.datatable-requerimiento:not(.ajaxTable)').DataTable({
            buttons: dtButtons
        })
    })
</script>
