$(document).ready(function () {
    // Inicializar el campo select múltiple con Select2
    $("#obligaciones").select2({
        dropdownCssClass: 'custom-dropdown', // Clase CSS personalizada para el menú desplegable
        containerCssClass: 'custom-container', // Clase CSS personalizada para el contenedor del select
        closeOnSelect: false // Evitar que se cierre al seleccionar
    });
    // Agregar funcionalidad al botón "Seleccionar Todo"
    $("#selectAllButton").on("click", function () {
        $("#obligaciones").find("option").prop("selected", true);
        $("#obligaciones").trigger("change"); // Actualizar el select2 después de la selección
    });
    // Agregar funcionalidad al botón "Deseleccionar Todo"
    $("#deselectAllButton").on("click", function () {
        $("#obligaciones").find("option").prop("selected", false);
        $("#obligaciones").trigger("change"); // Actualizar el select2 después de la desselección
    });
    // Inicializar el campo select múltiple con Select2 de las obligaciones municipales
    $("#obligacionMunicipal").select2({
        dropdownCssClass: 'custom-dropdown', // Clase CSS personalizada para el menú desplegable
        containerCssClass: 'custom-container', // Clase CSS personalizada para el contenedor del select
        closeOnSelect: false // Evitar que se cierre al seleccionar
    });
    // Agregar funcionalidad al botón "Seleccionar Todo"
    $("#selectAllButton3").on("click", function () {
        $("#obligacionMunicipal").find("option").prop("selected", true);
        $("#obligacionMunicipal").trigger("change"); // Actualizar el select2 después de la selección
    });
    // Agregar funcionalidad al botón "Deseleccionar Todo"
    $("#deselectAllButton3").on("click", function () {
        $("#obligacionMunicipal").find("option").prop("selected", false);
        $("#obligacionMunicipal").trigger("change"); // Actualizar el select2 después de la desselección
    });

    // Inicializar el campo select múltiple con Select2 de las otras entidades
    $("#OtrasEntidades").select2({
        dropdownCssClass: 'custom-dropdown', // Clase CSS personalizada para el menú desplegable
        containerCssClass: 'custom-container', // Clase CSS personalizada para el contenedor del select
        closeOnSelect: false // Evitar que se cierre al seleccionar
    });
    // Agregar funcionalidad al botón "Seleccionar Todo"
    $("#selectAllButton4").on("click", function () {
        $("#OtrasEntidades").find("option").prop("selected", true);
        $("#OtrasEntidades").trigger("change"); // Actualizar el select2 después de la selección
    });
    // Agregar funcionalidad al botón "Deseleccionar Todo"
    $("#deselectAllButton4").on("click", function () {
        $("#OtrasEntidades").find("option").prop("selected", false);
        $("#OtrasEntidades").trigger("change"); // Actualizar el select2 después de la desselección
    });

    $("#empleados").select2({
        dropdownCssClass: 'custom-dropdown', // Clase CSS personalizada para el menú desplegable
        containerCssClass: 'custom-container', // Clase CSS personalizada para el contenedor del select
        closeOnSelect: false // Evitar que se cierre al seleccionar
    });
    // Agregar funcionalidad al botón "Seleccionar Todo"
    $("#selectAllButtonempleados").on("click", function () {
        $("#empleados").find("option").prop("selected", true);
        $("#empleados").trigger("change"); // Actualizar el select2 después de la selección
    });
    // Agregar funcionalidad al botón "Deseleccionar Todo"
    $("#deselectAllButtonempleados").on("click", function () {
        $("#empleados").find("option").prop("selected", false);
        $("#empleados").trigger("change"); // Actualizar el select2 después de la desselección
    });

    $("#ciiu").select2({
        dropdownCssClass: 'custom-dropdown', // Clase CSS personalizada para el menú desplegable
        containerCssClass: 'custom-container', // Clase CSS personalizada para el contenedor del select
        closeOnSelect: false // Evitar que se cierre al seleccionar
    });
    // Agregar funcionalidad al botón "Seleccionar Todo"
    $("#selectAllButtonCiiu").on("click", function () {
        $("#ciiu").find("option").prop("selected", true);
        $("#ciiu").trigger("change"); // Actualizar el select2 después de la selección
    });
    // Agregar funcionalidad al botón "Deseleccionar Todo"
    $("#deselectAllButtonCiiu").on("click", function () {
        $("#ciiu").find("option").prop("selected", false);
        $("#ciiu").trigger("change"); // Actualizar el select2 después de la desselección
    });

    $("#camara_comercio_establecimientos").select2({
        dropdownCssClass: 'custom-dropdown', // Clase CSS personalizada para el menú desplegable
        containerCssClass: 'custom-container', // Clase CSS personalizada para el contenedor del select
        closeOnSelect: false // Evitar que se cierre al seleccionar
    });
    // Agregar funcionalidad al botón "Seleccionar Todo"
    $("#selectAllButtonCamaraComercio").on("click", function () {
        $("#camara_comercio_establecimientos").find("option").prop("selected", true);
        $("#camara_comercio_establecimientos").trigger("change"); // Actualizar el select2 después de la selección
    });
    // Agregar funcionalidad al botón "Deseleccionar Todo"
    $("#deselectAllButtonCamaraComercio").on("click", function () {
        $("#camara_comercio_establecimientos").find("option").prop("selected", false);
        $("#camara_comercio_establecimientos").trigger("change"); // Actualizar el select2 después de la desselección
    });
});

