 <!-- Modal Enviar Correo -->
    <div class="modal fade" id="modalEnviarCorreo" tabindex="-1" aria-labelledby="modalCorreoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formEnviarCorreo" method="POST">
                @csrf
                <input type="hidden" name="gestion_id" id="correoGestionId">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Enviar gestión por correo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control fw-normal" placeholder="Correos adicionales"
                                id="correos_adicionales" name="correos_adicionales"
                                pattern="^([\w\.-]+@[\w\.-]+\.[a-zA-Z]{2,})(,\s*[\w\.-]+@[\w\.-]+\.[a-zA-Z]{2,})*$"
                                title="Debe ser una lista de correos separados por comas. Ejemplo: correo1@example.com, correo2@example.com">
                            <label for="correos_adicionales" class="fw-normal">Correos adicionales (separados por
                                coma)</label>
                        </div>
                        <div class="mb-3">
                            <label for="observacionCorreo" class="form-label">Observación</label>
                            <textarea name="observacion" id="observacionCorreo" class="form-control" rows="4"
                                placeholder="Escribe una observación..."></textarea>
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