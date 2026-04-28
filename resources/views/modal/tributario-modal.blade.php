<!-- Modal -->
<div class="modal fade" id="tributarioModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <style>
                .swal2-container {
                    z-index: 999999 !important;
                }
            </style>
            <!-- Modal header -->
            <div class="modal-header bg-primary text-white">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    style="color: #fff" stroke="currentColor" class="mb-0" width="25">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>&nbsp;&nbsp;
                <span class="modal-title display-6 fs-3" id="titulo-tributario"> Resumen de la tributario</span>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <input type="hidden" name="tributario_id" id="tributario_id">
                <input type="hidden" name="requerimiento_id" id="requerimiento_id">
                <div class="textarea"></div>
                <div id="descripcion_t"></div>
                @unless (in_array(Auth::user()->role_id, [7]))
                    <div class="form-floating" id="divobservacion">
                        <input type="text" class="form-control" placeholder="ingresa una observación" id="observacion_id"
                            name="observacion">
                        <label for="observacion">Observación DIAN</label>
                    </div>
                    <div class="form-floating" id="divobservacion2">
                        <input type="text" class="form-control" placeholder="ingresa una observación"
                            id="observacion_id2" name="observacion2">
                        <label for="observacion2">Observación Municipal</label>
                    </div>
                    <div class="form-floating" id="divobservacion3">
                        <input type="text" class="form-control" placeholder="ingresa una observación"
                            id="observacion_id3" name="observacion3">
                        <label for="observacion3">Observación Otras entidades</label>
                    </div>
                @endunless
            </div>

            <!-- Modal footer -->
            <div class="modal-footer bg-transparent border-0 text-center">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked"
                        style="display: inline-block;"
                        {{ in_array(Auth::user()->role_id, [7]) ? 'hidden disabled' : 'onclick="revisar()"' }}>
                    <label class="form-check-label" for="flexSwitchCheckChecked" id="flexlabel"
                        style="display: inline-block;"
                        {{ in_array(Auth::user()->role_id, [7]) ? 'hidden' : '' }}>Presentado</label>
                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked2"
                        style="display: inline-block;"
                        {{ in_array(Auth::user()->role_id, [7]) ? 'hidden disabled' : 'onclick="revisar()"' }}>
                    <label class="form-check-label" for="flexSwitchCheckChecked2" id="flexlabel2"
                        style="display: inline-block;"
                        {{ in_array(Auth::user()->role_id, [7]) ? 'hidden' : '' }}>Presentado</label>
                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked3"
                        style="display: block;"
                        {{ in_array(Auth::user()->role_id, [7]) ? 'hidden disabled' : 'onclick="revisar()"' }}>
                    <label class="form-check-label" for="flexSwitchCheckChecked3" id="flexlabel3"
                        style="display: block;"
                        {{ in_array(Auth::user()->role_id, [7]) ? 'hidden' : '' }}>Presentado</label>
                    <input class="form-check-input" type="checkbox" role="switch" id="notificarCorreo"
                        style="display: block;" {{ in_array(Auth::user()->role_id, [7]) ? 'hidden disabled' : '"' }}>
                    <label class="form-check-label" for="notificarCorreo" id="flexlabel4" style="display: block;"
                        {{ in_array(Auth::user()->role_id, [7]) ? 'hidden' : '' }}>Notificar <i
                            class="fa-solid fa-envelope"></i></label>
                    <input class="form-check-input" type="checkbox" role="switch" id="notificarwhatsapp"
                        style="display: block;" {{ in_array(Auth::user()->role_id, [7]) ? 'hidden disabled' : '"' }}>
                    <label class="form-check-label" for="notificarwhatsapp" id="flexlabel5" style="display: block;"
                        {{ in_array(Auth::user()->role_id, [7]) ? 'hidden' : '' }}>Notificar <i
                            class="fa-brands fa-whatsapp"></i></label>
                    <input class="form-check-input" type="checkbox" role="switch" id="notificarrevisoria"
                        style="display: block;" {{ in_array(Auth::user()->role_id, [7]) ? 'hidden disabled' : '"' }}>
                    <label class="form-check-label" for="notificarrevisoria" id="flexlabel6" style="display: block;"
                        {{ in_array(Auth::user()->role_id, [7]) ? 'hidden' : '' }}>Revisoria <i
                            class="fa-solid fa-envelope"></i></label>
                </div>
            </div>


            {{-- Notificacion whatsapp --}}
            <form action="{{ route('admin.calendario.notificacionwhatsapp') }}" method="POST"
                enctype="multipart/form-data" style="display:none;" id="notificacion-whatsapp">
                @csrf

                <input type="hidden" id="empresa_obligacion" name="empresa">
                <input type="hidden" id="id_obligacion" name="id">
                <input type="hidden" id="nombre_obligacion" name="nombre">

                <div class="form-floating mb-3 mx-3">
                    <input class="form-control fw-normal" id="documento-wsp" type="file" name="notificacion_pdf"
                        accept=".pdf" onchange="validateFiles()">
                </div>

                <div class="bg-transparent border-0 text-center mb-3">
                    <button type="submit" id="enviarWhatsapp" class="btn btn-back border btn-radius" form="notificacion-whatsapp">Enviar
                        Whatsapp</button>
                </div>
            </form>

            <!-- Sección de correo -->
            <form id="formCorreo" action="{{ route('admin.calendariotb.correo') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-body" id="correoDetalles" style="display: none;">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="agregar_correos_checkbox">
                        <label class="form-check-label" for="agregar_correos_checkbox">
                            ¿Agregar correos adicionales?
                        </label>
                    </div>
                    <div class="form-floating mb-3" id="div_correos_adicionales" style="display: none;">
                        <input type="text" class="form-control fw-normal" placeholder="Correos adicionales"
                            id="correos_adicionales" name="correos_adicionales"
                            pattern="^([\w\.-]+@[\w\.-]+\.[a-zA-Z]{2,})(,\s*[\w\.-]+@[\w\.-]+\.[a-zA-Z]{2,})*$"
                            title="Debe ser una lista de correos separados por comas. Ejemplo: correo1@example.com, correo2@example.com">
                        <label for="correos_adicionales" class="fw-normal">Correos adicionales (separados por
                            coma)</label>
                    </div>
                    <div class="form-floating mb-3" id="divobservacion4">
                        <input type="text" class="form-control fw-normal"
                            placeholder="Ingresa una observación o un enlace" id="observacion_correo"
                            name="observacion_correo">
                        <label for="observacion_correo" class="fw-normal">Observación Email</label>
                    </div>
                    <div class="form-floating mb-3" id="divarchivo">
                        <input type="hidden" name="empresa" id="empresa_correo"
                            value="{{ $nombreempresa->razon_social ?? '' }}">
                        <input type="hidden" name="nombre_empresa" id="nombre_empresa" value="">
                        <input type="hidden" name="obligacion" id="obligacion" value="">
                        <input type="hidden" name="fecha_vencimiento" id="fecha_vencimiento" value="">
                        {{-- <input type="hidden" name="observacion_correo" id="observacion_correo" value=""> --}}
                        <input type="hidden" name="id" id="id">
                        <input class="form-control fw-normal" id="formFileMultiple" type="file"
                            name="notificacion_pdf[]" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.zip"
                            onchange="validateFiles()">
                    </div>
                    <div id="fileError" class="alert alert-danger" style="display: none;"></div>
                    <div class="bg-transparent border-0 text-center">
                        <button id="enviarCorreo1" type="button" class="btn btn-back border btn-radius">Enviar
                            Correo</button>
                    </div>
                </div>
            </form>


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
    document.addEventListener('DOMContentLoaded', function() {
        const notificarCorreoCheckbox = document.getElementById('notificarCorreo');
        const notificarwhastappCheckbox = document.getElementById('notificarwhatsapp');
        const correoDetallesDiv = document.getElementById('correoDetalles');
        const enviarCorreoButton = document.getElementById('enviarCorreo1');
        // const observacionCorreo = document.getElementById('observacion_email').value;
        const archivoInput = document.getElementById('formFileMultiple');
        const agregarCorreosCheckbox = document.getElementById('agregar_correos_checkbox');
        const correosAdicionalesDiv = document.getElementById('div_correos_adicionales');
        // Mostrar la sección de correos adicionales si el checkbox está marcado
        agregarCorreosCheckbox.addEventListener('change', function() {
            if (this.checked) {
                correosAdicionalesDiv.style.display = 'block';
            } else {
                correosAdicionalesDiv.style.display = 'none';
            }
        });
        notificarCorreoCheckbox.addEventListener('change', function() {
            if (this.checked) {
                correoDetallesDiv.style.display = 'block';
            } else {
                correoDetallesDiv.style.display = 'none';
            }
        });
        // Evento para restablecer el estado del modal al mostrarlo
        $('#tributarioModal').on('show.bs.modal', function() {
            // Restablecer el estado del checkbox y ocultar la sección de correo
            notificarCorreoCheckbox.checked = false;
            correoDetallesDiv.style.display = 'none';

            // Restablecer los campos de observación y archivo adjunto
            document.getElementById('observacion_email').value = '';
            document.getElementById('formFileMultiple').value = '';
        });

        enviarCorreoButton.addEventListener('click', function() {
            event.preventDefault(); // Detener el envío del formulario
            if (!validarCorreosAdicionales()) {
                event.preventDefault(); // Detener el envío del formulario
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
            // Obtener el texto del modal
            const correosAdicionales = document.getElementById('correos_adicionales').value;
            const descripcionModal = document.getElementById('descripcion_t').innerText;
            // const observacionCorreo = document.getElementById('observacion_email').value;
            // Definir expresiones regulares para extraer los datos deseados
            const obligacionRegex = /Obligación (DIAN|Municipal|Otras entidades): ([^\n]+)/;
            const fechaVencimientoRegex = /Fecha de vencimiento: ([^\n]+)/;

            // Buscar coincidencias en el texto
            const obligacionMatch = descripcionModal.match(obligacionRegex);
            const fechaVencimientoMatch = descripcionModal.match(fechaVencimientoRegex);

            // Extraer los valores encontrados
            const obligacion = obligacionMatch ? obligacionMatch[2].trim() : null;
            const fechaVencimiento = fechaVencimientoMatch ? fechaVencimientoMatch[1].trim() : null;
            // Obtener el elemento del campo de entrada de la empresa
            const empresaElement = document.getElementById('empresa_correo');
            // Inicializar variable para el nombre de la empresa
            let nombreEmpresa;
            // Verificar si el campo de entrada de la empresa tiene un valor
            if (empresaElement && empresaElement.value) {
                nombreEmpresa = empresaElement.value.trim();
            } else {
                // Obtener los elementos del título del modal
                const tituloElementModal = document.getElementById('titulo-tributario');

                // Inicializar variables para los textos
                let tituloModal = tituloElementModal ? tituloElementModal.innerText : '';

                // Verificar la estructura del título
                if (tituloModal.includes('-')) {
                    const partes = tituloModal.split('-');

                    // Si el título tiene tres partes, tomar la del medio
                    if (partes.length === 3) {
                        nombreEmpresa = partes[1].trim();
                    } else {
                        // Si el título tiene dos partes o más, tomar la segunda parte
                        nombreEmpresa = partes[1].trim();
                    }
                }
            }
            var archivoAdjunto;
            if (archivoInput && archivoInput.files && archivoInput.files.length > 0) {
                archivoAdjunto = archivoInput.files[0];
            } else {
                archivoAdjunto = 0;
            }
            // Agregar los datos a los campos ocultos del formulario
            document.getElementById('nombre_empresa').value = nombreEmpresa;
            document.getElementById('fecha_vencimiento').value = fechaVencimiento;
            // document.getElementById('observacion_correo').value = observacionCorreo;
            document.getElementById('obligacion').value = obligacion;
            // Esperar un breve momento antes de enviar el formulario
            setTimeout(function() {
                // Enviar el formulario
                document.getElementById('formCorreo').submit();
            }, 100);

        });
    });

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
</script>
@if (session('messagec'))
    <script>
        Swal.fire({
            title: '{{ session('messagec') }}',
            icon: '{{ session('messagec') == 'Correo enviado con éxito' ? 'success' : 'error' }}',
            confirmButtonText: 'Aceptar'
        });
    </script>
@endif
