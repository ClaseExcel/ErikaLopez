<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $documentTitle }}</title>

    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .page-break {
            page-break-after: always;
        }
        .table {
            border-collapse: collapse;
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
        }
        .table td,
        .table th {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }
        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }
        .table tbody + tbody {
            border-top: 2px solid #dee2e6;
        }
        .table .table {
            background-color: #fff;
        }
        .table-sm td,
        .table-sm th {
            padding: 0.3rem;
        }
        .table-bordered {
            border: 1px solid #dee2e6;
        }
        .table-bordered td,
        .table-bordered th {
            border: 1px solid #dee2e6;
        }
        .table-bordered thead td,
        .table-bordered thead th {
            border-bottom-width: 2px;
        }
        .table-borderless tbody + tbody,
        .table-borderless td,
        .table-borderless th,
        .table-borderless thead th {
            border: 0;
        }
    </style>
</head>
<body>
            
    <img src="{{$logo}}" class="img-fluid py-4" alt="logo-empresa" style="height:50px;float:right;margin-right:5%">  
    <br><br><br><br><br>
    <strong style="font-size:18px;color:#003463;">  Informe Gerencial</strong>
    <div class="m-0 py-0">{{ $razonSocial }} -  {{ $nit }} </div> 
    <small style="font-size:13px;"><i class="">{{ $fechaInicio }} hasta {{ $fechaFin }}</i></small>   
    <br><br><br><br><br>
    <div>
        <br><br>
        <img src="{{ $graficoCostosGastos }}" style="width: 340px;">
        <img src="{{ $graficoIngresos }}" style="width: 340px;">
        <br><br>
        <img src="{{ $graficoSituacion }}"  style="width: 340px; margin-left:25%; margin-right:25%">
        <img src="{{ $graficoBalanceSituacion }}"  style="width: 340px;">        
                
    </div>
    
    <div class="page-break"></div>

    <img src="{{$logo}}" class="img-fluid py-4" alt="logo-empresa" style="height:50px;float:right;margin-right:5%">
    <br><br><br><br><br>

    <strong style="font-size:18px;color:#003463;">Informe Gerencial</strong><br> 
    {{ $razonSocial }} -  {{ $nit }}<br> 
    <small style="font-size:13px;"><i class="">{{ $fechaInicio }} hasta {{ $fechaFin }}</i></small>   
    <br><br><br><br> 

    <table class="table table-sm table-bordered mb-4" style=" width:94%">
        <tr>
            <td colspan="2" class="text-center" style="background-color:rgb(18 102 117);color:white"><strong>Balance situacion</strong></td>
        </tr>
        <tr>
            <td>Total activo</td>
            <td style="text-align: right; width:30%">$ {{ $totalActivo }}</td>
        </tr>     
        <tr>
            <td>Total pasivo</td>
            <td style="text-align: right; width:30%">$ {{ $totalPasivo }}</td>
        </tr>    
        <tr>
            <td>Patrimonio</td>
            <td style="text-align: right; width:30%">$ {{ $totalPatrimonio }}</td>
        </tr>                                                             
    </table>
    <br>          
    <table class="table table-sm table-bordered mb-4" style=" width:94%">
        <tr> 
            <td colspan="2" class="text-center" style="background-color:rgb(18 102 117);color:white"><strong>Desempeño financiero</strong></td>
        </tr>
        <tr>
            <td>Ingresos</td>
            <td style="text-align: right; width:30%">$ {{ $ingresos }}</td>
        </tr>
        <tr>
            <td>Costos de Ventas</td>
            <td style="text-align: right; width:30%">$ {{ $costoVentas }}</td>
        </tr>
        <tr>
            <td>Utilidad bruta</td>
            <td style="text-align: right; width:30%">$ {{ $utilidadBruta }}</td>
        </tr>
        <tr>
            <td>Gastos de Administración</td>
            <td style="text-align: right; width:30%">$ {{ $gastosAdministracion }}</td>
        </tr>
        <tr>
            <td>Gastos de Ventas</td>
            <td style="text-align: right; width:30%">$ {{ $gastosVentas }}</td>
        </tr>
        <tr>
            <td>Otros Gastos</td>
            <td style="text-align: right; width:30%">$ {{ $otrosGastos }}</td>
        </tr>
        <tr>
            <td>{{ $mensajeUtilidad }}</td>
            <td style="text-align: right; width:30%">$ {{ $utilidadNeta }}</td>
        </tr>
    </table>
    <br>
    
    


    <div class="page-break"></div>

    
    <img src="{{$logo}}" height="50" class="img-fluid py-4" alt="logo-empresa" style="height:50px;float:right; margin-right:5%">
    <br><br><br><br><br>
    <table class="table table-sm table-bordered mb-4" style=" width:94%">
        <tr>
            <td colspan="2" class="text-center" style="background-color:rgb(18 102 117);color:white"><strong>Indicadores financieros</strong></td>
        </tr>
        <tr>
            <td>EBITDA</td>
            <td style="text-align: right; width:30%">$ {{ $ebitda }}</td>
        </tr>
        <tr>
            <td>Capital de Trabajo</td>
            <td style="text-align: right; width:30%">$ {{ $capitalTrabajo }}</td>
        </tr>
        <tr>
            <td>Margen de Ganancia</td>
            <td style="text-align: right; width:30%">{{ $margenGananciaNeta }}%</td>
        </tr>
        <tr>
            <td>ROA</td>
            <td style="text-align: right; width:30%">{{ $rentabilidadActivosROA }}%</td>
        </tr>
        <tr>
            <td>ROE</td>
            <td style="text-align: right; width:30%">{{ $rentabilidadPatrimonioROE }}%</td>
        </tr>
        <tr>
            <td>Liquidez Corriente</td>
            <td style="text-align: right; width:30%">{{ $liquidezCorriente }}</td>
        </tr>
        <tr>
            <td>Prueba Ácida</td>
            <td style="text-align: right; width:30%">{{ $pruebaAcida }}</td>
        </tr>
        <tr>
            <td>Nivel de Endeudamiento</td>
            <td style="text-align: right; width:30%">{{ $nivelEndeudamiento }}</td>
        </tr>
    </table>   
    <br>
    <table class="table table-sm table-bordered mb-4" style=" width:94%">   
        <tr>
            <td colspan="2" class="text-center" style="background-color:rgb(18 102 117);color:white"><strong>Impuestos DIAN</td>
        </tr>
        <tr>
            <td>IVA Generado</td>
            <td style="text-align: right; width:30%">$ {{ $ivaGenerado }}</td>
        </tr>
        <tr>
            <td>IVA Descontable</td>
            <td style="text-align: right; width:30%">$ {{ $ivaDescontable }}</td>
        </tr>
        <tr>
            <td>{{ $titleIva }}</td>
            <td style="text-align: right; width:30%">$ {{ $ivaTotalPagarFavor }}</td>
        </tr>
    </table>
    <br>
    <table class="table table-sm table-bordered mb-4" style=" width:94%">
        <tr>
            <td colspan="2" class="text-center" style="background-color:rgb(18 102 117);color:white"><strong>Impuesto al consumo generado</strong></td>
        </tr>
        <tr>
            <td>Impuesto al consumo generado</td>
            <td style="text-align: right; width:30%">$ {{ $impuestoConsumoGenerado }}</td>
        </tr>                                                                
    </table>
    <br>
    
        
    
</body>
</html>