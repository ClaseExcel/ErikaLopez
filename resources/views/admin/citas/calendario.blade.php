@extends('layouts.admin')
@section('title', 'Citas')
@section('library')
    @include('cdn.fullcalendar-head')
@endsection
@section('content')

    <div class="row">
        <div class="col-md-12 col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        {{-- <div class="col-12 col-md-6"><span
                                class="badge bg-light border rounded-pill w-100 py-1 fw-normal fs-6 mb-2 text-start"><i
                                    class="fas fa-circle dis-color"></i> Disponible</span></div> --}}
                                    <div class="col-12 col-md-6"><span
                                        class="badge bg-light border rounded-pill w-100 py-1 fw-normal fs-6 mb-2 text-start"><i
                                            class="fas fa-circle res-color"></i> Reservado</span></div>
                                <div class="col-12 col-md-6"><span
                                    class="badge bg-light border rounded-pill w-100 py-1 fw-normal fs-6 mb-2 text-start"><i
                                        class="fas fa-circle dis-color" style="color: #1e7b7f !important"></i> Cancela cliente</span></div>
                                <div class="col-12 col-md-6"><span
                                    class="badge bg-light border rounded-pill w-100 py-1 fw-normal fs-6 mb-2 text-start"><i
                                        class="fas fa-circle dis-color" style="color: #33d1d6 !important"></i> Cancela Empresa</span></div>
                                <div class="col-12 col-md-6"><span
                                    class="badge bg-light border rounded-pill w-100 py-1 fw-normal fs-6 mb-2 text-start"><i
                                        class="fas fa-circle dis-color" style="color: #fbbf6d !important"></i> Reprograma cliente</span></div>
                                <div class="col-12 col-md-6"><span
                                    class="badge bg-light border rounded-pill w-100 py-1 fw-normal fs-6 mb-2 text-start"><i
                                        class="fas fa-circle dis-color" style="color: #bf9252 !important"></i> Reprograma empresa</span></div>
                                <div class="col-12 col-md-6"><span
                                    class="badge bg-light border rounded-pill w-100 py-1 fw-normal fs-6 mb-2 text-start"><i
                                        class="fas fa-circle dis-color" style="color: #585956 !important"></i> Realizada</span></div>
                                <div class="col-12 col-md-6"><span
                                    class="badge bg-light border rounded-pill w-100 py-1 fw-normal fs-6 mb-2 text-start"><i
                                        class="fas fa-circle dis-color" style="color: #7e807b !important"></i> Programada</span></div>
                        {{-- <div class="col-12 col-md-6"><span
                                class="badge bg-light border rounded-pill w-100 py-1 fw-normal fs-6 mb-2 text-start"><i
                                    class="fas fa-circle can-color"></i> Cancelado</span></div>
                        <div class="col-12 col-md-6"><span
                                class="badge bg-light border rounded-pill w-100 py-1 fw-normal fs-6 mb-2 text-start"><i
                                    class="fas fa-circle nodis-color"></i> No disponible</span></div> --}}
                    </div>
                </div>
            </div>
        </div>


        <div class="col-sm-12 col-xl-8">
            <div class="card">
                <div class="card-header">
                    <i class="far fa-calendar"></i> Calendario de citas
                </div>
                <div id="calendario-agenda"></div>
            </div>
        </div>
    </div>

    @include('admin.citas.modal')
    @include('admin.citas.modalEdit')
    @include('admin.agenda.showCita')

@endsection

