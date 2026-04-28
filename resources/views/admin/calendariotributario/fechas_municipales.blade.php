<table>
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
            <th>Observación</th>
            <th>Detalle Tributario</th>
            <th>Nombre Detalle</th>
            <th>Notificado</th>
            <th>Notifica revisor</th>
            <th>Notifica whastapp</th>
        </tr>
    </thead>
    <tbody>
        @forelse($fechasMunicipales as $municipal)
        <tr>
            <td>{{ $municipal->fecha_vencimiento ?? '-' }}</td>
            <td>{{ $municipal->empresa->razon_social ?? '-' }}</td>
            <td>{{ $municipal->empresa->NIT ?? '-' }}</td>
            <td>{{ $municipal->nombre ?? '-' }}</td>
            <td>{{ $municipal->codigo_municipio ?? '-' }}</td>
            <td>{{ $municipal->codigo_tributario ?? '-' }}</td>
            <td>{{ $municipal->fecha_revision ?? '-' }}</td>
            <td>{{ $municipal->observacion ?? '-' }}</td>
            <td>{{ $municipal->detalle_tributario ?? '-' }}</td>
            <td>{{ $municipal->nombre_detalle ?? '-' }}</td>
            <td>{{ $municipal->correo == 1 ? 'Sí' : ($municipal->correo ?? '-') }}</td>
            <td>{{ $municipal->revisor == 1 ? 'Sí' : ($municipal->revisor ?? '-') }}</td>
            <td>{{ $municipal->whatsapp == 1 ? 'Sí' : ($municipal->whatsapp ?? '-') }}</td>
        </tr>
        @empty
        <tr><td colspan="10">No hay datos disponibles.</td></tr>
        @endforelse
    </tbody>
</table>
