<div class="row">
    <div class="col-lg-4">
        @can('ACCEDER_CALENDARIO_TRIBUTARIO')
            <div class="card">
                @if (
                    $cantEvents != '' &&
                        $cantEvents != 'vacio' &&
                        (!empty($nombreempresa) ||
                            (!empty($nombreobligacion) && $nombreobligacion != 'Sin datos') ||
                            !empty($nombreresponsable)))
                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="alert alert-primary alert-dismissible fade show mb-0" role="alert">
                                    <i class="fas fa-info-circle"></i>
                                    @if (!empty($nombreempresa))
                                        {{ 'Se encontraron ' . $cantEvents . ' obligaciones para:' . $nombreempresa->razon_social }}
                                    @elseif (!empty($nombreobligacion) && $nombreobligacion != 'Sin datos')
                                        {{ 'Se encontraron ' . $cantEvents . ' obligaciones para: ' . $nombreobligacion }}
                                    @elseif (!empty($nombreresponsable) && $nombreresponsable != 'vacio')
                                        {{ 'Se encontraron ' . $cantEvents . ' obligaciones para: ' . $nombreresponsable->nombre_completo }}
                                    @endif
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="card-header border-0 bg-transparent text-dark pb-0">
                    <div class="fs-5">
                        <i class="fas fa-search"></i> Filtrar Obligaciones
                    </div>
                </div>
                <div class="card-body py-0 mt-0 ">
                    <p>
                        seleccione una empresa para mostrar la información.
                    </p>
                </div>


                <form action="{{ route('admin.calendario.calendarioobligaciones') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div>
                            <div class="form-floating mb-3">
                                <input class="form-control" list="datalistOptions" placeholder="Escribe Para Buscar..."
                                    name="empresa_id" id="empresa_id" wire:model.debounce.500ms="empresa"
                                    oninput="updateCodigoObligacion(this.value)" autocomplete="off">
                                <datalist id="datalistOptions">
                                    @foreach ($empresas as $empresa)
                                        <option value="{{ $empresa->id }} - {{ $empresa->razon_social }}"
                                            data-id="{{ $empresa->id }}"></option>
                                    @endforeach
                                </datalist>
                                <label class="fw-normal">Empresa</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input class="form-control" list="datalistOptionsobligaciones"
                                    placeholder="Escribe Para Buscar..." name="codigoobligacion" id="codigoobligacion"
                                    oninput="updateEmpresa(this.value)" autocomplete="off">
                                <datalist id="datalistOptionsobligaciones">
                                    @foreach ($obligaciones as $obligacion)
                                        <option value="{{ $obligacion->codigo }} - {{ $obligacion->nombre }}"
                                            data-id="{{ $obligacion->codigo }}"></option>
                                    @endforeach
                                </datalist>
                                <label class="fw-normal">Obligacion DIAN</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input class="form-control" list="datalistOptionsobligacionesm"
                                    placeholder="Escribe Para Buscar..." name="codigoobligacionm" id="codigoobligacionm"
                                    oninput="updateEmpresa(this.value)" autocomplete="off">
                                <datalist id="datalistOptionsobligacionesm">
                                    @foreach ($obligacionesmunicipales as $obligacionm)
                                        <option value="{{ $obligacionm->codigo }} - {{ $obligacionm->nombre }}"
                                            data-id="{{ $obligacionm->codigo }}"></option>
                                    @endforeach
                                </datalist>
                                <label class="fw-normal">Obligaciones Municipales</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input class="form-control" list="datalistOptionsotrasentidades"
                                    placeholder="Escribe Para Buscar..." name="codigootraentidades" id="codigootraentidades"
                                    oninput="updateEmpresa(this.value)" autocomplete="off">
                                <datalist id="datalistOptionsotrasentidades">
                                    @foreach ($otrasentidades as $otraentidad)
                                        <option value="{{ $otraentidad->codigo }} - {{ $otraentidad->nombre }}"
                                            data-id="{{ $otraentidad->codigo }}"></option>
                                    @endforeach
                                </datalist>
                                <label class="fw-normal">Otras Entidades</label>
                            </div>
                            @if (Auth::user()->role_id == 1)
                                <div class="form-floating mb-3">
                                    <input class="form-control" list="datalistOptionresponsables"
                                        placeholder="Escribe Para Buscar..." name="responsable" id="responsable"
                                        oninput="updateEmpresa(this.value)" autocomplete="off">
                                    <datalist id="datalistOptionresponsables">
                                        @foreach ($responsables as $id => $nombre)
                                            <option value="{{ $id }}-{{ $nombre }}"
                                                data-id="{{ $id }}"
                                                {{ $empresa->empleados != null && in_array($id, json_decode($empresa->empleados)) ? 'selected' : '' }}>
                                                {{ $id . ' - ' . $nombre }}
                                            </option>
                                        @endforeach
                                    </datalist>
                                    <label class="fw-normal">Responsable</label>
                                </div>
                            @endif
                        </div>

                    </div>
                    <div class="card-footer text-end bg-transparent border-0 mb-3">
                        <button type="submit" class="btn btn-save btn-radius px-4 mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" style="fill: white" height="1em"
                                viewBox="0 0 512 512">
                                <path
                                    d="M487.976 0H24.028C2.71 0-8.047 25.866 7.058 40.971L192 225.941V432c0 7.831 3.821 15.17 10.237 19.662l80 55.98C298.02 518.69 320 507.493 320 487.98V225.941l184.947-184.97C520.021 25.896 509.338 0 487.976 0z" />
                            </svg>
                            Aplicar filtro
                        </button>
                        <button onclick="location.reload()" class="btn btn-save btn-radius px-4 mb-3">
                            <i class="fa-solid fa-rotate fa-lg" style="color: #ffffff;"></i>
                            Reiniciar filtro
                        </button>
                    </div>
                </form>

            </div>
        @endcan

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <span class="badge bg-light border rounded-pill w-100 py-1 fw-normal fs-6 mb-2 text-start">
                            <i class="fas fa-circle req-color"></i>
                            <span class="text-wrap">Municipales</span>
                        </span>
                    </div>
                    <div class="col-12 col-md-6">
                        <span class="badge bg-light border rounded-pill w-100 py-1 fw-normal fs-6 mb-2 text-start">
                            <i class="fas fa-circle fin-color"></i>
                            <span class="text-wrap">DIAN</span>
                        </span>
                    </div>
                    <div class="col-12 col-md-6">
                        <span class="badge bg-light border rounded-pill w-100 py-1 fw-normal fs-6 mb-2 text-start">
                            <i class="fas fa-circle proc-color"></i>
                            <span class="text-wrap">Presentado</span>
                        </span>
                    </div>
                    <div class="col-12 col-md-6">
                        <span class="badge bg-light border rounded-pill w-100 py-1 fw-normal fs-6 mb-2 text-start">
                            <i class="fas fa-circle entidades-color"></i>
                            <span class="text-wrap">Otros</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="col">
        <div class="card">
            <div class="card-header fs-5 d-flex justify-content-between align-items-center" id="titulos">
                <i class="far fa-calendar">&nbsp;</i> Calendario tributario
                @if ($nombreempresa != '' && $nombreempresa != 'vacio')
                    {{ $nombreempresa->razon_social }}
                @elseif(!empty($nombreobligacion) && $nombreobligacion != 'Sin datos')
                    {{ $nombreobligacion }}
                @elseif($nombreresponsable != '' && !empty($nombreresponsable) && $nombreresponsable != 'vacio')
                    {{ $nombreresponsable->nombre_completo }}
                @else
                    {{ ' ' }}
                @endif
                <div class="ml-auto d-flex align-items-center">
                    <!-- Botón para abrir el modal -->
                    <button type="button" class="btn btn-sm btn-save btn-radius" data-bs-toggle="modal"
                        data-bs-target="#modalDescarga">
                        <i class="fa-solid fa-file-arrow-down fa-lg"></i>
                    </button>
                    @include('modal.informetributario-modal')
                    <div id="correo" style="display: none;margin-left: 10px;">
                        <input type="hidden" id="user_rol" value="{{ Auth::user()->role_id }}">
                        <form action="{{ route('admin.calendario.correofechas') }}" method="POST" id="correoForm">
                            @csrf
                            <input type="hidden" name="eventsCorreo" id="events-inputCorreo">
                            <input type="hidden" id="currentMonthDateCorreo" name="currentMonthDateCorreo"
                                value="">
                            <input type="hidden" name="empresapdfCorreo" id="empresapdfCorreo">
                            <input type="hidden" name="opcioncreacion" id="opcioncreacionCorreo" value="2">
                            <button type="submit" class="btn btn-sm btn-save btn-radius" id="enviarCorreoButton"><i
                                    class="fa-solid fa-envelope"></i></button>
                        </form>
                    </div>
                    <div id="download-pdf" style="display: none; margin-left: 10px;">
                        <form action="{{ route('admin.calendario.descargarPDF') }}" method="POST" target="_blank">
                            @csrf
                            <input type="hidden" name="events" id="events-input">
                            <input type="hidden" id="currentMonthDate" name="currentMonthDate" value="">
                            <input type="hidden" name="empresapdf" id="empresapdf">
                            <input type="hidden" name="opcioncreacion" id="opcioncreacion" value="1">
                            <button type="submit" class="btn btn-sm btn-save btn-radius"><i
                                    class="fa-solid fa-file-pdf fa-lg"></i></button>
                        </form>
                    </div>
                </div>
            </div>
            @include('modal.tributario-modal')
            <div id="calendario-tributario"></div>
        </div>
    </div>
