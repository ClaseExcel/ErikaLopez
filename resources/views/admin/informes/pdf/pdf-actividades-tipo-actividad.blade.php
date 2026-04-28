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
            border: 0.5px solid black;
            border-collapse: collapse;
        }

        th,
        tr,
        td {
            padding: 6px;
        }

        th {
            background-color: #232222;
            color: #FFFFFF;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="banner" style="margin-bottom:30px;">
        <img src="{{ public_path('./images/logos/logo_contable.png') }}" height="150" border="0">
    </div>


    <table style="width:100%;margin-bottom:30px;">
        <thead>
            <tr>
                <th style="font-weight:bold; font-size:15px;" colspan="2">Informe de actividades especificas por
                    usuario</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><b>Usuario:</b> {{ $datos['usuario'] ? $datos['usuario'] : 'todos' }}</td>
                <td><b>Empresa:</b> {{ $datos['empresa'] ? $datos['empresa'] : 'todos' }}</td>
            </tr>
            <tr>
                <td><b>Fecha inicio:</b> {{ $datos['fecha_inicio'] }}</td>
                <td style="border-bottom: 0.5px solid black"><b>Fecha fin:</b> {{ $datos['fecha_fin'] }}</td>
            </tr>
            @if ($datos['tipo_actividad'])
                <tr>
                    <td colspan="2"><b>Tipo de actividad:</b>
                        {{ $datos['tipo_actividad'] ? $datos['tipo_actividad'] : 'todos' }} </td>
                </tr>
            @endif
        </tbody>
    </table>

    <table style="width:100%;">
        <thead>
            <tr>
                <th width="5%"> # Actividad</th>
                <th width="5%">% de avance</th>
                <th>Nombre de la actividad</th>
                <th>Tipo de actividad</th>
                <th>Empresa</th>
                <th>Responsable</th>
                <th width="10%">Fecha de vencimiento</th>
                <th width="10%">Fecha de inicio de actividad</th>
                <th width="10%">Fecha finalización actividad</th>
                <th width="10%">Total tiempo realizado</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($actividades as $actividad)
                <tr>
                    <td>{{ $actividad['id'] }} </td>
                    <td>{{ $actividad['porcentaje_avance'] }} </td>
                    <td>{{ $actividad['nombre_actividad'] }} </td>
                    <td>{{ $actividad['tipo_actividad'] }} </td>
                    <td>{{ $actividad['empresa'] }} </td>
                    <td>{{ $actividad['responsable'] }} </td>
                    <td>{{ $actividad['fecha_vencimiento'] }} </td>
                    <td>{{ $actividad['fecha_inicio'] }} </td>
                    <td>{{ $actividad['fecha_final'] }} </td>
                    <td>{{ $actividad['total_tiempo_realizado'] ? $actividad['total_tiempo_realizado'] : 'No se ha finalizado la actividad' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <table style="width:100%;">
        <thead>
            <tr>
                <th width="5%"> # Actividad</th>
                <th width="5%">% de avance</th>
                <th width="11.7%">Nombre de la actividad</th>
                <th width="38.3%">Justificación</th>
                <th>Observación</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($actividades as $actividad)
                <tr>
                    <td>{{ $actividad['id'] }} </td>
                    <td>{{ $actividad['porcentaje_avance'] }} </td>
                    <td>{{ $actividad['nombre_actividad'] }} </td>
                    <td>
                        @foreach ($actividad['justificacion'] as $seguimiento)
                            <span class="text-secondary">{{ $seguimiento->time . ' - ' . $seguimiento->estado }} :
                                {!! $seguimiento != '' ? nl2br($seguimiento->descripcion) : 'No hay ninguna justificación disponible.' !!} <br>
                        @endforeach
                    </td>
                    <td>{{ $actividad['observacion'] ? $actividad['observacion'] : 'Sin observación' }} </td>
                </tr>
            @endforeach
            <tr>
                <td style="background-color: #232222; color: #FFFFFF;">Total Actividades</td>
                <td colspan="4"> {{ $cantidad }} </td>
            </tr>
        </tbody>
    </table>

</body>

</html>