function fillCiudadSelect(selectedDepartamento) {
    const ciudadSelect = $("#ciudad_id");

    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        url: `/admin/empresas/municipio`,
        type: "POST",
        data: { departamento: selectedDepartamento },
        success: function (result) {
            ciudadSelect.append(new Option("Seleccione una opción", "null"));

            $.each(result, function (index, item) {
                ciudadSelect.append(new Option(item.municipio, item.municipio));
            });

            ciudadSelect.select2();
            ciudadSelect.prop("disabled", false);
        },
    });
}

// Llama a la función cuando el documento se ha cargado
// document.addEventListener("DOMContentLoaded", function () {
//     const departamento = document.querySelector("#departamentos");
//     const validareditar = document.querySelector("#validareditar");
//     $("#departamentos_y_ciudades").val(JSON.stringify(obligacionesMunicipalesData));
//     // Llama a la función al cargar la página para llenar el select de ciudades
//     if (validareditar.value === '1') {
//         fillCiudadSelect(departamento.value);
//     } else {
//         $("#departamentos_y_ciudades").val("");
//     }


//     departamento.addEventListener("change", () => {
//         let depar = departamento.value;
//         const ciudadSelect = $("#ciudad_id");
//         ciudadSelect.empty();
//         // Llama a la función cuando cambia el departamento
//         fillCiudadSelect(depar);
//     });

//     const departamentoSelect = $("#departamentos");
//     const ciudadSelect = $("#ciudad_id");
//     const departamentoMunicipioTable = $("#departamentoMunicipioTable tbody");
//     let departamentosCiudadesData = obligacionesMunicipalesData || [];

//     // Función para actualizar la tabla con los datos existentes
//     function actualizarTabla() {
//         departamentoMunicipioTable.empty();
//         departamentosCiudadesData.forEach(item => {
//             departamentoMunicipioTable.append(`<tr><td>${item['departamentos']}</td><td>${item['ciudad'].join(', ')}</td></tr>`);
//         });
//     }

//     // Actualiza la tabla al cargar la página
//     actualizarTabla();

//     $("#guardarDepartamentoMunicipio").on("click", function () {
//         const departamentoSeleccionado = departamentoSelect.val();
//         const ciudadesSeleccionadas = ciudadSelect.val();

//         if (departamentoSeleccionado && ciudadesSeleccionadas) {
//             // Buscar si ya existe un registro con el mismo departamento
//             const registroExistente = departamentosCiudadesData.find(item => item.departamentos === departamentoSeleccionado);

//             if (registroExistente) {
//                 // Si existe, combina los municipios seleccionados con el registro existente
//                 registroExistente.ciudad = [...new Set([...registroExistente.ciudad, ...ciudadesSeleccionadas])];
//             } else {
//                 // Si no existe, agrega un nuevo registro
//                 departamentosCiudadesData.push({ departamentos: departamentoSeleccionado, ciudad: ciudadesSeleccionadas });
//             }

//             // Actualiza el campo oculto con los datos en formato JSON
//             $("#departamentos_y_ciudades").val(JSON.stringify(departamentosCiudadesData));

//             // Actualiza la tabla
//             actualizarTabla();

//             // Limpia las selecciones en los selects
//             departamentoSelect.val(null).trigger('change');
//             ciudadSelect.val(null).trigger('change');
//         }
//     });

//     $("#eliminarDepartamentoMunicipio").on("click", function () {
//         const departamentoSeleccionado = departamentoSelect.val();
//         const ciudadesSeleccionadas = ciudadSelect.val();

//         if (departamentoSeleccionado && ciudadesSeleccionadas) {
//             // Buscar si existe un registro con el mismo departamento
//             const registroExistenteIndex = departamentosCiudadesData.findIndex(item => item.departamentos === departamentoSeleccionado);

//             if (registroExistenteIndex !== -1) {
//                 const registroExistente = departamentosCiudadesData[registroExistenteIndex];

//                 // Verificar si el departamento tiene varios municipios
//                 if (registroExistente.ciudad.length > 1) {
//                     // Eliminar solo el municipio seleccionado
//                     registroExistente.ciudad = registroExistente.ciudad.filter(ciudad => !ciudadesSeleccionadas.includes(ciudad));
//                 } else {
//                     // Si solo hay un municipio, eliminar el departamento completo
//                     departamentosCiudadesData.splice(registroExistenteIndex, 1);
//                 }
//             }

//             // Actualiza el campo oculto con los datos en formato JSON o cadena vacía si el array está vacío
//             $("#departamentos_y_ciudades").val(departamentosCiudadesData.length > 0 ? JSON.stringify(departamentosCiudadesData) : '');

