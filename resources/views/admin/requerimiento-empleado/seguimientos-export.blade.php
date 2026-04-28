<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="icon" href="{{ asset('images/logos/logo-cardona-co.png') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Boostrap -->
    <link href="{{ asset('lib/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Jquery -->
    <script type="text/javascript" src="{{ asset('lib/jquery-3.6.1.min.js') }}"></script>
    <title>Requerimientos</title>
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/actareintegro/acta.css') }}" />

</head>

<body>
    <table>
        <thead>
            <tr>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Consecutivo</th>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Tipo de requerimiento</th>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Cliente</th>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Empleado que solicita</th>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Descripción</th>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Estado</th>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Fecha de vencimiento</th>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Responsable</th>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Observaciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($seguimientos as $seguimiento)
                <tr>
                    <td>{{ $seguimiento->requerimientos->consecutivo }}</td>
                    <td>{{ $seguimiento->requerimientos->tipo_requerimientos->nombre }}</td>
                    <td>{{ $seguimiento->empresa->razon_social }}</td>
                    <td>{{ $seguimiento->requerimientos->empleado_clientes->nombres . ' ' . $seguimiento->requerimientos->empleado_clientes->apellidos }}
                    </td>
                    <td>{{ $seguimiento->requerimientos->descripcion }}</td>
                    @if ($seguimiento->estado_requerimientos->nombre == 'Enviado')
                        <td style="background-color:#75e064;"> {{ $seguimiento->estado_requerimientos->nombre }} </td>
                    @elseif ($seguimiento->estado_requerimientos->nombre == 'Aceptado')
                        <td style="background-color:#64a6e0;"> {{ $seguimiento->estado_requerimientos->nombre }} </td>
                    @elseif ($seguimiento->estado_requerimientos->nombre == 'Rechazado')
                        <td style="background-color:#dc4a4a;"> {{ $seguimiento->estado_requerimientos->nombre }} </td>
                    @elseif ($seguimiento->estado_requerimientos->nombre == 'En proceso')
                        <td style="background-color:#e0c564;"> {{ $seguimiento->estado_requerimientos->nombre }} </td>
                    @elseif ($seguimiento->estado_requerimientos->nombre == 'Finalizado')
                        <td style="background-color:#64cfe0;"> {{ $seguimiento->estado_requerimientos->nombre }} </td>
                    @endif
                    <td>{{ $seguimiento->fecha_vencimiento ? $seguimiento->fecha_vencimiento : 'Sin fecha de vecimiento' }}
                    </td>
                    <td>{{ $seguimiento->usuario_responsable ? $seguimiento->usuario_responsable->nombres . ' ' . $seguimiento->usuario_responsable->apellidos : 'Sin responsable' }}
                    </td>
                    <td>{{ $seguimiento->observacion ? $seguimiento->observacion : 'Requerimiento sin asignar' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
<script src="{{ asset('lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script>