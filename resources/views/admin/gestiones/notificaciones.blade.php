@extends('layouts.admin')

@section('title', 'Notificaciones de Gestión')
@section('library')
    @include('cdn.datatables-head')
@endsection
@section('content')
    <div class="form-group">
        <a class="btn btn-back  border btn-radius px-4" href="{{ route('admin.gestiones.index') }}">
            <i class="fas fa-arrow-circle-left"></i> Atrás
        </a>
    </div>
    <div class="card">

        <div class="card-header">
            <h4><i class="fas fa-envelope"></i> Historial de Notificaciones de Gestión</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="notificaciones-table" style="width:100%">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Tipo Gestión</th>
                            <th>Fecha visita</th>
                            <th>Fecha Envío</th>
                            <th>Enviado por</th>
                            <th>Enviado a</th>
                            <th>Quién crea gestíon</th>
                            <th>Observación</th>
                            <th>Ver PDF</th>
                        </tr>
                    </thead>
                </table>
                
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@parent
    <script>
        document.addEventListener("DOMContentLoaded", function() {
                table = new DataTable('#notificaciones-table', {
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
                    pageLength: 10,
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('admin.gestiones.notificaciones') }}", //ruta donde se encuentra el metodo index
                    dataType: 'json',
                    type: "GET",
                    columns: [
                        { data: 'empresa', name: 'gestion.cliente.razon_social' },
                        { data: 'tipo_gestion', name: 'gestion.tipo_visita' },
                        { data: 'fecha_visita', name: 'fecha_visita' },
                        { data: 'fecha_envio', name: 'fecha_envio' },
                        { data: 'usuario', name: 'usuario.nombres' },
                        { data: 'correo', name: 'correo' },
                        { data: 'user_create_gestion_id', name: 'user_create_gestion_id' },
                        { data: 'observacion', name: 'observacion' }, // Esta línea ahora sí funciona
                        { data: 'ver_pdf', name: 'ver_pdf', orderable: false, searchable: false }
                    ],
                    initComplete: function() {


                    } //intiComplete


                });
            })
    </script> 
@endsection
