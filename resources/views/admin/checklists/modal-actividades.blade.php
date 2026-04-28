<div class="modal fade" id="modalInformacion" tabindex="-1" aria-labelledby="modalInformacionLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Seguimiento de actividades</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <div class="col-12 col-xl-6 mb-3">
                    <div class="form-floating mb-4">
                        <select id="periodicidad" name="periodicidad" class="form-select">
                            <option value="">Selecciona una periodicidad</option>
                            <option value="1">Anual</option>
                            <option value="12">Mensual</option>
                            <option value="2">Bimestral</option>
                            <option value="4">Cuatrimestral</option>
                            <option value="6">Semestral</option>
                        </select>
                        <label for="periodicidad" class="fw-normal">Periodicidad </label>
                    </div>
                </div>

                <input type="hidden" name="actividad_id" id="actividad_id">
                <table class="table-bordered display nowrap compact" id="tablaActividades" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Actividad</th>
                            @foreach ($mesesMap as $mes)
                                <th>{{ $mes }}: </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Se llena dinámicamente con JS -->
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Actividad</th>
                            @foreach ($mesesMap as $mes)
                                <th>{{ $mes }}: </th>
                            @endforeach
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var tablaActividades = $('#tablaActividades').DataTable({
            language: {
                url: "{{ asset('/js/datatable/Spanish.json') }}",
            },
            searching: false,
            ordering: false,
            responsive: true,
            paging: false,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.actividades-realizadas.index') }}",
                type: "POST",

                dataType: 'json',
                data: function(d) {
                    d._token = "{{ csrf_token() }}";
                    d.filter = $('#actividad_id').val();
                    d.periodicidad = $('#periodicidad').val()
                },
            },
            columns: [{
                    data: 'actividad',
                    name: 'actividad',
                    render: function(data, type, row) {
                        return `
                            <div class="d-flex flex-column" style="min-width:230px;">
                                <span class="flex-grow-1 text-truncate" title="${data}" style="max-width:230px;"> ${data}
                                </span>
                            </div>`;
                    }
                },
                @foreach ($mesesMap as $mes)
                    {
                        data: '{{ strtolower($mes) }}',
                        name: '{{ strtolower($mes) }}',
                        render: function(data) {
                            return data ? '✅' : '';
                        }
                    },
                @endforeach
            ],
            initComplete: function() {
                //Recarga la tabla con el valor que he seleccionado en responsables
                $('#periodicidad').change(function() {
                    tablaActividades.ajax.reload();
                });
            } //intiComplete
        });

        // Capturar el click en el botón de la tabla principal
        $(document).on('click', '.ver_actividades', function() {
            var actividadId = $(this).data('actividadid'); // tomar el id del checklist
            $('#actividad_id').val(actividadId); // asignar al input hidden
            tablaActividades.ajax.reload(); // recargar con el filtro
        });

        $('#modalInformacion').on('hidden.bs.modal', function() {
            $('#periodicidad').val('');
            $('#actividad_id').val('');
            tablaActividades.clear().draw();
        });

    });
</script>
