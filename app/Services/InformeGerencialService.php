<?php

namespace App\Services;

use App\Models\Clientes;
use App\Models\ContapymeCompleto;
use App\Models\CuentaBancaria;
use App\Models\Empresa;
use App\Models\InformesGenericos;
use Barryvdh\Debugbar\Facades\Debugbar;
use Barryvdh\Debugbar\Twig\Extension\Debug;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Throwable;

/**
 * InformeGerencialService
 * Servicio para el manejo de los informes gerenciales de las empresas
 * Se encarga de calcular las cuentas y enviarlas
 * a la IA para el analisis de indicadores financieros
 * 
 * @package App\Services
 */
class InformeGerencialService
{

    protected $markdownService;
    private $tiposGenericos = [
        'WORLD',
        'PYME',
        'WIMAX',
        'GENERICO',
        'ILIMITADA',
        'MILENIUM',
        'ALEGRA',
        'SAG'
    ];

    private const CHECK_DEVOLUCIONES = '1';
    private const CHECK_INGRESOS_OPERACIONALES = '2';
    private const CHECK_GASTOS = '3';
    private const CHECK_IVA = '4';
    private const CHECK_COSTO_VENTAS = '5';
    private const CHECK_CARTERA = '6';
    private const CHECK_COSTO_PRODUCCION = '7';

    public function __construct(MarkdownService $markdownService)
    {
        $this->markdownService = $markdownService;
    }

    /**
     * CalcularCuentas:
     * Calcula las cuentas de un informe gerencial
     * 
     * @param  array $informeQuery
     * @param  string $fecha_inicio
     * @param  string $fecha_fin
     * @param  string $nit
     */
    public function CalcularCuentas($informeQuery, $fecha_inicio, $fecha_fin, $nit, $checklist = [])
    {
        Debugbar::info($informeQuery);

        // dd($checklist);

        // Obtener informacion de la empresa por NIT
        $empresa = Empresa::where('NIT', $nit)->select('tipo', 'razon_social', 'logocliente')->first();
        // Obtener cuentas bancarias de la empresa en el periodo
        $cuentasBancarias = $this->cuentasBancarias($empresa, $fecha_inicio, $fecha_fin);

        //░█▀▀░█▀▄░█▀█░█▀▀░▀█▀░█▀▀░█▀█░█▀▀
        //░█░█░█▀▄░█▀█░█▀▀░░█░░█░░░█░█░▀▀█
        //░▀▀▀░▀░▀░▀░▀░▀░░░▀▀▀░▀▀▀░▀▀▀░▀▀▀
        $graficosData = $this->obtenerDatosGraficos($nit, $fecha_inicio, $fecha_fin, $empresa->tipo);
        //GRAFICOS
        $devolucionesGrafData = in_array(self::CHECK_DEVOLUCIONES, $checklist) ? $graficosData['devoluciones'] : []; //1
        $ingresosOperacionalesGrafData = in_array(self::CHECK_INGRESOS_OPERACIONALES, $checklist) ? $graficosData['ingresos_operacionales'] : []; //2
        $gastosGrafData = in_array(self::CHECK_GASTOS, $checklist) ? $graficosData['gastos'] : []; //3
        $ivaGrafData = in_array(self::CHECK_IVA, $checklist) ? $graficosData['iva'] : []; //4
        $costoVentasGrafData = in_array(self::CHECK_COSTO_VENTAS, $checklist) ? $graficosData['costoVentas'] : []; //5
        $carteraGrafData = in_array(self::CHECK_CARTERA, $checklist) ? $graficosData['cartera'] : []; //6
        $costoProduccionGrafData = in_array(self::CHECK_COSTO_PRODUCCION, $checklist) ? $graficosData['costoProduccion'] : []; //7
        //░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░


        $costoFinancieroNeto = $this->costoFinancieroNeto($informeQuery); //costo financiero neto         
        $totalActivoCorriente = $this->totalActivoCorriente($empresa, $nit, $fecha_fin); //calcular total activo corriente        
        $totalActivoNoCorriente = $this->totalActivoNoCorriente($informeQuery); //total activo no corriente        
        $totalActivo = $this->totalActivo($empresa, $nit, $fecha_inicio, $fecha_fin, $informeQuery); //total activo         
        $totalPasivoCorriente = $this->totalPasivoCorriente($empresa, $nit, $fecha_fin); //total pasivo corriente        
        $totalPasivoNoCorriente = $this->totalPasivoNoCorriente($informeQuery); //total pasivo no corriente        
        $totalPasivo = $this->totalPasivo($empresa, $nit, $fecha_inicio, $fecha_fin, $informeQuery); //total pasivo        
        $inventarios  = abs($this->getUniqValue($informeQuery, '14')); //inventarios 14   
        $devoluciones = abs($this->getUniqValue($informeQuery, '4175')); //devoluciones 4175    


        // ░█▀▄░█▀▀░█▀▀░█▀▀░█▄█░█▀█░█▀▀░█▀█░█▀█
        // ░█░█░█▀▀░▀▀█░█▀▀░█░█░█▀▀░█▀▀░█░█░█░█
        // ░▀▀░░▀▀▀░▀▀▀░▀▀▀░▀░▀░▀░░░▀▀▀░▀░▀░▀▀▀
        //ingresos
        $ingresos     = $this->calcularArrayCuentasSaldoFinal($empresa, $nit, $fecha_fin, ['41']); //ingresos cuenta 41
        //costo de ventas las que empiezan por 6 y 7
        $cuenta6 =  $this->calcularArrayCuentasSaldoFinal($empresa, $nit, $fecha_fin, ['6']);
        $cuenta7 = $this->calcularArrayCuentasSaldoFinal($empresa, $nit, $fecha_fin, ['7']);
        $costoVentas = $cuenta6 + $cuenta7;
        //utilidad bruta      
        $utilidadBruta = $ingresos - $costoVentas;
        //gastos administracion
        $gastosAdministracion = $this->calcularArrayCuentasSaldoFinal($empresa, $nit, $fecha_fin, ['51']); //gastos de administracion sumar cuentas 5 y 51
        //gastos de ventas
        $gastosVentas = abs($this->getUniqValue($informeQuery, '52'));
        //otros gastos 
        $otrosGastos = $this->calcularArrayCuentasSaldoFinal($empresa, $nit, $fecha_fin, ['53']); //otros gastos 53
        $cuenta42 = $this->calcularArrayCuentasSaldoFinal($empresa, $nit, $fecha_fin, ['42']);
        $cuenta54 = $this->calcularArrayCuentasSaldoFinal($empresa, $nit, $fecha_fin, ['54']);
        //utilidad neta
        $utilidadNeta = ($utilidadBruta - $gastosAdministracion - $gastosVentas - $otrosGastos) + $cuenta42 - $cuenta54; //utilidad neta cuenta 4 - cuenta 6 - cta51- cta52 - cta53 + cuenta42 - cuenta54
        // dd('utilidadBruta: ' . $utilidadBruta, 'gastosAdministracion: ' . $gastosAdministracion, 'gastosVentas: ' . $gastosVentas, 'otrosGastos: ' . $otrosGastos, 'cuenta42: ' . $cuenta42, 'cuenta54: ' . $cuenta54, 'utilidadNeta: ' . $utilidadNeta);



        //operativos y no operativos
        $operativos = abs($this->getUniqValue($informeQuery, '41')); //operativos 41        
        $noOperativos = abs($this->getUniqValue($informeQuery, '42')); //no operativos 42        
        $utilidad = ($ingresos - $devoluciones) - $costoVentas; //utilidad        
        $ganancia = ($ingresos - $costoVentas - $gastosVentas - $otrosGastos); //ganacia     
        //░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░   


        // ░▀█▀░█░█░█▀█
        // ░░█░░▀▄▀░█▀█
        // ░▀▀▀░░▀░░▀░▀
        //::::::::: EL TOTAL DE EL IVA SE ESTA OBTENIENDO EN LA VISTA 
        //generado
        $generado = $this->ivaGenerado($informeQuery);
        //descontable
        $descontable = $this->ivaDescontable($informeQuery);

        //total iva
        if ($generado >= $descontable) {
            $totalIva = $generado - $descontable;
        } else {
            $totalIva =  $descontable - $generado;
        }

        $reteIva = in_array(self::CHECK_IVA, $checklist) ? $this->reteIva($informeQuery) : 0; //reteiva 2367
        $retefuente = $this->retefuente($informeQuery); //retefuente 2365 + 2367
        $impuestosDian = $this->impuestosDian($informeQuery); //impuestosDian 135517

        $impuestoConsumo = $this->impuestoConsumo($nit, $fecha_inicio, $fecha_fin, $empresa->tipo); //impuesto al consumo 240809        

        //░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░



        $utilidadPerdidaOperativa = ceil($utilidadBruta - $gastosVentas - $gastosAdministracion); //utilidad Neta cuenta 4 - cuenta 6 - cta51- cta52 - cta53        
        $totalgastos = $this->totalGastos($informeQuery); //total gastos 53 - 5305        
        $gastosfinancieros = $this->getUniqValue($informeQuery, '5305'); //gastos financieros 5305
        //utilidad operativa
        $utilidadOperativa = ceil($utilidadPerdidaOperativa + $cuenta42 - $totalgastos - $gastosfinancieros);


        $salidas = $cuenta6 + $gastosAdministracion + $gastosVentas + $otrosGastos; //salidas sumar salidas 6 + 51 + 52 + 53    
        // dd('cuenta6 '.$cuenta6, 'gastosAdministracion '.$gastosAdministracion, 'gastosVentas '.$gastosVentas, 'otrosGastos '.$otrosGastos, 'salidas '.$salidas);  
        //mensaje de utilidad o perdida según el resultado
        $mensajeUtilidad = 'Utilidad neta';
        //si la salida es mayor que la utilidad neta
        if ($salidas > $ingresos) {
            $mensajeUtilidad = 'Pérdida neta';
        }


        //total patrimonio
        $totalPatrimonio = $this->totalPatrimonio($empresa, $nit, $fecha_inicio, $fecha_fin, $informeQuery, $empresa->tipo);



        //░▀█▀░█▀█░█▀▄░▀█▀░█▀▀░█▀█░█▀▄░█▀█░█▀▄░█▀▀░█▀▀
        //░░█░░█░█░█░█░░█░░█░░░█▀█░█░█░█░█░█▀▄░█▀▀░▀▀█
        //░▀▀▀░▀░▀░▀▀░░▀▀▀░▀▀▀░▀░▀░▀▀░░▀▀▀░▀░▀░▀▀▀░▀▀▀
        $depreciacion =  abs($this->getUniqValue($informeQuery, '5160')); //depreciacion
        $amortizacion = $this->calcularAmortizacion($nit, $fecha_fin, $empresa->tipo); //amortizacion
        //EBITDA
        $EBITDA = ($utilidadNeta + $depreciacion + $amortizacion);
        //capital de trabajo    
        $capitalTrabajo = ($totalActivoCorriente - $totalPasivoCorriente);
        //margen de ganacia   
        $margenGanaciaNeta = ($ingresos != 0) ? ($utilidadBruta / $ingresos) * 100 : 0;
        //ROA
        $ROA = ($totalActivo != 0) ? ($utilidadNeta / $totalActivo) * 100 : 0;
        //ROE
        $ROE = ($totalPatrimonio != 0) ? ($utilidadNeta   / ($utilidadNeta + $totalPatrimonio)) * 100 : 0;
        //liquidez corriente
        $liquidezCorriente = ($totalPasivoCorriente != 0) ? ($totalActivoCorriente / $totalPasivoCorriente)  : 0;
        //prueba acida
        $pruebaAcida = ($totalPasivoCorriente != 0) ? (($totalActivoCorriente - $inventarios) / $totalPasivoCorriente) : 0;
        //nivel de endeudamiento
        $nivelEndeudamiento = ($totalActivo != 0) ? ($totalPasivo / $totalActivo) * 100 : 0; //nivel de endeudamiento
        //░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░



        //gastos totales
        $gastosTotales = abs($this->getUniqValue($informeQuery, '5'));

        //Data para el analisis de indicadores financieros con la IA
        $dataIA = [
            'EBITDA' => $EBITDA,
            'Capital de Trabajo' => $capitalTrabajo,
            'Margen de Ganancia' => $margenGanaciaNeta,
            'ROA' => $ROA,
            'ROE' => $ROE,
            'Liquidez Corriente' => $liquidezCorriente,
            'Prueba Acida' => $pruebaAcida,
            'Nivel de Endeudamiento' => $nivelEndeudamiento
        ];

        $resultadoAnalisis = $this->analisisIndicadoresFinancierosIA($dataIA);


        $user = Auth::user(); //obtener usuario logeado
        $firma = $user->firma; //obtener firma del usuario
        $nombreCompleto = $user->nombres . ' ' . $user->apellidos; //obtener nombre completo del usuario
        $rol = $user->role->title; //obtener rol del usuario

        $data = [];
        //mapear con los nombres de las cuentas
        $data = collect([
            round($utilidadBruta), //0
            ceil($utilidadOperativa), //1
            round($costoFinancieroNeto), //2
            abs(round($totalActivoCorriente)), //3
            abs(round($totalActivoNoCorriente)), //4
            round($totalActivo), //5
            abs(round($totalPasivoCorriente)), //6
            abs(round($totalPasivoNoCorriente)), //7
            round($totalPasivo), //8
            round($totalPatrimonio), //9
            abs(round($inventarios)), //10
            abs(round($depreciacion)), //11
            abs(round($amortizacion)), //12
            abs(round($ingresos)), //13
            abs(round($costoVentas)), //14
            round($utilidadNeta), //15
            round($margenGanaciaNeta, 2), //16
            round($ROA, 2), //17
            round($ROE, 2), //18
            round($EBITDA), //19
            round($liquidezCorriente, 2), //20
            round($capitalTrabajo), //21
            round($pruebaAcida, 2), //22
            round($nivelEndeudamiento, 2), //23
            abs(round($devoluciones)), //24
            abs(round($gastosVentas)), //25
            round($otrosGastos), //26
            round($gastosAdministracion), //27
            abs(round($operativos)), //28
            abs(round($noOperativos)), //29
            abs(round($generado)), //30
            abs(round($descontable)), //31
            round($totalIva), //32
            round($impuestoConsumo), //33
            abs(round($gastosTotales)), //34
            $mensajeUtilidad, //35
            $resultadoAnalisis, //36
            $fecha_inicio, //37
            $fecha_fin, //38
            $empresa->razon_social, //39
            $empresa->tipo, //40
            $empresa->logocliente, //41
            $nit, //42
            $firma, //43 
            $nombreCompleto, //44
            $rol, //45
            $cuentasBancarias, //46
            $devolucionesGrafData, //47
            $ingresosOperacionalesGrafData, //48
            $gastosGrafData, //49
            $ivaGrafData, //50
            $carteraGrafData, //51
            $costoVentasGrafData, //52
            $costoProduccionGrafData, //53
            abs(round($impuestosDian)), //54
            abs(round($retefuente)), //55
            abs(round($reteIva)), //56
        ])->map(function ($item) {
            return ['total_mes' => $item];
        });

        Debugbar::info($data->toArray());

        return $data;
    }