//             // Actualiza la tabla
//             actualizarTabla();

//             // Limpia las selecciones en los selects
//             departamentoSelect.val(null).trigger('change');
//             ciudadSelect.val(null).trigger('change');
//         }
//     });

// });


let isNavigatingAway = false;

window.addEventListener("beforeunload", function (event) {
    if (isNavigatingAway == true) {
        localStorage.clear(); // Elimina el localStorage solo si no se está navegando
    }
});

document.querySelectorAll('a').forEach(function (element) {
    element.addEventListener('click', function () {
        isNavigatingAway = true; // Marca que el usuario está navegando
    });
});


if (routeCreate) {
    const formulario = document.getElementById('crearempresa');

    formulario.addEventListener('submit', function (event) {
        event.preventDefault(); // Evitar el envío normal del formulario
        var formData = new FormData(formulario);// Crear un objeto FormData

        // Enviar los datos usando fetch
        $.ajax({
            url: $(this).attr('action'), // URL del endpoint
            type: 'POST',
            data: formData,
            contentType: false, // No establecer el tipo de contenido
            processData: false, // No procesar los datos
            success: function (response) {
                console.log('Éxito:', response);


                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }, willClose: () => {
                        isNavigatingAway = true;
                        window.location.href = routeIndex;
                    }
                });
                Toast.fire({
                    icon: "success",
                    title: "La empresa se ha creado exitosamente."
                });

            },
            error: function (jqXHR, textStatus, errorThrown) {

                $('#NIT').text('');
                $('#razon_social').text('');
                $('#correo_electronico').text('');
                $('#numero_contacto').text('');
                $('#notifica_calendario').text('');
                $('#nombre_contacto').text('');
                $('#telefono_contacto').text('');
                $('#direccion_fisica').text('');
                $('#frecuencia_id').text('');
                $('#tipocliente').text('');
                $('#rut').text('');
                $('#empleado').text('');
                $('#obligaciones_error').text('');
                $('#camaracomercio_id').text('');
                $('#codigo_obligacionmunicipal').text('');
                $('#otras_entidades').text('');

                if (jqXHR.status === 422) { // Verifica si es un error de validación
                    const errors = jqXHR.responseJSON.errors; // Captura los errores

                    // Muestra el mensaje de error en el elemento correspondiente
                    if (errors.NIT) {
                        $('#NIT').text(errors.NIT[0]);
                    }
                    if (errors.razon_social) {
                        $('#razon_social').text(errors.razon_social[0]);
                    }
                    if (errors.correo_electronico) {
                        $('#correo_electronico').text(errors.correo_electronico[0]);
                    }
                    if (errors.numero_contacto) {
                        $('#numero_contacto').text(errors.numero_contacto[0]);
                    }
                    if (errors.notifica_calendario) {
                        $('#notifica_calendario').text(errors.notifica_calendario[0]);
                    }
                    if (errors.nombre_contacto) {
                        $('#nombre_contacto').text(errors.nombre_contacto[0]);
                    }
                    if (errors.telefono_contacto) {
                        $('#telefono_contacto').text(errors.telefono_contacto[0]);
                    }
                    if (errors.direccion_fisica) {
                        $('#direccion_fisica').text(errors.direccion_fisica[0]);
                    }
                    if (errors.frecuencia_id) {
                        $('#frecuencia_id').text(errors.frecuencia_id[0]);
                    }

                    if (errors.tipocliente) {
                        $('#tipocliente').text(errors.tipocliente[0]);
                    }
                    if (errors.rut) {
                        $('#rut').text(errors.rut[0]);
                    }

                    if (errors.empleados) {
                        $('#empleado').text(errors.empleados[0]);
                    }
                    if (errors.obligaciones) {
                        $('#obligaciones_error').text(errors.obligaciones[0]);
                    }
                    if (errors.camaracomercio_id) {
                        $('#camaracomercio_id').text(errors.camaracomercio_id[0]);
                    }

                    if (errors.codigo_obligacionmunicipal) {
                        $('#codigo_obligacionmunicipal').text(errors.codigo_obligacionmunicipal[0]);
                    }

                    if (errors.otras_entidades) {
                        $('#otras_entidades').text(errors.otras_entidades[0]);
                    }

                }

                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }

                });
                Toast.fire({
                    icon: "error",
                    title: jqXHR.responseJSON.error ? jqXHR.responseJSON.error : "Ingresa la información obligatoria."
                });

                console.error('Error:', textStatus, errorThrown);
            }
        });
    });
}

