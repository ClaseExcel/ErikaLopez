
$('#empresa_id').change(function () {
    let empresa = $(this).val();

    $('#responsable_id').prop("disabled", false);

    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        type: 'GET',
        url: 'actividad_cliente/usuario_id/' + empresa,
        success: function (responsableActividad) {

            let responsable = JSON.parse(responsableActividad);

            document.getElementById('responsable_id').value = '';
            document.querySelector('#todasLasEmpresas').checked = false;

            var dataList = document.getElementById('datalistOptionsr');

            while (dataList.firstChild) {
                dataList.removeChild(dataList.firstChild); // Eliminar todas las opciones del datalist
            }

            $.each(responsable, function (index, value) {
                $("#datalistOptionsr").append('<option value="' + value.id + ' - ' + value.nombres + ' ' + value.apellidos + '" data-id="' + value.id + '"></option>');
            });
        }
    })
});

document.querySelector('#todasLasEmpresas').addEventListener('change', function () {
    if (this.checked) {
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: 'GET',
            url: 'actividad_cliente/usuario_id/' + null,
            success: function (responsableActividad) {

                let responsable = JSON.parse(responsableActividad);
                document.getElementById('responsable_id').value = '';
                document.getElementById('empresa_id').value = '';
                document.getElementById('usercreaactId').value = '';

                var dataList = document.getElementById('datalistOptionsr');

                while (dataList.firstChild) {
                    dataList.removeChild(dataList.firstChild); // Eliminar todas las opciones del datalist
                }

                $.each(responsable, function (index, value) {
                    $("#datalistOptionsr").append('<option value="' + value.id + ' - ' + value.nombres + ' ' + value.apellidos + '" data-id="' + value.id + '"></option>');
                });
            }
        })
    } else {
        document.getElementById('responsable_id').value = '';
        document.getElementById('empresa_id').value = '';
    }
});


var calendar = null;

// $('#responsable').change(function () {
//     let responsable = $(this).val();
//     var calendarEmpleado = document.getElementById('calendario-empleados');
//     document.getElementById('calendario-actividades').style.display = 'none';

//     // Si ya existe una instancia de calendario, destrúyela antes de crear una nueva.
//     if (calendar !== null) {
//         calendar.destroy();
//     }

//     calendar = new FullCalendar.Calendar(calendarEmpleado, {
//         plugins: ['dayGrid', 'timeGrid', 'list', 'moment'],
//         header: {
//             left: "prev,next",
//             center: "title",
//             right: "dayGridMonth,listWeek,timeGridDay"
//         },
//         buttonText: {
//             today: 'Hoy',
//             month: 'Mes',
//             week: 'Semana',
//             day: 'Día',
//         },
//         events: 'actividad-empleado/' + responsable,
//         //Muestra modal al dar clic al evento
//         eventClick: function (calEvent, jsEvent, view) {
//             reopenModal();
//             document.getElementById('actividad_id').value = calEvent.event.id;
//             $('#actividadModal #titulo-actividad').html('<b>' + calEvent.event.title + '</b>');
//             $('#actividadModal #descripcion').html(calEvent.event._def.extendedProps
//                 .description);
//             $('#actividadModal').modal("show");
//         },
//         weekends: true,
//         selectable: true,
//         editable: false,
//         defaultView: 'dayGridMonth',
//         contentHeight: 850,
//         locale: 'es',
//         views: {
//             week: {
//                 type: 'timeGrid',
//                 duration: {
//                     days: 4
//                 }
//             },
//             day: {
//                 type: 'dayGrid',
//                 duration: {
//                     weeks: 4
//                 }
//             }
//         },
//         eventTimeFormat: {
//             hour: '2-digit',
//             minute: '2-digit',
//             hour12: true,
//         },
//         slotLabelFormat: {
//             hour: '2-digit',
//             minute: '2-digit',
//             hour12: true,
//         },
//         displayEventEnd: true,
//         minTime: '06:00:00',
//         maxTime: '21:00:00',
//     });

//     calendar.render();
// });


// $('#empresa_id').change(function () {
//     let empresa = $(this).val();
//     var calendarEmpleado = document.getElementById('calendario-empleados');
//     document.getElementById('calendario-actividades').style.display = 'none';

//     // Si ya existe una instancia de calendario, destrúyela antes de crear una nueva.
//     if (calendar !== null) {
//         calendar.destroy();
//     }

//     if(empresa == 0) {
//         window.location.reload();
//     }

//     calendar = new FullCalendar.Calendar(calendarEmpleado, {
//         plugins: ['dayGrid', 'timeGrid', 'list', 'moment'],
//         header: {
//             left: "prev,next",
//             center: "title",
//             right: "dayGridMonth,listWeek,timeGridDay"
//         },
//         buttonText: {
//             today: 'Hoy',
//             month: 'Mes',
//             week: 'Semana',
//             day: 'Día',
//         },
//         events: 'actividad-empresa/' + empresa,
//         //Muestra modal al dar clic al evento
//         eventClick: function (calEvent, jsEvent, view) {
//             reopenModal();
//             document.getElementById('actividad_id').value = calEvent.event.id;
//             $('#actividadModal #titulo-actividad').html('<b>' + calEvent.event.title + '</b>');
//             $('#actividadModal #descripcion').html(calEvent.event._def.extendedProps
//                 .description);
//             $('#actividadModal').modal("show");
//         },
//         weekends: true,
//         selectable: true,
//         editable: false,
//         defaultView: 'dayGridMonth',
//         contentHeight: 850,
//         locale: 'es',
//         views: {
//             week: {
//                 type: 'timeGrid',
//                 duration: {
//                     days: 4
//                 }
//             },
//             day: {
//                 type: 'dayGrid',
//                 duration: {
//                     weeks: 4
//                 }
//             }
//         },
//         eventTimeFormat: {
//             hour: '2-digit',
//             minute: '2-digit',
//             hour12: true,
//         },
//         slotLabelFormat: {
//             hour: '2-digit',
//             minute: '2-digit',
//             hour12: true,
//         },
//         displayEventEnd: true,
//         minTime: '06:00:00',
//         maxTime: '21:00:00',
//     });

//     calendar.render();
// });