    /**
     * Obtener datos para gráficos según el tipo de empresa
     * 
     * @param string $nit
     * @param string $fecha_inicio
     * @param string $fecha_fin
     * @param string $tipoEmpresa
     * @return array
     */
    private function obtenerDatosGraficos($nit, $fecha_inicio, $fecha_fin, $tipoEmpresa)
    {
        $datos = [
            'devoluciones' => [],
            'ingresos_operacionales' => [],
            'gastos' => [],
            'iva' => [],
            'valor_generado' => [],
            'valor_compras' => []

        ];

        if ($tipoEmpresa === 'NUBE') {
            $datos = $this->obtenerDatosGraficosNube($nit, $fecha_inicio, $fecha_fin);
        } elseif ($tipoEmpresa === 'CONTAPYME') {
            $datos = $this->obtenerDatosGraficosContapyme($nit, $fecha_inicio, $fecha_fin);
        } elseif ($tipoEmpresa === 'PYME') {
            $datos = $this->obtenerDatosGraficosPyme($nit, $fecha_inicio, $fecha_fin);
        } elseif (in_array($tipoEmpresa, $this->tiposGenericos)) {
            $datos = $this->obtenerDatosGraficosGenericos($nit, $fecha_inicio, $fecha_fin);
        }

        return $datos;
    }


    private function consultarIvaPersonalizado($nit, $fecha_inicio, $fecha_fin, $tipo)
    {
        $data = [];

        if ($tipo === 'generado') {
            $empresa = Empresa::select(
                'iva_generado_codigo_1',
                'iva_generado_codigo_2',
                'iva_generado_codigo_3'
            )
                ->where('NIT', $nit)
                ->first();
        } else {
            $empresa = Empresa::select(
                'iva_descontable_codigo_1',
                'iva_descontable_codigo_2',
                'iva_descontable_codigo_3',
                'iva_descontable_codigo_4'
            )
                ->where('NIT', $nit)
                ->first();
        }

        $data = $empresa ? array_values(array_filter($empresa->toArray(), function ($value) {
            return $value !== null && $value !== '';
        })) : [];

        return $data;
    }

    private function validarIvaPersonalizado($nit)
    {
        // Obtener una sola vez la empresa con los campos necesarios
        $empresa = Empresa::where('NIT', $nit)
            ->select(
                'iva_generado_codigo_1',
                'iva_generado_codigo_2',
                'iva_generado_codigo_3',
                'iva_descontable_codigo_1',
                'iva_descontable_codigo_2',
                'iva_descontable_codigo_3',
                'iva_descontable_codigo_4'
            )
            ->first();

        // Verificar si cada campo no es nulo (true/false)
        $cg1 = !is_null($empresa?->iva_generado_codigo_1);
        $cg2 = !is_null($empresa?->iva_generado_codigo_2);
        $cg3 = !is_null($empresa?->iva_generado_codigo_3);

        $cd1 = !is_null($empresa?->iva_descontable_codigo_1);
        $cd2 = !is_null($empresa?->iva_descontable_codigo_2);
        $cd3 = !is_null($empresa?->iva_descontable_codigo_3);
        $cd4 = !is_null($empresa?->iva_descontable_codigo_4);

        // Determinar si hay algún código generado o descontable
        $isCG = $cg1 || $cg2 || $cg3;
        $isCD = $cd1 || $cd2 || $cd3 || $cd4;

        // Variable final como string 'true' o 'false' si es necesario
        return ($isCG || $isCD) ? 'true' : 'false';
    }


