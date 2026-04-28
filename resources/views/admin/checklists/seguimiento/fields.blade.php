<div class="row">
    <input type="hidden" id="checklist_empresa_id" name="checklist_empresa_id" value="{{ $checklist->id }}">

    @if (request()->routeIs('admin.seguimiento_checklist.create'))
        <div class="col-12 col-xl-6">
            <div class="form-floating mb-4">
                <input type="month" id="mes" name="mes" class="form-control" min="{{ $checklist->año }}-01"
                    max="{{ $checklist->año }}-12">
                <label for="mes" class="fw-normal">Mes <strong class="text-danger"> *</strong></label>
                <input type="hidden" id="mesexistente" value="{{ old('mes', '') }}">
                @if ($errors->has('mes'))
                    <span class="text-danger">{{ $errors->first('mes') }}</span>
                @endif
            </div>
        </div>
    @else
        <input type="hidden" id="mes" name="mes" value="{{ $seguimiento->mes }}">
    @endif

    <div class="col-xl-12 ml-2 mb-3">
        <style>
            .max-text-resume {
                max-height: 520px;
                overflow-y: auto;
                overflow-x: auto;
                scroll-behavior: smooth;
                scrollbar-width: thin;
                /* Separar el scroll a la izquierda */
                padding-right: 16px;
            }
        </style>

        <table class="table" id="example">
            <thead>
                <tr>
                    <th class="text-help">Actividades</th>
                    <th class="text-help">Observaciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($actividades as $actividad)
                    <tr>
                        <td class="align-middle">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="{{ $actividad->id }}"
                                    id="check{{ $actividad->id }}" name="actividades_presentadas[]"
                                    {{ in_array($actividad->id, json_decode($seguimiento->actividades_presentadas) ?? []) ? 'checked' : '' }}
                                    <label class="form-check-label" for="check{{ $actividad->id }}"
                                    name="actividades_presentadas[]">
                                {{ $actividad->nombre }}
                                </label>
                            </div>
                        </td>
                        <td>
                            <textarea class="form-control" name="observaciones[{{ $actividad->id }}]" id="observaciones{{ $actividad->id }}"
                                rows="1">{{ $observaciones ? $observaciones[$actividad->id] : '' }}</textarea>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
