@extends('layouts.admin')
@section('title', 'Lista de modificaciones')
@section('library')
    @include('cdn.datatables-head')
@endsection
@section('content')
    <div class="row mb-0">
        <div class="col">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex justify-content-between align-items-center mb-1" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fa-solid fa-circle-info"></i> <b>&nbsp;{{ session('success') }}</b>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
    </div>
    @can('CREAR_MODIFICACIONES')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-back border btn-radius" href="{{ route('admin.modificaciones.create') }}">
                    <i class="fas fa-circle-plus"></i> Agregar modificaciones
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            <i class="fa-solid fa-sliders"></i> Lista de modificaciones 
        </div>

        <div class="card-body">
            <div class="">
                <table class="table-bordered table-striped display nowrap compact" id="datatable-Modification" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center">Compañía cliente</th>
                            <th class="text-center">Periodo contable</th>
                            <th class="text-center">Cuenta contable</th>
                            <th class="text-center">Valor ajustado</th>
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
    const baseUrl = "{{ url('admin/modificaciones') }}";
    function eliminar(id) {
            
            var route = `${baseUrl}/${id}`;
            console.log(route);
            Swal.fire({
                title: "¿Estás seguro?",
                text: "¿Deseas eliminar este registro?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#69c34e",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí",
                cancelButtonText: "Cancelar",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                        },
                        type: 'DELETE',
                        url: route,
                        success: function() {

                            Swal.fire({
                                title: "Eliminado",
                                text: "El registro ha sido eliminado exitosamente",
                                icon: "success"
                            }).then((result) => {
                                location.reload();
                            });
                        }
                    })

                }
            });
    }
    document.addEventListener("DOMContentLoaded", function() {
            table = new DataTable('#datatable-Modification', {
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
                ajax: "{{ route('admin.modificaciones.index') }}",
                dataType: 'json',
                type: "POST",
                columns: [
                {
                    data: 'compania.razon_social',
                    name: 'compania.razon_social',
                },
                {
                    data: 'periodo',
                    name: 'periodo',
                    className: 'text-right',
                },
                {
                    data: 'movimiento',
                    name: 'movimiento',
                    className: 'text-right',
                },
                {
                    data: 'valor_ajustado',
                    name: 'valor_ajustado',
                    className: 'text-right',
                    render: function (data, type, full, meta) {
                        return '$ ' + Number(data).toLocaleString('es-CO');
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