    /**
     * Consultar datos base para las cuentas principales
     */
    private function consultarDatosBase($modelo, $nit, $fecha_inicio, $fecha_fin, $tipo)
    {
        $datos = [];


        $isIvaPersonalizado = $this->validarIvaPersonalizado($nit);
        Debugbar::info('Validación de IVA personalizado: ' . $isIvaPersonalizado);

        if ($tipo === 'nube') {
            Debugbar::info('Entró a NUBE');
            // Cuenta 41 (devoluciones e ingresos operacionales)
            $datos['cuenta_41'] = $modelo::where('Nit', $nit)
                ->where('codigo_cuenta_contable_ga', 41)
                ->whereBetween('fechareporte_ga', [$fecha_inicio, $fecha_fin])
                ->select('fechareporte_ga', 'movimiento_debito_ga', 'movimiento_credito_ga')
                ->orderBy('fechareporte_ga', 'asc')
                ->get();


            if ($isIvaPersonalizado === 'true') {

                $generado = $this->consultarIvaPersonalizado($nit, $fecha_inicio, $fecha_fin, 'generado');
                $descontable = $this->consultarIvaPersonalizado($nit, $fecha_inicio, $fecha_fin, 'descontable');


                $datos['iva_generado'] = Clientes::where('Nit', $nit)
                    ->whereIn('codigo_cuenta_contable_ga', $generado)
                    ->whereBetween('fechareporte_ga', [$fecha_inicio, $fecha_fin])
                    ->select('fechareporte_ga', 'movimiento_debito_ga', 'movimiento_credito_ga')
                    ->orderBy('fechareporte_ga', 'asc')
                    ->get();

                $datos['iva_descontable'] = Clientes::where('Nit', $nit)
                    ->whereIn('codigo_cuenta_contable_ga', $descontable)
                    ->whereBetween('fechareporte_ga', [$fecha_inicio, $fecha_fin])
                    ->select('fechareporte_ga', 'movimiento_debito_ga', 'movimiento_credito_ga')
                    ->orderBy('fechareporte_ga', 'asc')
                    ->get();

                // dd($generado, $descontable, $data['iva_generado'], $data['iva_descontable']);


            } else {
                // Cuenta 2408 (IVA)
                $datos['cuenta_2408'] = $modelo::where('Nit', $nit)
                    ->where('codigo_cuenta_contable_ga', 2408)
                    ->whereBetween('fechareporte_ga', [$fecha_inicio, $fecha_fin])
                    ->select('fechareporte_ga', 'movimiento_debito_ga', 'movimiento_credito_ga')
                    ->orderBy('fechareporte_ga', 'asc')
                    ->get();

                $datos['iva_generado'] = null;
                $datos['iva_descontable'] = null;
            }

            // Cuentas 6(costo de ventas)
            $datos['cuenta_6'] = $modelo::where('Nit', $nit)
                ->where('codigo_cuenta_contable_ga', 6)
                ->whereBetween('fechareporte_ga', [$fecha_inicio, $fecha_fin])
                ->select('fechareporte_ga', 'movimiento_debito_ga', 'movimiento_credito_ga')
                ->orderBy('fechareporte_ga', 'asc')
                ->get();

            // Cuentas 7(costo de produccion)
            $datos['cuenta_7'] = $modelo::where('Nit', $nit)
                ->where('codigo_cuenta_contable_ga', 7)
                ->whereBetween('fechareporte_ga', [$fecha_inicio, $fecha_fin])
                ->select('fechareporte_ga', 'movimiento_debito_ga', 'movimiento_credito_ga')
                ->orderBy('fechareporte_ga', 'asc')
                ->get();

            $datos['cuenta_1305'] = $modelo::where('Nit', $nit)
                ->where('codigo_cuenta_contable_ga', 1305)
                ->whereBetween('fechareporte_ga', [$fecha_inicio, $fecha_fin])
                ->select('fechareporte_ga', 'saldo_final_ga')
                ->orderBy('fechareporte_ga', 'asc')
                ->get();
        } elseif ($tipo === 'CONTAPYME') {
            Debugbar::info('Entró a CONTAPYME');
            // Cuenta 41 (devoluciones e ingresos operacionales)
            $datos['cuenta_41'] = $modelo::where('Nit', $nit)
                ->where('cuenta', 41)
                ->whereBetween('fechareporte', [$fecha_inicio, $fecha_fin])
                ->select('fechareporte', 'debitos', 'creditos')
                ->orderBy('fechareporte', 'asc')
                ->get();



            if ($isIvaPersonalizado === 'true') {

                $generado = $this->consultarIvaPersonalizado($nit, $fecha_inicio, $fecha_fin, 'generado');
                $descontable = $this->consultarIvaPersonalizado($nit, $fecha_inicio, $fecha_fin, 'descontable');


                $datos['iva_generado'] = Clientes::where('Nit', $nit)
                    ->whereIn('cuenta', $generado)
                    ->whereBetween('fechareporte', [$fecha_inicio, $fecha_fin])
                    ->select('fechareporte', 'debitos', 'creditos')
                    ->orderBy('fechareporte', 'asc')
                    ->get();

                $datos['iva_descontable'] = Clientes::where('Nit', $nit)
                    ->whereIn('cuenta', $descontable)
                    ->whereBetween('fechareporte', [$fecha_inicio, $fecha_fin])
                    ->select('fechareporte', 'debitos', 'creditos')
                    ->orderBy('fechareporte', 'asc')
                    ->get();

                // dd($generado, $descontable, $data['iva_generado'], $data['iva_descontable']);


            } else {
                // Cuenta 2408 (IVA)
                $datos['cuenta_2408'] = $modelo::where('Nit', $nit)
                    ->where('cuenta', 2408)
                    ->whereBetween('fechareporte', [$fecha_inicio, $fecha_fin])
                    ->select('fechareporte', 'creditos')
                    ->orderBy('fechareporte', 'asc')
                    ->get();

                $datos['iva_generado'] = null;
                $datos['iva_descontable'] = null;
            }

            // Cuentas 6(costo de ventas)
            $datos['cuenta_6'] = $modelo::where('Nit', $nit)
                ->where('cuenta', 6)
                ->whereBetween('fechareporte', [$fecha_inicio, $fecha_fin])
                ->select('fechareporte', 'debitos', 'creditos')
                ->orderBy('fechareporte', 'asc')
                ->get();

            // Cuentas 7(costo de produccion)
            $datos['cuenta_7'] = $modelo::where('Nit', $nit)
                ->where('cuenta', 7)
                ->whereBetween('fechareporte', [$fecha_inicio, $fecha_fin])
                ->select('fechareporte', 'debitos', 'creditos')
                ->orderBy('fechareporte', 'asc')
                ->get();
            //cartera 1305
            $datos['cuenta_1305'] = $modelo::where('Nit', $nit)
                ->where('cuenta', 1305)
                ->whereBetween('fechareporte', [$fecha_inicio, $fecha_fin])
                ->select('fechareporte', 'nuevo_saldo')
                ->orderBy('fechareporte', 'asc')
                ->get();

            // Debugbar::info($datos['cuenta_1305']->toArray());
        } else {
            Debugbar::info('Entró a GENERICO');
            // Para genéricos
            // Cuenta 41 (devoluciones e ingresos operacionales)
            $datos['cuenta_41'] = $modelo::where('Nit', $nit)
                ->where('cuenta', 41)
                ->whereBetween('fechareporte', [$fecha_inicio, $fecha_fin])
                ->select('fechareporte', 'debitos', 'creditos')
                ->orderBy('fechareporte', 'asc')
                ->get();




            if ($isIvaPersonalizado === 'true') {

                $generado = $this->consultarIvaPersonalizado($nit, $fecha_inicio, $fecha_fin, 'generado');
                $descontable = $this->consultarIvaPersonalizado($nit, $fecha_inicio, $fecha_fin, 'descontable');


                $datos['iva_generado'] = InformesGenericos::where('Nit', $nit)
                    ->whereIn('cuenta', $generado)
                    ->whereBetween('fechareporte', [$fecha_inicio, $fecha_fin])
                    ->select('fechareporte', 'debitos', 'creditos')
                    ->orderBy('fechareporte', 'asc')
                    ->get();

                $datos['iva_descontable'] = InformesGenericos::where('Nit', $nit)
                    ->whereIn('cuenta', $descontable)
                    ->whereBetween('fechareporte', [$fecha_inicio, $fecha_fin])
                    ->select('fechareporte', 'debitos', 'creditos')
                    ->orderBy('fechareporte', 'asc')
                    ->get();

                // dd($generado, $descontable, $data['iva_generado'], $data['iva_descontable']);


            } else {
                // Cuenta 2408 (IVA)
                $datos['cuenta_2408'] = $modelo::where('Nit', $nit)
                    ->where('cuenta', 2408)
                    ->whereBetween('fechareporte', [$fecha_inicio, $fecha_fin])
                    ->select('fechareporte', 'creditos')
                    ->orderBy('fechareporte', 'asc')
                    ->get();

                $datos['iva_generado'] = null;
                $datos['iva_descontable'] = null;
            }

            // Cuentas 6(costo de ventas)
            $datos['cuenta_6'] = $modelo::where('Nit', $nit)
                ->where('cuenta', 6)
                ->whereBetween('fechareporte', [$fecha_inicio, $fecha_fin])
                ->select('fechareporte', 'debitos', 'creditos')
                ->orderBy('fechareporte', 'asc')
                ->get();

            // Cuentas 7(costo de produccion)
            $datos['cuenta_7'] = $modelo::where('Nit', $nit)
                ->where('cuenta', 7)
                ->whereBetween('fechareporte', [$fecha_inicio, $fecha_fin])
                ->select('fechareporte', 'debitos', 'creditos')
                ->orderBy('fechareporte', 'asc')
                ->get();

            $datos['cuenta_1305'] = $modelo::where('Nit', $nit)
                ->where('cuenta', 1305)
                ->whereBetween('fechareporte', [$fecha_inicio, $fecha_fin])
                ->select('fechareporte', 'debitos', 'creditos', 'saldo_final')
                ->orderBy('fechareporte', 'asc')
                ->get();
        }

        // dd($datos);

        return $datos;
    }