if (routeCreate == null) {
    const formulario = document.getElementById('editar-empresa');

    formulario.addEventListener('submit', function (event) {
        event.preventDefault(); // Evitar el envío normal del formulario
        var formData = new FormData(formulario);// Crear un objeto FormData

        // Enviar los datos usando fetch
        $.ajax({
            url: $(this).attr('action'), // URL del endpoint
            type: 'POST',
            data: formData,
            contentType: false, // No establecer el tipo de contenido
            processData: false, // No procesar los datos
            success: function (response) {
                console.log('Éxito:', response);


                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }, willClose: () => {
                        isNavigatingAway = true;
                        window.location.href = routeIndex;
                    }
                });
                Toast.fire({
                    icon: "success",
                    title: "La empresa se ha actualizado exitosamente."
                });

            },
            error: function (jqXHR, textStatus, errorThrown) {

                $('#NIT').text('');
                $('#razon_social').text('');
                $('#correo_electronico').text('');
                $('#numero_contacto').text('');
                $('#notifica_calendario').text('');
                $('#nombre_contacto').text('');
                $('#telefono_contacto').text('');
                $('#direccion_fisica').text('');
                $('#frecuencia_id').text('');
                $('#tipocliente').text('');
                $('#rut').text('');
                $('#empleado').text('');
                $('#obligaciones_error').text('');
                $('#camaracomercio_id').text('');
                $('#codigo_obligacionmunicipal').text('');
                $('#otras_entidades').text('');

                if (jqXHR.status === 422) { // Verifica si es un error de validación
                    const errors = jqXHR.responseJSON.errors; // Captura los errores

                    // Muestra el mensaje de error en el elemento correspondiente
                    if (errors.NIT) {
                        $('#NIT').text(errors.NIT[0]);
                    }
                    if (errors.razon_social) {
                        $('#razon_social').text(errors.razon_social[0]);
                    }
                    if (errors.correo_electronico) {
                        $('#correo_electronico').text(errors.correo_electronico[0]);
                    }
                    if (errors.numero_contacto) {
                        $('#numero_contacto').text(errors.numero_contacto[0]);
                    }
                    if (errors.notifica_calendario) {
                        $('#notifica_calendario').text(errors.notifica_calendario[0]);
                    }
                    if (errors.nombre_contacto) {
                        $('#nombre_contacto').text(errors.nombre_contacto[0]);
                    }
                    if (errors.telefono_contacto) {
                        $('#telefono_contacto').text(errors.telefono_contacto[0]);
                    }
                    if (errors.direccion_fisica) {
                        $('#direccion_fisica').text(errors.direccion_fisica[0]);
                    }
                    if (errors.frecuencia_id) {
                        $('#frecuencia_id').text(errors.frecuencia_id[0]);
                    }

                    if (errors.tipocliente) {
                        $('#tipocliente').text(errors.tipocliente[0]);
                    }
                    if (errors.rut) {
                        $('#rut').text(errors.rut[0]);
                    }

                    if (errors.empleados) {
                        $('#empleado').text(errors.empleados[0]);
                    }
                    if (errors.obligaciones) {
                        $('#obligaciones_error').text(errors.obligaciones[0]);
                    }
                    if (errors.camaracomercio_id) {
                        $('#camaracomercio_id').text(errors.camaracomercio_id[0]);
                    }

                    if (errors.codigo_obligacionmunicipal) {
                        $('#codigo_obligacionmunicipal').text(errors.codigo_obligacionmunicipal[0]);
                    }

                    if (errors.otras_entidades) {
                        $('#otras_entidades').text(errors.otras_entidades[0]);
                    }

                }

                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }

                });
                Toast.fire({
                    icon: "error",
                    title: jqXHR.responseJSON.error ? jqXHR.responseJSON.error : "Ingresa la información obligatoria."
                });

                console.error('Error:', textStatus, errorThrown);
            }
        });
    });
}

$('#empleados').change(function () {
    // Obtener el elemento select
    var empleados = document.getElementById('empleados');

    // Crear un array para almacenar los valores seleccionados
    var selectEmpleados = [];

    // Iterar sobre las opciones del select
    for (var option of empleados.options) {
        if (option.selected) {
            selectEmpleados.push(option.value); // Agregar el valor al array si está seleccionado
        }
    }

    localStorage.setItem("empleadosGuardados", selectEmpleados);
});

let empleados = localStorage.getItem("empleadosGuardados");

//Crea un array convirtiendo las opciones en number
if (empleados) {
    var arrayEmpleados = empleados.split(',').map(Number);
}


if (arrayEmpleados) {
    var select = document.getElementById('empleados');
    var options = select.getElementsByTagName('option');

    for (var i = 0; i < options.length; i++) {
        if (arrayEmpleados.includes(parseInt(options[i].value))) {
            options[i].selected = true;
        }
    }
}


$('#obligaciones').change(function () {
    // Obtener el elemento select
    var obligaciones = document.getElementById('obligaciones');

    // Crear un array para almacenar los valores seleccionados
    var selectedObligaciones = [];

    // Iterar sobre las opciones del select
    for (var option of obligaciones.options) {
        if (option.selected) {
            selectedObligaciones.push(option.value); // Agregar el valor al array si está seleccionado
        }
    }

    localStorage.setItem("obligacionesGuardados", selectedObligaciones);
});

var obligaciones = localStorage.getItem("obligacionesGuardados");

