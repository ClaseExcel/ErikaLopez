<style>
    .bg-header {
        background-color: #698DD6 !important;
        color: white;
    }
</style>
<div class="modal fade" id="modalFiltroEventos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa-solid fa-folder-open"></i>Exportar Novedades
                </h5>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.gestion-humana-eventos.exportar') }}" method="GET"
                    id="formExportarEventos">

                    <div class="row">
                        <div class="col-xl-6">
                            <div class="form-floating mb-3">
                                <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control"
                                    value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" placeholder=" " />
                                <label for="fecha_inicio" class="fw-normal">Fecha inicio </label>
                            </div>
                        </div>


                        <div class="col-xl-6">
                            <div class="form-floating mb-3">
                                <input type="date" name="fecha_fin" id="fecha_fin"
                                    value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="form-control"
                                    placeholder=" " />
                                <label for="fecha_fin" class="fw-normal">Fecha fin </label>
                            </div>
                        </div>
                    </div>

                </form>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-save" form="formExportarEventos"><i
                        class="fa-solid fa-file-export"></i> Exportar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="closeModal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<script></script>
