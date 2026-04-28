<div>
    {{-- The best athlete wants his opponent at his best. --}}
    <div class="row">
        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row d-flex justify-content-center">
                        @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
                            <div class="col-12 col-md-3 mb-3">
                                <div class="form-floating pb-0">
                                    <input class="form-control" list="datalistOptions" placeholder="Escribe Para Buscar..."
                                        name="empresa_id" id="empresa_id" wire:model.debounce.500ms="empresa"
                                        autocomplete="off">
                                    <datalist id="datalistOptions">
                                        @foreach ($empresas as $empresa)
                                            <option value="{{ $empresa->id }} - {{ $empresa->razon_social }}"
                                                data-id="{{ $empresa->id }}">
                                            </option>
                                        @endforeach
                                    </datalist>
                                    <label class="fw-normal" for="empresa_id">Empresa</label>
                                </div>
                            </div>

                            <div class="col-12 col-md-3 mb-3">
                                <div class="form-floating pb-0">
                                    <input class="form-control" list="datalistOptionsr"
                                        placeholder="Escribe Para Buscar..." name="responsable_id" id="responsable_id"
                                        wire:model.debounce.500ms="responsable" autocomplete="off">
                                    <datalist id="datalistOptionsr">
                                        @foreach ($responsables as $responsable)
                                            <option
                                                value="{{ $responsable->id }} - {{ $responsable->nombres . ' ' . $responsable->apellidos }}"
                                                data-id="{{ $responsable->id }}">

                                            </option>
                                        @endforeach
                                    </datalist>
                                    <label class="fw-normal" for="responsable_id">Responsables</label>
                                </div>
                            </div>
                        @endif

                        <div class="col-12 col-md-3 mb-3">
                            <div class="form-floating pb-0">
                                <input type="date" id="fechaini" name="fecha_vencimiento" wire:model="fechaInicio"
                                    value="{{ old('fecha_vencimiento', '') }}" class="form-control" placeholder="" />
                                <label for="fechaini" class="fw-normal">Fecha inicial </label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 mb-3">
                            <div class="form-floating pb-0">
                                <input type="date" id="fechafin" name="fecha_vencimiento" wire:model="fechaFin"
                                    min="{{ $fecha_min }}" value="{{ old('fecha_vencimiento', '') }}"
                                    class="form-control" placeholder="" />
                                <label for="fechafin" class="fw-normal">Fecha final </label>
                            </div>
                        </div>

                        <div class="col-12 my-2 text-center">
                            {{-- boton de aplicar filtro --}}
                            <button class="btn btn-save btn-radius px-4" wire:click="filtrarActividades">
                                <i class="fas fa-filter"></i>
                                Aplicar filtro
                            </button>
                            {{-- boton de reset --}}
                            <button class="btn btn-back btn-radius px-4 mr-0 mr-md-2" wire:click="quitarFiltro">
                                <i class="fas fa-eraser"></i>
                                Quitar filtro
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-12">
            <div class="row">
                <div class="col-12 col-xl-4 d-flex align-items-stretch">
                    <div class="card w-100" style="height:450px">
                        <div class="card-header bg-transparent border-0 pt-3 text-center" style="z-index: 10;">
                            <h2 class="text-dark text-bold" style="font-size: 19px">
                                Actividades
                            </h2>
                        </div>
                        <div class="card-body text-center align-middle pt-3">
                            <div id="chart-estado" class="w-100"></div>
                            <p class="card-text">
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-4 d-flex align-items-stretch">
                    <div class="card w-100" style="height:450px">
                        <div class="card-header bg-transparent border-0 pt-3 text-center" style="z-index: 10;">
                            <h2 class="text-dark text-bold" style="font-size: 19px">
                                Requerimientos
                            </h2>
                        </div>
                        @if ($requerimientos != '[0,0,0,0,0]')
                            <div class="card-body text-center align-middle pt-3" id="chart-div">
                                <canvas id="chart-requerimientos"></canvas>
                                <p id="mensaje"></p>
                            </div>
                        @else
                            <div class="card-body d-flex justify-content-center align-items-center p-0" id="chart-div">
                                <p class="text-info"><i class="fas fa-circle-info"></i> No se encuentran datos
                                    disponibles para mostrar.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-12 col-xl-4 d-flex align-items-stretch">
                    <div class="card w-100" style="height:450px">
                        <div class="card-header bg-transparent border-0 pt-3 text-center" style="z-index: 10;">
                            <h2 class="text-dark text-bold" style="font-size: 19px">
                                Tipo de actividad
                            </h2>
                        </div>
                        <div class="card-body text-center align-middle pt-3">
                            <div id="chart-actividades" class="w-100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-12 col-xl-4 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body text-center pb-0">
                    <div class="row">
                        <div class="col-7">
                            <h1 class="display-4 n-border text-dark m-0">{{ number_format($vencidos, 0, ',', '.') }}
                            </h1>
                        </div>
                        <div class="col-3 d-flex align-items-center justify-content-end">
                            <span class="w-75">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.3" stroke="currentColor" class="text-secondary"
                                    style="width: 40px;">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                                </svg>
                            </span>


                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-3 text-center" style="z-index: 10;">
                    <h2 class="display-6 text-dark fw-bold" style="font-size: 18px">
                        Total de actividades vencidas
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body text-center pb-0">
                    <div class="row">
                        <div class="col-7">
                            <h1 class="display-4 n-border text-dark m-0">
                                {{ number_format($finalizados, 0, ',', '.') }}</h1>
                        </div>
                        <div class="col-3 d-flex align-items-center justify-content-end">
                            <span class="w-75">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.3" stroke="currentColor" class="text-secondary"
                                    style="width: 40px;">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                                </svg>

                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-3 text-center" style="z-index: 10;">
                    <h2 class="display-6 text-dark fw-bold" style="font-size: 18px">
                        Total de actividades finalizadas
                    </h2>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-4 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body text-center pb-0">
                    <div class="row">
                        <div class="col-7">
                            <h1 class="display-4 n-border text-dark m-0">{{ number_format($cumplidos, 0, ',', '.') }}
                            </h1>
                        </div>
                        <div class="col-3 d-flex align-items-center justify-content-end">
                            <span class="w-75">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.3" stroke="currentColor" class="text-secondary"
                                    style="width: 40px;">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-3 text-center" style="z-index: 10;">
                    <h2 class="display-6 text-dark fw-bold" style="font-size: 18px">
                        Total de actividades cumplidas
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body text-center pb-0">
                    <div class="row">
                        <div class="col-7">
                            <h1 class="display-4 n-border text-dark m-0">
                                {{ number_format($programados, 0, ',', '.') }}</h1>
                        </div>
                        <div class="col-3 d-flex align-items-center justify-content-end">
                            <span class="w-75">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.3" stroke="currentColor" class="text-secondary"
                                    style="width: 40px;">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                                </svg>

                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-3 text-center" style="z-index: 10;">
                    <h2 class="display-6 text-dark fw-bold" style="font-size: 18px">
                        Total de actividades programadas
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body text-center pb-0">
                    <div class="row">
                        <div class="col-7">
                            <h1 class="display-4 n-border text-dark m-0">
                                {{ number_format($enproceso, 0, ',', '.') }}</h1>
                        </div>
                        <div class="col-3 d-flex align-items-center justify-content-end">
                            <span class="w-75">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.3" stroke="currentColor" class="text-secondary"
                                    style="width: 40px;">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-3 text-center" style="z-index: 10;">
                    <h2 class="display-6 text-dark fw-bold" style="font-size: 18px">
                        Total de actividades en proceso
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body text-center pb-0">
                    <div class="row">
                        <div class="col-7">
                            <h1 class="display-4 n-border text-dark m-0">{{ number_format($realizados, 0, ',', '.') }}
                            </h1>
                        </div>
                        <div class="col-3 d-flex align-items-center justify-content-end">
                            <span class="w-75">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.3" stroke="currentColor" class="text-secondary"
                                    style="width: 40px;">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75" />
                                </svg>

                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-3 text-center" style="z-index: 10;">
                    <h2 class="display-6 text-dark fw-bold" style="font-size: 18px">
                        Total de actividades realizadas
                    </h2>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://cdn.fusioncharts.com/fusioncharts/latest/fusioncharts.js"></script>
