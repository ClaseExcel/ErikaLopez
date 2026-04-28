@extends('layouts.admin')
@section('title',"Siigo Local")
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
            <i class="fa-solid fa-database"></i> Lista de balances SIIGO LOCAL
        </div>

        <div class="card-body">
            <div class="">
                <table class="table-bordered table-striped display nowrap compact" id="datatable-Clientes" style="width:100%">
                    <thead>
                        <tr>
                            <th>Empresa</th>
                            <th>Grupo</th>
                            <th>Cuenta</th>
                            <th>Subcuenta</th>
                            <th>Descripción</th>
                            <th>Ultimo Movimiento</th>
                            <th>Saldo Anterio</th>
                            <th>Debitos</th>
                            <th>Creditos</th>
                            <th>Nuevo saldo</th>
                            <th>Fecha reporte</th>
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
                ajax: "{{ route('admin.clientes.index') }}", //ruta donde se encuentra el metodo index
                dataType: 'json',
                type: "POST",
                columns: [
                    {
                        data: 'razon_social',
                        name: 'razon_social',
                    },
                    {
                        data: 'grupo',
                        name: 'grupo',
                    },
                    {
                        data: 'cuenta',
                        name: 'cuenta',
                    },
                    {
                        data: 'subcuenta',
                        name: 'subcuenta',
                    },
                    {
                        data: 'descripcion',
                        name: 'descripcion',
                    },
                    {
                        data: 'ultimo_movimiento',
                        name: 'ultimo_movimiento',
                    },
                    {
                        data: 'saldo_anterior',
                        name: 'saldo_anterior',
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
                        data: 'nuevo_saldo',
                        name: 'nuevo_saldo',
                    },
                    {
                        data: 'fechareporte',
                        name: 'fechareporte',
                    },
                    // {
                    //     data: 'actions',
                    //     name: 'actions',
                    //     searcheable:false,
                    //     orderable: false,
                    //     className:'actions-size'
                    // },
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
