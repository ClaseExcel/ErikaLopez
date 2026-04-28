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
    <title>Gestion Humana</title>
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/actareintegro/acta.css') }}" />

</head>

<body>
    <table>
        <thead>
            <tr>
                <th
                    style="background-color: #00205C; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Estado</th>
                <th
                    style="background-color: #00205C; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Tipo de documento</th>
                <th
                    style="background-color: #00205C; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Cédula</th>
                <th
                    style="background-color: #00205C; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Nombres</th>
                <th
                    style="background-color: #00205C; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Correo electrónico</th>
                <th
                    style="background-color: #00205C; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Teléfono</th>
                <th
                    style="background-color: #00205C; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Dirección</th>
                <th
                    style="background-color: #00205C; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Municipio de residencia</th>
                <th
                    style="background-color: #00205C; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Fecha de nacimiento</th>
                <th
                    style="background-color: #00205C; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Estado civil</th>
                <th
                    style="background-color: #00205C; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Empresa</th>
                <th
                    style="background-color: #00205C; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Tipo de contrato</th>
                <th
                    style="background-color: #00205C; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Cargo</th>
                <th
                    style="background-color: #00205C; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Salario</th>
                <th
                    style="background-color: #00205C; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Auxilio de transporte</th>
                <th
                    style="background-color: #00205C; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Bonificación</th>
                <th
                    style="background-color: #00205C; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Fecha de ingreso</th>
                <th
                    style="background-color: #00205C; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Fecha de finalización</th>
                <th
                    style="background-color: #00205C; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    EPS</th>
                <th
                    style="background-color: #00205C; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    AFP</th>
                <th
                    style="background-color: #00205C; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    CESANTÍAS</th>
                <th
                    style="background-color: #00205C; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    ARL</th>
                <th
                    style="background-color: #00205C; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    CCF</th>
                <th
                    style="background-color: #00205C; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Número de beneficiarios</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($gestiones as $gestion)
                <tr>
                    <td
                        @if ($gestion->estado == 'ACTIVO') style="background-color: #459b3a; color: #FFFFFF;"
                        @else
                            style="background-color: #bc2a2a; color: #FFFFFF;" @endif>
                        {{ $gestion->estado }}</td>
                    <td>{{ $gestion->tipo_documento }}</td>
                    <td>{{ $gestion->cedula }}</td>
                    <td>{{ $gestion->nombres }}</td>
                    <td>{{ $gestion->correo_electronico }}</td>
                    <td>{{ $gestion->telefono }}</td>
                    <td>{{ $gestion->direccion }}</td>
                    <td>{{ $gestion->municipio_residencia }}</td>
                    <td>{{ $gestion->fecha_nacimiento }}</td>
                    <td>{{ $gestion->estado_civil }}</td>
                    <td>{{ $gestion->empresa->razon_social }}</td>
                    <td>{{ $gestion->tipo_contrato }}</td>
                    <td>{{ $gestion->cargo }}</td>
                    <td>{{ $gestion->salario }}</td>
                    <td>{{ $gestion->auxilio_transporte }}</td>
                    <td>{{ $gestion->bonificacion }}</td>
                    <td>{{ $gestion->fecha_ingreso }}</td>
                    <td>{{ $gestion->fecha_finalizacion }}</td>
                    <td>{{ $gestion->eps }}</td>
                    <td>{{ $gestion->afp }}</td>
                    <td>{{ $gestion->cesantias }}</td>
                    <td>{{ $gestion->arl }}</td>
                    <td>{{ $gestion->ccf }}</td>
                    <td>{{ $gestion->numero_beneficiarios }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
<script src="{{ asset('lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
