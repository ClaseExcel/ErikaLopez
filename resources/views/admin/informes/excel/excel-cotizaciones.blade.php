<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="icon" href="{{ asset('../images/logos/geamcol_logo.svg') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Boostrap -->
    <link href="{{ asset('lib/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Jquery -->
    <script type="text/javascript" src="{{ asset('lib/jquery-3.6.1.min.js') }}"></script>
    <title>CRM</title>
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/actareintegro/acta.css') }}" />

</head>

<body>

    <table>
        <thead>
            <tr>
                <th
                    style="background-color: #1a56db; color: #FFFFFF;  border: #080000 1px solid;  text-align: center; border: solid #080000 10px;  width:300px">
                    Fecha de envío de la propuesta</th>
                <th
                    style="background-color: #1a56db; color: #FFFFFF;  border: #080000 1px solid;  text-align: center; border: solid #080000 10px; width:300px">
                    Fecha de vigencia de la propuesta</th>
                <th
                    style="background-color: #1a56db; color: #FFFFFF;  border: #080000 1px solid;  text-align: center; border: solid #080000 10px; width:300px">
                    Empresa solicitante</th>
                <th
                    style="background-color: #1a56db; color: #FFFFFF;  border: #080000 1px solid;  text-align: center; border: solid #080000 10px; width:300px">
                    Responsable de la cotización</th>
                <th
                    style="background-color: #1a56db; color: #FFFFFF;  border: #080000 1px solid;  text-align: center; border: solid #080000 10px; width:300px">
                    Nombre de contacto en la empresa</th>
                <th
                    style="background-color: #1a56db; color: #FFFFFF;  border: #080000 1px solid;  text-align: center; border: solid #080000 10px; width:300px">
                    Número de contacto en la empresa</th>
                <th
                    style="background-color: #1a56db; color: #FFFFFF;  border: #080000 1px solid;  text-align: center; border: solid #080000 10px; width:300px">
                    Servicio cotizado</th>
                <th
                    style="background-color: #1a56db; color: #FFFFFF;  border: #080000 1px solid;  text-align: center; border: solid #080000 10px; width:200px">
                    Precio de venta</th>
                <th
                    style="background-color: #1a56db; color: #FFFFFF;  border: #080000 1px solid;  text-align: center; border: solid #080000 10px; width:350px">
                    Observación</th>
                <th
                    style="background-color: #1a56db; color: #FFFFFF;  border: #080000 1px solid;  text-align: center; border: solid #080000 10px; width:300px">
                    Fecha del primer seguimiento</th>
                <th
                    style="background-color: #1a56db; color: #FFFFFF;  border: #080000 1px solid;  text-align: center; border: solid #080000 10px; width:350px">
                    Observación del primer seguimiento</th>
                <th
                    style="background-color: #1a56db; color: #FFFFFF;  border: #080000 1px solid;  text-align: center; border: solid #080000 10px; width:350px">
                    Estado de la cotización</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($datos as $dato)
                <tr>
                    <td>{{ $dato->fecha_envio }}</td>
                    <td>{{ $dato->fecha_vigencia }}</td>
                    <td>{{ $dato->cliente }}</td>
                    <td>{{ $dato->responsable->nombres . ' ' . $dato->responsable->apellidos }}</td>
                    <td>{{ $dato->nombre_contacto }}</td>
                    <td>{{ $dato->telefono_contacto }}</td>
                    <td>{{ $dato->servicio_cotizado }}</td>
                    <td>{{ $dato->precio_venta }}</td>
                    <td>{!! $cleanText = strip_tags($dato->observaciones, '<strong><br><i>') !!}</td>
                    <td>{{ $dato->fecha_primer_seguimiento }}</td>
                    <td>{!! $cleanText = strip_tags($dato->observacion_primer_seguimiento, '<strong><br><i>') !!}</td>
                    <td>{{ $dato->estado_cotizacion }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
<script src="{{ asset('lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
