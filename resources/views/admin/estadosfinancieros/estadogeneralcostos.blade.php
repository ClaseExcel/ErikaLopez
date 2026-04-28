
@extends('layouts.admin')
@section('title', 'Estado Resultados Costos')
@section('content')
<style>
    .tabla-financiera {
        font-size: 13px;
        table-layout:auto;
        border-collapse:separate;
    }

    .tabla-financiera th {
        background-color: #919396;
        color: white;
        text-align: center;
        vertical-align: middle;
    }

    .tabla-financiera td {
        vertical-align: middle;
        padding: 6px 10px;
    }

    .titulo-seccion {
        background-color: #d0ebfb !important;
        color: black !important;
        font-weight: bold;
    }

    .total-general {
        background-color: #3fbdee !important;
        color: white !important;
        font-weight: bold;
    }

    .numero {
        text-align: right;
        white-space: nowrap;
    }

    .descripcion-col{
        white-space:normal;
        word-break:normal;
    }

    .tabla-financiera tbody tr:hover {
        background-color: #f5f5f5;
    }

    /* CUENTA */
    .tabla-financiera thead th{
        position: sticky;
        top: 0;
        z-index: 10;
    }
    .tabla-financiera thead th{
        position: sticky;
        top: 0;
        z-index: 10;
        background:#919396;
    }
    .tabla-financiera{
        border-collapse: separate;
    }
    /* ancho estable para columnas de meses */
    /* .numero{
        min-width:110px;
    } */
    .toggle-seccion{
        cursor:pointer;
    }

    .toggle-seccion:hover{
        background:#bfe3f7 !important;
    }
    .tabla-financiera td{
        background:white;
    }
    .flecha{
        display:inline-block;
        width:15px;
        margin-right:6px;
        font-weight:bold;
    }

    .tabla-financiera th:nth-child(1),
    .tabla-financiera td:nth-child(1){
        width:50px;
    }

    .tabla-financiera th:nth-child(2),
    .tabla-financiera td:nth-child(2){
        width:15%;
    }
    .tabla-financiera th:nth-child(n+3):nth-child(-n+14),
    .tabla-financiera td:nth-child(n+3):nth-child(-n+14){
        text-align:right;
        width:80px;
    }
    .tabla-financiera td:nth-child(n+3),
    .tabla-financiera th:nth-child(n+3){
        text-align:right;
        min-width:90px;
        white-space:nowrap;
    }

    .tabla-financiera td{
        padding:6px 8px;
    }
    .tabla-financiera tbody tr:hover{
        background:#f5f9fc;
    }
    .tabla-financiera tbody tr:nth-child(even){
        background:#fafafa;
    }
    .flecha{
        display:inline-block;
        transition:transform 0.2s;
    }

    .flecha.rotar{
        transform:rotate(-90deg);
    }
    .subtotal{
        background-color:#d0ebfb !important;
        font-weight:600;
        border-top:2px solid #3fbdee;
    }

    .subtotal td{
        white-space:nowrap;
    }
    .fijo{
        position: sticky;
        top: 25px;   /* altura del thead */
        z-index: 9;
        background:#f4f9fc;
    }
