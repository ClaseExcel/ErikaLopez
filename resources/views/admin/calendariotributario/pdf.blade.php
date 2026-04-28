<!DOCTYPE html>
<html>
<head>
    <title>Calendario Tributario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .header-table td {
            border: none; /* Elimina los bordes para la tabla del encabezado */
            padding: 0; /* Elimina el espacio de relleno para los elementos de la tabla */
        }
        table.products-table {
            width: 100%;
            border-collapse: collapse;
        }
        table.products-table th, table.products-table td {
            border: 1px solid #dddddd; /* Agrega bordes a las celdas de la tabla de productos */
            padding: 8px; /* Agrega un espacio de relleno para los elementos de la tabla de productos */
            text-align: left;
        }
        table.products-table th {
            background-color: #232222;
            color:white;
        }
        .text-right {
            text-align: right !important;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td style="width: 50%;">
                <h1 style="color:#e99a49">{{$empresa}}</span></h1>
                <p> </p>
            </td>
            <td style="width: 50%; text-align: right;">
                <img id="logo" src="data:image/jpeg;base64,{{ $base64ImageLogo }}" style="width: 200px; height: auto;">
            </td>
        </tr>
    </table>
    
    <hr>
    @php
        // Unir los arrays y agregar manualmente el tipo
        $allEvents = array_merge(
            array_map(fn($event) => array_merge($event, ['tipo' => 'Dian']), $filteredEventRequerimientosArray),
            array_map(fn($event) => array_merge($event, ['tipo' => 'Municipal']), $filteredEventsArray),
            array_map(fn($event) => array_merge($event, ['tipo' => 'Otros']), $filteredEvents2Array)
        );

        // Ordenar por fecha ascendente
        usort($allEvents, fn($a, $b) => $a['start'] <=> $b['start']);
    @endphp
    <h2>Calendario tributario</h2>
    <table class="products-table">
        <thead>
            <tr>
                <th>Obligación</th>
                <th>Tipo</th>
                <th>Fecha vencimiento</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($allEvents as $event)
                <tr>
                    <td>
                        {{ preg_replace_callback(
                            '/^[^-]*-\s*(.*)/u', // Se añade "u" para soporte Unicode
                            function ($matches) {
                                return mb_convert_case(mb_strtolower($matches[1]), MB_CASE_TITLE, 'UTF-8');
                            },
                            $event['title'],
                        ) }}
                    </td>
                    <td>{{ $event['tipo'] }}</td>
                    <td>{{ $event['start'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p> </p>
    </div>
</body>
</html>
