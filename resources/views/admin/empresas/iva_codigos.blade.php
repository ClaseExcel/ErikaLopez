
{{-- IVA Generado inputs --}}
<div class="row mb-2">
    <label class="fw-normal mb-0 pl-3">Códigos IVA Generado</label>
    @for ($i = 1; $i <= 3; $i++)
        <div class="col-md-4">
            <div class="form-floating">
                
                <input type="text" class="form-control" name="ivaGenerado{{ $i }}" id="ivaGenerado{{ $i }}" maxlength="10"
                    inputmode="numeric" pattern="\d{1,10}" placeholder="0" value="{{ $empresa->{'iva_generado_codigo_' . $i} }}">
                <label for="ivaGenerado{{ $i }}" class="fw-normal">código #{{ $i }}</label>

            </div>
        </div>
    @endfor
    <div class="mb-3"></div>
    {{-- IVA Descontable inputs --}}
    <label class="fw-normal mb-0 pl-3">Códigos IVA Descontable</label>
    @for ($i = 1; $i <= 4; $i++)
        <div class="col-md-3">
            <div class="form-floating mb-3">

                <input type="text" class="form-control" name="ivaDescontable{{ $i }}" id="ivaDescontable{{ $i }}"
                    maxlength="10" inputmode="numeric" pattern="\d{1,10}" placeholder="0" value="{{ $empresa->{'iva_descontable_codigo_' . $i} }}">
                <label for="ivaDescontable{{ $i }}" class="fw-normal">código #{{ $i }}</label>

            </div>
        </div>
    @endfor
</div>
{{-- End IVA Generado --}}