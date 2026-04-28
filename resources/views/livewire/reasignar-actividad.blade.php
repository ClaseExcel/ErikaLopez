<div>
    <div class="modal fade" id="reasignar-modal" tabindex="-1" aria-labelledby="ReasignarModalLabel" aria-hidden="true"  data-bs-backdrop="static" data-bs-keyboard="false"  wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="ReasignarModalLabel"><i class="fas fa-exchange-alt"></i> Reasignar actividades</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="closeModal"></button>
            </div>
            <div class="modal-body">

              {{-- @foreach($selectedRows as $item)
                <span>{{ $item }}</span>
              @endforeach --}}
              
              
              {{-- lista de empresas --}}
              @if($empresa)
                <div class="form-floating mb-3">
                  <select class="form-select" disabled>
                      <option value="{{ $empresa->id }}">{{ $empresa->razon_social }}</option>
                  </select>
                    <label for="empresa" class="col-sm-2 col-form-label">Empresa</label>
                </div>
              @endif

              {{-- lista de empleados --}}
              @if($empleados)
                <div class="form-floating mb-4">
                  <select class="form-select" id="empleado" wire:model="empleado_id" wire:change="habilitarBotonGuardar">
                      <option value="" selected>Seleccione un empleado</option>
                      @foreach ($empleados as $empleado)         
                        {{-- @if($empleado->usuarios) --}}
                        <option value="{{ $empleado->id }}">{{ $empleado->nombres. ' ' . $empleado->apellidos  }}</option>
                        {{-- @endif --}}
                      @endforeach
                  </select>
                  <label for="empleado" class="col-sm-2 col-form-label">Empleado</label>
                </div>
              @endif


              {{-- tabla con actividades --}}
              @if($actividades)
              <div class="table-responsive" style="height: 165px; scrollbar-width: thin;">
                <table class="table table-hover table-striped table-sm">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Actividad</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($actividades as $actividad)
                    <tr>
                      <th scope="row">{{ $actividad->id }}</th>
                      <td>{{ $actividad->nombre }}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              @endif             
              
                


            </div>
            <div class="modal-footer border-0">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" wire:click="closeModal">Cancelar</button>
              <button type="button" class="btn btn-primary" wire:click="reasignar" {{ $isEnable }}>Guardar cambios</button>
            </div>
          </div>
        </div>
      </div>
</div>


<script>

  const isAuthReasignar = '{{ $authReasignar  }}';
  
  //abrir modal
  Livewire.on('show-reasignar-modal', () => {    
    $('#reasignar-modal').modal('show'); 
  });

  Livewire.on('close-reasignar-modal', () => {    
    $('#reasignar-modal').modal('hide');   
  });

  

</script>