</style>
    <div class="form-group">
        <a class="btn btn-light border btn-radius px-4" href="{{ URL::previous() }}">
            <i class="fas fa-arrow-circle-left"></i> Atrás
        </a>
    </div>
    <div class="card">
        <div class="card-header border-0 pb-0 pb-0 mt-1 py-2 d-flex justify-content-between align-items-center">
            <h5><i class="fa-solid fa-table-columns"></i> Estado de ingresos y gastos mes a mes</h5>
            <div class="d-flex ml-auto">
                    <!-- Exportar a excel -->
                <div id="exportExcel" >
                    <form id="tableForm"  action="{{ route('admin.estadosfinancieros.pdfgeneral') }}" method="POST" >
                        @csrf
                        <input type="hidden" name="tipopdf" id="tipopdf" value='5'>
                        <input type="hidden" name="compania" value="{{$compania}}">
                        <input type="hidden" name="fecha" value="{{$fecha_inicio}}">
                        <input type="hidden" name="tableData" id="tableData" value="">
                        <input type="hidden" name="totales" value="">
                        <button type="submit" class="btn btn-sm btn-save btn-radius px-3"><i class="fa-solid fa-file-excel"></i></button>
                    </form>
                </div>
                <div id="download-pdf" >
                    <form id="tableForm"  action="{{ route('admin.estadosfinancieros.pdfgeneral') }}" method="POST" target="_blank">
                        @csrf
                        <input type="hidden" name="tipopdf" id="tipopdf" value='4'>
                        <input type="hidden" name="compania" value="{{$compania}}">
                        <input type="hidden" name="fecha" value="{{$fecha_inicio}}">
                        <input type="hidden" name="tableData" id="tableData" value="">
                        <input type="hidden" name="totales" value="">
                        <button type="submit" class="btn btn-sm btn-save btn-radius px-3"><i class="fa-solid fa-file-pdf fa-lg"></i></button>
                    </form>
                </div> 

            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table border-0" style="width: 100%;">
                        <tr>
                            <td class="align-middle border-0">
                                <h4 class="title mb-0">
                                    {{ $compania }}
                                </h4>
                                 <h5 class="mb-0">
                                    Estado de ingresos y gastos mes a mes
                                </h5>
                                <h5 class="mb-0">
                                    Periodo: {{ $fecha }}
                                </h5>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            @php
            function filaTieneValores($fila, $mesesMostrar) {
                foreach ($mesesMostrar as $mes) {
                    if (($fila->$mes ?? 0) != 0) {
                        return true;
                    }
                }
                return ($fila->total_acumulado ?? 0) != 0;
            }
            @endphp
            <div class="row table-responsive">
               <div class="row">
                    <div style="overflow:auto; max-height:600px;">
                        <table class="table table-bordered table-sm tabla-financiera">
                            <thead>
                                <tr>
                                    <th style="width: 120px;">Cuenta</th>
                                    <th class="descripcion-col">Descripción</th>

                                    @foreach($mesesMostrar as $mes)
                                        <th class="numero">{{ ucfirst($mes) }}</th>
                                    @endforeach

                                    <th class="numero">Acumulado</th>
                                </tr>
                            </thead>
                            @php
                                $grupo4 = $informeResultados->filter(fn($f) => str_starts_with($f->cuenta, '4'));
                                $grupo5 = $informeResultados->filter(fn($f) => str_starts_with($f->cuenta, '5'));
                                $grupo6 = $informeResultados->filter(fn($f) => str_starts_with($f->cuenta, '6'));
                                $grupo7 = $informeResultados->filter(fn($f) => str_starts_with($f->cuenta, '7'));
                            @endphp
                            <tbody>
                            {{-- ===== ingresos (4) ===== --}}
                            <tr class="titulo-seccion fijo text-left toggle-seccion" data-target="grupo4">
                                <td class="titulo-seccion" colspan="{{ 2 + count($mesesMostrar) + 1 }}">
                                    <span class="flecha">▼</span> Ingresos
                                </td>
                            </tr>

                            @foreach($grupo4 as $fila)
                            @if(filaTieneValores($fila,$mesesMostrar))
                                    <tr class="grupo4">
                                    <td>{{ $fila->cuenta }}</td>
                                    <td>{{ $fila->descripcion }}</td>

                                    @foreach($mesesMostrar as $mes)
                                        <td class="text-end">
                                            {{ number_format($fila->$mes ?? 0, 0, ',', '.') }}
                                        </td>
                                    @endforeach

                                    <td class="text-end">
                                        {{ number_format($fila->total_acumulado ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endif

                            @endforeach
                            {{-- Subtotal grupo 4 --}}
                            <tr class="subtotal">
                                <td class="subtotal" colspan="2">Subtotal Ingresos</td>

                                @foreach($mesesMostrar as $mes)
                                    <td class="subtotal text-end">
                                        {{ number_format($grupo4->sum($mes), 0, ',', '.') }}
                                    </td>
                                @endforeach

                                <td class="subtotal text-end">
                                    {{ number_format($grupo4->sum('total_acumulado'), 0, ',', '.') }}
                                </td>
                            </tr>


                            {{-- Espacio coqueto --}}
                            <tr>
                                <td colspan="{{ 2 + count($mesesMostrar) + 1 }}" style="height:15px;"></td>
                            </tr>

                            {{-- ===== Gastos (6) ===== --}}
                            <tr class="titulo-seccion fijo text-left toggle-seccion" data-target="grupo5">
                                <td class="titulo-seccion" colspan="{{ 2 + count($mesesMostrar) + 1 }}">
                                    <span class="flecha">▼</span> Gastos
                                </td>
                            </tr>

                            @foreach($grupo5 as $fila)
                                @if(filaTieneValores($fila,$mesesMostrar))
                                    <tr class="grupo5">
                                    <td>{{ $fila->cuenta }}</td>
                                    <td>{{ $fila->descripcion }}</td>

                                    @foreach($mesesMostrar as $mes)
                                        <td class="text-end">
                                            {{ number_format($fila->$mes ?? 0, 0, ',', '.') }}
                                        </td>
                                    @endforeach

                                    <td class="text-end">
                                        {{ number_format($fila->total_acumulado ?? 0, 0, ',', '.') }}
                                    </td>
                                    </tr>
                                @endif

                            @endforeach
                            {{-- Subtotal grupo 6 --}}
                            <tr class="subtotal">
                                <td class="subtotal" colspan="2">Subtotal Gastos</td>

                                @foreach($mesesMostrar as $mes)
                                    <td class="subtotal text-end">
                                        {{ number_format($grupo5->sum($mes), 0, ',', '.') }}
                                    </td>
                                @endforeach

                                <td class="subtotal text-end">
                                    {{ number_format($grupo5->sum('total_acumulado'), 0, ',', '.') }}
                                </td>
                            </tr>


                            {{-- Espacio coqueto --}}
                            <tr>
                                <td colspan="{{ 2 + count($mesesMostrar) + 1 }}" style="height:15px;"></td>
                            </tr>

                            {{-- ===== COSTOS (6) ===== --}}
                            <tr class="titulo-seccion fijo text-left toggle-seccion" data-target="grupo6">
                                <td class="titulo-seccion" colspan="{{ 2 + count($mesesMostrar) + 1 }}">
                                    <span class="flecha">▼</span> Costos
                                </td>
                            </tr>

                            @foreach($grupo6 as $fila)
                            @if(filaTieneValores($fila,$mesesMostrar))
                                    <tr class="grupo6">
                                    <td>{{ $fila->cuenta }}</td>
                                    <td>{{ $fila->descripcion }}</td>

                                    @foreach($mesesMostrar as $mes)
                                        <td class="text-end">
                                            {{ number_format($fila->$mes ?? 0, 0, ',', '.') }}
                                        </td>
                                    @endforeach

                                    <td class="text-end">
                                        {{ number_format($fila->total_acumulado ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endif

                            @endforeach

                            {{-- Subtotal grupo 6 --}}
                            <tr class="subtotal">
                                <td class="subtotal" colspan="2">Subtotal Costos</td>

                                @foreach($mesesMostrar as $mes)
                                    <td class="subtotal text-end">
                                        {{ number_format($grupo6->sum($mes), 0, ',', '.') }}
                                    </td>
                                @endforeach

                                <td class="subtotal text-end">
                                    {{ number_format($grupo6->sum('total_acumulado'), 0, ',', '.') }}
                                </td>
                            </tr>


                            {{-- Espacio coqueto --}}
                            <tr>
                                <td colspan="{{ 2 + count($mesesMostrar) + 1 }}" style="height:15px;"></td>
                            </tr>


                            {{-- ===== Costos de producción (7) ===== --}}
                            <tr class="titulo-seccion fijo text-left toggle-seccion" data-target="grupo7">
                                <td class="titulo-seccion" colspan="{{ 2 + count($mesesMostrar) + 1 }}">
                                   <span class="flecha">▼</span> Costos de producción
                                </td>
                            </tr>

                            @foreach($grupo7 as $fila)
                            @if(filaTieneValores($fila,$mesesMostrar))
                                <tr class="grupo7">
                                <td>{{ $fila->cuenta }}</td>
                                <td>{{ $fila->descripcion }}</td>

                                @foreach($mesesMostrar as $mes)
                                    <td class="text-end">
                                        {{ number_format($fila->$mes ?? 0, 0, ',', '.') }}
                                    </td>
                                @endforeach

                                <td class="text-end">
                                    {{ number_format($fila->total_acumulado ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endif
                            @endforeach

                            {{-- Subtotal grupo 7 --}}
                            <tr class="subtotal">
                                <td class="subtotal" colspan="2">Subtotal Costos de producción</td>

                                @foreach($mesesMostrar as $mes)
                                    <td class="subtotal text-end">
                                        {{ number_format($grupo7->sum($mes), 0, ',', '.') }}
                                    </td>
                                @endforeach

                                <td class="subtotal text-end">
                                    {{ number_format($grupo7->sum('total_acumulado'), 0, ',', '.') }}
                                </td>
                            </tr>

                            {{-- ===== TOTAL GENERAL ===== --}}
                            <tr class="total-general">
                                <td class="total-general" colspan="2">UTILIDAD DEL EJERCICIO</td>

                                @foreach($mesesMostrar as $mes)
                                    <td class="total-general text-end">
                                        {{ number_format(
                                            $grupo4->sum($mes) 
                                            - $grupo5->sum($mes) 
                                            - $grupo6->sum($mes) 
                                            - $grupo7->sum($mes),
                                        0, ',', '.') }}
                                    </td>
                                @endforeach

                                <td class="total-general text-end">
                                    {{ number_format(
                                        $grupo4->sum('total_acumulado') 
                                        - $grupo5->sum('total_acumulado') 
                                        - $grupo6->sum('total_acumulado') 
                                        - $grupo7->sum('total_acumulado'),
                                    0, ',', '.') }}
                                </td>
                            </tr>

                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script id="ejhgoj">
document.querySelectorAll('.toggle-seccion').forEach(header => {

    header.addEventListener('click', function(){

        const grupo = this.dataset.target;
        const flecha = this.querySelector('.flecha');

        document.querySelectorAll('.'+grupo).forEach(row=>{
            row.classList.toggle('d-none');
        });

        flecha.classList.toggle('rotar');

    });

});

</script>
@endsection
