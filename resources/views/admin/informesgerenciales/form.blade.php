
<div class="row" id="formulario-informe" style="display:block">
    <div class="col-12 -col-md-8 col-xl-5">
        <div class="card">
            <div class="card-body">
                <div class="col-12">
                    <div class="form-floating mb-3">
                        <input class="form-control" id="compania" list="datalistOptions" placeholder="Escribe Para Buscar..."
                            oninput="updateCompania(this)" autocomplete="off">
                        <datalist id="datalistOptions">
                            @foreach ($empresas as $id => $razon_social)
                                <option value="{{ $razon_social }}" data-id="{{ $id }}">{{ $razon_social }}</option>
                            @endforeach
                        </datalist>
                        <input type="hidden" name="empresa_id" id="empresa_id">
                        <label class="fw-normal" for="empresa">Empresa</label>
                    </div>
                </div>
                {{-- Fecha inicial --}}
                <div class="col-12 mb-4" style="display: none" id="mostrarFechaInicial">
                    <div class="form-floating">
                        <input class="form-control" type="date" placeholder="" name="fechaInicial" id="fecharInicial">

                        <label class="fw-normal" for="fecharInicial">Fecha de inicial <b class="text-danger">*</b></label>
                        @if ($errors->has('fecharInicial'))
                            <span class="text-danger">{{ $errors->first('fecharInicial') }}</span>
                        @endif
                    </div>
                </div>
                {{-- Fecha final --}}
                <div class="col-12 mb-4" style="display: none" id="mostrarFechaFinal">
                    <div class="form-floating">
                        <input class="form-control" type="date" placeholder="" name="fechaFinal" id="fecharFinal">

                        <label class="fw-normal" for="fecharFinal">Fecha final <b class="text-danger">*</b></label>
                        @if ($errors->has('fecharFinal'))
                            <span class="text-danger">{{ $errors->first('fecharFinal') }}</span>
                        @endif
                    </div>
                </div>

                @include('admin.informesgerenciales.checklist')

                {{-- boton generar informe --}}
                <div class="col-12 text-center" style="display: none" id="btn-generar-informe">
                    <button class="btn btn-save btn-radius px-4">Generar Informe</button>
                </div>
            </div>
        </div>
    </div>
</div>