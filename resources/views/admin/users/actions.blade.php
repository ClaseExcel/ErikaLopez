@can('VER_USUARIOS')
    <a class="btn btn-xs btn-primary" href="{{ route('admin.users.show', $id) }}">
        Ver
    </a>
@endcan

@can('EDITAR_USUARIOS')
    <a class="btn btn-xs btn-info" href="{{ route('admin.users.edit', $id) }}">
        Editar
    </a>
@endcan

@can('ELIMINAR_USUARIOS')
    @if ($id != 1)
        <form action="{{ route('admin.users.destroy', $id) }}" method="POST"
            onsubmit="return confirm('¿Estas seguro?');" style="display: inline-block;">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" class="btn btn-xs btn-danger" value="Eliminar">
        </form>
    @endif
@endcan
