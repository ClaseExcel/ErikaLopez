<style>
    .btn-ia {
        color: #003463;
        background-color: #f2f7f8;
        border: 1px solid rgb(14, 103, 117);
    }

    .btn-ia:hover {
        color: #f2f7f8;
        background-color: #003463;
        animation: changeColor 0.5s infinite alternate;
    }

    @keyframes changeColor {
        0% {
            color: #a4f3ff;
        }

        100% {
            color: #FABF6E;
        }
    }

    .btn-h:hover,
    .btn-h:focus {
        color: #fff !important;
    }

    .alert-guardar {
        color: #fff !important;
        background-color: #3fbdee !important;
        border-color: #3fbdee !important;
    }

    .alert-guardar:hover {
        color: #fff !important;
        background-color: #1b98ca !important;
        border-color: #1b98ca !important;
    }

    .alert-cargar {
        color: #e9fffa !important;
        background-color: #626973 !important;
        border-color: #626973 !important;

    }

    .alert-cargar:hover {
        color: #fff !important;
        background-color: #454f5e !important;
        border-color: #454f5e !important;

    }
</style>
<div class="tab-content bg-white p-4 border border-left rounded-start rounded-end" id="myTabContent" style="min-height: 267px">
    <div class="tab-pane fade show active" id="inicial" role="tabpanel" aria-labelledby="inicial-tab">
        <div class="row d-flex justify-content-center" id="desempeno-financiero-div">
        </div>
    </div>

    {{-- indicadores financieros --}}
    <div class="tab-pane fade" id="final" role="tabpanel" aria-labelledby="final-tab">
        <div class="row d-flex justify-content-center" id="indicadores-financieros-div">
        </div>
    </div>

    {{-- iva --}}
    <div class="tab-pane fade" id="impuestos-dian" role="tabpanel" aria-labelledby="impuestos-dian-tab">
        <div class="row d-flex justify-content-center" id="impuesto-iva-div">
        </div>
    </div>
    {{-- impuesto al consumo --}}
    <div class="tab-pane fade" id="impuesto-consumo" role="tabpanel" aria-labelledby="impuesto-consumo-tab">
        <div class="row d-flex justify-content-center">
            <div class="col-12 col-sm-6 col-xl-3">
                <div id="impuesto-consumo-generado"></div>
            </div>
        </div>
    </div>

    {{-- analisis financiero ia --}}
    <div class="tab-pane fade" id="analisis-financiero-ia" role="tabpanel" aria-labelledby="analisis-financiero-ia-tab">
        <div class="row ">
            <div class="col-12" id="ia-respuesta"></div>
        </div>
    </div>

    {{-- informacion extra pdf --}}
    <div class="tab-pane fade" id="informacion-extra" role="tabpanel" aria-labelledby="informacion-extra-tab">

        <div class="row bg-light py-3 px-2 mx-0 border border-bottom-0 accordion-header">
            <div class="col-12 my-2">Aquí puede agregar información adicional opcional para el PDF.</div>
            <div class="col-12 d-flex justify-content-between my-3">
                {{-- boton para guardar informacion extra --}}
                <button class="btn btn-sm alert-guardar btn-radius mr-2 btn-h border-0 px-4" onclick="saveInformacionExtra()"><i
                        class="fas fa-save"></i> Guardar información adicional</button>
                {{-- boton para cargar informacion extra --}}
                <button class="btn btn-sm alert-cargar btn-radius btn-h border-0 px-4" onclick="loadInformacionExtra()"><i
                        class="fas fa-download"></i>
                    Cargar información adicional</button>
            </div>
        </div>

        @php
            $sections = [
                ['id' => 'dian', 'title' => 'DIAN'],
                ['id' => 'ica', 'title' => 'Proyección ICA'],
                ['id' => 'renta', 'title' => 'Proyección Renta'],
                ['id' => 'simple', 'title' => 'Proyección Régimen Simple de Tributación'],
                ['id' => 'cuentas', 'title' => 'Cuentas bancarias'],
                ['id' => 'observaciones', 'title' => 'Observaciones', 'no_image' => true],
                ['id' => 'contingencia', 'title' => 'Contingencia', 'no_image' => true],
            ];
        @endphp

        <!-- INICIO: Contenedor del acordeón -->
        <div class="accordion" id="accordionPdf">
            @foreach ($sections as $section)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading{{ ucfirst($section['id']) }}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse{{ ucfirst($section['id']) }}" aria-expanded="false"
                            aria-controls="collapse{{ ucfirst($section['id']) }}">
                            <i class="far fa-plus-square"></i> &nbsp; {{ $section['title'] }}
                        </button>
                    </h2>
                    <div id="collapse{{ ucfirst($section['id']) }}" class="accordion-collapse collapse"
                        aria-labelledby="heading{{ ucfirst($section['id']) }}" data-bs-parent="#accordionPdf">
                        <div class="accordion-body">
                            @if (empty($section['no_image']))
                                <div id="imagen-form-{{ $section['id'] }}" style="display:none;" class="bg-light rounded border">
                                    <button type="button" class="btn btn-sm btn-outline-danger m-3" onclick="hideImageForm('{{ $section['id'] }}');">
                                        <i class="fas fa-times"></i> Cancelar adjunto
                                    </button>
                                    <img id="imagen-preview-{{ $section['id'] }}" src="#" style="display:none;max-height:600px"
                                        class="mx-auto"></img>

                                    <div class="row px-3">
                                        <div class="col-12 col-xl-6">
                                            <div class="form-group mt-3">
                                                <label for="imagen-{{ $section['id'] }}" class="fw-normal mb-1">Imagen<small
                                                        class="font-italic">(Formatos permitidos: jpeg, jpg y png)</small></label>
                                                <input type="file" class="form-control"
                                                    id="imagen-{{ $section['id'] }}"name="imagen-{{ $section['id'] }}"
                                                    accept="image/jpeg, image/png, image/jpg">
                                            </div>
                                        </div>
                                        <div class="col-12 col-xl-6">
                                            <div class="form-group mt-3">
                                                <label for="texto-{{ $section['id'] }}" class="fw-normal mb-1">Descripción corta de la imagen</label>
                                                <input type="text" class="form-control" id="texto-{{ $section['id'] }}"
                                                    name="texto-{{ $section['id'] }}" maxlength="200">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-secondary mb-3 btn-adjuntar-{{ $section['id'] }}"
                                    onclick="document.getElementById('imagen-form-{{ $section['id'] }}').style.display='block'; this.style.display='none';">
                                    <i class="fas fa-paperclip"></i> Adjuntar imagen
                                </button>

                                <script>
                                    function hideImageForm(sectionId) {
                                        document.getElementById('imagen-form-' + sectionId).style.display = 'none';
                                        document.querySelector('.btn-adjuntar-' + sectionId).style.display = 'block';
                                        document.getElementById('imagen-' + sectionId).value = '';
                                        document.getElementById('texto-' + sectionId).value = '';
                                        document.getElementById('imagen-preview-' + sectionId).src = '#';
                                        document.getElementById('imagen-preview-' + sectionId).style.display = 'none';
                                    }
                                </script>
                            @endif
                            <div class="form-group mt-3">
                                <div class="d-flex">
                                    <label for="observaciones-finales-{{ $section['id'] }}" class="fw-normal mb-1">
                                        Observaciones finales
                                        <small class="font-italic">(Max. 500 caracteres)</small>
                                    </label>
                                </div>
                                <textarea class="form-control" id="observaciones-finales-{{ $section['id'] }}" name="observaciones-finales-{{ $section['id'] }}" rows="3"
                                    maxlength="500" style="height: 100px"></textarea>
                            </div>
                            <div class="row" style="display:block;">
                                <div class="col">
                                    <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Mejorar el texto escrito."
                                        class="btn mb-2 btn-sm btn-ia shadow-none btn-radius border px-3 ml-auto btn-enhance-text"
                                        onclick="enhanceText(document.getElementById('observaciones-finales-{{ $section['id'] }}').value, 'observaciones-finales-{{ $section['id'] }}');">
                                        <i class="fas fa-hand-sparkles"></i> Mejorar texto con IA
                                    </a>
                                    <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Volver al texto anterior."
                                        class="btn mb-2 btn-sm btn-ia shadow-none btn-radius btn-volver-texto border px-3 ml-2 disabled"
                                        onclick="volverTextoAnterior('observaciones-finales-{{ $section['id'] }}');">
                                        <i class="fas fa-undo-alt"></i> Volver al texto anterior
                                    </a>
                                </div>
                            </div>
                            @if (empty($section['no_image']))
                                <script>
                                    // Preview de la imagen y limpieza al hacer clic en la imagen
                                    document.getElementById('imagen-preview-{{ $section['id'] }}').addEventListener('click', function() {
                                        document.getElementById('imagen-preview-{{ $section['id'] }}').src = '#';
                                        document.getElementById('imagen-preview-{{ $section['id'] }}').style.display = 'none';
                                        document.getElementById('imagen-{{ $section['id'] }}').value = '';
                                        document.getElementById('texto-{{ $section['id'] }}').value = '';
                                    });

                                    let input{{ ucfirst($section['id']) }} = document.getElementById('imagen-{{ $section['id'] }}');

                                    function readURL{{ ucfirst($section['id']) }}(input) {
                                        if (input.files && input.files[0]) {
                                            var reader = new FileReader();
                                            reader.onload = function(e) {
                                                document.getElementById('imagen-preview-{{ $section['id'] }}').src = e.target.result;
                                                document.getElementById('imagen-preview-{{ $section['id'] }}').style.display = 'block';
                                            }
                                            reader.readAsDataURL(input.files[0]);
                                        }
                                    }

                                    input{{ ucfirst($section['id']) }}.addEventListener('change', function() {
                                        readURL{{ ucfirst($section['id']) }}(this);
                                    });
                                </script>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <!-- FIN: Contenedor del acordeón -->

    </div>
