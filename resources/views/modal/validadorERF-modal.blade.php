<button type="button" class="btn btn-light border btn-radius px-4" id="btnValidar"><i class="fa-solid fa-check-double"></i> Validador</button>

<!-- Modal -->
<div class="modal fade" id="modalValidacion" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Validación de Totales</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        
        <!-- Botones resumido/detallado -->
        <div class="d-flex justify-content-end mb-3">
          <div class="btn-group" role="group">
            <button type="button" id="btnResumido" class="btn btn-light border btn-radius px-4 btn-sm active">
              Resumido
            </button>
            <button type="button" id="btnDetallado" class="btn btn-light border btn-radius px-4 btn-sm">
              Detallado
            </button>
          </div>
        </div>

        <!-- Aquí se inyecta la tabla -->
        <div id="resultadoValidacion"></div>

      </div>
    </div>
  </div>
</div>

