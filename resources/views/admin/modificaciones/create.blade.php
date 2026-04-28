
@extends('layouts.admin')
@section('title', 'Agregar modificación')
@section('content')

<div class="form-group">
    <a class="btn btn-back border btn-radius px-4" href="{{ route('admin.modificaciones.index') }}">
        <i class="fas fa-arrow-circle-left"></i> Atrás
    </a>
</div>

<div class="row mb-0">
    <div class="col">
        @if (session('message'))
            <div class="alert alert-success alert-dismissible fade show d-flex justify-content-bewteen align-items-center mb-1"
                role="alert">
                <div class="col-10">
                    <i class="fa-solid fa-circle-info"></i> <b>{{ session('message') }}</b>
                </div>
                <div class="col-2 d-flex align-items-center text-center">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif
    </div>
</div>
<div class="row">
    <div class="col-12 col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-circle-plus"></i> Agregar modificación
            </div>

            <div class="card-body">
                <form method="POST"  id="formularioMovimientos" action="{{ route("admin.modificaciones.store") }}" enctype="multipart/form-data">
                    @csrf

                    <div class="form-floating mb-3">
                        <input class="form-control {{ $errors->has('periodo') ? 'is-invalid' : '' }} " 
                            type="date" placeholder="" name="periodo" id="periodo" value="{{ old('periodo', '') }}" required>
                        <label class="fw-normal" for="periodo">Periodo contable <b class="text-danger">*</b></label>
                        @if($errors->has('periodo'))
                            <span class="text-danger">{{ $errors->first('periodo') }}</span>
                        @endif
                    </div>
                    
                    <div class="form-floating mb-3">
                        <select class="form-select {{ $errors->has('compania_id') ? 'is-invalid' : '' }}" name="compania_id" id="compania_id" required>
                            <option value="">Seleccionar</option>
                            @foreach($compania as $id => $nombre)
                                <option
                                    value="{{ $id }}" {{ old('compania_id') == $id ? 'selected' : '' }} >{{ $nombre }}
                                </option>
                            @endforeach
                        </select>
                        <label class="fw-normal" for="compania_id">Compañía cliente <b class="text-danger">*</b></label>
                        @if($errors->has('compania_id'))
                            <span class="text-danger">{{ $errors->first('compania_id') }}</span>
                        @endif
                    </div>
                    <div class="form-group mb-3">
                        <input type="hidden" name="campo_modificado">
                        <input type="hidden" name="valor_ajustado">
                        <input type="hidden" name="saldoinicial">
                        <input type="hidden" name="debitos">
                        <input type="hidden" name="creditos">
                        <input type="hidden" name="saldo_original">
                        <label class="required fw-normal" for="movimiento">Cuenta contable </label>
                        <select class="form-control select2 {{ $errors->has('movimiento') ? 'is-invalid' : '' }}" name="movimiento" id="movimiento" required>
                            <option value="">Seleccionar</option>
                        </select>
                        @if($errors->has('movimiento'))
                            <span class="text-danger">{{ $errors->first('movimiento') }}</span>
                        @endif
                    </div>
                    <div class="col-12">
                        <!-- Botón de Reiniciar con un diseño más atractivo -->
                        <button id="reiniciar" type="button" class="btn btn-back border btn-radius px-4" style="display:none; margin-bottom: 20px;">
                            <i class="fas fa-redo-alt"></i> Reiniciar
                        </button>

                        <!-- Tabla de Datos con estilo atractivo -->
                        <table id="dataTable" class="table table-striped table-hover table-bordered" style="display:none;width: 100%;table-layout: fixed; ">
                            <thead>
                                <tr>
                                    <th>Cuenta</th>
                                    <th>Saldo Inicial</th>
                                    <th>Débitos</th>
                                    <th>Créditos</th>
                                    <th>Saldo Movimiento</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody id="dataTableBody">
                                <!-- Las filas se agregarán dinámicamente aquí -->
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script src="{{ asset('assets/js/modificaciones/movimiento.js') }}" defer></script>
<script>
    $(document).ready(function() {
        $('#movimiento').select2();
    });
</script>
@endsection