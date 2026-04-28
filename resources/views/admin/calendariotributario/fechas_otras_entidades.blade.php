<table>
    <thead>
        <tr><th colspan="9" style="text-align: center">Fechas Otras entidades</th></tr>
        <tr>
            <th>Fecha Vencimiento</th>
            <th>Empresa</th>
            <th>NIT</th>
            <th>Nombre</th>
            <th>Código Otra Entidad</th>
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
        @forelse($detallesTributarios as $detalle)
        <tr>
            <td>{{ $detalle->fecha_vencimiento ?? '-' }}</td>
            <td>{{ $detalle->empresa->razon_social ?? '-' }}</td>
            <td>{{ $detalle->empresa->NIT ?? '-' }}</td>
            <td>{{ $detalle->nombre ?? '-' }}</td>
            <td>{{ $detalle->codigo_otraentidad ?? '-' }}</td>
            <td>{{ $detalle->codigo_tributario ?? '-' }}</td>
            <td>{{ $detalle->fecha_revision ?? '-' }}</td>
            <td>{{ $detalle->observacion ?? '-' }}</td>
            <td>{{ $detalle->detalle_tributario ?? '-' }}</td>
            <td>{{ $detalle->nombre_detalle ?? '-' }}</td>
            <td>{{ $detalle->correo == 1 ? 'Sí' : ($detalle->correo ?? '-') }}</td>
            <td>{{ $detalle->revisor == 1 ? 'Sí' : ($detalle->revisor ?? '-') }}</td>
            <td>{{ $detalle->whatsapp == 1 ? 'Sí' : ($detalle->whatsapp ?? '-') }}</td>
        </tr>
        @empty
        <tr><td colspan="10">No hay datos disponibles.</td></tr>
        @endforelse
    </tbody>
</table>
