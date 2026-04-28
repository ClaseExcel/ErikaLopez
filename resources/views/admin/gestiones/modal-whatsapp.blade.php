 <!-- Modal Enviar Correo -->
    <div class="modal fade" id="modalEnviarWhatsapp" tabindex="-1" aria-labelledby="modalWhatsappLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formEnviarWhatsapp" method="POST">
                @csrf
                <input type="hidden" name="gestion_id" id="WhatsappGestionId">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Enviar gestión por whatsapp</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control fw-normal" placeholder="número de teléfono"
                                id="numero" name="numero">
                            <label for="numero" class="fw-normal">Número a notificar</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-save">Enviar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>