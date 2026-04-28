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


<div class="form-floating mb-3">
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


<div class="form-floating mb-3">
    <textarea id="descripcion" rows="5" name="descripcion" class="form-control border-0" style="height: 100px"
        disabled>{{ $requerimiento->requerimientos->descripcion }}</textarea>
    @if ($errors->has('descripcion'))
        <p id="descripcion" class="text-danger">{{ $errors->first('descripcion') }}</p>
    @endif
    <label for="descripcion" class="">Descripción</label>
</div>


<div class="form-floating mb-3">

    @if ($requerimiento->estado_requerimiento_id == 4 || $requerimiento->estado_requerimiento_id == 5)
        <select id="estado_requerimiento" name="estado_requerimiento_id" disabled class="form-select">
            <option value="" disabled>Selecciona un estado</option>
            @foreach ($estados as $estado)
                <option value="{{ $estado->id }}"
                    {{ old('estado_requerimiento_id', $requerimiento->estado_requerimiento_id) == $estado->id ? 'selected' : '' }}>
                    {{ $estado->nombre }} </option>
            @endforeach
        </select>
    @else
        <select id="estado_requerimiento" name="estado_requerimiento_id" class="form-select">
            <option value="" disabled selected>Selecciona un estado</option>
            @foreach ($estados as $estado)
                <option value="{{ $estado->id }}"
                    {{ old('estado_requerimiento_id', $requerimiento->estado_requerimiento_id) == $estado->id ? 'selected' : '' }}>
                    {{ $estado->nombre }} </option>
            @endforeach
        </select>
    @endif
    <label for="estado_requerimiento" class="">Estado requerimento <b class="text-danger">*</b></label>

    @if ($errors->has('estado_requerimiento_id'))
        <p id="estado_requerimiento_id" class="text-danger">
            {{ $errors->first('estado_requerimiento_id') }}</p>
    @endif
</div>

@if ($requerimiento->estado_requerimiento_id == 4 || $requerimiento->estado_requerimiento_id == 5)
    <div class="form-floating mb-3">
        <textarea id="observacion" rows="3" name="observacion" disabled class="form-control" style="height: 100px">
            {{ $requerimiento->observacion }}
        </textarea>
        <label for="observacion" class="fw-normal">Observación <b class="text-danger">*</b></label>
        @if ($errors->has('observacion'))
            <p id="observacion" class="text-danger">
                {{ $errors->first('observacion') }}
            </p>
        @endif
    </div>
@else
    <div class="form-floating mb-3">
        <textarea id="observacion" rows="3" name="observacion" class="form-control" style="height: 100px">{{ $requerimiento->observacion }}</textarea>
        <label for="observacion" class="">Observación <b class="text-danger">*</b></label>
        @if ($errors->has('observacion'))
            <p id="observacion" class="text-danger">
                {{ $errors->first('observacion') }}
            </p>
        @endif
    </div>
@endif


<div class="form-floating mb-3" id='fecha' style="display:none;">
    <input datepicker datepicker-autohide type="date" name="fecha_vencimiento"
        value="{{ old('fecha_vencimiento', date('Y-m-d')) }}" class="form-control" placeholder="Selecciona una fecha">
    <label for="fecha_vencimiento" class="">
        Fecha de vencimiento <b class="text-danger">*</b>
    </label>

    @if ($errors->has('fecha_vencimiento'))
        <p id="fecha_vencimiento" class="text-danger">
            {{ $errors->first('fecha_vencimiento') }}
        </p>
    @endif
</div>

<div class="form-floating mb-3" id='responsable' style="display:none;">
    <select id="user" name="user_id" class="form-select">
        <option value="" selected disabled>Selecciona un responsable</option>
        @foreach ($responsables as $responsable)
            <option value="{{ $responsable->id }}"
                {{ old('user_id', $requerimiento->user_id) == $responsable->id ? 'selected' : '' }}>
                {{ $responsable->nombres . ' ' . $responsable->apellidos }} </option>
        @endforeach
    </select>
    <label for="user" class="">Responsable requerimiento <b class="text-danger">*</b></label>

    @if ($errors->has('user_id'))
        <p id="user_id" class="text-danger ">
            {{ $errors->first('user_id') }}
        </p>
    @endif

</div>
