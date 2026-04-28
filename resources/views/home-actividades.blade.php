@extends('layouts.admin')
@section('title', 'Home')
@section('library')
    @include('cdn.fullcalendar-head')
@endsection
@section('content')

    <style>
        .fc td {
            border: none;
            border-top: 1px solid #e9ecef;
            border-bottom: none;
            border-left: 1px solid #e9ecef;
            border-right: 1px solid #e9ecef;
        }
    </style>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header fs-5">
                    <i class="far fa-calendar"></i> Calendario de actividades y
                    requerimientos @if ($nombreempresa != '' && $nombreempresa != 'vacio')
                        {{ '- ' . $nombreempresa->razon_social }}
                    @endif
                </div>

                <div class="card-body py-0 pl-0" style="padding-right: 7px">
                    <div class="row justify-content-center d-block d-sm-flex">
                        <div class="col-12 col-sm-5 col-xl-4">
                            @if ($cantEvents != '' && $cantEvents != 'vacio')
                                <div class="row">
                                    <div class="col-lg-12 py-2 px-3">
                                        <div class="alert alert-primary alert-dismissible fade show mb-0" role="alert">
                                            <i class="fas fa-info-circle"></i>
                                            {{ 'Se encontraron ' . $cantEvents . ' actividades ' }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 py-2 px-3">
                                        <div class="alert alert-primary alert-dismissible fade show mb-0" role="alert">
                                            <i class="fas fa-info-circle"></i>
                                            {{ 'Se encontraron ' . $cantRequerimientos . ' requerimientos ' }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <p class="px-4 pt-3">
                                <span class="fs-5">
                                    <i class="fas fa-search"></i> Filtrar actividades
                                </span><br>
                                seleccione una empresa y un responsable para filtrar las actividades. <b><small>
                                        (Para reiniciar el filtro selecciona cualquier empresa)</small></b>
                            </p>

                            @livewireScripts

                            @can('FILTRAR_ACTIVIDADES')

                                <form action="{{ route('admin.filtro.cliente') }}" method="POST" id="eventos">
                                    @csrf
                                    <div class="card-body">
                                        @livewire('empresa-filter')
                                        @if (Auth::user()->role_id == 1)
                                            <div class="card-header border-0 bg-transparent text-dark pb-2 pl-0">
                                                <div class="fs-5">
                                                    <i class="fas fa-search"></i> Usuario que creó la actividad.
                                                </div>
                                            </div>

                                            <div class="form-floating">
                                                <input class="form-control" list="datalistOptionsact"
                                                    placeholder="Escribe Para Buscar..." name="usercreaactId" id="usercreaactId"
                                                    autocomplete="off">
                                                <datalist id="datalistOptionsact">
                                                    <option value="">Todos los Usuarios</option>
                                                    @foreach ($usercreaact as $responsable)
                                                        <option
                                                            value="{{ $responsable->id }} - {{ $responsable->nombres . ' ' . $responsable->apellidos }}"
                                                            data-id="{{ $responsable->id }}">

                                                        </option>
                                                    @endforeach
                                                </datalist>
                                                <label class="fw-normal">Usuario creo actividad</label>
                                            </div>
                                        @endif
                                    </div>
                                </form>

                                <div class="card-body text-center border-0 p-0 w-100 mb-5 d-flex gap-1 justify-content-center">
                                    <button class="btn btn-save btn-radius" form="eventos" type="submit">
                                        <i class="fas fa-filter"></i> Aplicar filtro
                                    </button>
                                    <button class="btn btn-save btn-radius"
                                        onclick="window.location.href='/admin/calendario-index'">
                                        <i class="fa-solid fa-rotate fa-lg" style="color: #ffffff;"></i>
                                        Reiniciar filtro
                                    </button>
                                </div>
                            @endcan

                            <p class="text-center fs-5">Estados actividades</p>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <span
                                            class="badge bg-light border rounded-pill w-100 py-1 fw-normal fs-6 mb-2 text-start"><i
                                                class="fas fa-circle act-color"></i> Actividades</span>
                                    </div>
                                    <div class="col-12 col-md-6"> <span
                                            class="badge bg-light border rounded-pill w-100 py-1 fw-normal fs-6 mb-2 text-start"><i
                                                class="fas fa-circle can-color"></i> Canceladas</span></div>
                                    <div class="col-12 col-md-6"> <span
                                            class="badge bg-light border rounded-pill w-100 py-1 fw-normal fs-6 mb-2 text-start"><i
                                                class="fas fa-circle repr-color"></i> Reprogramadas</span></div>
                                    <div class="col-12 col-md-6"><span
                                            class="badge bg-light border rounded-pill w-100 py-1 fw-normal fs-6 mb-2 text-start"><i
                                                class="fas fa-circle ven-color"></i> Vencidas</span></div>
                                    <div class="col-12 col-md-6"><span
                                            class="badge bg-light border rounded-pill w-100 py-1 fw-normal fs-6 mb-2 text-start"><i
                                                class="fas fa-circle fin-color"></i> Finalizadas</span></div>
                                    <div class="col-12 col-md-6"><span
                                            class="badge bg-light border rounded-pill w-100 py-1 fw-normal fs-6 mb-2 text-start"><i
                                                class="fas fa-circle cump-color"></i> Cumplidas</span></div>
                                </div>
                            </div>

                            <p class="text-center fs-5">Estados requerimientos</p>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-6"><span
                                            class="badge bg-light border rounded-pill w-100 py-1 fw-normal fs-6 mb-2 text-start"><i
                                                class="fas fa-circle acep-color"></i> Aceptados</span></div>
                                    <div class="col-12 col-md-6"><span
                                            class="badge bg-light border rounded-pill w-100 py-1 fw-normal fs-6 mb-2 text-start"><i
                                                class="fas fa-circle proc-color"></i> En proceso</span></div>
                                    <div class="col-12 col-md-6"><span
                                            class="badge bg-light border rounded-pill w-100 py-1 fw-normal fs-6 mb-2 text-start"><i
                                                class="fas fa-circle finreq-color"></i> Finalizados</span></div>
                                </div>
                            </div>
                        </div>
                        <style>
                            #calendar-border {
                                border: none;
                                border-left: 1px solid #d4d4d4;
                            }
                        </style>
                        <div class="col-12 col-sm-7 col-xl-8 px-0" id="calendar-border">
                            <div id="calendario-actividades"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('scripts')
    @parent
    <script src="{{ asset('js/actividadCalendario/calendario.js') }}" defer></script>
    <script>
        let usuario_logged = {!! Auth::user()->id !!};
        let events = {!! json_encode($events) !!};
        let event_requerimientos = {!! json_encode($event_requerimientos) !!};
        let festivos = {!! json_encode($festivos) !!};

        //concatenar los eventos de requerimientos y actividades
        if (event_requerimientos != null && festivos != null) {
            events = events.concat(event_requerimientos).concat(festivos);
        }


        document.addEventListener('DOMContentLoaded', function() {

            var calendarEl = document.getElementById('calendario-actividades');

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
                events: function(info, successCallback, failureCallback) {
                    var rutaActual = window.location.pathname;
                    if (rutaActual == '/admin/calendario-index') {
                        $.ajax({
                            url: '/admin/calendario-index/events', // reemplaza esto con la ruta a tu API
                            type: 'GET',
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                            },
                            dataType: 'json',
                            data: {
                                start_date: moment(info.startStr).format('Y-MM-DD'),
                                end_date: moment(info.endStr).format('Y-MM-DD')
                            },
                            success: function(eventos) {
                                successCallback(eventos);
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.log("Error: " + textStatus);
                                console.log("Información detallada: " + errorThrown);
                                console.log(jqXHR);
                            }
                        });
                    } else {
                        successCallback(events);
                    }
                },
                weekends: true,
                selectable: true,
                editable: true,
                //Muestra modal al dar clic al evento
                eventClick: function(calEvent, jsEvent, view) {

                    document.getElementById('actividad_id').value = calEvent.event.id;
                    document.getElementById('id_actividad_calendario').value = calEvent.event.id;
                    document.getElementById('requerimiento_id').value = calEvent.event.id;

                    if (calEvent.event._def.extendedProps.notificado == 1) {
                        document.getElementById('form-notificacion').checked = true;
                    }


                    //roles que pueden reasignar y reportar una actividad
                    var roles = [1];

                    //Si el usuario logueado es igual al responsable de la actividad dejar reportar si no mostrar el boton reasignar
                    if (roles.includes(calEvent.event._def.extendedProps.user_rol)) {
                        document.getElementById('button_reasignar').style.display = 'block';
                        document.getElementById('button_reportar').style.display = 'block';
                    } else if (calEvent.event._def.extendedProps.responsable === usuario_logged) {
                        document.getElementById('button_reportar').style.display = 'block';
                        document.getElementById('button_reasignar').style.display = 'none';
                    } else {
                        document.getElementById('button_reportar').style.display = 'none';
                        document.getElementById('button_reasignar').style.display = 'block';
                    }

                    $('#actividadModal #titulo-actividad').html('<b>' + calEvent.event.title + '</b>');
                    $('#actividadModal #descripcion').html(calEvent.event._def.extendedProps
                        .description);

                    //toma el color de requerimientos y oculta el boton de reporte actividades
                    if (calEvent.event.backgroundColor == '#ffc107' ||
                        calEvent.event.backgroundColor == '#17a2b8' ||
                        calEvent.event.backgroundColor == '#007bff') {
                        document.getElementById('button_reportar').style.display = 'none';
                        document.getElementById('button_reasignar').style.display = 'none';
                    }


                    //Si el usuario logueado es igual al responsable de el requerimiento dejar reportar si no mostrar el boton ver requerimientos
                    if (calEvent.event._def.extendedProps.responsable === usuario_logged) {
                        document.getElementById('button_ver').style.display = 'none';
                        document.getElementById('button_reportar_req').style.display = 'block';
                    } else {
                        document.getElementById('button_reportar_req').style.display = 'none';
                        document.getElementById('button_ver').style.display = 'block';
                    }


                    //toma el color de actividades y oculta el boton de reporte requerimientos
                    if (calEvent.event.backgroundColor == '#f5ca05' ||
                        calEvent.event.backgroundColor == '#B695C0' ||
                        calEvent.event.backgroundColor == '#0da13c' ||
                        calEvent.event.backgroundColor == '#fa5661' ||
                        calEvent.event.backgroundColor == '#000' ||
                        calEvent.event.backgroundColor == '#a7a7a7') {

                        document.getElementById('button_ver').style.display = 'none';
                        document.getElementById('button_reportar_req').style.display = 'none';
                    }

                    //si el requerimiento esta finalizado no mostrar boton reportar
                    if (calEvent.event.backgroundColor == '#17a2b8') {
                        document.getElementById('button_reportar_req').style.display = 'none';
                    }

                    if (calEvent.event.backgroundColor == '#0da13c' ||
                        calEvent.event.backgroundColor == '#000') {
                        document.getElementById('button_reportar').style.display = 'none';
                        document.getElementById('button_reasignar').style.display = 'none';
                        document.getElementById('button_ver').style.display = 'none';
                        document.getElementById('button_reportar_req').style.display = 'none';
                    }

                    if (calEvent.event.backgroundColor == '#0da13c' || calEvent.event.backgroundColor ==
                        '#f5ca05') {
                        document.getElementById('notificaciones').style.display = 'block';
                    } else {
                        document.getElementById('notificaciones').style.display = 'none';
                    }


                    // Evita el clic en el evento con el ID 'evento_no_clicable' que son los Festivos
                    if (calEvent.event.id === 'evento_no_clicable') {
                        return false;
                    }

                    $('#actividadModal').modal("show");

                },
                defaultView: 'dayGridMonth',
                contentHeight: 550,
                locale: 'es',
                views: {
                    month: {
                        eventLimit: 4 // muestra un máximo de 3 eventos por día en la vista de mes
                    },
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

            calendar.render();

        });

        function reportarActividad() {
            var actividad = $('#actividad_id').val();
            var route = "{{ route('admin.reporte.index', 'actividad_id') }}";
            route = route.replace('actividad_id', actividad);

            location.href = route;
        }

        function reasignarActividad() {
            var actividad = $('#actividad_id').val();
            var route = "{{ route('admin.reporte.reasignar', 'actividad_id') }}";
            route = route.replace('actividad_id', actividad);

            location.href = route;
        }

        function verReq() {

            location.href = "{{ route('admin.requerimientos.empleado.index') }}";
        }

        function reportarReq() {
            var requerimiento_id = $('#requerimiento_id').val();
            var route = "{{ route('admin.seguimientos.cliente.edit', 'requerimiento_id') }}";
            route = route.replace('requerimiento_id', requerimiento_id);

            location.href = route;
        }

        // Evento para cambios en usercreaactId
        document.querySelector('#usercreaactId').addEventListener('input', function() {
            // elimina los datos de los input de empresa y responsable 
            document.querySelector('#empresa_id').value = '';
            document.querySelector('#responsable_id').value = '';
        });
    </script>

@endsection
