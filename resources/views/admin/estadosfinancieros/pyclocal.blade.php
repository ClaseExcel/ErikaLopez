@extends('layouts.admin')
@section('title',"Balance general")
@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
  
  
    .sumable {
        /* Añadir un estilo específico para las celdas sumables si es necesario */
        text-align: right;
    }
    #tableingresos {
        border-collapse: collapse;
    }

    #tableingresos thead {
        background-color: #919396; /* Fondo claro para el encabezado */
        color: #ffffff;           /* Texto oscuro */
        font-weight: bold;
    }

    #tableingresos tbody tr:hover {
        background-color: #e9ecef; /* Color de fondo para hover en las filas */
    }
</style>
<div class="form-group">
    <a class="btn btn-light border btn-radius px-4" href="{{ URL::previous() }}">
        <i class="fas fa-arrow-circle-left"></i> Atrás
    </a>
</div>
<div class="card">
    <div class="card-header  border-0 pb-0 pb-0 mt-1 py-2 d-flex justify-content-between align-items-center">
        <i class="fas fa-list"></i>&nbsp;Balance general
        <div class="ml-auto">
            <div id="download-pdf" >
                <form id="tableForm"  action="{{ route('admin.estadosfinancieros.pdfgeneral') }}" method="POST" target="_blank">
                    @csrf
                    <input type="hidden" name="tipopdf" id="tipopdf" value='0'>
                    <input type="hidden" name="compania" value="{{$compania}}">
                    <input type="hidden" name="fecha" value="{{$fecha}}">
                    <input type="hidden" name="tableData" id="tableData" value="{{$datos}}">
                    <button type="submit" class="btn btn-sm btn-save btn-radius"><i class="fa-solid fa-file-pdf fa-lg"></i></button>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 card-body">
            <h5 class="subtitle">
                {{$compania}}
                <span class="float-right">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-light border btn-radius px-4" onclick="setContext('mostrar');" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <i class="fa-solid fa-magnifying-glass"></i> Mostrar detalles
                    </button>
                </span>
            </h5>
            @include('admin.estadosfinancieros.modal', [
                'nit' => $nit,
                'fecha_inicio' => $fechareporte,
            ])
            
            <h6>
                {{strtoupper($fecha)}}
            </h6>
            <div class="table-responsive">
                <table class="table table-sm table-bordered  w-100" id="tableingresos">
                    <thead class="card-header">
                        <tr>
                            <th>Cuenta</th>
                            <th class="text-left">Nombre cuenta</th>
                            <th class="text-center">Saldo inicial</th>
                            <th class="text-center">Débito</th>
                            <th class="text-center">Crédito</th>
                            <th class="text-center">Saldo final</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Ordena los datos para que 'ACTIVOS DE MENOR CUANTIA' aparezca al final
                            $sortedDatos = $datos->sort(function ($a, $b) {
                                if ($a->nombre_orden_informes === 'ACTIVOS DE MENOR CUANTIA') {
                                    return 1; // 'ACTIVOS DE MENOR CUANTIA' va al final
                                }
                                if ($b->nombre_orden_informes === 'ACTIVOS DE MENOR CUANTIA') {
                                    return -1; // Todos los demás van antes
                                }
                                return 0; // Mantiene el orden original si ninguno es 'ACTIVOS DE MENOR CUANTIA'
                            });
                        @endphp
                        @foreach ($sortedDatos as $item)
                            <tr>
                                <td>
                                    @if ($item->nombre_orden_informes === 'ACTIVOS DE MENOR CUANTIA')
                                        5395
                                    @else
                                        {{ $item->cuenta }}
                                    @endif
                                </td>
                                <td class="text-left">{{ $item->nombre_orden_informes }}</td>
                                <td class="sumable">${{ number_format((float)str_replace(',', '', $item->saldoinicial), 0, '.', ',') }}</td>
                                <td class="sumable">${{ number_format((float)str_replace(',', '', $item->debitos), 0, '.', ',') }}</td>
                                <td class="sumable">${{ number_format((float)str_replace(',', '', $item->creditos), 0, '.', ',') }}</td>
                                <td class="sumable">${{ number_format((float)str_replace(',', '', $item->saldo_mov), 0, '.', ',') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
       $('#tableingresos').DataTable({
           "order": [[0, "asc"]] // Ordena la primera columna en orden ascendente
           "paginate" :false;
       });
   });

</script>
@endsection