    /**
     * analisisIndicadoresFinancierosIA
     * @param  mixed $dataIA
     */
    private function analisisIndicadoresFinancierosIA($dataIA)
    {
        // aaaareturn 'Ocurrió un error al intentar obtener la respuesta. Por favor, inténtelo de nuevo recargando la página.';
        try {
            $response = Http::withHeaders([
                "Content-Type" => "application/json",
                "Authorization" => "Bearer " . env('CHAT_GPT_KEY')
            ])->post('https://api.openai.com/v1/chat/completions', [
                "model" =>  env('CHAT_GPT_MODEL'),
                "messages" => [
                    [
                        "role" => "assistant",
                        "content" => 'Actúa como asistente de análisis financiero experto. Analiza los indicadores que te proporcione y entrega tu opinión en este formato:

                                1. EBITDA: [Interpretación breve, en una o dos frases]
                                2. Capital de Trabajo: [Interpretación breve, en una o dos frases]
                                3. Margen de Ganancia: [Interpretación breve, en una o dos frases]
                                4. ROA: [Interpretación breve, en una o dos frases]
                                5. ROE: [Interpretación breve, en una o dos frases]
                                6. Liquidez Corriente: [Interpretación breve, en una o dos frases]
                                7. Prueba Ácida: [Interpretación breve, en una o dos frases]
                                8. Nivel de Endeudamiento: [Interpretación breve, en una o dos frases]

                                Reglas:
                                - Usa lenguaje formal y claro, sin tecnicismos innecesarios.
                                - Cada punto debe ser 1-2 frases máximo.
                                - Respuesta total ≤ 1400 caracteres (incluyendo espacios).
                                - Si falta contexto o datos, añade al final: “Nota: [falta X o Y]”.
                                - No uses asteriscos, negritas, ni formatos de markdown.
                                - No incluyas saludos, despedidas ni textos de cortesía.

                                Al final, agrega una recomendación formal basada en el análisis global de los indicadores.'
                    ],
                    [
                        "role" => "user",
                        "content" => json_encode($dataIA)
                    ],
                ],
                "temperature" => 0,
                "max_completion_tokens" => 2048
            ])->json();

            $respuesta =  $response['choices'][0]['message']['content'];

            return $respuesta;
            //respuesta en markdown
            // return $this->markdownService->parse($respuesta);
        } catch (Throwable $e) {
            // return $e->getMessage();
            return 'Ocurrió un error al intentar obtener la respuesta. Por favor, inténtelo de nuevo recargando la página.';
        }
    }


    /**
     * Obtener datos de gráficos para empresas tipo NUBE
     */
    private function obtenerDatosGraficosNube($nit, $fecha_inicio, $fecha_fin)
    {
        // Obtener datos base para devoluciones e ingresos operacionales (cuenta 41)
        $datosBasicos = $this->consultarDatosBase(
            Clientes::class,
            $nit,
            $fecha_inicio,
            $fecha_fin,
            'nube'
        );

        $devoluciones = $datosBasicos['cuenta_41']->map(function ($detalle) {
            return [
                'fecha' => $detalle->fechareporte_ga,
                'valor' => round($detalle->movimiento_debito_ga)
            ];
        });

        if (
            $devoluciones->isNotEmpty() &&
            $devoluciones->every(function ($item) {
                return (float) ($item['valor'] ?? 0) === 0.0;
            })
        ) {
            $devoluciones = [];
        }


        $ingresosOperacionales = $datosBasicos['cuenta_41']->map(function ($detalle) {
            return [
                'fecha' => $detalle->fechareporte_ga,
                'valor' => round($detalle->movimiento_credito_ga)
            ];
        });

        // Gastos (cuenta 5)
        $gastos = Clientes::where('Nit', $nit)
            ->where('codigo_cuenta_contable_ga', 5)
            ->whereBetween('fechareporte_ga', [$fecha_inicio, $fecha_fin])
            ->select('fechareporte_ga', 'movimiento_debito_ga', 'movimiento_credito_ga')
            ->orderBy('fechareporte_ga', 'asc')
            ->get()
            ->map(function ($detalle) {
                return [
                    'fecha' => $detalle->fechareporte_ga,
                    'valor' => round($detalle->movimiento_debito_ga - $detalle->movimiento_credito_ga)
                ];
            });


        $cartera = $datosBasicos['cuenta_1305']->map(function ($detalle) {
            return [
                'fecha' => $detalle->fechareporte_ga,
                'valor' => round($detalle->saldo_final_ga)
            ];
        });

        //costo de ventas 6
        $costoVentas = $datosBasicos['cuenta_6']->map(function ($detalle) {
            return [
                'fecha' => $detalle->fechareporte_ga,
                'valor' => round($detalle->movimiento_debito_ga - $detalle->movimiento_credito_ga)
            ];
        });

        //costo de produccion 7
        $costoProduccion = $datosBasicos['cuenta_7']->map(function ($detalle) {
            return [
                'fecha' => $detalle->fechareporte_ga,
                'valor' => round($detalle->movimiento_debito_ga - $detalle->movimiento_credito_ga)
            ];
        });


        $valorGenerado = collect([]);
        $valorCompras = collect([]);

        if ($datosBasicos['iva_generado'] && $datosBasicos['iva_descontable']) {
            // dd('IVA personalizado encontrado. Usando códigos personalizados para IVA generado y descontable.');            

            $cuentasGenerado = $datosBasicos['iva_generado'];
            $cuentasDescontable = $datosBasicos['iva_descontable'];

            if ($cuentasGenerado->isEmpty()) {
                $valorGenerado = collect([]); // Colección vacía si no hay datos
            } else {
                $valorGenerado = $cuentasGenerado->map(function ($detalle) {
                    return [
                        'fecha' => $detalle->fechareporte_ga,
                        'valor_generado' => abs(round($detalle->movimiento_credito_ga)) - abs(round($detalle->movimiento_debito_ga))
                    ];
                });
            }

            if ($cuentasDescontable->isEmpty()) {
                $valorCompras = collect([]); // Colección vacía si no hay datos
            } else {
                $valorCompras = $cuentasDescontable->map(function ($detalle) {
                    return [
                        'fecha' => $detalle->fechareporte_ga,
                        'valor_compras' => abs(round($detalle->movimiento_credito_ga)) - abs(round($detalle->movimiento_debito_ga))
                    ];
                });
            }

            $iva = collect();
            $maxCount = max($valorGenerado->count(), $valorCompras->count());

            for ($i = 0; $i < $maxCount; $i++) {
                $generado = $valorGenerado[$i] ?? ['fecha' => null, 'valor_generado' => 0];
                $compras = $valorCompras[$i] ?? ['fecha' => null, 'valor_compras' => 0];

                $iva->push([
                    'fecha' => $generado['fecha'] ?? $compras['fecha'],
                    'valor_generado' => abs(round($generado['valor_generado'] ?? 0)),
                    'valor_compras' => abs(round($compras['valor_compras'] ?? 0))
                ]);
            }


            // dd($valorGenerado, $valorDescontable);


        } else {

            // IVA (cuenta 2408)
            // $valorGenerado = $datosBasicos['cuenta_2408'];//anterior
            $valorGenerado = $datosBasicos['cuenta_2408'] ?? collect([]);
            $valorCompras = $this->obtenerValorComprasNube($nit, $fecha_inicio, $fecha_fin);

            $iva = collect();
            $fechasGenerado = $valorGenerado->pluck('fechareporte_ga')->unique();
            $fechasCompras = $valorCompras->pluck('fechareporte_ga')->unique();
            $todasLasFechas = $fechasGenerado->merge($fechasCompras)->unique();

            foreach ($todasLasFechas as $fecha) {
                $generado = $valorGenerado->firstWhere('fechareporte_ga', $fecha);
                $compra = $valorCompras->firstWhere('fechareporte_ga', $fecha);

                $iva->push([
                    'fecha' => $fecha,
                    'valor_generado' => abs(round($generado?->movimiento_credito_ga ?? 0)),
                    'valor_compras' => abs(round($compra?->movimiento_debito_ga ?? 0))
                ]);
            }
        }

        return [
            'devoluciones' => $devoluciones,
            'ingresos_operacionales' => $ingresosOperacionales,
            'gastos' => $gastos,
            'iva' => $iva,
            'valor_generado' => $valorGenerado,
            'valor_compras' => $valorCompras,
            'cartera' => $cartera,
            'costoVentas' => $costoVentas,
            'costoProduccion' => $costoProduccion

        ];
    }

