
$('#estado_actividad_id').change(function () {

    var empleado = document.getElementById('empleado');
    var just = document.getElementById('just');
    var modalidad = document.getElementById('modalidad');
    var empresa = document.getElementById('empresa');
    var progreso = document.getElementById('progreso');
    var documento = document.getElementById('documento');
    var recomendaciones = document.getElementById('recomendaciones');

    //Cancelado
    if ($(this).val() == 4) {
        empleado.style.display = 'none';
        empresa.style.display = 'none';
        modalidad.style.display = 'none';
        just.style.display = 'block';
        progreso.style.display = 'block';
        documento.style.display = 'block';
    //Pausado o Reactivado
    }else if ($(this).val() == 3 || $(this).val() == 9){
        empleado.style.display = 'none';
        empresa.style.display = 'none';
        just.style.display = 'block';
        progreso.style.display = 'block';
        modalidad.style.display = 'block';
        documento.style.display = 'block';
    //Reasignar
    } else if ($(this).val() == 5) {
        progreso.style.display = 'none';
        documento.style.display = 'none';
        just.style.display = 'none';
        modalidad.style.display = 'none';
        empleado.style.display = 'block';
        empresa.style.display = 'block';
    //Finalizado, Cumplido
    }else if($(this).val() == 7 || $(this).val() == 8){
        just.style.display = 'block';
        documento.style.display = 'block';
        recomendaciones.style.display = 'block';
        progreso.style.display = 'none';
        modalidad.style.display = 'none';
    } else {
        just.style.display = 'none';
        modalidad.style.display = 'none';
        progreso.style.display = 'none';
        empleado.style.display = 'none';
        empresa.style.display = 'none';
        documento.style.display = 'none';
    }
});

if(routeCreate) {
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
    
    var responsable = $('#responsable_id').val();
    
    if(responsable) {
        $('#cliente_id').prop("disabled", false);
        findEmpresas(responsable);
    }
    
    $('#responsable_id').change(function () {
        var responsableActividad = $(this).val();
        $('#cliente_id').prop("disabled", false);
        $('#usuario_id').prop("disabled", true);
    
        document.getElementById('usuario_id').value = '';
    
       findEmpresas(responsableActividad);
    });
    
    $('#cliente_id').change(function () {
        let valorSeleccionado = $(this).val(); // Obtén el valor actual del input
        var empresa = $('#datalistOptionsEmpresa option').filter(function() {
            return $(this).val() === valorSeleccionado; // Filtra la opción que coincide con el valor
        }).data('id'); // Ahora obtén el data-id
    
        localStorage.setItem("empresaSeleccionada", valorSeleccionado);
    
        let cliente = document.getElementById('empresa_asociada');
    
        if (empresa == 1) {
            cliente.style.display = 'block';
        } else {
            cliente.style.display = 'none';
        }
    
        $('#usuario_id').prop("disabled", false);
        findResponsables(empresa);
    });
    
    $('#usuario_id').change(function () {
        var usuario_guardado = document.getElementById("usuario_id").value;
        //Guarda la empresa que ya se había seleccionado
        localStorage.setItem("usuarioSeleccionada", usuario_guardado);
    });
    
    function findEmpresas(responsableActividad) {
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: 'GET',
            url: 'cliente_id/' + responsableActividad,
            success: function (response) {
    
                let empresas = JSON.parse(response);
                document.getElementById('cliente_id').value = '';
    
                var dataList = document.getElementById('datalistOptionsEmpresa');
    
                while (dataList.firstChild) {
                    dataList.removeChild(dataList.firstChild); // Eliminar todas las opciones del datalist
                }
    
                $.each(empresas, function (index, value) {
                    $("#datalistOptionsEmpresa").append('<option value="'  + value.id + ' - ' +  value.razon_social + '" data-id="' + value.id + '"></option>');
                });
    
                // Restaurar la opción seleccionada
                var empresa_guardada = localStorage.getItem("empresaSeleccionada");
    
                if (empresa_guardada) {
    
                        $('#usuario_id').prop("disabled", false);
                        var empresa = $('#datalistOptionsEmpresa option').filter(function() {
                            return $(this).val() === empresa_guardada; // Filtra la opción que coincide con el valor
                        }).data('id'); // Ahora obtén el data-id
    
                        findResponsables(empresa);
    
                        if (empresa == 1) {
                            document.getElementById('empresa_asociada').style.display = 'block';
                        } else {
                            document.getElementById('empresa_asociada').style.display = 'none';
                        }
    
                    var datalistOptions = document.getElementById('datalistOptionsEmpresa');
                    var options = datalistOptions.getElementsByTagName('option');
    
                    for (var i = 0; i < options.length; i++) {
                        if (options[i].value === empresa_guardada) {
                            document.getElementById('cliente_id').value = options[i].value;
                            break;
                        }
                    }
                }
            }
        })
    }
    
    function findResponsables(empresa) {
        $('#usuario_id').prop("disabled", false);
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: 'GET',
            url: 'usuario_id/' + empresa,
            success: function (response) {
    
                let responsables = JSON.parse(response);
                document.getElementById('usuario_id').value = '';
    
                var dataList = document.getElementById('datalistOptionsResponsable');
    
                while (dataList.firstChild) {
                    dataList.removeChild(dataList.firstChild); // Eliminar todas las opciones del datalist
                }
    
                $.each(responsables, function (index, value) {
                    $("#datalistOptionsResponsable").append('<option value="' + value.id + ' - ' +  value.nombres + ' ' + value.apellidos + '" data-id="' + value.id + '"></option>');
                });
    
                // Restaurar la opción seleccionada
                var usuario_guardado = localStorage.getItem("usuarioSeleccionada");
    
                if (usuario_guardado) {
                    var datalistOptions = document.getElementById('datalistOptionsResponsable');
                    var options = datalistOptions.getElementsByTagName('option');
    
                    for (var i = 0; i < options.length; i++) {
                        if (options[i].value === usuario_guardado) {
                            document.getElementById('usuario_id').value = options[i].value;
                            break;
                        }
                    }
                }
            }
        })
    
    }
    
    $('#empresa_asociada_id').change(function () {
        var empresa_asociada = document.getElementById("empresa_asociada_id").value;
        //Guarda la empresa que ya se había seleccionado
        localStorage.setItem("empresaAsociadaSeleccionada", empresa_asociada);
    });
    
    var empresa_asociada = localStorage.getItem("empresaAsociadaSeleccionada");
    
    if (empresa_asociada) {
        var datalistOptions = document.getElementById('datalistOptionsEmpresaAsociada');
        var options = datalistOptions.getElementsByTagName('option');
    
        for (var i = 0; i < options.length; i++) {
            if (options[i].value === empresa_asociada) {
                document.getElementById('empresa_asociada_id').value = options[i].value;
                break;
            }
        }
    }

    document.getElementById('crearActividad').addEventListener('submit', function(event) {
        // Deshabilitar el botón de envío
        document.getElementById('btn-crear').disabled = true;
    });
}

$('#estado_actividad_id').change(function () {
    let empresa = $('#empresa_id').val();
    $('#usuario_id').prop("disabled", false);
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        type: 'GET',
        url: 'usuario_id/' + empresa,
        success: function ($responsable) {

            let responsable = JSON.parse($responsable);

            $('#usuario_id').empty();

            $("#usuario_id").append('<option value="">Seleccione una opción</option>');

            $.each(responsable, function (index, value) {
                $("#usuario_id").append('<option value=' + value.id + '>' + value
                    .nombres + ' ' + value.apellidos + '</option>');
            });
        }
    })
});