//Crea un array con las opciones guardadas sin convertirlas en number
if (obligaciones) {
    var arrayObligaciones = obligaciones.split(',');
}

if (arrayObligaciones) {
    var select = document.getElementById('obligaciones');
    var options = select.getElementsByTagName('option');

    for (var i = 0; i < options.length; i++) {
        if (arrayObligaciones.includes(options[i].value)) {
            options[i].selected = true;
        }
    }
}

$('#camaracomercio').change(function () {
    var camara_comercio = document.getElementById("camaracomercio").value;
    //Guarda la que ya se había seleccionado
    localStorage.setItem("CamaraComercioGuardado", camara_comercio);
});

// Restaurar la opción seleccionada
var camaracomercio = localStorage.getItem("CamaraComercioGuardado");

if (camaracomercio) {

    var datalistOptions = document.getElementById('datalistOptionsCamara');
    var options = datalistOptions.getElementsByTagName('option');

    for (var i = 0; i < options.length; i++) {
        if (options[i].value === camaracomercio) {
            document.getElementById('camaracomercio').value = options[i].value;
            break;
        }
    }
}


$('#obligacionMunicipal').change(function () {
    // Obtener el elemento select
    var obligacionesMunicipal = document.getElementById('obligacionMunicipal');

    // Crear un array para almacenar los valores seleccionados
    var selectedObligacionesMunicipales = [];

    // Iterar sobre las opciones del select
    for (var option of obligacionesMunicipal.options) {
        if (option.selected) {
            selectedObligacionesMunicipales.push(option.value); // Agregar el valor al array si está seleccionado
        }
    }

    localStorage.setItem("obligacionesMunicipalesGuardados", selectedObligacionesMunicipales);
});

var obligacionesMunicipales = localStorage.getItem("obligacionesMunicipalesGuardados");

//Crea un array con las opciones guardadas sin convertirlas en number
if (obligacionesMunicipales) {
    var arrayObligacionesMunicipales = obligacionesMunicipales.split(',');
}

if (arrayObligacionesMunicipales) {
    var select = document.getElementById('obligacionMunicipal');
    var options = select.getElementsByTagName('option');

    for (var i = 0; i < options.length; i++) {
        if (arrayObligacionesMunicipales.includes(options[i].value)) {
            options[i].selected = true;
        }
    }
}


$('#OtrasEntidades').change(function () {
    // Obtener el elemento select
    var OtrasEntidades = document.getElementById('OtrasEntidades');

    // Crear un array para almacenar los valores seleccionados
    var selectedOtrasEntidades = [];

    // Iterar sobre las opciones del select
    for (var option of OtrasEntidades.options) {
        if (option.selected) {
            selectedOtrasEntidades.push(option.value); // Agregar el valor al array si está seleccionado
        }
    }

    localStorage.setItem("OtrasEntidadesGuardados", selectedOtrasEntidades);
});

var OtrasEntidades = localStorage.getItem("OtrasEntidadesGuardados");

//Crea un array con las opciones guardadas sin convertirlas en number
if (OtrasEntidades) {
    var arrayOtrasEntidades = OtrasEntidades.split(',');
}

if (arrayOtrasEntidades) {
    var select = document.getElementById('OtrasEntidades');
    var options = select.getElementsByTagName('option');

    for (var i = 0; i < options.length; i++) {
        if (arrayOtrasEntidades.includes(options[i].value)) {
            options[i].selected = true;
        }
    }
}

$('#ciiu').change(function () {
    // Obtener el elemento select
    var ciiu = document.getElementById('ciiu');

    // Crear un array para almacenar los valores seleccionados
    var selectedCiiu = [];

    // Iterar sobre las opciones del select
    for (var option of ciiu.options) {
        if (option.selected) {
            selectedCiiu.push(option.value); // Agregar el valor al array si está seleccionado
        }
    }

    localStorage.setItem("ciiuGuardados", selectedCiiu);
});

var ciiu = localStorage.getItem("ciiuGuardados");

//Crea un array con las opciones guardadas sin convertirlas en number
if (ciiu) {
    var arrayCiiu = ciiu.split(',');
}

if (arrayCiiu) {
    var select = document.getElementById('ciiu');
    var options = select.getElementsByTagName('option');

    for (var i = 0; i < options.length; i++) {
        if (arrayCiiu.includes(options[i].value)) {
            options[i].selected = true;
        }
    }
}



$('#camara_comercio_establecimientos').change(function () {
    // Obtener el elemento select
    var CCE = document.getElementById('camara_comercio_establecimientos');

    // Crear un array para almacenar los valores seleccionados
    var selectedCCE = [];

    // Iterar sobre las opciones del select
    for (var option of CCE.options) {
        if (option.selected) {
            selectedCCE.push(option.value); // Agregar el valor al array si está seleccionado
        }
    }

    localStorage.setItem("CCEGuardados", selectedCCE);
});

var CCE = localStorage.getItem("CCEGuardados");