@section('scripts')
    @parent

    <script src="{{ asset('js/festivos.js') }}"></script>

    <script>
        let events = {!! json_encode($events) !!};
        let citas = {!! json_encode($citas) !!};
        let usuario_cliente = {{ $usuario_cliente->id }};

        document.addEventListener('DOMContentLoaded', function() {

            var calendarEl = document.getElementById('calendario-agenda');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                plugins: ['dayGrid', 'timeGrid', 'list', 'moment'],
                header: {
                    left: "prev,next",
                    center: "title",
                    right: "dayGridMonth,listWeek,timeGridDay"
                },
                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
                    week: 'Semana',
                    day: 'Día',
                },
                //Muestra modal al dar clic al evento
                eventClick: function(calEvent, jsEvent, view) {

                    console.log(calEvent.event._def.extendedProps.empresa);

                    var fecha_inicio = moment(calEvent.event.start).format('YYYY-MM-DD HH:mm:ss');
                    var fecha_fin = moment(calEvent.event.end).format('YYYY-MM-DD HH:mm:ss');

                    const fechaActual = moment().format('YYYY-MM-DD HH:mm:ss');
                    const diferenciaDias = moment(fecha_fin).diff(fechaActual, 'days');

                    const ahora = new Date();
                    const horaLimite = new Date(ahora);
                    horaLimite.setHours(18, 0, 0, 0);

                    if (fecha_fin <= (fechaActual)) {
                        // Si la fecha final del evento es anterior a la fecha actual, no hacer nada
                        return;
                    } else if (ahora > horaLimite && diferenciaDias <= 1) {
                        return;
                    }

                    // Evita el clic en el evento con el ID 'evento_no_clicable' que son los Festivos
                    if (calEvent.event.id === 'evento_no_clicable') {
                        return false;
                    }

                     //Busca si la cita esta reservada para mostrar sus datos de la cita
                     citas.forEach(element => {
                        
                        if (element.fecha_inicio.includes(fecha_inicio)) {

                            moment.locale('es');
                            
                            $("#ShowCitaModal #empresa").html('<b> Empresa: </b>' + calEvent.event.extendedProps.empresa);
                            $("#ShowCitaModal #titulo-cita").html('<b> Cita para: </b>' + calEvent.event.extendedProps.empresa);
                            $("#ShowCitaModal #empleado").html('<b> Persona quién cita: </b>' + calEvent.event.extendedProps.empleado);
                            $("#ShowCitaModal #motivo").html(element.motivo);
                            $("#ShowCitaModal #horario").html(
                                '<b> Fecha y hora de cita programada: </b>' + moment(element.fecha_inicio).format('D [de] MMMM [del] Y, HH:mm a')
                                 + ' - ' + moment(element.fecha_fin).format('HH:mm a'));

                            if (element.modalidad_id == 1) {
                                $("#ShowCitaModal #modalidad").html('<a style="color:#42758F;" href="' + element.link + '" target="_blank">' + element.link + '</a>'
                                );
                            } else {
                                $("#ShowCitaModal #modalidad").html(element.direccion);
                            }

                            if (element.observacion != null) {
                                $("#ShowCitaModal #observacion").html('<b> Observación: </b>' +
                                    element.observacion);
                            }

                            $('#ShowCitaModal').modal("show");

                        }

                    });
                },
                weekends: true,
                selectable: true,
                editable: true,
                defaultView: 'dayGridMonth',
                contentHeight: 550,
                locale: 'es',
                views: {
                    week: {
                        type: 'timeGrid',
                        duration: {
                            days: 4
                        }
                    },
                    day: {
                        type: 'dayGrid',
                        duration: {
                            weeks: 4
                        }
                    }
                },
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true,
                },
                slotLabelFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true,
                },
                displayEventEnd: true,
                minTime: '06:00:00',
                maxTime: '21:00:00',
            });

            events.forEach(element => {
                var startDate = new Date(element.start);
                var endDate = new Date(element.end);
                var now = new Date();

                let hasMatch = false;

                for (const cita of citas) {
                    const startCita = new Date(cita.fecha_inicio);

                    //compara si la fecha de la cita es similar a la de la agenda y si el estado es 3 
                    if ((moment(startCita).isSame(startCita, 'day') && cita.estados == 3)) {
                        calendar.addEvent({
                            id: element.id,
                            empresa: element.empresa,
                            empleado: element.empleado,
                            estado: element.estado,
                            start: startDate,
                            end: endDate,
                            backgroundColor: element.backgroundColor,
                            borderColor: element.borderColor,
                            textColor: '#fff',
                            estado: 3
                        });

                        hasMatch = true;
                        break;
                    }

                    //Compara la fecha de la agenda con la de la cita creada si es similar y esta agendada
                    if (moment(startCita).isSame(startCita, 'day') && cita.estados != 3) {
                        calendar.addEvent({
                            id: element.id,
                            empresa: element.empresa,
                            empleado: element.empleado,
                            estado: element.estado,
                            start: startDate,
                            end: endDate,
                            backgroundColor: element.backgroundColor,
                            borderColor: element.borderColor,
                            textColor: '#fff'
                        });

                        hasMatch = true;
                        break;
                    }
                }
            });
            
            calendar.render();

        });

        function closealert() {
            $('#alert').modal('hide');
        }


        $('#modalidadCreate').change(function() {
            var virtual = document.getElementById('virtual');
            var fisica = document.getElementById('fisica');

            if ($(this).val() == 1) {
                fisica.style.display = 'none';
                virtual.style.display = 'block';
            } else {
                virtual.style.display = 'none';
                fisica.style.display = 'block';
            }
        });

        function crearCita() {

            var empleadoCliente = $('#empleado_cliente_id').val();
            var agendaId = $('#agenda_id').val();
            var motivo = $('#motivoCreate').val();
            var link = $('#linkCreate').val();
            var fechaInicio = $('#fecha_inicio').val();
            var fechaFinal = $('#fecha_fin').val();
            var estado = $('#estado').val();
            var direccion = $('#direccionCreate').val();
            var observacion = $('#observacionCreate').val();
            var modalidad_id = $('#modalidadCreate').val();

            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                type: 'POST',
                url: 'citas',
                data: {
                    empleado_cliente_id: empleadoCliente,
                    motivo: motivo,
                    modalidad_id: modalidad_id,
                    agenda_id: agendaId,
                    link: link,
                    fecha_inicio: fechaInicio,
                    fecha_fin: fechaFinal,
                    estados: estado,
                    direccion: direccion,
                    observacion: observacion
                },
                success: function(result) {
                    $('#citaModal').modal('hide');

                    Swal.fire({
                        icon: 'success',
                        title: 'Creación exitosa',
                        text: 'Se ha creado tu cita con exito',
                    }).then((result) => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    $('#validation-errors').html('');
                    $.each(xhr.responseJSON.errors, function(key, value) {
                        $('#validation-errors').append('<div style="color:red;">' + value +
                            '</div>');
                    });
                },
            });
        }

        //trae la informacion del empleado
        function buscarEmpleado(usuario_id, modalidad, motivo, observacion, link, direccion, fecha_inicio,
            fecha_fin,
            cita_id, agenda_id) {

            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                type: 'GET',
                url: 'agenda/EmpleadoCliente/' + usuario_id + '/' + agenda_id,
                success: function(data) {

                    moment.locale("es");

                    document.getElementById('cita_id').value = cita_id;
                    $("#ShowCitaModal #empresa").html('<b> Empresa: </b>' + data.empresa);
                    $("#ShowCitaModal #titulo-cita").html('<b> Cita para: </b>' + data.empresa);
                    $("#ShowCitaModal #empleado").html('<b> Empleado: </b>' + data.nombres);
                    $("#ShowCitaModal #motivo").html('<b> Motivo: </b>' + motivo);
                    $("#ShowCitaModal #horario").html('<b> Fecha y hora de cita programada: </b>' +
                        moment(fecha_inicio).format('D [de] MMMM [del] YYYY, HH:mm a') + ' - ' + moment(
                            fecha_fin).format('HH:mm a'));

                    if (modalidad == 1) {
                        $("#ShowCitaModal #modalidad").html('<b> Virtual: </b>' +
                            '<a style="color:blue; href="' + link + '" target="_blank">' + link +
                            '</a>');
                    } else {
                        $("#ShowCitaModal #modalidad").html('<b> Dirección: </b>' + direccion);
                    }

                    if (observacion != undefined) {
                        $("#ShowCitaModal #observacion").html('<b> Observación: </b>' + observacion);
                    }

                    $('#ShowCitaModal').modal("show");

                },
            });
        }

        function showModalEditarCita() {
            const cita = $('#cita_id').val();
            //oculto el modal que muestra los datos
            $('#ShowCitaModal').modal('hide');

            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                type: 'GET',
                url: 'citas/' + cita + '/edit ',
                success: function(data) {
                    if (data == 0) {

                        Swal.fire({
                            icon: 'error',
                            title: 'Acción no autorizada',
                            text: 'No tienes permitido realizar esta acción',
                        })

                    } else {
                        //muestro el editar cita
                        $('#citaEditar').modal("show");

                        var virtual = document.getElementById('virtualEdit');
                        var fisico = document.getElementById('fisicaEdit');

                        $("#citaEditar #motivoEdit").val(data.motivo);

                        if (data.modalidad_id == 1) {
                            document.getElementById("modalidadEdit").selectedIndex = 1
                            virtual.style.display = 'block';
                            fisico.style.display = 'none';
                        } else {
                            document.getElementById("modalidadEdit").selectedIndex = 2
                            fisico.style.display = 'block';
                            virtual.style.display = 'none';
                        }

                        document.getElementById('citaId').value = cita;
                    }
                },
            });
        }

        $('#modalidadEdit').change(function() {

            var virtual = document.getElementById('virtualEdit');
            var fisica = document.getElementById('fisicaEdit');

            if ($(this).val() == 1) {
                fisica.style.display = 'none';
                virtual.style.display = 'block';

                document.getElementById('linkEdit').value = ""
            } else {
                virtual.style.display = 'none';
                fisica.style.display = 'block';
                document.getElementById('direccionEdit').value = ""
            }
        });

        function editarCita() {
            const cita = $('#citaId').val();
            var motivo = $('#motivoEdit').val();
            var direccion = $('#direccionEdit').val();
            var link = $('#linkEdit').val();
            var observacion = $('#observacionEdit').val();
            var modalidad_id = $('#modalidadEdit').val();

            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                type: 'PUT',
                url: 'citas/' + cita,
                data: {
                    motivo: motivo,
                    modalidad_id: modalidad_id,
                    direccion: direccion,
                    observacion: observacion,
                    link: link,

                },
                success: function(data) {
                    if (data == true) {
                        $('#citaEditar').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Actualización exitosa',
                            text: 'Se ha actualizado tu cita con exito',
                        }).then((result) => {
                            location.reload();
                        });

                    }
                },
                error: function(xhr) {
                    $('#validation-errors-edit').html('');
                    $.each(xhr.responseJSON.errors, function(key, value) {
                        $('#validation-errors-edit').append('<div style="color:red;">' + value +
                            '</div>');
                    });
                },
            });
        }

        function cancelarCita() {
            const cita_id = $('#cita_id').val();
            $('#ShowCitaModal').modal('hide');

            Swal.fire({
                title: '¿Deseas cancelar la cita?',
                text: "No podrás revertir esta cita",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                        },
                        type: 'DELETE',
                        url: 'citas/' + cita_id,
                        success: function(data) {
                            if (data == true) {
                                $('#ShowCitaModal').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Cancelación exitosa',
                                    text: 'Se cancelado tu cita con exito',
                                }).then((result) => {
                                    location.reload();
                                });

                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Acción no autorizada',
                                    text: 'No tienes permitido realizar esta acción',
                                })
                            }
                        },
                    });
                }
            })

        }
    </script>
@endsection
