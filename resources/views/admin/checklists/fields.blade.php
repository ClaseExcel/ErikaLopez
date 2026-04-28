<div class="row">
    <div class="col-12 col-xl-6">
        <div class="form-floating mb-3">
            <input class="form-control" list="datalistOptionsEmpresa" placeholder="Escribe para buscar..." name="empresa_id"
                id="empresa_id" autocomplete="off">
            <datalist id="datalistOptionsEmpresa">
                <option value="">Todas las empresas</option>
                @foreach ($empresas as $cliente)
                    <option value="{{ $cliente->id . ' - ' . $cliente->razon_social }}" data-id="{{ $cliente->id }}">
                    </option>
                @endforeach
            </datalist>
            <input type="hidden" id="empresaexistente" value="{{ $checklist->empresa_id }}">
            <label class="fw-normal">Empresa <strong class="text-danger"> *</strong></label>
            @if ($errors->has('empresa_id'))
                <span class="text-danger">{{ $errors->first('empresa_id') }}</span>
            @endif
        </div>
    </div>

    <script>
        var valorExistente = document.getElementById('empresaexistente').value;

        if (valorExistente) {
            var datalistOptions = document.getElementById('datalistOptionsEmpresa');
            var options = datalistOptions.getElementsByTagName('option');

            for (var i = 0; i < options.length; i++) {
                var valorDataId = options[i].getAttribute('data-id');
                if (valorDataId == valorExistente) {
                    document.getElementById('empresa_id').value = options[i].value;
                    break;
                }
            }
        }
    </script>

    <div class="col-12 col-xl-6">
        <div class="form-floating mb-4">
            <select id="year" name="año" class="form-select">
                <option value="">Selecciona un año</option>
            </select>
            <label for="year" class="fw-normal">Año <strong class="text-danger"> *</strong></label>
            <input type="hidden" id="añoexistente" value="{{ old('año', $checklist->año) }}">
            @if ($errors->has('año'))
                <span class="text-danger">{{ $errors->first('año') }}</span>
            @endif
        </div>
    </div>

    <script>
        const endYear = 2024; // Año de fin
        const startYear = new Date().getFullYear(); // Año actual
        const nextYear = startYear + 1;
        const inputYear = document.getElementById('year');
        const añoExistente = document.getElementById('añoexistente').value;

        for (let year = nextYear; year >= endYear; year--) {
            const option = document.createElement('option');
            option.value = year;
            option.textContent = year;
            if (añoExistente && añoExistente == year) {
                option.selected = true;
            }
            inputYear.appendChild(option);
        }
    </script>

    <div class="col-xl-6">
        <div class="form-floating mb-3">
            <input class="form-control" list="actividadesList" placeholder="Escribe Para Buscar..." name="actividad"
                id="actividadInput" autocomplete="off">
            <datalist id="actividadesList">
                @foreach ($actividades as $actividad)
                    <option value="{{ $actividad->id . ' - ' . $actividad->nombre }}">
                @endforeach
            </datalist>
            <label class="fw-normal">Actividad <b class="text-danger">*</b></label>
            @if ($errors->has('actividad'))
                <span id="actividad" class="text-danger text-sm ">{{ $errors->first('actividad') }}</span>
            @endif
        </div>
    </div>

    <div class="col-12 col-xl-6">
        <div class="form-floating mb-4">
            <select id="periodicidad" name="periodicidad" class="form-select">
                <option value="">Selecciona una periodicidad</option>
                <option value="1">Anual</option>
                <option value="12">Mensual</option>
                <option value="2">Bimestral</option>
                <option value="4">Cuatrimestral</option>
                <option value="6">Semestral</option>
            </select>
            <label for="periodicidad" class="fw-normal">Periodicidad <strong class="text-danger"> *</strong></label>
            @if ($errors->has('periodicidad'))
                <span class="text-danger">{{ $errors->first('periodicidad') }}</span>
            @endif
        </div>
    </div>

    <div class="col-xl-12">
        <input type="checkbox" id="agregarTodas"> Agregar todas las actividades
    </div>

    <style>
        .max-text-resume {
            height: 280px;
            overflow-y: auto;
            overflow-x: hidden;
            scroll-behavior: smooth;
            /* cambiar color scroll bar a azul */
            scrollbar-width: thin;
        }
    </style>

    <div class="col-12 my-4">
        <div class="max-text-resume">
            <table class="table table-sm table-bordered" id="actividadesTable">
                <thead>
                    <tr>
                        <th>Actividad</th>
                        <th>Periodicidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($checklist->actividades)
                        @foreach (json_decode($checklist->actividades) as $actividades)
                            @php
                                $actividad = app\Models\ActividadesChecklist::where('id', $actividades[0])->first();
                            @endphp
                            <tr>
                                <td>{{ $actividades[0] . ' - ' . $actividad->nombre }}</td>
                                <td>
                                    @php
                                        $periodicidadLabels = [
                                            '1' => 'Anual',
                                            '12' => 'Mensual',
                                            '2' => 'Bimestral',
                                            '4' => 'Cuatrimestral',
                                            '6' => 'Semestral',
                                        ];
                                    @endphp
                                    {{ $periodicidadLabels[$actividades[1]] ?? $actividades[1] }}
                                </td>
                                <td class="text-center"><button type="button" class="btn btn-sm btn-danger"
                                        onclick="eliminarActividad('{{ $actividades[0] }}')">Eliminar</button></td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <input type="hidden" name="actividades" id="actividadesSeleccionadas" value="{{ $checklist->actividades }}">

