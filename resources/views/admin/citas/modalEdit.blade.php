
<!-- Modal -->
<div class="modal fade" id="citaEditar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">

        <!-- Modal header -->
        <div class="modal-header  bg-help">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" style="color: #fff" stroke="currentColor" class="mb-0" width="25">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
          </svg>&nbsp;&nbsp;
          <span class="modal-title display-6 fs-3" id="titulo-actividad">  Editar cita</span>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closemodal()"></button>
        </div>

        <!-- Modal body -->
        <div class="modal-body"> 
            <div class="col mb-3">
                <div id="validation-errors-edit"></div>
            </div>

            <form>
                <div class="row">
                    <input type="hidden" id="citaId">
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-floating mb-3">
                            <input type="text" id="motivoEdit" name="motivo"
                                class="form-control {{ $errors->has('observacion') ? 'is-invalid' : '' }}"
                                placeholder=" " />
                            <label for="motivo" class="fw-normal">
                                Motivo <b class="text-danger">*</b></label>
                        </div>
                    </div>

                    <div class="col-sm-12 col-lg-6">
                        <div class="form-floating  mb-3">
                            <select id="modalidadEdit" name="modalidad_id"
                                class="form-select {{ $errors->has('modalidad_id') ? 'is-invalid' : '' }}"">
                                <option value="">Selecciona una modalidad</option>
                                @foreach ($modalidades as $modalidad)
                                    <option value="{{ $modalidad->id }}">{{ $modalidad->nombre }}</option>
                                @endforeach
                            </select>
                            <label class="fw-normal" for="modalidad_id">Modalidades <b class="text-danger">*</b></label>
                        </div>
                    </div>


                    <div class="col-12">
                        <div class="form-floating mb-3" id="fisicaEdit" style="display: none;">
                            <input type="text" id="direccionEdit" name="direccion"
                                class="form-control {{ $errors->has('direccion') ? 'is-invalid' : '' }} "
                                type="text" placeholder="" />
                            <label for="direccion" id="label-fisico" class="fw-normal">
                                Direccion</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-floating mb-3" id="virtualEdit" style="display: none;">
                            <input type="text" id="linkEdit" name="link"
                                class="form-control {{ $errors->has('link') ? 'is-invalid' : '' }}"
                                placeholder=" " />
                            <label for="link" id="label-virtual" class="fw-normal">
                                Link</label>
                        </div>

                    </div>


                    <div class="col-12">
                        <div class="form-floating mb-3">
                            <input type="text" id="observacionEdit" name="observacion"
                                class="form-control {{ $errors->has('observacion') ? 'is-invalid' : '' }}"
                                placeholder=" " />
                            <label for="observacion" class="fw-normal">
                                Observación</label>
                        </div>
                    </div>

                </div>
            </form>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer bg-transparent border-0">
            <button
            class="btn btn-save"
            onclick="editarCita()">Actualizar</button>
        </div>
      </div>
    </div>
  </div>