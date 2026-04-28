<div>
    {{-- The whole world belongs to you. --}}
    <div>
        <div class="form-floating mb-3">
            <input class="form-control" list="datalistOptions" placeholder="Escribe para buscar..."
            name="empresa_id" id="empresa_id" wire:model.debounce.500ms="empresa" autocomplete="off">
            <datalist id="datalistOptions">
                @foreach ($empresas as $empresa)
                    <option value="{{ $empresa->id }} - {{ $empresa->razon_social }}" data-id="{{ $empresa->id }}"></option>
                @endforeach
            </datalist>
            <label class="fw-normal" for="empresa_id">Empresas</label>
        </div>

        <div class="form-floating mb-3">
            <input class="form-control" list="datalistOptionsUser" placeholder="Escribe para buscar..."
            name="user_id" id="user_id" wire:model.debounce.500ms="usuario" autocomplete="off">
            <datalist id="datalistOptionsUser">
                @foreach ($usuarios as $usuario)
                    <option value="{{ $usuario->id . ' - ' . $usuario->nombres . ' ' . $usuario->apellidos }}"  data-id="{{ $usuario->id }}"></option>
                @endforeach
            </datalist>
            <label class="fw-normal" for="user_id">Responsables</label>
        </div>
    </div>
</div>
