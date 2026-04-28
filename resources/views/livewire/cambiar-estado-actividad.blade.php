<div>
    <div class="modal fade w-100" id="estado-modal" tabindex="-1" aria-labelledby="estadoModalLabel" aria-hidden="true"  data-bs-backdrop="static" data-bs-keyboard="false"  wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="estadoModalLabel"><i class="fas fa-retweet"></i> Cambiar estado</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="closeModal"></button>
            </div>
            <div class="modal-body">

              <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i> <strong>Advertencia:</strong> La modificación de múltiples registros podría afectar el resultado en los informes y reportes. Utilice esta opción con precaución.
                {{-- <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> --}}
              </div>

                {{-- lista de estados --}}
                <div class="form-floating mb-3">
                        <select class="form-select" id="idEstado" wire:model="idEstado" wire:change.prevent="camposExtra" >
                            <option value="0" selected>Seleccione un estado</option>
                            @foreach ($estados as $estado)
                            <option value="{{ $estado->id }}">{{ $estado->nombre }}</option>
                            @endforeach
                        </select>
                    <label for="estado" class="fw-normal">Estado  <strong class="text-danger">*</strong></label>
                </div>

                
                
                {{-- campos extra --}}

                {{-- lista de modalidades --}}
                @if($isModalidad)
                <div class="form-floating mb-3">
                    <select class="form-select" id="idModalidad" wire:model="modalidad" wire:change="validarModalidad" >
                        <option value="" selected>Seleccione una modalidad</option>
                        @foreach ($modalidades as $modalidad)
                        <option value="{{ $modalidad }}">{{ $modalidad }}</option>
                        @endforeach
                    </select>
                    <label for="idModalidad" class="fw-normal">Modalidad  <strong class="text-danger">*</strong></label>
                </div>
                @endif

                {{-- progreso select de 1 en 1 hasta 100 --}}
                @if($isProgreso)
                  <div class="form-floating mb-3">
                      <select class="form-select" id="progreso" wire:model="progreso" required wire:change="validarProgreso">
                          <option value="" selected>Seleccione un progreso</option>
                          @for ($i = 1; $i <= 100; $i++)
                              <option value="{{ $i }}">{{ $i }}%</option>
                          @endfor
                      </select>
                      <label for="progreso" class="fw-normal">Progreso <strong class="text-danger">*</strong> </label>
                  </div>
                @endif
                {{-- justificacion --}}
                @if($isJustificacion)
                  <div class="form-group mb-3">
                    <label for="justificacion" class="fw-normal mb-0 text-secondary">Justificación <strong class="text-danger">*</strong></label>
                      <textarea class="form-control" id="justificacion" wire:model="justificacion" placeholder="Escribe una justificación..." rows="3" required wire:keydown="validarJustificacion"></textarea>
                  </div>
                @endif

                {{-- recomendacion--}}
                @if($isRecomendacion)
                  <div class="form-group mb-3">
                    <label for="recomendacion" class="fw-normal mb-0">Recomendación <strong class="text-danger">*</strong></label>
                      <textarea class="form-control" id="recomendacion" wire:model="recomendacion" placeholder="Escribe una recomendación..." rows="3" required wire:keydown="validarRecomendacion"></textarea>
                  </div>
                @endif



                {{-- fecha de entrega --}}
                @if($isFechaVencimiento)
                  <div class="form-floating mb-3">
                      <input type="date" class="form-control" id="fechaVencimiento" wire:model="fechaVencimiento" min="{{ date('Y-m-d') }}" required wire:change="validarFechaVencimiento">
                      <label for="fechaVencimiento" class="fw-normal">Fecha de Vencimiento <strong class="text-danger">*</strong></label>
                  </div>
                @endif
            </div>

            <div class="modal-footer border-0">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" wire:click="closeModal">Cancelar</button>
              <button type="button" class="btn btn-primary" wire:click="cambiarEstado" {{ $isEnable == true ? '' : 'disabled' }} >Guardar cambios</button>
            </div>
          </div>
        </div>
      </div>
</div>

<script>

  const isAuthEstado = '{{ $authCambiarEstado  }}';

  //abrir modal
  Livewire.on('show-actividad-modal', () => {    
    $('#estado-modal').modal('show'); 
  });

  Livewire.on('close-actividad-modal', () => {    
    $('#estado-modal').modal('hide');   
  });
  
</script>