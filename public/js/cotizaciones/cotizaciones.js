
ClassicEditor.create(document.querySelector('#observaciones'), {
    toolbar: {
        items: [
            'bold', 'italic', '|', 'link', '|',
            'bulletedList', 'numberedList', '|',
            'undo', 'redo',
        ],
        shouldNotGroupWhenFull: true
    },
    link: {
        decorators: {
            openInNewTab: {
                mode: 'manual',
                label: 'Abre en una ventana nueva',
                defaultValue: true,
                attributes: {
                    target: '_blank',
                    rel: 'noopener noreferrer'
                }
            }
        }
    }
})
    .catch(error => {
        console.error(error);
    });

ClassicEditor.create(document.querySelector('#observacion_primer_seguimiento'), {
    toolbar: {
        items: [
            'bold', 'italic', '|', 'link', '|',
            'bulletedList', 'numberedList', '|',
            'undo', 'redo',
        ],
        shouldNotGroupWhenFull: true
    },
    link: {
        decorators: {
            openInNewTab: {
                mode: 'manual',
                label: 'Abre en una ventana nueva',
                defaultValue: true,
                attributes: {
                    target: '_blank',
                    rel: 'noopener noreferrer'
                }
            }
        }
    }
})
    .catch(error => {
        console.error(error);
    });

ClassicEditor.create(document.querySelector('#observacion_proximo_seguimiento'), {
    toolbar: {
        items: [
            'bold', 'italic', '|', 'link', '|',
            'bulletedList', 'numberedList', '|',
            'undo', 'redo',
        ],
        shouldNotGroupWhenFull: true
    },
    link: {
        decorators: {
            openInNewTab: {
                mode: 'manual',
                label: 'Abre en una ventana nueva',
                defaultValue: true,
                attributes: {
                    target: '_blank',
                    rel: 'noopener noreferrer'
                }
            }
        }
    }
})
    .catch(error => {
        console.error(error);
    });


$('#cliente_id').change(function () {
    let cliente = $(this).val();
    var nombre_contacto = document.getElementById('nombre_contacto');
    var numero_contacto = document.getElementById('telefono_contacto');

    if (nombre_contacto.value) {
        nombre_contacto.value = '';
    }

    if (numero_contacto.value) {
        numero_contacto.value = '';
    }

    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        type: 'GET',
        url: routeCliente + '/' + cliente,
        success: function (empresas) {

            let informacion = JSON.parse(empresas);

            nombre_contacto.value = informacion.nombre_contacto;
            numero_contacto.value = informacion.numero_contacto;
        }
    })
});


if (routeCreate) {
    let numero_cotizacion = document.getElementById("numero_cotizacion");
    // Define el rango
    const numeroAleatorio = Math.floor(Math.random() * 9000000) + 10000000;
    // Asigna el número aleatorio al input
    numero_cotizacion.value = numeroAleatorio;

    window.onload = function () {
        var inputsFecha = document.querySelectorAll('input[type="date"]'); // Obtener todos los inputs de tipo date
    
        var fechaActual = new Date(); // Fecha actual
        var mes = fechaActual.getMonth() + 1; // Obtener el mes actual
        var dia = fechaActual.getDate(); // Obtener el día actual
        var anio = fechaActual.getFullYear(); // Obtener el año actual
    
        if (dia < 10) dia = '0' + dia; // Agregar un cero si el día es menor a 10
        if (mes < 10) mes = '0' + mes; // Agregar un cero si el mes es menor a 10
    
        var fechaMinima = anio + '-' + mes + '-' + dia; // Crear la fecha mínima en formato yyyy-mm-dd
    
        // Establecer la fecha mínima en todos los inputs de fecha
        for (var i = 0; i < inputsFecha.length; i++) {
            inputsFecha[i].setAttribute('min', fechaMinima);
        }
    }
}