</div>
@section('scripts')
    @parent

    <script>
        let events = {!! json_encode($events) !!};
        let events2 = {!! json_encode($events2) !!};
        let event_requerimientos = {!! json_encode($event_requerimientos) !!};
        let festivos = {!! json_encode($festivos) !!};
        let usuario_logged = {!! Auth::user()->id !!};
        let allEvents = events.concat(events2, event_requerimientos, festivos);
        document.addEventListener('DOMContentLoaded', function() {

            var calendarEl = document.getElementById('calendario-tributario');
            var checkboxEl = document.getElementById('flexSwitchCheckChecked');
            var checkboxEl2 = document.getElementById('flexSwitchCheckChecked2');
            var checkboxEl3 = document.getElementById('flexSwitchCheckChecked3');
            var checkboxcorreo = document.getElementById('notificarCorreo');
            var checkboxwhatsapp = document.getElementById('notificarwhatsapp');
            var currentMonthDateInput = document.getElementById('currentMonthDate');
            var currentMonthDateInputCorreo = document.getElementById('currentMonthDateCorreo');
            var checkboxrevisoria = document.getElementById('notificarrevisoria');

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
                events: allEvents,
                weekends: true,
                selectable: true,
                editable: true,
                //Muestra modal al dar clic al evento
                eventClick: function(calEvent, jsEvent, view) {
                    document.getElementById('tributario_id').value = calEvent.event.id;
                    document.getElementById('id').value = calEvent.event.id;
                    $('#tributarioModal #titulo-tributario').html('<b>' + calEvent.event.title +
                        '</b>');
                    $('#tributarioModal #descripcion_t').html(calEvent.event._def.extendedProps
                        .description);
                    if (calEvent.event.extendedProps.tipo === 1) {
                        $('#divobservacion').css('display', 'block');
                        $('#flexSwitchCheckChecked').css('display', 'block');
                        $('#divobservacion2').css('display', 'none');
                        $('#divobservacion3').css('display', 'none');
                        $('#flexSwitchCheckChecked2').css('display', 'none');
                        $('#flexSwitchCheckChecked3').css('display', 'none');
                        $('#flexlabel2').css('display', 'none');
                        $('#flexlabel').css('display', 'block');
                        $('#flexlabel3').css('display', 'none');
                        $('#tributarioModal #observacion_id').val(calEvent.event.extendedProps
                            .observacion);
                        // Configura el contenido del modal con el checkbox "Revisado"
                        // console.log(calEvent.event.extendedProps.revision)
                        if (calEvent.event.extendedProps.revision !== null) {
                            checkboxEl.checked = true;
                        } else {
                            checkboxEl.checked = false;
                        }

                        // Actualiza la función onclick del checkbox
                        checkboxEl.onclick = function() {
                            var observacion = $('#tributarioModal #observacion_id').val();
                            // Verificar si el valor está vacío y asignar null si es el caso
                            observacion = observacion.trim() !== '' ? observacion : 'null';
                            marcarRevisado(calEvent.event.id, this.checked, observacion);
                        };
                    } else if (calEvent.event.extendedProps.tipo === 2) {
                        $('#divobservacion').css('display', 'none');
                        $('#flexSwitchCheckChecked').css('display', 'none');
                        $('#divobservacion2').css('display', 'block');
                        $('#divobservacion3').css('display', 'none');
                        $('#flexSwitchCheckChecked2').css('display', 'block');
                        $('#flexSwitchCheckChecked3').css('display', 'none');
                        $('#flexlabel2').css('display', 'block');
                        $('#flexlabel').css('display', 'none');
                        $('#flexlabel3').css('display', 'none');
                        $('#tributarioModal #observacion_id2').val(calEvent.event.extendedProps
                            .observacion);
                        // Configura el contenido del modal con el checkbox "Revisado"
                        // console.log(calEvent.event.extendedProps.revision)
                        if (calEvent.event.extendedProps.revision !== null) {
                            checkboxEl2.checked = true;
                        } else {
                            checkboxEl2.checked = false;
                        }

                        // Actualiza la función onclick del checkbox
                        checkboxEl2.onclick = function() {
                            var observacion = $('#tributarioModal #observacion_id2').val();
                            // Verificar si el valor está vacío y asignar null si es el caso
                            observacion = observacion.trim() !== '' ? observacion : 'null';
                            console.log(calEvent.event.id);
                            marcarRevisado2(calEvent.event.id, this.checked, observacion);
                        };
                        // $('#divobservacion2').css('display', 'none');
                        // $('#boton').css('display', 'none');
                    } else if (calEvent.event.extendedProps.tipo === 3) {
                        $('#divobservacion').css('display', 'none');
                        $('#flexSwitchCheckChecked').css('display', 'none');
                        $('#divobservacion2').css('display', 'none');
                        $('#divobservacion3').css('display', 'block');
                        $('#flexSwitchCheckChecked2').css('display', 'none');
                        $('#flexSwitchCheckChecked3').css('display', 'block');
                        $('#flexlabel2').css('display', 'none');
                        $('#flexlabel').css('display', 'none');
                        $('#flexlabel3').css('display', 'block');
                        $('#tributarioModal #observacion_id3').val(calEvent.event.extendedProps
                            .observacion);
                        // Configura el contenido del modal con el checkbox "Revisado"
                        // console.log(calEvent.event.extendedProps.revision)
                        if (calEvent.event.extendedProps.revision !== null) {
                            checkboxEl3.checked = true;
                        } else {
                            checkboxEl3.checked = false;
                        }

                        // Actualiza la función onclick del checkbox
                        checkboxEl3.onclick = function() {
                            var observacion = $('#tributarioModal #observacion_id3').val();
                            // Verificar si el valor está vacío y asignar null si es el caso
                            observacion = observacion.trim() !== '' ? observacion : 'null';
                            console.log(calEvent.event.id);
                            marcarRevisado3(calEvent.event.id, this.checked, observacion);
                        };
                        // $('#divobservacion2').css('display', 'none');
                        // $('#boton').css('display', 'none');
                    }

                    if (calEvent.event.id === 'evento_no_clicable') {
                        return false;
                    }
                    // Configurar el checkbox de WhatsApp
                    let checkboxwspp = document.getElementById('notificarwhatsapp');
                    checkboxwspp = clearEventListeners(checkboxwspp);
                    if (calEvent.event.extendedProps.whatsapp !== null) {
                        checkboxwspp.checked = true;
                    } else {
                        checkboxwspp.checked = false;
                    }

                    let checkboxrevisor = document.getElementById('notificarrevisoria');
                    checkboxrevisor = clearEventListeners(checkboxrevisor);
                    if (calEvent.event.extendedProps.revisor !== null) {
                        checkboxrevisor.checked = true;
                    } else {
                        checkboxrevisor.checked = false;
                    }
                    // Función de notificación
                    const notify = function() {
                        notificarwhatsapp(calEvent.event.extendedProps.obligacion, calEvent.event
                            .id, calEvent.event.extendedProps.empresa);
                    };
                    const notifyrevisor = function() {
                        notificarrevisoria(calEvent.event.extendedProps.obligacion, calEvent.event
                            .id, calEvent.event.extendedProps.empresa, calEvent.event
                            .extendedProps.fecha);
                    };
                    // Agregar el evento change al checkbox
                    checkboxwspp.addEventListener('change', notify);
                    checkboxrevisor.addEventListener('change', notifyrevisor);
                    // checkboxwspp.addEventListener('change', function() {
                    //     notificarwhatsapp(calEvent.event.extendedProps.obligacion,calEvent.event.id,calEvent.event.extendedProps.empresa)
                    // })
                    $('#tributarioModal').modal("show");
                    const checkbox = document.getElementById('notificarCorreo');
                    const label = document.getElementById('flexlabel4');
                    const iconoCorreo = '<i class="fa-solid fa-envelope"></i>';

                    if (checkbox && label) {
                        checkbox.addEventListener('change', function() {
                            if (this.checked) {
                                label.innerHTML = 'Notificado ' + iconoCorreo;
                            } else {
                                label.innerHTML = 'Notificar ' + iconoCorreo;
                            }
                        });

                        if (calEvent.event.extendedProps.correo !== null) {
                            checkbox.checked = true;
                            label.innerHTML = 'Notificado ' + iconoCorreo;
                        } else {
                            checkbox.checked = false;
                            label.innerHTML = 'Notificar ' + iconoCorreo;
                        }
                    } else {
                        console.error('Checkbox or label element not found');
                    }
                    ///correo revisoria fiscal 
                    const checkboxrevisoria = document.getElementById('notificarrevisoria');
                    const labelrevisoria = document.getElementById('flexlabel6');
                    const iconoCorreorevisoria = '<i class="fa-solid fa-envelope"></i>';

                    if (checkboxrevisoria && labelrevisoria) {
                        checkboxrevisoria.addEventListener('change', function() {
                            if (this.checked) {
                                labelrevisoria.innerHTML = 'Revisoria ' + iconoCorreo;
                            } else {
                                labelrevisoria.innerHTML = 'Revisoria ' + iconoCorreo;
                            }
                        });

                        if (calEvent.event.extendedProps.revisor !== null) {
                            checkboxrevisoria.checked = true;
                            labelrevisoria.innerHTML = 'Revisoria ' + iconoCorreo;
                        } else {
                            checkboxrevisoria.checked = false;
                            labelrevisoria.innerHTML = 'Revisoria ' + iconoCorreo;
                        }
                    } else {
                        console.error('Checkbox or label element not found');
                    }

                },
                defaultView: 'dayGridMonth',
                contentHeight: 550,
                locale: 'es',
                datesRender: function(info) {
                    // Obtener el primer día del mes visible
                    var currentDate = info.view.currentStart;
                    var year = currentDate.getFullYear();
                    var month = ('0' + (currentDate.getMonth() + 1)).slice(-
                        2); // Añadir cero delante si es necesario
                    var day = '01'; // Primer día del mes

                    // Formatear la fecha en el formato deseado
                    var formattedDate = `${year}-${month}-${day}`;

                    // Actualizar el valor del input
                    currentMonthDateInput.value = formattedDate;
                    currentMonthDateInputCorreo.value = formattedDate;
                },
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
            //pdf 
            let allEventspdf = {
                events: events,
                events2: events2,
                event_requerimientos: event_requerimientos
            };
            var nombreEmpresa = {!! json_encode($nombreempresa != '' && $nombreempresa != 'vacio') !!};
            if (allEventspdf && nombreEmpresa != false) {
                document.getElementById('download-pdf').style.display = 'block';
                document.getElementById('events-input').value = JSON.stringify(allEventspdf);
                document.getElementById('empresapdf').value = {!! $nombreempresa != '' && $nombreempresa != 'vacio' ? json_encode($nombreempresa->razon_social) : "''" !!};
                var userRol = document.getElementById('user_rol').value;
                // Mostrar el botón de correo solo si el rol del usuario no es 7
                if (userRol != 7) {
                    document.getElementById('correo').style.display = 'block';
                }
                document.getElementById('events-inputCorreo').value = JSON.stringify(allEventspdf);
                document.getElementById('empresapdfCorreo').value = {!! $nombreempresa != '' && $nombreempresa != 'vacio' ? json_encode($nombreempresa->razon_social) : "''" !!};
            }

            function marcarRevisado(eventId, check, observacion) {
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    url: '/admin/calendariotributario/marcar-revisado',
                    method: 'POST',
                    data: {
                        id: eventId,
                        check: check,
                        observacion: observacion
                    },
                    dataType: 'json',
                    success: function(response) {
                        checkboxEl.checked = response.fecha_revision !== null;
                        calendar.refetchEvents();
                        window.location.reload();
                    },
                    error: function(error) {
                        console.error('Error al marcar como revisado:', error);
                    }
                });
            }

            function marcarRevisado2(eventId, check, observacion) {
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    url: '/admin/calendariotributario/marcar-revisado2',
                    method: 'POST',
                    data: {
                        id: eventId,
                        check: check,
                        observacion: observacion
                    },
                    dataType: 'json',
                    success: function(response) {
                        // Actualiza el calendario después de marcar como "Revisado"
                        checkboxEl.checked = response.fecha_revision !== null;
                        calendar.refetchEvents();
                        window.location.reload();
                    },
                    error: function(error) {
                        console.error('Error al marcar como revisado:', error);
                    }
                });
            }

            function marcarRevisado3(eventId, check, observacion) {
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    url: '/admin/calendariotributario/marcar-revisado3',
                    method: 'POST',
                    data: {
                        id: eventId,
                        check: check,
                        observacion: observacion
                    },
                    dataType: 'json',
                    success: function(response) {
                        // Actualiza el calendario después de marcar como "Revisado"
                        checkboxEl.checked = response.fecha_revision !== null;
                        calendar.refetchEvents();
                        window.location.reload();
                    },
                    error: function(error) {
                        console.error('Error al marcar como revisado:', error);
                    }
                });
            }

            function notificarwhatsapp(nombre, id, empresa) {

                const archivoInput = document.getElementById('notificacion-whatsapp');
                const notificarwhastappCheckbox = document.getElementById('notificarwhatsapp');
                const idObligacion = document.getElementById('id_obligacion');
                const empresaObligacion = document.getElementById('empresa_obligacion')
                const nombreObligacion = document.getElementById('nombre_obligacion');

                if (notificarwhastappCheckbox.checked) {
                    archivoInput.style.display = 'block';
                    idObligacion.value = id;
                    empresaObligacion.value = empresa;
                    nombreObligacion.value = nombre;
                } else {
                    archivoInput.style.display = 'none';
                    idObligacion.value = '';
                    empresaObligacion.value = '';
                    nombreObligacion.value = '';
                }
            }

            document.getElementById('notificacion-whatsapp').addEventListener('submit', function(event) {
                console.log('entro');
                var formulario = document.getElementById('notificacion-whatsapp');
                event.preventDefault(); // Evitar el envío normal del formulario
                var formData = new FormData(formulario); // Crear un objeto FormData

                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    url: $(this).attr('action'), // URL del endpoint
                    data: formData,
                    method: 'POST',
                    dataType: 'json',
                    contentType: false, // No establecer el tipo de contenido
                    processData: false, // No procesar los datos
                    success: function(response) {
                        Swal.fire({
                            title: 'Enviado',
                            text: 'Mensaje enviado con éxito',
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                calendar.refetchEvents();
                                window.location.reload();
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Error',
                            text: 'Ocurrió un error al enviar el mensaje',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                });
            });

            function notificarrevisoria(nombre, id, empresa, fecha) {
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    url: '/admin/calendariotributario/notificacionrevisoria/' + nombre + '/' + id + '/' +
                        empresa + '/' + fecha,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        // Actualiza el calendario después de marcar como "Revisado"
                        checkboxrevisoria.checked = response.valido == 1;
                        if (response.valido == 1) {
                            Swal.fire({
                                title: 'Enviado',
                                text: 'Mensaje enviado con éxito',
                                icon: 'success',
                                confirmButtonText: 'Aceptar'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    calendar.refetchEvents();
                                    window.location.reload();
                                }
                            });
                        } else if (response.valido == 0) {
                            Swal.fire({
                                title: 'Error',
                                text: 'Ocurrió un error al enviar el mensaje',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                        }

                    },
                    error: function(error) {
                        console.error('Error al marcar como revisado:', error);
                    }
                });
            }

            function clearEventListeners(element) {
                const oldElement = element.cloneNode(true);
                element.parentNode.replaceChild(oldElement, element);
                return oldElement;
            }


        });
    </script>
    <script>
        document.getElementById('correoForm').addEventListener('submit', function(event) {
            Swal.fire({
                title: 'Enviando correo...',
                icon: 'info',
                showConfirmButton: false,
                position: 'top',
                toast: true,
                timer: 2500
            });
        });

        function updateCodigoObligacion(value) {
            document.getElementById('codigoobligacion').value = '';
        }

        function updateEmpresa(value) {
            document.getElementById('empresa_id').value = '';
        }
    </script>
@endsection
