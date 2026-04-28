<div class="modal fade" id="modalFiltro" tabindex="-1" aria-labelledby="modalFiltroLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filtro actividades</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <form action="{{ route('admin.filtro-actividades') }}" method="POST" id="form-filtro">
                    @csrf

                    <input type="hidden" value="{{ $checklist->id }}" name="id_checklist">
                    <div class="row">
                        <div class="col-12 col-xl-12">
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

                        <div class="form-group text-end">
                            <button class="btn btn-save btn-radius px-4" type="submit" form="form-filtro">
                                <i class="fa-solid fa-filter"></i>  Filtrar
                            </button>
                            <a class="btn  btn-back border btn-radius px-4"
                                href="{{ route('admin.checklist_empresas.show', $checklist->id) }}">
                                <i class="fa-solid fa-rotate fa-lg"></i> Reiniciar filtro
                            </a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
