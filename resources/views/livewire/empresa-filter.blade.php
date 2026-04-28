<div>
    {{-- Stop trying to control. --}}
    @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
        <div>
            <div class="form-floating mb-3">
                <input class="form-control" list="datalistOptions" placeholder="Escribe Para Buscar..." name="empresa_id"
                    id="empresa_id" {{-- wire:model.debounce.500ms="empresa"  --}} autocomplete="off">
                <datalist id="datalistOptions">
                    @foreach ($empresas as $empresa)
                        <option value="{{ $empresa->id }} - {{ $empresa->razon_social }}" data-id="{{ $empresa->id }}">
                        </option>
                    @endforeach
                </datalist>
                <label class="fw-normal">Empresa</label>
            </div>

            <div class="form-check pb-3 pl-4">
                <input class="form-check-input" type="checkbox" id="todasLasEmpresas" wire:model="todasLasEmpresas">
                <label class="form-check-label" for="todasLasEmpresas">
                    Todas las empresas
                </label>
            </div>
        </div>


        <div class="form-floating  mb-3">
            <input class="form-control" list="datalistOptionsr" placeholder="Escribe Para Buscar..."
                name="responsable_id" id="responsable_id" {{-- wire:model.debounce.500ms="responsable"  --}} autocomplete="off">
            <datalist id="datalistOptionsr">
                @if ($responsables->count() == 0)
                    <option value="" selected>Todos los responsables</option>
                    @foreach ($responsables as $responsable)
                        <option
                            value="{{ $responsable->user_id }}- {{ $responsable->nombres . ' ' . $responsable->apellidos }}"
                            data-id="{{ $responsable->user_id }}">

                        </option>
                    @endforeach
                @else
                    <option value="">Todos los responsables</option>
                @endif
            </datalist>
            <label class="fw-normal">Responsables</label>
        </div>
    @else
        @if (Auth::user()->role_id != 7 && Auth::user()->role_id != 8)
            <div>
                <div class="form-floating mb-3">
                    <input class="form-control" list="datalistOptions" placeholder="Escribe Para Buscar..."
                        name="empresa_id" id="empresa_id" {{-- wire:model.debounce.500ms="empresa"  --}} autocomplete="off">
                    <datalist id="datalistOptions">
                        @foreach ($empresas as $empresa)
                            <option value="{{ $empresa['id'] }} - {{ $empresa['razon_social'] }}"
                                data-id="{{ $empresa['id'] }}"></option>
                        @endforeach
                    </datalist>
                    <label class="fw-normal">Empresa</label>
                </div>
            </div>

            <div class="form-floating  mb-3">
                <input class="form-control" list="datalistOptionsr" placeholder="Escribe Para Buscar..."
                    name="responsable_id" id="responsable_id" {{-- wire:model.debounce.500ms="responsable" --}} autocomplete="off">
                <datalist id="datalistOptionsr">
                    @if ($responsables->count() == 0)
                        <option value="" selected>Todos los responsables</option>
                        @foreach ($responsables as $responsable)
                            <option
                                value="{{ $responsable->user_id }}- {{ $responsable->nombres . ' ' . $responsable->apellidos }}"
                                data-id="{{ $responsable->user_id }}">

                            </option>
                        @endforeach
                    @else
                        <option value="">Todos los responsables</option>
                    @endif
                </datalist>
                <label class="fw-normal">Responsables</label>
            </div>
        @endif
    @endif
</div>
<script>
    // document.querySelector('#empresa_id').addEventListener('input', function() {
    //     @this.set('empresa', this.value);
    //     // Desselecciona el checkbox cuando cambia el valor del campo de empresa
    //     document.querySelector('#todasLasEmpresas').checked = false;
    //     // elimina los datos de usercreaactId
    //     document.querySelector('#usercreaactId').value = '';
    // });

    // document.querySelector('#todasLasEmpresas').addEventListener('change', function() {
    //     if (this.checked) {
    //         // Si se marca la opción "Todas las empresas", establece el valor de empresa en blanco
    //         @this.set('empresa', '');
    //         // elimina los datos de usercreaactId
    //         document.querySelector('#usercreaactId').value = '';

    //     } else {
    //         @this.set('empresa', '');
    //         @this.set('responsable', '');

    //     }
    // });
</script>
