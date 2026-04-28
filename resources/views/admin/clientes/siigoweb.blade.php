@extends('layouts.admin')
@section('title',"Siigo Web")
@section('library')
    @include('cdn.datatables-head')
@endsection
@section('content')
    @can('CREAR_CLIENTES')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-back border btn-radius" href="{{ route('admin.clientes.create') }}">
                    <i class="fas fa-circle-plus"></i> Agregar balances
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            <i class="fa-solid fa-server"></i> Lista de balances SIIGO WEB
        </div>

        <div class="card-body">
            <div class="">
                  <table class="table-bordered table-striped display nowrap compact" id="datatable-Clientes" style="width:100%">
                    <thead>
                        <tr>
                            <th>Empresa</th>
                            <th>Nivel</th>
                            <th>Transaccional</th>
                            <th>Código cuenta contable</th>
                            <th>Nombre cuenta contable</th>
                            <th>Saldo inicial</th>
                            <th>Movimiento débito</th>
                            <th>Movimiento crédito</th>
                            <th>Saldo final</th>
                            <th>Fecha reporte</th>
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
        document.addEventListener("DOMContentLoaded", function() {
            table = new DataTable('#datatable-Clientes', {
                language: {
                    url: "{{ asset('/js/datatable/Spanish.json') }}",
                },
                layout: {
                    topStart: ['search'],
                    topEnd: ['pageLength'],
                    bottomEnd: {
                        paging: {
                            type: 'simple_numbers',
                            numbers: 5,
                        }
                    }
                },
                ordering: true,
                //ordenar por la columna 0 de forma ascendente
                order: [
                    [5, 'desc']
                ],
                responsive: true,
                // columnDefs: [

                // ],
                // searching: true,
                pageLength: 10,
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.clientes.siigoweb') }}",
                dataType: 'json',
                type: "POST",
                columns: [
                    {
                        data: 'razon_social',
                        name: 'razon_social',
                    },
                    {
                        data: 'nivel_ga',
                        name: 'nivel_ga',
                    },
                    {
                        data: 'transacional_ga',
                        name: 'transacional_ga',
                    },
                    {
                        data: 'codigo_cuenta_contable_ga',
                        name: 'codigo_cuenta_contable_ga',
                    },
                    {
                        data: 'nombre_cuenta_contable_ga',
                        name: 'nombre_cuenta_contable_ga',
                    },
                    {
                        data: 'saldo_inicial_ga',
                        name: 'saldo_inicial_ga',
                    },
                    {
                        data: 'movimiento_debito_ga',
                        name: 'movimiento_debito_ga',
                    },
                    {
                        data: 'movimiento_credito_ga',
                        name: 'movimiento_credito_ga',
                    },
                    {
                        data: 'saldo_final_ga',
                        name: 'saldo_final_ga',
                    },
                    {
                        data: 'fechareporte_ga',
                        name: 'fechareporte_ga',
                    },
                ],  
                // select: {
                //     style: 'multi',
                //     className: 'selected-row',
                // },
                initComplete: function() {


                } //intiComplete


            });

        });
    </script>
@endsection
