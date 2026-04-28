<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="icon" href="{{ asset('../images/logos/logo_contable.png') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Boostrap -->
    <link href="{{ asset('lib/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Jquery -->
    <script type="text/javascript" src="{{ asset('lib/jquery-3.6.1.min.js') }}"></script>
    <title>actividades</title>
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/actareintegro/acta.css') }}" />

</head>

<body>
    <table>
        <thead>
            <tr>
                <th></th>
                <th style="font-weight:bold; font-size:12px;">Informe de actividades especificas por empresas</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td>
                    Empresa: {{ $datos['empresa'] ? $datos['empresa'] : 'todos' }}
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    Fecha inicio: {{ $datos['fecha_inicio'] }}
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    Fecha fin: {{ $datos['fecha_fin'] }}
                </td>
            </tr>
            @if($datos['tipo_actividad'])
            <tr>
                <td></td>
                <td>
                   Tipo de actividad: {{ $datos['tipo_actividad'] ? $datos['tipo_actividad'] : 'todos' }} 
                </td>
            </tr>
            @endif

            @if($datos['estado'])
            <tr>
                <td></td>
                <td>
                    Estado: {{ $datos['estado'] ? $datos['estado'] : 'todos'}}
                </td>
            </tr>
            @endif
        </tbody>
    </table>

    @if($datos['tipo_informe'] == 'tipo_actividad')
    <table>
        <thead>
            <tr>
                <th
                style="background-color: #232222; color: #FFFFFF;  border: #080000 1px solid;  text-align: center; border: solid #080000 10px; width:200px">
                # Actividad</th>
                <th
                style="background-color: #232222; color: #FFFFFF;  border: #080000 1px solid;  text-align: center; border: solid #080000 10px;">
                Nombre de la actividad</th>
                <th
                style="background-color: #232222; color: #FFFFFF;  border: #080000 1px solid;  text-align: center; border: solid #080000 10px;">
                Responsable</th>
                <th
                style="background-color: #232222; color: #FFFFFF;  border: #080000 1px solid;  text-align: center; border: solid #080000 10px;">
                Tipo de actividad</th>
                <th
                style="background-color: #232222; color: #FFFFFF;  border: #080000 1px solid;  text-align: center; border: solid #080000 10px; width:300px">
                Porcentaje de avance actividad</th>
                <th
                style="background-color: #232222; color: #FFFFFF;  border: #080000 1px solid;  text-align: center; border: solid #080000 10px; width:300px">
                Fecha de vencimiento</th>
                <th
                style="background-color: #232222; color: #FFFFFF;  border: #080000 1px solid;  text-align: center; border: solid #080000 10px; width:300px">
                Fecha de inicio de actividad</th>
                <th
                style="background-color: #232222; color: #FFFFFF;  border: #080000 1px solid;  text-align: center; border: solid #080000 10px; width:300px">
                Fecha finalización actividad</th>
                <th
                style="background-color: #232222; color: #FFFFFF;  border: #080000 1px solid;  text-align: center; border: solid #080000 10px; width:300px">
                Total tiempo realizado</th>
                <th
                style="background-color: #232222; color: #FFFFFF;  border: #080000 1px solid;  text-align: center; border: solid #080000 10px; width:300px">
                Justificación</th>
                <th
                style="background-color: #232222; color: #FFFFFF;  border: #080000 1px solid;  text-align: center; border: solid #080000 10px; width:300px">
                Observación</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($cantidadActividades as $actividad)
                <tr>
                    <td>{{ $actividad['id'] }} </td>
                    <td>{{ $actividad['nombre_actividad'] }} </td>
                    <td>{{ $actividad['responsable'] }} </td>
                    <td>{{ $actividad['tipo_actividad'] }} </td>
                    <td>{{ $actividad['porcentaje_avance'] }} </td>
                    <td>{{ $actividad['fecha_vencimiento'] }} </td>
                    <td>{{ $actividad['fecha_inicio'] }} </td>
                    <td>{{ $actividad['fecha_final'] }} </td>
                    <td>{{ $actividad['total_tiempo_realizado'] }} </td>
                     <td>@foreach ($actividad['justificacion'] as $seguimiento)
                        <span class="text-secondary">{{ $seguimiento->time . ' - ' . $seguimiento->estado }} :
                        {!! $seguimiento != ''
                        ? nl2br($seguimiento->descripcion)
                        : 'No hay ninguna justificación disponible.' !!} <br>
                    @endforeach</td>
                    <td>{{ $actividad['observacion'] }} </td>
                </tr>
            @endforeach
            <tr>
                <td style="background-color: #232222; color: #FFFFFF;">Total Actividades</td>
                <td> {{ $total }} </td>
            </tr>
        </tbody>
    </table>
    @endif
    @if($datos['tipo_informe'] == 'estado')
    <table>
        <thead>
            <tr>
                <th
                style="background-color: #232222; color: #FFFFFF;  border: #080000 1px solid;  text-align: center; border: solid #080000 10px; width:100px">
                # Actividad</th>
                <th
                style="background-color: #232222; color: #FFFFFF;  border: #080000 1px solid;  text-align: center; border: solid #080000 10px;">
                Nombre de la actividad</th>
                <th
                style="background-color: #232222; color: #FFFFFF;  border: #080000 1px solid;  text-align: center; border: solid #080000 10px;">
                Responsable</th>
                <th
                style="background-color: #232222; color: #FFFFFF;  border: #080000 1px solid;  text-align: center; border: solid #080000 10px;">
                Estado de la actividad</th>
                <th
                style="background-color: #232222; color: #FFFFFF;  border: #080000 1px solid;  text-align: center; border: solid #080000 10px; width:300px">
                Porcentaje de avance actividad</th>
                <th
                style="background-color: #232222; color: #FFFFFF;  border: #080000 1px solid;  text-align: center; border: solid #080000 10px; width:300px">
                Fecha de vencimiento</th>
                <th
                style="background-color: #232222; color: #FFFFFF;  border: #080000 1px solid;  text-align: center; border: solid #080000 10px; width:300px">
                Fecha de inicio de actividad</th>
                <th
                style="background-color: #232222; color: #FFFFFF;  border: #080000 1px solid;  text-align: center; border: solid #080000 10px; width:300px">
                Fecha de finalización de actividad</th>
                <th
                style="background-color: #232222; color: #FFFFFF;  border: #080000 1px solid;  text-align: center; border: solid #080000 10px; width:300px">
                Total tiempo realizado</th>
                <th
                style="background-color: #232222; color: #FFFFFF;  border: #080000 1px solid;  text-align: center; border: solid #080000 10px; width:300px">
                Justificación</th>
                <th
                style="background-color: #232222; color: #FFFFFF;  border: #080000 1px solid;  text-align: center; border: solid #080000 10px; width:300px">
                Observación</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($cantidadActividadesEstado as $actividad)
                <tr>
                    <td>{{ $actividad['id'] }} </td>
                    <td>{{ $actividad['nombre_actividad'] }} </td>
                    <td>{{ $actividad['responsable'] }} </td>
                    <td>{{ $actividad['estado'] }} </td>
                    <td>{{ $actividad['porcentaje_avance'] }} </td>
                    <td>{{ $actividad['fecha_vencimiento'] }} </td>
                    <td>{{ $actividad['fecha_inicio'] }} </td>
                    <td>{{ $actividad['fecha_final'] }} </td>
                    <td>{{ $actividad['total_tiempo_realizado'] }} </td>
                     <td>@foreach ($actividad['justificacion'] as $seguimiento)
                        <span class="text-secondary">{{ $seguimiento->time . ' - ' . $seguimiento->estado }} :
                        {!! $seguimiento != ''
                        ? nl2br($seguimiento->descripcion)
                        : 'No hay ninguna justificación disponible.' !!} <br>
                    @endforeach</td>
                    <td>{{ $actividad['observacion'] }} </td>
                </tr>
            @endforeach
            <tr>
                <td style="background-color: #232222; color: #FFFFFF;">Total Actividades</td>
                <td> {{ $total }} </td>
            </tr>
        </tbody>
    </table>
    @endif
</body>

</html>
<script src="{{ asset('lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script>