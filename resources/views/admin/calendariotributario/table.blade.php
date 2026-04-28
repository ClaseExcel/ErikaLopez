@extends('layouts.admin')
@section('title', 'Calendario Tributario')
@section('library')
    @include('cdn.datatables-head')
@endsection
@section('content')

    @can('CREAR_CALENDARIO_TRIBUTARIO')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-back  border btn-radius" href="{{ route('admin.calendario.index') }}">
                    <i class="fas fa-arrow-circle-left"></i> Atrás 
                </a>
            </div>
            
        </div>
    @endcan

        <div class="card">
            <div class="card-header">
                <i class="far fa-calendar"></i> Calendario Tributario
            </div>
    
            <div class="card-body">
                <div>
                    <div class="">
                        <table class="table-bordered table-striped display nowrap compact" id="datatable-Masivo" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Código tributario</th>
                                    <th>Fecha Vencimiento</th>
                                    <th>Detalle tributario</th>
                                    <th>Últimos dígitos</th>
                                    <th>Ultimo dígito</th>
                                    <th>Rango inicial</th>
                                    <th>Rango final</th>
                                    <th>Código municipio</th>
                                </tr>
                            </thead>
                            <tbody>
                        
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
        document.addEventListener("DOMContentLoaded", function() {
            table = new DataTable('#datatable-Masivo', {
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
                columnDefs: [
                    { "className": "text-right", "targets": [0, 2, 5, 6] },
                    { "className": "text-center", "targets": [1, 3, 4] },
                ],
                // searching: true,
                pageLength: 10,
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.calendario.table') }}", //ruta donde se encuentra el metodo index
                dataType: 'json',
                type: "POST",
                columns: [
                    {
                        data: 'codigo_tributario',
                        name: 'codigo_tributario',
                    },
                    {
                        data: 'fecha_vencimiento',
                        name: 'fecha_vencimiento',
                    },
                    {
                        data: 'detalle_tributario',
                        name: 'detalle_tributario',
                    },
                    {
                        data: 'ultimos_digitos',
                        name: 'ultimos_digitos',
                    },
                    {
                        data: 'ultimo_digito',
                        name: 'ultimo_digito',
                    },
                    {
                        data: 'rango_inicial',
                        name: 'rango_inicial',
                    },
                    {
                        data: 'rango_final',
                        name: 'rango_final',
                    },
                   
                    {
                        data: 'codigo_municipio',
                        name: 'codigo_municipio',
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