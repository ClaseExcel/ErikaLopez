@extends('layouts.admin')
@section('title', 'Lista de centros costos')
@section('library')
    @include('cdn.datatables-head')
@endsection
@section('content')
    @can('CREAR_CENTROS_COSTOS')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-back border btn-radius" href="{{ route('admin.centros_costos.create') }}">
                    <i class="fas fa-circle-plus"></i> Agregar centros de costos
                </a>
            </div>
        </div>
    @endcan
     @if (session('message2'))
                    <div class="row px-2">
                        <div class="alert alert-{{ session('color') }} border-0 alert-dismissible fade show d-flex align-items-center"
                            role="alert">
                            <div class="d-flex flex-grow-1">
                                <div>
                                    <i class="fa-solid fa-circle-info"></i> <b>{{ session('message2') }}</b>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    </div>
                @endif
    <div class="card">
        <div class="card-header">
            <i class="fa-solid fa-landmark-dome"></i> Lista de centros de costos
        </div>

        <div class="card-body">
            <div class="">
                <table class="table-bordered table-striped display nowrap compact" id="datatable-CostCenters" style="width:100%">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Compañía cliente</th>
                            <th>Estado</th>
                            <th style="width: 120px">
                                &nbsp;
                            </th>
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
            table = new DataTable('#datatable-CostCenters', {
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
                ajax: "{{ route('admin.centros_costos.index') }}",
                dataType: 'json',
                type: "POST",
                columns: [
                {
                    data: 'codigo',
                    name: 'codigo',
                },
                {
                    data: 'nombre',
                    name: 'nombre',
                },
                {
                    data: 'compania.razon_social',
                    name: 'compania.razon_social',
                },
                {
                    data: 'estado',
                    name: 'estado',
                    render: function (data, type, full, meta) {
                        return data == 1 ? 'Activo' : 'Inactivo';
                    }
                },
                {
                    data: 'actions',
                    name: 'actions',
                    searcheable:false,
                    orderable: false,
                    className:'actions-size'
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
