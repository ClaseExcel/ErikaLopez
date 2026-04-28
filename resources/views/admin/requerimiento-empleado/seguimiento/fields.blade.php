<div class="row">
    <div class="text-end mb-3">
        @if ($documento != null)
            <button type="button" class="btn btn-outline-primary btn-radius px-4"
                onclick="location.href='{{ route('admin.requerimientos.cliente.download', ['id' => $requerimiento->requerimientos->id]) }}'">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="mb-2" width="20">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M7.5 7.5h-.75A2.25 2.25 0 004.5 9.75v7.5a2.25 2.25 0 002.25 2.25h7.5a2.25 2.25 0 002.25-2.25v-7.5a2.25 2.25 0 00-2.25-2.25h-.75m-6 3.75l3 3m0 0l3-3m-3 3V1.5m6 9h.75a2.25 2.25 0 012.25 2.25v7.5a2.25 2.25 0 01-2.25 2.25h-7.5a2.25 2.25 0 01-2.25-2.25v-.75" />
                </svg>
                Descargar archivos
            </button>
        @endif
    </div>

    <div class="col-12">
        <div class="form-floating mb-3">
            <select class="form-select {{ $errors->has('tipo_requerimiento_id') ? 'is-invalid' : '' }}"
                name="tipo_requerimiento_id" id="tipo_requerimiento_id" disabled>
                <option value="">Selecciona un tipo de requerimiento</option>
                @foreach ($tipo_requerimientos as $requerimientos)
                    <option value="{{ $requerimientos->id }}"
                        {{ $requerimiento->requerimientos->tipo_requerimiento_id == $requerimientos->id ? 'selected' : '' }}>
                        {{ $requerimientos->nombre }}
                    </option>
                @endforeach
            </select>

            <label class="fw-normal" for="tipo_requerimiento_id">Tipo de requerimiento</label>
            @if ($errors->has('tipo_requerimiento_id'))
                <span class="text-danger">{{ $errors->first('tipo_requerimiento_id') }}</span>
            @endif
        </div>
    </div>

    <div class="col-12 mb-3">
        <label for="descripcion" class="fw-normal">Descripción</label>
        <textarea id="descripcion" rows="5" name="descripcion" class="form-control" disabled>{{ $requerimiento->requerimientos->descripcion }}</textarea>
        @if ($errors->has('descripcion'))
            <p id="descripcion" class="text-danger">{{ $errors->first('descripcion') }}
            </p>
        @endif
    </div>

    <div class="col-12">
        <div class="form-floating mb-3">
            @if (!is_null($requerimiento->estado_requerimiento_id))
                <select id="estado_requerimiento" name="estado_requerimiento_id" class="form-select">
                    <option value="" selected disabled>Selecciona un estado</option>
                    @foreach ($estados as $estado)
                        @if ($requerimiento->estado_requerimiento_id == $estado->id)
                            <option
                                value="{{ old('estado_requerimiento_id', $requerimiento->estado_requerimiento_id) }}">
                                {{ $estado->nombre }} </option>
                        @else
                            <option value={{ $estado->id }}> {{ $estado->nombre }} </option>
                        @endif
                    @endforeach
                </select>
            @else
                <select id="estado_requerimiento" name="estado_requerimiento_id" class="form-select">
                    <option value="" selected disabled>Selecciona un estado</option>
                    @foreach ($estados as $estado)
                        <option value="{{ $estado->id }}">{{ $estado->nombre }}</option>
                    @endforeach
                </select>
            @endif
            <label for="estado_requerimiento" class="fw-normal">Estado requerimento <b class="text-danger">*</b></label>

            @if ($errors->has('estado_requerimiento_id'))
                <p id="estado_requerimiento_id" class="text-danger">
                    {{ $errors->first('estado_requerimiento_id') }}</p>
            @endif
        </div>
    </div>

    <div class="my-3" id="documento" style="display:none;">
        <div class="row">
            <div class="col-md-6 mb-3">
                <input type="file" name="documento" class="form-control" />
            </div>
            @for ($i = 1; $i <= 5; $i++)
                <div class="col-md-6 mb-3">
                    <input type="file" name="documento_extra[]" class="form-control" />
                </div>
            @endfor
        </div>
    </div>

    <div class="col-12 mb-3">
        <label for="observacion" class="fw-normal">Observación <b class="text-danger">*</b></label>
        <textarea id="observacion" rows="3" name="observacion" class="form-control">{{ $requerimiento->observacion }}</textarea>
        @if ($errors->has('observacion'))
            <p id="observacion" class="text-danger">{{ $errors->first('observacion') }}
            </p>
        @endif
    </div>
</div>