    private function obtenerDatosGraficosContapyme($nit, $fecha_inicio, $fecha_fin)
    {
        // Obtener datos base para devoluciones e ingresos operacionales (cuenta 41)
        $datosBasicos = $this->consultarDatosBase(
            ContapymeCompleto::class,
            $nit,
            $fecha_inicio,
            $fecha_fin,
            'CONTAPYME'
        );

        $devoluciones = $datosBasicos['cuenta_41']->map(function ($detalle) {
            return [
                'fecha' => $detalle->fechareporte,
                'valor' => round($detalle->debitos)
            ];
        });

        $ingresosOperacionales = $datosBasicos['cuenta_41']->map(function ($detalle) {
            return [
                'fecha' => $detalle->fechareporte,
                'valor' => round($detalle->creditos)
            ];
        });

        $cartera = $datosBasicos['cuenta_1305']->map(function ($detalle) {
            return [
                'fecha' => $detalle->fechareporte,
                'valor' => round($detalle->nuevo_saldo)
            ];
        });




        $costoVentas = $datosBasicos['cuenta_6']->map(function ($detalle) {
            return [
                'fecha' => $detalle->fechareporte,
                'valor' => round($detalle->debitos - $detalle->creditos)
            ];
        });

        $costoProduccion = $datosBasicos['cuenta_7']->map(function ($detalle) {
            return [
                'fecha' => $detalle->fechareporte,
                'valor' => round($detalle->debitos - $detalle->creditos)
            ];
        });

        // Debugbar::info('Costo de ventas: ' . $costoVentas);
        // Debugbar::info('Costo de produccion: ' . $costoProduccion);


        // Gastos (cuenta 5)
        $gastos = ContapymeCompleto::where('Nit', $nit)
            ->where('cuenta', 5)
            ->whereBetween('fechareporte', [$fecha_inicio, $fecha_fin])
            ->select('fechareporte', 'debitos', 'creditos')
            ->orderBy('fechareporte', 'asc')
            ->get()
            ->map(function ($detalle) {
                return [
                    'fecha' => $detalle->fechareporte,
                    'valor' => round($detalle->debitos - $detalle->creditos)
                ];
            });

        // IVA (cuenta 2408)
        $valorGenerado = $datosBasicos['cuenta_2408'];
        $valorCompras = $this->obtenerValorComprasContapyme($nit, $fecha_inicio, $fecha_fin);

        $iva = $valorGenerado->map(function ($detalle, $key) use ($valorCompras) {
            return [
                'fecha' => $detalle->fechareporte,
                'valor_generado' => abs(round($detalle->creditos)),
                'valor_compras' => abs(round($valorCompras[$key]['valor'] ?? 0))
            ];
        });

        return [
            'devoluciones' => $devoluciones,
            'ingresos_operacionales' => $ingresosOperacionales,
            'gastos' => $gastos,
            'iva' => $iva,
            'valor_generado' => $valorGenerado,
            'valor_compras' => $valorCompras,
            'cartera' => $cartera,
            'costoVentas' => $costoVentas,
            'costoProduccion' => $costoProduccion
        ];
    }
    /**
     * Obtener datos de gráficos para empresas tipo PYME
     */
    private function obtenerDatosGraficosPyme($nit, $fecha_inicio, $fecha_fin)
    {
        $cuentaSQL = <<<SQL
        REPLACE( CONCAT(
            TRIM(IFNULL(clientes.grupo, '')),
            TRIM(IFNULL(clientes.cuenta, '')),
            TRIM(IFNULL(clientes.subcuenta, ''))
        ), ' ', ' ')
        SQL;

        // Consulta única para obtener todos los datos necesarios
        $resultados = Clientes::where('Nit', $nit)
            ->where(function ($query) use ($cuentaSQL) {
                $query
                    ->whereRaw("$cuentaSQL = ?", [41])
                    ->orWhereRaw("$cuentaSQL = ?", [6])
                    ->orWhereRaw("$cuentaSQL = ?", [7])
                    ->orWhereRaw("$cuentaSQL = ?", [51])
                    ->orWhereRaw("$cuentaSQL = ?", [2408])
                    ->orWhereRaw("$cuentaSQL = ?", [1305]);
            })
            ->whereBetween('fechareporte', [$fecha_inicio, $fecha_fin])
            ->select(
                'fechareporte',
                DB::raw("$cuentaSQL as cuenta_concat"),
                'debitos',
                'creditos'
            )
            ->orderBy('fechareporte', 'asc')
            ->get();

        // Debugbar::info('Resultados PYME: ' . $resultados);

        return $this->procesarResultadosPyme($resultados);
    }
    /**
     * Obtener datos de gráficos para empresas tipo GENERICO y similares
     */
    private function obtenerDatosGraficosGenericos($nit, $fecha_inicio, $fecha_fin)
    {

        // Obtener datos base
        $datosBasicos = $this->consultarDatosBase(
            InformesGenericos::class,
            $nit,
            $fecha_inicio,
            $fecha_fin,
            'generico'
        );

        $devoluciones = $datosBasicos['cuenta_41']->map(function ($detalle) {
            return [
                'fecha' => $detalle->fechareporte,
                'valor' => round($detalle->debitos)
            ];
        });

        $ingresosOperacionales = $datosBasicos['cuenta_41']->map(function ($detalle) {
            return [
                'fecha' => $detalle->fechareporte,
                'valor' => round($detalle->creditos)
            ];
        });

        // Gastos (cuenta 51)
        $gastos = InformesGenericos::where('Nit', $nit)
            ->where('cuenta', 51)
            ->whereBetween('fechareporte', [$fecha_inicio, $fecha_fin])
            ->select('fechareporte', 'debitos', 'creditos')
            ->orderBy('fechareporte', 'asc')
            ->get()
            ->map(function ($detalle) {
                return [
                    'fecha' => $detalle->fechareporte,
                    'valor' => round($detalle->debitos - $detalle->creditos)
                ];
            });

        $cartera = $datosBasicos['cuenta_1305']->map(function ($detalle) {
            return [
                'fecha' => $detalle->fechareporte,
                'valor' => round($detalle->creditos) - round($detalle->debitos)
            ];
        });

        $costoVentas = $datosBasicos['cuenta_6']->map(function ($detalle) {
            return [
                'fecha' => $detalle->fechareporte,
                'valor' => round($detalle->debitos - $detalle->creditos)
            ];
        });

        $costoProduccion = $datosBasicos['cuenta_7']->map(function ($detalle) {
            return [
                'fecha' => $detalle->fechareporte,
                'valor' => round($detalle->debitos - $detalle->creditos)
            ];
        });

        $valorGenerado = collect([]);
        $valorCompras = collect([]);

        // Debugbar::info('Datos básicos para IVA: ' . $datosBasicos['iva_generado'] . ' y ' . $datosBasicos['iva_descontable']);

        if ($datosBasicos['iva_generado'] && $datosBasicos['iva_descontable']) {
            Debugbar::info('IVA personalizado encontrado.');

            $cuentasGenerado = $datosBasicos['iva_generado'];
            $cuentasDescontable = $datosBasicos['iva_descontable'];

            if ($cuentasGenerado->isEmpty()) {
                $valorGenerado = collect([]); // Colección vacía si no hay datos
            } else {
                $valorGenerado = $cuentasGenerado->map(function ($detalle) {
                    return [
                        'fecha' => $detalle->fechareporte,
                        'valor_generado' => abs(round($detalle->creditos)) - abs(round($detalle->debitos))
                    ];
                });
            }

            if ($cuentasDescontable->isEmpty()) {
                $valorCompras = collect([]); // Colección vacía si no hay datos
            } else {
                $valorCompras = $cuentasDescontable->map(function ($detalle) {
                    return [
                        'fecha' => $detalle->fechareporte,
                        'valor_compras' => abs(round($detalle->creditos)) - abs(round($detalle->debitos))
                    ];
                });
            }

            $iva = collect();
            $maxCount = max($valorGenerado->count(), $valorCompras->count());

            for ($i = 0; $i < $maxCount; $i++) {
                $generado = $valorGenerado[$i] ?? ['fecha' => null, 'valor_generado' => 0];
                $compras = $valorCompras[$i] ?? ['fecha' => null, 'valor_compras' => 0];

                $iva->push([
                    'fecha' => $generado['fecha'] ?? $compras['fecha'],
                    'valor_generado' => abs(round($generado['valor_generado'] ?? 0)),
                    'valor_compras' => abs(round($compras['valor_compras'] ?? 0))
                ]);
            }
        } else {
            Debugbar::info('No se encontró IVA personalizado.');
            // IVA (cuenta 2408)
            // $valorGenerado = $datosBasicos['cuenta_2408'];//anterior
            $valorGenerado = $datosBasicos['cuenta_2408'] ?? collect([]);
            $valorCompras = $this->obtenerValorComprasGenericos($nit, $fecha_inicio, $fecha_fin);

            $iva = collect();
            $fechasGenerado = $valorGenerado->pluck('fechareporte')->unique();
            $fechasCompras = $valorCompras->pluck('fecha')->unique();
            $todasLasFechas = $fechasGenerado->merge($fechasCompras)->unique()->sort();

            foreach ($todasLasFechas as $fecha) {
                $generado = $valorGenerado->firstWhere('fechareporte', $fecha);
                $compra = $valorCompras->firstWhere('fecha', $fecha);

                $iva->push([
                    'fecha' => $fecha,
                    'valor_generado' => $generado ? abs(round($generado->creditos)) : 0,
                    'valor_compras' => $compra ? abs(round($compra['valor'])) : 0
                ]);
            }
        }


        return [
            'devoluciones' => $devoluciones,
            'ingresos_operacionales' => $ingresosOperacionales,
            'gastos' => $gastos,
            'iva' => $iva,
            'valor_generado' => $valorGenerado,
            'valor_compras' => $valorCompras,
            'cartera' => $cartera,
            'costoVentas' => $costoVentas,
            'costoProduccion' => $costoProduccion
        ];
    }
    /**
     * Obtener valor de compras para empresas NUBE
     */
    private function obtenerValorComprasNube($nit, $fecha_inicio, $fecha_fin)
    {
        $compras240810 = Clientes::where('Nit', $nit)
            ->where('codigo_cuenta_contable_ga', 240802)
            ->whereBetween('fechareporte_ga', [$fecha_inicio, $fecha_fin])
            ->select(
                'fechareporte_ga',
                DB::raw('ROUND(SUM(movimiento_debito_ga) - SUM(movimiento_credito_ga)) AS valor')
            )
            ->groupBy('fechareporte_ga')
            ->orderBy('fechareporte_ga', 'asc')
            ->get()
            ->keyBy('fechareporte_ga');

        $compras240815 = Clientes::where('Nit', $nit)
            ->where('codigo_cuenta_contable_ga', 240815)
            ->whereBetween('fechareporte_ga', [$fecha_inicio, $fecha_fin])
            ->select(
                'fechareporte_ga',
                DB::raw('ROUND(SUM(movimiento_debito_ga) - SUM(movimiento_credito_ga)) AS valor')
            )
            ->groupBy('fechareporte_ga')
            ->orderBy('fechareporte_ga', 'asc')
            ->get()
            ->keyBy('fechareporte_ga');

        $fechas = $compras240810->keys()->merge($compras240815->keys())->unique()->sort();

        return $fechas->map(function ($fecha) use ($compras240810, $compras240815) {
            $valor240810 = $compras240810->get($fecha)->valor ?? 0;
            $valor240815 = $compras240815->get($fecha)->valor ?? 0;

            return [
                'fecha' => $fecha,
                'valor' => round($valor240810 + $valor240815)
            ];
        });
    }