//Crea un array con las opciones guardadas sin convertirlas en number
if (CCE) {
    var arrayCCE = CCE.split(',');
}

if (arrayCCE) {
    var select = document.getElementById('camara_comercio_establecimientos');
    var options = select.getElementsByTagName('option');

    for (var i = 0; i < options.length; i++) {
        if (arrayCCE.includes(options[i].value)) {
            options[i].selected = true;
        }
    }
}

$('#year').change(function () {
    var year = $(this).val();
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        type: 'GET',
        url: routeUVT + '/' + year,
        success: function (uvt) {
            let valor = JSON.parse(uvt);

            $('#uvt').val(valor.valor_pesos);
            $('#salario_minimo').val(valor.salario_minimo);

        }
    })
});

let year = $('#year').val();

if (year) {
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        type: 'GET',
        url: routeUVT + '/' + year,
        success: function (uvt) {
            let valor = JSON.parse(uvt);

            $('#uvt').val(valor.valor_pesos);
            $('#salario_minimo').val(valor.salario_minimo);
            document.getElementById('accordionEmpresa').style.display = 'block';
        }
    })
}


$('#regimen_simple_tributacion').change(function () {
    var opcion = $(this).val();

    if (opcion == "SI") { //Si pertenece al regimén es obligación anual (482)
        var selectedOptions = $('#obligaciones').val() || []; //Mantiene las obligaciones que ya han sido seleccionadas
        selectedOptions.push('482', '471', '47');

        var index = selectedOptions.indexOf('48');
        if (index > -1) {
            selectedOptions.splice(index, 1);
        }

        var index2 = selectedOptions.indexOf('481');
        if (index2 > -1) {
            selectedOptions.splice(index2, 1);
        }

        $('#obligaciones').val(selectedOptions).trigger('change');
        $('#obligaciones').on('select2:unselecting', function (e) {
            if (e.params.args.data.id === '482') {
                e.preventDefault();
            }
        });
    } else {
        var selectedOptions = $('#obligaciones').val() || [];
        var codesToRemove = ['482', '471', '47'];
        selectedOptions = selectedOptions.filter(function (code) {
            return !codesToRemove.includes(code);
        });
        $('#obligaciones').val(selectedOptions).trigger('change');
    }
});

let regimenSimple = $('#regimen_simple_tributacion').val();

if (regimenSimple) {
    if (regimenSimple == "SI") { //Si pertenece al regimén es obligación anual (482)
        var selectedOptions = $('#obligaciones').val() || [];
        selectedOptions.push('482');

        $('#obligaciones').val(selectedOptions).trigger('change');

        $('#obligaciones').on('select2:unselecting', function (e) {
            if (e.params.args.data.id === '482') {
                e.preventDefault();
            }
        });
    } else {
        var selectedOptions = $('#obligaciones').val() || [];
        var index = selectedOptions.indexOf('482');
        if (index > -1) {
            selectedOptions.splice(index, 1);
        }
        $('#obligaciones').val(selectedOptions).trigger('change');
    }
}

function sumIngresos() {
    var uvt = $('#uvt').val();
    var valor = parseFloat(uvt) * 92000;

    var totalIngresos = 0;
    // Obtener los valores de los inputs y sumarlos
    totalIngresos += parseFloat($("#ingresos_gravados").val()) || 0;
    totalIngresos += parseFloat($("#ingresos_exentos").val()) || 0;

    total = totalIngresos;

    if ($('#regimen_simple_tributacion').val() == "NO") { //Si no pertenece al regimén seleccionar las obligaciones dependiendo de la condición
        if (total >= valor) { //Si la sumas de los ingreso gravados y exentos es mayor o igual a 92.000 UVT la obligación es Bimestral (481)
           var selectedOptions = $('#obligaciones').val() || [];
           selectedOptions.push('481');

           var index = selectedOptions.indexOf('48');
           if (index > -1) {
               selectedOptions.splice(index, 1);
           }

           $('#obligaciones').val(selectedOptions).trigger('change');
           $('#obligaciones').on('select2:unselecting', function (e) {
               if (e.params.args.data.id === '481') {
                   e.preventDefault();
               }
           });

        } else if (total < valor) { //Si la sumas de los ingreso gravados y exentos es menor a 92.000 UVT la obligación es Cuatrimestral (48)
           var selectedOptions = $('#obligaciones').val() || [];
           selectedOptions.push('48');

           var index = selectedOptions.indexOf('481');
           if (index > -1) {
               selectedOptions.splice(index, 1);
           }


            $('#obligaciones').val(selectedOptions).trigger('change');
            $('#obligaciones').on('select2:unselecting', function (e) {
                if (e.params.args.data.id === '48') {
                    e.preventDefault();
                }
            });
        }
    }
}

$("#ingresos_gravados, #ingresos_exentos").on("input", function () {
    sumIngresos();
});

let ingresosGravados = $('#ingresos_gravados').val();
let ingresosExentos = $('#ingresos_exentos').val();

if (ingresosGravados || ingresosExentos) {
    sumIngresos();
}


