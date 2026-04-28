<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Informe</title>
    <style>
        th:nth-child(1),
        td:nth-child(1) {
            width: 30% !important; /* Ajustar la columna de DESCRIPCIÓN a un valor más grande */
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.6;
            /* Reducir tamaño de fuente */
            margin: 0;
            /* Ajustar márgenes */
            padding: 0;
            /* Ajustar padding */
            background-color: #ffffff;
        }
        body {
            padding-top: 0px; /* Ajusta según la altura de tu cabecera */
            position: relative;
        }
        .content-wrapper {
            margin: 0; /* Eliminar márgenes para evitar recortes */
            width: 100%; /* Mantiene el ancho igual que el contenedor principal */
            max-height: 80vh; /* Limita la altura del contenedor para que el contenido sea desplazable */
            overflow-y: inherit; /* Permite que el contenido se desplace si excede la altura */
            padding-bottom: 50px; /* Asegura que haya espacio suficiente para el footer */
            position: relative; /* Posiciona el contenido de forma normal */
        }
        .table-title {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            color: #333333;
        }
        .section-title {
            text-align: center;
            font-size: 17px;
            font-weight: bold;
            color: #333333;
        }
        .section-subtitle {
            text-align: center;
            font-size: 15px;
            font-weight: normal;
            color: #333333;
            margin-bottom: 20px;
        }
        .container {
            width: 100%;
            padding: 10px;
            /* Ajustar padding */
            box-sizing: border-box;
            margin: 0 auto;
            /* Centrar el contenedor */
            overflow-x: auto;
        }

        
        table {
            width: 100%;
            border-collapse: collapse;
            border: none !important;
            margin-top: 30px;
            page-break-inside: inside;
            margin: 0 !important; /* Eliminar márgenes para evitar recortes */
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            /* Reducir padding en celdas */
            text-align: left;
            font-size: 10px;
            /* Reducir tamaño de fuente en celdas */
        }
        .titulos {
            background-color: #4abaee !important;
            color: white !important;
            font-weight: bold;
        }
        .titulos2 {

            color: black;
            font-weight: bold;
        }
         .totales {
            background-color: #D9D9D9 !important;
            color: black;
            font-weight: bold;
        }
        th {
            background-color: #4abaee;
            color: white;
            text-transform: uppercase;
            font-size: 12px;
            /* Reducir tamaño de fuente en encabezados */
        }

        th:nth-child(1),
        td:nth-child(1) {
            width: 30%;
            /* Ajusta el ancho de la primera columna */
        }

        th:nth-child(2),
        td:nth-child(2) {
            width: 15%;
            /* Ajusta el ancho de la segunda columna */
        }

        @page {
            margin: 10mm;
            /* Ajustar márgenes para la impresión */
        }

        td {
            font-size: 12px;
        }

        td.numeric {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        thead {
            display: table-header-group;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        img {
            width: 20%;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .table-title {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            margin: 20px 0;
            color: #333333;
        }

        .section-title {
            text-align: center;
            font-size: 17px;
            font-weight: bold;
            color: #333333;
            margin-top: 20px;
        }

        .section-subtitle {
            text-align: center;
            font-size: 15px;
            font-weight: normal;
            color: #666666;
            margin-bottom: 20px;
        }

        /* Estilos específicos solo para la tabla con clase 'datatable-informe' */
        table.datatable-informe {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        table.datatable-informe th,
        table.datatable-informe td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }

        table.datatable-informe th {
            background-color: #78797a;
            color: #fff;
        }

        table.datatable-informe td {
            background-color: #f8f9fa;
        }

        table.datatable-informe h6 {
            margin: 0;

        }

        /* Ajusta el ancho de las columnas de manera proporcional */
        table.datatable-informe td.text-end {
            text-align: right;
        }

        /* Evita que el texto de las celdas se corte o salga de la tabla */
        table.datatable-informe {
            table-layout: fixed;
            word-wrap: break-word;
        }

        .datatable-informe {
            width: 100%;
            border-collapse: collapse;
        }

        .datatable-informe th,
        .datatable-informe td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ccc;
        }

        /* Alternar color de filas */
        .datatable-informe tr:nth-child(even) {
            background-color: #e0d6d6;
        }

        .datatable-informe tr:nth-child(odd) {
            background-color: #ffffff;
        }

        /* Estilo solo para la primera columna en la tabla datatable-informe */
        .datatable-informe td:first-child {
            font-size: 15px;
            white-space: nowrap;
        }

        /* Ajuste de bordes y eliminación de espaciado */
        .datatable-informe td,
        .datatable-informe th {
            border: 1px solid #ccc;
            margin: 0;
            padding: 8px;
        }

        h6 {
            font-weight: normal !important;
            text-align: left;
        }
        .datatable-informe th,
        .datatable-informe td {
            width: 150px; /* Ajusta el ancho según tus necesidades */
        }
    </style>
</head>

<body>
    <div class="container">
        <table style="width: 100%; border-collapse: collapse; border: none; margin-top: -30px;">
            <tr>
                <td style="width: 20%; border: none;"></td>

                <td style="width: 60%; text-align: center; vertical-align: top; border: none;">
                    <div style="line-height: 1.2; margin-top: 0;">
                        <h2 style="margin: 0;">{{ $compania }}</h2>
                        <h3 style="margin: 0;">NIT - {{$nit}}</h3>
                        <h3 style="margin: 0;">{{ $informe }}</h3>
                        <p style="margin: 0;"><b>{{ $fecha }}</b></p>
                    </div>
                </td>

                <td rowspan="1" style="width: 20%; text-align: center; vertical-align: middle; border: none;">
                    <img id="logo" src="data:image/jpeg;base64,{{ $base64ImageLogo }}" alt="Logo" style="width: 150px; height: auto; display: block; margin: 0 auto;">
                </td>
            </tr>
        </table>
        <div class="row table-responsive">
            <table class="table table-sm table-bordered datatable-informe w-100">
                @php
                    $meses = [
                        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                    ];
                @endphp
                <thead>
                    <tr>
                        <th>DESCRIPCIÓN</th>
                        @foreach(range(1, 12) as $i)
                            @if(isset($informeData2[0]["mes$i"]))
                                <th style="text-align: center;">{{ $meses[$i] }}</th>
                            @endif
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php
                        $secciones = [
                            'Activo Corriente' => ['Efectivo y equivalentes al efectivo', 'Inversiones', 'Cuentas comerciales y otras cuentas por cobrar', 'Activos por impuestos corrientes', 'Inventarios', 'Anticipos y avances', 'Otros activos', 'Total activo corriente'],
                            'Activo No Corriente' => ['Inversiones no corriente', 'Propiedades planta y equipos', 'Activos Intangibles', 'Impuesto diferido', 'Total activo no corriente', 'Total activo'],
                            'Pasivo Corriente' => ['Obligaciones financieras', 'Cuentas comerciales y otras cuentas por pagar', 'Pasivos por Impuestos Corrientes', 'Beneficios a empleados', 'Anticipos y avances recibidos', 'Otros Pasivos', 'Total pasivos corrientes'],
                            'Pasivo No Corriente' => ['Obligaciones Financieras', 'Cuentas por pagar comerciales y otras cuentas por pagar', 'Pasivos Contingentes', 'Pasivo por impuesto diferido', 'Total pasivos no corrientes', 'Total Pasivo'],
                            'Patrimonio' => ['Capital social', 'Superavit de capital', 'Reservas', 'Utilidad y/o perdidas del ejercicio', 'Utilidad y/o perdidas acumuladas', 'Ganancias acumuladas - Adopcion por primera vez', 'Dividendos o participacion', 'Superavit de Capital Valorizacion', 'Total patrimonio', 'Total Pasivo & Patrimonio'],
                        ];
                    @endphp
            
                    @foreach ($secciones as $titulo => $cuentas)
                        <tr><td style="text-align: left; font-weight: bold;" colspan="{{ count($informeData2[0])-1 }}" class="titulos">{{ $titulo }}</td></tr>
                        @foreach ($cuentas as $cuenta)
                            @foreach ($informeData2 as $item)
                                @if($item['descripcion'] === $cuenta)
                                    @php
                                        $isTotal = str_starts_with($item['descripcion'], 'Total');
                                        $isUtilidadAcumulada = $item['descripcion'] === 'Utilidad y/o perdidas acumuladas';
                                        $isUtilidadEjercicio = $item['descripcion'] === 'Utilidad y/o perdidas del ejercicio';
            
                                        // Manejo de signos según la descripción
                                        $signAdjustment = function ($value) use ($isUtilidadAcumulada, $isUtilidadEjercicio) {
                                            if ($isUtilidadAcumulada || $isUtilidadEjercicio) {
                                                return str_starts_with($value, '-') ? ltrim($value, '-') : '-' . $value;
                                            }
                                            return ltrim($value, '-');
                                        };
                                        // Verificar si todos los meses están en 0
                                        $todosEnCero = true;

                                        foreach (range(1, 12) as $i) {
                                            if (isset($item["mes$i"])) {
                                                $valor = (float) str_replace(',', '', $item["mes$i"]);
                                                if ($valor != 0) {
                                                    $todosEnCero = false;
                                                    break;
                                                }
                                            }
                                        }
                                    @endphp
                                    @if(!$todosEnCero)
                                        <tr @if ($isTotal) style="background-color: #cecece; color: #78797a; font-weight: bold;" @endif>
                                            <td style="text-align: left;">{{ $item['descripcion'] }}</td>
                                            @foreach(range(1, 12) as $i)
                                                @if(isset($item["mes$i"]))
                                                    <td class="texto" style="text-align: right;">
                                                        @if(is_numeric($item["mes$i"]))
                                                        {{ is_numeric($item["mes$i"]) 
                                                        ? number_format((float) str_replace(',', '', $signAdjustment($item["mes$i"])), 0) 
                                                        : '0.00' }}
                                                        @else
                                                        {{$item["mes$i"] ? $item["mes$i"] : '0.00'}}
                                                        @endif
                                                    </td>
                                                @endif
                                            @endforeach
                                        </tr>
                                        @endif
                                    @endif
                            @endforeach
                        @endforeach
                        {{-- Fila vacía --}}
                        
                        <tr><td colspan="{{ count($informeData2[0])-1 }}">&nbsp;</td></tr>
                    @endforeach
                </tbody>
            </table>  
        </div>
    </div>
</body>

</html>
