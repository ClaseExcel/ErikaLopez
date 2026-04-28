<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Certificación Estados Financieros</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.6;
            margin: 0; /* Eliminar márgenes para evitar recortes */
        }

        .header {
            position: fixed;
            top: -40;
            left: 0;
            right: 0;
            height: 100px;
            z-index: 9999;
            background-color: white;
            margin: 0 !important;
            padding: 0 !important;
            line-height: 0;
        }

        .header img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: contain; /* o cover según el diseño */
        }

        body {
            padding-top: 0px; /* Ajusta según la altura de tu cabecera */
            position: relative;
        }

        
        /* Estilo para el contenedor principal de contenido */
        /* Contenedor principal de contenido */
        .content-wrapper {
            margin: 0; /* Eliminar márgenes para evitar recortes */
            width: 100%; /* Mantiene el ancho igual que el contenedor principal */
            max-height: 80vh; /* Limita la altura del contenedor para que el contenido sea desplazable */
            overflow-y: inherit; /* Permite que el contenido se desplace si excede la altura */
            padding-bottom: 50px; /* Asegura que haya espacio suficiente para el footer */
            position: relative; /* Posiciona el contenido de forma normal */
        }

        .footer {
            position: fixed;
            bottom: 35px; /* Empuja el footer más abajo de lo normal */
            left: -40px;
            right: -40px;
            height: 100px;
            text-align: center;
            /* z-index: 10; */
            pointer-events: none; /* 🔥 evita que tape clicks/texto */
        }

        .footer img {
            width: 100%;
            height: auto;
            position: absolute;
            z-index: -1; /* 🔥 SIEMPRE detrás */
        }

        .footer-text {
            position: relative;
            z-index: 1; /* texto encima de la imagen */
            font-size: 11px;
            font-style: italic;
        }


        .footer-text p {
            margin: 0;
        }

        .footer-text a {
            color: #A6A6A6; /* Color del enlace */
            text-decoration: none;
            font-size: 11px; /* Ajusta el tamaño de la fuente */
            font-weight: bold;
            font-style: normal;
        }

        .footer-text a:hover {
            text-decoration: underline;
        }

        /* Ocultar el footer dentro del contenedor no-footer-container */
        .no-footer-container .footer {
            display: none ;
        }

        .certificacion {
            text-align: justify !important;
            margin-bottom: 50px;
            margin: 2cm;
            color: #333F4F;
            margin: 0 !important; /* Eliminar márgenes para evitar recortes */
        }

        .tabla-integrales {
            text-align: justify;
            margin-bottom: 2px;
            margin: 0cm;
            page-break-inside: avoid;
            border-collapse: collapse;
            margin: 0 !important; /* Eliminar márgenes para evitar recortes */
            /* Opcional: para eliminar espacio entre bordes de celdas */
        }

        .tabla-integrales th,
        .tabla-integrales td {
            border: none;
            /* Elimina los bordes de las celdas */
        }

        .firmas {
            text-align: center;
            margin-top: 50px;
            page-break-inside: avoid;
            
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border: none !important;
            margin-top: 30px;
            page-break-inside: avoid;
            margin: 0 !important; /* Eliminar márgenes para evitar recortes */
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
            font-size: 9px;
            /* Reducción del tamaño de fuente */
        }

        .titulos {
            background-color: #464648 !important;
            color: white !important;
            font-weight: bold;
        }

        .titulos2 {

            color: black;
            font-weight: bold;
        }

        .totales {
            background-color: #D9D9D9 !important;
            color: black;
            font-weight: bold;
        }

       
        .firma {
            display: inline-block;
            width: 30%;
            text-align: center;
            vertical-align: top;
            min-height: 150px; /* asegura misma altura general */
        }

        /* Contenedor de la imagen */
        .firma2,
        .firma2.no-image {
            height: 70px; /* altura reservada para la imagen */
            width: 100px;
            margin: 0 auto;
            display: block;
            position: relative;
        }

        /* Imagen centrada verticalmente */
        .firma2 img {
            max-height: 100%;
            max-width: 100%;
            display: block;
            margin: 0 auto;
            opacity: 0.85;
            mix-blend-mode: multiply;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%); /* centra perfectamente la firma dentro del bloque */
        }

        /* Línea opcional para firmas sin imagen */
        .firma2.no-image {
            border-bottom: 1px solid #000;
        }

        /* Texto siempre en la misma altura, pero más cerca si hay imagen */
        .firmatexto {
            font-size: 9px;
            color: black;
            line-height: 1.1;
            margin-top: 5px !important; /* menos separación entre imagen y texto */
        }

        .justificar{
            text-align: justify !important;
        }

        .page-break {
            page-break-before: always;
        }

        ol {
            counter-reset: item;
        }

        ol li {
            display: block;
            position: relative;
        }

        ol li::before {
            content: counter(item) ".";
            counter-increment: item;
            position: absolute;
            left: -25px;
            font-weight: bold;
        }

        .custom-border-table {
            border-collapse: collapse;
            width: 100%;
        }

        .custom-border-table th,
        .custom-border-table td {
            padding: 10px;
            text-align: left;
        }

        .custom-border-table th {
            border-top: 2px solid black;
            border-bottom: 2px solid black;
        }

        .custom-border-table tr {
            border-top: 1px solid black;
            border-bottom: 1px solid black;
        }

        /* Elimina bordes laterales de las celdas */
        .custom-border-table td,
        .custom-border-table th {
            border-left: none;
            border-right: none;
        }

        p {
            margin: 5px;
            /* Elimina el margen alrededor del párrafo */
            padding: 5px;
            /* Elimina el relleno dentro del párrafo */
         
        }

        table {
            margin: 5px;
            /* Elimina el margen alrededor de la tabla */
            padding: 2px;
            /* Elimina el relleno dentro de la tabla */
        }

        .no-break {
            page-break-inside: avoid;
        }

        .grid-container {
            display: grid;
            grid-template-columns: 1fr;
            /* Una columna de ancho completo */
        }

        .grid-item {
            background-color: #464648;
            color: white;
            font-weight: bold;
            padding: 10px;
            /* Ajusta el padding según sea necesario */
            text-align: left;
            /* Centra el texto */

        }

        .borde-inferior {
            border-bottom: 10px solid #464648;
            /* Cambia el grosor y color del borde según tus necesidades */
        }
         p.compacto {
            margin-bottom: 0.2em;
        }
    </style>
</head>
@php
    function generarNotasDesde($bloques, $informeData, $anio, $anioAnterior, $notasEspecificasBloque, $contadorNotaInicial)
    {
        $notasParaMostrarBloques = [];
        $mapaNotasOriginales = [];
        $contadorNota = $contadorNotaInicial;
        $notaIngresosFinancieros = null;
        $gastos = null;

        foreach ($bloques as $descripcion) {
            $valor1 = isset($informeData[$descripcion][$anio]) ? (float)$informeData[$descripcion][$anio] : 0;
            $valor2 = isset($informeData[$descripcion][$anioAnterior]) ? (float)$informeData[$descripcion][$anioAnterior] : 0;

            if ($valor1 == 0 && $valor2 == 0) {
                continue;
            }

            if (isset($notasEspecificasBloque[$descripcion])) {
                $notaOriginal = $notasEspecificasBloque[$descripcion];

                if (isset($mapaNotasOriginales[$notaOriginal])) {
                    $notasParaMostrarBloques[$descripcion] = $mapaNotasOriginales[$notaOriginal];
                } else {
                    $notasParaMostrarBloques[$descripcion] = $contadorNota;
                    $mapaNotasOriginales[$notaOriginal] = $contadorNota;
                    $contadorNota++;
                }
            } else {
                $notasParaMostrarBloques[$descripcion] = $contadorNota;
                $contadorNota++;
            }

            // Capturar número de nota solo para "Ingresos financieros"
            if ($descripcion === 'Ingresos de actividades ordinarias') {
                $notaIngresosFinancieros = $notasParaMostrarBloques[$descripcion];
            }
                        // Capturar número de nota solo para "Ingresos financieros"
            if ($descripcion === 'Gastos de administración' || $descripcion === 'Gastos de ventas') {
                $gastos = $notasParaMostrarBloques[$descripcion];
            }
        }

        return [
            'notas' => $notasParaMostrarBloques,
            'ultimo' => $contadorNota,
            'notaIngresosFinancieros' => $notaIngresosFinancieros,
            'gastos' => $gastos,
        ];
    }
     use Carbon\Carbon;

    $ultimoDiaMes = Carbon::createFromDate($anioAnterior, 12, 1)->endOfMonth()->day;
