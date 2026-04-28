<div>

    <div class="form-floating mb-3">
        Estado
        <div class="form-check form-switch" style="padding-left: 39px;">
            <input class="form-check-input" type="checkbox" id="estado" name="estado"
                {{ $estado == '1' ? 'checked' : '' }} wire:change="cambiarEstado">

            @if ($estado == '1')
                <div class="fw-normal badge badge-success rounded-pill text-start mx-3" style="font-size: 12px; width:72px">
                    <i class="fas fa-check-circle"></i> Activo
                </div>
            @else
                <div class="fw-normal badge badge-danger rounded-pill text-start mx-3" style="font-size: 12px; width:72px">
                    <i class="fas fa-times-circle"></i> Inactivo
                </div>
            @endif

        </div>
    </div>

    <style>
        /* aumentar el tamaño del input de estado */
        .form-check-input[type=checkbox] {
            width: 3em;
            height: 1.2em;
        }
    </style>

</div>
