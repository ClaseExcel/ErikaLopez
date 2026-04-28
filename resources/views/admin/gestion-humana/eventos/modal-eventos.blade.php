<style>
    .bg-header {
        background-color: #698DD6 !important;
        color: white;
    }
</style>
<div class="modal fade" id="modalEventos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa-solid fa-folder-open"></i> Novedades</h5>
                <a  class="btn btn-back" href="{{ route('admin.gestion-humana-eventos.create', $gestion_humana->id) }}" title="Crear nueva novedad"><i class="fa-solid fa-bullhorn"></i></a>
            </div>
            <div class="modal-body">

                <div class="col-xl-12">
                    <table class="table-bordered table-striped display nowrap compact" id="datatable-Eventos"
                        width="100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Observación</th>
                                <th>Concepto</th>
                                <th>Fecha de inicio</th>
                                <th>Fecha fin</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="closeModal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<script>
    let routeEventos = "{{ route('admin.gestion-humana-eventos.index') }}";
    let gestion_humana_id = "{{ $gestion_humana->id }}";
    let table; // Declare table in a higher scope
    document.addEventListener("DOMContentLoaded", function() {
        table = new DataTable('#datatable-Eventos', {
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
            processing: true,
            serverSide: true,
            responsive: true,
            ordering: true,
            pageLength: 3,
            ajax: {
                url: routeEventos,
                type: "GET",
                data: function(d) {
                    d.gestion_humana_id = gestion_humana_id;
                },
                dataType: 'json'
            },
            columns: [{
                    data: null,
                    className: 'text-center cursor-pointer',
                    render: function(data, type, row) {
                        var evento_id = row.id;
                        var editUrl = "{{ route('admin.gestion-humana-eventos.edit', ':id') }}"
                            .replace(':id', evento_id);
                        return `<a class="btn-secondary btn-sm" href="` + editUrl + `" title="Editar registro"><i class="fa-solid fa-pencil"></i>
                                </a>`;
                    },
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'observacion',
                    render: function(data, type, row) {

                        setTimeout(function() {
                            var popoverTriggerList = [].slice.call(document
                                .querySelectorAll('[data-bs-toggle="popover"]'));
                            popoverTriggerList.map(function(popoverTriggerEl) {
                                return new bootstrap.Popover(popoverTriggerEl, {
                                    container: '#modalEventos'
                                });
                            });
                        }, 100);

                        return data && data.length > 70 ?
                            `<span  tabindex="0"
                                        role="button"
                                        data-bs-toggle="popover"
                                        data-bs-trigger="hover focus"
                                        data-bs-content="${data ? data : 'No hay observación'}"
                                        data-bs-placement="top"
                                        style="cursor:pointer;"
                                        id="popover-detalle-${data}"> ${data.substring(0, 70) + '...'}</span>` :
                            (data ? data : 'No hay observación')
                    },
                },
                {
                    data: 'concepto.nombre',
                },
                {
                    data: 'fecha_inicio',
                    name: 'fecha_inicio',
                },
                {
                    data: 'fecha_fin',
                    name: 'fecha_fin',
                },
            ],
        });
    });
</script>