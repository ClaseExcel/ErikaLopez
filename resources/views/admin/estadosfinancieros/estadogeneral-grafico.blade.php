@extends('layouts.admin')
@section('title', 'Estados Financieros')
@section('content')


    <div class="form-group">
        <a class="btn btn-light border btn-radius px-4" href="{{ URL::previous() }}">
            <i class="fas fa-arrow-circle-left"></i> Atrás
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5><i class="fa-solid fa-table-columns"></i> Informe gráfico por meses</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <h5 class="subtitle">
                        {{ $compania }}
                    </h5>
                    @if ($centrocostov)
                        <h5>
                            Centro de costo: {{ $centrocostov->codigo. '-'. $centrocostov->nombre }}
                        </h5>
                    @endif
                    <h5>
                        Periodo: {{ $fecha }}
                        {{-- <span class="float-right">
                            Total General: ${{ number_format($totalGeneral, 2, ',', '.') }}
                        </span> --}}
                    </h5>
                </div>
            </div>

            <div class="card-body d-flex justify-content-center" id="chartdiv"></div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <!-- Incluyendo Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        var informePorMes = {!! json_encode($informePorMesMerged) !!};

        // Filtrar meses con datos diferentes a 0
        function filtrarDatos(informe) {
            let filteredLabels = [];
            let filteredData = {};

            // Obtener todos los meses disponibles en el informe
            let meses = Object.keys(informe[Object.keys(informe)[0]]); // Tomamos los meses de una de las cuentas (asumimos que todos tienen los mismos meses)

            // Inicializar filteredData con las mismas claves pero con arrays vacíos
            Object.keys(informe).forEach(key => {
                filteredData[key] = {};
            });

            meses.forEach(mes => {
                let anyDataNonZero = false;
                Object.keys(informe).forEach(key => {
                    if (informe[key][mes] !== 0) {
                        anyDataNonZero = true;
                    }
                });

                if (anyDataNonZero) {
                    filteredLabels.push(mes);
                    Object.keys(informe).forEach(key => {
                        filteredData[key][mes] = informe[key][mes];
                    });
                }
            });

            return { filteredLabels, filteredData };
        }

        $(document).ready(function() {
            let { filteredLabels, filteredData } = filtrarDatos(informePorMes);
            informe(filteredLabels, filteredData);
        });

        function informe(labels, data) {
            $('#chartdiv').empty().append(
                '<canvas id="chartInforme" class="d-flex justify-content-center" style="height:350px; width:100%"></canvas>'
            );
            var chartLine = document.getElementById("chartInforme").getContext("2d");

            new Chart(chartLine, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: Object.keys(data).map(key => {
                        var color = getRandomDistinctColor();
                        return {
                            label: key.replace(/_/g, ' '),
                            data: labels.map(label => data[key][label]),
                            backgroundColor: color,
                            borderColor: color,
                            borderWidth: 2
                        }
                    })
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            lineSpacing: 20
                        },
                    }

                }
            });
        }

        var usedColors = {}; // Objeto para almacenar colores usados

        function getRandomDistinctColor() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            do {
                color = '#';
                for (var i = 0; i < 6; i++) {
                    color += letters[Math.floor(Math.random() * 16)];
                }
            } while (usedColors[color]); // Evitar colores repetidos

            usedColors[color] = true;
            return color;
        }
    </script>
@endsection
