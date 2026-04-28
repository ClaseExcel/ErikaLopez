@extends('layouts.admin')
@section('title',"Mov Siigo Local")
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
            <i class="fa-solid fa-database"></i> Lista de clientes movimientos siigo local
        </div>

        <div class="card-body">
            <div class="">
                <table class="display nowrap datatable-User w-100">
                    <thead>
                        <tr>
                            <th>EMPRESA</th>
                            <th>CUENTA  DESCRIPCION</th>
                            <th>SALDO INICIAL</th>
                            <th>COMPROBANTE</th>
                            <th>FECHA</th>
                            <th>NIT</th>
                            <th>NOMBRE</th>
                            <th>DESCRIPCION</th>
                            <th>INVENTARIO-CRUCE-CHEQUE</th>
                            <th>BASE</th>
                            <th>CC SCC</th>
                            <th>DEBITOS</th>
                            <th>CREDITOS</th>
                            <th>SALDO MOV.</th>
                            <th>FECHA REPORTE</th>
                            {{-- <th style="width: 120px">
                                &nbsp;
                            </th> --}}
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
                    [1, 'DESC']
                ],  
                exportOptions: {
                columns: [0, 1, 2], // Especificar las columnas que se deben exportar
                },                                
                pageLength: 10,
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.movimientos.index') }}", //ruta donde se encuentra el metodo index
                dataType: 'json',
                type: "POST",
                columns: [
                    {
                        data: 'razon_social',
                        name: 'razon_social',
                    },
                    {
                        data: 'cuenta_descripcion',
                        name: 'cuenta_descripcion',
                    },
                    {
                        data: 'saldoinicial',
                        name: 'saldoinicial',
                    },
                    {
                        data: 'comprobante',
                        name: 'comprobante',
                    },
                    {
                        data: 'fecha',
                        name: 'fecha',
                    },
                    {
                        data: 'nit_sl',
                        name: 'nit_sl',
                    },
                    {
                        data: 'nombre',
                        name: 'nombre',
                    },
                    {
                        data: 'descripcion',
                        name: 'descripcion',
                    },
                    {
                        data: 'inventario_cruce_cheque',
                        name: 'inventario_cruce_cheque',
                    },
                    {
                        data: 'base',
                        name: 'base',
                    },
                    {
                        data: 'cc_scc',
                        name: 'cc_scc',
                    },
                    {
                        data: 'debitos',
                        name: 'debitos',
                    },
                    {
                        data: 'creditos',
                        name: 'creditos',
                    },
                    {
                        data: 'saldo_mov',
                        name: 'saldo_mov',
                    },
                    {
                        data: 'fecha_reporte',
                        name: 'fecha_reporte',
                    },
                    // {
                    //     data: 'actions',
                    //     name: 'actions',
                    //     searcheable:false,
                    //     orderable: false,
                    //     className:'actions-size'
                    // },
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