    private function obtenerValorComprasContapyme($nit, $fecha_inicio, $fecha_fin)
    {
        $compras240810 = ContapymeCompleto::where('Nit', $nit)
            ->where('cuenta', 240810)
            ->whereBetween('fechareporte', [$fecha_inicio, $fecha_fin])
            ->select(
                'fechareporte',
                DB::raw('ROUND(SUM(debitos) - SUM(creditos)) AS valor')
            )
            ->groupBy('fechareporte')
            ->orderBy('fechareporte', 'asc')
            ->get()
            ->keyBy('fechareporte');

        $compras240815 = ContapymeCompleto::where('Nit', $nit)
            ->where('cuenta', 240815)
            ->whereBetween('fechareporte', [$fecha_inicio, $fecha_fin])
            ->select(
                'fechareporte',
                DB::raw('ROUND(SUM(debitos) - SUM(creditos)) AS valor')
            )
            ->groupBy('fechareporte')
            ->orderBy('fechareporte', 'asc')
            ->get()
            ->keyBy('fechareporte');

        $fechas = $compras240810->keys()->merge($compras240815->keys())->unique()->sort();

        return $fechas->map(function ($fecha) use ($compras240810, $compras240815) {
            $valor240810 = $compras240810->get($fecha)->valor ?? 0;
            $valor240815 = $compras240815->get($fecha)->valor ?? 0;

            return [
                'fecha' => $fecha,
                'valor' => round($valor240810 + $valor240815)
            ];
        });
    }

    /**
     * Obtener valor de compras para empresas genéricas
     */
    private function obtenerValorComprasGenericos($nit, $fecha_inicio, $fecha_fin)
    {
        $compras240810 = InformesGenericos::where('Nit', $nit)
            ->where('cuenta', 240810)
            ->whereBetween('fechareporte', [$fecha_inicio, $fecha_fin])
            ->select(
                'fechareporte',
                DB::raw('ROUND(SUM(debitos) - SUM(creditos)) AS valor')
            )
            ->groupBy('fechareporte')
            ->orderBy('fechareporte', 'asc')
            ->get()
            ->keyBy('fechareporte');

        $compras240815 = InformesGenericos::where('Nit', $nit)
            ->where('cuenta', 240815)
            ->whereBetween('fechareporte', [$fecha_inicio, $fecha_fin])
            ->select(
                'fechareporte',
                DB::raw('ROUND(SUM(debitos) - SUM(creditos)) AS valor')
            )
            ->groupBy('fechareporte')
            ->orderBy('fechareporte', 'asc')
            ->get()
            ->keyBy('fechareporte');

        $fechas = $compras240810->keys()->merge($compras240815->keys())->unique()->sort();

        $compras = $fechas->map(function ($fecha) use ($compras240810, $compras240815) {
            $valor240810 = $compras240810->get($fecha)->valor ?? 0;
            $valor240815 = $compras240815->get($fecha)->valor ?? 0;

            return [
                'fecha' => $fecha,
                'valor' => round($valor240810 + $valor240815)
            ];
        });

        return $compras;
    }

    /**
     * Procesar resultados para empresas PYME
     */
    private function procesarResultadosPyme($resultados)
    {
        $devoluciones = $resultados->where('cuenta_concat', 41)
            ->map(function ($detalle) {
                return [
                    'fecha' => $detalle->fechareporte,
                    'valor' => round($detalle->debitos)
                ];
            })->values();

        $ingresosOperacionales = $resultados->where('cuenta_concat', 41)
            ->map(function ($detalle) {
                return [
                    'fecha' => $detalle->fechareporte,
                    'valor' => round($detalle->creditos)
                ];
            })->values();

        $gastos = $resultados->where('cuenta_concat', 51)
            ->map(function ($detalle) {
                return [
                    'fecha' => $detalle->fechareporte,
                    'valor' => round($detalle->debitos - $detalle->creditos)
                ];
            })->values();

        $cartera = $resultados->where('cuenta_concat', 16)
            ->map(function ($detalle) {
                return [
                    'fecha' => $detalle->fechareporte,
                    'valor' => abs($detalle->creditos -  $detalle->debitos),
                ];
            })->values();
        // Debugbar::info('Cartera PYME: ' . $cartera);

        $costoVentas = $resultados->where('cuenta_concat', 6)
            ->map(function ($detalle) {
                return [
                    'fecha' => $detalle->fechareporte,
                    'valor' => round($detalle->debitos - $detalle->creditos)
                ];
            })->values();
        $costoProduccion = $resultados->where('cuenta_concat', 7)
            ->map(function ($detalle) {
                return [
                    'fecha' => $detalle->fechareporte,
                    'valor' => round($detalle->debitos - $detalle->creditos)
                ];
            })->values();


        $valorGenerado = $resultados->where('cuenta_concat', 2408)
            ->map(function ($detalle) {
                return [
                    'fecha' => $detalle->fechareporte,
                    'creditos' => $detalle->creditos
                ];
            })->values();

        $valorCompras = $resultados->where('cuenta_concat', 2408)
            ->map(function ($detalle) {
                return [
                    'fecha' => $detalle->fechareporte,
                    'debitos' => $detalle->debitos
                ];
            })->values();



        $iva = $valorGenerado->map(function ($detalle, $key) use ($valorCompras) {
            $valorCompra = isset($valorCompras[$key]) ? round($valorCompras[$key]['debitos']) : 0;
            return [
                'fecha' => $detalle['fecha'],
                'valor_generado' => round($detalle['creditos']),
                'valor_compras' => $valorCompra
            ];
        });

        return [
            'devoluciones' => $devoluciones,
            'ingresos_operacionales' => $ingresosOperacionales,
            'gastos' => $gastos,
            'iva' => $iva,
            'valor_generado' => $valorGenerado,
            'valor_compras' => $valorCompras,
            'cartera' => $cartera,
            'costoVentas' => $costoVentas,
            'costoProduccion' => $costoProduccion
        ];
    }




    /**
     * MEJORAS
     */

    /** 
     * Obtener valor único de una cuenta específica del informe genérico
     * retorna el valor sin abs
     */
    private function getUniqValue($informeQuery, $codigo)
    {
        if (!isset($informeQuery[$codigo])) {
            return 0;
        }
        $data = floatval(str_replace(',', '', $informeQuery[$codigo]->total_mes ?? 0));
        return $data;
    }

    private function cuentasBancarias($empresa, $fecha_inicio, $fecha_fin)
    {
        // $data = CuentaBancaria::with(['empresa:id,razon_social'])
        //     ->where('empresa_id', $empresa->id)
        //     ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
        //     ->orderBy('fecha', 'asc')
        //     ->get();

        // return $data;

        return [];
    }

    private function calcularArrayCuentasSaldoFinal($empresa, $nit, $fecha_fin, $cuentas = [])
    {

        if (empty($cuentas)) {
            return 0;
        }

        if ($empresa->tipo == 'PYME') {
            $tabla = 'clientes';
            $fecha = 'fechareporte';
            $cuenta = 'cuenta';
            $saldoFinal = 'nuevo_saldo';
        } elseif ($empresa->tipo == 'NUBE') {
            $tabla = 'clientes';
            $fecha = 'fechareporte_ga';
            $cuenta = 'codigo_cuenta_contable_ga';
            $saldoFinal = 'saldo_final_ga';
        } elseif ($empresa->tipo == 'CONTAPYME') {
            $tabla = 'contapyme_completo';
            $fecha = 'fechareporte';
            $cuenta = 'cuenta';
            $saldoFinal = 'nuevo_saldo';
        } elseif ($empresa->tipo == 'LOGGRO') {
            $tabla = 'loggro';
            $fecha = 'fechareporte';
            $cuenta = 'cuenta';
            $saldoFinal = 'nuevo_saldo';
        } elseif ($empresa->tipo == 'BEGRANDA') {
            $tabla = 'begranda';
            $fecha = 'fechareporte';
            $cuenta = 'cuenta';
            $saldoFinal = 'nuevo_saldo';
        } elseif (in_array($empresa->tipo, $this->tiposGenericos)) {
            $tabla = 'informesgenericos';
            $fecha = 'fechareporte';
            $cuenta = 'cuenta';
            $saldoFinal = 'saldo_final';
        }

        $data = DB::table($tabla)
            ->select(DB::raw("COALESCE(SUM($saldoFinal), 0) AS total"))
            ->whereIn($cuenta, $cuentas)
            ->where('nit', $nit)
            ->where($fecha, date('Y-m-t', strtotime($fecha_fin)))
            ->value('total');

        $data = abs(floatval($data));

        return $data;
    }

    private function costoFinancieroNeto($informeQuery)
    {
        $data =
            abs(floatval(str_replace(',', '', $informeQuery['4210']->total_mes)))
            - abs(floatval(str_replace(',', '', $informeQuery['5315']->total_mes)));
        return $data;
    }


