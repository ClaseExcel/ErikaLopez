<!DOCTYPE html>
<html>
<head>
    <title>Calendario Vencimiento</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            color: #313756;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #313756;
            font-size: 24px;
            text-align: center;
        }
        p {
            font-size: 16px;
            line-height: 1.5;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        thead {
            background-color: #232222;
            color: #ffffff;
        }
        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }
        td {
            border-bottom: 1px solid #dddddd;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #666666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Calendario de Vencimientos</h1>
        <p>Buen día, apreciado <strong>{{ $empresa }}</strong>.</p>
        <p>Le informamos sobre el calendario de vencimientos de <strong>{{ $currentMonthName }}</strong>:</p>
        @php
            // Combinar los arrays en uno solo
            $allEvents = array_merge($filteredEventRequerimientosArray, $filteredEventsArray, $filteredEvents2Array);

            // Ordenar por fecha ascendente (de menor a mayor)
            usort($allEvents, function ($a, $b) {
                return $a['start'] <=> $b['start']; // Comparación directa de strings en formato YYYY-MM-DD
            });
        @endphp
        <table>
            <thead>
                <tr>
                    <th>Detalle</th>
                    <th>Periodicidad</th>
                    <th>Vencimiento</th>
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
                        <td>{{ $currentMonthName }}</td>
                        <td>{{ $event['start'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p>En caso de tener documentos pendientes para el cierre de los impuestos mencionados, agradecemos su envío
            oportuno para cumplir con estas obligaciones.</p>
        <p>Cordialmente,</p>
        <p><strong> Erika Lopez</strong></p>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Erika Lopez. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
