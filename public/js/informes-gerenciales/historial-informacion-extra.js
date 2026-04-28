function collectInformacionExtra() {
    // Definir las secciones y sus campos asociados
    const sections = [
        { id: "dian" },
        { id: "ica" },
        { id: "renta" },
        { id: "simple" },
        { id: "cuentas" },
    ];

    // Crear un objeto FormData para enviar los datos
    let formData = new FormData();

    // Crear un array para almacenar los datos de cada sección
    let seccionesArray = [];

    // Recorrer las secciones y agregar sus datos al array
    sections.forEach((section) => {
        const imagen =
            document.getElementById(`imagen-${section.id}`)?.files?.[0] || null;
        const textoImagen =
            document.getElementById(`texto-${section.id}`)?.value?.trim() || "";
        const valor =
            document
                .getElementById(`observaciones-finales-${section.id}`)
                ?.value?.trim() || "";

        // isSeccion solo true si los 3 campos están llenos
        const isSeccion = !!(imagen || textoImagen || valor);

        seccionesArray.push({
            seccion: section.id,
            imagen: imagen,
            textoImagen: textoImagen,
            valor: valor,
            isSeccion: isSeccion,
        });
    });

    // Agregar campos adicionales (observaciones y contingencia)
    const observaciones =
        document
            .getElementById("observaciones-finales-observaciones")
            ?.value?.trim() || "";
    const contingencia =
        document
            .getElementById("observaciones-finales-contingencia")
            ?.value?.trim() || "";

    seccionesArray.push({
        seccion: "observaciones",
        valor: observaciones,
        isSeccion: !!observaciones,
    });
    seccionesArray.push({
        seccion: "contingencia",
        valor: contingencia,
        isSeccion: !!contingencia,
    });

    // Verificar si hay al menos un campo con información
    const hayDatos = seccionesArray.some((sec) => sec.isSeccion);

    if (!hayDatos) {
        Swal.fire({
            icon: "info",
            title: "Sin datos",
            text: "No hay información para guardar.",
            showConfirmButton: true,
        });
        return null;
    }

    // Agregar el array al FormData como JSON
    formData.append("secciones", JSON.stringify(seccionesArray));

    // Adjuntar imágenes individualmente al FormData (no se pueden enviar en JSON)
    sections.forEach((section, idx) => {
        const imagen = document.getElementById(`imagen-${section.id}`)
            ?.files?.[0];
        if (imagen) {
            formData.append(`imagen_${section.id}`, imagen);
        }
    });
    return formData;
}

function saveInformacionExtra() {
    //sweealert de confirmacion
    Swal.fire({
        title: "¿Guardar información extra?",
        text: "Importante: si ya existe información guardada, será reemplazada. ¿Desea continuar?",
        icon: "info",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, guardar",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            let formData = collectInformacionExtra();
            let nit = document.getElementById("ntmpid").innerText;
            formData.append("id_empresa", nit);
            let fechaInicio = document.getElementById("fi").innerText;
            formData.append("fecha_inicial", fechaInicio);
            let fechaFin = document.getElementById("ff").innerText;
            formData.append("fecha_final", fechaFin);

            // Puedes usar AJAX para enviar los datos al servidor si es necesario
            $.ajax({
                url: "{{ route('admin.informesgerenciales.guardar-historial') }}",
                type: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                },
                data: formData, // enviar directamente el FormData
                processData: false, // evitar que jQuery procese los datos
                contentType: false, // evitar que jQuery establezca el content-type
                success: function (response) {
                    console.log("Información extra guardada correctamente.");
                },
                error: function (xhr, status, error) {
                    console.error(
                        "Error al guardar la información extra:",
                        error
                    );
                },
            });
        }
    });
}

function loadInformacionExtra() {
    //confirmacion de carga
    Swal.fire({
        title: "¿Cargar información extra guardada?",
        text: "Importante: esto reemplazará cualquier información que haya ingresado actualmente. ¿Desea continuar?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, cargar",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        //advertencia de carga
        Swal.fire({
            title: "Cargando información extra...",
            html: "Espere un momento por favor.",
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            },
        });

        let nit = document.getElementById("ntmpid").innerText;
        let fechaInicio = document.getElementById("fi").innerText;
        let fechaFin = document.getElementById("ff").innerText;
        $.ajax({
            url: "{{ route('admin.informesgerenciales.cargar-historial') }}",
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
            },
            data: {
                id_empresa: nit,
                fecha_inicial: fechaInicio,
                fecha_final: fechaFin,
            },
            success: function (response) {
                Swal.close();
                if (
                    response.historial &&
                    Array.isArray(response.historial) &&
                    response.historial.length > 0
                ) {
                    response.historial.forEach((section) => {
                        // Observaciones finales
                        const obsInput = document.getElementById(
                            `observaciones-finales-${section.seccion}`
                        );
                        if (obsInput) {
                            obsInput.value = section.valor || "";
                        }
                        // Texto de la imagen
                        const textoInput = document.getElementById(
                            `texto-${section.seccion}`
                        );
                        if (textoInput) {
                            textoInput.value = section.textoImagen || "";
                        }
                        // Imagen
                        if (section.imagen) {
                            const preview = document.getElementById(
                                `imagen-preview-${section.seccion}`
                            );
                            const inputFile = document.getElementById(
                                `imagen-${section.seccion}`
                            );
                            if (preview && section.imagen) {
                                // Si la imagen es una ruta relativa, ajusta la URL base según tu configuración
                                let imageUrl = section.imagen;
                                if (
                                    !/^data:image\/|^https?:\/\//.test(imageUrl)
                                ) {
                                    imageUrl =
                                        "{{ asset('storage/images/informe_historial') }}/" +
                                        imageUrl;
                                }
                                preview.src = imageUrl;
                                preview.style.display = "block";

                                // Si el input file existe, crea un objeto File y asígnalo (solo posible con DataTransfer)
                                if (inputFile) {
                                    fetch(imageUrl)
                                        .then((res) => res.blob())
                                        .then((blob) => {
                                            const fileName = imageUrl
                                                .split("/")
                                                .pop();
                                            const file = new File(
                                                [blob],
                                                fileName,
                                                {
                                                    type: blob.type,
                                                }
                                            );
                                            const dataTransfer =
                                                new DataTransfer();
                                            dataTransfer.items.add(file);
                                            inputFile.files =
                                                dataTransfer.files;
                                        })
                                        .catch(() => {
                                            // Si falla, solo muestra el preview
                                        });
                                }
                            }
                            const formDiv = document.getElementById(
                                `imagen-form-${section.seccion}`
                            );
                            if (formDiv) {
                                formDiv.style.display = "block";
                            }
                            const btnAdjuntar = document.querySelector(
                                `.btn-adjuntar-${section.seccion}`
                            );
                            if (btnAdjuntar) {
                                btnAdjuntar.style.display = "none";
                            }
                        }
                    });
                } else {
                    Swal.fire({
                        icon: "info",
                        title: "Sin historial",
                        text: "No se encontró información adicional guardada para este periodo.",
                        showConfirmButton: true,
                    });
                    console.error(
                        "No se encontró información adicional guardada."
                    );
                }
            },
            error: function (xhr, status, error) {
                Swal.close();
                console.error(
                    "Error al cargar la información adicional:",
                    error
                );
            },
        });
    });
}