        // $cuentasSumar = ['21', '22', '23', '24', '25', '26', '28'];
    // $cuentasRestar = ['2105', '2355', '2357', '2360', '2640', '2635', '27'];
    private function totalActivoCorriente($empresa, $nit, $fecha_fin)
    {
        $cuentasSumar = ['11', '12', '13', '1330', '1355', '14'];
        $cuentasRestar = ['1330', '1355', '1730'];

        if ($empresa->tipo == 'PYME') {
            $tabla = 'clientes';
            $fecha = 'fechareporte';
            $cuenta = 'cuenta';
            $creditos = 'creditos';
            $debitos = 'debitos';
            $saldoFinal = 'nuevo_saldo';
        } elseif ($empresa->tipo == 'NUBE') {
            $tabla = 'clientes';
            $fecha = 'fechareporte_ga';
            $cuenta = 'codigo_cuenta_contable_ga';
            $creditos = 'movimiento_credito_ga';
            $debitos = 'movimiento_debito_ga';
            $saldoFinal = 'saldo_final_ga';
        } elseif ($empresa->tipo == 'CONTAPYME') {
            $tabla = 'contapyme_completo';
            $fecha = 'fechareporte';
            $cuenta = 'cuenta';
            $creditos = 'creditos';
            $debitos = 'debitos';
            $saldoFinal = 'nuevo_saldo';
        } elseif ($empresa->tipo == 'LOGGRO') {
            $tabla = 'loggro';
            $fecha = 'fechareporte';
            $cuenta = 'cuenta';
            $creditos = 'creditos';
            $debitos = 'debitos';
            $saldoFinal = 'saldo_final';
        } elseif ($empresa->tipo == 'BEGRANDA') {
            $tabla = 'begranda';
            $fecha = 'fechareporte';
            $cuenta = 'cuenta';
            $creditos = 'creditos';
            $debitos = 'debitos';
            $saldoFinal = 'saldo_final';
        } elseif (in_array($empresa->tipo, $this->tiposGenericos)) {
            $tabla = 'informesgenericos';
            $fecha = 'fechareporte';
            $cuenta = 'cuenta';
            $creditos = 'creditos';
            $debitos = 'debitos';
            $saldoFinal = 'saldo_final';
        } else {
            return 0;
        }

        $totalCuentasSumar = DB::table($tabla)
            ->select(DB::raw("COALESCE(SUM($saldoFinal), 0) AS total"))
            ->whereIn($cuenta, $cuentasSumar)
            ->where('nit', $nit)
            ->where($fecha, date('Y-m-t', strtotime($fecha_fin)))
            ->value('total');

        $totalCuentasRestar = DB::table($tabla)
            ->select(DB::raw("COALESCE(SUM($saldoFinal), 0) AS total"))
            ->whereIn($cuenta, $cuentasRestar)
            ->where('nit', $nit)
            ->where($fecha, date('Y-m-t', strtotime($fecha_fin)))
            ->value('total');


            

        $data = floatval($totalCuentasSumar) - floatval($totalCuentasRestar);

        // Debugbar::info('Total Activo Corriente: ' . $data);

        return abs($data);
    }

    private function totalActivoNoCorriente($informeQuery)
    {
        $data =
            abs(floatval(str_replace(',', '', $informeQuery['1290']->total_mes)))
            + abs(floatval(str_replace(',', '', $informeQuery['15']->total_mes)))
            + abs(floatval(str_replace(',', '', $informeQuery['16']->total_mes)))
            + abs(floatval(str_replace(',', '', $informeQuery['173']->total_mes)));
        return $data;
    }

    private function totalActivo($empresa, $nit, $fecha_inicio, $fecha_fin, $informeQuery)
    {
        //total activo 
        if ($empresa->tipo  == 'NUBE') {
            $data = DB::table('clientes')
                ->select(DB::raw("COALESCE(SUM(saldo_final_ga), 0) AS total_saldo"))
                ->where('codigo_cuenta_contable_ga', 1)
                ->where('nit', $nit)
                ->where('fechareporte_ga', date('Y-m-t', strtotime($fecha_fin)))
                ->value('total_saldo');

            $data = abs(floatval($data));
        } elseif (in_array($empresa->tipo, $this->tiposGenericos)) {
            $data = round(floatval(str_replace(',', '', $informeQuery['1']->total_mes)));
        } else {
            $data = round(floatval(str_replace(',', '', $informeQuery['1']->total_mes)));
        }

        return $data;
    }


    // $cuentasSumar = ['21', '22', '2305', '2310', '2315', '2320', '2330', '2335', '2340', '2345', '2350', '23', '2365', '2367', '2368', '2369', '24', '2615', '2370', '2380', '25', '2610', '2805', '2810', '2815'];
    // $cuentasRestar = ['210517', '2305', '2310', '2315', '2330', '2335', '2340', '2345', '2350', '2365', '2368', '2369', '2370', '2380', '2355', '2357', '2360'];
    private function totalPasivoCorriente($empresa, $nit, $fecha_fin)
    {
        $cuentasSumar = ['21', '22', '23', '24', '25', '26', '28'];
        $cuentasRestar = ['2105', '2355', '2357', '2360', '2640', '2635', '27'];

        if ($empresa->tipo == 'PYME') {
            $tabla = 'clientes';
            $fecha = 'fechareporte';
            $cuenta = 'cuenta';
            $creditos = 'creditos';
            $debitos = 'debitos';
            $saldoFinal = 'nuevo_saldo';
        } elseif ($empresa->tipo == 'NUBE') {
            $tabla = 'clientes';
            $fecha = 'fechareporte_ga';
            $cuenta = 'codigo_cuenta_contable_ga';
            $creditos = 'movimiento_credito_ga';
            $debitos = 'movimiento_debito_ga';
            $saldoFinal = 'saldo_final_ga';
        } elseif ($empresa->tipo == 'CONTAPYME') {
            $tabla = 'contapyme_completo';
            $fecha = 'fechareporte';
            $cuenta = 'cuenta';
            $creditos = 'creditos';
            $debitos = 'debitos';
            $saldoFinal = 'nuevo_saldo';
        } elseif ($empresa->tipo == 'LOGGRO') {
            $tabla = 'loggro';
            $fecha = 'fechareporte';
            $cuenta = 'cuenta';
            $creditos = 'creditos';
            $debitos = 'debitos';
            $saldoFinal = 'saldo_final';
        } elseif ($empresa->tipo == 'BEGRANDA') {
            $tabla = 'begranda';
            $fecha = 'fechareporte';
            $cuenta = 'cuenta';
            $creditos = 'creditos';
            $debitos = 'debitos';
            $saldoFinal = 'saldo_final';
        } elseif (in_array($empresa->tipo, $this->tiposGenericos)) {
            $tabla = 'informesgenericos';
            $fecha = 'fechareporte';
            $cuenta = 'cuenta';
            $creditos = 'creditos';
            $debitos = 'debitos';
            $saldoFinal = 'saldo_final';
        } else {
            return 0;
        }

        $totalCuentasSumar = DB::table($tabla)
            ->select(DB::raw("COALESCE(SUM($saldoFinal), 0) AS total"))
            ->whereIn($cuenta, $cuentasSumar)
            ->where('nit', $nit)
            ->where($fecha, date('Y-m-t', strtotime($fecha_fin)))
            ->value('total');

        $totalCuentasRestar = DB::table($tabla)
            ->select(DB::raw("COALESCE(SUM($saldoFinal), 0) AS total"))
            ->whereIn($cuenta, $cuentasRestar)
            ->where('nit', $nit)
            ->where($fecha, date('Y-m-t', strtotime($fecha_fin)))
            ->value('total');

        $data = floatval($totalCuentasSumar) - floatval($totalCuentasRestar);

        // Debugbar::info('Total Pasivo Corriente: ' . $data);

        return abs($data);
    }


    private function totalPasivoNoCorriente($informeQuery)
    {
        $data =
            abs(floatval(str_replace(',', '', $informeQuery['2105']->total_mes)))
            + abs(floatval(str_replace(',', '', $informeQuery['2355']->total_mes)))
            + abs(floatval(str_replace(',', '', $informeQuery['2357']->total_mes)))
            + abs(floatval(str_replace(',', '', $informeQuery['2360']->total_mes)))
            + abs(floatval(str_replace(',', '', $informeQuery['2640']->total_mes)))
            + abs(floatval(str_replace(',', '', $informeQuery['2635']->total_mes)))
            + abs(floatval(str_replace(',', '', $informeQuery['27']->total_mes)));
        return $data;
    }

    private function totalPasivo($empresa, $nit, $fecha_inicio, $fecha_fin, $informeQuery)
    {
        if ($empresa->tipo  == 'NUBE') {
            // $data = DB::table('clientes')
            //     ->select(DB::raw("COALESCE(SUM(saldo_inicial_ga), 0) AS total_saldo"))
            //     ->where('codigo_cuenta_contable_ga', 2)
            //     ->where('nit', $nit) // Filtra por el NIT del cliente
            //     ->whereBetween('fechareporte_ga', [
            //         date('Y-m-01', strtotime($fecha_inicio)), // Primer día del mes
            //         date('Y-m-t', strtotime($fecha_fin))   // Último día del mes
            //     ])
            //     ->value('total_saldo'); // Obtiene el valor de total_saldo
            $data = DB::table('clientes')
                ->select(DB::raw("COALESCE(SUM(saldo_final_ga), 0) AS total_saldo"))
                ->where('codigo_cuenta_contable_ga', 2)
                ->where('nit', $nit)
                ->where('fechareporte_ga', date('Y-m-t', strtotime($fecha_fin)))
                ->value('total_saldo');

            $data = abs(floatval($data));
        } elseif (in_array($empresa->tipo, $this->tiposGenericos)) {
            $data = round(floatval(str_replace(',', '', $informeQuery['2']->total_mes)));
        }

        return $data;
    }