<script type="text/javascript"
    src="https://cdn.fusioncharts.com/fusioncharts/latest/themes/fusioncharts.theme.fusion.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    let fini = document.getElementById('fechaini');
    let ffin = document.getElementById('fechafin');

    fini.addEventListener('change', function() {
        ffin.setAttribute('min', this.value);
    });
</script>

<script>
    var tipo_actividades = @json($tipoActividades);
    var TipoActividadesFormat = JSON.parse(tipo_actividades);

    var estado_actividades = @json($estadoActividades);
    var estadoActividadesFormat = JSON.parse(estado_actividades);

    FusionCharts.ready(function() {
        var chartActividades = new FusionCharts({
            type: 'doughnut3d',
            renderAt: 'chart-actividades',
            width: '100%',
            height: '350',
            dataFormat: 'json',
            dataSource: {
                "chart": {
                    "pieRadius": "50%",
                    "theme": "fusion",
                },
                "data": TipoActividadesFormat
            }
        });

        var chartEstado = new FusionCharts({
            type: 'scrollbar2d',
            renderAt: 'chart-estado',
            width: '100%',
            height: '350',
            dataFormat: 'json',
            dataSource: {
                "chart": {
                    "theme": "fusion"
                },
                "categories": [{
                    "category": [{
                            "label": "Programado"
                        },
                        {
                            "label": "En Proceso"
                        },
                        {
                            "label": "Pausado"
                        },
                        {
                            "label": "Cancelado"
                        },
                        {
                            "label": "Vencido"
                        },
                        {
                            "label": "Finalizado"
                        },
                        {
                            "label": "Cumplido"
                        },
                        {
                            "label": "Reactivado"
                        },
                        {
                            "label": "Reprogramado"
                        },
                    ]
                }],
                "dataset": [{
                    "data": estadoActividadesFormat
                }]
            }
        });

        chartEstado.render();
        chartActividades.render();
    });
