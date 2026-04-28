<!-- Modal -->
<div class="modal fade" id="actividadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <!-- Modal header -->
            <div class="modal-header  bg-help">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    style="color: #fff" stroke="currentColor" class="mb-0" width="25">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>&nbsp;&nbsp;
                <span class="modal-title display-6 fs-3" id="titulo-actividad"> Resumen de la actividad</span>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <input type="hidden" name="actividad_id" id="actividad_id">
                <input type="hidden" name="requerimiento_id" id="requerimiento_id">
                <div id="descripcion"></div>
            </div>

            <div class="modal-body mb-1 pb-1 d-flex justify-content-end">
                @if (Auth::user()->role_id != 7)
                    <div class="row" style="display:none;" id="notificaciones">
                        <div class="col-12 mx-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="form-notificacion">
                                <label class="form-check-label" for="form-notificacion">Notificar <i
                                        class="fa-solid fa-envelope"></i></label>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <form class="modal-body" action="{{ route('admin.calendario-actividades.correo') }}" method="POST"
                enctype="multipart/form-data" id="form-correo" style="display: none;">
                @csrf
                <div class="row">
                    <input type="hidden" name="id_actividad_calendario" id="id_actividad_calendario">
                    <div class="col-xl-12 mx-2 mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="agregar-correos-checkbox">
                            <label class="form-check-label" for="agregar-correos-checkbox">
                                ¿Agregar correos adicionales?
                            </label>
                        </div>
                    </div>
                    <div class="col-xl-12 mb-2">
                        <div class="form-floating" id="correo-adicional-email" style="display: none;">
                            <input type="text" class="form-control fw-normal" placeholder="Correos adicionales"
                                id="correos_adicionales" name="correos_adicionales"
                                title="Debe ser una lista de correos separados por comas. Ejemplo: correo1@example.com, correo2@example.com">
                            <label for="correos_adicionales" class="fw-normal">Correos adicionales (separados por
                                coma)</label>
                        </div>
                    </div>
                    <div class="col-xl-12 mb-2">
                        <div class="form-floating" id="observacion-email">
                            <input type="text" class="form-control fw-normal"
                                placeholder="Ingresa una observación o un enlace" id="observacion_email"
                                name="observacion_email">
                            <label for="observacion_email" class="fw-normal">Observación Email</label>
                        </div>
                    </div>
                    <div class="col-xl-12 mb-3" id="divarchivo">
                        <small for="formFileMultiple" class="fw-normal text-info">Sube como máximo 3 documentos</small>
                        <input class="form-control" type="file" id="formFileMultiple" name="notificacion_pdf[]"
                            multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.zip" onchange="validateFiles()">
                    </div>

                    <div class="bg-transparent border-0 text-center">
                        <button id="enviarCorreo" class="btn btn-back border btn-radius">Enviar Correo</button>
                    </div>

                </div>
            </form>


            <!-- Modal footer -->
            <div class="modal-footer bg-transparent border-0">
                <button id="button_reasignar" style='display:none;' class="btn btn-save"
                    onclick="reasignarActividad()">Reasignar responsable</button>
                <button id="button_reportar" style='display:none;' class="btn btn-save"
                    onclick="reportarActividad()">Reportar actividad</button>

                <button id="button_ver" style='display:none;' class="btn btn-save" onclick="verReq()">Ver
                    requerimientos</button>
                <button id="button_reportar_req" style='display:none;' class="btn btn-save"
                    onclick="reportarReq()">Reportar requerimiento</button>
            </div>
        </div>
    </div>
</div>


<script>
    function validateFiles() {
        const input = document.getElementById('formFileMultiple');
        const maxFiles = 3;
        const maxSize = 2 * 1024 * 1024; // Tamaño máximo de 2 MB

        let errors = [];

        // Validar cantidad de archivos
        if (input.files.length > maxFiles) {
            errors.push(`Solo puedes subir un máximo de ${maxFiles} archivos.`);
        }

        // Validar tamaño de cada archivo
        for (const file of input.files) {
            if (file.size > maxSize) {
                errors.push(`El archivo "${file.name}" supera el tamaño máximo permitido de 2 MB.`);
            }
        }

        // Mostrar errores con SweetAlert
        if (errors.length > 0) {
            Swal.fire({
                icon: 'error',
                title: 'Error en la selección de archivos',
                html: errors.join('<br>'),
            });

            // Limpiar el input para forzar nueva selección
            input.value = '';
        }
    }

    function validarCorreosAdicionales() {
        const correosAdicionalesInput = document.getElementById('correos_adicionales').value.trim();
        if (correosAdicionalesInput === '') {
            return true; // No hay correos adicionales, todo está bien
        }
        const correosArray = correosAdicionalesInput.split(',');
        const correoRegex = /^[\w\.-]+@[\w\.-]+\.[a-zA-Z]{2,}$/;

        // Verificar si todos los correos cumplen con el formato
        for (let correo of correosArray) {
            correo = correo.trim();
            if (!correoRegex.test(correo)) {
                return false; // El correo no es válido
            }
        }
        return true; // Todos los correos son válidos
    }

    const notificarCorreoCheckbox = document.getElementById('form-notificacion');
    const formCorreo = document.getElementById('form-correo');

    notificarCorreoCheckbox.addEventListener('change', function() {
        if (this.checked) {
            formCorreo.style.display = 'block';
        } else {
            formCorreo.style.display = 'none';
        }
    });


    const correosAdicionalesDiv = document.getElementById('correo-adicional-email');
    const agregarCorreosCheckbox = document.getElementById('agregar-correos-checkbox');

    agregarCorreosCheckbox.addEventListener('change', function() {
        if (this.checked) {
            correosAdicionalesDiv.style.display = 'block';
        } else {
            correosAdicionalesDiv.style.display = 'none';
        }
    });

    const formulario = document.getElementById('form-correo');

    formulario.addEventListener('submit', function(event) {
        event.preventDefault(); // Evitar el envío normal del formulario
        var formData = new FormData(formulario); // Crear un objeto FormData

        if (!validarCorreosAdicionales()) {
            Swal.fire({
                title: 'Error en los correos adicionales',
                text: 'Uno o más correos no tienen un formato válido.',
                icon: 'error',
                confirmButtonText: 'Revisar'
            });
            return;
        }

        Swal.fire({
            title: 'Enviando correo...',
            icon: 'info',
            showConfirmButton: false,
            position: 'top',
            toast: true,
            timer: 1500
        });

        // Enviar los datos usando fetch
        $.ajax({
            url: $(this).attr('action'), // URL del endpoint
            type: 'POST',
            data: formData,
            contentType: false, // No establecer el tipo de contenido
            processData: false, // No procesar los datos
            success: function(response) {

                Swal.fire({
                    icon: 'success',
                    title: 'Correo enviado',
                    text: 'El correo fue enviado correctamente.',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    // Limpiar los campos de observación y correos adicionales
                    document.getElementById('observacion_email').value = '';
                    document.getElementById('correos_adicionales').value = '';
                    location.reload();
                });

            },
            error: function(jqXHR, textStatus, errorThrown) {

                Swal.fire({
                    title: "Error",
                    text: jqXHR.responseJSON.error ? jqXHR.responseJSON.error :
                        "Hubo un error al enviar el correo.",
                    icon: "error"
                });

                console.error('Error:', textStatus, errorThrown);
            }
        });
    });
</script>
