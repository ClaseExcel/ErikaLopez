<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe Tributario</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; margin:20px }
        h2 { text-align: center; background-color: #232222; color: white; padding: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; page-break-inside: auto; table-layout: fixed; /* Ajusta columnas automáticamente */ }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left;word-wrap: break-word; /* Evita desbordamiento */ }
        th { background-color: #232222; color: white; }
        tr { page-break-inside: avoid; }
        .page-break { page-break-before: always; }
        .header-table { width: 100%; border: none; margin-bottom: 20px; }
        .header-table td { border: none; padding: 5px; }
        .title-cell { text-align: center; font-size: 14px; font-weight: bold; }
        .logo-container { text-align: right; }
        .logo-container img { width: 100px; height: auto; }
        .col-observacion {width: 50px; max-width: 50px;word-wrap: break-word; white-space: normal;
            overflow-wrap: break-word;
        }
    </style>
</head>
<body>
    <table style="width: 100%; border-collapse: collapse;border:none;">
        <tr>
            <td style="width: 25%;border:none;"></td> <!-- Columna vacía -->
            <td style="width: 50%; text-align: center; font-size: 14px;border:none;">
                <strong>INFORME TRIBUTARIO</strong><br>
                <strong style="color:#232222">desde: {{$fechainicio}}</strong><br>
                <strong>Hasta:</strong> {{ $fechafin }}
            </td>
            <td style="width: 25%; text-align: center;border:none;">
                @if($base64ImageLogo)
                    <img src="{{ $base64ImageLogo }}" alt="Logo" style="width: 100px; height: auto;">
                @endif
            </td>
        </tr>
    </table>

    {{-- 📌 Fechas por Empresa --}}
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr><th colspan="9" style="text-align: center">Fechas por Empresa</th></tr>
            <tr>
                <th>Fecha Vencimiento</th>
                <th>Empresa</th>
                <th>NIT</th>
                <th>Nombre</th>
                <th>Código Tributario</th>
                <th>Fecha Revisión</th>
                <th class="col-observacion">Observación</th>
                <th>Detalle Tributario</th>
                <th>Nombre Detalle</th>
            </tr>
        </thead>
        <tbody>
            @forelse($fechasEmpresa as $fecha)
            <tr>
                <td>{{ $fecha->fecha_vencimiento ?? '-' }}</td>
                <td>{{ $fecha->empresa->razon_social ?? '-' }}</td>
                <td>{{ $fecha->empresa->NIT ?? '-' }}</td>
                <td>{{ $fecha->nombre ?? '-' }}</td>
                <td style="text-align: right">{{ $fecha->codigo_tributario ?? '-' }}</td>
                <td>{{ $fecha->fecha_revision ?? '-' }}</td>
                <td class="col-observacion">
                    @if(Str::startsWith($fecha->observacion, ['http://', 'https://']))
                        <a href="{{ $fecha->observacion }}" target="_blank">LINK <i class="fa-solid fa-link"></i></a>
                    @else
                        {{ $fecha->observacion ?? '-' }}
                    @endif
                </td>
                {{-- <td class="col-observacion">{{ $fecha->observacion ?? '-' }}</td> --}}
                <td style="text-align: right">{{ $fecha->detalle_tributario ?? '-' }}</td>
                <td>{{ $fecha->nombre_detalle ?? '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="9">No hay datos disponibles.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="page-break"></div>

    {{-- 🏛️ Fechas de Otras Entidades --}}
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr><th colspan="10" style="text-align: center">Fechas de Otras Entidades</th></tr>
            <tr>
                <th>Fecha Vencimiento</th>
                <th>Empresa</th>
                <th>NIT</th>
                <th>Nombre</th>
                <th>Código Otra Entidad</th>
                <th>Código Tributario</th>
                <th>Fecha Revisión</th>
                <th class="col-observacion">Observación</th>
                <th>Detalle Tributario</th>
                <th>Nombre Detalle</th>
            </tr>
        </thead>
        <tbody>
            @forelse($detallesTributarios as $detalle)
            <tr>
                <td>{{ $detalle->fecha_vencimiento ?? '-' }}</td>
                <td>{{ $detalle->empresa->razon_social ?? '-' }}</td>
                <td>{{ $detalle->empresa->NIT ?? '-' }}</td>
                <td>{{ $detalle->nombre ?? '-' }}</td>
                <td style="text-align: right">{{ $detalle->codigo_otraentidad ?? '-' }}</td>
                <td style="text-align: right">{{ $detalle->codigo_tributario ?? '-' }}</td>
                <td>{{ $detalle->fecha_revision ?? '-' }}</td>
                <td class="col-observacion">
                    @if(Str::startsWith($detalle->observacion, ['http://', 'https://']))
                        <a href="{{ $detalle->observacion }}" target="_blank">LINK <i class="fa-solid fa-link"></i></a>
                    @else
                        {{ $detalle->observacion ?? '-' }}
                    @endif
                </td>
                <td style="text-align: right">{{ $detalle->detalle_tributario ?? '-' }}</td>
                <td>{{ $detalle->nombre_detalle ?? '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="10">No hay datos disponibles.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="page-break"></div>

    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr><th colspan="10" style="text-align: center">Fechas Municipales</th></tr>
            <tr>
                <th>Fecha Vencimiento</th>
                <th>Empresa</th>
                <th>NIT</th>
                <th>Nombre</th>
                <th>Código Municipio</th>
                <th>Código Tributario</th>
                <th>Fecha Revisión</th>
                <th class="col-observacion">Observación</th>
                <th>Detalle Tributario</th>
                <th>Nombre Detalle</th>
            </tr>
        </thead>
        <tbody>
            @forelse($fechasMunicipales as $municipal)
            <tr>
                <td>{{ $municipal->fecha_vencimiento ?? '-' }}</td>
                <td>{{ $municipal->empresa->razon_social ?? '-' }}</td>
                <td>{{ $municipal->empresa->NIT ?? '-' }}</td>
                <td>{{ $municipal->nombre ?? '-' }}</td>
                <td style="text-align: right">{{ $municipal->codigo_municipio ?? '-' }}</td>
                <td style="text-align: right">{{ $municipal->codigo_tributario ?? '-' }}</td>
                <td>{{ $municipal->fecha_revision ?? '-' }}</td>
                <td class="col-observacion">
                    @if(Str::startsWith($municipal->observacion, ['http://', 'https://']))
                        <a href="{{ $municipal->observacion }}" target="_blank">LINK <i class="fa-solid fa-link"></i></a>
                    @else
                        {{ $municipal->observacion ?? '-' }}
                    @endif
                </td>
                <td style="text-align: right">{{ $municipal->detalle_tributario ?? '-' }}</td>
                <td>{{ $municipal->nombre_detalle ?? '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="10">No hay datos disponibles.</td></tr>
            @endforelse
        </tbody>
    </table>

    <p style="text-align: center; font-size: 12px; color: #616161;">Este informe fue generado automáticamente.</p>
</body>
</html>
