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
    <title>Empresas</title>
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/actareintegro/acta.css') }}" />

</head>

<body>
    <table>
        <thead>
            <tr>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Estado</th>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    NIT</th>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    DV</th>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Razón Social</th>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Tipo de cliente</th>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Correo electrónico</th>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Número Contacto</th>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Dirección Fisica</th>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Frecuencia</th>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Obligaciones</th>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Código obligación municipal</th>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Cámara de comercio</th>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Empleados</th>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Cédula</th>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    SIGLA</th>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    CIIU</th>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    CIIU para municipios</th>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Contraseña DIAN</th>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Firma DIAN</th>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Clave cámara de comercio portal</th>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Firma cámara de comercio</th>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    ICA Usuario portal</th>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    ICA Clave portal</th>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    ARL</th>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Clave ARL</th>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Aportes</th>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    CCF</th>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Usuario / Clave EPS</th>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Usuario / Clave UGPP</th>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Usuario FACT / Nómina electrónica</th>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Clave FACT / Nómina electrónica</th>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Usuario sistema contable</th>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Clave sistema contable</th>
                <th
                    style="background-color: #232222; color: #FFFFFF; border: #080000 1px solid; text-align: center; border: solid #080000 10px;">
                    Otras entidades</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($empresas as $empresa)
                <tr>
                    <td>{{ $empresa->estado == 1 ? 'Activo' : 'Inactivo' }}</td>
                    <td>{{ $empresa->NIT }}</td>
                    <td>{{ $empresa->dv }}</td>
                    <td>{{ $empresa->razon_social }}</td>
                    <td>{{ $empresa->tipocliente }}</td>
                    <td>{{ $empresa->correo_electronico }}</td>
                    <td>{{ $empresa->numero_contacto }}</td>
                    <td>{{ $empresa->direccion_fisica }}</td>
                    <td>{{ $empresa->frecuencia->nombre }}</td>
                    <td>
                        @foreach ($obligaciones as $obligacion)
                            @if ($empresa->obligaciones)
                                @if (in_array($obligacion->codigo, json_decode($empresa->obligaciones)))
                                    {{ $obligacion->codigo . '-' . $obligacion->nombre }}{{ !$loop->last ? ',' : '' }}
                                @endif
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach ($obligacionesmunicipales as $obligacionmunicipal)
                            @if ($empresa->codigo_obligacionmunicipal)
                                @if (in_array($obligacionmunicipal->codigo, json_decode($empresa->codigo_obligacionmunicipal)))
                                    {{ $obligacionmunicipal->codigo . '-' . $obligacionmunicipal->nombre }}{{ !$loop->last ? ',' : '' }}
                                @endif
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach ($camarascomercio as $camara)
                            @if ($camara->id == $empresa->camaracomercio_id)
                                {{ $camara->nombre }}
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach ($empleados as $empleado)
                            @if ($empresa->empleados)
                                @if (in_array($empleado->id, json_decode($empresa->empleados)))
                                    {{ $empleado->nombres . ' ' . $empleado->apellidos }}{{ !$loop->last ? ',' : '' }}
                                @endif
                            @endif
                        @endforeach
                    </td>
                    <td>{{ $empresa->Cedula }}</td>
                    <td>{{ $empresa->sigla }}</td>
                    <td> @if ($empresa->ciiu != 'null' && $empresa->ciiu)
                            @foreach ($ciius as $ciiu)
                                @if (in_array($ciiu->codigo, json_decode($empresa->ciiu)))
                                    {{ $ciiu->nombre }}{{ !$loop->last ? ',' : '' }}
                                @endif
                            @endforeach
                        @endif
                    </td>
                    <td>{{ $empresa->ciiu_municipios }}</td>
                    <td>{{ $empresa->contrasenadian }}</td>
                    <td>{{ $empresa->firmadian }}</td>
                    <td>{{ $empresa->camaracomercioclaveportal }}</td>
                    <td>{{ $empresa->firmacamaracomercio }}</td>
                    <td>{{ implode(', ', $empresa->usuario_ica != 'null' && $empresa->usuario_ica != null ? json_decode($empresa->usuario_ica) : []) }}
                    </td>
                    <td>{{ $empresa->icaclaveportal  != 'null' && $empresa->icaclaveportal != null ? (is_array(json_decode($empresa->icaclaveportal)) ? implode(', ', json_decode($empresa->icaclaveportal)) : $empresa->icaclaveportal) : '' }}
                    </td>
                    <td>{{ $empresa->arl }}</td>
                    <td>{{ $empresa->clavearl }}</td>
                    <td>{{ $empresa->aportes }}</td>
                    <td>{{ $empresa->ccf }}</td>
                    <td>{{ $empresa->usuario_clave_eps }}</td>
                    <td>{{ $empresa->usuario_clave_ugpp }}</td>
                    <td>{{ $empresa->usuario_fac_nomina }}</td>
                    <td>{{ $empresa->clave_fact_nomina }}</td>
                    <td>{{ $empresa->usuario_sistema_contable }}</td>
                    <td>{{ $empresa->clave_sistema_contable }}</td>
                    <td>
                        @foreach ($otrasentidades as $otraentidad)
                            @if ($empresa->otras_entidades)
                                @if (in_array($otraentidad->codigo, json_decode($empresa->otras_entidades)))
                                    {{ $otraentidad->codigo . '-' . $otraentidad->nombre }}{{ !$loop->last ? ',' : '' }}
                                @endif
                            @endif
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
<script src="{{ asset('lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