$('#operaciones_excentas').change(function () {
    var opcion = $(this).val();
    if ($('#regimen_simple_tributacion').val() == "NO") {
        if (opcion == "SI") { //Si realiza operaciones excentas es obligación Bimestral (481)
            var selectedOptions = $('#obligaciones').val() || [];
            selectedOptions.push('481');

            var index = selectedOptions.indexOf('48');
            if (index > -1) {
                selectedOptions.splice(index, 1);
            } 

            $('#obligaciones').val(selectedOptions).trigger('change');
            $('#obligaciones').on('select2:unselecting', function (e) {
                if (e.params.args.data.id === '481') {
                    e.preventDefault();
                }
            });
        }
    }
});

let operacionExcentas = $('#operaciones_excentas').val();

if (operacionExcentas) {
    if ($('#regimen_simple_tributacion').val() == "NO") {
        if (operacionExcentas == "SI") { //Si realiza operaciones excentas es obligación Bimestral (481)
           var selectedOptions = $('#obligaciones').val() || [];
            selectedOptions.push('481');

            var index = selectedOptions.indexOf('48');
            if (index > -1) {
                selectedOptions.splice(index, 1);
            }
 

            $('#obligaciones').val(selectedOptions).trigger('change');
            $('#obligaciones').on('select2:unselecting', function (e) {
                if (e.params.args.data.id === '481') {
                    e.preventDefault();
                }
            });
        }
    }
}

$('#actividades_exp_imp').change(function () {
    var opcion = $(this).val();

    if ($('#regimen_simple_tributacion').val() == "NO") {
        if (opcion == "SI") { //Si realiza actividades de exportación o importación es obligación Bimestral (481)
            var selectedOptions = $('#obligaciones').val() || [];
            selectedOptions.push('481');

            var index = selectedOptions.indexOf('48');
            if (index > -1) {
                selectedOptions.splice(index, 1);
            }
 

            $('#obligaciones').val(selectedOptions).trigger('change');
            $('#obligaciones').on('select2:unselecting', function (e) {
                if (e.params.args.data.id === '481') {
                    e.preventDefault();
                }
            });
        }
    }
});

let actividadesExpImp = $('#actividades_exp_imp').val();

if (actividadesExpImp) {
    if ($('#regimen_simple_tributacion').val() == "NO") {
        if (actividadesExpImp == "SI") { //Si realiza actividades de exportación o importación es obligación Bimestral (481)
            var selectedOptions = $('#obligaciones').val() || [];
            selectedOptions.push('481');

            var index = selectedOptions.indexOf('48');
            if (index > -1) {
                selectedOptions.splice(index, 1);
            }
 

            $('#obligaciones').val(selectedOptions).trigger('change');
            $('#obligaciones').on('select2:unselecting', function (e) {
                if (e.params.args.data.id === '481') {
                    e.preventDefault();
                }
            });
        }
    }
}


$('#gran_contribuyente').change(function () {
    var opcion = $(this).val();

    if ($('#regimen_simple_tributacion').val() == "NO") {
        if (opcion == "SI") { //Si es gran contribuyente es obligación Bimestral (481)
            var selectedOptions = $('#obligaciones').val() || [];
            selectedOptions.push('481');

            var index = selectedOptions.indexOf('48');
            if (index > -1) {
                selectedOptions.splice(index, 1);
            }
 

            $('#obligaciones').val(selectedOptions).trigger('change');
            $('#obligaciones').on('select2:unselecting', function (e) {
                if (e.params.args.data.id === '481') {
                    e.preventDefault();
                }
            });
        }
    }
});

let granContribuyente = $('#gran_contribuyente').val();

if (granContribuyente) {
    if ($('#regimen_simple_tributacion').val() == "NO") {
        if (granContribuyente == "SI") { //Si es gran contribuyente es obligación Bimestral (481)
            var selectedOptions = $('#obligaciones').val() || [];
            selectedOptions.push('481');

            var index = selectedOptions.indexOf('48');
            if (index > -1) {
                selectedOptions.splice(index, 1);
            }
 

            $('#obligaciones').val(selectedOptions).trigger('change');
            $('#obligaciones').on('select2:unselecting', function (e) {
                if (e.params.args.data.id === '481') {
                    e.preventDefault();
                }
            });
        }
    }
}

function sumDeclaracion() {
    var uvt = $('#uvt').val();
    var valor = parseFloat(uvt) * 45000;

    var totalIngresos = 0;
    // Obtener los valores de los inputs y sumarlos
    totalIngresos += parseFloat($("#ingresos_brutos_fiscales_anio_anterior").val()) || 0;
    total = totalIngresos;

    if (total >= valor) { //Si la suma de los ingresos brutos del año anterior son mayor o igual a 45.000 UVT debe presentar declaración de renta.
        $('#formato_conciliacion_fiscal').val('SI')
    } else if (total < valor) {
        $('#formato_conciliacion_fiscal').val('NO')
    }
}

$("#ingresos_brutos_fiscales_anio_anterior").on("input", function () {
    sumDeclaracion();
});

