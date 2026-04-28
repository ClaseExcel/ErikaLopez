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
    <title>Novedades Gestion Humana</title>
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/actareintegro/acta.css') }}" />

</head>

<body>
    <table>
        <thead>
            <tr>
                <th
                    style="background-color: #00205C; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Empresa</th>
                <th
                    style="background-color: #00205C; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Concepto</th>
                <th
                    style="background-color: #00205C; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Fecha inicio</th>
                <th
                    style="background-color: #00205C; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Fecha fin</th>
                <th
                    style="background-color: #00205C; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Nombres</th>
                <th
                    style="background-color: #00205C; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Observación</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($eventos as $evento)
                <tr>
                    <td>{{ $evento->gestionHumana->empresa->razon_social }}</td>
                    <td>{{ $evento->concepto->nombre }}</td>
                    <td>{{ $evento->fecha_inicio }}</td>
                    <td>{{ $evento->fecha_fin }}</td>
                    <td>{{ $evento->gestionHumana->nombres }}</td>
                    <td>{{ $evento->observacion }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
<script src="{{ asset('lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