</div>



<script>
    const actividadesArray = @json($checklist->actividades ? json_decode($checklist->actividades) : []);

    const actividadInput = document.getElementById('actividadInput');
    const periodicidadInput = document.getElementById('periodicidad');
    const agregarTodasCheck = document.getElementById('agregarTodas');

    // Listeners normales
    actividadInput.addEventListener('change', agregarActividad);
    periodicidadInput.addEventListener('change', agregarActividad);

    // Controlar agregarTodas
    agregarTodasCheck.addEventListener('change', function() {
        const actividadSeleccionada = actividadInput.value.trim();

        if (actividadSeleccionada) {
            // Si hay actividad seleccionada, no permitir agregarTodas
            Swal.fire({
                title: "Ya tienes una actividad seleccionada, no puedes usar 'Agregar todas'.",
                icon: "error"
            });
            agregarTodasCheck.checked = false; // opcional: desmarcar el checkbox
            return;
        }

        // Si no hay actividad seleccionada, sí permitir
        agregarTodas();
    });

    // También puedes condicionar el cambio de periodicidad
    periodicidadInput.addEventListener('change', function() {
        const actividadSeleccionada = actividadInput.value.trim();

        if (!actividadSeleccionada && agregarTodasCheck.checked) {
            agregarTodas();
        } else {
            agregarActividad();
        }
    });


    function agregarActividad() {
        const actividadRaw = document.getElementById('actividadInput').value.trim();
        const periodicidad = document.getElementById('periodicidad').value;

        if (!actividadRaw || !periodicidad) return;

        const actividadId = actividadRaw.split(' - ')[0];

        if (actividadesArray.some(item => item[0] === actividadId)) return;

        actividadesArray.push([actividadId, periodicidad]);

        renderTabla();
        resetCampos();
    }

    function agregarTodas() {
        const periodicidad = document.getElementById('periodicidad').value;
        const check = document.getElementById('agregarTodas').checked;
        document.getElementById('actividadInput').disabled = true;

        if (check) {
            if (!periodicidad) return;

            document.getElementById('actividadInput').disabled = true;
            const opciones = document.querySelectorAll('#actividadesList option');

            opciones.forEach(opt => {
                const actividadRaw = opt.value;
                const actividadId = actividadRaw.split(' - ')[0];

                if (!actividadesArray.some(item => item[0] === actividadId)) {
                    actividadesArray.push([actividadId, periodicidad]);
                }
            });
        } else {
            // ✅ Si se desmarca el check, limpiar todas las actividades
            actividadesArray.length = 0; // vaciar el array
            document.getElementById('actividadInput').disabled = false;
        }

        renderTabla();
        resetCampos();
    }

    function eliminarActividad(id) {
        const index = actividadesArray.findIndex(item => item[0] === id);
        if (index !== -1) {
            actividadesArray.splice(index, 1);
            renderTabla();
        }
    }

    function renderTabla() {
        const tbody = document.querySelector('#actividadesTable tbody');
        tbody.innerHTML = '';
        actividadesArray.forEach(item => {
            const actividadRaw = document.querySelector(`#actividadesList option[value^="${item[0]}"]`)
                ?.value || item[0];
            const row = document.createElement('tr');
            row.innerHTML = `
            <td>${actividadRaw}</td>
            <td>${getPeriodicidadLabel(item[1])}</td>
            <td class="text-center"><button type="button" class="btn btn-sm btn-danger" onclick="eliminarActividad('${item[0]}')">Eliminar</button></td>
        `;
            tbody.appendChild(row);
        });
        document.getElementById('actividadesSeleccionadas').value = JSON.stringify(actividadesArray);
    }

    function resetCampos() {
        document.getElementById('actividadInput').value = '';
        document.getElementById('periodicidad').value = '';
    }

    function getPeriodicidadLabel(value) {
        const map = {
            '1': 'Anual',
            '12': 'Mensual',
            '2': 'Bimestral',
            '4': 'Cuatrimestral',
            '6': 'Semestral'
        };
        return map[value] || value;
    }
</script>