    private function calcularArrayCreditosMenosDebitos($empresa, $nit, $fecha_fin, $cuentas = [])
    {

        if (empty($cuentas)) {
            return 0;
        }


        if ($empresa->tipo == 'PYME') {
            $tabla = 'clientes';
            $fecha = 'fechareporte';
            $cuenta = 'cuenta';
            $creditos = 'creditos';
            $debitos = 'debitos';
        } elseif ($empresa->tipo == 'NUBE') {
            $tabla = 'clientes';
            $fecha = 'fechareporte_ga';
            $cuenta = 'codigo_cuenta_contable_ga';
            $creditos = 'movimiento_credito_ga';
            $debitos = 'movimiento_debito_ga';
        } elseif ($empresa->tipo == 'CONTAPYME') {
            $tabla = 'contapyme_completo';
            $fecha = 'fechareporte';
            $cuenta = 'cuenta';
            $creditos = 'creditos';
            $debitos = 'debitos';
        } elseif ($empresa->tipo == 'LOGGRO') {
            $tabla = 'loggro';
            $fecha = 'fechareporte';
            $cuenta = 'cuenta';
            $creditos = 'creditos';
            $debitos = 'debitos';
        } elseif ($empresa->tipo == 'BEGRANDA') {
            $tabla = 'begranda';
            $fecha = 'fechareporte';
            $cuenta = 'cuenta';
            $creditos = 'creditos';
            $debitos = 'debitos';
        } elseif (in_array($empresa->tipo, $this->tiposGenericos)) {
            $tabla = 'informesgenericos';
            $fecha = 'fechareporte';
            $cuenta = 'cuenta';
            $creditos = 'creditos';
            $debitos = 'debitos';
        } else {
            return 0;
        }

        $data = DB::table($tabla)
            ->select(DB::raw("COALESCE(SUM($creditos) - SUM($debitos), 0) AS total"))
            ->whereIn($cuenta, $cuentas)
            ->where('nit',  $nit)
            ->where($fecha, date('Y-m-t', strtotime($fecha_fin)))
            ->value('total');

        return abs(floatval($data));
    }

    private function ivaGenerado($informeQuery)
    {
        $data =
            abs(floatval(str_replace(',', '', $informeQuery['240801']->total_mes)))
            + abs(floatval(str_replace(',', '', $informeQuery['240805']->total_mes)));
        return $data;
    }

    private function ivaDescontable($informeQuery)
    {
        $data =
            abs(floatval(str_replace(',', '', $informeQuery['240802']->total_mes)))
            + abs(floatval(str_replace(',', '', $informeQuery['240810']->total_mes)));
        return $data;
    }

    private function retefuente($informeQuery)
    {
        $data =
            abs(floatval(str_replace(',', '', $informeQuery['2365']->total_mes)))
            ? abs(floatval(str_replace(',', '', $informeQuery['2365']->total_mes))) : 0;
        // Debugbar::info('Retención en la fuente: ' . $data);
        return $data;
    }

    private function reteIva($informeQuery)
    {
        $data =
            abs(floatval(str_replace(',', '', $informeQuery['2367']->total_mes)))
            ? abs(floatval(str_replace(',', '', $informeQuery['2367']->total_mes))) : 0;
        // $data = abs(floatval(str_replace(',', '', $informeQuery['2367']->total_mes)));
        // Debugbar::info('Retención IVA: ' . $data);
        return $data;
    }

    private function impuestosDian($informeQuery)
    {
        $data =
            abs(floatval(str_replace(',', '', $informeQuery['135517']->total_mes)))
            ? abs(floatval(str_replace(',', '', $informeQuery['135517']->total_mes))) : 0;
        return $data;
    }

    private function impuestoConsumo($nit, $fecha_inicio, $fecha_fin, $tipo)
    {
        if ($tipo == 'PYME') {
            $tabla = 'clientes';
            $fecha = 'fechareporte';
            $cuenta = 'cuenta';
            $creditos = 'creditos';
            $debitos = 'debitos';
        } elseif ($tipo == 'NUBE') {
            $tabla = 'clientes';
            $fecha = 'fechareporte_ga';
            $cuenta = 'codigo_cuenta_contable_ga';
            $creditos = 'movimiento_credito_ga';
            $debitos = 'movimiento_debito_ga';
        } elseif ($tipo == 'CONTAPYME') {
            $tabla = 'contapyme_completo';
            $fecha = 'fechareporte';
            $cuenta = 'cuenta';
            $creditos = 'creditos';
            $debitos = 'debitos';
        } elseif ($tipo == 'LOGGRO') {
            $tabla = 'loggro';
            $fecha = 'fechareporte';
            $cuenta = 'cuenta';
            $creditos = 'creditos';
            $debitos = 'debitos';
        } elseif ($tipo == 'BEGRANDA') {
            $tabla = 'begranda';
            $fecha = 'fechareporte';
            $cuenta = 'cuenta';
            $creditos = 'creditos';
            $debitos = 'debitos';
        } elseif (in_array($tipo, $this->tiposGenericos)) {
            $tabla = 'informesgenericos';
            $fecha = 'fechareporte';
            $cuenta = 'cuenta';
            $creditos = 'creditos';
            $debitos = 'debitos';
        }

        $data = DB::select("
            SELECT $cuenta, (SUM($creditos) - SUM($debitos)) as total
            FROM $tabla 
            WHERE Nit = ?
            AND $fecha = ?
            AND ($cuenta = '24950101' OR $cuenta = '24950105')
            GROUP BY $cuenta
        ", [$nit, date('Y-m-t', strtotime($fecha_fin))]);
        // Debugbar::info('Consulta impuesto consumo: ', $data);

        $total = collect($data)->sum(function ($row) {
            return floatval($row->total ?? 0);
        });

        // Debugbar::info([
        //     'impuestoConsumo_rows' => $data,
        //     'impuestoConsumo_total' => $total,
        // ]);

        return $total;
    }

    private function totalGastos($informeQuery)
    {
        $data =
            floatval(str_replace(',', '', $informeQuery['53']->total_mes))
            - floatval(str_replace(',', '', $informeQuery['5305']->total_mes));
        return $data;
    }

    private function totalPatrimonio($empresa, $nit, $fecha_inicio, $fecha_fin, $informeQuery)
    {
        $cuentas = ['4', '5', '6', '7', '31', '32', '33', '34', '35', '36', '37', '38', '39'];

        if ($empresa->tipo == 'PYME') {
            $tabla = 'clientes';
            $fecha = 'fechareporte';
            $cuenta = 'cuenta';
            $saldoFinal = 'nuevo_saldo';
        } elseif ($empresa->tipo == 'NUBE') {
            $tabla = 'clientes';
            $fecha = 'fechareporte_ga';
            $cuenta = 'codigo_cuenta_contable_ga';
            $saldoFinal = 'saldo_final_ga';
        } elseif ($empresa->tipo == 'CONTAPYME') {
            $tabla = 'contapyme_completo';
            $fecha = 'fechareporte';
            $cuenta = 'cuenta';
            $saldoFinal = 'nuevo_saldo';
        } elseif ($empresa->tipo == 'LOGGRO') {
            $tabla = 'loggro';
            $fecha = 'fechareporte';
            $cuenta = 'cuenta';
            $saldoFinal = 'nuevo_saldo';
        } elseif ($empresa->tipo == 'BEGRANDA') {
            $tabla = 'begranda';
            $fecha = 'fechareporte';
            $cuenta = 'cuenta';
            $saldoFinal = 'nuevo_saldo';
        } elseif (in_array($empresa->tipo, $this->tiposGenericos)) {
            $data = round(floatval(str_replace(',', '', $informeQuery['3']->total_mes)));
            return $data;
        }

        $data = DB::table($tabla)
            ->select(DB::raw("COALESCE(SUM($saldoFinal), 0) AS total_saldo"))
            ->whereIn($cuenta, $cuentas)
            ->where('nit', $nit)
            ->where($fecha, date('Y-m-t', strtotime($fecha_fin)))
            ->value('total_saldo');

        $data = abs(floatval($data));

        return $data;
    }

    private function calcularAmortizacion($nit, $fecha_fin, $tipo)
    {

        if ($tipo == 'PYME') {
            $tabla = 'clientes';
            $fecha = 'fechareporte';
            $cuenta = 'cuenta';
            // $creditos = 'creditos';
            // $debitos = 'debitos';
            // $saldoInicial = 'saldo_anterior';
            $saldoFinal = 'nuevo_saldo';
        } elseif ($tipo == 'NUBE') {
            $tabla = 'clientes';
            $fecha = 'fechareporte_ga';
            $cuenta = 'codigo_cuenta_contable_ga';
            // $creditos = 'movimiento_credito_ga';
            // $debitos = 'movimiento_debito_ga';
            // $saldoInicial = 'saldo_inicial_ga';
            $saldoFinal = 'saldo_final_ga';
        } elseif ($tipo == 'CONTAPYME') {
            $tabla = 'contapyme_completo';
            $fecha = 'fechareporte';
            $cuenta = 'cuenta';
            // $creditos = 'creditos';
            // $debitos = 'debitos';
            // $saldoInicial = 'saldo_anterior';
            $saldoFinal = 'nuevo_saldo';
        } elseif ($tipo == 'LOGGRO') {
            $tabla = 'loggro';
            $fecha = 'fechareporte';
            $cuenta = 'cuenta';
            // $creditos = 'creditos';
            // $debitos = 'debitos';
            // $saldoInicial = 'saldo_anterior';
            $saldoFinal = 'nuevo_saldo';
        } elseif ($tipo == 'BEGRANDA') {
            $tabla = 'begranda';
            $fecha = 'fechareporte';
            $cuenta = 'cuenta';
            // $creditos = 'creditos';
            // $debitos = 'debitos';
            // $saldoInicial = 'saldo_anterior';
            $saldoFinal = 'nuevo_saldo';
        } elseif (in_array($tipo, $this->tiposGenericos)) {
            $tabla = 'informesgenericos';
            $fecha = 'fechareporte';
            $cuenta = 'cuenta';
            // $creditos = 'creditos';
            // $debitos = 'debitos';
            // $saldoInicial = 'saldo_anterior';
            $saldoFinal = 'saldo_final';
        }

        $data = DB::table($tabla)
            ->select(DB::raw("COALESCE(SUM($saldoFinal), 0) AS total_saldo"))
            ->where($cuenta, 5165)
            ->where('nit', $nit)
            ->where($fecha, date('Y-m-t', strtotime($fecha_fin)))
            ->value('total_saldo');

        $data = abs(floatval($data));

        return $data;
    }
}
