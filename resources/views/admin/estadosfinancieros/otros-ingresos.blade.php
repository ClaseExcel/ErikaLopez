
<!-- Contenedor para mostrar el modal -->
<div class="modal fade" id="otrosIngresosModal" tabindex="-1" role="dialog" aria-labelledby="otrosIngresosModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="otrosIngresosModalLabel">Resultados fecha: {{$fecha_inicio}}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="otrosIngresosModalBody">
             <!-- Aquí se cargarán los resultados -->
            <div id="loading-animation" class="text-center" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
