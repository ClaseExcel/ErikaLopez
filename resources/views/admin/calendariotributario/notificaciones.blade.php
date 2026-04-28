@extends('layouts.admin')
@section('title', 'Notificaciones Calendario Tributario')
@section('library')
    @include('cdn.datatables-head')
@endsection
@section('content')

    @can('ACCEDER_CALENDARIO_TRIBUTARIO')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-back border btn-radius" href="{{ route('admin.calendario.index') }}">
                    <i class="fas fa-arrow-circle-left"></i> Atrás 
                </a>
            </div>
            
        </div>
    @endcan
    <style>
        .datatable-User td:nth-child(6) { /* La sexta columna es la de correos */
            max-width: 200px; /* Ajusta este valor según lo que prefieras */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
    <div class="card">
        <div class="card-header">
            <i class="far fa-calendar"></i> Notificaciones enviadas
        </div>

        <div class="card-body">
            <div>
                <div class="">
                    <table class="table-bordered table-striped display nowrap compact" id="datatable-Notificacion" style="width:100%">
                        <thead>
                            <tr>
                                <th>Nombre Empresa</th>
                                <th>Fecha envio</th>
                                <th>Usuario envia</th>
                                <th>Obligación</th>
                                <th>Observación correo</th>
                                <th>Correo enviado</th>
                                <th>Archivo</th>
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
            table = new DataTable('#datatable-Notificacion', {
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
                ajax: "{{ route('admin.calendario.notificacion') }}", //ruta donde se encuentra el metodo index
                dataType: 'json',
                type: "GET",
                columns: [
                    {
                        data: 'nombre_empresa',
                        name: 'nombre_empresa',
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data, type, full, meta) {
                            // Formatear la fecha usando JavaScript
                            var date = new Date(data);
                            var day = date.getDate().toString().padStart(2, '0');
                            var month = (date.getMonth() + 1).toString().padStart(2, '0'); // Enero es 0
                            var year = date.getFullYear();
                            var hours = date.getHours().toString().padStart(2, '0');
                            var minutes = date.getMinutes().toString().padStart(2, '0');
                            var formattedDate = day + '-' + month + '-' + year + ' ' + hours + ':' + minutes;
                            return formattedDate;
                        }
                    },
                    {
                        data: 'usuario_nombre',
                        name: 'usuario_nombre',
                    },
                    {
                        data: 'obligacion',
                        name: 'obligacion',
                    },
                    {
                        data: 'observacion_correo',
                        name: 'observacion_correo',
                    },
                    {
                        data: 'correos',
                        name: 'correos',
                        render: function(data, type, full, meta) {
                            // Verificar si data es null o una cadena vacía
                            if (!data) {
                                return '';
                            }
                            return '<span title="' + data + '">' + data + '</span>';
                        }
                    },
                    {
                        data: 'archivo_url',
                        name: 'archivo_url',
                        render: function(data, type, full, meta) {
                            return data ? '<a href="' + data + '" class="btn border btn-radius" target="_blank"><i class="fa-solid fa-file-export"></i></a>' : '';
                        },
                        orderable: false,
                        searchable: false
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
