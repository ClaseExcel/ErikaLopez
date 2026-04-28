@extends('layouts.admin')
@section('title', 'Fechas existentes')
@section('library')
    @include('cdn.datatables-head')
@endsection

@section('content')
    <div class="form-group">
        <a class="btn btn-back border btn-radius px-4" href="{{ url()->previous() }}">
            <i class="fas fa-arrow-circle-left"></i> Atrás
        </a>
    </div>
    <div class="card" style="max-width: 1100px; margin-left: 0;">
        <div class="card-header">
            <i class="fa-solid fa-database"></i> Fechas Existentes
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-striped table-bordered w-100" id="fechasExistentesTable">
                    <thead>
                        <tr>
                            <th>NIT</th>
                            <th>Empresa</th>
                            <th>Usuario cargo</th>
                            <th>Año Reporte</th>
                            <th>Mes Reporte</th>
                            <th>Fecha Creación</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $('#fechasExistentesTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("admin.clientes.existentes.data") }}',
            columns: [
                { data: 'NIT', name: 'empresa.NIT' },
                { data: 'razon_social', name: 'empresa.razon_social' },
                { data: 'usuario', name: 'creador.name' },
                { data: 'year_reporte', name: 'fecha_creacion' },
                { data: 'month_reporte', name: 'month_reporte' },
                { data: 'created_at', name: 'created_at' }
            ],
            language: {
                url: "{{ asset('/js/datatable/Spanish.json') }}",
            },
            dom: 'lftip' // <-- esto elimina botones y simplifica controles
        });
    });
</script>
@endsection 
