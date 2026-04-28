<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre Actividad</th>
            <th>Progreso</th>
            <th>Prioridad</th>
            <th>Fecha Vencimiento</th>
            <th>Periodicidad</th>
            <th>Nota</th>
            <th>Responsable</th>
            <th>Usuario</th>
            <th>Empresa Asociada</th>
            <th>Estado Actividad</th>
        </tr>
    </thead>
    <tbody>
        @foreach($actividades as $actividad)
            <tr>
                <td>{{ $actividad->id }}</td>
                <td>{{ $actividad->nombre }}</td>
                <td>{{ $actividad->progreso }}</td>
                @php
                    if ($actividad->prioridad === 1) {
                        $actividad->prioridad = 'SI';
                    } elseif ($actividad->prioridad === 0) {
                        $actividad->prioridad = 'NO';
                    }
                @endphp
                <td>{{ $actividad->prioridad == 1 || $actividad->prioridad === 'SI' ? 'SI' : 'NO' }}</td>
                <td>{{ \Carbon\Carbon::parse($actividad->fecha_vencimiento)->format('d-m-Y') }}</td>
                <td>{{ $actividad->periodicidad }}</td>
                <td>{{ $actividad->nota }}</td>
                <td>{{ optional($actividad->responsable)->nombre }}</td>
                <td>{{ optional($actividad->usuario)->nombres.' '.optional($actividad->usuario)->apellidos }}</td>
                <td>{{ optional($actividad->empresa_asociada)->razon_social }}</td>
                <td>{{ optional($actividad->reporte_actividades)->estado_actividades->nombre ?? '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
