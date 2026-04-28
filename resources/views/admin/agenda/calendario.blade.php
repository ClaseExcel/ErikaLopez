@extends('layouts.admin')
@section('title', 'Agenda')
@section('library')
    @include('cdn.fullcalendar-head')
@endsection
@section('content')

    <div class="row">
        <div class="col-12 col-lg-4">
            <div class="col-md-12">
                @can('FILTRAR_AGENDA')
                    <div class="card">
                        @if ($cantEvents != '' && $cantEvents != 'vacio')
                            <div class="card-body pb-0">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="alert alert-primary alert-dismissible fade show mb-0" role="alert">
                                            <i class="fas fa-info-circle"></i>
                                            {{ 'Se encontraron ' . $cantEvents . ' agendas ' }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
        
                        <div class="card-header border-0 bg-transparent text-dark pb-0">
                            <div class="fs-5">
                                <i class="fas fa-search"></i> Filtrar agendas
                            </div>
                        </div>

                        <div class="card-body py-0 mt-0 ">
                            <p class="text-center">
                                seleccione una empresa y un responsable para filtrar las agendas.
                            </p>
                        </div>
        
                        @livewireScripts
        
                        <form action="{{ route('admin.filtroAgenda') }}" method="POST" id="eventos">
                            @csrf
                            <div class="card-body">
                                @livewire('filtro-agendas')
                            </div>
                        </form>
                        
                        <div class="card-footer text-end bg-transparent border-0 mb-3">
                            <button type="submit" class="btn btn-save btn-radius px-4 mb-3" form="eventos">
                                <svg xmlns="http://www.w3.org/2000/svg" style="fill: #ffffff" height="1em" viewBox="0 0 512 512">
                                    <path
                                        d="M487.976 0H24.028C2.71 0-8.047 25.866 7.058 40.971L192 225.941V432c0 7.831 3.821 15.17 10.237 19.662l80 55.98C298.02 518.69 320 507.493 320 487.98V225.941l184.947-184.97C520.021 25.896 509.338 0 487.976 0z" />
                                </svg>
                                Aplicar filtro
                            </button>
                            <button onclick="window.location.href='/admin/citas-agenda/{{ Auth::user()->id }}'" class="btn btn-save btn-radius px-4 mb-3">
                                <i class="fa-solid fa-rotate fa-lg" style="color: #ffffff;"></i>
                                Reiniciar filtro
                            </button>
                        </div>
                    </div>
                @endcan
                </div>
        
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                {{-- { <div class="col-12 col-md-6"><span
                                        class="badge bg-light border rounded-pill w-100 py-1 fw-normal fs-6 mb-2 text-start"><i
                                            class="fas fa-circle dis-color"></i> Disponible</span></div>} --}}
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
        </div>

        <div class="col-sm-12 col-xl-8">
            <div class="card">
                <div class="card-header">
                    <i class="far fa-calendar"></i> Calendario de citas 
                    @if ($nombreempresa != '' && $nombreempresa != 'vacio')
                    - <b>{{$nombreempresa->razon_social }}</b> 
                    @endif
                    @if ($nombreresponsable != '' && $nombreresponsable != 'vacio')
                    - <b>{{ $nombreresponsable->nombres . ' ' . $nombreresponsable->apellidos }}</b>
                    @endif
                </div>
                <div id="calendario-agenda"></div>
            </div>
        </div>
    </div>

    @include('admin.agenda.showCita')


@endsection
@section('scripts')
    @parent

    {{-- <script src="{{asset('js/festivos.js')}}"></script>    --}}

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

    <script>
        let events = {!! json_encode($events) !!};
        let citas = {!! json_encode($citas) !!};

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

                    var fecha_inicio = moment(calEvent.event.start).format('YYYY-MM-DD HH:mm:ss');
                    var fecha_fin = moment(calEvent.event.end).format('YYYY-MM-DD HH:mm:ss');

                    if (calEvent.event.extendedProps.estado == 3) {
                        return
                    }

                    // Evita el clic en el evento con el ID 'evento_no_clicable' que son los Festivos
                    if (calEvent.event.id === 'evento_no_clicable') {
                        return false;
                    }

                    //Busca si la cita esta reservada para mostrar sus datos de la cita
                    citas.forEach(element => {

                        if (element.fecha_inicio.includes(fecha_inicio)) {

                            moment.locale('es');

                            $("#ShowCitaModal #empresa").html('<span class="fw-bold"> Empresa  </span><br>' + calEvent
                                .event.extendedProps.empresa);
                            $("#ShowCitaModal #titulo-cita").html('<span class="fw-bold"> Cita  </span>' +
                                calEvent.event.extendedProps.empresa);
                                
                            $("#ShowCitaModal #empleado").html('<span class="fw-bold"> Persona quién cita</span><br>' +
                                calEvent.event.extendedProps.empleado);
                            $("#ShowCitaModal #motivo").html(element.motivo);
                            
                            $("#ShowCitaModal #horario").html(
                                '<span class="fw-bold"> Cita programada para </span><br>' + moment(element
                                    .fecha_inicio).format('D [de] MMMM [del] Y, HH:mm a') +
                                ' - ' + moment(element.fecha_fin).format('HH:mm a'));

                            if (element.modalidad_id == 1) {
                                $("#ShowCitaModal #modalidad").html('<a style="color:#42758F;" href="' + element.link + '" target="_blank">' + element.link + '</a>'
                                );
                            } else {
                                $("#ShowCitaModal #modalidad").html('<span style="color:#42758F;">' + element.direccion + '</span>');
                            }

                            if (element.observacion != null) {
                                $("#ShowCitaModal #observacion").html('<span class="fw-bold"> Observación </span><br>' +
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
    </script>
@endsection
