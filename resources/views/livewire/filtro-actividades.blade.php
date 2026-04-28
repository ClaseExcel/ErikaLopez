<div>
    <div class="row">
        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <div>
                        <i class="fas fa-search fa-sm"></i> Filtrar actividad
                    </div>
                </div>
                <div class="card-body mb-0 pb-0">
                    <div class="row d-flex justify-content-center">
                        <div class="col-12 col-md-1">
                            <div class="form-floating mb-3">
                                <input type="text" id="id" name="id" wire:model="idActividad"
                                    class="form-control" placeholder="" />
                                <label for="id" class="fw-normal">#</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="form-floating mb-3">
                                <input type="text" id="nombre_actividad" name="nombre_actividad"
                                    wire:model="nombreActividad" class="form-control" placeholder="" />
                                <label for="nombre_actividad" class="fw-normal">Nombre actividad </label>
                            </div>
                        </div>
                        <div class="col-12 col-md-2">
                            <div class="form-floating mb-3">
                                <div class="form-floating mb-3">
                                    <select class="form-select" wire:model="estado">
                                        <option value="">Todos los estados</option>
                                        @foreach ($estados as $estado)
                                            <option value="{{ $estado->id }}">{{ $estado->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <label class="fw-normal" for="estado">Estados</label>
                                </div>
                            </div>
                        </div>

                        {{-- Todos menos el cliente puede filtrar por empresas --}}
                        @if (Auth::user()->role->title != 'Cliente')
                            <div class="col-12 col-md-3">
                                <div class="form-floating mb-3">
                                    <select class="form-select" wire:model="empresa">
                                        <option value="">Todas las empresas</option>
                                        @foreach ($empresas as $empresa)
                                            <option value="{{ $empresa->id }}">{{ $empresa->razon_social }}</option>
                                        @endforeach
                                    </select>
                                    <label class="fw-normal" for="empresa">Empresa</label>
                                </div>
                            </div>
                        @endif

                        {{-- Solo el usuario administrador filtra por responsables --}}
                        @if (Auth::user()->role_id == 1)
                            <div class="col-12 col-md-3">
                                <div class="form-floating mb-3">
                                    <select class="form-select" wire:model="responsable">
                                        <option value="">Todos los responsables</option>
                                        @foreach ($responsables as $responsable)
                                            <option value="{{ $responsable->id }}">
                                                {{ $responsable->nombres . ' ' . $responsable->apellidos }}</option>
                                        @endforeach
                                    </select>
                                    <label class="fw-normal" for="responsable">Responsables</label>
                                </div>
                            </div>
                        @endif

                        <div class="col-12 col-md-6">
                            <div class="form-floating mb-3">
                                <input type="date" id="fechaini" name="fecha_vencimiento"
                                    wire:model="fechaInicioVencimiento" value="{{ old('fecha_vencimiento', '') }}"
                                    class="form-control" placeholder="" />
                                <label for="fecha_vencimiento" class="fw-normal">Fecha inicial de vencimiento</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-floating mb-3">
                                <input type="date" id="fechafin" name="fecha_vencimiento"
                                    wire:model="fechaFinVencimiento" min="{{ $fecha_min }}"
                                    value="{{ old('fecha_vencimiento', '') }}" class="form-control" placeholder="" />
                                <label for="fecha_vencimiento" class="fw-normal">Fecha final de vencimiento</label>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    let fini = document.getElementById('fechaini');
                    let ffin = document.getElementById('fechafin');

                    fini.addEventListener('change', function() {
                        ffin.setAttribute('min', this.value);
                    });
                </script>

                <div class="col-12 mt-0 mb-3 mx-auto text-center">
                    <div class="row mx-auto mb-3">
                        <div class="col-12 text-center" style="font-size: 20px;">
                            {!! $filtroAplicado !!}
                        </div>
                    </div>

                    {{-- boton de reset --}}
                    <button class="btn btn-back btn-radius px-4 mr-0 mr-md-2" wire:click="quitarFiltro">
                        <i class="fas fa-eraser"></i>
                        Quitar filtro
                    </button>
                    {{-- boton de aplicar filtro --}}
                    <button class="btn btn-save btn-radius px-4" wire:click="filtrarTabla">
                        <i class="fas fa-filter"></i>
                        Aplicar filtro
                    </button>
                </div>


            </div>
        </div>

        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-list"></i> Actividad cliente
                </div>

                <div class="card-body">
                    <div class="col-12">
                        <table class="table-bordered table-striped datatable-Actividades w-100">
                            <thead>
                                <tr class="text-center">
                                    <th class="text-end">ID</th>
                                    <th>Nombre actividad</th>
                                    <th class="text-end">Progreso</th>
                                    <th>Inicio</th>
                                    <th>Vencimiento</th>
                                    <th>Estado</th>
                                    <th>Empresa</th>
                                    <th width="120"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($actividades as $actividad)
                                    <tr>
                                        <td>{{ $actividad->id }}</td>
                                        <td>{{ $actividad->nombre }}</td>
                                        <td class="text-end">{{ $actividad->progreso }}%</td>
                                        <td>{{ $actividad->reporte_actividades ? $actividad->reporte_actividades->fecha_inicio : 'Aún se ha iniciado' }}
                                        </td>
                                        <td>{{ $actividad->fecha_vencimiento }}</td>
                                        <td>{{ $actividad->reporte_actividades->estado_actividades->nombre }}</td>
                                        <td>{{ $actividad->empresa_asociada ? $actividad->empresa_asociada->razon_social : $actividad->cliente->razon_social }}
                                        </td>


                                        <td class="text-center actions-width">
                                            @can('VER_ACTIVIDADES')
                                                <a class="btn-ver px-2 py-0"
                                                    href="{{ route('admin.actividad_cliente.show', $actividad->id) }}"
                                                    title="Ver más información"><i class="fas fa-eye"></i></a>
                                            @endcan
                                            @can('EDITAR_ACTIVIDADES')
                                                <a class="btn-editar px-2 py-0"
                                                    href="{{ route('admin.actividad_cliente.edit', $actividad->id) }}"
                                                    title="Editar registro">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                            @endcan
                                            @if (auth()->user()->role_id == 1)
                                                <a class="btn-editar px-2 py-0"
                                                    href="{{ route('admin.reporte.index', $actividad->id) }}"
                                                    title="Reportar registro">
                                                    <i class="fas fa-file-alt"></i>
                                                </a>
                                                <a class="btn-editar px-2 py-0"
                                                    href="{{ route('admin.reporte.reasignar', $actividad->id) }}"
                                                    title="Reasignar registro">
                                                    <i class="fa-solid fa-user-pen"></i>
                                                </a>
                                            @else
                                                @if ($actividad->usuario_id == Auth::user()->id)
                                                    <a class="btn-editar px-2 py-0"
                                                        href="{{ route('admin.reporte.index', $actividad->id) }}"
                                                        title="Reportar registro">
                                                        <i class="fas fa-file-alt"></i>
                                                    </a>
                                                @else
                                                    <a class="btn-editar px-2 py-0"
                                                        href="{{ route('admin.reporte.reasignar', $actividad->id) }}"
                                                        title="Reasignar registro">
                                                        <i class="fa-solid fa-user-pen"></i>
                                                    </a>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