</div>

<script>
    let textoInicial = '';

    function enhanceText(text, idTextarea) {

        textoInicial = text.trim();

        if (text.length == 0) {
            //sweealert
            Swal.fire({
                icon: 'info',
                title: '¡Atención!',
                text: 'Escribe algo para mejorar con IA.',
                showConfirmButton: false,
            });
            return;
        }

        // Save the current scroll position
        // let scrollPosition = document.getElementById(idTextarea).scrollTop;

        //alerta de espera 
        Swal.fire({
            title: 'Procesando...',
            html: 'Espere un momento por favor.',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading()
            },
        });

        //deshabilitar botones con la clase btn-enhance-text
        let buttons = document.getElementsByClassName('btn-enhance-text');
        let buttonsVolver = document.getElementsByClassName('btn-volver-texto');
        for (let i = 0; i < buttons.length; i++) {
            buttons[i].classList.add('disabled');
            buttonsVolver[i].classList.add('disabled');
        }

        $.ajax({
            url: "{{ route('admin.informesgerenciales.enhanceText') }}",
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                text: text
            },
            success: function(response) {

                Swal.close();

                document.getElementById(idTextarea).value = response.enhancedText;

                // Animate the enhanced text letter by letter
                let textarea = $('#' + idTextarea);
                let enhancedText = response.enhancedText;
                textarea.val('');
                let i = 0;
                let interval = setInterval(function() {
                    textarea.val(textarea.val() + enhancedText.charAt(i));
                    i++;
                    if (i >= enhancedText.length) {
                        clearInterval(interval);
                        for (let i = 0; i < buttons.length; i++) {
                            buttons[i].classList.remove('disabled');
                            buttonsVolver[i].classList.remove('disabled');
                        }
                    }
                }, 10);

                // Restore the scroll position
                // document.getElementById(idTextarea).scrollTop = scrollPosition;

            }
        });
    }

    function volverTextoAnterior(idTextarea) {
        document.getElementById(idTextarea).value = textoInicial;
        textoInicial = '';

        // Restore the scroll position
        // document.getElementById(idTextarea).scrollTop = scrollPosition;

        //deshabilitar botones con la clase btn-enhance-text
        let buttonsVolver = document.getElementsByClassName('btn-volver-texto');
        for (let i = 0; i < buttonsVolver.length; i++) {
            buttonsVolver[i].classList.add('disabled');
        }
    }

    function collectInformacionExtra() {
        // Definir las secciones y sus campos asociados
        const sections = [{
                id: 'dian'
            },
            {
                id: 'ica'
            },
            {
                id: 'renta'
            },
            {
                id: 'simple'
            },
            {
                id: 'cuentas'
            }
        ];

        // Crear un objeto FormData para enviar los datos
        let formData = new FormData();

        // Crear un array para almacenar los datos de cada sección
        let seccionesArray = [];

        // Recorrer las secciones y agregar sus datos al array
        sections.forEach(section => {
            const imagen = document.getElementById(`imagen-${section.id}`)?.files?.[0] || null;
            const textoImagen = document.getElementById(`texto-${section.id}`)?.value?.trim() || '';
            const valor = document.getElementById(`observaciones-finales-${section.id}`)?.value?.trim() || '';

            // isSeccion solo true si los 3 campos están llenos
            const isSeccion = !!(imagen || textoImagen || valor);

            seccionesArray.push({
                seccion: section.id,
                imagen: imagen,
                textoImagen: textoImagen,
                valor: valor,
                isSeccion: isSeccion
            });
        });

        // Agregar campos adicionales (observaciones y contingencia)
        const observaciones = document.getElementById('observaciones-finales-observaciones')?.value?.trim() || '';
        const contingencia = document.getElementById('observaciones-finales-contingencia')?.value?.trim() || '';

        seccionesArray.push({
            seccion: 'observaciones',
            valor: observaciones,
            isSeccion: !!observaciones
        });
        seccionesArray.push({
            seccion: 'contingencia',
            valor: contingencia,
            isSeccion: !!contingencia
        });

        // Verificar si hay al menos un campo con información
        const hayDatos = seccionesArray.some(sec => sec.isSeccion);

        if (!hayDatos) {
            Swal.fire({
                icon: 'info',
                title: 'Sin datos',
                text: 'No hay información para guardar.',
                showConfirmButton: true,
            });
            return null;
        }

        // Agregar el array al FormData como JSON
        formData.append('secciones', JSON.stringify(seccionesArray));

        // Adjuntar imágenes individualmente al FormData (no se pueden enviar en JSON)
        sections.forEach((section, idx) => {
            const imagen = document.getElementById(`imagen-${section.id}`)?.files?.[0];
            if (imagen) {
                formData.append(`imagen_${section.id}`, imagen);
            }
        });
        return formData;
    }

    function saveInformacionExtra() {

        //sweealert de confirmacion
        Swal.fire({
            title: '¿Guardar información extra?',
            text: "Si ya existe información guardada, será reemplazada. ¿Desea continuar?",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#00c89b',
            cancelButtonColor: '#003463',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {

                let formData = collectInformacionExtra();
                let nit = document.getElementById('ntmpid').innerText;
                formData.append('id_empresa', nit);
                let fechaInicio = document.getElementById('fi').innerText;
                formData.append('fecha_inicial', fechaInicio);
                let fechaFin = document.getElementById('ff').innerText;
                formData.append('fecha_final', fechaFin);

                $.ajax({
                    url: "{{ route('admin.informesgerenciales.guardar-historial') }}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: formData, // enviar directamente el FormData
                    processData: false,
                    contentType: false,
                    success: function(response) {

                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 2000
                        });

                    },
                    error: function(xhr, status, error) {
                        console.error('Error al guardar la información extra:', error);
                    }
                });

            }
        });
    }

    function loadInformacionExtra(isAdviced = true) {
        // Función para cargar la información extra desde el servidor y poblar los campos
        function cargarDatos() {
            Swal.fire({
                title: 'Cargando información extra...',
                html: 'Espere un momento por favor.',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading()
                },
            });

            let nit = document.getElementById('ntmpid').innerText;
            let fechaInicio = document.getElementById('fi').innerText;
            let fechaFin = document.getElementById('ff').innerText;

            $.ajax({
                url: "{{ route('admin.informesgerenciales.cargar-historial') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    id_empresa: nit,
                    fecha_inicial: fechaInicio,
                    fecha_final: fechaFin
                },
                success: function(response) {
                    Swal.close();
                    if (response.historial && Array.isArray(response.historial) && response.historial.length > 0) {
                        response.historial.forEach(section => {
                            // Observaciones finales
                            const obsInput = document.getElementById(`observaciones-finales-${section.seccion}`);
                            if (obsInput) {
                                obsInput.value = section.valor || '';
                            }
                            // Texto de la imagen
                            const textoInput = document.getElementById(`texto-${section.seccion}`);
                            if (textoInput) {
                                textoInput.value = section.textoImagen || '';
                            }
                            // Imagen
                            if (section.imagen) {
                                const preview = document.getElementById(`imagen-preview-${section.seccion}`);
                                const inputFile = document.getElementById(`imagen-${section.seccion}`);
                                if (preview) {
                                    let imageUrl = section.imagen;
                                    if (!/^data:image\/|^https?:\/\//.test(imageUrl)) {
                                        imageUrl = "{{ asset('storage/images/informe_historial') }}/" + imageUrl;
                                    }
                                    preview.src = imageUrl;
                                    preview.style.display = 'block';

                                    if (inputFile) {
                                        fetch(imageUrl)
                                            .then(res => res.blob())
                                            .then(blob => {
                                                const fileName = imageUrl.split('/').pop();
                                                const file = new File([blob], fileName, {
                                                    type: blob.type
                                                });
                                                const dataTransfer = new DataTransfer();
                                                dataTransfer.items.add(file);
                                                inputFile.files = dataTransfer.files;
                                            })
                                            .catch(() => {
                                                // Si falla, solo muestra el preview
                                            });
                                    }
                                }
                                const formDiv = document.getElementById(`imagen-form-${section.seccion}`);
                                if (formDiv) {
                                    formDiv.style.display = 'block';
                                }
                                const btnAdjuntar = document.querySelector(`.btn-adjuntar-${section.seccion}`);
                                if (btnAdjuntar) {
                                    btnAdjuntar.style.display = 'none';
                                }
                            }
                        });


                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: 'Información adicional cargada correctamente.',
                            showConfirmButton: true,
                            confirmButtonColor: '#00c89b',
                        });

                    } else {
                        Swal.fire({
                            icon: 'info',
                            title: 'Sin historial',
                            text: 'No se encontró información adicional guardada para este periodo.',
                            showConfirmButton: true,
                            confirmButtonColor: '#00c89b',
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.close();
                }
            });
        }

        if (isAdviced) {
            Swal.fire({
                title: 'Buscando información adicional para este periodo.',
                text: "Esto reemplazará cualquier información que haya ingresado actualmente. ¿Desea continuar?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#00c89b',
                cancelButtonColor: '#003463',
                confirmButtonText: 'Sí, cargar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    cargarDatos();
                }
            });
        } else {
            cargarDatos();
        }
    }
</script>