</script>

<script>
    var colores = ['#56BEEA', '#EB321A', '#EAC358', '#66A638', '#8C8C81', '#fd860d'];

    Livewire.on('recargarGraficos', (data) => {
        // Actualiza los datos en JavaScript (por ejemplo, en tu gráfico)

        if (data[0].requerimientos == "[0,0,0,0,0]") {
            console.log("entro");

            if (chartRequerimiento) {
                chartRequerimiento.destroy();
            }

            $(document).ready(function() {
                $("#mensaje").replaceWith(
                    '<p class="text-info"><i class="fas fa-circle-info"></i> No se encuentran datos disponibles para mostrar.</p>'
                );
            });

        } else {

            if (chartRequerimiento) {
                chartRequerimiento.destroy();
            }

            $('#chart-div').empty().append('<canvas id="chart-requerimientos"></canvas>');
            var ctx = document.getElementById('chart-requerimientos').getContext('2d'); // 2d context
            var chartRequerimiento = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Aceptado', 'Rechazado', 'En proceso', 'Finalizado', 'Desistió', 'Vencido'],
                    datasets: [{
                        data: JSON.parse(data[0].requerimientos),
                        backgroundColor: colores,
                        cutout: '50%',
                        circumference: 180,
                        rotation: 270
                    }]
                },
                options: {
                    aspectRatio: 2,
                }
            });
        }





        FusionCharts.ready(function() {
            var chartActividades = new FusionCharts({
                type: 'doughnut3d',
                renderAt: 'chart-actividades',
                width: '100%',
                height: '350',
                dataFormat: 'json',
                dataSource: {
                    "chart": {
                        "pieRadius": "50%",
                        "theme": "fusion",
                    },
                    "data": JSON.parse(data[0].tipoActividades)
                }
            });

            var chartEstado = new FusionCharts({
                type: 'scrollbar2d',
                renderAt: 'chart-estado',
                width: '100%',
                height: '350',
                dataFormat: 'json',
                dataSource: {
                    "chart": {
                        "theme": "fusion"
                    },
                    "categories": [{
                        "category": [{
                                "label": "Programado"
                            },
                            {
                                "label": "En Proceso"
                            },
                            {
                                "label": "Pausado"
                            },
                            {
                                "label": "Cancelado"
                            },
                            {
                                "label": "Vencido"
                            },

                            {
                                "label": "Finalizado"
                            },
                            {
                                "label": "Cumplido"
                            },
                            {
                                "label": "Reactivado"
                            },
                            {
                                "label": "Reprogramado"
                            },
                        ]
                    }],
                    "dataset": [{
                        "data": JSON.parse(data[0].estadoActividades)
                    }]
                }
            });

            chartEstado.render();
            chartActividades.render();
        });

    });

    var requerimientos = {{ $requerimientos }};

    var ctx = document.getElementById('chart-requerimientos').getContext('2d'); // 2d context
    var chartRequerimiento = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Aceptado', 'Rechazado', 'En proceso', 'Finalizado', 'Desistió', 'Vencido'],
            datasets: [{
                data: requerimientos,
                backgroundColor: colores,
                cutout: '50%',
                circumference: 180,
                rotation: 270
            }]
        },
        options: {
            aspectRatio: 2,
        }
    });
</script>