let ingresosBrutosFiscales = $('#ingresos_brutos_fiscales_anio_anterior').val();

if (ingresosBrutosFiscales) {
    sumDeclaracion();
}


$("#patrimonio_brutos_diciembre_anio_anterior, #ingreso_brutos_tributario_diciembre_anio_anterior").on("input", function () {
    var uvt = $('#uvt').val();
    var valor = parseFloat(uvt) * 100000;

    var patrimonio = parseFloat($('#patrimonio_brutos_diciembre_anio_anterior').val()) || 0;
    var ingresos_brutos = parseFloat($('#ingreso_brutos_tributario_diciembre_anio_anterior').val()) || 0;

    if (patrimonio >= valor || ingresos_brutos >= valor) { //Si el patrimonio o los igresos brutos son mayores o iguales a 100.000 UVT debe presentar firma del contador
        $('#declaracion_tributaria_firma_contador').val('SI')
    } else {
        $('#declaracion_tributaria_firma_contador').val('NO')
    }
});

let patrimonioBrutos = $('#patrimonio_brutos_diciembre_anio_anterior').val();
let ingresosBrutos = $('#ingreso_brutos_tributario_diciembre_anio_anterior').val();

if (patrimonioBrutos || ingresosBrutos) {
    var uvt = $('#uvt').val();
    var valor = parseFloat(uvt) * 100000;

    var patrimonio = parseFloat(patrimonioBrutos) || 0;
    var ingresos_brutos = parseFloat(ingresosBrutos) || 0;

    if (patrimonio >= valor || ingresos_brutos >= valor) { //Si el patrimonio o los igresos brutos son mayores o iguales a 100.000 UVT debe presentar firma del contador
        $('#declaracion_tributaria_firma_contador').val('SI')
    } else {
        $('#declaracion_tributaria_firma_contador').val('NO')
    }
}


$("#activos_brutos_diciembre_anio_anterior, #ingreso_brutos_diciembre_anio_anterior").on("input", function () {
    var salario_minimo = $('#salario_minimo').val();
    var valor1 = parseFloat(salario_minimo) * 5000;
    var valor2 = parseFloat(salario_minimo) * 3000;

    var activos_brutos = parseFloat($('#activos_brutos_diciembre_anio_anterior').val()) || 0;
    var ingresos_brutos = parseFloat($('#ingreso_brutos_diciembre_anio_anterior').val()) || 0;

    if (activos_brutos >= valor1 || ingresos_brutos >= valor2) { //Si los activos o los ingresos brutos son mayores o iguales a 5.000 salarios mínimos 
        // o los ingresos brutos son mayores o iguales a 3.000 salarios mínimos debe tener revisor fiscal
        $('#revisor_fiscal').val('SI')
    } else {
        $('#revisor_fiscal').val('NO')
    }
});

let activosBrutos = $('#activos_brutos_diciembre_anio_anterior').val();
let ingresosBrutosDiciembre = $('#ingreso_brutos_diciembre_anio_anterior').val();

if (activosBrutos || ingresosBrutosDiciembre) {
    var salario_minimo = $('#salario_minimo').val();
    var valor1 = parseFloat(salario_minimo) * 5000;
    var valor2 = parseFloat(salario_minimo) * 3000;

    var activos_brutos = parseFloat($('#activos_brutos_diciembre_anio_anterior').val()) || 0;
    var ingresos_brutos = parseFloat($('#ingreso_brutos_diciembre_anio_anterior').val()) || 0;

    if (activos_brutos >= valor1 || ingresos_brutos >= valor2) { //Si los activos o los ingresos brutos son mayores o iguales a 5.000 salarios mínimos 
        // o los ingresos brutos son mayores o iguales a 3.000 salarios mínimos debe tener revisor fiscal
        $('#revisor_fiscal').val('SI')
    } else {
        $('#revisor_fiscal').val('NO')
    }
}

$('#add-input-ica').click(function () {
    $('#inputs-container-ica').append(`
    <div class="input-wrapper">
        <div class="row">
            <div class="col-xl-6">
                <div class="form-floating mb-3">
                    <input type="text" id="usuario_ica" name="usuario_ica[]"
                        value="" class="form-control"
                        placeholder=" " />
                    <label for="usuario_ica" class="fw-normal">Usuario ICA portal </label>
                </div>
            </div>

            <div class="col-xl-5">
                <div class="form-floating mb-3">
                    <input type="text" id="icaclaveportal" name="icaclaveportal[]"
                        value="" class="form-control"
                        placeholder=" " />
                    <label for="icaclaveportal" class="fw-normal">ICA contraseña portal </label>
                </div>
            </div>

            <div class="col-xl-1 mb-3 d-flex align-items-center">
                <button class="remove-input-ica btn btn-danger btn-sm" type="button">Eliminar</button>
            </div>
        </div>
    </div>
    `);

});

$('#inputs-container-ica').on('click', '.remove-input-ica', function () {
    $(this).closest('.input-wrapper').remove();
});