@extends('layouts.admin')
@section('title', 'Seguimiento: ' . $checklist->empresa->razon_social)
@section('library')
    @include('cdn.datatables-head')
@endsection
@section('content')

    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-back  border btn-radius"
                onclick="location.href='{{ route('admin.checklist_empresas.index') }}'">
                <i class="fas fa-arrow-circle-left"></i> Atrás
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-10">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.seguimiento_checklist.store') }}"
                        enctype="multipart/form-data">
                        @csrf

                        @include('admin.checklists.seguimiento.fields')

                        <div class="form-group text-end">
                            <button class="btn btn-save btn-radius px-4" type="submit">
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let table;
        // Initialize DataTable with Spanish language support and other configurations
        document.addEventListener("DOMContentLoaded", function() {
            table = new DataTable('#example', {
                language: {
                    url: "{{ asset('/js/datatable/Spanish.json') }}",
                },
                scrollY: 500,
                ordering: false,
                paging: false,
                searching: false,
                info: false,
                responsive: true,
            });

        });

        $('#mes').change(function() {
            let mes = $(this).val();
            let checklist_empresa_id = $('#checklist_empresa_id').val();
            var route =
                "{{ route('admin.seguimiento_checklist.mes_existente', [':checklist_empresa_id', ':mes']) }}"
                .replace(':checklist_empresa_id', checklist_empresa_id).replace(':mes', mes);
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                type: 'GET',
                url: route,
                success: function(response) {
                    if (response.seguimiento) {
                        var actividades_presentadas = JSON.parse(response.seguimiento.actividades_presentadas);
                        var observaciones = JSON.parse(response.seguimiento.observaciones);

                        // Si no hay seguimiento, puedes limpiar los checkboxes
                        $('input[type="checkbox"]').prop('checked', false);
                        // Y limpiar los textareas
                        $('textarea').val('');

                        // Marcar los checkboxes correspondientes
                        actividades_presentadas.forEach(function(actividad_id) {
                            $('#check' + actividad_id).prop('checked', true);
                        });

                        // Rellenar las observaciones en los textareas correspondientes
                        for (const [actividad_id, observacion] of Object.entries(observaciones)) {
                            $('#observaciones' + actividad_id).val(observacion);
                        }
                    } else {
                        // Si no hay seguimiento, puedes limpiar los checkboxes
                        $('input[type="checkbox"]').prop('checked', false);
                        // Y limpiar los textareas
                        $('textarea').val('');
                        // Si no hay seguimiento, puedes continuar con la lógica normal
                        console.log('No hay seguimiento para este mes.');
                    }
                }
            })
        });
    </script>
@endsection
