<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

<head>
    <style>
        body {
            font-family: "Open Sans", sans-serif;
            font-size: 12px;
        }

        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        th,
        tr,
        td {
            padding: 6px;
        }
    </style>
</head>

<body>
    <div class="banner" style="margin-bottom:30px;">
        <img src="{{ public_path('./images/logos/logo_contable.png') }}" alt="erikalopez" height="90px">
    </div>

    <table style="width:100%">
        @php
            $consecutivoFormateado = str_pad($datos['id'], 4, '0', STR_PAD_LEFT);
        @endphp
        <tr>
            <th rowspan="3">
                <h3>REPORTE DE ACTIVIDADES<h3>
            </th>
            <td style="color:red;"><b>OP-FR-002-{{$consecutivoFormateado}}</b></td>
        </tr>
        <tr>
            <td><b>Consecutivo:</b> OP-FR-002-{{$consecutivoFormateado}}</td>
        </tr>
        <tr>
            <td><b>Versión:</b> 01</td>
        </tr>
        <tr>
            <td><b>Fecha:</b> {{ $datos['fecha'] }} </td>
            <td><b>Teléfono</b> {{ $datos['telefono'] }}</td>
        </tr>
    </table>
    <table style="width:100%;">
        <tr>
            <th colspan="2" style="text-align:left;"><b>Cliente:</b> {{ $datos['cliente'] }}</th>
        </tr>
        <tr>
            <td><b>Dirección:</b> {{ $datos['direccion'] }}</td>
            <td><b>Persona contacto:</b> </td>
        </tr>
        <tr>
            <td><b>Tipo visita:</b> {{ $datos['tipo_visita'] }}</td>
            <td><b>Servicio contratado:</b> </td>
        </tr>
        <tr>
            <td><b>Hora de inicio: </b> {{ $datos['hora_inicio'] }}</td>
            <td><b>Hora de finalización:</b> {{ $datos['hora_fin'] }}</td>
        </tr>
    </table>
    <table style="width:100%;">
        <tr style="background-color:#e3e3e3;">
            <th>ACTIVIDAD PROGRAMADA</th>
        </tr>
        <tr>
            <td>
                {{ $datos['actividad_programada'] }}
            </td>
        </tr>
    </table>
    <table style="width:100%;">
        <tr>
            <th colspan="5">DESARROLLO DE LA ACTIVIDAD</th>
        </tr>
        <tr style="background-color:#232222;">
            <th>Tipo</th>
            <th>Fecha</th>
            <th>Inicio</th>
            <th>Fin</th>
            <th style="width:50%">Ejecución</th>
        </tr>

        @foreach ($historial as $historia)
            <tr>
                <td>{{ $historia['tipo'] }}</td>
                <td>{{ $historia['fecha'] }}</td>
                <td>{{ $historia['inicio'] }}</td>
                <td>{{ $historia['fin'] }}</td>
                <td>{{ $historia['justificacion'] }}</td>
            </tr>
        @endforeach
    </table>
    <table style="width:100%;">
        <tr style="background-color:#e3e3e3;">
            <th>RECOMENDACIONES Y TAREAS</th>
        </tr>
        <tr>
            <td>
                {{ $datos['recomendacion'] }}
            </td>
        </tr>
    </table>

    <table style="width:100%;">
        <div class="content" style="padding:10px;">
            <h4>ELABORADO POR</h4>
            <p>
                <b>Nombres:</b> {{ $datos['elaborado_por'] }}
            </p>
            <p>
                <b>Cargo:</b> {{ $datos['cargo'] }}
            </p>

            <h4>RESPONSABLE ACTIVIDAD</h4>
            <p>
                <b>Nombres:</b> {{ $datos['responsable'] }}
            </p>
            <p>
                <b>Cargo:</b> {{ $datos['cargo_responsable'] }}
            </p>
        </div>
</body>

</html>
