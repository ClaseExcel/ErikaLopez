<table>
    <thead>
        <tr><th colspan="9">Fechas por Empresa</th></tr>
        <tr>
            <th>Fecha Vencimiento</th>
            <th>Empresa</th>
            <th>NIT</th>
            <th>Nombre</th>
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
        @forelse($fechasEmpresa as $fecha)
            <tr style="text-align: center;">
                <td>{{ $fecha->fecha_vencimiento ?? '-'}}</td>
                <td>{{ $fecha->empresa->razon_social ?? '-' }}</td>
                <td>{{ $fecha->empresa->NIT ?? '-' }}</td>
                <td>{{ $fecha->nombre ?? '-'}}</td>
                <td>{{ $fecha->codigo_tributario ?? '-'}}</td>
                <td>{{ $fecha->fecha_revision ?? '-' }}</td>
                <td>{{ $fecha->observacion ?? '-'}}</td>
                <td>{{ $fecha->detalle_tributario ?? '-'}}</td>
                <td>{{ $fecha->nombre_detalle ?? '-'}}</td>
                <td>{{ $fecha->correo == 1 ? 'Sí' : ($fecha->correo ?? '-') }}</td>
                <td>{{ $fecha->revisor == 1 ? 'Sí' : ($fecha->revisor ?? '-') }}</td>
                <td>{{ $fecha->whatsapp == 1 ? 'Sí' : ($fecha->whatsapp ?? '-') }}</td>
            </tr>
        @empty
            <tr><td colspan="10">No hay datos disponibles.</td></tr>
        @endforelse
    </tbody>
</table>
