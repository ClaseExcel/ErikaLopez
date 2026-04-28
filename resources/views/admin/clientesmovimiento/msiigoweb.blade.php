@extends('layouts.admin')
@section('title',"Siigo Web")
@section('content')
    @can('CREAR_CLIENTES')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-light border btn-radius" href="{{ route('admin.movimientos.create') }}">
                    <i class="fas fa-circle-plus"></i> Agregar movimientos
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            <i class="fa-solid fa-server"></i> Lista de movimientos clientes siigo web
        </div>

        <div class="card-body">
            <div class="">
                <table class="display nowrap datatable-User w-100">
                    <thead>
                        <tr>
                            <th>Empresa</th>
                            <th>Código contable</th>
                            <th>Cuenta contable</th>
                            <th>Comprobante</th>
                            <th>Secuencia</th>
                            <th>Fecha elaboración</th>
                            <th>Identificación</th>
                            <th>Suc</th>
                            <th>Nombre del tercero</th>
                            <th>Descripción</th>
                            <th>Detalle</th>
                            <th>Centro de costo</th>
                            <th>Saldo inicial</th>
                            <th>Débito</th>
                            <th>Crédito</th>
                            <th>Saldo Movimiento</th>
                            <th>Saldo total cuenta</th>
                            <th>Fecha Reporte</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function() {

            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);

            $.extend(true, $.fn.dataTable.defaults, {    
                responsive: true,            
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],  
                exportOptions: {
                columns: [0, 1, 2], // Especificar las columnas que se deben exportar
                },                                
                pageLength: 10,
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.movimientos.msiigoweb') }}", //ruta donde se encuentra el metodo index
                dataType: 'json',
                type: "POST",
                columns: [
                    {
                        data: 'razon_social',
                        name: 'razon_social',
                    },
                    {
                        data: 'codigo_contable_sw',
                        name: 'codigo_contable_sw',
                    },
                    {
                        data: 'cuenta_contable_sw',
                        name: 'cuenta_contable_sw',
                    },
                    {
                        data: 'comprobante_sw',
                        name: 'comprobante_sw',
                    },
                    {
                        data: 'secuencia_sw',
                        name: 'secuencia_sw',
                    },
                    {
                        data: 'fecha_elaboracion_sw',
                        name: 'fecha_elaboracion_sw',
                    },
                    {
                        data: 'identificacion_sw',
                        name: 'identificacion_sw',
                    },
                    {
                        data: 'suc_sw',
                        name: 'suc_sw',
                    },
                    {
                        data: 'nombre_tercero_sw',
                        name: 'nombre_tercero_sw',
                    },
                    {
                        data: 'descripcion_sw',
                        name: 'descripcion_sw',
                    },
                    {
                        data: 'detalle_sw',
                        name: 'detalle_sw',
                    },
                    {
                        data: 'centro_costo_sw',
                        name: 'centro_costo_sw',
                    },
                    {
                        data: 'saldo_inicial_sw',
                        name: 'saldo_inicial_sw',
                    },
                    {
                        data: 'debito_sw',
                        name: 'debito_sw',
                    },
                    {
                        data: 'credito_sw',
                        name: 'credito_sw',
                    },
                    {
                        data: 'saldo_movimiento_sw',
                        name: 'saldo_movimiento_sw',
                    },
                    {
                        data: 'salto_total_cuenta_sw',
                        name: 'salto_total_cuenta_sw',
                    },
                    {
                        data: 'fecha_reporte_sw',
                        name: 'fecha_reporte_sw',
                    },
                ],  
            });


            // let table = $('.datatable-User:not(.ajaxTable)').DataTable()//con botones 

            let table = $('.datatable-User:not(.ajaxTable)').DataTable({ buttons: dtButtons })
            
            // $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
            //     $($.fn.dataTable.tables(true)).DataTable()
            //         .columns.adjust();
            // });
        })
    </script>
@endsection