@endphp
<body>
     <!-- Cabecera -->
    {{-- <div class="header">
        <img src="https://clasedeexcel.com/imagenespdfs/cabecera_erikalopez.png" alt="Cabecera">
    </div> --}}
    <div class="no-footer-container">
        <!-- Página 1: Certificación -->
        <div class="certificacion">
            <h4 style="text-align: center;"><b>{{ $representantelegal['razon_social'] }}</b><br>NIT - {{$nit}}<br>CERTIFICACIÓN DE LOS ESTADOS FINANCIEROS <br> DEL REPRESENTANTE LEGAL Y EL CONTADOR DE LA COMPAÑÍA</h4>
            <div style="border-bottom: 1px solid #000;"></div>
            <p style="text-align: left">{{ $dia_numero }} de {{ $mes2 }} de {{ $anio }} </p>
            <p class="justificar">Los suscritos representante legal y contador de la compañía <b>{{ $representantelegal['razon_social'] }}</b> bajo cuya responsabilidad 
                se presentaron los estados financieros, certificamos que los estados financieros de la compañía a {{ $dia_numero }} de {{ $mes2 }} de {{ $anio }} han 
                sido fielmente tomados de los libros de la compañía y que antes de ser puestos a su disposición y de terceros, 
                hemos verificado las siguientes afirmaciones explicitas e implícitas contenidas en ellos:
            </p>
            <p class="compacto"><b>(a).</b> <b>EXISTENCIA</b> Todos los activos y pasivos incluidos en los estados financieros de <b>{{ $representantelegal['razon_social'] }}</b> a  {{ $dia_numero }} de {{ $mes2 }} de {{ $anio }} 
            existen, y todas las transacciones incluidas en dichos estados en las fechas de corte se ha realizado en el periodo correspondiente.</p>
            <p class="compacto"><b>(b).</b> <b>INTEGRIDAD</b> Todos los hechos económicos realizados por <b>{{ $representantelegal['razon_social'] }}</b> durante los periodos a corte del {{ $dia_numero }} de {{ $mes2 }} de {{ $anio }} han sido reconocidos en los estados financieros.</p>
            <p class="compacto"><b>(c).</b> <b>DERECHOS Y OBLIGACIONES </b> Los activos representan probables beneficios económicos futuros (derechos) y los pasivos representan probables sacrificios económicos futuros (obligaciones), obtenidos o a cargo de <b>{{ $representantelegal['razon_social'] }}</b> en la fecha de corte a {{ $dia_numero }} de {{ $mes2 }} de {{ $anio }}. </p>
            <p class="compacto"><b>(d).</b> <b>VALUACION </b> Todos los elementos han sido reconocidos por sus valores apropiados de acuerdo con las normas de contabilidad y de información financiera aceptadas en Colombia.</p>
            <p class="compacto"><b>(e).</b> <b>PRESENTACION Y REVELACION</b> Todos los hechos económicos que afectan a <b>{{ $representantelegal['razon_social'] }}</b> y sus subsidiarias han sido correctamente clasificados, descritos y revelados en los estados financieros.</p>
            <div class="tabla-integrales" style="font-size: 11px; margin: 0; padding: 2px !importante;">
                <div class="firmas">
                    <!-- Firma Representante Legal -->
                    <div class="firma" style="text-align: center;">
                        @if ($representantelegalfirma != '*')
                            <div class="firma2">
                                <img src="data:image/jpeg;base64,{{ $representantelegalfirma }}" alt="Firma Representante Legal">
                            </div>
                        @else
                            <div class="firma2 no-image"></div>
                        @endif
                        <div class="firmatexto" style="color:black; text-align: center; margin-top: 0;">
                            <strong>{{ $representantelegal['representantelegal'] ?? 'Sin datos encontrados' }}</strong><br>
                            Representante legal<br>C.C. {{ $representantelegal['Cedula'] ?? '' }}
                        </div>
                    </div>
                
                    <!-- Firma Contador -->
                    <div class="firma" style="text-align: center;">
                        @if ($base64Imagefirmacontador != '*')
                            <div class="firma2">
                                <img src="data:image/jpeg;base64,{{ $base64Imagefirmacontador }}" alt="Firma Contador">
                            </div>
                        @else
                            <div class="firma2 no-image"></div>
                        @endif
                        <div class="firmatexto" style="color:black; text-align: center; margin-top:0;">
                            <strong>{{ ($datoscontador['nombres'] ?? 'Sin datos') . ' ' . ($datoscontador['apellidos'] ?? '') }}</strong><br>
                            Contador Público<br>T.P {{ $datoscontador['tarje_profesional'] ?? '' }}
                        </div>
                    </div>
                
                    <!-- Firma Revisor Fiscal -->
                    @if (!empty($representantelegal['revisorfiscal']) && $representantelegal['revisorfiscal'] !== 'Sin datos encontrados')
                        <div class="firma" style="text-align: center;">
                            @if ($revisorfiscalfirma != '*')
                                <div class="firma2">
                                    <img src="data:image/jpeg;base64,{{ $revisorfiscalfirma }}" alt="Firma Revisor Fiscal">
                                </div>
                            @else
                                <div class="firma2 no-image"></div>
                            @endif
                            <div class="firmatexto" style="color:black; text-align: center; margin-top:0;">
                                <strong>{{ $representantelegal['revisorfiscal'] }}</strong><br>
                                Revisor Fiscal<br>T.P {{ $representantelegal['cedularevisor'] ?? '' }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
         <!-- Página 2: dictamen revisor fiscal  -->
        @if($dictamenFiscal)
         <div class="page-break"></div>
         <div class="certificacion">
            <h4 style="text-align: center;"><b>DICTAMEN A LOS ESTADOS FINANCIEROS</h4>
            <p><b>Señores: <br> ASAMBLEA GENERAL <br>{{ $representantelegal['razon_social'] }} <br>Medellin, Antioquia</b></p>
            <p><b>REFERENCIA:</b></p>
            <p class="justificar">Dictamen e informe Correspondiente al Ejercicio Económico de {{ $mes2 }} {{ $anio }} <br></p>
            <p class="justifacar">Respetados Señores: <br></p>
            <p class="justifacar">En mi calidad de Revisor Fiscal, he examinado el Estado de situación financiera de
                {{ $representantelegal['razon_social'] }}, con corte al {{ $dia_numero }} de {{ $mes2 }} de {{ $anio }}  el correspondiente
                Estado de Resultados Integral, el de Cambios en el Patrimonio, el de Cambios en
                la Situación Financiera y el de Flujos de Efectivo, por el año terminado en esa fecha
                y las revelaciones hechas a través de las notas que han sido preparadas como lo
                establece la ley y forman con ellos un todo indivisible. Dichos estados financieros
                fueron debidamente certificados por el Representante Legal y el Contador Público
                que los preparó, en los términos establecidos. Una de mis funciones es la de
                expresar una opinión sobre dichos estados financieros, basado en los resultados de
                mi auditoría. <br><br>
                Realicé el examen de acuerdo con las Normas de Auditoría Generalmente
                Aceptadas en Colombia. En cumplimiento de estas normas mi trabajo se desarrolló
                de la siguiente manera: Informe Económico y Social, Planificación de actividades
                partiendo del conocimiento de la entidad e identificando los principales procesos a
                efectos de determinar el enfoque, el alcance y la oportunidad de nuestras pruebas
                de auditoría. La ejecución del trabajo se llevó a cabo atendiendo las actividades
                previamente planificadas de tal manera que se permitiese obtener una seguridad
                razonable sobre la situación financiera y resultados de la Entidad. Estas actividades
                se desarrollaron con la siguiente metodología:
                <br> 
            </p>
            <p class="compacto"><b>(&#8226;).</b>Examen, sobre una base selectiva, de las evidencias que respaldan las cifras y las notas informativas a los estados financieros; </p>
            <p class="compacto"><b>(&#8226;).</b> Evaluación de principios o normas de contabilidad utilizados por la Administración;</p>
            <p class="compacto"><b>(&#8226;).</b> Evaluación de las principales estimaciones efectuadas por la administración; </p>
            <p class="compacto"><b>(&#8226;).</b> Evaluación de la presentación global de los estados financieros; </p>
            <p class="compacto"><b>(&#8226;).</b> Evaluación de las revelaciones acerca de las situaciones que así lo requirieron. </p>
            <br>
            <p class="justificar">Así, considero que mi auditoria proporciona una base razonable para fundamentar la opinión que expreso a continuación</p>
            <p class="justificar">En mi opinión, los estados financieros mencionados, fielmente tomados de los
                libros, presentan razonablemente la situación financiera de {{ $representantelegal['razon_social'] }} al
                {{ $dia_numero }} de {{ $mes2 }} de {{ $anio }} , así como los resultados de las operaciones, los cambios
                en el patrimonio, los cambios en el efectivo y los cambios en su situación financiera
                por el año terminado en esa fechas, de conformidad con normas e instrucciones de
                la Superintendencia de sociedad y principios de contabilidad generalmente
                aceptados en Colombia, aplicados uniformemente.
                En relación con la contabilidad, los libros de comercio, los actos de los
                administradores y la correspondencia, con base en el resultado y en el alcance de
                mis pruebas, conceptúo que la {{ $representantelegal['razon_social'] }}: </p>
            <br>
            <p class="compacto"><b>(&#8226;).</b> Ha llevado su contabilidad conforme a las normas legales y a latécnica contable. </p>
            <p class="compacto"><b>(&#8226;).</b> Ha dado cumplimiento a lo dispuesto por la Superintendencia de
                sociedades, a través de la Circular Básica Contable y Financiera, en
                lo referente a la aplicación de los criterios mínimos a tener en cuenta
                en el otorgamiento de créditos; la clasificación y evaluación de la
                cartera de créditos; la calificación de la cartera de créditos por nivel de
                riesgos; la suspensión de intereses e ingresos por otros conceptos y
                la constitución de provisiones 
            </p>
            <p class="compacto"><b>(&#8226;).</b> Ha dado cumplimiento a lo dispuesto por la Superintendencia de
                    sociedades, en lo referente a la aplicación de los criterios para la
                    evaluación y valoración de inversiones; para la constitución de
                    provisiones; el mantenimiento del margen de solvencia y del fondo de
                    liquidez requerido, así como para la medición y evaluación del riesgo
                    de liquidez. 
            </p>
            <p class="compacto"><b>(&#8226;).</b> Las operaciones registradas en los libros y los actos de los
                    administradores de la entidad se ajustan a las disposiciones que
                    regulan la actividad, a los estatutos y a las decisiones de la Asamblea
                    General;  
            </p>
            <p class="compacto"><b>(&#8226;).</b> La correspondencia, los comprobantes de las cuentas y los libros de
                actas y registro de socios, en su caso, se llevan y se conservan de
                manera adecuada.
            </p>
            <p class="compacto"><b>(&#8226;).</b>La empresa, cumple con las normas relacionadas con los derechos de
                    autor ley 603 de 2000.
            </p>
            <p class="justificar">{{ $representantelegal['razon_social'] }}, ha observado medidas adecuadas de control interno y de
                conservación y custodia de sus bienes y de los de terceros que pueden estar en su
                poder. Los asuntos relacionados con el Control Interno fueron expuestos en su
                debida oportunidad a la Administración.
                En relación con los aportes al Sistema de Seguridad Social, en atención de lo
                dispuesto en el artículo 11 del Decreto 1406 de 1999, y los plazos modificados en
                el decreto 1670 de 2008 y con base en el resultado de mis pruebas practicadas,
                hago constar que {{ $representantelegal['razon_social'] }}, durante el ejercicio económico de {{ $anio }},
                presentó correcta y oportunamente la información requerida en las
                autoliquidaciones de aportes al Sistema de Seguridad Social que le competían en
                el período y no se encuentra en mora por concepto de aportes al mismo.
            </p>

            <div class="tabla-integrales" style="font-size: 11px; margin: 0; padding: 2px !importante;">
                <div class="firmas">
                    <!-- Firma Revisor Fiscal -->
                    @if (!empty($representantelegal['revisorfiscal']) && $representantelegal['revisorfiscal'] !== 'Sin datos encontrados')
                        <div class="firma" style="text-align: center;">
                            @if ($revisorfiscalfirma != '*')
                                <div class="firma2">
                                    <img src="data:image/jpeg;base64,{{ $revisorfiscalfirma }}" alt="Firma Revisor Fiscal">
                                </div>
                            @else
                                <div class="firma2 no-image"></div>
                            @endif
                            <div class="firmatexto" style="color:black; text-align: center; margin-top:0;">
                                <strong>{{ $representantelegal['revisorfiscal'] }}</strong><br>
                                Revisor Fiscal<br>T.P {{ $representantelegal['cedularevisor'] ?? '' }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
         </div>
        @endif
    </div>
    <div class="content-wrapper">
         {{-- informe estado situacion financiera  --}}
        <div class="tabla-integrales" style="font-size: 10px; margin: 0; padding: 2px !important;">
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
                <tr>
                    <!-- Columna vacía -->
                    <td style="width: 33%;"></td>
                    <!-- Columna para el texto -->
                    <td style="width: 34%; text-align: center; vertical-align: top;">
                        <p style="color: #464648; margin: 0;">
                            <b>{{ $representantelegal['razon_social'] }}</b><br>
                            <b>NIT - {{$nit}}</b><br>
                            <b>ESTADO DE LA SITUACION FINANCIERA</b><br>
                            <b>a {{ strtoupper($mes2) }} {{ $dia_numero }} del {{ $anio }}.</b><br>
                            (Cifras expresadas en pesos colombianos)
                        </p>
                    </td>
                    @if($logocliente != '*')
                    <!-- Columna para el logo -->
                    <td style="width: 33%; text-align: right; vertical-align: top;">
                        <img id="logo" src="data:image/jpeg;base64,{{ $logocliente }}" style="max-width: 100px; max-height: 100px;">
                    </td>
                    @else
                    <td style="width: 33%; text-align: center; vertical-align: top;">
                        <p> </p>
                    </td>
                    @endif
                </tr>
            </table>
            <table class="table table-sm  table-striped datatable-informe w-100">
                <thead>
                    @if ($mes)
                        <tr>
                            <th style="border-bottom: 2px solid #464648;"></th>
                            <th class="titulos">NOTA</th>
                            <th class="titulos" style="text-align: right">{{ $mes }}<br>{{ $anio }}</th>
                            <th class="titulos" style="text-align: right">Diciembre<br>{{ $anioAnterior }}</th>
                            <th class="titulos" style="text-align: right">VAR%</th>
                            <th class="titulos" style="text-align: right">VARIACIÓN $</th>
                        </tr>
                    @else
                        <tr>
                            <th style="border-bottom: 2px solid #464648;"></th>
                            <th class="titulos">NOTA</th>
                            <th class="titulos" style="text-align: right">AÑO<br>{{ $anio }}</th>
                            <th class="titulos" style="text-align: right">AÑO<br>{{ $anioAnterior }}</th>
                            <th class="titulos" style="text-align: right">VAR%</th>
                            <th class="titulos" style="text-align: right">VARIACIÓN $</th>
                        </tr>
                    @endif
                </thead>
            
            <tbody>
                @php
                $secciones = [
                        'Activo Corriente' => [
                            'Efectivo y equivalentes al efectivo',
                            'Inversiones',
                            'Cuentas comerciales y otras cuentas por cobrar',
                            'Activos por impuestos corrientes',
                            'Inventarios',
                            'Anticipos y avances',
                            'Otros activos',
                            'Total activo corriente',
                        ],
                        'Activo No Corriente' => [
                            'Inversiones no corriente',
                            'Propiedades planta y equipos',
                            'Activos Intangibles',
                            'Impuesto diferido',
                            'Total activo no corriente',
                            'Total activo',
                        ],
                        'Pasivo Corriente' => [
                            'Obligaciones financieras',
                            'Cuentas comerciales y otras cuentas por pagar',
                            'Cuentas por pagar',
                            'Pasivos por Impuestos Corrientes',
                            'Beneficios a empleados',
                            'Anticipos y avances recibidos',
                            'Otros Pasivos',
                            'Total pasivos corrientes',
                        ],
                        'Pasivo No Corriente' => [
                            'Obligaciones Financieras',
                            'Cuentas por pagar comerciales y otras cuentas por pagar',
                            'Pasivos Contingentes',
                            'Pasivo por impuesto diferido',
                            'Otros pasivos no corrientes',
                            'Bonos y papeles comerciales',
                            'Total pasivos no corrientes',
                            'Total Pasivo',
                        ],
                        'Patrimonio' => [
                            'Capital social',
                            'Superavit de capital',
                            'Reservas',
                            'Utilidad y/o perdidas del ejercicio',
                            'Resultado del ejercicio',
                            'Utilidad y/o perdidas acumuladas',
                            'Ganancias acumuladas - Adopcion por primera vez',
                            'Dividendos o participacion',
                            'Superavit de Capital Valorizacion',
                            'Total patrimonio',
                            'Total Pasivo & Patrimonio',
                        ],
                ];
                // Verificamos el grupo NIIF
                if (isset($representantelegal['gruponiif']) && $representantelegal['gruponiif'] == 2) {
                    $notasEspecificas = [
                        'Efectivo y equivalentes al efectivo' => 3,
                        'Inversiones' => 4,
                        'Cuentas comerciales y otras cuentas por cobrar' => 4,
                        'Inversiones no corriente' => 6,
                        'Activos por impuestos corrientes' => 5,
                        'Inventarios' => 5,
                        'Anticipos y avances' => 5,
                        'Otros activos' => 5,
                        'Propiedades planta y equipos' => 11,
                        'Activos Intangibles' => 12,
                        'Impuesto diferido' => 13,
                        'Obligaciones financieras' => 7,
                        'Cuentas comerciales y otras cuentas por pagar' => 7,
                        'Cuentas por pagar' => 7,
                        'Pasivos por Impuestos Corrientes' => 7,
                        'Beneficios a empleados' => 8,
                        'Anticipos y avances recibidos' => 18,
                        'Otros Pasivos' => 19,
                        'Obligaciones Financieras' => 20,
                        'Cuentas por pagar comerciales y otras cuentas por pagar' => 6,
                        'Pasivos Contingentes' => 22,
                        'Pasivo por impuesto diferido' => 23,
                        'Otros pasivos no corrientes' => 40,
                        'Bonos y papeles comerciales' => 41,
                        'Capital social' => 25,
                        'Superavit de capital' => 25,
                        'Reservas' => 25,
                        'Utilidad y/o perdidas del ejercicio' => 25,
                        'Resultado del ejercicio' => 25,
                        'Utilidad y/o perdidas acumuladas' => 25,
                        'Ganancias acumuladas - Adopcion por primera vez' => 25,
                        'Dividendos o participacion' => 26,
                        'Superavit de Capital Valorizacion' => 24,
                    ];
                }else{
                    $notasEspecificas = [
                        'Efectivo y equivalentes al efectivo' => 3,
                        'Inversiones' => 4,
                        'Cuentas comerciales y otras cuentas por cobrar' => 5,
                        'Inversiones no corriente' => 6,
                        'Activos por impuestos corrientes' => 7,
                        'Inventarios' => 8,
                        'Anticipos y avances' => 9,
                        'Otros activos' => 10,
                        'Propiedades planta y equipos' => 11,
                        'Activos Intangibles' => 12,
                        'Impuesto diferido' => 13,
                        'Obligaciones financieras' => 14,
                        'Cuentas comerciales y otras cuentas por pagar' => 15,
                        'Cuentas por pagar' => 15,
                        'Pasivos por Impuestos Corrientes' => 16,
                        'Beneficios a empleados' => 17,
                        'Anticipos y avances recibidos' => 18,
                        'Otros Pasivos' => 19,
                        'Obligaciones Financieras' => 20,
                        'Cuentas por pagar comerciales y otras cuentas por pagar' => 21,
                        'Pasivos Contingentes' => 22,
                        'Pasivo por impuesto diferido' => 23,
                        'Otros pasivos no corrientes' => 40,
                        'Bonos y papeles comerciales' => 41,
                        'Capital social' => 24,
                        'Superavit de capital' => 24,
                        'Reservas' => 24,
                        'Utilidad y/o perdidas del ejercicio' => 25,
                        'Resultado del ejercicio' => 25,
                        'Utilidad y/o perdidas acumuladas' => 25,
                        'Ganancias acumuladas - Adopcion por primera vez' => 25,
                        'Dividendos o participacion' => 26,
                        'Superavit de Capital Valorizacion' => 24,
                    ];
                }
                $notasParaMostrar = [];
                @endphp
                {{--funcion para mostrar las notas con un contador general para el resto de funciones--}}
                @php
                    
                    $mapaNotasOriginales = [];
                    $contadorNota = 3;

                    // Para agilizar búsqueda de datos en $informedetallado
                    $informedetalladoMap = [];
                    foreach ($informedetallado as $item) {
                        $informedetalladoMap[$item['descripcion']] = $item;
                    }

                    foreach ($secciones as $titulo => $cuentas) {
                        foreach ($cuentas as $cuenta) {
                            // Saltar totales
                            if (str_starts_with($cuenta, 'Total')) continue;

                            // Verificar que la cuenta existe en $informedetallado y tiene datos no nulos
                            if (!isset($informedetalladoMap[$cuenta])) continue;

                            $item = $informedetalladoMap[$cuenta];

                            // Limpiar valores para compararlos como números
                            $totalaño1 = (float) str_replace(',', '', $item['totalaño1']);
                            $totalaño2 = (float) str_replace(',', '', $item['totalaño2']);

                            // Saltar si ambos años son cero
                            if ($totalaño1 == 0 && $totalaño2 == 0) continue;

                            // Si ya se asignó número para esta cuenta, saltar
                            if (isset($notasParaMostrar[$cuenta])) continue;

                            if (isset($notasEspecificas[$cuenta])) {
                                $notaOriginal = $notasEspecificas[$cuenta];
                                if (isset($mapaNotasOriginales[$notaOriginal])) {
                                    $notasParaMostrar[$cuenta] = $mapaNotasOriginales[$notaOriginal];
                                } else {
                                    $notasParaMostrar[$cuenta] = $contadorNota;
                                    $mapaNotasOriginales[$notaOriginal] = $contadorNota;
                                    $contadorNota++;
                                }
                            } else {
                                $notasParaMostrar[$cuenta] = $contadorNota;
                                $contadorNota++;
                            }
                        }
                    }
                function invertirSigno($valor)
                {
                    $valor = trim($valor);

                    if (Str::startsWith($valor, '-')) {
                        return ltrim($valor, '-');
                    }

                    return '-' . $valor;
                }
                @endphp
                @foreach ($secciones as $titulo => $cuentas)
                    <tr>
                        <td colspan="6" class="titulos2" style="border-bottom: 2px solid #464648;">
                            {{ $titulo }}
                        </td>
                    </tr>
                    @foreach ($cuentas as $cuenta)
                        @php
                            $cuentaEncontrada = false;
                        @endphp
                        
                        @foreach ($informedetallado as $item)
                            @if ($item['descripcion'] === $cuenta)
                                @php
                                    $isTotal = str_starts_with($item['descripcion'], 'Total');
                                    $dobleLineaTotales = ['Total activo', 'Total Pasivo', 'Total Pasivo & Patrimonio'];
                                    $bordeInferior = $isTotal
                                        ? (in_array($item['descripcion'], $dobleLineaTotales) ? 'border-bottom: double 3px #000;' : 'border-bottom: solid 1px #000;')
                                        : '';

                                    if($titulo == 'Activo Corriente' ) {
                                        $totalaño1 = trim(str_replace(',', '', $item['totalaño1']));
                                        $totalaño2 = trim(str_replace(',', '', $item['totalaño2']));
                                    }
                                    else{
                                        $totalaño1 = invertirSigno(str_replace(',', '', $item['totalaño1']));
                                        $totalaño2 = invertirSigno(str_replace(',', '', $item['totalaño2']));
                                    }

                                    $mostrarFila = ($totalaño1 != 0 || $totalaño2 != 0);
                                @endphp
                                @php
                                    $reemplazosprincipal = [
                                        'Total Pasivo & Patrimonio' => 'Total Pasivo + Patrimonio',
                                        // Puedes agregar más si lo necesitas:
                                        // 'Otra Descripción' => 'Nuevo Texto',
                                    ];
                                @endphp
                                @if ($mostrarFila)
                                    <tr @if ($isTotal) style="background-color: #d6d3d3; color: black; font-weight: bold;" @endif>
                                        
                                        <td>
                                            {{ $reemplazosprincipal[$item['descripcion']] ?? $item['descripcion'] }}
                                        </td>

                                        {{-- Nota visible solo si no es total --}}
                                        <td class="titulos2" style="text-align: center;">
                                            {{ !$isTotal && isset($notasParaMostrar[$item['descripcion']]) ? $notasParaMostrar[$item['descripcion']] : '' }}
                                        </td>
                                        @php
                                            $valor1 = (float) str_replace(',', '', $item['totalaño1']);
                                            $valor2 = (float) str_replace(',', '', $item['totalaño2']);
                                            $isUtilidadAcumulada = str_starts_with($item['descripcion'], 'Utilidad y/o perdidas acumuladas');
                                            $isUtilidadEjercicio = str_starts_with($item['descripcion'], 'Utilidad y/o perdidas del ejercicio');
                                            $isResultadoEjercicio = str_starts_with($item['descripcion'], 'Resultado del ejercicio');
                                             if($titulo == 'Activo Corriente' || $titulo == 'Activo No Corriente') {
                                                $isTotal = str_starts_with($item['descripcion'], 'Total');
                                                $valor1  = $valor1;
                                                $valor2  = $valor2;
                                            }else{
                                                $isTotal = str_starts_with($item['descripcion'], 'Total');
                                                $valor1 = invertirSigno($valor1);
                                                $valor2 = invertirSigno($valor2);
                                                if($isTotal){
                                                    $valor1 = invertirSigno($valor1);
                                                    $valor2 = invertirSigno($valor2);
                                                }
                                            }
                                            if($isUtilidadEjercicio){
                                                $valor1 = invertirSigno($valor1);
                                                $valor2 = invertirSigno($valor2);
                                            }
                                            $valor1 = $valor1 == 0 ? '-' : ( $valor1 < 0 ? '(' . number_format(abs($valor1), 0, ',', '.') . ')' : number_format($valor1, 0, ',', '.') );
                                            $valor2 = $valor2 == 0 ? '-' : ( $valor2 < 0 ? '(' . number_format(abs($valor2), 0, ',', '.') . ')' : number_format($valor2, 0, ',', '.') );
                                        @endphp
                                        {{-- Año 1 --}}
                                        <td style="{{ $bordeInferior }} text-align: right;">
                                            {!! $valor1 !!}
                                        </td>

                                        {{-- Año 2 --}}
                                        <td style="{{ $bordeInferior }} text-align: right !important;">
                                            {!! $valor2 !!}
                                        </td>

                                        {{-- VAR % --}}
                                        @php
                                            $anio1 = (float)$totalaño1;
                                            $anio2 = (float)$totalaño2;
                                           $valorSinPorcentaje = null;
                                            if ($anio2 != 0) {
                                                $valorSinPorcentaje = $anio1 == 0 ? 0 : ((abs($anio1) / abs($anio2)) - 1) * 100;
                                            }

                                            $valorvarFormateado = is_numeric($valorSinPorcentaje)
                                                ? number_format(round($valorSinPorcentaje), 0, ',', '.') 
                                                : 0;

                                        @endphp
                                        <td class="sizenotas" style="text-align: right; {{ $bordeInferior }}">
                                            {!! $valorvarFormateado == 0 
                                                ? '-' 
                                                : ($valorvarFormateado < 0 
                                                    ? '(' . ltrim($valorvarFormateado, '-') . '%)' 
                                                    : $valorvarFormateado . '%') !!}
                                        </td>

                                        {{-- VARIACIÓN $ --}}
                                        @php
                                            $clean1 = (float)str_replace(',', '', $item['totalaño1']);
                                            $clean2 = (float)str_replace(',', '', $item['totalaño2']);
                                            $valor3 = abs($clean1) - abs($clean2);
                                        @endphp
                                        <td style="text-align: right; {{ $bordeInferior }}">
                                            @if ($valor3 > 0)
                                                {{ number_format($valor3, 0, '.', ',') }}<span style="color: green;font-family: 'DejaVu Sans', sans-serif;">▲</span>
                                            @elseif ($valor3 < 0)
                                                ({{ ltrim(number_format($valor3, 0, '.', ','), '-') }}) <span style="color: red; font-family: 'DejaVu Sans', sans-serif;">▼</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endif
                        @endforeach
                    @endforeach
                @endforeach
            </tbody>
        </table>
        </div>
        <div class="tabla-integrales" style="font-size: 10px; margin: 0; padding: 2px !importante;">
            <div class="firmas">
                <!-- Firma Representante Legal -->
                <div class="firma" style="text-align: center;">
                    @if ($representantelegalfirma != '*')
                        <div class="firma2">
                            <img src="data:image/jpeg;base64,{{ $representantelegalfirma }}" alt="Firma Representante Legal">
                        </div>
                    @else
                        <div class="firma2 no-image"></div>
                    @endif
                    <div class="firmatexto" style="color:black; text-align: center; margin-top: 0;">
                        <strong>{{ $representantelegal['representantelegal'] ?? 'Sin datos encontrados' }}</strong><br>
                        Representante legal<br>C.C. {{ $representantelegal['Cedula'] ?? '' }}
                    </div>
                </div>
            
                <!-- Firma Contador -->
                <div class="firma" style="text-align: center;">
                    @if ($base64Imagefirmacontador != '*')
                        <div class="firma2">
                            <img src="data:image/jpeg;base64,{{ $base64Imagefirmacontador }}" alt="Firma Contador">
                        </div>
                    @else
                        <div class="firma2 no-image"></div>
                    @endif
                    <div class="firmatexto" style="color:black; text-align: center; margin-top:0;">
                        <strong>{{ ($datoscontador['nombres'] ?? 'Sin datos') . ' ' . ($datoscontador['apellidos'] ?? '') }}</strong><br>
                        Contador Público<br>T.P {{ $datoscontador['tarje_profesional'] ?? '' }}
                    </div>
                </div>
            
                <!-- Firma Revisor Fiscal -->
                @if (!empty($representantelegal['revisorfiscal']) && $representantelegal['revisorfiscal'] !== 'Sin datos encontrados')
                    <div class="firma" style="text-align: center;">
                        @if ($revisorfiscalfirma != '*')
                            <div class="firma2">
                                <img src="data:image/jpeg;base64,{{ $revisorfiscalfirma }}" alt="Firma Revisor Fiscal">
                            </div>
                        @else
                            <div class="firma2 no-image"></div>
                        @endif
                        <div class="firmatexto" style="color:black; text-align: center; margin-top:0;">
                            <strong>{{ $representantelegal['revisorfiscal'] }}</strong><br>
                            Revisor Fiscal<br>T.P {{ $representantelegal['cedularevisor'] ?? '' }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <!-- Salto de página -->
        <div class="page-break"></div>
        <!-- Página 2 en adelante: Tabla -->
        {{-- informe ESTADO DE RESULTADOS INTEGRALES  --}}
        <div class="tabla-integrales" style="font-size: 10px; margin: 0; padding: 2px;">
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
                <tr>
                    <!-- Columna vacía -->
                    <td style="width: 33%;"></td>
                    <!-- Columna para el texto -->
                    <td style="width: 34%; text-align: center; vertical-align: top;">
                        <p style="color: #464648; margin: 0;">
                            <b>{{ $representantelegal['razon_social'] }}</b><br>
                            <b>NIT - {{$nit}}</b><br>
                            <b>ESTADO DE RESULTADOS INTEGRAL</b><br>
                            <b>a {{ strtoupper($mes2) }} {{ $dia_numero }} del {{ $anio }}.</b><br>
                            (Cifras expresadas en pesos colombianos)
                        </p>
                    </td>
                    @if($logocliente != '*')
                    <!-- Columna para el logo -->
                    <td style="width: 33%; text-align: right; vertical-align: top;">
                        <img id="logo" src="data:image/jpeg;base64,{{ $logocliente }}" style="max-width: 100px; max-height: 100px;">
                    </td>
                    @else
                    <td style="width: 33%; text-align: center; vertical-align: top;">
                        <p> </p>
                    </td>
                    @endif
                </tr>
            </table>
            <table class="table table-sm table-striped datatable-informe w-100">
                <thead>
                    @if ($mes)
                        <tr>
                            <th style="border-bottom: 2px solid #464648;"> </th>
                            <th class="titulos" style="text-align: center;">NOTA</th> <!-- Columna para notas -->
                            <th class="titulos">{{ $mes . '-' . $anio }}</th>
                            <th class="titulos">{{ $mes. '-' . $anioAnterior }}</th>
                            <th class="titulos" style="text-align: center">VAR%</th>
                            <th class="titulos" style="text-align: center">VARIACIÓN $</th>
                            
                        
                        </tr>
                    @else
                        <tr>
                            <th style="border-bottom: 2px solid #464648;"> </th>
                            <<th class="titulos" style="text-align: center;">NOTA</th> <!-- Columna para notas -->
                            <th class="titulos">AÑO {{ $anio }}</th>
                            <th class="titulos">AÑO {{ $anioAnterior }}</th>
                            <th class="titulos" style="text-align: center">VAR%</th>
                            <th class="titulos" style="text-align: center">VARIACIÓN $</th>
                            
                        </tr>
                    @endif
                </thead>
                <tbody>
                    {{-- Mostrar las filas principales --}}
                    @php
                        $notasBloque1 = [
                            'Ingresos de actividades ordinarias' => '27',
                            'Costos de venta' => '28',
                        ];
                        $bloques1 = array_keys($notasBloque1);
                        $contadornotainicial = empty($notasParaMostrar) ? 3 : max($notasParaMostrar)+1;
                        $resultado1 = generarNotasDesde($bloques1, $informeData, $anio, $anioAnterior, $notasBloque1, $contadornotainicial);
                        $notasParaMostrarBloques = $resultado1['notas'];
                        $ultimoNumero = $resultado1['ultimo'];
                        if($representantelegal['gruponiif'] == 2){
                           $notaIngresosFinancieros = '14';
                        }else{
                            $notaIngresosFinancieros = $resultado1['notaIngresosFinancieros'];
                        }
                        
                    
                    @endphp
                    @foreach ([
                        'Ingresos de actividades ordinarias' => '27',
                        'Costos de venta' => '28',
                        'Utilidad Bruta' => '',
                    ] as $key => $nota)
                        @php
                            $anioActual = floatval($informeData[$key][$anio] ?? 0);
                            $anioPrevio = floatval($informeData[$key][$anioAnterior] ?? 0);
                        @endphp
                        @if ($anioActual != 0 || $anioPrevio != 0)
                        <tr>
                            <td @if ($key == 'Utilidad Bruta') class="titulos" @endif>
                                {{ $informeData[$key]['descripcionct'] }}
                            </td>
                            <td @if ($key == 'Utilidad Bruta')   class="titulos2" @endif style="text-align: center;" >
                                {{ $notasParaMostrarBloques[$key] ?? '' }}
                            </td>
                            <td @if ($key == 'Utilidad Bruta') class="totales" @endif style="text-align: right">
                            @if ($informeData[$key][$anio] == 0)
                                -
                            @else
                                @if ($key == 'Utilidad Bruta')
                                    @if ($informeData[$key][$anio] < 0)
                                        (${{ number_format(abs($informeData[$key][$anio]), 0, '.', ',') }})
                                    @else
                                        ${{ number_format($informeData[$key][$anio], 0, '.', ',') }}
                                    @endif
                                @else
                                    {{ number_format(abs($informeData[$key][$anio]), 0, '.', ',') }}
                                @endif
                            @endif
                        </td>
                        

                        <td @if ($key == 'Utilidad Bruta') class="totales" @endif style="text-align: right">
                            @if ($informeData[$key][$anioAnterior] == 0)
                                -
                            @else
                                @if ($key == 'Utilidad Bruta')
                                    ${{ number_format($informeData[$key][$anioAnterior], 0, '.', ',') }}
                                @else
                                    {{ number_format(abs($informeData[$key][$anioAnterior]), 0, '.', ',') }}
                                @endif
                            @endif
                        </td>
                            {{-- Columna para notas --}}
                            <td @if ($key == 'Utilidad Bruta') class="totales" @endif style="text-align: right">
                                @php
                                    $anio1 = (float) $informeData[$key][$anio];
                                    $anio2 = (float) $informeData[$key][$anioAnterior];
                                    $variacionv = null;

                                    if ($anio2 != 0) {
                                        if ($anio1 == 0) {
                                            $variacionv = 0;
                                        } else {
                                            $variacionv = (($anio1 / $anio2) - 1) * 100;
                                        }
                                    }
                                @endphp

                                @if($variacionv !== null)
                                    @if($variacionv < 0)
                                        {{ '(' . ltrim(number_format($variacionv, 0, '.', ',') . '%', '-') . ')' }}
                                    @elseif($variacionv > 0)
                                        {{ ltrim(number_format($variacionv, 0, '.', ',') . '%', '-') }}
                                    @else
                                        -
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            @php
                                $variacion = abs($anio1)-abs($anio2);;
                            @endphp
                            <td @if ($key == 'Utilidad Bruta') class="totales" @endif style="text-align: right">
                                
                                @if ($variacion > 0)
                                    @if ($key == 'Utilidad Bruta') $@endif {{ number_format($variacion, 0, ',', '.') }}
                                    <span style="color: green; font-family: 'DejaVu Sans', sans-serif;">▲</span>
                                @elseif ($variacion < 0)
                                    (@if ($key == 'Utilidad Bruta') $@endif {{ number_format(abs($variacion), 0, ',', '.') }})
                                    <span style="color: red; font-family: 'DejaVu Sans', sans-serif;">▼</span>
                                @else
                                    -
                                @endif
                            </td>
                            
                        </tr>
                        @endif
                    @endforeach
                    @php
                        $notasBloque2 = [
                            'Gastos de administración' => '29',
                            'Gastos de ventas' => '29',
                        ];
                        $bloques2 = array_keys($notasBloque2);
                        $resultado2 = generarNotasDesde($bloques2, $informeData, $anio, $anioAnterior, $notasBloque2, $ultimoNumero);
                        $notasParaMostrarBloques2 = $resultado2['notas'];
                        $ultimoNumero = $resultado2['ultimo'];
                        $gastos = $resultado2['gastos']; 
                    @endphp
                    {{-- Mostrar los datos adicionales --}}
                    @foreach ([
                        
                        'Gastos de administración' => '29',
                        'Gastos de ventas' => '29',
                    ] as $key => $nota)
                        @php
                            $anioActual = floatval($informeData[$key][$anio] ?? 0);
                            $anioPrevio = floatval($informeData[$key][$anioAnterior] ?? 0);
                        @endphp
                        @if ($anioActual != 0 || $anioPrevio != 0)
                        <tr>
                            <td>{{ $informeData[$key]['descripcionct'] }}</td>
                            <td class="titulos2" style="text-align: center">{{ $notasParaMostrarBloques2[$key] ?? '' }}</td> <!-- Columna para notas -->
                            <td style="text-align: right">{{ $informeData[$key][$anio] == 0 ? '-' : number_format(abs($informeData[$key][$anio]), 0, '.', ',') }}</td>
                            <td style="text-align: right">{{ $informeData[$key][$anioAnterior] == 0 ? '-' : number_format(abs($informeData[$key][$anioAnterior]), 0, '.', ',') }}</td>
                            <td style="text-align: right">
                                    @php
                                        $anio1 = (float) $informeData[$key][$anio];
                                        $anio2 = (float) $informeData[$key][$anioAnterior];
                                        $variacionv = null;

                                        if ($anio2 != 0) {
                                            if ($anio1 == 0) {
                                                $variacionv = 0;
                                            } else {
                                                $variacionv = (($anio1 / $anio2) - 1) * 100;
                                            }
                                        }
                                    @endphp

                                    @if($variacionv !== null)
                                        @if($variacionv < 0)
                                            {{ '(' . ltrim(number_format($variacionv, 0, '.', ',') . '%', '-') . ')' }}
                                        @elseif($variacionv > 0)
                                            {{ ltrim(number_format($variacionv, 0, '.', ',') . '%', '-') }}
                                        @else
                                            -
                                        @endif
                                    @else
                                        -
                                    @endif

                            </td>
                            @php
                                $variacion = abs($anio1)-abs($anio2);
                            @endphp
                            <td style="text-align: right">
                                @if ($variacion > 0)
                                    {{ number_format($variacion, 0, ',', '.') }}
                                    <span style="color: green; font-family: 'DejaVu Sans', sans-serif;">▲</span>
                                @elseif ($variacion < 0)
                                    ({{ number_format(abs($variacion), 0, ',', '.') }})
                                    <span style="color: red; font-family: 'DejaVu Sans', sans-serif;">▼</span>
                                @else
                                    -
                                @endif
                            </td>
                            
                        </tr>
                        @endif
                    @endforeach
        
                    {{-- Mostrar Utilidad (Pérdida) operativa --}}
                    <tr>
                        <td class="titulos" style="background-color: hsl(210, 2%, 34%); color: white; font-weight: bold;">
                            Utilidad (Pérdida) operativa
                        </td>
                        <td></td> <!-- Columna para notas vacía -->
                        <td style="text-align: right" class="totales">
                            @php
                                $valor = $informeData['Utilidad (Pérdida) operativa'][$anio];
                            @endphp
                            @if ($valor == 0)
                                -
                            @elseif ($valor < 0)
                                (${{ number_format(abs($valor), 0, '.', ',') }})
                            @else
                                ${{ number_format($valor, 0, '.', ',') }}
                            @endif
                        </td>

                        <td class="totales" style="text-align: right">
                            @php
                                $valorAnt = $informeData['Utilidad (Pérdida) operativa'][$anioAnterior];
                            @endphp
                            @if ($valorAnt == 0)
                                -
                            @elseif ($valorAnt < 0)
                                (${{ number_format(abs($valorAnt), 0, '.', ',') }})
                            @else
                                ${{ number_format($valorAnt, 0, '.', ',') }}
                            @endif
                        </td>

                        <td class="totales" style="text-align: right">
                                    @php
                                        $anio1 = (float) $informeData['Utilidad (Pérdida) operativa'][$anio] ; 
                                        $anio2 = (float) $informeData['Utilidad (Pérdida) operativa'][$anioAnterior]; 
                                        $variacionv = null;
                                        if ($anio2 != 0) {
                                            if ($anio1 == 0) {
                                                $variacionv = 0;
                                            } else {
                                                $variacionv = (($anio1 / $anio2) - 1) * 100;
                                            }
                                        }
                                    @endphp
                                    @if($variacionv !== null)
                                        @if($variacionv < 0)
                                            {{ '(' . ltrim(number_format($variacionv, 0, '.', ',') . '%', '-') . ')' }}
                                        @elseif($variacionv > 0)
                                            {{ ltrim(number_format($variacionv, 0, '.', ',') . '%', '-') }}
                                        @else
                                            -
                                        @endif
                                    @else
                                        -
                                    @endif

                            </td>
                            @php
                                $variacion = abs($anio1)-abs($anio2);
                            @endphp
                        <td  class="totales"  style="text-align: right">
                            @if ($variacion > 0)
                                ${{ number_format($variacion, 0, ',', '.') }}
                                <span style="color: green; font-family: 'DejaVu Sans', sans-serif;">▲</span>
                            @elseif ($variacion < 0)
                                (${{ number_format(abs($variacion), 0, ',', '.') }})
                                <span style="color: red; font-family: 'DejaVu Sans', sans-serif;">▼</span>
                            @else
                                -
                            @endif
                        </td>
                        
                    </tr>
        
                    {{-- Mostrar las filas restantes --}}
                    @php
                        $notasBloque3 = [
                            'Otros ingresos' => $notaIngresosFinancieros,
                            'Ingresos financieros' => $notaIngresosFinancieros,
                            'Otros gastos' => $gastos,
                            'Gastos financieros' => $gastos,
                        ];
                    @endphp
                    @foreach ([
                        'Otros ingresos' => '27',
                        'Ingresos financieros' => '27',
                        'Otros gastos' => '29',
                        'Gastos financieros' => '29',
                    ] as $key => $nota)

                        @php
                            $anio1 = (float) $informeData[$key][$anio];
                            $anio2 = (float) $informeData[$key][$anioAnterior];
                        @endphp

                        @if (!($anio1 == 0 && $anio2 == 0))
                            <tr>
                                <td>{{ $informeData[$key]['descripcionct'] }}</td>
                                <td class="titulos2" style="text-align: center;">{{ $notasBloque3[$key] ?? '' }}</td> <!-- Columna para notas -->
                                <td style="text-align: right">{{ $anio1 == 0 ? '-' : number_format(abs($anio1), 0, '.', ',') }}</td>
                                <td style="text-align: right">{{ $anio2 == 0 ? '-' : number_format(abs($anio2), 0, '.', ',') }}</td>
                                <td style="text-align: right">
                                    @php
                                        $variacionv = null;
                                        if ($anio2 != 0) {
                                            $variacionv = $anio1 == 0 ? 0 : (($anio1 / $anio2) - 1) * 100;
                                        }
                                    @endphp

                                    @if($variacionv !== null)
                                        @if($variacionv < 0)
                                            {{ '(' . ltrim(number_format($variacionv, 0, '.', ',') . '%', '-') . ')' }}
                                        @elseif($variacionv > 0)
                                            {{ ltrim(number_format($variacionv, 0, '.', ',') . '%', '-') }}
                                        @else
                                            -
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                @php
                                    $variacion = abs($anio1) - abs($anio2);
                                @endphp
                                <td style="text-align: right">
                                    @if ($variacion > 0)
                                        {{ number_format($variacion, 0, ',', '.') }}    
                                        <span style="color: green; font-family: 'DejaVu Sans', sans-serif;">▲</span>
                                    @elseif ($variacion < 0)
                                        ({{ number_format(abs($variacion), 0, ',', '.') }})
                                        <span style="color: red; font-family: 'DejaVu Sans', sans-serif;">▼</span>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    {{-- Mostrar Utilidad (Pérdida) antes de impuestos de renta --}}
                    <tr>
                        <td class="titulos" style="background-color: #464648; color: white; font-weight: bold;">
                            Utilidad (Pérdida) antes de impuestos de renta
                        </td>
                        <td></td> <!-- Columna para notas vacía -->
                        <td style="text-align: right" class="totales">
                            @php
                                $valor = $informeData['Utilidad (Pérdida) antes de impuestos de renta'][$anio];
                            @endphp
                            @if ($valor == 0)
                                -
                            @elseif ($valor < 0)
                                (${{ number_format(abs($valor), 0, '.', ',') }})
                            @else
                                ${{ number_format($valor, 0, '.', ',') }}
                            @endif
                        </td>

                        <td class="totales" style="text-align: right">
                            @php
                                $valorAnt = $informeData['Utilidad (Pérdida) antes de impuestos de renta'][$anioAnterior];
                            @endphp
                            @if ($valorAnt == 0)
                                -
                            @elseif ($valorAnt < 0)
                                (${{ number_format(abs($valorAnt), 0, '.', ',') }})
                            @else
                                ${{ number_format($valorAnt, 0, '.', ',') }}
                            @endif
                        </td>
                        <td class="totales" style="text-align: right">
                                    @php
                                        $anio1 = (float) $informeData['Utilidad (Pérdida) antes de impuestos de renta'][$anio] ; 
                                        $anio2 = (float) $informeData['Utilidad (Pérdida) antes de impuestos de renta'][$anioAnterior]; 
                                        $variacionv = null;

                                        if ($anio2 != 0) {
                                            if ($anio1 == 0) {
                                                $variacionv = 0;
                                            } else {
                                                $variacionv = (($anio1 / $anio2) - 1) * 100;
                                            }
                                        }
                                    @endphp
                                    @if($variacionv !== null)
                                        @if($variacionv < 0)
                                            {{ '(' . ltrim(number_format($variacionv, 0, '.', ',') . '%', '-') . ')' }}
                                        @elseif($variacionv > 0)
                                            {{ ltrim(number_format($variacionv, 0, '.', ',') . '%', '-') }}
                                        @else
                                            -
                                        @endif
                                    @else
                                        -
                                    @endif

                            </td>
                            @php
                                $variacion = abs($anio1)-abs($anio2);
                            @endphp
                        <td class="totales" style="text-align: right">
                            
                            @if ($variacion > 0)
                                ${{ number_format($variacion, 0, ',', '.') }}
                                <span style="color: green; font-family: 'DejaVu Sans', sans-serif;">▲</span>
                            @elseif ($variacion < 0)
                                (${{ number_format(abs($variacion), 0, ',', '.') }})
                                <span style="color: red; font-family: 'DejaVu Sans', sans-serif;">▼</span>
                            @else
                                -
                            @endif
                        </td>
                        
                    </tr>
                    {{-- Mostrar los datos adicionales --}}
                    @php
                        $notasBloque4 = [
                            'Gastos impuesto de renta y cree' => '30',
                        ];
                        $bloques4 = array_keys($notasBloque4);
                        $resultado4 = generarNotasDesde($bloques4, $informeData, $anio, $anioAnterior, $notasBloque4, $ultimoNumero);
                        
                        $notasParaMostrarBloques4 = $resultado4['notas'];
                        $ultimoNumero = $resultado4['ultimo'];
                    @endphp
                    @foreach ([
                        'Gastos impuesto de renta y cree' => '30',
                    ] as $key => $nota)
                        @if(
                            (!isset($informeData[$key][$anio]) || $informeData[$key][$anio] == 0) &&
                            (!isset($informeData[$key][$anioAnterior]) || $informeData[$key][$anioAnterior] == 0)
                        )
                            @continue
                        @else
                            <tr>
                                <td>Gastos impuesto a las ganancias</td>
                                <td class="titulos2" style="text-align: center">{{ $notasParaMostrarBloques4[$key] ?? ''  }}</td> <!-- Columna para notas -->
                                <td style="text-align: right">{{ $informeData[$key][$anio] == 0 ? '-' : number_format(abs($informeData[$key][$anio]), 0, '.', ',') }}</td>
                                <td style="text-align: right">{{ $informeData[$key][$anioAnterior] == 0 ? '-' : number_format(abs($informeData[$key][$anioAnterior]), 0, '.', ',') }}</td>
                                <td style="text-align: right">
                                        @php
                                            $anio1 = (float) $informeData[$key][$anio];
                                            $anio2 = (float) $informeData[$key][$anioAnterior];
                                            $variacionv = null;

                                            if ($anio2 != 0) {
                                                if ($anio1 == 0) {
                                                    $variacionv = 0;
                                                } else {
                                                    $variacionv = (($anio1 / $anio2) - 1) * 100;
                                                }
                                            }
                                        @endphp

                                        @if($variacionv !== null)
                                            @if($variacionv < 0)
                                                {{ '(' . ltrim(number_format($variacionv, 0, '.', ',') . '%', '-') . ')' }}
                                            @elseif($variacionv > 0)
                                                {{ ltrim(number_format($variacionv, 0, '.', ',') . '%', '-') }}
                                            @else
                                                -
                                            @endif
                                        @else
                                            -
                                        @endif

                                </td>
                                @php
                                    $variacion = abs($anio1)-abs($anio2);
                                @endphp
                                <td style="text-align: right">
                                    
                                    @if ($variacion > 0)
                                        {{ number_format($variacion, 0, ',', '.') }}
                                        <span style="color: green; font-family: 'DejaVu Sans', sans-serif;">▲</span>
                                    @elseif ($variacion < 0)
                                        ({{ number_format(abs($variacion), 0, ',', '.') }})
                                        <span style="color: red; font-family: 'DejaVu Sans', sans-serif;">▼</span>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
        
                    {{-- Mostrar Utilidad (Perdida) Neta del periodo --}}
                    <tr>
                        <td class="titulos" style="background-color: #464648; color: white; font-weight: bold;">
                            Utilidad (Perdida) Neta del periodo
                        </td>
                        <td></td> <!-- Columna para notas vacía -->
                        <td style="text-align: right" class="totales">
                        @php
                            $valorUNP = $informeData['Utilidad (Perdida) Neta del periodo'][$anio];
                        @endphp
                        @if ($valorUNP == 0)
                            -
                        @elseif ($valorUNP < 0)
                            (${{ number_format(abs($valorUNP), 0, '.', ',') }})
                        @else
                            ${{ number_format($valorUNP, 0, '.', ',') }}
                        @endif
                    </td>

                    <td class="totales" style="text-align: right">
                        @php
                            $valorAnt = $informeData['Utilidad (Perdida) Neta del periodo'][$anioAnterior];
                        @endphp
                        @if ($valorAnt == 0)
                            -
                        @elseif ($valorAnt < 0)
                            (${{ number_format(abs($valorAnt), 0, '.', ',') }})
                        @else
                            ${{ number_format($valorAnt, 0, '.', ',') }}
                        @endif
                    </td>

                        <td class="totales" style="text-align: right">
                                @php
                                    $anio1 = (float) $informeData['Utilidad (Perdida) Neta del periodo'][$anio];
                                    $anio2 = (float) $informeData['Utilidad (Perdida) Neta del periodo'][$anioAnterior];
                                    $variacionv = null;

                                    if ($anio2 != 0) {
                                        if ($anio1 == 0) {
                                            $variacionv = 0;
                                        } else {
                                            $variacionv = (($anio1 / $anio2) - 1) * 100;
                                        }
                                    }
                                @endphp

                                @if($variacionv !== null)
                                    @if($variacionv < 0)
                                        {{ '(' . ltrim(number_format($variacionv, 0, '.', ',') . '%', '-') . ')' }}
                                    @elseif($variacionv > 0)
                                        {{ ltrim(number_format($variacionv, 0, '.', ',') . '%', '-') }}
                                    @else
                                        -
                                    @endif
                                @else
                                    -
                                @endif

                        </td>
                        @php
                            $variacion = abs($anio1)-abs($anio2);
                        @endphp
                        <td class="totales"  style="text-align: right">
                            
                            @if ($variacion > 0)
                                ${{ number_format($variacion, 0, ',', '.') }}
                                <span style="color: green; font-family: 'DejaVu Sans', sans-serif;">▲</span>
                            @elseif ($variacion < 0)
                                (${{ number_format(abs($variacion), 0, ',', '.') }})
                                <span style="color: red; font-family: 'DejaVu Sans', sans-serif;">▼</span>
                            @else
                                -
                            @endif
                        </td>
                        
                    </tr>
        
                    <tr>
                        <td class="titulos" style="background-color: #464648; color: white; font-weight: bold;">
                            Resultado del ejercicio
                        </td>
                        <td></td> <!-- Columna para notas vacía -->
                        <td style="text-align: right" class="totales">
                            @php
                                $valor = $informeData['Utilidad (Perdida) Neta del periodo'][$anio];
                            @endphp
                            @if ($valor == 0)
                                -
                            @elseif ($valor < 0)
                                (${{ number_format(abs($valor), 0, '.', ',') }})
                            @else
                                ${{ number_format($valor, 0, '.', ',') }}
                            @endif
                        </td>

                        <td class="totales" style="text-align: right">
                            @php
                                $valorAnt = $informeData['Utilidad (Perdida) Neta del periodo'][$anioAnterior];
                            @endphp
                            @if ($valorAnt == 0)
                                -
                            @elseif ($valorAnt < 0)
                                (${{ number_format(abs($valorAnt), 0, '.', ',') }})
                            @else
                                ${{ number_format($valorAnt, 0, '.', ',') }}
                            @endif
                        </td>

                        <td class="totales" style="text-align: right">
                                    @php
                                        $anio1 = (float) $informeData['Utilidad (Perdida) Neta del periodo'][$anio];
                                        $anio2 = (float) $informeData['Utilidad (Perdida) Neta del periodo'][$anioAnterior];
                                        $variacionv = null;

                                        if ($anio2 != 0) {
                                            if ($anio1 == 0) {
                                                $variacionv = 0;
                                            } else {
                                                $variacionv = (($anio1 / $anio2) - 1) * 100;
                                            }
                                        }
                                    @endphp

                                    @if($variacionv !== null)
                                        @if($variacionv < 0)
                                            {{ '(' . ltrim(number_format($variacionv, 0, '.', ',') . '%', '-') . ')' }}
                                        @elseif($variacionv > 0)
                                            {{ ltrim(number_format($variacionv, 0, '.', ',') . '%', '-') }}
                                        @else
                                            -
                                        @endif
                                    @else
                                        -
                                    @endif

                            </td>
                            @php
                                $variacion = abs($anio1)-abs($anio2);
                            @endphp
                        <td class="totales"  style="text-align: right">
                            
                            @if ($variacion > 0)
                                ${{ number_format($variacion, 0, ',', '.') }}
                                <span style="color: green; font-family: 'DejaVu Sans', sans-serif;">▲</span>
                            @elseif ($variacion < 0)
                                (${{ number_format(abs($variacion), 0, ',', '.') }})
                                <span style="color: red; font-family: 'DejaVu Sans', sans-serif;">▼</span>
                            @else
                                -
                            @endif
                        </td>
                        
                    </tr>
                </tbody>
        
            </table>
            <p>Las notas adjuntas son parte integral de estos estados de resultados integrales.</p>
            <br>
            <div class="tabla-integrales" style="font-size: 10px; margin: 0; padding: 2px !importante;">
                <div class="firmas">
                    <!-- Firma Representante Legal -->
                    <div class="firma" style="text-align: center;">
                        @if ($representantelegalfirma != '*')
                            <div class="firma2">
                                <img src="data:image/jpeg;base64,{{ $representantelegalfirma }}" alt="Firma Representante Legal">
                            </div>
                        @else
                            <div class="firma2 no-image"></div>
                        @endif
                        <div class="firmatexto" style="color:black; text-align: center; margin-top: 0;">
                            <strong>{{ $representantelegal['representantelegal'] ?? 'Sin datos encontrados' }}</strong><br>
                            Representante legal<br>C.C. {{ $representantelegal['Cedula'] ?? '' }}
                        </div>
                    </div>
                
                    <!-- Firma Contador -->
                    <div class="firma" style="text-align: center;">
                        @if ($base64Imagefirmacontador != '*')
                            <div class="firma2">
                                <img src="data:image/jpeg;base64,{{ $base64Imagefirmacontador }}" alt="Firma Contador">
                            </div>
                        @else
                            <div class="firma2 no-image"></div>
                        @endif
                        <div class="firmatexto" style="color:black; text-align: center; margin-top:0;">
                            <strong>{{ ($datoscontador['nombres'] ?? 'Sin datos') . ' ' . ($datoscontador['apellidos'] ?? '') }}</strong><br>
                            Contador Público<br>T.P {{ $datoscontador['tarje_profesional'] ?? '' }}
                        </div>
                    </div>
                
                    <!-- Firma Revisor Fiscal -->
                    @if (!empty($representantelegal['revisorfiscal']) && $representantelegal['revisorfiscal'] !== 'Sin datos encontrados')
                        <div class="firma" style="text-align: center;">
                            @if ($revisorfiscalfirma != '*')
                                <div class="firma2">
                                    <img src="data:image/jpeg;base64,{{ $revisorfiscalfirma }}" alt="Firma Revisor Fiscal">
                                </div>
                            @else
                                <div class="firma2 no-image"></div>
                            @endif
                            <div class="firmatexto" style="color:black; text-align: center; margin-top:0;">
                                <strong>{{ $representantelegal['revisorfiscal'] }}</strong><br>
                                Revisor Fiscal<br>T.P {{ $representantelegal['cedularevisor'] ?? '' }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
            <!-- Salto de página -->
        <div class="page-break"></div>
        @if($verpatrimonioyflujo==1)
            {{--INFORME ESTADO CAMBIO PATRIMONIO--}}
            <div class="tabla-integrales" style="font-size: 10px; margin: 0; padding: 2px;">
                <style> 
                    .titulo {
                        text-align: center;
                        font-weight: bold;
                        color: #464648;
                    }
                    .subtitulo {
                        text-align: center;
                        font-weight: bold;
                    }
                    .cabecera {
                        background-color: #464648 !important;
                        color: #fffdfd !important;
                        font-weight: bold;
                    }
                        .fecha {
                        text-align: center;
                        font-size: 10px;
                    }
                    /* table {
                        width: 100%;
                        border-collapse: collapse;
                        font-size: 9px;
                    } */
                    th, td {
                        border: 1px solid #ddd;
                        padding: 5px;
                    }
                    th {
                        background-color: #f8f9fa;
                        font-weight: bold;
                        text-align: center;
                    }
                    .total-patrimonio {
                        background-color: #D9D9D9 !important;
                        color: #464648 !important;
                        font-weight: bold;
                    }
                    .text-end {
                        text-align: right;
                    }
                    .text-init{
                        text-align: left;
                    }

                </style>
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
                    <tr>
                        <!-- Columna vacía -->
                        <td style="width: 33%;"></td>
                        <!-- Columna para el texto -->
                        <td style="width: 34%; text-align: center; vertical-align: top;">
                            <p style="color: #464648; margin: 0;">
                                <b>{{ $representantelegal['razon_social'] }}</b><br>
                                <b>NIT - {{$nit}}</b><br>
                                <b>ESTADO CAMBIO PATRIMONIO</b><br>
                                <b>a {{ strtoupper($mes2) }} {{ $dia_numero }} del {{ $anio }}.</b><br>
                                (Cifras expresadas en pesos colombianos)
                            </p>
                        </td>
                        @if($logocliente != '*')
                        <!-- Columna para el logo -->
                        <td style="width: 33%; text-align: right; vertical-align: top;">
                            <img id="logo" src="data:image/jpeg;base64,{{ $logocliente }}" style="max-width: 100px; max-height: 100px;">
                        </td>
                        @else
                        <td style="width: 33%; text-align: center; vertical-align: top;">
                            <p> </p>
                        </td>
                        @endif
                    </tr>
                </table>
                </div>
                <style>
                    .total-patrimonio {
                        background-color: #D9D9D9 !important;
                        color:#464648 !important;
                        font-weight: bold;
                    }
                </style>
                <table class="table table-sm table-bordered datatable-informe w-100">
                    <thead class="card-header">
                        <tr>
                            <th rowspan="2" class="text-center">Concepto</th>
                            <th colspan="4" class="text-center">Año {{ array_key_first($informecambiopatrimonio) }}</th>
                            <th colspan="4" class="text-center">Año {{ array_key_last($informecambiopatrimonio) }}</th>
                        </tr>
                        <tr>
                            <th>Saldo Anterior</th>
                            <th>Aumento</th>
                            <th>Disminución</th>
                            <th>Saldo Actual</th>
                            <th>Saldo Anterior</th>
                            <th>Aumento</th>
                            <th>Disminución</th>
                            <th>Saldo Actual</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $nombresCuentas = [
                                '31' => 'CAPITAL SOCIAL',
                                '33' => 'RESERVAS',
                                '32' => 'SUPERÁVIT DE CAPITAL',
                                '34' => 'GANANCIAS ACUMULADAS',
                                '35' => 'DIVIDENDOS O PARTICIPACIÓN',
                                '36' => 'RESULTADOS DEL EJERCICIO',
                                '37' => 'RESULTADOS DE EJERCICIOS ANTERIORES',
                                '362' => 'RESULTADOS DEL ACUMULADOS',
                                'Total Patrimonio' => 'TOTAL PATRIMONIO'
                            ];
                            $anioActual = array_key_first($informecambiopatrimonio);
                            $anioAnterior = array_key_last($informecambiopatrimonio);
                        @endphp

                        @foreach ($informecambiopatrimonio[$anioActual] as $codigo => $itemActual)
                            @php
                                $itemAnterior = $informecambiopatrimonio[$anioAnterior][$codigo] ?? null;

                                // Normalizar valores a números
                                $valoresActual = [
                                    (float) str_replace(',', '', $itemActual->saldo_anterior ?? 0),
                                    (float) str_replace(',', '', $itemActual->aumento ?? 0),
                                    (float) str_replace(',', '', $itemActual->disminucion ?? 0),
                                    (float) str_replace(',', '', $itemActual->saldo_actual ?? 0),
                                ];

                                $valoresAnterior = $itemAnterior ? [
                                    (float) str_replace(',', '', $itemAnterior->saldo_anterior ?? 0),
                                    (float) str_replace(',', '', $itemAnterior->aumento ?? 0),
                                    (float) str_replace(',', '', $itemAnterior->disminucion ?? 0),
                                    (float) str_replace(',', '', $itemAnterior->saldo_actual ?? 0),
                                ] : [0,0,0,0];

                                // Verificar si todos son 0
                                $todosCeros = collect(array_merge($valoresActual, $valoresAnterior))->every(fn($v) => $v == 0);
                            @endphp

                            @if (!$todosCeros)
                                <tr class="{{ $itemActual->cuenta === 'Total Patrimonio' ? 'total-patrimonio' : '' }}">
                                    <td>
                                        {{ $nombresCuentas[$itemActual->cuenta] ?? $itemActual->cuenta }}
                                    </td>

                                    {{-- Año actual --}}
                                    <td class="text-end">{{ number_format($valoresActual[0], 0) }}</td>
                                    <td class="text-end">{{ number_format($valoresActual[1], 0) }}</td>
                                    <td class="text-end">{{ number_format($valoresActual[2], 0) }}</td>
                                    <td class="text-end">{{ number_format($valoresActual[3], 0) }}</td>

                                    {{-- Año anterior --}}
                                    @if ($itemAnterior)
                                        <td class="text-end">{{ number_format($valoresAnterior[0], 0) }}</td>
                                        <td class="text-end">{{ number_format($valoresAnterior[1], 0) }}</td>
                                        <td class="text-end">{{ number_format($valoresAnterior[2], 0) }}</td>
                                        <td class="text-end">{{ number_format($valoresAnterior[3], 0) }}</td>
                                    @else
                                        <td colspan="4" class="text-center">-</td>
                                    @endif
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <br>
                <div class="tabla-integrales" style="font-size: 10px; margin: 0; padding: 2px !importante;">
                    <div class="firmas">
                        <!-- Firma Representante Legal -->
                        <div class="firma" style="text-align: center;">
                            @if ($representantelegalfirma != '*')
                                <div class="firma2">
                                    <img src="data:image/jpeg;base64,{{ $representantelegalfirma }}" alt="Firma Representante Legal">
                                </div>
                            @else
                                <div class="firma2 no-image"></div>
                            @endif
                            <div class="firmatexto" style="color:black; text-align: center; margin-top: 0;">
                                <strong>{{ $representantelegal['representantelegal'] ?? 'Sin datos encontrados' }}</strong><br>
                                Representante legal<br>C.C. {{ $representantelegal['Cedula'] ?? '' }}
                            </div>
                        </div>
                    
                        <!-- Firma Contador -->
                        <div class="firma" style="text-align: center;">
                            @if ($base64Imagefirmacontador != '*')
                                <div class="firma2">
                                    <img src="data:image/jpeg;base64,{{ $base64Imagefirmacontador }}" alt="Firma Contador">
                                </div>
                            @else
                                <div class="firma2 no-image"></div>
                            @endif
                            <div class="firmatexto" style="color:black; text-align: center; margin-top:0;">
                                <strong>{{ ($datoscontador['nombres'] ?? 'Sin datos') . ' ' . ($datoscontador['apellidos'] ?? '') }}</strong><br>
                                Contador Público<br>T.P {{ $datoscontador['tarje_profesional'] ?? '' }}
                            </div>
                        </div>
                    
                        <!-- Firma Revisor Fiscal -->
                        @if (!empty($representantelegal['revisorfiscal']) && $representantelegal['revisorfiscal'] !== 'Sin datos encontrados')
                            <div class="firma" style="text-align: center;">
                                @if ($revisorfiscalfirma != '*')
                                    <div class="firma2">
                                        <img src="data:image/jpeg;base64,{{ $revisorfiscalfirma }}" alt="Firma Revisor Fiscal">
                                    </div>
                                @else
                                    <div class="firma2 no-image"></div>
                                @endif
                                <div class="firmatexto" style="color:black; text-align: center; margin-top:0;">
                                    <strong>{{ $representantelegal['revisorfiscal'] }}</strong><br>
                                    Revisor Fiscal<br>T.P {{ $representantelegal['cedularevisor'] ?? '' }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            {{--INFORME ESTADO FLUJOS DE EFECTIVO--}}
            <div class="tabla-integrales" style="font-size: 10px; margin: 0; padding: 2px;">
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
                    <tr>
                        <!-- Columna vacía -->
                        <td style="width: 33%;"></td>
                        <!-- Columna para el texto -->
                        <td style="width: 34%; text-align: center; vertical-align: top;">
                            <p style="color: #464648; margin: 0;">
                                <b>{{ $representantelegal['razon_social'] }}</b><br>
                                <b>NIT - {{$nit}}</b><br>
                                <b>ESTADO DE FLUJOS DE EFECTIVOS</b><br>
                                <b>a {{ strtoupper($mes2) }} {{ $dia_numero }} del {{ $anio }}.</b><br>
                                (Cifras expresadas en pesos colombianos)
                            </p>
                        </td>
                        @if($logocliente != '*')
                        <!-- Columna para el logo -->
                        <td style="width: 33%; text-align: right; vertical-align: top;">
                            <img id="logo" src="data:image/jpeg;base64,{{ $logocliente }}" style="max-width: 100px; max-height: 100px;">
                        </td>
                        @else
                        <td style="width: 33%; text-align: center; vertical-align: top;">
                            <p> </p>
                        </td>
                        @endif
                    </tr>
                </table>
                <div class="row table-responsive">
                        <style>
                            .total-patrimonio {
                                background-color: #D9D9D9 !important;
                                color:#464648 !important;
                                font-weight: bold;
                            }
                        </style>
                        <table class="table table-sm table-bordered datatable-informe w-100">
                            <thead class="card-header">
                                <tr>
                                    <th>CONCEPTO</th>
                                    <th>VALOR ACTUAL {{$mes2}} {{$anio}}</th>
                                    <th>VALOR ANTERIOR {{$mes2}} {{$anio-1}}</th>
                                    <th>VARIACIÓN $</th>
                                    <th>VARIACIÓN %</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- 1. Ciclo principal de secciones --}}
                                @if(is_array($informeestadoflujoefectivo))
                                @php 
                                    $sumaTotalAct_Actual = 0; 
                                    $sumaTotalAct_Anterior = 0; 
                                    $objVariacion_Actual = 0;
                                    $objVariacion_Anterior = 0;

                                    $formatNumber = function($n) {
                                        if (round($n, 0) == 0) return '-';
                                        $abs = number_format(abs($n), 0, ',', '.');
                                        return $n < 0 ? "({$abs})" : $abs;
                                    };
                                @endphp

                                @foreach ($informeestadoflujoefectivo as $titulo => $filas)
                                    <tr>
                                        <td colspan="5" style="font-weight: bold; text-align: left; background-color: #f3f3f3;">
                                            {{ strtoupper($titulo) }}
                                        </td>
                                    </tr>

                                    @if(is_array($filas))
                                        @php
                                            // REORDENAMIENTO: Si es Variación Efectivo, forzamos el orden pedido
                                            if ($titulo === 'VARIACION EFECTIVO') {
                                                $ordenDeseado = ['Efectivo Inicial', 'Efectivo Final', 'Incremento Neto'];
                                                usort($filas, function($a, $b) use ($ordenDeseado) {
                                                    return array_search($a['descripcion'], $ordenDeseado) - array_search($b['descripcion'], $ordenDeseado);
                                                });
                                            }
                                        @endphp

                                        @foreach ($filas as $fila)
                                            @php
                                                $descripcion = trim($fila['descripcion'] ?? '');
                                                $keys = array_keys($fila);
                                                $aniosKeys = array_values(array_filter($keys, fn($k) => str_starts_with($k, 'año')));
                                                sort($aniosKeys);

                                                if (count($aniosKeys) < 2) continue;

                                                $actual = floatval($fila[$aniosKeys[1]] ?? 0);
                                                $anterior = floatval($fila[$aniosKeys[0]] ?? 0);
                                                $variacionValor = $actual - $anterior;

                                                // Capturamos el Incremento Neto para validar contra la suma de actividades
                                                if ($descripcion == 'Incremento Neto') {
                                                    $objVariacion_Actual = $actual;
                                                    $objVariacion_Anterior = $anterior;
                                                }
                                                
                                                // Acumulamos sumas de las secciones de actividades para ambos años
                                                if (in_array($titulo, ['ACTIVIDADES DE OPERACION', 'ACTIVIDADES DE INVERSION', 'ACTIVIDADES DE FINANCIACION'])) {
                                                    $sumaTotalAct_Actual += $actual;
                                                    $sumaTotalAct_Anterior += $anterior;
                                                }
                                                // Cálculo de la variación porcentual
                                                $variacionPorcentaje = 0;
                                                if ($anterior != 0) {
                                                    $variacionPorcentaje = ($variacionValor / abs($anterior)) * 100;
                                                }
                                            @endphp

                                            <tr>
                                                <td style="{{ !str_contains($descripcion, '(+') ? 'font-weight:bold;' : '' }}">
                                                    {{ $descripcion }}
                                                </td>
                                                <td style="text-align:right;">{{ $formatNumber($actual) }}</td>
                                                <td style="text-align:right;">{{ $formatNumber($anterior) }}</td>
                                                <td style="text-align:right;">
                                                    @if (round($variacionValor, 0) > 0)
                                                        <span style="color: green; font-family: 'DejaVu Sans', sans-serif;">▲</span> {{ number_format($variacionValor, 0, ',', '.') }}
                                                    @elseif (round($variacionValor, 0) < 0)
                                                        <span style="color: red; font-family: 'DejaVu Sans', sans-serif;">▼</span> ({{ number_format(abs($variacionValor), 0, ',', '.') }})
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td style="text-align:right; font-weight:bold;">
                                                @if ($anterior != 0 && round($variacionValor, 0) != 0)
                                                    {{ number_format($variacionPorcentaje, 2, ',', '.') }}%
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach

                                {{-- 2. BLOQUE FINAL DE VALIDACIÓN --}}
                                @php
                                    $diffActual = round($objVariacion_Actual - $sumaTotalAct_Actual, 0);
                                    $diffAnterior = round($objVariacion_Anterior - $sumaTotalAct_Anterior, 0);
                                @endphp

                                <tr style="background-color: #e9ecef; font-weight: bold;">
                                    <td style="text-align: left;">Validador (Suma Actividades)</td>
                                    <td style="text-align: right;">{{ $formatNumber($sumaTotalAct_Actual) }}</td>
                                    <td style="text-align: right;">{{ $formatNumber($sumaTotalAct_Anterior) }}</td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr style="border-top: 2px solid #000; font-weight: bold;">
                                    <td style="text-align: left;">Diferencia</td>
                                    <td style="text-align: right;">
                                        {{ abs($diffActual) <= 1 ? '-' : $formatNumber($diffActual) }}
                                    </td>
                                    <td style="text-align: right;">
                                        {{ abs($diffAnterior) <= 1 ? '-' : $formatNumber($diffAnterior) }}
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @else
                                    <tr><td colspan="5" class="text-center">No se pudo cargar la estructura del informe.</td></tr>
                                @endif
                        </tbody>
                        </table>

                </div>
                <br>
                <div class="tabla-integrales" style="font-size: 10px; margin: 0; padding: 2px !importante;">
                    <div class="firmas">
                        <!-- Firma Representante Legal -->
                        <div class="firma" style="text-align: center;">
                            @if ($representantelegalfirma != '*')
                                <div class="firma2">
                                    <img src="data:image/jpeg;base64,{{ $representantelegalfirma }}" alt="Firma Representante Legal">
                                </div>
                            @else
                                <div class="firma2 no-image"></div>
                            @endif
                            <div class="firmatexto" style="color:black; text-align: center; margin-top: 0;">
                                <strong>{{ $representantelegal['representantelegal'] ?? 'Sin datos encontrados' }}</strong><br>
                                Representante legal<br>C.C. {{ $representantelegal['Cedula'] ?? '' }}
                            </div>
                        </div>
                    
                        <!-- Firma Contador -->
                        <div class="firma" style="text-align: center;">
                            @if ($base64Imagefirmacontador != '*')
                                <div class="firma2">
                                    <img src="data:image/jpeg;base64,{{ $base64Imagefirmacontador }}" alt="Firma Contador">
                                </div>
                            @else
                                <div class="firma2 no-image"></div>
                            @endif
                            <div class="firmatexto" style="color:black; text-align: center; margin-top:0;">
                                <strong>{{ ($datoscontador['nombres'] ?? 'Sin datos') . ' ' . ($datoscontador['apellidos'] ?? '') }}</strong><br>
                                Contador Público<br>T.P {{ $datoscontador['tarje_profesional'] ?? '' }}
                            </div>
                        </div>
                    
                        <!-- Firma Revisor Fiscal -->
                        @if (!empty($representantelegal['revisorfiscal']) && $representantelegal['revisorfiscal'] !== 'Sin datos encontrados')
                            <div class="firma" style="text-align: center;">
                                @if ($revisorfiscalfirma != '*')
                                    <div class="firma2">
                                        <img src="data:image/jpeg;base64,{{ $revisorfiscalfirma }}" alt="Firma Revisor Fiscal">
                                    </div>
                                @else
                                    <div class="firma2 no-image"></div>
                                @endif
                                <div class="firmatexto" style="color:black; text-align: center; margin-top:0;">
                                    <strong>{{ $representantelegal['revisorfiscal'] }}</strong><br>
                                    Revisor Fiscal<br>T.P {{ $representantelegal['cedularevisor'] ?? '' }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="page-break"></div>
        @endif
        {{-- SECCION NOTAS A LOS ESTADOS FINANCIEROS --}}
        <!-- seccion de la otra informacion -->
        <div class="certificacion">
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px; border: none;">
                <tr>
                    <!-- Columna vacía -->
                    <td style="width: 20%; border: none;"></td>
                    <!-- Columna para el texto -->
                    <td style="width: 45%; text-align: center; vertical-align: top; border: none;">
                        <p style="color: #464648; margin: 0;">
                            <b>NOTAS A LOS ESTADOS FINANCIEROS</b><br>{{ $representantelegal['razon_social'] }}<br>
                            <b>NIT - {{$nit}}</b><br>
                            <b>a {{ strtoupper($mes2) }} {{ $dia_numero }} del {{ $anio }}.</b><br>
                            (Cifras expresadas en pesos colombianos)
                        </p>
                    </td>
                    
                    @if($logocliente != '*')
                    <!-- Columna para el logo -->
                    <td style="width: 33%; text-align: right; vertical-align: top;border: none;">
                        <img id="logo" src="data:image/jpeg;base64,{{ $logocliente }}" style="max-width: 100px; max-height: 100px;">
                    </td>
                    @else
                    <td style="width: 20%; text-align: center; vertical-align: top;border: none;">
                        <p> </p>
                    </td>
                    @endif
                </tr>
            </table>
            
            <div class="grid-container">
                <div class="grid-item titulos"> <b>NOTA 1 {{ $representantelegal['gruponiif'] == 3 ? 'Ente económico y políticas contables' : 'ENTIDAD REPORTANTE' }}</b></div>
            </div>
            <h5 style="text-align: left;color:#464648;">{{ $representantelegal['razon_social'] }}</h5>
            <p class="justificar">
                @php
                    // Obtén el texto de la variable
                    $texto = $representantelegal['actividadeconomica'];

                    // Verifica si el texto está vacío
                    if (empty($texto)) {
                        $mensaje = 'Por favor, ingrese la información del ente economico en el módulo de empresas.';
                        $mostrarMensaje = true;
                    } else {
                        // Encuentra la posición de "Se inscribieron"
                        $posicion = strpos($texto, 'Se inscribieron');

                        // Divide el texto si se encuentra la palabra "Se inscribieron"
                        if ($posicion !== false) {
                            $parte1 = substr($texto, 0, $posicion);
                            $parte2 = substr($texto, $posicion);
                        } else {
                            // Si no se encuentra la palabra, el texto se mantiene como está
                            $parte1 = $texto;
                            $parte2 = '';
                        }

                        $mostrarMensaje = false;
                    }
                @endphp
                @if ($mostrarMensaje)
                    <p style="color: red;text-align: left;">{{ $mensaje }}</p>
                @else
                    {{ $parte1 }}<br>{{ $parte2 }}
                @endif
            </p>
            {{--nota 2--}}
            @if($representantelegal['gruponiif'] == 3)
                <div>
                    <div class="grid-container">
                        <div class="grid-item titulos"><b>NOTA 2: Marco técnico y normativo</b></div>
                    </div>
                    <h3 class="justificar">Declaración de cumplimiento</h3>
                    <p class="justificar">
                        Los estados financieros individuales de {{$representantelegal['razon_social']}} han sido preparados de acuerdo con los principios y normas de contabilidad e información financiera, aceptados en Colombia (NCIF), reglamentadas en el Decreto 2420 de 2015 y sus modificaciones. Estas normas están fundamentadas en las Normas Internacionales de Información Financiera - NIIF y sus Interpretaciones emitidas por el Consejo de Normas Internacionales de Contabilidad (IASB, por sus siglas en inglés) al segundo semestre de 2017 y otras disposiciones legales aplicables para las entidades vigiladas y/o controladas por la Contaduría General de la Nación, que pueden diferir en algunos aspectos de los establecidos por otros organismos de control del Estado.
                    </p>
                    <p class="justificar">
                        {{$representantelegal['razon_social']}} para elaborar sus estados financieros, los estructura bajo los siguientes supuestos contables:
                    </p>
                    <p class="justificar">
                        a. Base de acumulación (o devengo): {{$representantelegal['razon_social']}} reconoce los efectos de las transacciones y demás sucesos cuando ocurrieron; así mismo, se registrarán en los libros contables y se informará sobre ellos en los estados de los períodos con los cuales se relacionan.
                    </p>
                    <p class="justificar">
                        b. Negocio en marcha: {{$representantelegal['razon_social']}} preparará sus estados financieros sobre la base que está en funcionamiento, y continuará sus actividades de operación dentro del futuro previsible.
                    </p>
                    <h3 class="justificar">Bases de medición</h3>
                    <p class="justificar">
                        Los estados financieros de la Compañía han sido preparados sobre la base de costo histórico, excepto por activos y pasivos financieros a valor razonable con cambios en resultados y/o cambios en otro resultado integral que se valúan a sus valores razonables al cierre de cada periodo, como se explica en las políticas contables incluidas más adelante. Por lo general, el costo histórico se basa en el valor razonable de la contraprestación otorgada a cambio de los bienes y servicios. El valor razonable es el precio que se recibiría al vender un activo o se pagaría al transferir un pasivo en una transacción ordenada entre participantes del mercado a la fecha de la medición. Al estimar el valor razonable, la Compañía utiliza los supuestos que los participantes del mercado utilizarían al fijar el precio del activo o pasivo en condiciones de mercado presentes, incluyendo supuestos sobre el riesgo.
                    </p>
                    <h3 class="justificar">Moneda funcional y de presentación</h3>
                    <p class="justificar">
                        Los estados financieros son presentados en pesos colombianos, la cual es la moneda funcional de la Compañía y se determina en función al entorno económico principal en el que opera. Los estados financieros se presentan en pesos colombianos. Toda la información es presentada en millones de pesos.
                    </p>
                    <h3 class="justificar">Presentación de Estados Financieros </h3>
                    <p class="justificar">
                        a. Presentación razonable <br>
                        La sociedad presenta razonable y fielmente la situación financiera, el rendimiento financiero y los flujos de efectivo, revelando información adicional necesaria para la mejor razonabilidad de la información. 
                        
                    </p>
                    <p class="justificar">
                        b. Cumplimiento de la NIIF para las PYMES <br>
                        La entidad elaborará sus estados financieros con base a la Norma Internacional de Información Financiera para las Pequeñas y Medianas Entidades (NIIF para las PYMES), que es el marco de referencia adoptado. 

                    </p>
                    <p class="justificar">
                        c. Frecuencia de la información <br>
                        La empresa presenta un juego completo de estados financieros anualmente, el cual estará conformado por: un estado de situación financiera; un estado de resultado integral; un estado de cambios en el patrimonio; un estado de flujo de efectivo y notas de las principales políticas significativas de la empresa. 

                    </p>
                    <p class="justificar">
                        d. Revelaciones en las notas a los estados financieros <br>
                        Las notas a los estados financieros se presentan de forma sistemática, haciendo referencia a los antecedentes de la sociedad, la conformidad con la normativa internacional, las partidas similares que poseen importancia relativa se presenta por separado, la naturaleza de sus operaciones y principales actividades; el domicilio legal; su forma legal, incluyendo el dispositivo o dispositivos de ley pertinentes a su creación o funcionamiento y otra información breve sobre cambios fundamentales referidos a incrementos o disminuciones en su capacidad productiva, entre otros. 

                    </p>
                </div>
            @else
                <div>
                    <div class="grid-container">
                        <div class="grid-item titulos"><b>NOTA 2: PRINCIPALES POLÍTICAS Y PRÁCTICAS CONTABLES</b></div>
                    </div>
                    
                    <p class="justificar">
                        <h3 class="justificar"><strong>2.1	 Bases de Presentación de los Estados Financieros</strong></h3>
                        Los estados financieros, son preparados en concordancia con la Normas Internacionales de Información Financiera (NIIF) y atendiendo la normatividad vigente en Colombia establecida en la Ley 1304 del 2009, Decreto 3024 del 2013, Decretos 2483 de 2018 y su anexo técnico compilatorio No 2 de las normas internacionales de información financiera NIIF  - grupo 2, y el Decreto 2270 de 2019. Las NIIF comprenden a las Normas Internacionales de Información Financiera (NIIF). Los mismos serán elaborados sobre la base del costo histórico, el cual es modificado por el valor razonable de los activos financieros.
                        El 13 de Julio del 2009, el gobierno nacional expidió la Ley 1304, por la cual se regulan los principios y normas de contabilidad e información financiera y de aseguramiento de la información en Colombia, que conformen un sistema único y homogéneo de alta calidad, comprensible y de forzosa observancia. Esta ley aplica a todas las personas naturales y jurídicas que de acuerdo con la norma, estén obligadas a llevar contabilidad.
                        La Entidad preparó sus estados financieros de acuerdo con los Principios de Contabilidad Generalmente Aceptados en Colombia (PCGA). La información financiera correspondiente a períodos anteriores, incluida en los presentes estados financieros
                        Con propósitos comparativos, ha sido modificada y se presenta de acuerdo con el nuevo marco técnico normativo. 
                        Los estados financieros han sido preparados sobre la base del costo histórico.
                        Los presentes estados financieros se presentan en miles de pesos colombianos, salvo cuando se indique lo contrario.

                    </p>
                    
                    <p class="justificar">
                        <h3 class="justificar"><strong>2.2 Conversión de Transacciones y Saldos en Moneda Extranjera</strong></h3>
                        <strong>Moneda Funcional y Moneda de Presentación</strong><br>
                        Las partidas incluidas en los estados financieros se expresan en la moneda del ambiente económico primario donde opera la sociedad (pesos colombianos). 
                        Los estados financieros se presentan en “pesos colombianos”, que es la de presentación. <br><br>
                        <strong>Transacciones y Saldos en Moneda Extranjera</strong><br>
                        Las transacciones en moneda extranjera son inicialmente registradas a las tasas de cambio vigentes a la fecha de la transacción. <br>
                        Los activos y pasivos monetarios denominados en moneda extranjera se convierten a la moneda funcional al cierre de cada período con la tasa de cambio representativa del mercado certificada por la Superintendencia Financiera de Colombia. Las ganancias y pérdidas por diferencias en cambio se reconocen en el estado de resultado.
                        Las partidas no monetarias que se miden por su costo histórico en moneda extranjera se convierten utilizando las tasas de cambio vigentes a la fecha de la transacción. Las partidas no monetarias que se miden por su valor razonable se convierten a la tasa de cambio de la fecha en que se determina el valor razonable. Las ganancias o pérdidas que surjan de la conversión de las partidas no monetarias se reconocen en función de la ganancia o pérdida de la partida que dio origen a la diferencia por conversión. Por lo tanto, las diferencias por conversión de las partidas cuya ganancia o pérdida son reconocidas en el otro resultado integral o en los resultados se reconocen también en el otro resultado integral o en resultados, respectivamente.

                    </p>
                    <p class="justificar">
                        <h3 class="justificar"><strong>2.3	 Periodo Cubierto </strong></h3>
                        Los presentes Estados Financieros   cubren los siguientes períodos:<br>
                        <ol>
                            <li>Estados de Situación Financiera: Al 31  de diciembre del {{$anio}}</li>
                            <li>Estados de Resultado Integral: Por el periodo de {{ strtoupper($mes2) }} {{ $dia_numero }}  hasta el 31  diciembre del {{$anio}}</li>
                            <li>Otro Resultado integral: Por el periodo de {{ strtoupper($mes2) }} {{ $dia_numero }}  hasta el 31  diciembre del {{$anio}}</li>
                            <li>Cambio de situación en le patrimonio: Por el periodo de {{ strtoupper($mes2) }} {{ $dia_numero }}  hasta el 31  diciembre del {{$anio}}</li>
                            <li>Flujo de caja método indirecto: Por el periodo de {{ strtoupper($mes2) }} {{ $dia_numero }}  hasta el 31  diciembre del {{$anio}}</li>
                            <li>Y las revelaciones que comprenden un resumen de las políticas contables significativas y otra información explicativa.</li>
                        </ol>
                    </p>
                    <p class="justificar">
                        <h3 class="justificar"><strong>2.4	Clasificación de Saldos en Corrientes y No Corrientes</strong></h3>
                        En el estado de situación financiera, los saldos se clasifican en función de sus vencimientos, 
                        como corrientes con vencimiento igual o inferior a doce meses contados desde la fecha de corte 
                        de los estados financieros y como no corrientes, los mayores a ese ejercicio.
                    </p>
                    <p class="justificar">
                        <h3 class="justificar"><strong>2.5	Publicación de los Estados Financieros</strong></h3>
                        La empresa publicara de manera anual, el conjunto completo de los estados financieros <br>
                        <ol>
                            <li>Estados de Situación Financiera</li>
                            <li>Estados de Resultado Integral</li>
                            <li>Otro Resultado integral: </li>
                            <li>Cambio de situación en le patrimonio</li>
                            <li>Flujo de caja método directo</li>
                        </ol>
                        Y las revelaciones que comprenden un resumen de las políticas contables significativas y otra información explicativa.
                    </p>
                    <p class="justificar">
                        <h3 class="justificar"><strong>2.6	 Efectivo y Equivalentes de Efectivo</strong></h3>
                        Se considera efectivo tanto a los fondos en caja como a los depósitos bancarios a la vista de libre disponibilidad.
                         Se consideran equivalentes al efectivo a las inversiones a corto plazo de gran liquidez y libre disponibilidad que, 
                         sin previo aviso ni costo relevante, pueden convertirse fácilmente en una cantidad determinada de efectivo conocida 
                         con alto grado de certeza al momento de la imposición, están sujetas a un riesgo poco significativo de cambios en su
                          valor, con vencimientos hasta tres meses posteriores a la fecha de las respectivas imposiciones, y cuyo destino 
                          principal no es el de inversión o similar, sino el de cancelación de compromisos a corto plazo. Los adelantos en 
                          cuentas corrientes bancarias son préstamos que devengan interés, exigibles a la vista, y forman parte de la gestión 
                          de tesorería de la Matriz, por lo que también se asimilan a los equivalentes al efectivo.
                        <br>Los estados consolidados de flujo de efectivo que se acompañan fueron preparados usando el método directo, 
                        que consiste en rehacer el estado de resultados utilizando el sistema de caja, principalmente para determinar 
                        el flujo de efectivo en las actividades de operación.

                    </p>
                    <p class="justificar">
                        <h3 class="justificar"><strong>2.7	 Clasificación de Activos y Pasivos</strong></h3>
                        Se considera efectivo tanto a los fondos en caja como a los depósitos bancarios a la vista de libre disponibilidad.
                         Los activos y pasivos se clasifican de acuerdo con el uso a que se destinan o según su grado de realización, 
                         disponibilidad, exigibilidad o liquidación, en términos de tiempo y valores.
                        <br>Para el efecto, se entiende como activos corrientes aquellas sumas que serán realizables o estarán disponibles 
                        en un plazo no mayor a un año y como pasivos corrientes, aquellas sumas que serán exigibles o liquidables también en un plazo no mayor a un año.
                        <h4 class="justificar"><strong>Instrumentos Financieros</strong> </h4>
                        Los Instrumentos financieros se clasifican en:<br>
                        <strong>Activos Financieros </strong><br>
                        <strong>Pasivos Financieros </strong><br>
                        <strong>Activos No Financieros</strong><br>
                        Los activos y pasivos financieros se miden inicialmente al valor razonable. Los costos de transacción que son directamente 
                        atribuibles a la adquisición o emisión de activos y pasivos financieros (distintos a los activos y pasivos financieros 
                        designados al valor razonable con cambio en los resultados) se agregan o deducen del valor razonable de los activos o 
                        pasivos financieros, cuando sea apropiado, al momento del reconocimiento inicial. Los costos de transacción directamente 
                        atribuibles a la adquisición de activos o pasivos financieros designados al valor razonable con cambio en los resultados se reconocen de inmediato en ganancias o pérdidas.
                        <h4 class="justificar"><strong>2.7.1	Activos Financieros</strong> </h4>
                        Los activos financieros se clasifican de acuerdo con el propósito para el cual fueron adquiridos en las siguientes categorías:
                        <br>
                        Activos financieros al valor razonable con ajuste a resultados.
                        <br>
                        Deudores por cobrar y préstamos.
                        <br>
                        Activos financieros mantenidos hasta su vencimiento.
                        <br>
                        La clasificación depende del propósito para el cual se adquirieron los activos financieros. La gerencia determina la clasificación de sus activos financieros a la fecha de su reconocimiento inicial.
                        <br>
                        Todas las compras o ventas regulares de activos financieros son reconocidas y dadas de baja a la fecha de la transacción. Las compras o ventas regulares son todas aquellas compras o ventas de activos financieros que requieran la entrega de activos dentro del marco de tiempo establecido por una regulación o acuerdo en el mercado.
                        <h4 class="justificar"><strong>2.7.2 Deudores comerciales y otras cuentas por cobrar</strong> </h4>
                        Al inicio las cuentas por cobrar se miden por el valor razonable de la contraprestación por recibir.
                        <br>
                        Después del reconocimiento inicial, se miden al costo amortizado, menos cualquier deterioro del valor.
                        <br>
                        Las pérdidas que resulten del deterioro del valor se reconocen en el estado de resultado como costos.
                        <h4 class="justificar"><strong>2.7.3 Pasivos Financieros</strong> </h4>
                        Los pasivos financieros se reconocen inicialmente a su valor razonable, neto de los costos incurridos en la transacción y posteriormente se registran a su costo amortizado.<br>
                        Los pasivos financieros de la entidad incluyen las cuentas por pagar comerciales y otras cuentas por pagar, los sobregiros en cuentas corrientes bancarias, las deudas y préstamos que devengan intereses, los contratos de garantía financiera y los pasivos financieros derivados con y sin cobertura eficaz.<br>
                        El método del interés efectivo es un mecanismo de cálculo del costo amortizado de un pasivo financiero y de asignación de gasto por intereses durante el período. La tasa de interés efectiva es la tasa que descuenta exactamente los flujos futuros de efectivo a pagar a través del plazo total de la obligación financiera.<br>
                        Las cuentas por pagar son obligaciones de pago por bienes o servicios de salud y administrativos que se han adquirido de los proveedores nacionales y del exterior en el curso ordinario de los negocios. Las cuentas por pagar se clasifican como pasivos corrientes, si el pago debe ser efectuado en período de un año o menos (o en el ciclo normal de explotación de la empresa si es más largo). Si el pago debe ser efectuado en un período superior a un año se presentan como pasivos no corrientes.<br>
                        Los pasivos financieros se clasifican en pasivo corriente o pasivo no corriente, dependiendo del plazo definido para cada obligación, cada una de ellas se rige por un acuerdo que contiene las condiciones específicas para cada desembolso.<br>
                        La entidad da de baja los pasivos financieros cuando las obligaciones se liquidan, cancelan o expiran<br>
                        Las demás cuentas por pagar de corto plazo son medidas al valor nominal, no presentan diferencias con respecto al monto facturado debido a que la transacción no tiene costos significativos asociados.<br>
                        <h4 class="justificar"><strong>2.7.4 Cuentas y Documentos por Pagar</strong></h4>
                        Representan obligaciones a cargo de la entidad, originadas en bienes o servicios recibidos, se registran por separado en orden a su importancia y materialidad.
                    </p>
                    <p class="justificar">
                        <h3 class="justificar"><strong>2.8	 Inventarios</strong></h3>
                        Los inventarios se contabilizan al costo y este se determina con base en el método de promedio ponderado y al costo estándar, según el tipo de inventario.
                        <br>
                        La entidad evalúa al cierre de cada mes todos los tipos de inventario, para determinar productos con rotación normal, inventarios obsoletos e inventarios con baja rotación. El inventario de producto terminado es reducido a su valor neto realizable si éste es menor.
                        <br>
                        Cuando se presentan rebajas de valor, hasta alcanzar el valor neto realizable, la entidad reconoce dicha pérdida como costo del período. Si en los períodos siguientes se dan incrementos en el valor neto realizable, que signifiquen una reversión de la rebaja de valor, se reconocen como un menor valor del costo en el período en que ocurra y si se presentan en períodos posteriores, como una recuperación del valor neto realizable.
                        <br>
                        La entidad realiza análisis de ir recuperabilidad de los inventarios con obsolescencia de acuerdo al tipo de inventario (inventario de materias primas, materiales, químicos, suministros y repuestos, inventario de producto en proceso, inventario de producto terminado).
                        <br>
                        Para determinar el deterioro y la obsolescencia de los inventarios, la entidad analiza las referencias que tengan fecha de permanencia por más de dos años o que sin tener dos años de antigüedad no tengan expectativa de uso; o que por su calidad no son aptos para procesos subsiguientes, para las referencias que se concluya que existe obsolescencia se aplica el 100% del deterioro sobre su costo teniendo en cuenta los criterios y lineamientos para el manejo de inventarios obsoletos.
                        <br>
                        La entidad considera como inventarios de baja rotación aquellos que están entre cero y dos años y para los cuales determina el precio de venta y los costos y gastos de vender igual a los inventarios con rotación normal.
                        Obligaciones Financieras
                        <br>
                        Comprende el valor de las obligaciones contraídas para la obtención de Créditos y Leasing con Entidades Financieras, con destino a incrementar el capital de trabajo y a financiar la Propiedad de Inversión en la que está invirtiendo la compañía, proyectando su crecimiento y mejora de calidad en la parte asistencial, administrativa y comercial; estas obligaciones se clasifican en corto y largo plazo

                    </p>
                    <p class="justificar">
                        <h3 class="justificar"><strong>2.9	 Propiedad, Planta y Equipo - Neto</strong></h3>
                        Las propiedades plantas y equipos son aquellas que posee la entidad  para su uso en la producción o suministro de bienes y servicios o para propósitos administrativos; que se esperan usar durante más de un período, son valorizadas al costo, menos la depreciación acumulada y las pérdidas por deterioro que se identifiquen<br>
                        El costo de los elementos de propiedades, planta y equipos comprende: <br>
                        <ul style="list-style-type: lower-alpha; padding-left: 2em;">
                            <li>Precio de compra: Incluye los aranceles de importación, el impuesto a las ventas u otros impuestos no deducibles menos los descuentos y rebajas.</li>
                            <li>Costos necesarios de puesta en marcha: Incluye todos los costos directamente atribuibles a la ubicación y fabricación del activo en el lugar y en las condiciones necesarias para que pueda operar de la forma prevista por la gerencia.</li>
                        </ul>
                        Únicamente pueden capitalizarse aquellos costos posteriores que cumplan los siguientes requisitos: <br>
                        <ul style="list-style-type: lower-alpha; padding-left: 2em;">
                            <li>Incrementan la productividad del activo (unidades producidas o eficiencias que impliquen menores costos de producción).</li>
                            <li>Incrementan la vida útil del activo.</li>
                            <li>Cuando elementos de una partida de propiedad, planta y equipo poseen vidas útiles distintas, y su valor es representativo, son registradas como partidas separadas (componentes importantes) de propiedad, planta y equipo.</li>
                        </ul>
                        La depreciación de las propiedades, planta y equipo comienzan cuando el activo esté disponible para su uso, esto es, cuando se encuentre en la ubicación y en las condiciones necesarias para operar de la forma prevista por la gerencia. La depreciación de un activo cesa cuando el activo se clasifique como mantenido para la venta y la fecha en que se produzca la baja en cuentas del mismo.
                        <br>
                        El método de depreciación definido por la entidad es el método de línea recta para todas las clases de activos fijos.
                        <br>
                        El cargo por depreciación de cada período se reconoce en el resultado del período
                        <br>
                        Las vidas útiles para depreciar las propiedades, planta y equipo de la entidad son las siguientes:
                        <table>
                            <tr>
                                <th style="text-align: center;"><b>CLASE</b></th>
                                <th>VIDA ÚTIL </th>
                            </tr>
                            <tr>
                                <td>CONSTRUCCIONES Y EDIFICACIONES</td>
                                <td>45 años</td>
                            </tr>
                            <tr>
                                <td>MAQUINARIA Y EQUIPO</td>
                                <td>10 años</td>
                            </tr>
                            <tr>
                                <td>MUEBLES Y ENSERES</td>
                                <td>10 años</td>
                            </tr>
                            <tr>
                                <td>EQUIPO DE OFICINA</td>
                                <td>5 años</td>
                            </tr>
                            <tr>
                                <td>EQUIPO DE COMPUTACION Y COMUNICACION</td>
                                <td>5 años</td>
                            </tr>
                            <tr>
                                <td>FLOTA Y EQUIPO DE TRANSPORTE</td>
                                <td>10 años</td>
                            </tr>
                        </table>
                        <ul style="list-style-type: lower-alpha; padding-left: 2em;">
                            <li>Estima un valor residual del 20% para Construcciones y edificaciones y del 10% para los demás Activos.</li>
                            <li>Si existe algún indicio de que se ha producido un cambio significativo en la tasa de depreciación, vida útil o valor residual de un activo, se revisa depreciación de ese activo de forma prospectiva para reflejar las nuevas expectativas.</li>
                            <li>Se activarán únicamente los activos superiores a 50UVT. </li>
                        </ul>
                        Los gastos de reparación y mantenimiento se registran con cargo a los resultados, en tanto que las mejoras y reparaciones que alargan la vida útil del activo son registradas como mayor valor del mismo.
                    </p>
                    <p class="justificar">
                        <h3 class="justificar"><strong>2.10 Propiedades de Inversión</strong></h3>
                        Son propiedades de inversión, (terrenos o edificios), aquellos que tiene la entidad , para arrendarlos, ganar rentas o plusvalías y no para su uso en la producción o suministro de bienes o servicios o bien para fines administrativos o su venta en el curso ordinario de las operaciones (inventarios).<br>
                        El costo de las propiedades de inversión comprende: <br>
                        <ul style="list-style-type: lower-alpha; padding-left: 2em;">
                            <li>Costos de adquisición y cualquier desembolso directamente atribuible tales como: honorarios profesionales por servicios legales e impuestos por traspaso de las propiedades.</li>
                        </ul>
                        Costos en que se incurra para construir una partida de propiedades de inversión. <br>
                        La entidad utiliza el método del costo para la medición posterior de las propiedades de inversión y el método de depreciación definido es el método de línea recta.
                        <br>
                        La entidad revisa al cierre del período contable las vidas útiles definidas por cada activo para verificar si continúan siendo adecuadas. Para las propiedades de inversión fue definida en 45  años para construcciones y edificaciones, para terrenos se definió una vida útil indefinida.
                        <br>
                        La entidad evalúa, al final de cada período, si existe algún indicio de deterioro del valor de algún activo. Si existe, la entidad estima el importe recuperable del activo.
                        <br>
                        Cualquier ganancia o pérdida por la venta de una propiedad de inversión (calculada como la diferencia entre el valor de venta y el valor en libros del elemento) se reconoce en el resultado del período.
                        <br>
                        Las propiedades de inversión se dan de baja al momento de su venta o cuando no se espera obtener beneficios económicos futuros por su uso o disposición.

                    </p>
                    <p class="justificar">
                        <h3 class="justificar"><strong>2.11 Arrendamientos Operativos y Financieros</strong></h3>
                        La determinación de si un acuerdo constituye o incluye un arrendamiento se basa en la esencia del acuerdo a la fecha de su celebración, en la medida en que el cumplimiento del acuerdo dependa del uso de uno o más activos específicos, o de que el acuerdo conceda el derecho de uso del activo, incluso si tal derecho no se encuentra especificado de manera explícita en el acuerdo.
                    </p>
                    <p class="justificar">
                        <h3 class="justificar"><strong>2.12 Activos Intangibles</strong></h3>
                        Los activos intangibles incluidos en los estados financieros cumplen las definiciones de identificabilidad, control y generación de beneficios económicos futuros para ser reconocidos.
                        <br>
                        El costo de los activos intangibles, corresponde al precio que una entidad paga para adquirir separadamente un activo intangible menos la amortización acumulada y las pérdidas acumuladas por deterioro.
                        <br>
                        La vida útil de un activo intangible se define teniendo en cuenta el período que se esperan beneficios económicos futuros. El método de amortización utilizado por la entidad es el método de línea recta. Tanto el período como el método de amortización utilizado para un activo intangible se revisan como mínimo, al final de cada año.
                        <br>
                        Los activos intangibles tienen una vida útil definida y se amortizan en un rango de 1 a 5 años y de acuerdo a las condiciones contractuales de su adquisición.
                        <br>
                        Las marcas generadas internamente no son reconocidas en el estado de situación financiera. Los activos intangibles se miden posteriormente bajo el modelo del costo, del cual se deducen del monto de reconocimiento inicial, las amortizaciones en función de las vidas útiles estimadas y las pérdidas por deterioro de valor que se presenten o acumulen. El efecto de las amortizaciones y el de los potenciales deterioros se registra en los resultados del período, a menos que en el caso de las primeras, se registren como mayor valor en la construcción o confección de un nuevo activo.
                        <br>
                        Un activo intangible se da de baja al momento de su venta o cuando no se espera obtener beneficios económicos futuros por su uso o disposición. La ganancia o pérdida surgida al dar de baja el activo, se calcula como la diferencia entre los ingresos de la venta neta, en su caso, y el valor en libros del activo. Este efecto se reconoce en los resultados del período.

                    </p>
                    <p class="justificar">
                        <h3 class="justificar"><strong>2.13 Otros Activos No Financieros</strong></h3>
                        Los otros activos no financieros incluyen principalmente costos y gastos que cubren varias vigencias contables tales como intereses, seguros y mantenimientos. Estas partidas se amortizan en el período en el cual se considera se recibirá el beneficio futuro.
                        <br>
                        Al cierre de cada período la entidad garantiza que los saldos en las cuentas de gastos pagados por anticipado corresponden a pagos por bienes o servicios que aún no han sido recibidos y se presentan como una partida de otros activos no financieros.
                    </p>
                    <p class="justificar">
                        <h3 class="justificar"><strong>2.14 Costos por Préstamos</strong></h3>
                        Los costos por préstamos que sean directamente atribuibles a la adquisición, construcción o producción
                         de un activo que necesariamente lleve un período de tiempo sustancial para que esté disponible para 
                         su uso esperado o su venta (activos aptos), se capitalizan como parte del costo del activo respectivo. 
                         Todos los demás costos por préstamos se contabilizan como gastos en el período en el que se incurren. 
                         Los costos por préstamos incluyen los intereses y otros costos en los que incurre la entidad en relación con la
                          celebración de los acuerdos de préstamos respectivos.
                    </p>
                    <p class="justificar">
                        <h3 class="justificar"><strong>2.15 Impuesto, Gravámenes y Tasas</strong></h3>
                        Representan el valor de los gravámenes de carácter general y obligatorio a favor del Estado y a cargo de la entidad, determinados con base en las liquidaciones privadas sobre las respectivas bases impositivas generadas en el correspondiente período fiscal.
                        <br>
                        El saldo incluye retención en la fuente, retención de industria y comercio, impuesto sobre la renta y complementarios, impuesto sobre las ventas, impuesto de industria y comercio, los cuales se registran según la normatividad fiscal vigente.
                        <br>
                        El saldo por pagar por impuesto sobre la renta se determina con base en estimaciones y su valor es llevado a los resultados del período, se presenta neto de anticipos y retenciones al cierre del período contable.
                        <br>
                        El impuesto sobre la renta por pagar se determina con base en estimaciones, según disposiciones fiscales vigentes, que para el año en curso es del 35 %. La provisión para impuesto sobre la renta es llevada al resultado del ejercicio.

                    </p>
                    <p class="justificar">
                        <h3 class="justificar"><strong>2.16 Impuestos Diferidos</strong></h3>
                        El impuesto sobre la renta diferido se reconoce por diferencias temporarias existentes entre la base fiscal de los activos y los pasivos y su valor en los libros para propósitos de reporte financiero.
                        <br>
                        Los impuestos diferidos son medidos a la tasa impositiva que se espera aplicar a las diferencias temporarias cuando estas sean revertidas, con base en las leyes que han sido aprobadas o que están a punto de ser aprobadas a la fecha del informe.
                        <br>
                        El valor en libros de los activos por impuestos diferidos se revisa en cada fecha de presentación y se reduce en la medida en que ya no sea probable que existan utilidades gravables suficientes para emplear la totalidad o parte del activo por impuesto diferido. Los activos por impuestos diferidos no reconocidos son revisados en cada fecha de cierre y se reconocen en la medida en que sea probable que existan utilidades gravables futuras que permiten que el activo por impuesto diferido sea recuperado.
                        <br>
                        El impuesto diferido relacionado con partidas reconocidas fuera de resultados, se reconocen en correlación con la transacción subyacente, ya sea en ORI o directamente en el patrimonio.
                        <br>
                        Los activos y pasivos por impuestos diferidos se compensan si existe un derecho exigible para compensar los activos y pasivos por impuestos corrientes, y cuando los activos y pasivos por impuestos diferidos se derivan de impuestos sobre las ganancias correspondientes a la misma autoridad fiscal y recaen sobre la misma entidad o contribuyente fiscal, o en diferentes entidades o contribuyentes fiscales, pero la entidad pretende liquidar los activos y pasivos fiscales corrientes por su importe neto, o bien, realizar simultáneamente sus activos y pasivos fiscales.
                        <br>
                        Se revelará a cada finalización del periodo y no en los estados financieros.
                    </p>
                    <p class="justificar">
                        <h3 class="justificar"><strong>2.17 Beneficios a los Empleados</strong></h3>
                        Incluye tanto las obligaciones laborales como las estimaciones realizadas para cubrir todos los beneficios a empleados que posee la entidad, los beneficios a empleados son todas las formas de contraprestación concedida por la entidad a cambio de los servicios prestados por los empleados o por las indemnizaciones por el cese de las actividades laborales de los empleados, los cuales se clasifican en: beneficios de corto plazo, de largo plazo y post-empleo.
                        <br>
                        Los beneficios a corto plazo corresponden a los beneficios diferentes a los de indemnizaciones que se esperan liquidar totalmente antes de los doce meses al final del periodo anual sobre que se informa entre ellos se incluye sueldos, salarios 
                        Aportes a la seguridad social, licencias remuneradas y ausencias por incapacidades, primas de servicios, prima de vacaciones y participación en ganancias e incentivos como bonos de desempeño. Todos los beneficios de corto plazo se reconocen y se miden por el valor a pagar.
                        <br>
                        Los beneficios de largo plazo y post-empleo corresponden a los beneficios a empleados diferentes a los beneficios de corto plazo, entre ellos se incluyen las primas de antigüedad, pensiones de jubilación, retroactividad de cesantías, y seguro de vida. Todos los beneficios de largo plazo son valorados aplicando el método de la unidad de crédito proyectada calculado por un actuario al cierre de cada período contable. La entidad contabiliza los costos de servicios como mayor valor de los beneficios y el costo del interés de cada beneficio como gastos financieros.
                    </p>
                    <p class="justificar">
                        <h3 class="justificar"><strong>2.18 Provisiones, Pasivos Contingentes y Activos Contingentes </strong></h3>
                        <h4 class="justificar"><strong>Provisiones</strong></h4>
                        Una provisión se reconoce si existe una obligación legal o implícita derivada de un hecho o suceso pasado que puede ser estimada de forma fiable y es probable que sea necesario un flujo de salida de beneficios económicos para resolver la obligación en el futuro. <br>
                        Si el efecto del valor temporal del dinero es significativo, las provisiones se descuentan utilizando una tasa actual de mercado antes de impuestos que refleja, cuando corresponda, los riesgos específicos del pasivo. Cuando se reconoce el descuento, el aumento de la provisión producto del paso del tiempo, se reconoce como costos financieros en el estado de resultados.
                        <h4 class="justificar"><strong>Pasivos Contingentes</strong></h4>
                        Un pasivo contingente es una obligación posible, surgida a raíz de sucesos pasados y cuya existencia ha de ser confirmada solo por la ocurrencia, o en su caso la no ocurrencia, de uno o más sucesos futuros inciertos que no están enteramente bajo el control dela entidad; o una obligación presente, surgida a raíz de sucesos pasados, que no se ha reconocido contablemente porque: 
                        <ul style="list-style-type: lower-alpha; padding-left: 2em;">
                            <li>No es probable que para satisfacerla se vaya a requerir una salida de recursos que incorporen beneficios económicos.</li>
                            <li>El importe de La obligación no pueda ser medido con La suficiente fiabilidad.</li>
                        </ul>
                         <h4 class="justificar"><strong>Activos Contingentes</strong></h4>
                        Un activo contingente es un activo de naturaleza posible, surgido a raíz de sucesos pasados, cuya existencia ha de ser confirmada sólo por La ocurrencia, o en su caso por La no ocurrencia, de uno o más eventos inciertos en el futuro, que no están enteramente bajo el control de la entidad.
                        <br>
                        Un activo contingente no es reconocido en los estados financieros, sino que es informado en notas, pero sólo en el caso en que sea probable Ia entrada de beneficios económicos.
                        <br>
                        Para cada tipo de activo contingente a las respectivas fechas de cierre de los períodos sobre los que se informa, la entidad revela 

                        <ol style="list-style-type: lower-alpha; padding-left: 2em;">
                            <li>Una breve descripción de Ia naturaleza del mismo y, cuando fuese posible,</li>
                            <li>una estimación de sus efectos financieros.</li>
                        </ol>
                    </p>
                    <p class="justificar">
                        <h3 class="justificar"><strong>2.19 Reconocimiento de Ingresos</strong></h3>
                        Se miden por el valor razonable, y se registra por las ventas realizadas, netos de descuentos comerciales, 
                        y del impuesto al valor agregado. La compañía reconoce los ingresos cuando el importe de los mismos se puede medir
                        con fiabilidad, sea probable que la compañía reciba los beneficios económicos asociados con la transacción, 
                        los costos incurridos en la transacción, así como los que quedan por incurrir hasta completarla, 
                        puedan ser medidos con fiabilidad y cuando se han cumplido los criterios específicos para cada una de las actividades
                        de la compañía.
                    </p>
                    <p class="justificar">
                        <h3 class="justificar"><strong>2.20 Reconocimientos de Costos y Gastos</strong></h3>
                        Los costos y gastos se reconocen y se llevan a resultados por el sistema de causación.
                        <br>
                        Los costos directos e indirectos necesarios para la comercialización de los bienes  a los cuales se realizan a través contratación con proveedores Nacionales y del Exterior, especializados en cada tipo de bienes o servicios que requiera la compañía.
                        <br>
                        Los gastos operacionales de administración corresponden a los relacionados directamente con la dirección, planeación y organización, para el desarrollo normal de la actividad operativa, incluyendo las operaciones y transacciones de las áreas ejecutiva, financiera, comercial, legal y administrativa.
                    </p>
                </div>
                <br>
            @endif
            @php
                $notaNumero = 2;
                
                if($representantelegal['gruponiif'] == '2'){
                    $agrupaciones = [
                        '3' => ['descripcion' => 'Efectivo y equivalentes al efectivo'],
                        '4' => ['descripcion' => 'Cuentas comerciales y otras cuentas por cobrar'],
                        '5' => ['descripcion' => 'Activos por impuestos corrientes'],
                        '6' => ['descripcion' => 'Otros Pasivos'],
                        '7' => ['descripcion' => 'Pasivos por Impuestos Corrientes'],
                        '8' => ['descripcion' => 'Beneficios a empleados'],
                        '14' => ['descripcion' => 'Cuentas por pagar comerciales y otras cuentas por pagar'],
                        '15' => ['descripcion' => 'Propiedades planta y equipos'],
                    ];
                    $cuentasPorNota = [
                        '3' => [ // Nota 3
                            ['1105'],['1110'],['1115'],['1120'],['1125'],['1145'],
                        ],
                        '4' => [ // Nota 4
                             ['1305'],['1310'],['1315'],['1320'],['1323'],['1325'],['1328'],['1332'],['1335'],['1340'],
                            ['1345'],['1350'],['1360'],['1365'],['1370'],['1380'],['1385'],['1390'],['1399'],
                        ],
                        '5' => [ // Nota 5
                            ['1330'],['18'],['17'],['1730'],['19'],['14']
                        ],
                        '6' => [ // Nota 6
                            ['2820'],['2825'],['2830'],['2835'],['2840'],['2850'],['2855'],['2860'],['2865'],['2870'],['2895'],
                            
                            
                        ],
                        '7' => [ // Nota 7
                            ['2365'],['2367'],['2368'],['2369'],['24'],['2615'],
                        ],
                        '8' => [ // Nota 8
                            ['2370'],['2380'],['25'],['2610'],
                        ],
                        '14' => [ // Nota 9
                           ['2355'],['2357'],['2360'],
                        ],
                        '15' => [ // Nota 15
                            ['1500'],['1501'],['1502'],['1503'],['1504'],['1505'],['1506'],['1507'],['1508'],['1509'],
                            ['1510'],['1511'],['1512'],['1513'],['1514'],['1515'],['1516'],['1517'],['1518'],['1519'],
                            ['1520'],['1521'],['1522'],['1523'],['1524'],['1525'],['1526'],['1527'],['1528'],['1529'],
                            ['1530'],['1531'],['1532'],['1533'],['1534'],['1535'],['1536'],['1537'],['1538'],['1539'],
                            ['1540'],['1541'],['1542'],['1543'],['1544'],['1545'],['1546'],['1547'],['1548'],['1549'],
                            ['1550'],['1551'],['1552'],['1553'],['1554'],['1555'],['1556'],['1557'],['1558'],['1559'],
                            ['1560'],['1561'],['1562'],['1563'],['1564'],['1565'],['1566'],['1567'],['1568'],['1569'],
                            ['1570'],['1571'],['1572'],['1573'],['1574'],['1575'],['1576'],['1577'],['1578'],['1579'],
                            ['1580'],['1581'],['1582'],['1583'],['1584'],['1585'],['1586'],['1587'],['1588'],['1589'],
                            ['1590'],['1591'],['1592'],['1593'],['1594'],['1595'],['1596'],['1597'],['1598'],['1599']
                        ],
                       
                    ];

                }else{
                    $agrupaciones = [
                        '3' => ['descripcion' => 'Efectivo y equivalentes al efectivo'],
                        '4' => ['descripcion' => 'Inversiones'],
                        '5' => ['descripcion' => 'Cuentas comerciales y otras cuentas por cobrar'],
                        '6' => ['descripcion' => 'Inversiones no corriente'],
                        '7' => ['descripcion' => 'Activos por impuestos corrientes'],
                        '8' => ['descripcion' => 'Inventarios'],
                        '9' => ['descripcion' => 'Anticipos y avances'],
                        '10' => ['descripcion' => 'Otros activos'],
                        '11' => ['descripcion' => 'Propiedades planta y equipos'],
                        '12' => ['descripcion' => 'Activos Intangibles'],
                        '13' => ['descripcion' => 'Impuesto diferido'],
                        '14' => ['descripcion' => 'Obligaciones financieras'],
                        '15' => ['descripcion' => 'Cuentas comerciales y otras cuentas por pagar'],
                        '16' => ['descripcion' => 'Pasivos por Impuestos Corrientes'],
                        '17' => ['descripcion' => 'Beneficios a empleados'],
                        '18' => ['descripcion' => 'Anticipos y avances recibidos'],
                        '19' => ['descripcion' => 'Otros Pasivos'],
                        '20' => ['descripcion' => 'Obligaciones Financieras'],
                        '21' => ['descripcion' => 'Cuentas por pagar comerciales y otras cuentas por pagar'],
                        '22' => ['descripcion' => 'Pasivos Contingentes'],
                        '23' => ['descripcion' => 'Pasivo por impuesto diferido'],
                        '40' => ['descripcion' => 'Otros pasivos no corrientes'],
                        '41' => ['descripcion' => 'Bonos y papeles comerciales'],
                        '35' => ['descripcion' => 'Dividendos o participación'],
                        '36' => ['descripcion' => 'Utilidad y/o perdidas del ejercicio'],
                        '24' => ['descripcion' => 'Capital social'],
                        '28' => ['descripcion' => 'Costos'],
                        '29' => ['descripcion' => 'Gastos'],
                        '30' => ['descripcion' => 'Gastos de impuestos de renta y cree'],
                        '301'=> ['descripcion' => 'Hechos posteriores'],
                        '31' => ['descripcion' => 'Nota'],
                        '32' => ['descripcion' => 'Aprobación de los Estados Financieros '],
                        '27' => ['descripcion' => 'Ingresos'],

                    ];
                    $cuentasPorNota = [
                        '3' => [ // Nota 3
                            ['1105'],['1110'],['1115'],['1120'],['1125'],['1145'],
                        ],
                        '4' => [ // Nota 4
                            ['1200'],['1201'],['1202'],['1203'],['1204'],['1206'],['1207'],['1208'],['1209'],
                            ['1210'],['1211'],['1212'],['1213'],['1214'],['1215'],['1216'],['1217'],['1218'],['1219'],
                            ['1220'],['1221'],['1222'],['1223'],['1224'],['1225'],['1226'],['1227'],['1228'],['1229'],
                            ['1230'],['1231'],['1232'],['1233'],['1234'],['1235'],['1236'],['1237'],['1238'],['1239'],
                            ['1240'],['1241'],['1242'],['1243'],['1244'],['1246'],['1247'],['1248'],['1249'],
                            ['1250'],['1251'],['1252'],['1253'],['1254'],['1255'],['1256'],['1257'],['1258'],['1259'],
                            ['1260'],['1261'],['1262'],['1263'],['1264'],['1265'],['1266'],['1267'],['1268'],['1269'],
                            ['1270'],['1271'],['1272'],['1273'],['1274'],['1275'],['1276'],['1277'],['1278'],['1279'],
                            ['1280'],['1281'],['1282'],['1283'],['1284'],['1285'],['1286'],['1287'],['1288'],['1289'],
                            ['1291'],['1292'],['1293'],['1294'],['1296'],['1297'],['1298'],['1299'],
                            ['1205'],['1295'],['1245'],
                        ],
                        '5' => [ // Nota 5
                            ['1305'],['1310'],['1315'],['1320'],['1323'],['1325'],['1328'],['1332'],['1335'],['1340'],
                            ['1345'],['1350'],['1360'],['1365'],['1370'],['1380'],['1385'],['1390'],['1399'],
                        ],
                        '6' => [ // Nota 6
                            ['1290'],
                        ],
                        '7' => [ // Nota 7
                            ['1355'],
                        ],
                        '8' => [ // Nota 8
                            ['1400'],['1401'],['1402'],['1403'],['1404'],['1405'],['1406'],['1407'],['1408'],['1409'],
                            ['1410'],['1411'],['1412'],['1413'],['1414'],['1415'],['1416'],['1417'],['1418'],['1419'],
                            ['1420'],['1421'],['1422'],['1423'],['1424'],['1425'],['1426'],['1427'],['1428'],['1429'],
                            ['1430'],['1431'],['1432'],['1433'],['1434'],['1435'],['1436'],['1437'],['1438'],['1439'],
                            ['1440'],['1441'],['1442'],['1443'],['1444'],['1445'],['1446'],['1447'],['1448'],['1449'],
                            ['1450'],['1451'],['1452'],['1453'],['1454'],['1455'],['1456'],['1457'],['1458'],['1459'],
                            ['1460'],['1461'],['1462'],['1463'],['1464'],['1465'],['1466'],['1467'],['1468'],['1469'],
                            ['1470'],['1471'],['1472'],['1473'],['1474'],['1475'],['1476'],['1477'],['1478'],['1479'],
                            ['1480'],['1481'],['1482'],['1483'],['1484'],['1485'],['1486'],['1487'],['1488'],['1489'],
                            ['1490'],['1491'],['1492'],['1493'],['1494'],['1495'],['1496'],['1497'],['1498'],['1499'],
                        ],
                        '9' => [ // Nota 9
                            ['1330'],
                            
                        ],
                        '10' => [ // Nota 10
                            ['18'],['19'],
                        ],
                        '11' => [ // Nota 11
                            ['1500'],['1501'],['1502'],['1503'],['1504'],['1505'],['1506'],['1507'],['1508'],['1509'],
                            ['1510'],['1511'],['1512'],['1513'],['1514'],['1515'],['1516'],['1517'],['1518'],['1519'],
                            ['1520'],['1521'],['1522'],['1523'],['1524'],['1525'],['1526'],['1527'],['1528'],['1529'],
                            ['1530'],['1531'],['1532'],['1533'],['1534'],['1535'],['1536'],['1537'],['1538'],['1539'],
                            ['1540'],['1541'],['1542'],['1543'],['1544'],['1545'],['1546'],['1547'],['1548'],['1549'],
                            ['1550'],['1551'],['1552'],['1553'],['1554'],['1555'],['1556'],['1557'],['1558'],['1559'],
                            ['1560'],['1561'],['1562'],['1563'],['1564'],['1565'],['1566'],['1567'],['1568'],['1569'],
                            ['1570'],['1571'],['1572'],['1573'],['1574'],['1575'],['1576'],['1577'],['1578'],['1579'],
                            ['1580'],['1581'],['1582'],['1583'],['1584'],['1585'],['1586'],['1587'],['1588'],['1589'],
                            ['1590'],['1591'],['1592'],['1593'],['1594'],['1595'],['1596'],['1597'],['1598'],['1599']
                        ],
                        '12' => [ // Nota 12
                            ['1605'],['1610'],['1615'],['1620'],['1625'],['1630'],['1635'],['1640'],['1645'],['1650'],
                            ['1655'],['1660'],['1665'],['1670'],['1675'],['1680'],['1685'],['1690'],['1695'],['1698'],
                            ['1699']
                        ],
                        '13' => [ // Nota 13
                            ['17'],['1730'],
                        ],
                        '14' => [ // Nota 14
                            ['2101'],['2102'],['2103'],['2104'],['2105'],['2106'],['2107'],['2108'],['2109'],['2110'],
                            ['2111'],['2112'],['2113'],['2114'],['2115'],['2116'],['2117'],['2118'],['2119'],['2120'],
                            ['2121'],['2122'],['2123'],['2124'],['2125'],['2126'],['2127'],['2128'],['2129'],['2130'],
                            ['2131'],['2132'],['2133'],['2134'],['2135'],['2136'],['2137'],['2138'],['2139'],['2140'],
                            ['2141'],['2142'],['2143'],['2144'],['2145'],['2146'],['2147'],['2148'],['2149'],['2150'],
                            ['2151'],['2152'],['2153'],['2154'],['2155'],['2156'],['2157'],['2158'],['2159'],['2160'],
                            ['2161'],['2162'],['2163'],['2164'],['2165'],['2166'],['2167'],['2168'],['2169'],['2170'],
                            ['2171'],['2172'],['2173'],['2174'],['2175'],['2176'],['2177'],['2178'],['2179'],['2180'],
                            ['2181'],['2182'],['2183'],['2184'],['2185'],['2186'],['2187'],['2188'],['2189'],['2190'],
                            ['2191'],['2192'],['2193'],['2194'],['2195'],['2196'],['2197'],['2198'],['2199'],['210517']
                        ],
                        '15' => [ // Nota 15
                            ['22'],['2305'],['2310'],['2315'],['2320'],['2330'],['2335'],['2340'],['2345'],['2350'],
                        ],
                        '16' => [ // Nota 16
                            ['2365'],['2367'],['2368'],['2369'],['24'],['2615'],
                        ],
                        '17' => [ // Nota 17
                            ['2370'],['2380'],['25'],['2610'],
                        ],
                        '18' => [ // Nota 18
                            ['2805'],['2810'],['2815'],
                        ],
                        '19' => [ // Nota 19
                            ['2820'],['2825'],['2830'],['2835'],['2840'],['2850'],['2855'],['2860'],['2865'],['2870'],['2895'],
                        ],
                        '20' => [ // Nota 20
                            ['210517'],
                        ],
                        '21' => [ // Nota 21
                            ['2355'],['2357'],['2360'],
                        ],
                        '22' => [ // Nota 22
                            ['26'],['2610'],['2615'],
                        ],
                        '23' => [ // Nota 23
                            ['27'],
                        ],
                        '40' => [ // Nota 40
                            ['28'],
                        ],
                        '41' => [ // Nota 41
                            ['29'],
                        ],
                        '42'
                        // Agrega las otras notas con sus respectivas cuentas aquí...
                    ];
                }
                
                $detalleNotas = collect();
                // Inicializa una variable para rastrear si ya hemos impreso la tabla para los grupos
                $cuentasCapitalSocial = [
                    'Capital social',
                    'Superavit de capital',
                    'Reservas',
                    'Superavit de Capital Valorizacion',
                ];
                $cuentasUtilidades = [
                    'Utilidad y/o perdidas del ejercicio',
                    'Resultado del ejercicio',
                    'Utilidad y/o perdidas acumuladas',
                    'Ganancias acumuladas - Adopcion por primera vez',
                ];
                
                $agrupacionesOrdenadas = collect($agrupaciones)->sortBy(function ($data, $rango) use ($notasEspecificas) {
                    return $notasEspecificas[$data['descripcion']] ?? PHP_INT_MAX; // Si no tiene número de nota, lo manda al final
                });
            @endphp
            @php
                $descripcionesPositivas = [
                    'Cuentas por pagar',
                    'Pasivos por Impuestos Corrientes',
                    'Beneficios a empleados',
                    'Anticipos y avances recibidos',
                    'Otros Pasivos',
                    'Obligaciones financieras',
                    'Cuentas por pagar comerciales y otras cuentas por pagar',
                    'Pasivos Contingentes',
                    'Pasivo por impuesto diferido',
                    'Otros pasivos no corrientes',
                    'Bonos y papeles comerciales',
                ];
            @endphp

            {{-- Primero imprimimos todas las cuentas individuales que no estén agrupadas hasta la 23 --}}
            @php
                $yaSeAgregoNota5 = false;
            @endphp
            
            @foreach ($agrupacionesOrdenadas as $rango => $data)
                
                @php
                    if ($representantelegal['gruponiif'] == 2 && ($rango == 5 || $rango == 15  || $rango == 14)) {
                        continue; // Saltamos la nota 5 y la 14 y 15 si es grupo niif 2
                    }
                    $descripcionNota = $data['descripcion'];
                    $notaNumeroCorrecto = $notasParaMostrar[$descripcionNota] ?? $notaNumero;
                    $filtradoIndividuales = collect($informedetallado)->filter(function ($item) use ($cuentasCapitalSocial, $cuentasUtilidades) {
                        return !in_array($item['descripcion'], array_merge($cuentasCapitalSocial, $cuentasUtilidades));
                    });
                    $filtradoPorDescripcion = $filtradoIndividuales->filter(function ($item) use ($descripcionNota) {
                        return $item['descripcion'] === $descripcionNota  && $item['descripcion'] != 'Dividendos o participacion';
                    });
                    // Verificar si TODOS los elementos tienen ambos años en "0" o vacíos
                    $todosSonCero = $filtradoPorDescripcion->every(function ($detalle) {
                        $año1 = str_replace([',', '.'], '', trim($detalle['totalaño1'] ?? '0')); // Eliminar comas y puntos
                        $año2 = str_replace([',', '.'], '', trim($detalle['totalaño2'] ?? '0'));

                        return ($año1 === '0' || $año1 === '') && ($año2 === '0' || $año2 === '');
                    });
                @endphp
                @if ($filtradoPorDescripcion->isNotEmpty() && !$filtradoPorDescripcion->every(function ($detalle) {
                    $a1 = floatval(str_replace(',', '', $detalle['totalaño1'] ?? 0));
                    $a2 = floatval(str_replace(',', '', $detalle['totalaño2'] ?? 0));
                    return $a1 == 0 && $a2 == 0;
                }))

                    @php
                        // Buscar la nota correspondiente a la descripción actual
                        $claveNota = collect($agrupaciones)->search(function ($grupo) use ($descripcionNota) {
                            return $grupo['descripcion'] === $descripcionNota;
                        });
                        $subcuentasFiltradas = collect();
                        if (!empty($cuentasPorNota[$claveNota])) {
                            foreach ($cuentasPorNota[$claveNota] as $grupo) {
                                $grupo = (array) $grupo;

                                $subcuentas = $cuentasnotas->filter(function ($item) use ($grupo) {
                                    return in_array($item->cuenta, $grupo);
                                });

                                $subcuentasFiltradas = $subcuentasFiltradas->merge($subcuentas);
                            }

                            $subcuentasFiltradas = $subcuentasFiltradas->unique('cuenta');
                        }
                    @endphp
                    <div class="grid-container">
                        <div class="grid-item titulos">
                            @php
                                $descripcionFinal = $descripcionNota; // Por defecto

                                if ($representantelegal['gruponiif'] == 2 && isset($claveNota)) {
                                    // Usar la descripción de agrupacionesniif2 por clave
                                    $descripcionFinal = $agrupacionesniif2[$claveNota]['descripcion'] ?? $descripcionNota;
                                } elseif (isset($claveNota)) {
                                    // Usar la descripción original de agrupaciones
                                    $descripcionFinal = $agrupaciones[$claveNota]['descripcion'] ?? $descripcionNota;
                                }
                            @endphp

                            <b>NOTA {{ $notaNumeroCorrecto }}: {{ $descripcionFinal }}</b>
                        </div>
                    </div>
                    @if (!$todosSonCero)
                    <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse;" class="custom-border-table">
                        <thead>
                            <tr>
                                <th class="sizenotas"><strong>Partida</strong></th>
                                <th class="sizenotas" style="text-align: right;">{{ $dia_numero . '/' . $mes3 . '/' . $anio }}</th>
                                <th class="sizenotas" style="text-align: right;">{{ $ultimoDiaMes .'/' . 12 . '/' . $anioAnterior }}</th>
                                <th class="sizenotas" style="text-align: right;"><strong>VAR %</strong></th>
                                <th class="sizenotas" style="text-align: right;"><strong>VARIACIÓN $</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- SUBCUENTAS --}}
                            @foreach ($subcuentasFiltradas as $item)
                                @php
                                    $año1 = floatval(str_replace(',', '', $item->saldo_anio_actual ?? 0));
                                    $año2 = floatval(str_replace(',', '', $item->saldo_anio_anterior ?? 0));
                                    $cuentaActual = (string) $item->cuenta;
                                    $notaActual = (string) $claveNota;
                                    // Aplicar abs() si la descripción principal está en las positivas
                                    $forzarPositivo = in_array($descripcionNota, $descripcionesPositivas);
                                    // Verifica si estamos en nota '14' y cuenta 210517, entonces lo forzamos negativo
                                    if ($notaActual === '14' && $cuentaActual === '210517') {
                                        $mostrarA1 = -1 * abs($año1);
                                        $mostrarA2 = -1 * abs($año2);
                                    } else {
                                        $mostrarA1 = $forzarPositivo ? -1 * $año1 : $año1;
                                        $mostrarA2 = $forzarPositivo ? -1 * $año2 : $año2;
                                    }


                                    $variacion = $mostrarA1 - $mostrarA2;
                                    $varRaw = ($mostrarA2 != 0) ? (($mostrarA1 / $mostrarA2) - 1) * 100 : null;
                                    $varFormateado = is_numeric($varRaw)
                                        ? number_format(abs(round($varRaw)), 0, ',', '.')
                                        : '-';
                                @endphp

                                @if ($año1 == 0 && $año2 == 0)
                                    @continue
                                @endif

                                <tr>
                                    <td class="sizenotas">{{ ucfirst(strtolower($item->descripcion)) }}</td>
                                    <td class="sizenotas" style="text-align: right;">
                                        {{ $mostrarA1 == 0 ? '-' : ($mostrarA1 < 0 ? '(' . number_format(abs($mostrarA1), 0, '.', ',') . ')' : number_format($mostrarA1, 0, '.', ',')) }}
                                    </td>
                                    <td class="sizenotas" style="text-align: right;">
                                        {{ $mostrarA2 == 0 ? '-' : ($mostrarA2 < 0 ? '(' . number_format(abs($mostrarA2), 0, '.', ',') . ')' : number_format($mostrarA2, 0, '.', ',')) }}
                                    </td>
                                    <td class="sizenotas" style="text-align: right;">
                                        @if (!is_numeric($varRaw) || in_array($varFormateado, ['0', '0.00']))
                                            -
                                        @elseif ($varRaw < 0)
                                            ({{ $varFormateado }}%)
                                        @else
                                            {{ $varFormateado }}%
                                        @endif
                                    </td>
                                    <td class="sizenotas" style="text-align: right;">
                                        @if ($variacion > 0)
                                            {{ number_format($variacion, 0, '.', ',') }} <span style="color: green;font-family: 'DejaVu Sans', sans-serif;">▲</span>
                                        @elseif ($variacion < 0)
                                            ({{ number_format(abs($variacion), 0, '.', ',') }}) <span style="color: red;font-family: 'DejaVu Sans', sans-serif;">▼</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            @php
                                // Definir las variables que se usarán
                                $total1 = 0;
                                $total2 = 0;
                                $descripcion = '';
                                if ($rango == 5 && $representantelegal['gruponiif'] != 2) {
                                    // Para la nota 5, sumar todas las subcuentas filtradas
                                    $total1 = $subcuentasFiltradas->sum(function ($item) {
                                        return floatval(str_replace(',', '', $item->saldo_anio_actual ?? 0));
                                    });
                                    $total2 = $subcuentasFiltradas->sum(function ($item) {
                                        return floatval(str_replace(',', '', $item->saldo_anio_anterior ?? 0));
                                    });
                                    $descripcion = 'Cuentas comerciales y otras cuentas por cobrar';
                                } else {
                                    // Para otras agrupaciones, se sigue mostrando el total como venías haciéndolo
                                    foreach ($filtradoPorDescripcion as $detalle) {
                                        $total1 = floatval(str_replace(',', '', $detalle['totalaño1'] ?? 0));
                                        $total2 = floatval(str_replace(',', '', $detalle['totalaño2'] ?? 0));
                                        
                                        $reemplazos = [
                                            'Pasivo por impuesto diferido' => 'Diferidos',
                                            'Cuentas por pagar comerciales y otras cuentas por pagar' => 'Cuentas comerciales y otras cuentas por pagar',
                                            // Agrega más reemplazos si necesitas
                                        ];
                                        $descripcion = $reemplazos[$detalle['descripcion']] ?? ucfirst(strtolower($detalle['descripcion']));

                                        // Solo mostramos una fila total para cada descripción
                                        break;
                                    }
                                }
                               

                                $variacionp = abs($total1) - abs($total2);
                                $varRaw = ($total2 != 0) ? (($total1 / $total2) - 1) * 100 : null;
                                $varFormateado = is_numeric($varRaw)
                                    ? number_format(abs(round($varRaw)), 0, ',', '.')
                                    : '-';
                            @endphp
                            <tr>
                                <td class="sizenotas" style="font-weight: bold !important">
                                    Total {{ $descripcion }}
                                </td>
                                <td class="sizenotas" style="font-weight: bold !important; text-align: right;">
                                    @if ($total1 == 0)
                                        -
                                    @elseif (in_array($descripcionNota, $descripcionesPositivas))
                                        {{ '$' . number_format(abs($total1), 0, '.', ',') }}
                                    @else
                                        {{ $total1 < 0 ? '(' . number_format(abs($total1), 0, '.', ',') . ')' : number_format($total1, 0, '.', ',') }}
                                    @endif
                                </td>
                                <td class="sizenotas" style="font-weight: bold !important; text-align: right;">
                                    @if ($total2 == 0)
                                        -
                                    @elseif (in_array($descripcionNota, $descripcionesPositivas))
                                        {{ '$' . number_format(abs($total2), 0, '.', ',') }}
                                    @else
                                        {{ $total2 < 0 ? '(' . number_format(abs($total2), 0, '.', ',') . ')' : number_format($total2, 0, '.', ',') }}
                                    @endif
                                </td>
                                <td class="sizenotas" style="font-weight: bold !important;text-align: right;">
                                    @if (!is_numeric($varRaw) || in_array($varFormateado, ['0', '0.00']))
                                        -
                                    @elseif ($varRaw < 0)
                                        ({{ $varFormateado }}%)
                                    @else
                                        {{ $varFormateado }}%
                                    @endif
                                </td>
                                <td class="sizenotas" style="font-weight: bold !important;text-align: right;">
                                    @if ($variacionp > 0)
                                        {{ number_format($variacionp, 0, '.', ',') }}<span style="color: green;font-family: 'DejaVu Sans', sans-serif;">▲</span>
                                    @elseif ($variacionp < 0)
                                        ({{ number_format(abs($variacionp), 0, '.', ',') }}) <span style="color: red; font-family: 'DejaVu Sans', sans-serif;">▼</span>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            
                        </tbody>
                    </table>
                    @endif
                    @if($representantelegal['gruponiif'] == 2)
                        
                        @php
                            // Plantilla cruda MENSAJE DE LAS NOTAS
                            $plantilla = $mensajes[$claveNota] ?? '';

                            // Datos que necesitas inyectar
                            $valorCuenta = $filtradoPorDescripcion->sum(function ($item) {
                                return abs((int)str_replace(',', '', $item['totalaño1'] ?? '0'));
                            });
                            $fechaActual   =  $dia_numero .' de '. $mes2  . ' del ' . $anio;
                            $empresa       = $representantelegal['razon_social'];
                            $anioActual    = $anio;

                            // Sustituciones
                            $reemplazos = [
                                '{SALTO_PAGINA}'  => '<br>',
                                '{VALOR_CUENTA}'  => '$'.number_format($valorCuenta, 0, ',', '.'),
                                '{FECHA_ACTUAL}'  => $fechaActual,
                                '{EMPRESA}'       => $empresa,
                                '{ANIO}'          => $anioActual,
                            ];

                            // Compilar mensaje
                            $mensajeCompilado = strtr($plantilla, $reemplazos);
                            // Poner en negrita todas las palabras completamente en mayúscula (mínimo 2 letras para evitar "Y", "A", etc.)
                            $mensajeCompilado = preg_replace_callback('/\b([A-ZÁÉÍÓÚÑ]{2,})\b/u', function ($matches) {
                                return '<strong>' . $matches[1] . '</strong>';
                            }, $mensajeCompilado);
                        @endphp
                    
                        @if ($todosSonCero)
                            <p>La empresa no cuenta con este rubro</p>
                        @else
                            {{-- Usa {!! !!} para que Blade no escape el HTML de SALTO_PAGINA --}}
                            <p>{!! $mensajeCompilado !!}</p>
                        @endif
                    @else
                        <p>{{ $todosSonCero ? 'La empresa no cuenta con este rubro' : ($mensajes[$rango] ?? '') }}</p>

                    @endif
                  

                @endif
                @php
                    $nota5YaExiste = collect($informedetallado)->contains(function ($item) {
                        return strtoupper(trim($item['descripcion'])) === 'Activos por impuestos corrientes'
                            && floatval(str_replace([',', '.'], ['', '.'], str_replace('.', '', $item['totalaño1'] ?? 0))) != 0;
                    });
                @endphp
                 @if ($rango == 4 && $representantelegal['gruponiif'] == 2)
                    @php
                        // Array de todas las cuentas que quieres mostrar
                        $subcuentasParaTabla = [
                            'Activos por impuestos corrientes',
                            'Anticipos y avances',
                            'Inventarios',
                            // Agrega aquí las que necesites
                        ];

                        // Filtrar las cuentas del informe
                        $subcuentasFiltradas = collect($informedetallado)->filter(function ($item) use ($subcuentasParaTabla) {
                            return in_array($item['descripcion'], $subcuentasParaTabla);
                        });

                        // Totales
                        $totalA1 = $subcuentasFiltradas->sum(function ($item) {
                            return floatval(str_replace([',', '.'], ['', '.'], str_replace('.', '', $item['totalaño1'] ?? 0)));
                        });
                        $totalA2 = $subcuentasFiltradas->sum(function ($item) {
                            return floatval(str_replace([',', '.'], ['', '.'], str_replace('.', '', $item['totalaño2'] ?? 0)));
                        });

                        $variacionTotal = $totalA1 - $totalA2;
                        $varRawTotal = ($totalA2 != 0) ? (($totalA1 / $totalA2) - 1) * 100 : null;
                        $varFormateadoTotal = is_numeric($varRawTotal) ? number_format(abs(round($varRawTotal)), 0, ',', '.') : '-';
                    @endphp

                    <div class="grid-container">
                        <div class="grid-item titulos">
                            <b>NOTA 5: ACTIVOS POR IMPUESTOS CORRIENTES</b>
                        </div>
                    </div>

                    <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse;" class="custom-border-table">
                        <thead>
                            <tr>
                                <th class="sizenotas"><strong>Partida</strong></th>
                                <th class="sizenotas" style="text-align: right;">{{ $dia_numero . '/' . $mes3 . '/' . $anio }}</th>
                                <th class="sizenotas" style="text-align: right;">{{ $ultimoDiaMes .'/' . 12 . '/' . $anioAnterior }}</th>
                                <th class="sizenotas" style="text-align: right;"><strong>VAR %</strong></th>
                                <th class="sizenotas" style="text-align: right;"><strong>VARIACIÓN $</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subcuentasFiltradas as $detalle)
                                @php
                                    $a1 = floatval(str_replace([',', '.'], ['', '.'], str_replace('.', '', $detalle['totalaño1'] ?? 0)));
                                    $a2 = floatval(str_replace([',', '.'], ['', '.'], str_replace('.', '', $detalle['totalaño2'] ?? 0)));
                                    $variacion = $a1 - $a2;
                                    $varRaw = ($a2 != 0) ? (($a1 / $a2) - 1) * 100 : null;
                                    $varFormateado = is_numeric($varRaw) ? number_format(abs(round($varRaw)), 0, ',', '.') : '-';
                                @endphp
                                <tr>
                                    <td class="sizenotas">{{ ucfirst(strtolower($detalle['descripcion'])) }}</td>
                                    <td class="sizenotas" style="text-align: right;">
                                        {{ $a1 == 0 ? '-' : ($a1 < 0 ? '(' . number_format(abs($a1), 0, '.', ',') . ')' : number_format($a1, 0, '.', ',')) }}
                                    </td>
                                    <td class="sizenotas" style="text-align: right;">
                                        {{ $a2 == 0 ? '-' : ($a2 < 0 ? '(' . number_format(abs($a2), 0, '.', ',') . ')' : number_format($a2, 0, '.', ',')) }}
                                    </td>
                                    <td class="sizenotas" style="text-align: right;">
                                        @if (!is_numeric($varRaw) || in_array($varFormateado, ['0', '0.00']))
                                            -
                                        @elseif ($varRaw < 0)
                                            ({{ $varFormateado }}%)
                                        @else
                                            {{ $varFormateado }}%
                                        @endif
                                    </td>
                                    <td class="sizenotas" style="text-align: right;">
                                        @if ($variacion > 0)
                                            {{ number_format($variacion, 0, '.', ',') }} <span style="color: green;font-family: 'DejaVu Sans', sans-serif;">▲</span>
                                        @elseif ($variacion < 0)
                                            ({{ number_format(abs($variacion), 0, '.', ',') }}) <span style="color: red;font-family: 'DejaVu Sans', sans-serif;">▼</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                            {{-- Fila Total --}}
                            <tr>
                                <td class="sizenotas" style="font-weight: bold !important">Total Activos por impuestos corrientes</td>
                                <td class="sizenotas" style="text-align: right; font-weight: bold !important">
                                    {{ $totalA1 == 0 ? '-' : number_format($totalA1, 0, '.', ',') }}
                                </td>
                                <td class="sizenotas" style="text-align: right; font-weight: bold !important">
                                    {{ $totalA2 == 0 ? '-' : number_format($totalA2, 0, '.', ',') }}
                                </td>
                                <td class="sizenotas" style="text-align: right; font-weight: bold !important">
                                    @if (!is_numeric($varRawTotal) || in_array($varFormateadoTotal, ['0', '0.00']))
                                        -
                                    @elseif ($varRawTotal < 0)
                                        ({{ $varFormateadoTotal }}%)
                                    @else
                                        {{ $varFormateadoTotal }}%
                                    @endif
                                </td>
                                <td class="sizenotas" style="text-align: right; font-weight: bold !important">
                                    @if ($variacionTotal > 0)
                                        {{ number_format($variacionTotal, 0, '.', ',') }} <span style="color: green;font-family: 'DejaVu Sans', sans-serif;">▲</span>
                                    @elseif ($variacionTotal < 0)
                                        ({{ number_format(abs($variacionTotal), 0, '.', ',') }}) <span style="color: red;font-family: 'DejaVu Sans', sans-serif;">▼</span>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    @if($representantelegal['gruponiif'] == 2)
                        
                        @php
                            // Plantilla cruda MENSAJE DE LAS NOTAS
                            $plantilla = $mensajes[5] ?? '';

                            // Datos que necesitas inyectar
                            $valorCuenta = $filtradoPorDescripcion->sum(function ($item) {
                                return abs((int)str_replace(',', '', $item['totalaño1'] ?? '0'));
                            });
                            $fechaActual   =  $dia_numero .' de '. $mes2  . ' del ' . $anio;
                            $empresa       = $representantelegal['razon_social'];
                            $anioActual    = $anio;

                            // Sustituciones
                            $reemplazos = [
                                '{SALTO_PAGINA}'  => '<br>',
                                '{VALOR_CUENTA}'  => '$'.number_format($valorCuenta, 0, ',', '.'),
                                '{FECHA_ACTUAL}'  => $fechaActual,
                                '{EMPRESA}'       => $empresa,
                                '{ANIO}'          => $anioActual,
                            ];

                            // Compilar mensaje
                            $mensajeCompilado = strtr($plantilla, $reemplazos);
                            $yaSeAgregoNota5 = true;
                            // Poner en negrita todas las palabras completamente en mayúscula (mínimo 2 letras para evitar "Y", "A", etc.)
                            $mensajeCompilado = preg_replace_callback('/\b([A-ZÁÉÍÓÚÑ]{2,})\b/u', function ($matches) {
                                return '<strong>' . $matches[1] . '</strong>';
                            }, $mensajeCompilado);
                        @endphp
                    
                        @if ($todosSonCero)
                            <p>La empresa no cuenta con este rubro</p>
                        @else
                            {{-- Usa {!! !!} para que Blade no escape el HTML de SALTO_PAGINA --}}
                            <p>{!! $mensajeCompilado !!}</p>
                        @endif
                    @endif
                @endif

                @if ($yaSeAgregoNota5 && $representantelegal['gruponiif'] == 2)
                
                    @php
                        // Array de todas las cuentas que quieres mostrar
                        $subcuentasParaTabla = [
                            'Propiedades planta y equipos',
                            // Agrega aquí las que necesites
                        ];

                        // Filtrar las cuentas del informe
                        $subcuentasFiltradas = collect($informedetallado)->filter(function ($item) use ($subcuentasParaTabla) {
                            return in_array($item['descripcion'], $subcuentasParaTabla);
                        });

                        // Totales
                        $totalA1 = $subcuentasFiltradas->sum(function ($item) {
                            return floatval(str_replace([',', '.'], ['', '.'], str_replace('.', '', $item['totalaño1'] ?? 0)));
                        });
                        $totalA2 = $subcuentasFiltradas->sum(function ($item) {
                            return floatval(str_replace([',', '.'], ['', '.'], str_replace('.', '', $item['totalaño2'] ?? 0)));
                        });

                        $variacionTotal = $totalA1 - $totalA2;
                        $varRawTotal = ($totalA2 != 0) ? (($totalA1 / $totalA2) - 1) * 100 : null;
                        $varFormateadoTotal = is_numeric($varRawTotal) ? number_format(abs(round($varRawTotal)), 0, ',', '.') : '-';
                    @endphp

                    <div class="grid-container">
                        <div class="grid-item titulos">
                            <b>NOTA 6: PROPIEDADES PLANTAS Y EQUIPOS</b>
                        </div>
                    </div>

                    <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse;" class="custom-border-table">
                        <thead>
                            <tr>
                                <th class="sizenotas"><strong>Partida</strong></th>
                                <th class="sizenotas" style="text-align: right;">{{ $dia_numero . '/' . $mes3 . '/' . $anio }}</th>
                                <th class="sizenotas" style="text-align: right;">{{ $ultimoDiaMes .'/' . 12 . '/' . $anioAnterior }}</th>
                                <th class="sizenotas" style="text-align: right;"><strong>VAR %</strong></th>
                                <th class="sizenotas" style="text-align: right;"><strong>VARIACIÓN $</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subcuentasFiltradas as $detalle)
                                @php
                                    $a1 = floatval(str_replace([',', '.'], ['', '.'], str_replace('.', '', $detalle['totalaño1'] ?? 0)));
                                    $a2 = floatval(str_replace([',', '.'], ['', '.'], str_replace('.', '', $detalle['totalaño2'] ?? 0)));
                                    $variacion = $a1 - $a2;
                                    $varRaw = ($a2 != 0) ? (($a1 / $a2) - 1) * 100 : null;
                                    $varFormateado = is_numeric($varRaw) ? number_format(abs(round($varRaw)), 0, ',', '.') : '-';
                                @endphp
                                <tr>
                                    <td class="sizenotas">{{ ucfirst(strtolower($detalle['descripcion'])) }}</td>
                                    <td class="sizenotas" style="text-align: right;">
                                        {{ $a1 == 0 ? '-' : ($a1 < 0 ? '(' . number_format(abs($a1), 0, '.', ',') . ')' : number_format($a1, 0, '.', ',')) }}
                                    </td>
                                    <td class="sizenotas" style="text-align: right;">
                                        {{ $a2 == 0 ? '-' : ($a2 < 0 ? '(' . number_format(abs($a2), 0, '.', ',') . ')' : number_format($a2, 0, '.', ',')) }}
                                    </td>
                                    <td class="sizenotas" style="text-align: right;">
                                        @if (!is_numeric($varRaw) || in_array($varFormateado, ['0', '0.00']))
                                            -
                                        @elseif ($varRaw < 0)
                                            ({{ $varFormateado }}%)
                                        @else
                                            {{ $varFormateado }}%
                                        @endif
                                    </td>
                                    <td class="sizenotas" style="text-align: right;">
                                        @if ($variacion > 0)
                                            {{ number_format($variacion, 0, '.', ',') }} <span style="color: green;font-family: 'DejaVu Sans', sans-serif;">▲</span>
                                        @elseif ($variacion < 0)
                                            ({{ number_format(abs($variacion), 0, '.', ',') }}) <span style="color: red;font-family: 'DejaVu Sans', sans-serif;">▼</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                            {{-- Fila Total --}}
                            <tr>
                                <td class="sizenotas" style="font-weight: bold !important">Total Propiedades plata y equipos</td>
                                <td class="sizenotas" style="text-align: right; font-weight: bold !important">
                                    {{ $totalA1 == 0 ? '-' : number_format($totalA1, 0, '.', ',') }}
                                </td>
                                <td class="sizenotas" style="text-align: right; font-weight: bold !important">
                                    {{ $totalA2 == 0 ? '-' : number_format($totalA2, 0, '.', ',') }}
                                </td>
                                <td class="sizenotas" style="text-align: right; font-weight: bold !important">
                                    @if (!is_numeric($varRawTotal) || in_array($varFormateadoTotal, ['0', '0.00']))
                                        -
                                    @elseif ($varRawTotal < 0)
                                        ({{ $varFormateadoTotal }}%)
                                    @else
                                        {{ $varFormateadoTotal }}%
                                    @endif
                                </td>
                                <td class="sizenotas" style="text-align: right; font-weight: bold !important">
                                    @if ($variacionTotal > 0)
                                        {{ number_format($variacionTotal, 0, '.', ',') }} <span style="color: green;font-family: 'DejaVu Sans', sans-serif;">▲</span>
                                    @elseif ($variacionTotal < 0)
                                        ({{ number_format(abs($variacionTotal), 0, '.', ',') }}) <span style="color: red;font-family: 'DejaVu Sans', sans-serif;">▼</span>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                      @php
                            // Plantilla cruda MENSAJE DE LAS NOTAS
                            $plantilla = $mensajes[15] ?? '';

                            // Datos que necesitas inyectar
                            $valorCuenta = $filtradoPorDescripcion->sum(function ($item) {
                                return abs((int)str_replace(',', '', $item['totalaño1'] ?? '0'));
                            });
                            $fechaActual   =  $dia_numero .' de '. $mes2  . ' del ' . $anio;
                            $empresa       = $representantelegal['razon_social'];
                            $anioActual    = $anio;

                            // Sustituciones
                            $reemplazos = [
                                '{SALTO_PAGINA}'  => '<br>',
                                '{VALOR_CUENTA}'  => '$'.number_format($valorCuenta, 0, ',', '.'),
                                '{FECHA_ACTUAL}'  => $fechaActual,
                                '{EMPRESA}'       => $empresa,
                                '{ANIO}'          => $anioActual,
                            ];

                            // Compilar mensaje
                            $mensajeCompilado = strtr($plantilla, $reemplazos);
                            $yaSeAgregoNota5 = false;
                            // Poner en negrita todas las palabras completamente en mayúscula (mínimo 2 letras para evitar "Y", "A", etc.)
                            $mensajeCompilado = preg_replace_callback('/\b([A-ZÁÉÍÓÚÑ]{2,})\b/u', function ($matches) {
                                return '<strong>' . $matches[1] . '</strong>';
                            }, $mensajeCompilado);
                        @endphp
                    
                        @if ($todosSonCero)
                            <p>La empresa no cuenta con este rubro</p>
                        @else
                            {{-- Usa {!! !!} para que Blade no escape el HTML de SALTO_PAGINA --}}
                            <p>{!! $mensajeCompilado !!}</p>
                        @endif
                @endif

           
            @endforeach
           
            {{-- Agrupaciónes individuales --}}
            @if ($representantelegal['gruponiif'] == 3)
                {{-- Agrupación para "Capital Social" (una única tabla para todas las cuentas) --}}
                @php
                    $filtradoCapitalSocial = collect($informedetallado)->filter(function ($item) use ($cuentasCapitalSocial) {
                        return in_array($item['descripcion'], $cuentasCapitalSocial);
                    });

                    // Convertir los valores a numéricos antes de sumarlos
                    $totalAño1Capital = $filtradoCapitalSocial->sum(function ($item) {
                        return abs((int)str_replace(',', '', $item['totalaño1']));
                    });

                    $totalAño2Capital = $filtradoCapitalSocial->sum(function ($item) {
                        return abs((int)str_replace(',', '', $item['totalaño2']));
                    });
                    // Obtener el número de nota correspondiente
                    $notaCapitalSocial = $notasParaMostrar['Capital social'] ?? $notaNumero;
                    $sinCapitalSocial = ($totalAño1Capital == 0 && $totalAño2Capital == 0);
                @endphp

                @if ($filtradoCapitalSocial->isNotEmpty())
                    <div class="grid-container">
                        <div class="grid-item titulos"><b>NOTA {{ $notaCapitalSocial}}: Capital Social</b></div>
                    </div>
                    <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse;"
                        class="custom-border-table">
                        <thead>
                            <tr>
                                <th class="sizenotas"><strong>Partida</strong></th>
                                <th class="sizenotas" style="text-align: right;">{{ $dia_numero . '/' . $mes3 . '/' . $anio }}</th>
                                <th class="sizenotas" style="text-align: right;">{{ 31 . '/' . 12 . '/' . $anioAnterior }}</th>
                                <th class="sizenotas" style="text-align: right;"><strong>VAR %</strong></th>
                                <th class="sizenotas" style="text-align: right;"><strong>VARIACIÓN $</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($filtradoCapitalSocial as $detalle)
                                @php
                                    $valor1 = isset($detalle['totalaño1']) ? preg_replace('/[^\d\-]/', '', $detalle['totalaño1']) : '0';
                                    $valor2 = isset($detalle['totalaño2']) ? preg_replace('/[^\d\-]/', '', $detalle['totalaño2']) : '0';
                                @endphp
                                {{-- Omitir si ambos años son 0 --}}
                                @if ($valor1 == 0 && $valor2 == 0)
                                    @continue
                                @endif
                                <tr>
                                    <td class="sizenotas">{{ $detalle['descripcion'] }}</td>
                                    <td class="sizenotas" style="text-align: right;">
                                        {{ $valor1 < 0 ? '('.number_format(abs((int)$valor1)).')' : number_format(abs((int)$valor1)) }}
                                    </td>
                                    <td class="sizenotas" style="text-align: right;">
                                        {{ $valor2 < 0 ? '('.number_format(abs((int)$valor2)).')' : number_format(abs((int)$valor2)) }}
                                    </td>

                                    <td class="sizenotas" style="text-align: right;">
                                        @php
                                            $varRaw = trim($detalle['VAR']);
                                            $varNumeric = (float) str_replace(['%', ','], '', $varRaw);
                                            $varRedondeado = is_numeric($varNumeric)
                                            ? number_format(round($varNumeric), 0, ',', '.') 
                                            : '-';
                                        @endphp

                                        @if (in_array($varRedondeado, [0, 0.00]))
                                            -
                                        @elseif ($varRedondeado < 0)
                                            ({{ abs($varRedondeado) }}%)
                                        @else
                                            {{ $varRedondeado }}%
                                        @endif

                                    </td>
                                    @php
                                    // Eliminar todas las comas
                                                $clean1 = floatval(str_replace(',', '', $detalle['totalaño1']));
                                                $clean2 = floatval(str_replace(',', '', $detalle['totalaño2']));

                                                $variacion = abs($clean1) - abs($clean2);
                                    @endphp
                                    <td class="sizenotas" style="text-align: right">
                                    
                                        @if ($variacion > 0)
                                            {{ number_format($variacion, 0, ',', '.')}}
                                            <span style="color: green; font-family: 'DejaVu Sans', sans-serif;">▲</span>
                                        @elseif ($variacion < 0)
                                            ({{ ltrim(number_format($variacion, 0, ',', '.'), '-') }})
                                            <span style="color: red; font-family: 'DejaVu Sans', sans-serif;">▼</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            {{-- Total para Capital Social --}}
                            <tr>
                                @php
                                    $variacionValor = abs($totalAño1Capital) - abs($totalAño2Capital);
                                    // Evitar división por cero
                                    if ($totalAño1Capital == 0 || $totalAño2Capital == 0) {
                                        $variacionPorcentaje = '0%';
                                    } else {
                                        $variacionPorcentaje = round((($totalAño1Capital / $totalAño2Capital) - 1) * 100) . '%';
                                    }

                                    $variacionValorFormateado = number_format($variacionValor, 0, ',', '.');
                                @endphp
                                <td class="sizenotas"><strong>Total Capital Social</strong></td>
                                <td class="sizenotas" style="text-align: right"><strong>${{ number_format(abs($totalAño1Capital), 0, ',', '.') }}</strong></td> <!-- Formato: miles con punto y dos decimales -->
                                <td class="sizenotas" style="text-align: right"><strong>${{ number_format(abs($totalAño2Capital), 0, ',', '.') }}</strong></td> <!-- Formato: miles con punto y dos decimales -->
                                <td class="sizenotas" style="text-align: right"><strong>
                                    @php
                                        $varRaw = trim($variacionPorcentaje);
                                        $varNumeric = (float)str_replace(['%', ','], '', $varRaw);
                                    @endphp

                                    @if (in_array($varRaw, ['0', '0.00', '0.00%','0%']))
                                        -
                                    @elseif ($varNumeric < 0)
                                        ({{ ltrim($varRaw, '-') }})
                                    @else
                                        {{ $varRaw }}%
                                    @endif
                                </strong></td>
                                @php
                                    $variacion = (float)str_replace(',', '', $variacionValorFormateado);
                                @endphp
                                <td class="sizenotas"  style="text-align: right">
                                    <strong>
                                    @if ($variacion > 0)
                                        ${{ $variacionValorFormateado }}
                                        <span style="color: green; font-family: 'DejaVu Sans', sans-serif;">▲</span>
                                    @elseif ($variacion < 0)
                                        (${{ ltrim($variacionValorFormateado, '-') }})
                                        <span style="color: red; font-family: 'DejaVu Sans', sans-serif;">▼</span>
                                    @else
                                        -
                                    @endif
                                    </strong>
                                </td>
                            
                            </tr>
                        </tbody>
                    </table>
                    <p>{{ $sinCapitalSocial ? 'La empresa no cuenta con este rubro' : ($mensajes['24'] ?? '') }}</p> 
                    <br>
                    @php
                        $notaNumero++;
                    @endphp
                @endif

                {{-- Agrupación para "Utilidades y Pérdidas" (una única tabla para todas las cuentas) --}}
                @php
                    $filtradoUtilidades = collect($informedetallado)->filter(function ($item) use ($cuentasUtilidades) {
                        return in_array($item['descripcion'], $cuentasUtilidades);
                    });

                    // Obtener el número de nota específico
                    $numeroNotaUtilidades = $notasParaMostrar['Utilidad y/o perdidas del ejercicio'] ?? $notaNumero;

                    // Convertir valores no numéricos a 0, eliminar comas antes de sumar, y ajustar los signos según la descripción
                    $totalAño1Utilidades = $filtradoUtilidades->sum(function ($item) {
                        $valor = (int)str_replace(',', '', $item['totalaño1'] ?? 0);
                        return $item['descripcion'] === 'Utilidad y/o perdidas acumuladas' ? abs(-$valor) : abs($valor);
                    });

                    $totalAño2Utilidades = $filtradoUtilidades->sum(function ($item) {
                        $valor = (int)str_replace(',', '', $item['totalaño2'] ?? 0);
                        return $item['descripcion'] === 'Utilidad y/o perdidas acumuladas' ? abs(-$valor) : abs($valor);
                    });

                    // Verificar si ambos totales son 0 o están vacíos
                    $sinutilidad = ($totalAño1Utilidades == 0 && $totalAño2Utilidades == 0);
                @endphp

                @if ($filtradoUtilidades->isNotEmpty())
                    <div class="grid-container">
                        <div class="grid-item titulos"><b>NOTA {{ $numeroNotaUtilidades }}: Utilidades y Pérdidas</b></div>
                    </div>
                    <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse;"
                        class="custom-border-table">
                        <thead>
                            <tr>
                                <th class="sizenotas"><strong>Partida</strong></th>
                                <th class="sizenotas" style="text-align: right;">{{ $dia_numero . '/' . $mes3 . '/' . $anio }}</th>
                                <th class="sizenotas" style="text-align: right;">{{ 31 . '/' . 12 . '/' . $anioAnterior }}</th>
                                <th class="sizenotas" style="text-align: right;"><strong>VAR %</strong></th>
                                <th class="sizenotas" style="text-align: right;"><strong>VARIACIÓN $</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($filtradoUtilidades as $detalle)
                                @php
                                    // Procesar cada valor individualmente
                                    $totalaño1 = (int)str_replace(',', '', $detalle['totalaño1'] ?? 0);
                                    $totalaño2 = (int)str_replace(',', '', $detalle['totalaño2'] ?? 0);
                                    // Si la descripción es "Utilidad y/o perdidas acumuladas", invertir el signo
                                    if ($detalle['descripcion'] === 'Utilidad y/o perdidas acumuladas') {
                                        $totalaño1 *= -1;
                                        $totalaño2 *= -1;
                                    }

                                    
                                @endphp
                                {{-- Omitir si ambos años son 0 --}}
                                @if ($totalaño1 == 0 && $totalaño2 == 0)
                                    @continue
                                @endif
                                <tr>
                                    <td class="sizenotas">{{ $detalle['descripcion'] }}</td>
                                    <td class="sizenotas" style="text-align: right">
                                        {{ ($totalaño1 ?? 0) == 0 ? '-' : (($totalaño1 < 0) ? '($' . number_format(ltrim($totalaño1, '-'), 0, ',', '.') . ')' : '$' . number_format($totalaño1, 0, ',', '.')) }}
                                    </td>
                                    <td class="sizenotas" style="text-align: right">
                                        {{ ($totalaño2 ?? 0) == 0 ? '-' : (($totalaño2 < 0) ? '($' . number_format(ltrim($totalaño2, '-'), 0, ',', '.') . ')' : '$' . number_format($totalaño2, 0, ',', '.')) }}
                                    </td>

                                    <td class="sizenotas" style="text-align: right">
                                        @php
                                            $varRaw = trim($detalle['VAR']);
                                            $varNumeric = (float) str_replace(['%', ','], '', $varRaw);
                                            $varRedondeado = is_numeric($varNumeric)
                                            ? number_format(round($varNumeric), 0, ',', '.') 
                                            : '-';
                                        @endphp

                                        @if (in_array($varRedondeado, [0, 0.00]))
                                            -
                                        @elseif ($varRedondeado < 0)
                                            ({{ abs($varRedondeado) }}%)
                                        @else
                                            {{ $varRedondeado }}%
                                        @endif
                                    </td>
                                    @php
                                        // Eliminar todas las comas
                                        $clean1 = floatval(str_replace(',', '', $totalaño1));
                                        $clean2 = floatval(str_replace(',', '', $totalaño2));

                                        $variacion = abs($clean1) - abs($clean2);
                                @endphp
                                <td class="sizenotas" style="text-align: right">
                                    
                                    @if ($variacion > 0)
                                    {{ number_format($variacion, 0, ',', '.') }}
                                        <span style="color: green; font-family: 'DejaVu Sans', sans-serif;">▲</span>
                                    @elseif ($variacion < 0)
                                        ({{ ltrim(number_format($variacion, 0, ',', '.'), '-') }})
                                        <span style="color: red; font-family: 'DejaVu Sans', sans-serif;">▼</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                    <p>{{ $sinutilidad ? 'La empresa no cuenta con este rubro' : ($mensajes['36'] ?? '') }}</p> 
                @endif

                {{-- continuar con la nota 26 --}}
                @php
                    $descripcionNota = 'Dividendos o participacion';
                    $notaNumeroCorrecto = $notasParaMostrar['Dividendos o participacion'] ?? $notaNumero;
                    $filtradoIndividuales2 = collect($informedetallado)->filter(function ($item) use ($descripcionNota) {
                        return $item['descripcion'] === $descripcionNota;
                    });
                
                    // Verificar si TODOS los elementos tienen ambos años en "0" o vacíos
                    $todosSonCero2 = $filtradoIndividuales2->every(function ($detalle) {
                        $año1 = str_replace([',', '.'], '', trim($detalle['totalaño1'] ?? '0')); // Eliminar comas y puntos
                        $año2 = str_replace([',', '.'], '', trim($detalle['totalaño2'] ?? '0'));
                
                        return ($año1 === '0' || $año1 === '') && ($año2 === '0' || $año2 === '');
                    });
                @endphp
                @if (!$todosSonCero)
                    @if ($filtradoIndividuales2->isNotEmpty())
                        <div class="grid-container">
                            <div class="grid-item titulos"><b>NOTA {{ $notaNumeroCorrecto }}: {{ str_replace('Pasivo por impuesto diferido', 'Diferidos', $descripcionNota) }}</b></div>
                        </div>
                            <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse;" class="custom-border-table">
                                <thead>
                                    <tr>
                                        <th class="sizenotas"><strong>Partida</strong></th>
                                        <th class="sizenotas" style="text-align: right;">{{ $dia_numero . '/' . $mes3 . '/' . $anio }}</th>
                                        <th class="sizenotas" style="text-align: right;">{{ 31 . '/' . 12 . '/' . $anioAnterior }}</th>
                                        <th class="sizenotas" style="text-align: right;"><strong>VAR %</strong></th>
                                        <th class="sizenotas" style="text-align: right;"><strong>VARIACIÓN $</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($filtradoIndividuales2 as $detalle)
                                        <tr>
                                            <td class="sizenotas">{{ str_replace('Pasivo por impuesto diferido', 'Diferidos', $detalle['descripcion']) }}</td>
                                            <td class="sizenotas" style="text-align: right;">
                                                @php
                                                    $valor1 = str_replace('.', '', $detalle['totalaño1'] ?? '0');
                                                @endphp
                                                {{ (float)$valor1 == 0 ? '-' : ((float)$valor1 < 0 ? '($' . ltrim($detalle['totalaño1'], '-') . ')' : '$' . $detalle['totalaño1']) }}
                                            </td>

                                            <td class="sizenotas" style="text-align: right;">
                                                @php
                                                    $valor2 = str_replace('.', '', $detalle['totalaño2'] ?? '0');
                                                @endphp
                                                {{ (float)$valor2 == 0 ? '-' : ((float)$valor2 < 0 ? '($' . ltrim($detalle['totalaño2'], '-') . ')' : '$' . $detalle['totalaño2']) }}
                                            </td>

                                            <td class="sizenotas" style="text-align: right;">
                                                    @php
                                                        $varRaw = trim($detalle['VAR']);
                                                        $varNumeric = (float) str_replace(['%', ','], '', $varRaw);
                                                        $varRedondeado = is_numeric($varNumeric)
                                                            ? number_format(round($varNumeric), 0, ',', '.') 
                                                            : '-';
                                                    @endphp

                                                    @if (in_array($varRedondeado, [0, 0.00]))
                                                        -
                                                    @elseif ($varRedondeado < 0)
                                                        ({{ abs($varRedondeado) }}%)
                                                    @else
                                                        {{ $varRedondeado }}%
                                                    @endif
                                            </td>
                                            @php
                                                // Eliminar todas las comas
                                                $clean1 = floatval(str_replace(',', '', $detalle['totalaño1']));
                                                $clean2 = floatval(str_replace(',', '', $detalle['totalaño2']));
                                                $variacion = abs($clean1) - abs($clean2);
                                            @endphp
                                            <td class="sizenotas" style="text-align: right;">
                                                    @if ($variacion > 0)
                                                        {{ number_format($variacion, 0, ',', '.')}}
                                                        <span style="color: green; font-family: 'DejaVu Sans', sans-serif;">▲</span>
                                                    @elseif ($variacion < 0)
                                                        ({{ ltrim(number_format($variacion, 0, ',', '.'), '-') }})
                                                        <span style="color: red; font-family: 'DejaVu Sans', sans-serif;">▼</span>
                                                    @else
                                                        -
                                                    @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        <p>{{ $todosSonCero2 ? 'La empresa no cuenta con este rubro' : ($mensajes['Dividendos o participacion'] ?? '') }}</p>
                        <br>
                    @endif
                @endif
                
                {{-- Agrupación para "Ingresos" (una única tabla para todas las cuentas) --}}
                @php
                    $notasBloque1 = [
                        'Ingresos de actividades ordinarias' => '27',
                    ];
                    $bloques1 = array_keys($notasBloque1);
                    $contadornotainicial = empty($notasParaMostrar) ? 3 : max($notasParaMostrar);
                    $resultado1 = generarNotasDesde($bloques1, $informeData, $anio, $anioAnterior, $notasBloque1, $contadornotainicial);
                    $notasParaMostrarBloques = $resultado1['notas'];
                    $ultimoNumero = $resultado1['ultimo'];
                @endphp
                <div class="grid-container">
                    <div class="grid-item titulos"><b>NOTA {{$ultimoNumero}}: Ingresos</b></div>
                </div>
                @php
                    // Calcular los totales sumando "Otros ingresos" e "Ingresos financieros"
                    $totalAño1OtrosIngresos = 
                        abs($informeData['Ingresos de actividades ordinarias'][$anio]) +
                        abs($informeData['Otros ingresos'][$anio]) + 
                        abs($informeData['Ingresos financieros'][$anio]);

                    $totalAño2OtrosIngresos = 
                        abs($informeData['Ingresos de actividades ordinarias'][$anioAnterior]) +
                        abs($informeData['Otros ingresos'][$anioAnterior]) + 
                        abs($informeData['Ingresos financieros'][$anioAnterior]);
                    $sinIngresos = ($totalAño1OtrosIngresos == 0 && $totalAño2OtrosIngresos == 0);
                @endphp
                @if (!$sinIngresos)
                    <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse;"
                        class="custom-border-table">
                        <thead>
                            <tr>
                                <th class="sizenotas"><strong>Partida</strong></th>
                                <th class="sizenotas" style="text-align: right;">{{ $dia_numero . '/' . $mes3 .'/'. $anio }}</th>
                                    <th class="sizenotas" style="text-align: right;">{{ $dia_numero . '/' . $mes3 .'/'. $anioAnterior  }}</th>
                                <th class="sizenotas" style="text-align: right;"><strong>VAR %</strong></th>
                                <th class="sizenotas" style="text-align: right;"><strong>VARIACIÓN $</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ([
                                'Ingresos de actividades ordinarias' => 'NOTA 27',
                                'Otros ingresos' => 'NOTA 27',
                                'Ingresos financieros' => 'NOTA 27',
                            ] as $key => $nota)
                                @php
                                    $total1 = $informeData[$key][$anio];
                                    $total2 = $informeData[$key][$anioAnterior];
                                @endphp
                                {{-- Omitir si ambos años son 0 --}}
                                @if ($total1 == '0' && $total2 == '0')
                                    @continue
                                @endif
                                <tr>
                                    <td class="sizenotas">
                                        {{ $informeData[$key]['descripcionct'] }}
                                    </td>
                                    <td class="sizenotas" style="text-align: right">
                                        {{ ($informeData[$key][$anio] ?? 0) == 0 
                                            ? '-' 
                                            : '$' . number_format(abs($informeData[$key][$anio]), 0, '.', ',') 
                                        }}
                                    </td>

                                    <td class="sizenotas" style="text-align: right">
                                        {{ ($informeData[$key][$anioAnterior] ?? 0) == 0 
                                            ? '-' 
                                            : '$' . number_format(abs($informeData[$key][$anioAnterior]), 0, '.', ',') 
                                        }}
                                    </td>


                                    <td class="sizenotas" style="text-align: right">
                                        {{-- {{ number_format($informeData[$key]['var%'], 2, ',', '.')}}% --}}
                                        @php
                                            $total1 = floatval(str_replace(',', '', $informeData[$key][$anio]));
                                            $total2 = floatval(str_replace(',', '', $informeData[$key][$anioAnterior]));
                                            $variacion = abs($total1) - abs($total2);
                                            $varRawtotal = ($total2 != 0) ? round((($total1 / $total2) - 1) * 100, 0) : null;
                                            $varRaw = is_numeric($varRawtotal)
                                            ? number_format(round($varRawtotal), 0, ',', '.') 
                                            : '-';
                                        @endphp
                                        @if (in_array($varRaw, ['0', '0.00', '0.00%','0%','']))
                                            -
                                        @elseif ($varRaw < 0)
                                            ({{ ltrim($varRaw, '-') }}%)
                                        @else
                                            {{ $varRaw }}%
                                        @endif
                                    </td>
                                    <td class="sizenotas" style="text-align: right">
                                        @if ($variacion > 0)
                                            {{ number_format($variacion, 0, '.', ',') }}
                                            <span style="color: green; font-family: 'DejaVu Sans', sans-serif;">▲</span>
                                        @elseif ($variacion < 0)
                                            ({{ ltrim(number_format($variacion, 0, '.', ',') , '-') }})
                                            <span style="color: red; font-family: 'DejaVu Sans', sans-serif;">▼</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                @php
                                    $variacionValor = $totalAño1OtrosIngresos - $totalAño2OtrosIngresos;
                                    // Evitar división por cero
                                    if ($totalAño1OtrosIngresos == 0 || $totalAño2OtrosIngresos == 0) {
                                        $variacionPorcentaje = '0%';
                                    } else {
                                        $variacionPorcentaje = ($totalAño2OtrosIngresos != 0) ? round((($totalAño1OtrosIngresos / $totalAño2OtrosIngresos) - 1) * 100, 0) : null;

                                        // $variacionPorcentaje = number_format((($totalAño1OtrosIngresos / $totalAño2OtrosIngresos) - 1) * 100, 2) . '%';
                                    }

                                    $variacionValorFormateado = number_format($variacionValor, 0, ',', '.');
                                @endphp
                                <td class="sizenotas"><strong>Total Ingresos</strong></td>
                                <td class="sizenotas" style="text-align: right">
                                    <strong>{{ ($totalAño1Utilidades ?? 0) == 0 ? '-' : (($totalAño1OtrosIngresos < 0) ? '($' . number_format(ltrim($totalAño1OtrosIngresos, '-'), 0, ',', '.') . ')' : '$' . number_format($totalAño1OtrosIngresos, 0, ',', '.')) }}</strong>
                                </td>
                                <td class="sizenotas" style="text-align: right">
                                    <strong> {{ ($totalAño2Utilidades ?? 0) == 0 ? '-' : (($totalAño2OtrosIngresos < 0) ? '($' . number_format(ltrim($totalAño2OtrosIngresos, '-'), 0, ',', '.') . ')' : '$' . number_format($totalAño2OtrosIngresos, 0, ',', '.')) }}</strong>
                                </td>
                                <td class="sizenotas" style="text-align: right"><strong>
                                    @php
                                        $varRaw = trim($variacionPorcentaje);
                                        $varNumerico = (float)str_replace(['%', ','], '', $varRaw);
                                        $varNumeric = is_numeric($varNumerico)
                                            ? number_format(round($varNumerico), 0, ',', '.') 
                                            : '-';
                                    @endphp
                                    @if (in_array($varRaw, ['0', '0.00', '0.00%','']))
                                        -
                                    @elseif ($varNumeric < 0)
                                        ({{ ltrim($varRaw, '-') }}%)
                                    @else
                                        {{ $varRaw }}%
                                    @endif
                                </strong></td>
                                    @php
                                        $variacion = (float)str_replace(',', '', $variacionValorFormateado);
                                    @endphp
                                    <td class="sizenotas" style="text-align: right">
                                        @if ($variacionValorFormateado > 0)
                                            <strong> ${{ $variacionValorFormateado }}</strong>
                                            <span style="color: green; font-family: 'DejaVu Sans', sans-serif;">▲</span>
                                        @elseif ($variacionValorFormateado < 0)
                                            <strong> (${{ ltrim($variacionValorFormateado, '-') }})</strong>
                                            <span style="color: red; font-family: 'DejaVu Sans', sans-serif;">▼</span>
                                        @else
                                            <strong> -</strong>
                                            
                                        @endif
                                    </td>
                            </tr>
                        </tbody>
                    </table>
                @endif
                <p>{{ $sinIngresos ? 'La empresa no cuenta con este rubro' : ($mensajes['27'] ?? '') }}</p> <!-- Mensaje específico para Utilidades y Pérdidas -->
                {{-- Agrupación para "Costos" (una única tabla para todas las cuentas) --}}
                @php
                    $notasBloque2 = [
                        'Costos de venta' => 'NOTA 28',
                    ];
                    $bloques2 = array_keys($notasBloque2);
                    $resultado2 = generarNotasDesde($bloques2, $informeData, $anio, $anioAnterior, $notasBloque2, $ultimoNumero);
                    $notasParaMostrarBloques2 = $resultado2['notas'];
                    $ultimoNumero = $resultado2['ultimo'];
                @endphp
                <div class="grid-container">
                    <div class="grid-item titulos"><b>NOTA {{$ultimoNumero}}: Costos</b></div>
                </div>
                <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse;"
                    class="custom-border-table">
                    <thead>
                        <tr>
                            <th class="sizenotas"><strong>Partida</strong></th>
                            <th class="sizenotas" style="text-align: right;">{{ $dia_numero . '/' . $mes3 .'/'. $anio }}</th>
                            <th class="sizenotas" style="text-align: right;">{{ $dia_numero . '/' . $mes3 .'/'. $anioAnterior  }}</th>
                            <th class="sizenotas" style="text-align: right;"><strong>VAR %</strong></th>
                            <th class="sizenotas" style="text-align: right;"><strong>VARIACIÓN $</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ([
                            'Costos de venta' => 'NOTA 28',
                        ] as $key => $nota)
                        @php
                            $totalAño1Costos =$informeData[$key][$anio];
                            $totalAño2Costos =$informeData[$key][$anioAnterior];
                            // Evitar división por cero
                            if ($informeData[$key][$anio] == 0 || $informeData[$key][$anioAnterior] == 0) {
                                $variacionPorcentaje = '0%';
                            } else {
                                $variacionPorcentaje = number_format((($informeData[$key][$anio] / $informeData[$key][$anioAnterior]) - 1) * 100, 2) . '%';
                            }
                            $variacionValor = abs($totalAño1Costos) - abs($totalAño2Costos);
                            $sincostos = ($totalAño1Costos == 0 && $totalAño2Costos == 0);
                        @endphp
                        {{-- Omitir si ambos años son 0 --}}
                            @if ($totalAño1Costos == 0 && $totalAño2Costos == 0)
                                @continue
                            @endif
                            <tr>
                                <td class="sizenotas">
                                    {{ $informeData[$key]['descripcionct'] }}
                                </td>
                                <td class="sizenotas" style="text-align: right">
                                    {{ ($informeData[$key][$anio] ?? 0) == 0 
                                        ? '-' 
                                        : ($informeData[$key][$anio] < 0 
                                            ? '(' . number_format(ltrim($informeData[$key][$anio], '-'), 0, '.', ',') . ')' 
                                            :   number_format($informeData[$key][$anio], 0, '.', ',')) 
                                    }}
                                </td>

                                <td class="sizenotas" style="text-align: right">
                                    {{ ($informeData[$key][$anioAnterior] ?? 0) == 0 
                                        ? '-' 
                                        : ($informeData[$key][$anioAnterior] < 0 
                                            ? '(' . number_format(ltrim($informeData[$key][$anioAnterior], '-'), 0, '.', ',') . ')' 
                                            :  number_format($informeData[$key][$anioAnterior], 0, '.', ',')) 
                                    }}
                                </td>

                                <td class="sizenotas" style="text-align: right">
                                    @php
                                        $varRaw = trim($variacionPorcentaje);
                                        $varNumerico = (float)str_replace(['%', ','], '', $varRaw);
                                        $varNumeric = is_numeric($varNumerico)
                                            ? number_format(round($varNumerico), 0, ',', '.') 
                                            : '-';
                                    @endphp

                                    @if (in_array($varRaw, ['0', '0.00', '0.00%','']))
                                        -
                                    @elseif ($varNumeric < 0)
                                        ({{ ltrim($varRaw, '-') }})
                                    @else
                                        {{ $varRaw }}
                                    @endif
                                </td>
                                <td class="sizenotas" style="text-align: right">
                                    @if ($variacionValor > 0)
                                        {{ number_format($variacionValor, 0, ',', '.') }}
                                        <span style="color: green; font-family: 'DejaVu Sans', sans-serif;">▲</span>
                                    @elseif ($variacion < 0)
                                        ({{ ltrim(number_format($variacionValor, 0, ',', '.'), '-') }})
                                        <span style="color: red; font-family: 'DejaVu Sans', sans-serif;">▼</span>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <p>{{ $sincostos ? 'La empresa no cuenta con este rubro' : ($mensajes['28'] ?? '') }}</p> <!-- Mensaje específico para Utilidades y Pérdidas -->

                {{-- Agrupación para "Gastos" (una única tabla para todas las cuentas) --}}
                <div class="grid-container">
                    <div class="grid-item titulos"><b>NOTA {{$gastos}}: Gastos</b></div>
                </div>
                <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse;"
                    class="custom-border-table">
                    <thead>
                        <tr>
                            <th class="sizenotas"><strong>Partida</strong></th>
                            <th class="sizenotas" style="text-align: right;">{{ $dia_numero . '/' . $mes3 .'/'. $anio }}</th>
                            <th class="sizenotas" style="text-align: right;">{{ $dia_numero . '/' . $mes3 .'/'. $anioAnterior  }}</th>
                            <th class="sizenotas" style="text-align: right;">VAR %</th>
                            <th class="sizenotas" style="text-align: right;">VARIACIÓN $</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Calcular los totales sumando "Gastos"
                            $totalAño1Gastos = 
                                abs($informeData['Gastos de administración'][$anio]) + 
                                abs($informeData['Gastos de ventas'][$anio])+ 
                                abs($informeData['Otros gastos'][$anio])+ 
                                abs($informeData['Gastos financieros'][$anio]);

                            $totalAño2Gastos = 
                                abs($informeData['Gastos de administración'][$anioAnterior]) + 
                                abs($informeData['Gastos de ventas'][$anioAnterior])+ 
                                abs($informeData['Otros gastos'][$anioAnterior])+ 
                                abs($informeData['Gastos financieros'][$anioAnterior]);
                            $singastos = ($totalAño1Gastos == 0 && $totalAño2Gastos == 0);
                        @endphp
                        @foreach ([
                            'Gastos de administración' => 'NOTA 29',
                            'Gastos de ventas' => 'NOTA 29',
                            'Otros gastos' => 'NOTA 29',
                            'Gastos financieros' => 'NOTA 29',
                        ] as $key => $nota)
                            <tr>
                                @php
                                    // Evitar división por cero
                                    if ($informeData[$key][$anio] == 0 || $informeData[$key][$anioAnterior] == 0) {
                                        $variacionPorcentaje1 = '0%';
                                    } else {
                                        $variacionPorcentaje1 = number_format((($informeData[$key][$anio] / $informeData[$key][$anioAnterior]) - 1) * 100, 2) . '%';
                                    }
                                    $variacionValor1 = abs($informeData[$key][$anio]) - abs($informeData[$key][$anioAnterior]);
                                @endphp
                                {{-- Omitir si ambos años son 0 --}}
                                @if ($informeData[$key][$anio] == '0' && $informeData[$key][$anioAnterior] == '0')
                                    @continue
                                @endif
                                <td class="sizenotas">
                                    @php
                                        $descripcion = $informeData[$key]['descripcionct'] == 'Gastos impuesto de renta y cree'
                                            ? 'Gastos impuesto a las ganancias'
                                            : $informeData[$key]['descripcionct'];
                                    @endphp
                                    {{ $descripcion }}
                                </td>
                                <td class="sizenotas" style="text-align: right">
                                    {{ ($informeData[$key][$anio] ?? 0) == 0 
                                        ? '-' 
                                        : ($informeData[$key][$anio] < 0 
                                            ? '(' . number_format(ltrim($informeData[$key][$anio], '-'), 0, '.', ',') . ')' 
                                            :  number_format($informeData[$key][$anio], 0, '.', ',')) 
                                    }}
                                </td>
                                <td class="sizenotas" style="text-align: right">
                                    {{ ($informeData[$key][$anioAnterior] ?? 0) == 0 
                                        ? '-' 
                                        : ($informeData[$key][$anioAnterior] < 0 
                                            ? '(' . number_format(ltrim($informeData[$key][$anioAnterior], '-'), 0, '.', ',') . ')' 
                                            :  number_format($informeData[$key][$anioAnterior], 0, '.', ',')) 
                                    }}
                                </td>

                                <td class="sizenotas" style="text-align: right">
                                    @php
                                        $varRaw = trim($variacionPorcentaje1);
                                        $varNumerico = (float)str_replace(['%', ','], '', $varRaw);
                                        $varNumeric = is_numeric($varNumerico)
                                            ? number_format(round($varNumerico), 0, ',', '.') 
                                            : '-';
                                    @endphp

                                    @if (in_array($varRaw, ['0', '0.00', '0.00%','0%','']))
                                        -
                                    @elseif ($varNumeric < 0)
                                        ({{ ltrim($varRaw, '-') }})
                                    @else
                                        {{ $varRaw }}
                                    @endif
                                </td>
                                @php
                                    $variacion = (float)str_replace(',', '', $variacionValor1);
                                @endphp
                                <td class="sizenotas" style="text-align: right">
                                    
                                    @if ($variacion > 0)
                                    {{ number_format($variacionValor1, 0, ',', '.') }}
                                        <span style="color: green; font-family: 'DejaVu Sans', sans-serif;">▲</span>
                                    @elseif ($variacion < 0)
                                        ({{ ltrim(number_format($variacionValor1, 0, ',', '.'), '-') }})
                                        <span style="color: red; font-family: 'DejaVu Sans', sans-serif;">▼</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                
                            </tr>
                        @endforeach
                        <tr>
                            @php
                                // Evitar división por cero
                                if ($totalAño1Gastos == 0 || $totalAño2Gastos == 0) {
                                    $variacionPorcentaje = '0%';
                                } else {
                                    $variacionPorcentaje = number_format((($totalAño1Gastos / $totalAño2Gastos) - 1) * 100, 2) . '%';
                                }
                                $variacionValor = $totalAño1Gastos - $totalAño2Gastos;
                            @endphp
                            <td class="sizenotas"><strong>Total Gastos</strong></td>
                            <td class="sizenotas" style="text-align: right">
                                <strong>
                                    {{ ($totalAño1Gastos ?? 0) == 0 
                                        ? '-' 
                                        : ($totalAño1Gastos < 0 
                                            ? '(' . number_format(ltrim($totalAño1Gastos, '-'), 0, ',', '.') . ')' 
                                            : number_format($totalAño1Gastos, 0, ',', '.')) 
                                    }}
                                </strong>
                            </td>

                            <td class="sizenotas" style="text-align: right">
                                <strong>
                                    {{ ($totalAño2Gastos ?? 0) == 0 
                                        ? '-' 
                                        : ($totalAño2Gastos < 0 
                                            ? '(' . number_format(ltrim($totalAño2Gastos, '-'), 0, ',', '.') . ')' 
                                            : number_format($totalAño2Gastos, 0, ',', '.')) 
                                    }}
                                </strong>
                            </td>

                            <td class="sizenotas" style="text-align: right"><strong>
                                @php
                                    $varRaw = trim($variacionPorcentaje );
                                    $varNumerico = (float)str_replace(['%', ','], '', $varRaw);
                                    $varNumeric = is_numeric($varNumerico)
                                            ? number_format(round($varNumerico), 0, ',', '.') 
                                            : '-';
                                @endphp

                                @if (in_array($varRaw, ['0', '0.00', '0.00%','0%','']))
                                    -
                                @elseif ($varNumeric < 0)
                                    ({{ ltrim($varRaw, '-') }})
                                @else
                                    {{ $varRaw }}
                                @endif
                            
                            </strong></td>
                            
                            @php
                                $variacion = (float)str_replace(',', '', $variacionValor);
                            @endphp
                            <td class="sizenotas" style="text-align: right">
                                
                                @if ($variacion > 0)
                                <strong>${{ number_format($variacionValor, 0, ',', '.') }}</strong>
                                    <span style="color: green; font-family: 'DejaVu Sans', sans-serif;">▲</span>
                                @elseif ($variacion < 0)
                                    <strong> (${{ ltrim(number_format($variacionValor, 0, ',', '.'), '-') }})</strong>
                                    <span style="color: red; font-family: 'DejaVu Sans', sans-serif;">▼</span>
                                @else
                                    <strong> -</strong>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p>{{ $singastos ? 'La empresa no cuenta con este rubro' : ($mensajes['29'] ?? '') }}</p> <!-- Mensaje específico para Utilidades y Pérdidas -->

                {{-- Agrupación para "Gastos impuesto de renta y cree" (una única tabla para todas las cuentas) --}}
                @php
                    // Definimos la clave y obtenemos los totales
                    $key = 'Gastos impuesto de renta y cree';
                    $totalAño1Gastos = $informeData[$key][$anio] ?? 0;
                    $totalAño2Gastos = $informeData[$key][$anioAnterior] ?? 0;
                    $singastos = ($totalAño1Gastos == 0 && $totalAño2Gastos == 0);
                    $notasiguienteparasumar = $gastos
                @endphp
                @if (!$singastos)
                    {{-- Agrupación para "Gastos impuesto de renta y cree" --}}
                    <div class="grid-container">

                        <div class="grid-item titulos"><b>NOTA {{$notasiguienteparasumar = $gastos + 1}}: Gastos impuesto a las ganancias</b></div>
                    </div>
                    {{-- Tabla para "Gastos impuesto de renta y cree" --}}
                    <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse;"
                        class="custom-border-table">
                        <thead>
                            <tr>
                                <th class="sizenotas"><strong>Partida</strong></th>
                                <th class="sizenotas" style="text-align: right;">{{ $dia_numero . '/' . $mes3 .'/'. $anio }}</th>
                                <th class="sizenotas" style="text-align: right;">{{ $dia_numero . '/' . $mes3 .'/'. $anioAnterior  }}</th>
                                <th class="sizenotas" style="text-align: right;"><strong>VAR %</strong></th>
                                <th class="sizenotas" style="text-align: right;"><strong>VARIACIÓN $</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (['Gastos impuesto de renta y cree' => 'NOTA 30'] as $key => $nota)
                                @php
                                    $totalAño1Gastos = $informeData[$key][$anio];
                                    $totalAño2Gastos = $informeData[$key][$anioAnterior];
                                    $variacionPorcentaje = ($totalAño2Gastos == 0) ? '0%' :
                                        number_format((($totalAño1Gastos / $totalAño2Gastos) - 1) * 100, 2) . '%';

                                    $descripcion = $informeData[$key]['descripcionct'] == 'Gastos impuesto de renta y cree'
                                        ? 'Gastos impuesto a las ganancias'
                                        : $informeData[$key]['descripcionct'];

                                    $variacion = abs($totalAño1Gastos) - abs($totalAño2Gastos);
                                    $varRaw = trim($variacionPorcentaje);
                                    $varNumeric = (float)str_replace(['%', ','], '', $varRaw);
                                    $varRaw = is_numeric($varRaw)
                                            ? number_format(round($varRaw), 0, ',', '.') 
                                            : '-';
                                @endphp
                                <tr>
                                    <td class="sizenotas"> <strong>{{ $descripcion }} </strong></td>
                                    <td class="sizenotas" style="text-align: right">
                                        <strong>
                                            {{ ($totalAño1Gastos ?? 0) == 0 
                                                ? '-' 
                                                : ($totalAño1Gastos < 0 
                                                    ? '(' . number_format(ltrim($totalAño1Gastos, '-'), 0, ',', '.') . ')' 
                                                    : number_format($totalAño1Gastos, 0, ',', '.')) 
                                            }}
                                        </strong>
                                    </td>

                                    <td class="sizenotas" style="text-align: right">
                                        <strong>
                                            {{ ($totalAño2Gastos ?? 0) == 0 
                                                ? '-' 
                                                : ($totalAño2Gastos < 0 
                                                    ? '(' . number_format(ltrim($totalAño2Gastos, '-'), 0, ',', '.') . ')' 
                                                    : number_format($totalAño2Gastos, 0, ',', '.')) 
                                            }}
                                        </strong>
                                    </td>

                                    <td class="sizenotas" style="text-align: right"> <strong>
                                        @if (in_array($varRaw, ['0', '0.00', '0.00%', '', '0%', '%', '-',0]))
                                            -
                                        @elseif ($varRedondeado < 0)
                                            ({{ ltrim($varRaw, '-') }})
                                        @else
                                            {{ $varRaw }}
                                        @endif
                                    </strong>
                                    </td>
                                    <td class="sizenotas" style="text-align: right"> <strong>
                                        @if ($variacion > 0)
                                            {{ number_format($variacion, 0, ',', '.') }}
                                            <span style="color: green; font-family: 'DejaVu Sans', sans-serif;">▲</span>
                                        @elseif ($variacion < 0)
                                            ({{ ltrim(number_format($variacion, 0, ',', '.'), '-') }})
                                            <span style="color: red; font-family: 'DejaVu Sans', sans-serif;">▼</span>
                                        @else
                                            -
                                        @endif
                                    </strong>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <p>{{ $singastos ? 'La empresa no cuenta con este rubro' : ($mensajes['30'] ?? '') }}</p>
                @endif
                <!-- Mensaje específico para Utilidades y Pérdidas -->
                {{-- Agrupación para "Hechos Posteriores" (una única tabla para todas las cuentas) --}}
                <div class="grid-container">
                    <div class="grid-item titulos"><b>NOTA {{$notasiguienteparasumar = $notasiguienteparasumar+1}}: Hechos Posteriores</b></div>
                </div>
                <p>{{ $mensajes['301'] ?? '' }}</p> <!-- Mensaje específico para Utilidades y Pérdidas -->
                <br>
                @if($mensajes['31'] != 'la nota queda en blanco para anotaciones o aclaraciones puntuales')
                {{-- Agrupación para "Hechos Posteriores" (una única tabla para todas las cuentas) --}}
                <div class="grid-container">
                    <div class="grid-item titulos"><b>NOTA {{$notasiguienteparasumar = $notasiguienteparasumar+1}}</b></div>
                </div>
                <p>{{ $mensajes['31'] ?? '' }}</p> <!-- Mensaje aclaraciones o echos posteriores -->
                <br>
                @endif
                {{-- Agrupación para "Aprobacion de los Estados Financieros " (una única tabla para todas las cuentas) --}}
                <div class="grid-container">
                    <div class="grid-item titulos"><b>NOTA {{$notasiguienteparasumar = $notasiguienteparasumar+1}}</b></div>
                </div>
                <p>{{ $mensajes['32'] ?? '' }}</p> <!-- Mensaje específico para Utilidades y Pérdidas -->
                <br>
            @else
                 {{--nota 8--}}
                @php
                    $descripcionNota = 'Cuentas por pagar comerciales y otras cuentas por pagar';
                    $notaNumeroCorrecto = $notasParaMostrar[$descripcionNota] ?? $notaNumero;
                    // Filtrar esa única cuenta
                    $filtradoPorDescripcion = collect($informedetallado)->filter(function ($item) use ($descripcionNota) {
                        return $item['descripcion'] === $descripcionNota;
                    });

                    $todosSonCero = $filtradoPorDescripcion->every(function ($detalle) {
                        $año1 = str_replace([',', '.'], '', trim($detalle['totalaño1'] ?? '0'));
                        $año2 = str_replace([',', '.'], '', trim($detalle['totalaño2'] ?? '0'));
                        return ($año1 === '0' || $año1 === '') && ($año2 === '0' || $año2 === '');
                    });

                    // Obtener la clave de la agrupación correspondiente
                    $claveNota = collect($agrupaciones)->search(function ($grupo) use ($descripcionNota) {
                        return $grupo['descripcion'] === $descripcionNota;
                    });

                    $subcuentasFiltradas = collect();
                    if (!empty($cuentasPorNota[$claveNota])) {
                        foreach ($cuentasPorNota[$claveNota] as $grupo) {
                            $grupo = (array) $grupo;
                            $subcuentas = $cuentasnotas->filter(function ($item) use ($grupo) {
                                return in_array($item->cuenta, $grupo);
                            });
                            $subcuentasFiltradas = $subcuentasFiltradas->merge($subcuentas);
                        }
                        $subcuentasFiltradas = $subcuentasFiltradas->unique('cuenta');
                    }

                    $descripcionFinal = $descripcionNota;
                    if ($representantelegal['gruponiif'] == 2 && isset($claveNota)) {
                        $descripcionFinal = $agrupacionesniif2[$claveNota]['descripcion'] ?? $descripcionNota;
                    } elseif (isset($claveNota)) {
                        $descripcionFinal = $agrupaciones[$claveNota]['descripcion'] ?? $descripcionNota;
                    }
                @endphp

                @if ($filtradoPorDescripcion->isNotEmpty() && !$todosSonCero)
                    <div class="grid-container">
                        <div class="grid-item titulos">
                            <b>NOTA {{ $notaNumeroCorrecto }}: {{ $descripcionFinal }}</b>
                        </div>
                    </div>

                    {{-- Tabla de subcuentas --}}
                    <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse;" class="custom-border-table">
                        <thead>
                            <tr>
                                <th class="sizenotas"><strong>Partida</strong></th>
                                <th class="sizenotas" style="text-align: right;">{{ $dia_numero . '/' . $mes3 . '/' . $anio }}</th>
                                <th class="sizenotas" style="text-align: right;">{{ $ultimoDiaMes .'/' . 12 . '/' . $anioAnterior }}</th>
                                <th class="sizenotas" style="text-align: right;"><strong>VAR %</strong></th>
                                <th class="sizenotas" style="text-align: right;"><strong>VARIACIÓN $</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subcuentasFiltradas as $item)
                                @php
                                    $año1 = floatval(str_replace(',', '', $item->saldo_anio_actual ?? 0));
                                    $año2 = floatval(str_replace(',', '', $item->saldo_anio_anterior ?? 0));

                                    if ($año1 == 0 && $año2 == 0) continue;

                                    $variacion = $año1 - $año2;
                                    $varRaw = ($año2 != 0) ? (($año1 / $año2) - 1) * 100 : null;
                                    $varFormateado = is_numeric($varRaw) ? number_format(abs(round($varRaw)), 0, ',', '.') : '-';
                                @endphp
                                <tr>
                                    <td class="sizenotas">{{ ucfirst(strtolower($item->descripcion)) }}</td>
                                    <td class="sizenotas" style="text-align: right;">
                                        {{ $año1 == 0 ? '-' : ($año1 < 0 ? '(' . number_format(abs($año1), 0, '.', ',') . ')' : number_format($año1, 0, '.', ',')) }}
                                    </td>
                                    <td class="sizenotas" style="text-align: right;">
                                        {{ $año2 == 0 ? '-' : ($año2 < 0 ? '(' . number_format(abs($año2), 0, '.', ',') . ')' : number_format($año2, 0, '.', ',')) }}
                                    </td>
                                    <td class="sizenotas" style="text-align: right;">
                                        @if (!is_numeric($varRaw) || in_array($varFormateado, ['0', '0.00']))
                                            -
                                        @elseif ($varRaw < 0)
                                            ({{ $varFormateado }}%)
                                        @else
                                            {{ $varFormateado }}%
                                        @endif
                                    </td>
                                    <td class="sizenotas" style="text-align: right;">
                                        @if ($variacion > 0)
                                            {{ number_format($variacion, 0, '.', ',') }} <span style="color: green;font-family: 'DejaVu Sans', sans-serif;">▲</span>
                                        @elseif ($variacion < 0)
                                            ({{ number_format(abs($variacion), 0, '.', ',') }}) <span style="color: red;font-family: 'DejaVu Sans', sans-serif;">▼</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                            {{-- Fila total --}}
                            @php
                                $total1 = $filtradoPorDescripcion->sum(function ($item) {
                                    return floatval(str_replace(',', '', $item['totalaño1'] ?? 0));
                                });
                                $total2 = $filtradoPorDescripcion->sum(function ($item) {
                                    return floatval(str_replace(',', '', $item['totalaño2'] ?? 0));
                                });
                                $variacionp = abs($total1) - abs($total2);
                                $varRaw = ($total2 != 0) ? (($total1 / $total2) - 1) * 100 : null;
                                $varFormateado = is_numeric($varRaw) ? number_format(abs(round($varRaw)), 0, ',', '.') : '-';
                            @endphp
                            <tr>
                                <td class="sizenotas" style="font-weight: bold !important">Total {{ $descripcionFinal }}</td>
                                <td class="sizenotas" style="text-align: right; font-weight: bold !important;">
                                    {{ $total1 == 0 ? '-' : ($total1 < 0 ? '(' . number_format(abs($total1), 0, '.', ',') . ')' : number_format($total1, 0, '.', ',')) }}
                                </td>
                                <td class="sizenotas" style="text-align: right; font-weight: bold !important;">
                                    {{ $total2 == 0 ? '-' : ($total2 < 0 ? '(' . number_format(abs($total2), 0, '.', ',') . ')' : number_format($total2, 0, '.', ',')) }}
                                </td>
                                <td class="sizenotas" style="text-align: right; font-weight: bold !important;">
                                    @if (!is_numeric($varRaw) || in_array($varFormateado, ['0', '0.00']))
                                        -
                                    @elseif ($varRaw < 0)
                                        ({{ $varFormateado }}%)
                                    @else
                                        {{ $varFormateado }}%
                                    @endif
                                </td>
                                <td class="sizenotas" style="text-align: right; font-weight: bold !important;">
                                    @if ($variacionp > 0)
                                        {{ number_format($variacionp, 0, '.', ',') }}<span style="color: green;">▲</span>
                                    @elseif ($variacionp < 0)
                                        ({{ number_format(abs($variacionp), 0, '.', ',') }})<span style="color: red;">▼</span>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    {{-- Mensaje final --}}
                    @php
                        $plantilla = $mensajes[$claveNota] ?? '';
                        $valorCuenta = $filtradoPorDescripcion->sum(function ($item) {
                            return abs((int)str_replace(',', '', $item['totalaño1'] ?? '0'));
                        });
                        $fechaActual   =  $dia_numero .' de '. $mes2  . ' del ' . $anio;
                        $empresa       = $representantelegal['razon_social'];
                        $anioActual    = $anio;

                        $reemplazos = [
                            '{SALTO_PAGINA}'  => '<br>',
                            '{VALOR_CUENTA}'  => '$'.number_format($valorCuenta, 0, ',', '.'),
                            '{FECHA_ACTUAL}'  => $fechaActual,
                            '{EMPRESA}'       => $empresa,
                            '{ANIO}'          => $anioActual,
                        ];

                        $mensajeCompilado = strtr($plantilla, $reemplazos);
                        $mensajeCompilado = preg_replace_callback('/\b([A-ZÁÉÍÓÚÑ]{2,})\b/u', function ($matches) {
                            return '<strong>' . $matches[1] . '</strong>';
                        }, $mensajeCompilado);
                    @endphp

                    <p>{!! $mensajeCompilado !!}</p>
                @elseif ($todosSonCero)
                    <p>La empresa no cuenta con este rubro.</p>
                @endif
               {{--patrimonio--}}
                @php
                    $cuentasPatrimonio = [
                            'Capital social',
                            'Superavit de capital',
                            'Reservas',
                            'Utilidad y/o perdidas del ejercicio',
                            'Resultado del ejercicio',
                            'Utilidad y/o perdidas acumuladas',
                            'Ganancias acumuladas - Adopcion por primera vez',
                            'Dividendos o participacion',
                            'Superavit de Capital Valorizacion'
                    ];
                    $filtradoPatrimonio = collect($informedetallado)->filter(function ($item) use ($cuentasPatrimonio) {
                        return in_array($item['descripcion'], $cuentasPatrimonio);
                    });

                    $totalAño1Patrimonio = $filtradoPatrimonio->sum(function ($item) {
                        return abs((int) str_replace(',', '', $item['totalaño1']));
                    });

                    $totalAño2Patrimonio = $filtradoPatrimonio->sum(function ($item) {
                        return abs((int) str_replace(',', '', $item['totalaño2']));
                    });

                    $sinPatrimonio = ($totalAño1Patrimonio == 0 && $totalAño2Patrimonio == 0);
                    $notasBloque1 = [
                        'patrimonio' => '27',
                    ];
                    $bloques1 = array_keys($notasBloque1);
                    $contadornotainicial = empty($notasParaMostrar) ? 3 : max($notasParaMostrar);
                    $resultado1 = generarNotasDesde($bloques1, $informeData, $anio, $anioAnterior, $notasBloque1, $contadornotainicial);
                    $notasParaMostrarBloques = $resultado1['notas'];
                    $ultimoNumero = $resultado1['ultimo'];
                @endphp
                @if ($filtradoPatrimonio->isNotEmpty())
                    <div class="grid-container">
                        <div class="grid-item titulos"><b>NOTA {{ $ultimoNumero }}: PATRIMONIO</b></div>
                    </div>

                    <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse;" class="custom-border-table">
                        <thead>
                            <tr>
                                <th class="sizenotas"><strong>Partida</strong></th>
                                <th class="sizenotas" style="text-align: right;">{{ $dia_numero . '/' . $mes3 . '/' . $anio }}</th>
                                <th class="sizenotas" style="text-align: right;">{{ '31/12/' . $anioAnterior }}</th>
                                <th class="sizenotas" style="text-align: right;"><strong>VAR %</strong></th>
                                <th class="sizenotas" style="text-align: right;"><strong>VARIACIÓN $</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($filtradoPatrimonio as $detalle)
                                @php
                                    $valor1 = preg_replace('/[^\d\-]/', '', $detalle['totalaño1'] ?? '0');
                                    $valor2 = preg_replace('/[^\d\-]/', '', $detalle['totalaño2'] ?? '0');
                                    if($detalle['descripcion']  != 'Utilidad y/o perdidas del ejercicio') {
                                        $valor1 = $valor1*-1;
                                        $valor2 = $valor2*-1;
                                    }
                                    
                                @endphp

                                @if ($valor1 == 0 && $valor2 == 0)
                                    @continue
                                @endif

                                <tr>
                                    <td class="sizenotas">{{ $detalle['descripcion'] }}</td>
                                    <td class="sizenotas" style="text-align: right;">
                                        {{ $valor1 < 0 ? '(' . number_format(abs((int)$valor1), 0, ',', '.') . ')' : number_format(abs((int)$valor1), 0, ',', '.') }}
                                    </td>
                                    <td class="sizenotas" style="text-align: right;">
                                        {{ $valor2 < 0 ? '(' . number_format(abs((int)$valor2), 0, ',', '.') . ')' : number_format(abs((int)$valor2), 0, ',', '.') }}
                                    </td>

                                    {{-- VAR % --}}
                                    @php
                                        $varRaw = trim($detalle['VAR'] ?? '0');
                                        $varNumeric = (float) str_replace(['%', ','], '', $varRaw);
                                        $varRedondeado = is_numeric($varNumeric) ? number_format(round($varNumeric), 0, ',', '.') : '-';
                                    @endphp
                                    <td class="sizenotas" style="text-align: right;">
                                        @if (in_array($varRedondeado, [0, 0.00]))
                                            -
                                        @elseif ($varRedondeado < 0)
                                            ({{ abs($varRedondeado) }}%)
                                        @else
                                            {{ $varRedondeado }}%
                                        @endif
                                    </td>

                                    {{-- VARIACIÓN $ --}}
                                    @php
                                        $clean1 = floatval(str_replace(',', '', $detalle['totalaño1']));
                                        $clean2 = floatval(str_replace(',', '', $detalle['totalaño2']));
                                        $variacion = abs($clean1) - abs($clean2);
                                    @endphp
                                    <td class="sizenotas" style="text-align: right">
                                        @if ($variacion > 0)
                                            {{ number_format($variacion, 0, ',', '.') }}
                                            <span style="color: green; font-family: 'DejaVu Sans', sans-serif;">▲</span>
                                        @elseif ($variacion < 0)
                                            ({{ ltrim(number_format($variacion, 0, ',', '.'), '-') }})
                                            <span style="color: red; font-family: 'DejaVu Sans', sans-serif;">▼</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                            {{-- Total Patrimonio --}}
                            @php
                                $variacionTotal = abs($totalAño1Patrimonio) - abs($totalAño2Patrimonio);
                                if ($totalAño1Patrimonio == 0 || $totalAño2Patrimonio == 0) {
                                    $variacionPorcentaje = '0%';
                                } else {
                                    $variacionPorcentaje = round((($totalAño1Patrimonio / $totalAño2Patrimonio) - 1) * 100) . '%';
                                }

                                $variacionTotalFormateado = number_format($variacionTotal, 0, ',', '.');
                            @endphp

                            <tr>
                                <td class="sizenotas"><strong>Total Patrimonio</strong></td>
                                <td class="sizenotas" style="text-align: right"><strong>${{ number_format(abs($totalAño1Patrimonio), 0, ',', '.') }}</strong></td>
                                <td class="sizenotas" style="text-align: right"><strong>${{ number_format(abs($totalAño2Patrimonio), 0, ',', '.') }}</strong></td>
                                <td class="sizenotas" style="text-align: right">
                                    <strong>
                                        @php
                                            $varRaw = trim($variacionPorcentaje);
                                            $varNumeric = (float) str_replace(['%', ','], '', $varRaw);
                                        @endphp

                                        @if (in_array($varRaw, ['0', '0.00', '0.00%', '0%']))
                                            -
                                        @elseif ($varNumeric < 0)
                                            ({{ ltrim($varRaw, '-') }})
                                        @else
                                            {{ $varRaw }}%
                                        @endif
                                    </strong>
                                </td>
                                <td class="sizenotas" style="text-align: right">
                                    <strong>
                                        @if ($variacionTotal > 0)
                                            ${{ $variacionTotalFormateado }}
                                            <span style="color: green; font-family: 'DejaVu Sans', sans-serif;">▲</span>
                                        @elseif ($variacionTotal < 0)
                                            (${{ ltrim($variacionTotalFormateado, '-') }})
                                            <span style="color: red; font-family: 'DejaVu Sans', sans-serif;">▼</span>
                                        @else
                                            -
                                        @endif
                                    </strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    @php
                        // Plantilla cruda MENSAJE DE LAS NOTAS
                        $plantilla = $mensajes['9'] ?? '';

                        // Datos que necesitas inyectar
                        $valorCuenta = $filtradoPorDescripcion->sum(function ($item) {
                            return abs((int)str_replace(',', '', $item['totalaño1'] ?? '0'));
                        });
                        $fechaActual = $dia_numero . ' de ' . $mes2 . ' del ' . $anio;
                        $empresa = $representantelegal['razon_social'];
                        $anioActual = $anio;

                        // Sustituciones
                        $reemplazos = [
                            '{SALTO_PAGINA}' => '<br>',
                            '{VALOR_CUENTA}' => '$' . number_format($valorCuenta, 0, ',', '.'),
                            '{FECHA_ACTUAL}' => $fechaActual,
                            '{EMPRESA}' => $empresa,
                            '{ANIO}' => $anioActual,
                        ];

                        // Compilar mensaje
                        $mensajeCompilado = strtr($plantilla, $reemplazos);

                        // Poner en negrita todas las palabras completamente en mayúscula (mínimo 2 letras para evitar "Y", "A", etc.)
                        $mensajeCompilado = preg_replace_callback('/\b([A-ZÁÉÍÓÚÑ]{2,})\b/u', function ($matches) {
                            return '<strong>' . $matches[1] . '</strong>';
                        }, $mensajeCompilado);

                        // Separar el mensaje en líneas
                       // Obtener las líneas del mensaje separadas por ítems (1), (2), etc.
                       $lineas = preg_split('/(?=\(\d+\))/', $mensajeCompilado, -1, PREG_SPLIT_NO_EMPTY);

                        // Buscar el índice de la línea que comienza con (2)
                        $indice = -1;
                        foreach ($lineas as $i => $linea) {
                            if (strpos(trim($linea), '(2)') === 0) {
                                $indice = $i;
                                break;
                            }
                        }

                        // Generar la tabla HTML como antes
                        $tablaCapitalHTML = '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; margin-top:10px;">
                            <thead>
                                <tr>
                                    <th style="text-align:left;">Partida</th>
                                    <th style="text-align:right;">' . $dia_numero . '/' . $mes3 . '/' . $anio . '</th>
                                    <th style="text-align:right;">31/12/' . $anioAnterior . '</th>
                                </tr>
                            </thead>
                            <tbody>';

                        $filtradoCapital = collect($informedetallado)->filter(function ($item) {
                            return $item['descripcion'] === 'Capital social';
                        });

                        foreach ($filtradoCapital as $fila) {
                            $valor1 = abs((int)str_replace(',', '', $fila['totalaño1']));
                            $valor2 = abs((int)str_replace(',', '', $fila['totalaño2']));

                            if ($valor1 == 0 && $valor2 == 0) continue;

                            $tablaCapitalHTML .= '<tr>
                                <td>' . $fila['descripcion'] . '</td>
                                <td style="text-align:right;">' . number_format($valor1, 0, ',', '.') . '</td>
                                <td style="text-align:right;">' . number_format($valor2, 0, ',', '.') . '</td>
                            </tr>';
                        }

                        $tablaCapitalHTML .= '</tbody></table>';

                        // Insertar la tabla antes del (2)
                        if ($indice !== -1) {
                            array_splice($lineas, $indice, 0, [$tablaCapitalHTML]);
                        }

                        // Unir las líneas de nuevo
                        $mensajeConTabla = implode('<br>', $lineas);
                    @endphp

                    @if($sinPatrimonio)
                        <p>La empresa no cuenta con este rubro</p>
                    @else
                        {{-- Usa {!! !!} para que Blade no escape el HTML de SALTO_PAGINA --}}
                        {!! $mensajeConTabla !!}
                        <br>
                    @endif
                    @php $notaNumero++; @endphp
                @endif
                <br>
                {{--ingresos--}}
                   {{-- Agrupación para "Ingresos" (una única tabla para todas las cuentas) --}}
                @php
                    $notasBloque1 = [
                        'Ingresos de actividades ordinarias' => '27',
                    ];
                    $bloques1 = array_keys($notasBloque1);
                    $contadornotainicial = empty($notasParaMostrar) ? 3 : max($notasParaMostrar);
                    $resultado1 = generarNotasDesde($bloques1, $informeData, $anio, $anioAnterior, $notasBloque1, $contadornotainicial);
                    $notasParaMostrarBloques = $resultado1['notas'];
                    $ultimoNumero = $resultado1['ultimo'];
                @endphp
                <div class="grid-container">
                    <div class="grid-item titulos"><b>NOTA {{$ultimoNumero}}: OPERACIONES CONTINUAS</b></div>
                    @php
                        // Calcular los totales sumando "Otros ingresos" e "Ingresos financieros"
                        $totalAño1OtrosIngresos = 
                            abs($informeData['Ingresos de actividades ordinarias'][$anio]);

                        $totalAño2OtrosIngresos = 
                            abs($informeData['Ingresos de actividades ordinarias'][$anioAnterior]);
                        $sinIngresos = ($totalAño1OtrosIngresos == 0 && $totalAño2OtrosIngresos == 0);
                    @endphp
                    @php
                            // Plantilla cruda MENSAJE DE LAS NOTAS
                            $plantilla = $mensajes['10'] ?? '';
                            // Sustituciones
                            $reemplazos = [
                                '{SALTO_PAGINA}' => '<br>',
                            ];

                            // Compilar mensaje
                            $mensajeCompilado = strtr($plantilla, $reemplazos);
                    @endphp
                    @if($sinIngresos)
                            <p>La empresa no cuenta con este rubro</p>
                    @else
                            {{-- Usa {!! !!} para que Blade no escape el HTML de SALTO_PAGINA --}}
                            {!! $mensajeCompilado !!} <br>
                    @endif
                    @if (!$sinIngresos)
                        <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse;" class="custom-border-table">
                            <thead>
                                <tr>
                                    <th class="sizenotas"><strong>Partida</strong></th>
                                    <th class="sizenotas" style="text-align: right;">{{ $dia_numero . '/' . $mes3 .'/'. $anio }}</th>
                                <th class="sizenotas" style="text-align: right;">{{ $dia_numero . '/' . $mes3 .'/'. $anioAnterior  }}</th>
                                    <th class="sizenotas" style="text-align: right;"><strong>VAR %</strong></th>
                                    <th class="sizenotas" style="text-align: right;"><strong>VARIACIÓN $</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ([
                                    'Ingresos de actividades ordinarias' => 'NOTA 27',
                                ] as $key => $nota)
                                    @php
                                        $total1 = $informeData[$key][$anio];
                                        $total2 = $informeData[$key][$anioAnterior];
                                    @endphp
                                    {{-- Omitir si ambos años son 0 --}}
                                    @if ($total1 == '0' && $total2 == '0')
                                        @continue
                                    @endif
                                    <tr>
                                        <td class="sizenotas">
                                            {{ $informeData[$key]['descripcionct'] }}
                                        </td>
                                        <td class="sizenotas" style="text-align: right">
                                            {{ ($informeData[$key][$anio] ?? 0) == 0 
                                                ? '-' 
                                                : '$' . number_format(abs($informeData[$key][$anio]), 0, '.', ',') 
                                            }}
                                        </td>

                                        <td class="sizenotas" style="text-align: right">
                                            {{ ($informeData[$key][$anioAnterior] ?? 0) == 0 
                                                ? '-' 
                                                : '$' . number_format(abs($informeData[$key][$anioAnterior]), 0, '.', ',') 
                                            }}
                                        </td>


                                        <td class="sizenotas" style="text-align: right">
                                            {{-- {{ number_format($informeData[$key]['var%'], 2, ',', '.')}}% --}}
                                            @php
                                                $total1 = floatval(str_replace(',', '', $informeData[$key][$anio]));
                                                $total2 = floatval(str_replace(',', '', $informeData[$key][$anioAnterior]));
                                                $variacion = abs($total1) - abs($total2);
                                                $varRawtotal = ($total2 != 0) ? round((($total1 / $total2) - 1) * 100, 0) : null;
                                                $varRaw = is_numeric($varRawtotal)
                                                ? number_format(round($varRawtotal), 0, ',', '.') 
                                                : '-';
                                            @endphp
                                            @if (in_array($varRaw, ['0', '0.00', '0.00%','0%','']))
                                                -
                                            @elseif ($varRaw < 0)
                                                ({{ ltrim($varRaw, '-') }}%)
                                            @else
                                                {{ $varRaw }}%
                                            @endif
                                        </td>
                                        <td class="sizenotas" style="text-align: right">
                                            @if ($variacion > 0)
                                                {{ number_format($variacion, 0, '.', ',') }}
                                                <span style="color: green; font-family: 'DejaVu Sans', sans-serif;">▲</span>
                                            @elseif ($variacion < 0)
                                                ({{ ltrim(number_format($variacion, 0, '.', ',') , '-') }})
                                                <span style="color: red; font-family: 'DejaVu Sans', sans-serif;">▼</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    @php
                                        $variacionValor = $totalAño1OtrosIngresos - $totalAño2OtrosIngresos;
                                        // Evitar división por cero
                                        if ($totalAño1OtrosIngresos == 0 || $totalAño2OtrosIngresos == 0) {
                                            $variacionPorcentaje = '0%';
                                        } else {
                                            $variacionPorcentaje = ($totalAño2OtrosIngresos != 0) ? round((($totalAño1OtrosIngresos / $totalAño2OtrosIngresos) - 1) * 100, 0) : null;

                                            // $variacionPorcentaje = number_format((($totalAño1OtrosIngresos / $totalAño2OtrosIngresos) - 1) * 100, 2) . '%';
                                        }

                                        $variacionValorFormateado = number_format($variacionValor, 0, ',', '.');
                                    @endphp
                                    <td class="sizenotas"><strong>Total Ingresos</strong></td>
                                    <td class="sizenotas" style="text-align: right">
                                        <strong>{{ ($totalAño1OtrosIngresos ?? 0) == 0 ? '-' : (($totalAño1OtrosIngresos < 0) ? '($' . number_format(ltrim($totalAño1OtrosIngresos, '-'), 0, ',', '.') . ')' : '$' . number_format($totalAño1OtrosIngresos, 0, ',', '.')) }}</strong>
                                    </td>
                                    <td class="sizenotas" style="text-align: right">
                                        <strong> {{ ($totalAño2OtrosIngresos ?? 0) == 0 ? '-' : (($totalAño2OtrosIngresos < 0) ? '($' . number_format(ltrim($totalAño2OtrosIngresos, '-'), 0, ',', '.') . ')' : '$' . number_format($totalAño2OtrosIngresos, 0, ',', '.')) }}</strong>
                                    </td>
                                    <td class="sizenotas" style="text-align: right"><strong>
                                        @php
                                            $varRaw = trim($variacionPorcentaje);
                                            $varNumerico = (float)str_replace(['%', ','], '', $varRaw);
                                            $varNumeric = is_numeric($varNumerico)
                                                ? number_format(round($varNumerico), 0, ',', '.') 
                                                : '-';
                                        @endphp
                                        @if (in_array($varRaw, ['0', '0.00', '0.00%','']))
                                            -
                                        @elseif ($varNumeric < 0)
                                            ({{ ltrim($varRaw, '-') }})
                                        @else
                                            {{ $varRaw }}
                                        @endif
                                    </strong></td>
                                        @php
                                            $variacion = (float)str_replace(',', '', $variacionValorFormateado);
                                        @endphp
                                        <td class="sizenotas" style="text-align: right">
                                            @if ($variacionValorFormateado > 0)
                                                <strong> ${{ $variacionValorFormateado }}</strong>
                                                <span style="color: green; font-family: 'DejaVu Sans', sans-serif;">▲</span>
                                            @elseif ($variacionValorFormateado < 0)
                                                <strong> (${{ ltrim($variacionValorFormateado, '-') }})</strong>
                                                <span style="color: red; font-family: 'DejaVu Sans', sans-serif;">▼</span>
                                            @else
                                                <strong> -</strong>
                                                
                                            @endif
                                        </td>
                                </tr>
                            </tbody>
                        </table>
                    @endif
                </div>
                <br>
                <br>
                {{--costos--}}
                @php
                    $notasBloque2 = [
                        'Costos de venta' => '28',
                    ];
                    $bloques2 = array_keys($notasBloque2);
                    $resultado2 = generarNotasDesde($bloques2, $informeData, $anio, $anioAnterior, $notasBloque2, $ultimoNumero);
                    $notasParaMostrarBloques2 = $resultado2['notas'];
                    $ultimoNumero = $resultado2['ultimo'];
                @endphp
                <div class="grid-container">
                    <div class="grid-item titulos"><b>NOTA {{$ultimoNumero}}: COSTO POR COMERCIALIZACION</b></div>
                    <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse;"
                        class="custom-border-table">
                        <thead>
                            <tr>
                                <th class="sizenotas"><strong>Partida</strong></th>
                                <th class="sizenotas" style="text-align: right;">{{ $dia_numero . '/' . $mes3 .'/'. $anio }}</th>
                                <th class="sizenotas" style="text-align: right;">{{ $dia_numero . '/' . $mes3 .'/'. $anioAnterior  }}</th>
                                <th class="sizenotas" style="text-align: right;"><strong>VAR %</strong></th>
                                <th class="sizenotas" style="text-align: right;"><strong>VARIACIÓN $</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ([
                                'Costos de venta' => 'NOTA 28',
                            ] as $key => $nota)
                            @php
                                $totalAño1Costos =$informeData[$key][$anio];
                                $totalAño2Costos =$informeData[$key][$anioAnterior];
                                // Evitar división por cero
                                if ($informeData[$key][$anio] == 0 || $informeData[$key][$anioAnterior] == 0) {
                                    $variacionPorcentaje = '0%';
                                } else {
                                    $variacionPorcentaje = number_format((($informeData[$key][$anio] / $informeData[$key][$anioAnterior]) - 1) * 100, 2) . '%';
                                }
                                $variacionValor = abs($totalAño1Costos) - abs($totalAño2Costos);
                                $sincostos = ($totalAño1Costos == 0 && $totalAño2Costos == 0);
                            @endphp
                            {{-- Omitir si ambos años son 0 --}}
                                @if ($año1 == 0 && $año2 == 0)
                                    @continue
                                @endif
                                <tr>
                                    <td class="sizenotas">
                                        {{ $informeData[$key]['descripcionct'] }}
                                    </td>
                                    <td class="sizenotas" style="text-align: right">
                                        {{ ($informeData[$key][$anio] ?? 0) == 0 
                                            ? '-' 
                                            : ($informeData[$key][$anio] < 0 
                                                ? '(' . number_format(ltrim($informeData[$key][$anio], '-'), 0, '.', ',') . ')' 
                                                :   number_format($informeData[$key][$anio], 0, '.', ',')) 
                                        }}
                                    </td>

                                    <td class="sizenotas" style="text-align: right">
                                        {{ ($informeData[$key][$anioAnterior] ?? 0) == 0 
                                            ? '-' 
                                            : ($informeData[$key][$anioAnterior] < 0 
                                                ? '(' . number_format(ltrim($informeData[$key][$anioAnterior], '-'), 0, '.', ',') . ')' 
                                                :  number_format($informeData[$key][$anioAnterior], 0, '.', ',')) 
                                        }}
                                    </td>

                                    <td class="sizenotas" style="text-align: right">
                                        @php
                                            $varRaw = trim($variacionPorcentaje);
                                            $varNumerico = (float)str_replace(['%', ','], '', $varRaw);
                                            $varNumeric = is_numeric($varNumerico)
                                                ? number_format(round($varNumerico), 0, ',', '.') 
                                                : '-';
                                        @endphp

                                        @if (in_array($varRaw, ['0', '0.00', '0.00%','']))
                                            -
                                        @elseif ($varNumeric < 0)
                                            ({{ ltrim($varRaw, '-') }})
                                        @else
                                            {{ $varRaw }}
                                        @endif
                                    </td>
                                    <td class="sizenotas" style="text-align: right">
                                        @if ($variacionValor > 0)
                                            {{ number_format($variacionValor, 0, ',', '.') }}
                                            <span style="color: green; font-family: 'DejaVu Sans', sans-serif;">▲</span>
                                        @elseif ($variacion < 0)
                                            ({{ ltrim(number_format($variacionValor, 0, ',', '.'), '-') }})
                                            <span style="color: red; font-family: 'DejaVu Sans', sans-serif;">▼</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @php
                        // Plantilla cruda MENSAJE DE LAS NOTAS
                        $plantilla = $mensajes['11'] ?? '';
                        // Sustituciones
                        $reemplazos = [
                            '{SALTO_PAGINA}' => '<br>',
                        ];

                        // Compilar mensaje
                        $mensajeCompilado = strtr($plantilla, $reemplazos);
                    @endphp
                    @if($sinIngresos)
                            <p>La empresa no cuenta con este rubro</p>
                    @else
                            {{-- Usa {!! !!} para que Blade no escape el HTML de SALTO_PAGINA --}}
                            {!! $mensajeCompilado !!} <br>
                    @endif
                   
                </div>
                <br>
                <br>
                {{--gastos--}}
                 {{-- Agrupación para "Gastos" (una única tabla para todas las cuentas) --}}
                <div class="grid-container">
                    <div class="grid-item titulos"><b>NOTA {{$gastos}}: GASTOS DE ADMINISTRACIÓN Y VENTAS</b></div>
                
                    <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse;"
                        class="custom-border-table">
                        <thead>
                            <tr>
                                <th class="sizenotas"><strong>Partida</strong></th>
                                <th class="sizenotas" style="text-align: right;">{{ $dia_numero . '/' . $mes3 .'/'. $anio }}</th>
                                <th class="sizenotas" style="text-align: right;">{{ $dia_numero . '/' . $mes3 .'/'. $anioAnterior  }}</th>
                                <th class="sizenotas" style="text-align: right;">VAR %</th>
                                <th class="sizenotas" style="text-align: right;">VARIACIÓN $</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                // Calcular los totales sumando "Gastos"
                                $totalAño1Gastos = 
                                    abs($informeData['Gastos de administración'][$anio]) + 
                                    abs($informeData['Gastos de ventas'][$anio])+ 
                                    abs($informeData['Otros gastos'][$anio])+ 
                                    abs($informeData['Gastos financieros'][$anio]);

                                $totalAño2Gastos = 
                                    abs($informeData['Gastos de administración'][$anioAnterior]) + 
                                    abs($informeData['Gastos de ventas'][$anioAnterior])+ 
                                    abs($informeData['Otros gastos'][$anioAnterior])+ 
                                    abs($informeData['Gastos financieros'][$anioAnterior]);
                                $singastos = ($totalAño1Gastos == 0 && $totalAño2Gastos == 0);
                            @endphp
                            @foreach ([
                                'Gastos de administración' => 'NOTA 29',
                                'Gastos de ventas' => 'NOTA 29',
                                'Otros gastos' => 'NOTA 29',
                                'Gastos financieros' => 'NOTA 29',
                            ] as $key => $nota)
                                <tr>
                                    @php
                                        // Evitar división por cero
                                        if ($informeData[$key][$anio] == 0 || $informeData[$key][$anioAnterior] == 0) {
                                            $variacionPorcentaje1 = '0%';
                                        } else {
                                            $variacionPorcentaje1 = number_format((($informeData[$key][$anio] / $informeData[$key][$anioAnterior]) - 1) * 100, 2) . '%';
                                        }
                                        $variacionValor1 = abs($informeData[$key][$anio]) - abs($informeData[$key][$anioAnterior]);
                                    @endphp
                                    {{-- Omitir si ambos años son 0 --}}
                                    @if ($informeData[$key][$anio] == '0' && $informeData[$key][$anioAnterior] == '0')
                                        @continue
                                    @endif
                                    <td class="sizenotas">
                                        @php
                                            $descripcion = $informeData[$key]['descripcionct'] == 'Gastos impuesto de renta y cree'
                                                ? 'Gastos impuesto a las ganancias'
                                                : $informeData[$key]['descripcionct'];
                                        @endphp
                                        {{ $descripcion }}
                                    </td>
                                    <td class="sizenotas" style="text-align: right">
                                        {{ ($informeData[$key][$anio] ?? 0) == 0 
                                            ? '-' 
                                            : ($informeData[$key][$anio] < 0 
                                                ? '(' . number_format(ltrim($informeData[$key][$anio], '-'), 0, '.', ',') . ')' 
                                                :  number_format($informeData[$key][$anio], 0, '.', ',')) 
                                        }}
                                    </td>
                                    <td class="sizenotas" style="text-align: right">
                                        {{ ($informeData[$key][$anioAnterior] ?? 0) == 0 
                                            ? '-' 
                                            : ($informeData[$key][$anioAnterior] < 0 
                                                ? '(' . number_format(ltrim($informeData[$key][$anioAnterior], '-'), 0, '.', ',') . ')' 
                                                :  number_format($informeData[$key][$anioAnterior], 0, '.', ',')) 
                                        }}
                                    </td>

                                    <td class="sizenotas" style="text-align: right">
                                        @php
                                            $varRaw = trim($variacionPorcentaje1);
                                            $varNumerico = (float)str_replace(['%', ','], '', $varRaw);
                                            $varNumeric = is_numeric($varNumerico)
                                                ? number_format(round($varNumerico), 0, ',', '.') 
                                                : '-';
                                        @endphp

                                        @if (in_array($varRaw, ['0', '0.00', '0.00%','0%','']))
                                            -
                                        @elseif ($varNumeric < 0)
                                            ({{ ltrim($varRaw, '-') }})
                                        @else
                                            {{ $varRaw }}
                                        @endif
                                    </td>
                                    @php
                                        $variacion = (float)str_replace(',', '', $variacionValor1);
                                    @endphp
                                    <td class="sizenotas" style="text-align: right">
                                        
                                        @if ($variacion > 0)
                                        {{ number_format($variacionValor1, 0, ',', '.') }}
                                            <span style="color: green; font-family: 'DejaVu Sans', sans-serif;">▲</span>
                                        @elseif ($variacion < 0)
                                            ({{ ltrim(number_format($variacionValor1, 0, ',', '.'), '-') }})
                                            <span style="color: red; font-family: 'DejaVu Sans', sans-serif;">▼</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    
                                </tr>
                            @endforeach
                            <tr>
                                @php
                                    // Evitar división por cero
                                    if ($totalAño1Gastos == 0 || $totalAño2Gastos == 0) {
                                        $variacionPorcentaje = '0%';
                                    } else {
                                        $variacionPorcentaje = number_format((($totalAño1Gastos / $totalAño2Gastos) - 1) * 100, 2) . '%';
                                    }
                                    $variacionValor = $totalAño1Gastos - $totalAño2Gastos;
                                @endphp
                                <td class="sizenotas"><strong>Total Gastos</strong></td>
                                <td class="sizenotas" style="text-align: right">
                                    <strong>
                                        {{ ($totalAño1Gastos ?? 0) == 0 
                                            ? '-' 
                                            : ($totalAño1Gastos < 0 
                                                ? '(' . number_format(ltrim($totalAño1Gastos, '-'), 0, ',', '.') . ')' 
                                                : number_format($totalAño1Gastos, 0, ',', '.')) 
                                        }}
                                    </strong>
                                </td>

                                <td class="sizenotas" style="text-align: right">
                                    <strong>
                                        {{ ($totalAño2Gastos ?? 0) == 0 
                                            ? '-' 
                                            : ($totalAño2Gastos < 0 
                                                ? '(' . number_format(ltrim($totalAño2Gastos, '-'), 0, ',', '.') . ')' 
                                                : number_format($totalAño2Gastos, 0, ',', '.')) 
                                        }}
                                    </strong>
                                </td>

                                <td class="sizenotas" style="text-align: right"><strong>
                                    @php
                                        $varRaw = trim($variacionPorcentaje );
                                        $varNumerico = (float)str_replace(['%', ','], '', $varRaw);
                                        $varNumeric = is_numeric($varNumerico)
                                                ? number_format(round($varNumerico), 0, ',', '.') 
                                                : '-';
                                    @endphp

                                    @if (in_array($varRaw, ['0', '0.00', '0.00%','0%','']))
                                        -
                                    @elseif ($varNumeric < 0)
                                        ({{ ltrim($varRaw, '-') }})
                                    @else
                                        {{ $varRaw }}
                                    @endif
                                
                                </strong></td>
                                
                                @php
                                    $variacion = (float)str_replace(',', '', $variacionValor);
                                @endphp
                                <td class="sizenotas" style="text-align: right">
                                    
                                    @if ($variacion > 0)
                                    <strong>${{ number_format($variacionValor, 0, ',', '.') }}</strong>
                                        <span style="color: green; font-family: 'DejaVu Sans', sans-serif;">▲</span>
                                    @elseif ($variacion < 0)
                                        <strong> (${{ ltrim(number_format($variacionValor, 0, ',', '.'), '-') }})</strong>
                                        <span style="color: red; font-family: 'DejaVu Sans', sans-serif;">▼</span>
                                    @else
                                        <strong> -</strong>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    @php
                        // Plantilla cruda MENSAJE DE LAS NOTAS
                        $plantilla = $mensajes['12'] ?? '';
                        // Sustituciones
                        $reemplazos = [
                            '{SALTO_PAGINA}' => '<br>',
                        ];

                        // Compilar mensaje
                        $mensajeCompilado = strtr($plantilla, $reemplazos);
                    @endphp
                    @if($sinIngresos)
                            <p>La empresa no cuenta con este rubro</p>
                    @else
                            {{-- Usa {!! !!} para que Blade no escape el HTML de SALTO_PAGINA --}}
                            {!! $mensajeCompilado !!} <br>
                    @endif
                </div>
                <br>
                <br>
                {{-- Resultado --}}
                @php
                    $notasBloque1 = [
                        'Ingresos de actividades ordinarias' => '27',
                    ];
                    $bloques1 = array_keys($notasBloque1);
                    $contadornotainicial = empty($notasParaMostrar) ? 3 : max($notasParaMostrar);
                    $resultado1 = generarNotasDesde($bloques1, $informeData, $anio, $anioAnterior, $notasBloque1, $contadornotainicial);
                    $notasParaMostrarBloques = $resultado1['notas'];
                    $ultimoNumero = $resultado1['ultimo'];
                @endphp
                <div class="grid-container">
                    <div class="grid-item titulos"><b>NOTA {{$gastos+1}}: INGRESOS Y GASTOS FINANCIEROS</b></div>
                    
                    @php
                        // Calcular los totales sumando "Otros ingresos" e "Ingresos financieros"
                        $totalAño1OtrosIngresos = 
                            abs($informeData['Otros ingresos'][$anio]) + 
                            abs($informeData['Ingresos financieros'][$anio]);

                        $totalAño2OtrosIngresos = 
                            abs($informeData['Otros ingresos'][$anioAnterior]) + 
                            abs($informeData['Ingresos financieros'][$anioAnterior]);
                        $sinIngresos = ($totalAño1OtrosIngresos == 0 && $totalAño2OtrosIngresos == 0);
                    @endphp
                    @if (!$sinIngresos)
                        <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse;"
                            class="custom-border-table">
                            <thead>
                                <tr>
                                    <th class="sizenotas"><strong>Partida</strong></th>
                                    <th class="sizenotas" style="text-align: right;">{{ $dia_numero . '/' . $mes3 .'/'. $anio }}</th>
                                <th class="sizenotas" style="text-align: right;">{{ $dia_numero . '/' . $mes3 .'/'. $anioAnterior  }}</th>
                                    <th class="sizenotas" style="text-align: right;"><strong>VAR %</strong></th>
                                    <th class="sizenotas" style="text-align: right;"><strong>VARIACIÓN $</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ([
                                    'Otros ingresos' => 'NOTA 27',
                                    'Ingresos financieros' => 'NOTA 27',
                                ] as $key => $nota)
                                    @php
                                        $total1 = $informeData[$key][$anio];
                                        $total2 = $informeData[$key][$anioAnterior];
                                    @endphp
                                    {{-- Omitir si ambos años son 0 --}}
                                    @if ($total1 == '0' && $total2 == '0')
                                        @continue
                                    @endif
                                    <tr>
                                        <td class="sizenotas">
                                            {{ $informeData[$key]['descripcionct'] }}
                                        </td>
                                        <td class="sizenotas" style="text-align: right">
                                            {{ ($informeData[$key][$anio] ?? 0) == 0 
                                                ? '-' 
                                                : '$' . number_format(abs($informeData[$key][$anio]), 0, '.', ',') 
                                            }}
                                        </td>

                                        <td class="sizenotas" style="text-align: right">
                                            {{ ($informeData[$key][$anioAnterior] ?? 0) == 0 
                                                ? '-' 
                                                : '$' . number_format(abs($informeData[$key][$anioAnterior]), 0, '.', ',') 
                                            }}
                                        </td>


                                        <td class="sizenotas" style="text-align: right">
                                            {{-- {{ number_format($informeData[$key]['var%'], 2, ',', '.')}}% --}}
                                            @php
                                                $total1 = floatval(str_replace(',', '', $informeData[$key][$anio]));
                                                $total2 = floatval(str_replace(',', '', $informeData[$key][$anioAnterior]));
                                                $variacion = abs($total1) - abs($total2);
                                                $varRawtotal = ($total2 != 0) ? round((($total1 / $total2) - 1) * 100, 0) : null;
                                                $varRaw = is_numeric($varRawtotal)
                                                ? number_format(round($varRawtotal), 0, ',', '.') 
                                                : '-';
                                            @endphp
                                            @if (in_array($varRaw, ['0', '0.00', '0.00%','0%','']))
                                                -
                                            @elseif ($varRaw < 0)
                                                ({{ ltrim($varRaw, '-') }}%)
                                            @else
                                                {{ $varRaw }}%
                                            @endif
                                        </td>
                                        <td class="sizenotas" style="text-align: right">
                                            @if ($variacion > 0)
                                                {{ number_format($variacion, 0, '.', ',') }}
                                                <span style="color: green; font-family: 'DejaVu Sans', sans-serif;">▲</span>
                                            @elseif ($variacion < 0)
                                                ({{ ltrim(number_format($variacion, 0, '.', ',') , '-') }})
                                                <span style="color: red; font-family: 'DejaVu Sans', sans-serif;">▼</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    @php
                                        $variacionValor = $totalAño1OtrosIngresos - $totalAño2OtrosIngresos;
                                        // Evitar división por cero
                                        if ($totalAño1OtrosIngresos == 0 || $totalAño2OtrosIngresos == 0) {
                                            $variacionPorcentaje = '0%';
                                        } else {
                                            $variacionPorcentaje = ($totalAño2OtrosIngresos != 0) ? round((($totalAño1OtrosIngresos / $totalAño2OtrosIngresos) - 1) * 100, 0) : null;

                                            // $variacionPorcentaje = number_format((($totalAño1OtrosIngresos / $totalAño2OtrosIngresos) - 1) * 100, 2) . '%';
                                        }

                                        $variacionValorFormateado = number_format($variacionValor, 0, ',', '.');
                                    @endphp
                                    <td class="sizenotas"><strong>Total Ingresos</strong></td>
                                    <td class="sizenotas" style="text-align: right">
                                        <strong>{{ ($totalAño1OtrosIngresos ?? 0) == 0 ? '-' : (($totalAño1OtrosIngresos < 0) ? '($' . number_format(ltrim($totalAño1OtrosIngresos, '-'), 0, ',', '.') . ')' : '$' . number_format($totalAño1OtrosIngresos, 0, ',', '.')) }}</strong>
                                    </td>
                                    <td class="sizenotas" style="text-align: right">
                                        <strong> {{ ($totalAño1OtrosIngresos ?? 0) == 0 ? '-' : (($totalAño2OtrosIngresos < 0) ? '($' . number_format(ltrim($totalAño2OtrosIngresos, '-'), 0, ',', '.') . ')' : '$' . number_format($totalAño2OtrosIngresos, 0, ',', '.')) }}</strong>
                                    </td>
                                    <td class="sizenotas" style="text-align: right"><strong>
                                        @php
                                            $varRaw = trim($variacionPorcentaje);
                                            $varNumerico = (float)str_replace(['%', ','], '', $varRaw);
                                            $varNumeric = is_numeric($varNumerico)
                                                ? number_format(round($varNumerico), 0, ',', '.') 
                                                : '-';
                                        @endphp
                                        @if (in_array($varRaw, ['0', '0.00', '0.00%','']))
                                            -
                                        @elseif ($varNumeric < 0)
                                            ({{ ltrim($varRaw, '-') }})
                                        @else
                                            {{ $varRaw }}
                                        @endif
                                    </strong></td>
                                        @php
                                            $variacion = (float)str_replace(',', '', $variacionValorFormateado);
                                        @endphp
                                        <td class="sizenotas" style="text-align: right">
                                            @if ($variacionValorFormateado > 0)
                                                <strong> ${{ $variacionValorFormateado }}</strong>
                                                <span style="color: green; font-family: 'DejaVu Sans', sans-serif;">▲</span>
                                            @elseif ($variacionValorFormateado < 0)
                                                <strong> (${{ ltrim($variacionValorFormateado, '-') }})</strong>
                                                <span style="color: red; font-family: 'DejaVu Sans', sans-serif;">▼</span>
                                            @else
                                                <strong> -</strong>
                                                
                                            @endif
                                        </td>
                                </tr>
                            </tbody>
                        </table>
                    @endif
                   @php
                        // Plantilla cruda MENSAJE DE LAS NOTAS
                        $plantilla = $mensajes['13'] ?? '';
                        // Sustituciones
                        $reemplazos = [
                            '{SALTO_PAGINA}' => '<br>',
                        ];

                        // Compilar mensaje
                        $mensajeCompilado = strtr($plantilla, $reemplazos);
                        // Poner en negrita todas las palabras completamente en mayúscula (mínimo 2 letras para evitar "Y", "A", etc.)
                        $mensajeCompilado = preg_replace_callback('/\b([A-ZÁÉÍÓÚÑ]{2,})\b/u', function ($matches) {
                            return '<strong>' . $matches[1] . '</strong>';
                        }, $mensajeCompilado);
                    @endphp
                    @if($sinIngresos)
                            <p>La empresa no cuenta con este rubro</p>
                    @else
                            {{-- Usa {!! !!} para que Blade no escape el HTML de SALTO_PAGINA --}}
                            {!! $mensajeCompilado !!} <br>
                    @endif
                </div>
            @endif
        </div>
        <style>
            /* Aplica margen solo en la primera página para dar espacio al bloque de firmas */
            @page:first {
                margin-top: 100px; /* Ajusta el valor según el espacio necesario para las imágenes */
            }
        </style>
        <div class="tabla-integrales" style="font-size: 10px; margin: 0; padding: 2px !importante;">
            <div class="firmas">
                <!-- Firma Representante Legal -->
                <div class="firma" style="text-align: center;">
                    @if ($representantelegalfirma != '*')
                        <div class="firma2">
                            <img src="data:image/jpeg;base64,{{ $representantelegalfirma }}" alt="Firma Representante Legal">
                        </div>
                    @else
                        <div class="firma2 no-image"></div>
                    @endif
                    <div class="firmatexto" style="color:black; text-align: center; margin-top: 0;">
                        <strong>{{ $representantelegal['representantelegal'] ?? 'Sin datos encontrados' }}</strong><br>
                        Representante legal<br>C.C. {{ $representantelegal['Cedula'] ?? '' }}
                    </div>
                </div>
            
                <!-- Firma Contador -->
                <div class="firma" style="text-align: center;">
                    @if ($base64Imagefirmacontador != '*')
                        <div class="firma2">
                            <img src="data:image/jpeg;base64,{{ $base64Imagefirmacontador }}" alt="Firma Contador">
                        </div>
                    @else
                        <div class="firma2 no-image"></div>
                    @endif
                    <div class="firmatexto" style="color:black; text-align: center; margin-top:0;">
                        <strong>{{ ($datoscontador['nombres'] ?? 'Sin datos') . ' ' . ($datoscontador['apellidos'] ?? '') }}</strong><br>
                        Contador Público<br>T.P {{ $datoscontador['tarje_profesional'] ?? '' }}
                    </div>
                </div>
            
                <!-- Firma Revisor Fiscal -->
                @if (!empty($representantelegal['revisorfiscal']) && $representantelegal['revisorfiscal'] !== 'Sin datos encontrados')
                    <div class="firma" style="text-align: center;">
                        @if ($revisorfiscalfirma != '*')
                            <div class="firma2">
                                <img src="data:image/jpeg;base64,{{ $revisorfiscalfirma }}" alt="Firma Revisor Fiscal">
                            </div>
                        @else
                            <div class="firma2 no-image"></div>
                        @endif
                        <div class="firmatexto" style="color:black; text-align: center; margin-top:0;">
                            <strong>{{ $representantelegal['revisorfiscal'] }}</strong><br>
                            Revisor Fiscal<br>T.P {{ $representantelegal['cedularevisor'] ?? '' }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
             
        </div>
          <!-- Pie de página -->
        {{-- <div class="footer">
            <img src="https://clasedeexcel.com/imagenespdfs/piepagina-erikalopez.png">
            <div class="footer-text">
                <p><a href="gerencia@erikalopezsas.com" target="_blank"></a></p>
            </div>
        </div> --}}
    </div>
   
  
</body>

</html>
