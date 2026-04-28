<!-- Modal -->
<div class="modal fade" id="ShowCitaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">

            <!-- Modal header -->
            <div class="modal-header bg-help">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    style="color: #fff" stroke="currentColor" class="mb-0" width="25">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>&nbsp;&nbsp;
                <span class="modal-title display-6 fs-3" id="titulo-cita"> </span>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body py-1">
                <input type="hidden" id="cita_id">
                <div class="row">
                    <div class="col-xl-6">
                        <span class="fs-3" id="motivo"></span>
                    </div>
                    <div class="col-xl-12 mb-3" style="overflow: hidden;white-space: nowrap;text-overflow: ellipsis;">
                        <span class="fs-4" id="modalidad"></span>
                    </div>
                    <div class="col-xl-12 mb-3">
                        <span id="horario"></span>
                    </div>
                    <div class="col-xl-12 mb-3">
                        <span id="observacion"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6">
                        <span id="empresa"></span>
                        
                    </div>
                    <div class="col-xl-6">
                        <span id="empleado"></span>
                    </div>
                </div>
              
            </div>


            <!-- Modal footer -->
            <div class="modal-footer bg-transparent border-0">


                {{-- @can('EDITAR_CITAS_CLIENTE')
                    <button onclick="showModalEditarCita();" id="editarCita" class="btn btn-save" type="submit">
                        Editar cita
                    </button>
                @endcan


                <button onclick="cancelarCita();" id="cancelarCita"  class="btn btn-save" type="submit">
                    Cancelar cita
                </button> --}}

            </div>
        </div>
    </div>
</div>
</div>
