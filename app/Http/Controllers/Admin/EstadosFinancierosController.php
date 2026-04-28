<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ConsultaPorCuentasExport;
use App\Exports\InformeCostosGastosExport;
use App\Exports\InformeEstadoPatrimonioExport;
use App\Exports\InformeEstadoResultadosExport;
use App\Exports\InformeFlujoEfectivoExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateEstadosFinancierosRequest;
use App\Http\Requests\CreateOrdenInformesRequest;
use App\Models\AgrupacionesNIIF;
use App\Models\Begranda;
use App\Models\CentroCosto;
use App\Models\Clientes;
use App\Models\ClientesMoviemientos;
use App\Models\ClientesMovimientos;
use App\Models\Compania;
use App\Models\Contapyme;
use App\Models\ContapymeCompleto;
use App\Models\Empresa;
use App\Models\Impuestorenta;
use App\Models\InformesGenericos;
use App\Models\loggro;
use App\Models\Modificacion;
use App\Models\orden_compania_informes;
use App\Models\OrdenInformes;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Ramsey\Uuid\Type\Integer;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Intervention\Image\Facades\Image;
use Throwable;
use Illuminate\Support\Str;
use App\Exports\InformeGeneralExport;
use App\Services\MetasComparativeService;
use App\Services\MetasExportExcelService;
use App\Services\MetasExportPdfService;
use App\Services\ConsolidadorEstadosFinancierosService;

class EstadosFinancierosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $consolidador;

    public function __construct(ConsolidadorEstadosFinancierosService $consolidador)
    {
        $this->consolidador = $consolidador;
    }

    public function index()
    {
        abort_if(Gate::denies('GESTIONAR_INFORMES'), Response::HTTP_UNAUTHORIZED);
        $companias = Empresa::orderBy('razon_social')->pluck('razon_social', 'nit');
        return view('admin.estadosfinancieros.index', compact('companias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        abort_if(Gate::denies('CREAR_INFORMES'), Response::HTTP_UNAUTHORIZED);
        $companias = Empresa::pluck('razon_social', 'NIT');
        $selectedCompania = $request->input('compania'); // Compañía seleccionada

        $ordenPersonalizado = null; // Inicializa como null por defecto
        $opciones = []; // Inicializa las opciones como un array vacío

        if (!empty($selectedCompania)) {
            // Obtén el tipo de empresa
            $tipoEmpresa = Empresa::where('NIT', $selectedCompania)->value('tipo');
            
            // Filtra las opciones según el tipo de empresa
            $opciones = OrdenInformes::where('tipo', $tipoEmpresa)
                ->pluck('nombre', 'agrupador_cuenta')
                ->toArray();

            // Obtén el orden personalizado si existe
            $ordenPersonalizado = orden_compania_informes::where('nit', $selectedCompania)->first();
        }

        return view('admin.estadosfinancieros.ordencompania', compact('companias', 'opciones', 'ordenPersonalizado', 'selectedCompania'));
    }

    public function obtenerOrdenPersonalizado(Request $request, $compania)
    {
        // Obtén el tipo de empresa
        $tipoEmpresa = Empresa::where('NIT', $compania)->value('tipo');
        if($tipoEmpresa=='NUBE'){
            $tipoEmpresa ='PYME';
        }else{
            $tipoEmpresa ='CONTAPYME';
        }
        // Obtén las opciones personalizadas según la compañía y tipo
        $ordenPersonalizado = OrdenInformes::where('tipo', $tipoEmpresa)
            ->pluck('nombre', 'agrupador_cuenta')
            ->toArray();
    
        // Devuelve los datos en formato JSON
        return response()->json(['orden' => $ordenPersonalizado]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateEstadosFinancierosRequest $request)
    {
        // Verifica si el usuario tiene permisos para ver informes, de lo contrario, muestra un error 403 Forbidden
        abort_if(Gate::denies('VER_INFORMES'), Response::HTTP_UNAUTHORIZED);

        // Obtiene la información de la compañía basada en el NIT proporcionado en la solicitud
        $companias = Empresa::where('NIT', $request->input('compania'))->first();
        // Obtiene la orden de la compañía (asumiendo que solo hay un registro por NIT)
        $cuentas = orden_compania_informes::select('orden')->where('nit', $companias->NIT)->get();
        $orden = $cuentas->pluck('orden')->first();
        if (empty($orden)) {
            return redirect()->back()
                ->with('message2', 'No existen datos de ordenamiento para esta compañía. Comunícate con el administrador')
                ->with('color', 'warning');
        }

        if ($companias->empresaasociada) {

            $orden2 = orden_compania_informes::where('nit', $companias->empresaasociada)->value('orden');

            if (empty($orden2)) {
                return redirect()->back()
                    ->with('message2', 'No existen datos de ordenamiento para la compañia asociada NIT: '.$companias->empresaasociada.' Comunícate con el administrador')
                    ->with('color', 'warning');
            }

        }
        $contexto = $this->buildContexto($request, $companias, $orden);
        
        return match ((int)$request->estado) {
            1 => $this->estadoPycLocal($contexto),
            3 => $this->estadoGeneral($contexto),
            4 => $this->estadoResultados($contexto,$cuentas),
            5 => $this->estadoSituacionFinanciera($contexto),
            6 => $this->estadoCambioPatrimonios($contexto),
            7 => $this->estadoFlujoEfectivo($contexto),
            8 => $this->estadoGeneralCostos($contexto),
            default => abort(404),
        };
        
    }

    private function buildContexto($request, Empresa $empresa, $orden): array
    {
        $fecha = $request->input('fechareporte');
        $fechaCarbon = Carbon::parse($fecha)->firstOfMonth();
     
        return [
            'empresa' => $empresa,
            'nit' => $empresa->NIT,
            'siigo' => $empresa->tipo,
            'fecha' => $fecha,
            'fechaCarbon' => $fechaCarbon,
            'anio' => $fechaCarbon->year,
            'anioAnterior' => $fechaCarbon->year - 1,
            'estado' => (int)$request->estado,
            'tipoinforme' => $request->tipoinforme,
            'tipoinforme2' => $request->input('tipoinforme2'),
            'centro_costo' => $request->input('centro_costo'),
            'orden' => $orden,
        ];
    }

    private function estadoPycLocal($contexto)
    {
            $nit = $contexto['nit'];
            // Obtiene datos para el informe PYC local
            list($datos, $compania, $fecha) = $this->pyc($contexto['nit'], $contexto['fecha']);
            $fechareporte = $contexto['fecha'];
            // Busca en el modelo OrdenInformes y obtén los datos relevantes
            $data = ['CONTAPYME', 'PYME'];
            $resultados = OrdenInformes::whereIn('tipo', $data)
                ->whereRaw('LENGTH(agrupador_cuenta) <= 4') // Filtro para máximo 4 dígitos
                ->distinct() // Evitar duplicados
                ->get(['agrupador_cuenta', 'nombre']);

            // Agregar manualmente la cuenta 41 con el nombre 'Ventas'
            $resultados->push((object)[
                'agrupador_cuenta' => '41',
                'nombre' => 'Ventas',
            ]);
            $resultados->push((object)[
                'agrupador_cuenta' => '5',
                'nombre' => 'Gastos',
            ]);
            return view('admin.estadosfinancieros.pyclocal', compact('datos', 'compania', 'fecha','resultados','nit','fechareporte'));
    }

    private function estadoGeneral($contexto)
    {
        // Implementación del estado general
         // Obtiene datos para el informe general
        if ($contexto['tipoinforme'] == 2) {
            $datos2 = $this->Modificacion($contexto['nit'], $contexto['empresa']->id, $contexto['fecha']);
        } else {
            $datos2 = $this->Modificacion($contexto['nit'], $contexto['empresa']->id, $contexto['fecha']);
        }
        if($contexto['empresa']->empresaasociada){
            $informe1 = app(\App\Services\InformeMesaMesService::class)
                 ->ejecutar(
                    $contexto['nit'],
                    $contexto['fecha'],
                    $contexto['empresa']->tipo,
                    $datos2,
                    $contexto['centro_costo']
                );

            $informe2 = app(\App\Services\InformeMesaMesService::class)
                 ->ejecutar(
                    $contexto['empresa']->empresaasociada,
                    $contexto['fecha'],
                    $contexto['empresa']->tipo,
                    $datos2,
                    $contexto['centro_costo']
                );
            $operadorUsuario = $contexto['empresa']->operador;
            $informeResultados = $this->consolidador->combinarInformes($informe1, $informe2, $operadorUsuario);
        }else{
            $informeResultados = app(\App\Services\InformeMesaMesService::class)
            ->ejecutar(
                $contexto['nit'],
                $contexto['fecha'],
                $contexto['empresa']->tipo,
                $datos2,
                $contexto['centro_costo']
            );
        }
       
        $compania = $contexto['empresa']->razon_social;
            $fecha = Carbon::parse($contexto['fecha'])->locale('es_ES');
            $fecha = $fecha->isoFormat('MMMM-YYYY');
            // Obtiene el total general de los resultados
            $totalGeneral = $informeResultados['totalGeneral'];
            // Obtiene el informe por mes
            $informePorMes = $informeResultados['informePorMes'];
            // Obtiene totales del informe
            $totales = $informeResultados['totales'];
            $nit = $contexto['empresa']->NIT;
            $fecha_inicio = $contexto['fecha'];
            $cuentas = explode(",", $contexto['orden']);
            $siigo = $contexto['siigo'];
            // Elimina cualquier carácter no numérico de cada elemento del array
            $cuentas = array_map(function($elemento) {
                return preg_replace("/[^0-9]/", "", $elemento);
            }, $cuentas);
            $longitudPermitida = ($siigo === 'CONTAPYME') ? [1, 4] : [2, 4];
            $excluir = ['4250', '4295', '5250'];

            $cuentas = array_filter($cuentas, function ($elemento) use ($longitudPermitida, $excluir) {
                if (in_array($elemento, $excluir, true)) {
                    return false;
                }

                return in_array(strlen($elemento), $longitudPermitida, true);
            });

            
            // Busca en el modelo OrdenInformes y obtén los datos relevantes
            ///esto es para mostrar todas la cuentas en el select de detalles
            $resultados = OrdenInformes::select('agrupador_cuenta', 'nombre')
                ->whereIn('tipo', ['CONTAPYME', 'PYME'])
                ->whereRaw('LENGTH(agrupador_cuenta) <= 4')
                ->groupBy('agrupador_cuenta', 'nombre')
                ->get();

            $resultados->push((object)[
                'agrupador_cuenta' => '41',
                'nombre' => 'Ventas',
            ]);

            //nombre centro de costo 
            $centrocostov = CentroCosto::select('codigo', 'nombre')->where('id', $contexto['centro_costo'])
            ->whereNot('estado', [0])->first();
            $iaAnalisis = $this->analisisFinanciaroFiscalIA($informePorMes);
            
        // Metas de ventas
        $empresaId = $contexto['empresa']->id;
        $dateNumber = $contexto['fecha'];
        // Delegamos al Service
        $metasComparative = app(MetasComparativeService::class)->execute($empresaId, $informePorMes, $dateNumber);
        return view('admin.estadosfinancieros.estadogeneralcontapyme', compact('informePorMes', 'totalGeneral', 'compania', 'fecha', 'centrocostov', 'totales','nit','fecha_inicio','resultados','iaAnalisis','siigo','empresaId','dateNumber','metasComparative'));
    }


    public function exportMetas(Request $request)
    {
        abort_if(Gate::denies('VER_INFORMES'), Response::HTTP_FORBIDDEN);
        return app(MetasExportExcelService::class)->execute($request);
    }

    public function exportMetasPdf(Request $request)
    {
        abort_if(Gate::denies('VER_INFORMES'), Response::HTTP_FORBIDDEN);
        return app(MetasExportPdfService::class)->execute($request);
    }

    private function estadoGeneralCostos($contexto)
    {
        // Implementación del estado general
         // Obtiene datos para el informe general
        $datos2=null;
        if($contexto['empresa']->empresaasociada){

            $informe1 = app(\App\Services\InformeCostosMesaMesService::class)
                ->ejecutar(
                    $contexto['nit'],
                    $contexto['fecha'],
                    $contexto['empresa']->tipo,
                    $datos2,
                    $contexto['centro_costo']
                );

            $informe2 = app(\App\Services\InformeCostosMesaMesService::class)
                ->ejecutar(
                    $contexto['empresa']->empresaasociada,
                    $contexto['fecha'],
                    $contexto['empresa']->tipo,
                    $datos2,
                    $contexto['centro_costo']
                );

            $operadorUsuario = $contexto['empresa']->operador;

            $informeResultados = $this->consolidador
                ->combinarInformesCostosMes(
                    $informe1,
                    $informe2,
                    $operadorUsuario
                );

        }else{

            $informeResultados = app(\App\Services\InformeCostosMesaMesService::class)
                ->ejecutar(
                    $contexto['nit'],
                    $contexto['fecha'],
                    $contexto['empresa']->tipo,
                    $datos2,
                    $contexto['centro_costo']
                );
        }
        $fechaInicio = Carbon::parse($contexto['fecha'])->firstOfMonth();
        $mesLimite = $fechaInicio->month; // 1 a 12
        $meses = [
            1 => 'enero',
            2 => 'febrero',
            3 => 'marzo',
            4 => 'abril',
            5 => 'mayo',
            6 => 'junio',
            7 => 'julio',
            8 => 'agosto',
            9 => 'septiembre',
            10 => 'octubre',
            11 => 'noviembre',
            12 => 'diciembre',
        ];

        $mesesMostrar = array_slice($meses, 0, $mesLimite, true);
        $compania = $contexto['empresa']->razon_social;
        $fecha = Carbon::parse($contexto['fecha'])->locale('es_ES');
        $fecha = $fecha->isoFormat('MMMM-YYYY');
        $nit = $contexto['empresa']->NIT;
        $fecha_inicio = $contexto['fecha'];
        $siigo = $contexto['siigo'];
        $iaAnalisis = $this->analisisFinanciaroFiscalIA($informeResultados);
        return view('admin.estadosfinancieros.estadogeneralcostos', compact('informeResultados', 'mesesMostrar', 'compania', 'fecha', 'nit', 'fecha_inicio', 'siigo', 'iaAnalisis'));
       
    }

    private function estadoResultados($contexto, $cuentas)
    {
        $centro_costo = $contexto['centro_costo'];
        $nit = $contexto['nit'];
        $siigo = $contexto['siigo'];
        $tipoinforme = $contexto['tipoinforme'];
        $tipoinformeresultados= $contexto['tipoinforme2'];

        if($contexto['empresa']->empresaasociada){
            $informe1 = app(\App\Services\InformeEstadoResultadosServices::class)
                ->ejecutar(
                    $contexto['fecha'],
                    $contexto['nit'],
                    $contexto['siigo'],
                    $contexto['centro_costo'],
                    $contexto['tipoinforme'],
                    $tipoinformepdf = null,
                    $valorparautilidad=null,
                );

            $informe2 = app(\App\Services\InformeEstadoResultadosServices::class)
                ->ejecutar(
                    $contexto['fecha'],
                    $contexto['empresa']->empresaasociada,
                    $contexto['siigo'],
                    $contexto['centro_costo'],
                    $contexto['tipoinforme'],
                    $tipoinformepdf = null,
                    $valorparautilidad=null,
                );
                
            $operadorUsuario = $contexto['empresa']->operador;
            $informeResultados = $this->consolidador->combinarEstadoResultados($informe1, $informe2, $operadorUsuario);
               //estado de resultados para calcular la utilidad y mostrar en el estado de cambio en el patrimonio
            $informe1u = app(\App\Services\InformeEstadoResultadosServices::class)
                    ->ejecutar($contexto['fecha'],$contexto['nit'],$contexto['siigo'],$contexto['centro_costo'],$contexto['tipoinforme'],$tipoinformepdf = 2,$valorparautilidad=1,);

            $informe2u = app(\App\Services\InformeEstadoResultadosServices::class)
                ->ejecutar($contexto['fecha'],$contexto['empresa']->empresaasociada,$contexto['siigo'],$contexto['centro_costo'],$contexto['tipoinforme'],$tipoinformepdf = 2, $valorparautilidad=1,);
                
            $informeResultadosutilidad =$this->consolidador->combinarEstadoResultados($informe1u, $informe2u, $operadorUsuario);
             $anio=$informeResultados['anio'];
            $anioAnterior=$informeResultados['anioAnterior'];
            $mes = $informeResultados['mes'];
            $utilidadanio1=number_format($informeResultadosutilidad['informeData']['Utilidad (Perdida) Neta del periodo'][$anio], 0, '.', ',');
            $utilidadanio2=number_format($informeResultadosutilidad['informeData']['Utilidad (Perdida) Neta del periodo'][$anioAnterior], 0, '.', ',');
            $informesf1= app(\App\Services\InformeSituacionFinancieraServices::class)->ejecutar($contexto['fecha'],$contexto['nit'],$contexto['siigo'],$contexto['centro_costo'],2,$tipoinformeresultados,$utilidadanio1,$utilidadanio2);
            $informesf2= app(\App\Services\InformeSituacionFinancieraServices::class)->ejecutar($contexto['fecha'],$contexto['empresa']->empresaasociada,$contexto['siigo'],$contexto['centro_costo'],2,$tipoinformeresultados,$utilidadanio1,$utilidadanio2);
            $informeData2 = $this->consolidador->combinarSituacionFinanciera( $informesf1,$informesf2,$operadorUsuario);
        }else{
            // Implementación del estado de resultados
            $informeResultados = app(\App\Services\InformeEstadoResultadosServices::class)
            ->ejecutar(
                $contexto['fecha'],
                $contexto['nit'],
                $contexto['siigo'],
                $contexto['centro_costo'],
                $contexto['tipoinforme'],
                $tipoinformepdf = null,
                $valorparautilidad=null,
            );
             $informeResultadosutilidad =app(\App\Services\InformeEstadoResultadosServices::class)
            ->ejecutar(
                $contexto['fecha'],
                $contexto['nit'],
                $contexto['siigo'],
                $contexto['centro_costo'],
                $contexto['tipoinforme'],
                $tipoinformepdf = 2,
                $valorparautilidad=1,
            );
            $anio=$informeResultados['anio'];
            $anioAnterior=$informeResultados['anioAnterior'];
            $mes = $informeResultados['mes'];
            $utilidadanio1=number_format($informeResultadosutilidad['informeData']['Utilidad (Perdida) Neta del periodo'][$anio], 0, '.', ',');
            $utilidadanio2=number_format($informeResultadosutilidad['informeData']['Utilidad (Perdida) Neta del periodo'][$anioAnterior], 0, '.', ',');
            $informeData2= app(\App\Services\InformeSituacionFinancieraServices::class)->ejecutar($contexto['fecha'],$contexto['nit'],$contexto['siigo'],$contexto['centro_costo'],$contexto['tipoinforme'],$contexto['tipoinforme2'],$utilidadanio1,$utilidadanio2);
        }
       
        
        $informeData=$informeResultados['informeData'];
        

       

        $fecha_real=$contexto['fecha'];
        $fechatemporal = Carbon::parse($contexto['fecha'])->locale('es_ES');
        $anioo = $fechatemporal->isoFormat('YYYY'); // Obtiene el año, por ejemplo "2023"
        $numeroMes = null;
        $textoMes  = '';

        if (preg_match('/^corte\s*(\d+)$/i', $mes, $matches)) {
            $numeroMes = (int) $matches[1];
            $textoMes  = 'Corte';
        } elseif (is_numeric($mes)) {
            $numeroMes = (int) $mes;
        } else {
            $textoMes = 'Desconocido';
        }
        $mes = $numeroMes
        ? ucfirst(Carbon::create()->locale('es')->month($numeroMes)->translatedFormat('F'))
        : 0;
        $empresa   = $contexto['empresa'];
        $compania  = $empresa->razon_social;
        $grupoNiif = $empresa->gruponiif ?? '3';
        $agrupacionesBD = AgrupacionesNIIF::where('gruponiif', $grupoNiif)->get();
        // Array tipo: $agrupaciones
        $agrupaciones = $agrupacionesBD->mapWithKeys(function ($item) {
            return [
                $item->codigo => ['descripcion' => $item->descripcion],
            ];
        })->toArray();
         // Array tipo: $mensajesPredeterminados
        $mensajesPredeterminados = $agrupacionesBD->mapWithKeys(function ($item) {
            return [
                $item->codigo => $item->mensaje,
            ];
        })->toArray();
        // Busca en el modelo OrdenInformes y obtén los datos relevantes
         $fecha_inicio = $fecha_real;
         $fecha = $fecha_real;
        // Busca en el modelo OrdenInformes y obtén los datos relevantes
        $resultados = OrdenInformes::whereIn('agrupador_cuenta', $cuentas)->get(['agrupador_cuenta', 'nombre']);


        $iaAnalisis = $this->analisisFinanciaroFiscalIA($informeData);
        
            // Muestra la vista del informe general 
        return view('admin.estadosfinancieros.estadoresultados', compact('informeData','anio','anioAnterior','resultados', 'compania', 'fecha', 'centro_costo','nit','fecha_inicio','mes','textoMes','siigo','tipoinforme','agrupaciones','mensajesPredeterminados','iaAnalisis','fecha_real','tipoinformeresultados','informeData2'));
    }


    private function estadoSituacionFinanciera($contexto)
    {
        // Implementación del estado de situación financiera
        $fechatemporal = Carbon::parse($contexto['fecha'])->locale('es_ES');
        $anioo = $fechatemporal->isoFormat('YYYY'); // Obtiene el año, por ejemplo "2023"
        $empresa   = $contexto['empresa'];
        $centro_costo = $contexto['centro_costo'];
        $nit = $contexto['nit'];
        $siigo = $contexto['siigo'];
        $tipoinforme = $contexto['tipoinforme'];
        $tipoinformeresultados= $contexto['tipoinforme2'];
        $grupoNiif = $empresa->gruponiif ?? '3';
        $agrupacionesBD = AgrupacionesNIIF::where('gruponiif', $grupoNiif)->get();
        // Array tipo: $agrupaciones
        $agrupaciones = $agrupacionesBD->mapWithKeys(function ($item) {
            return [
                $item->codigo => ['descripcion' => $item->descripcion],
            ];
        })->toArray();

        // Array tipo: $mensajesPredeterminados
        $mensajesPredeterminados = $agrupacionesBD->mapWithKeys(function ($item) {
            return [
                $item->codigo => $item->mensaje,
            ];
        })->toArray();
        $fecha_real=$contexto['fecha'];
        $fecha = Carbon::parse($fecha_real)->firstOfMonth();
        $anio = $fecha->year;
        $anioAnterior = $anio - 1;
        $resultados='';
        $compania = $empresa->razon_social;
        $fecha_inicio=$fecha;
        $mes=0;
        $textoMes = '';
        if($tipoinformeresultados=='1'){
            $mes = $fecha->month;
            $informeData2=app(\App\Services\InformeSituacionFinancieraMesServices::class)->ejecutar($fecha,$nit,$siigo,$centro_costo,$tipoinforme,$tipoinformeresultados);
            $iaAnalisis = $this->analisisFinanciaroFiscalIA($informeData2);
        
            return view('admin.estadosfinancieros.estadosmesames', 
                        compact('informeData2','anio','anioAnterior','resultados', 'compania', 'fecha',
                                'centro_costo','nit','fecha_inicio','fecha_real','mes','textoMes','siigo','tipoinforme',
                                'agrupaciones','mensajesPredeterminados','iaAnalisis','tipoinformeresultados'));
        }else{
            if($contexto['empresa']->empresaasociada){
                $informe1 = app(\App\Services\InformeEstadoResultadosServices::class)
                    ->ejecutar($contexto['fecha'],$contexto['nit'], $contexto['siigo'],$contexto['centro_costo'],$tipoinformeresultados,$tipoinformepdf = 2,$valorparautilidad=1,);

                $informe2 = app(\App\Services\InformeEstadoResultadosServices::class)
                    ->ejecutar($contexto['fecha'],$contexto['empresa']->empresaasociada,$contexto['siigo'],$contexto['centro_costo'],$tipoinformeresultados,$tipoinformepdf = 2,$valorparautilidad=1,);
                    
                $operadorUsuario = $contexto['empresa']->operador;
                $informeResultados = $this->consolidador->combinarEstadoResultados($informe1, $informe2, $operadorUsuario);
            }else{
                // Implementación del estado de resultados
                $informeResultados = app(\App\Services\InformeEstadoResultadosServices::class)
                ->ejecutar($contexto['fecha'],$contexto['nit'], $contexto['siigo'],$contexto['centro_costo'],$tipoinformeresultados,$tipoinformepdf = 2,$valorparautilidad=1,);
            }
            $utilidadanio1=number_format($informeResultados['informeData']['Utilidad (Perdida) Neta del periodo'][$anio], 0, '.', ',');
            $utilidadanio2=number_format($informeResultados['informeData']['Utilidad (Perdida) Neta del periodo'][$anioAnterior], 0, '.', ',');
            
            if($contexto['empresa']->empresaasociada){
                $informesf1= app(\App\Services\InformeSituacionFinancieraServices::class)->ejecutar(
                    $fecha,
                    $nit,
                    $siigo,
                    $centro_costo,
                    $tipoinforme,
                    $tipoinformeresultados,
                    $utilidadanio1,
                    $utilidadanio2
                );
                $informesf2= app(\App\Services\InformeSituacionFinancieraServices::class)->ejecutar(
                    $fecha,
                    $contexto['empresa']->empresaasociada,
                    $siigo,
                    $centro_costo,
                    $tipoinforme,
                    $tipoinformeresultados,
                    $utilidadanio1,
                    $utilidadanio2
                );
                    
                $operadorUsuario = $contexto['empresa']->operador;

                $informeData2 = $this->consolidador->combinarSituacionFinanciera(
                    $informesf1,
                    $informesf2,
                    $operadorUsuario
                );
                
            }else{
                // Implementación del estado de resultados
                $informeData2 = app(\App\Services\InformeSituacionFinancieraServices::class)->ejecutar(
                    $fecha,
                    $nit,
                    $siigo,
                    $centro_costo,
                    $tipoinforme,
                    $tipoinformeresultados,
                    $utilidadanio1,
                    $utilidadanio2
                );
            }
            $iaAnalisis = $this->analisisFinanciaroFiscalIA($informeData2);
        
            return view('admin.estadosfinancieros.estadosfinancieros', 
                        compact('informeData2','anio','anioAnterior','resultados', 'compania', 'fecha',
                                'centro_costo','nit','fecha_inicio','fecha_real','mes','textoMes','siigo','tipoinforme',
                                'agrupaciones','mensajesPredeterminados','iaAnalisis','tipoinformeresultados'));
        }
    }

    private function estadoCambioPatrimonios($contexto)
    {
        // Implementación del estado de cambio en el patrimonio
        $fecha_real=$contexto['fecha'];
        $fecha = Carbon::parse($fecha_real)->firstOfMonth();
        $anio = $fecha->year;
        $anioAnterior = $anio - 1;
        $compania = $contexto['empresa']->razon_social;
        $tipoinformeresultados=2;
        $centro_costo = $contexto['centro_costo'];
        $nit = $contexto['nit'];
        $siigo = $contexto['siigo'];
        if($contexto['empresa']->empresaasociada){

            $patrimonio1 = app(\App\Services\InformeEstadoPatrimonioServices::class)
                ->ejecutar( $fecha,$contexto['nit'],$contexto['siigo'],$contexto['centro_costo'],3,$anio, $anioAnterior);

            $patrimonio2 = app(\App\Services\InformeEstadoPatrimonioServices::class)
                ->ejecutar( $fecha, $contexto['empresa']->empresaasociada,$contexto['siigo'], $contexto['centro_costo'],3,$anio,$anioAnterior );

            $informe = $this->consolidador->combinarCambioPatrimonio($patrimonio1,$patrimonio2,$contexto['empresa']->operador);
            //estado de resultados para calcular la utilidad y mostrar en el estado de cambio en el patrimonio
            $informe1 = app(\App\Services\InformeEstadoResultadosServices::class)
                    ->ejecutar($contexto['fecha'],$contexto['nit'],$contexto['siigo'],$contexto['centro_costo'],$contexto['tipoinforme'],$tipoinformepdf = 2,$valorparautilidad=1,);

            $informe2 = app(\App\Services\InformeEstadoResultadosServices::class)
                ->ejecutar($contexto['fecha'],$contexto['empresa']->empresaasociada,$contexto['siigo'],$contexto['centro_costo'],$contexto['tipoinforme'],$tipoinformepdf = 2, $valorparautilidad=1,);
                
            $operadorUsuario = $contexto['empresa']->operador;
            $informeResultadosdiciembre = $this->consolidador->combinarEstadoResultados($informe1, $informe2, $operadorUsuario);
            $utilidadanio1=number_format($informeResultadosdiciembre['informeData']['Utilidad (Perdida) Neta del periodo'][$anio], 0, '.', ',');
            $utilidadanio2=number_format($informeResultadosdiciembre['informeData']['Utilidad (Perdida) Neta del periodo'][$anio-1], 0, '.', ',');
            //estado de situacion financiera para mostrar en el estado de cambio en el patrimonio
            $informesf1= app(\App\Services\InformeSituacionFinancieraServices::class)->ejecutar($contexto['fecha'],$contexto['nit'],$contexto['siigo'],$contexto['centro_costo'],2,$tipoinformeresultados,$utilidadanio1,$utilidadanio2);
            $informesf2= app(\App\Services\InformeSituacionFinancieraServices::class)->ejecutar($contexto['fecha'],$contexto['empresa']->empresaasociada,$contexto['siigo'],$contexto['centro_costo'],2,$tipoinformeresultados,$utilidadanio1,$utilidadanio2);
            $informedetallado = $this->consolidador->combinarSituacionFinanciera( $informesf1,$informesf2,$operadorUsuario);

        }else{

            $informe = app(\App\Services\InformeEstadoPatrimonioServices::class)
                ->ejecutar( $fecha,$contexto['nit'],$contexto['siigo'],$contexto['centro_costo'],3,$anio, $anioAnterior);
            $informeResultadosdiciembre = app(\App\Services\InformeEstadoResultadosServices::class)
            ->ejecutar($contexto['fecha'],$contexto['nit'],$contexto['siigo'],$contexto['centro_costo'],$tipoinformeresultados,$tipoinformepdf = 2,$valorparautilidad=1,);
            $utilidadanio1=number_format($informeResultadosdiciembre['informeData']['Utilidad (Perdida) Neta del periodo'][$anio], 0, '.', ',');
            $utilidadanio2=number_format($informeResultadosdiciembre['informeData']['Utilidad (Perdida) Neta del periodo'][$anio-1], 0, '.', ',');
            $informedetallado = app(\App\Services\InformeSituacionFinancieraServices::class)->ejecutar($contexto['fecha'],$contexto['nit'],$contexto['siigo'],$contexto['centro_costo'],2,$tipoinformeresultados,$utilidadanio1,$utilidadanio2);
        }
        
       
        
        return view('admin.estadosfinancieros.estadocambiopatrimonio', compact('informe','compania','fecha_real','informedetallado'));

    }
    private function estadoFlujoEfectivo($contexto)
    {
        // Implementación del estado de flujo de efectivo
        $fecha_real=$contexto['fecha'];
        $fecha = Carbon::parse($fecha_real)->firstOfMonth();
        $anio = $fecha->year;
        $anioAnterior = $anio - 1;
        // Obtiene el nombre del mes en español y lo convierte a mayúsculas
        $mes = mb_strtoupper($fecha->translatedFormat('F'), 'UTF-8');
        $compania = $contexto['empresa']->razon_social;
        // Generar siempre el flujo de la empresa principal
        $flujo1 = $this->obtenerFlujoPorNit($contexto['nit'], $contexto['siigo'], $contexto['centro_costo'], $contexto['tipoinforme'], $fecha, $anio, $anioAnterior);

        if ($contexto['empresa']->empresaasociada) {
            // Generar flujo de la asociada
            $flujo2 = $this->obtenerFlujoPorNit($contexto['empresa']->empresaasociada, $contexto['siigo'], $contexto['centro_costo'], $contexto['tipoinforme'], $fecha, $anio, $anioAnterior);

            // Combinar
            $resultado = $this->consolidador->combinarFlujoEfectivo(
                $flujo1, 
                $flujo2, 
                $anio, 
                $anioAnterior, 
                $contexto['empresa']->operador
            );
        } else {
            $resultado = $flujo1;
        }

        // Asegurar que sea array
        $informes = is_string($resultado) ? json_decode($resultado, true) : $resultado;
        return view('admin.estadosfinancieros.estadodeflujosefectivo', compact('informes','compania','fecha_real','mes','anio','anioAnterior'));
    }

    private function obtenerFlujoPorNit($nit, $siggo,$centro_costo= null, $tipoinforme,$fecha, $anio, $anioAnterior)
    {
        // 1. Instanciar servicios (Solo una vez)
        $serviceER = app(\App\Services\InformeEstadoResultadosServices::class);
        $serviceSF = app(\App\Services\InformeSituacionFinancieraServices::class);
        $serviceFE = app(\App\Services\InformeEstadoFlujoEfectivoServices::class);

        // 2. Ejecutar Estado de Resultados
        $resutilidad = $serviceER->ejecutar($fecha, $nit, $siggo, $centro_costo, 2, 2, 1);

        // 3. Formatear utilidades para Situación Financiera
        $u1 = number_format($resutilidad['informeData']['Utilidad (Perdida) Neta del periodo'][$anio] ?? 0, 0, '.', ',');
        $u2 = number_format($resutilidad['informeData']['Utilidad (Perdida) Neta del periodo'][$anioAnterior] ?? 0, 0, '.', ',');

        // 4. Ejecutar Situación Financiera
        $situacion = $serviceSF->ejecutar($fecha, $nit, $siggo, $centro_costo, $tipoinforme, 2, $u1, $u2);

        // 5. Retornar el flujo generado
        return $serviceFE->generarFlujoEfectivo($situacion, $resutilidad, $anio);
    }

    public function boot()
    {
        Carbon::setLocale('es');
    }

    // analisis
    public function analisisFinanciaroFiscalIA( $informeData )
    {

        // return '';

        $informeData = json_encode($informeData);

        // $content = 'Por favor realice un análisis financiero y fiscal detallado de los siguientes estados financieros '. $informeData .',
        //             considerando indicadores macroeconómicos relevantes y el marco normativo colombiano.                     
        //             El análisis debe incluir una evaluación exhaustiva de la situación económica y financiera de la empresa, 
        //             identificando las áreas de oportunidad para optimizar el pago de impuestos de acuerdo con la normatividad vigente en Colombia. 
        //             Además, proporcione 10 recomendaciones de acciones estratégicas que puedan generar un impacto positivo en la empresa.
        //              Asimismo, indique 5 estrategias específicas para incrementar los ingresos basadas en el sector al que pertenece la empresa, 
        //              y sugiera 2 nuevas líneas de negocio relacionadas con la actividad principal que puedan diversificar y fortalecer el crecimiento de la empresa.
        //              ';

        $content = 'ayudame generar un informe de los siguientes estados financieros '. $informeData .' de manera profesional pero muy completo y facil de leer para la gerencia. ademas de recomendaciones al finalizar';

        try {
            $response = Http::withHeaders([
                "Content-Type" => "application/json",
                "Authorization" => "Bearer " . env('CHAT_GPT_KEY')
            ])->post('https://api.openai.com/v1/chat/completions', [
                "model" =>  env('CHAT_GPT_MODEL'),
                "messages" => [
                    [
                        "role" => "user",
                        "content" => $content,
                    ],
                    [
                        "role" => "user",
                        "content" => "ponerle una descripción de lo que se preguntó al inicio de la respuesta por favor"
                    ],
                    [
                        "role" => "user",
                        "content" => "no extenderse demasiado en los detalles de la respuesta por favor" 
                    ],
                    [
                        "role" => "user",
                        "content" => "no volver a mostrar todos los numeros de los estados financieros por favor, solo los necesarios para la respuesta"  
                    ],
                    [
                        "role" => "user",
                        "content" => "si la respuesta contiene una tabla por favor mostrarla en formato de lista"
                    ],
                    [
                        "role" => "user",
                        "content" => "solo requiero tu asistencia para la respuesta, no necesito que me hagas preguntas adicionales"
                    ],
                ],
                "temperature" => 0,
                "max_completion_tokens" => 2048
            ])->json();

            $htmlResponse = '<div>' . nl2br(e($response['choices'][0]['message']['content'])) . '</div>';
            //convertir **This is bold text** a <strong>This is bold text</strong>
            $htmlResponse = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $htmlResponse);
            //convertir *This is italic text* a <em>This is italic text</em>
            $htmlResponse = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $htmlResponse);
            // _This text is italicized_ a <em>This text is italicized</em>
            $htmlResponse = preg_replace('/_(.*?)_/', '<em>$1</em>', $htmlResponse);
             // # A first-level heading a <h1>A first-level heading</h1>
            $htmlResponse = preg_replace('/\#(.*?)\n/', '<h5>$1</h5>', $htmlResponse);
            // ## A second-level heading a <h2>A second-level heading</h2>
            $htmlResponse = preg_replace('/\#{2}(.*?)\n/', '<h5>$1</h5>', $htmlResponse);
            // ### A third-level heading a <h3>A third-level heading</h3>
            $htmlResponse = preg_replace('/\#{3}(.*?)\n/', '<h5>$1</h5>', $htmlResponse);
            
            

            return $htmlResponse;

            // dd($response['usage']['total_tokens']);
            // return $response['choices'][0]['message']['content'];

        } catch (Throwable $e) {          
            // return $e->getMessage();
            return 'Ocurrió un error al intentar obtener la respuesta. Por favor, inténtelo de nuevo recargando la página.';
        }
    }

    /**
     * Obtiene datos de modificación por cuenta contable para un informe en un mes específico.
     *
     * @param string $nit NIT de la compañía.
     * @param int $id ID de la compañía.
     * @param string $fecha Fecha del informe.
     * @return \Illuminate\Support\Collection Datos de modificación para un informe.
     */
    private function Modificacion($nit, $id, $fecha)
    {
        // Obtiene las cuentas y nombres de la compañía basadas en el NIT
        $cuentas = OrdenInformes::select('agrupador_cuenta', 'nombre')->where('nit', $nit)->get();
        $codigosCuenta = $cuentas->pluck('agrupador_cuenta')->toArray();

        // Obtén el mes a partir de la fecha proporcionada
        // Configura el idioma en español (España)
        $mes = Carbon::parse($fecha)->locale('es_ES');
        $mes = strtoupper($mes->isoFormat('MMMM'));

        // Consulta datos de modificación para el informe en un mes específico
        $datosmodificados = Modificacion::selectRaw('MAX(movimiento) as cuenta')
            ->selectRaw('(SELECT MAX(oi.nombre) FROM ordeninformes oi WHERE SUBSTRING(movimiento, 1, 4) = oi.agrupador_cuenta) AS nombre_orden_informes')
            ->selectRaw('TRIM(TRAILING ".00" FROM FORMAT(SUM(valor_ajustado), 2)) as valorajustado')
            ->where('compania_id', $id)
            ->where(function ($query) use ($codigosCuenta) {
                foreach ($codigosCuenta as $codigo) {
                    $query->orWhereRaw('SUBSTRING(movimiento, 1, 4) = ?', [$codigo]);
                }
            })
            ->whereRaw('YEAR(periodo) = YEAR(?)', [$fecha])
            ->whereRaw('MONTH(periodo) = MONTH(?)', [$fecha])
            ->groupBy('nombre_orden_informes')
            ->get()
            ->map(function ($item) use ($mes) {
                $item->mes = $mes; // Agrega el mes a cada elemento de la colección
                return $item;
            });

        return $datosmodificados;
    }


    /**
     * Obtiene datos específicos para generar un informe PYC basado en el tipo de SYYGO.
     *
     * @param string $nit NIT de la compañía.
     * @param string $fecha Fecha del informe.
     * @return array Datos para el informe PYC.
     */
    private function pyc($nit, $fecha)
    {
        // Obtiene la información de la compañía basada en el NIT
        $companias = Empresa::where('nit', $nit)->first();
        $datos2 = $this->Modificacion($companias->NIT, $companias->id, $fecha);
        $datos = app(\App\Services\InformeBalanceCuentasServices::class)
            ->ejecutar($companias->NIT, $fecha,$datos2,$companias->tipo);
        // Obtiene la razón social y formatea la fecha para mostrar en el informe
        $compania = $companias->razon_social;
        $fecha = Carbon::parse($fecha)->locale('es_ES');
        $fecha = $fecha->isoFormat('MMMM-YYYY');

        return [$datos, $compania, $fecha];
    }

    /**
     * Genera un informe general de cuentas para un período específico.
     *
     * @param string $nit NIT de la compañía.
     * @param string $fechaInicio Fecha de inicio del informe.
     * @param string $siigo Tipo de compañía (NUBE o no).
     * @param array $datos2 Datos de modificación para ajustar el informe.
     * @return array Informe general de cuentas por mes y total general.
     */

    public function pdfestadoresultado(Request $request){
         // Configurar opciones de Dompdf
        $data = json_decode($request->input('messages'), true) ?? [];
        $mensajes = $data['mensajes'];
        $flujoEfectivo = $data['flujo_efectivo'];
        $dictamenFiscal = $data['dictamen_fiscal'];
       
        $nit=$request->input('nit');
        $siigo=$request->input('siigo');
        $centro_costo=$request->input('centro_costo');
        $tipoinforme = $request->input('tipoinforme') ?? "2";
        $tipoinformeresultados = $request->input('tipoinforme2') ?? "2";
        if($flujoEfectivo){
            $verpatrimonioyflujo = 1;
        }else{
            $verpatrimonioyflujo = $tipoinforme == 3 ? 1 : 0;
        }
        $fechareal =Carbon::parse($request->input('fechareal'))->format('Y-m-d');
        $fechaInicio = Carbon::parse($request->input('fechaInicio'));
        $aniototal = $fechaInicio->year; // Obtén el año antes de formatear
        $fechaInicio = $fechaInicio->format('Y-m-d'); // Formatea la fecha después
        $fechapatrimonio = Carbon::parse($request->input('fechareal'))->firstOfMonth(); //fecha para estadocambopatrimonio
        $contadorid = Empresa::where('NIT', $nit)->value('contador');
        $empresaasociada = Empresa::where('NIT', $nit)->value('empresaasociada');
        if($contadorid == null) {
            $contadorid = 1;
        }
        $datoscontador=User::select('nombres','apellidos','tarje_profesional','firma')->find($contadorid);
        $representantelegal=Empresa::select('Cedula','representantelegal','razon_social','tipo','actividadeconomica','firmarepresentante','firmarevisorfiscal','logocliente','revisorfiscal','cedularevisor','gruponiif')->where('NIT',$nit)->first();
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $utilidadanio1=0;
        $utilidadanio2=0;
        if($empresaasociada){
            $operador = Empresa::where('NIT', $nit)->value('operador') ?? '+';
            // =============================
            // ESTADO RESULTADOS DICIEMBRE
            // =============================

            $infResDic1 = app(\App\Services\InformeEstadoResultadosServices::class)
                ->ejecutar($fechaInicio,$nit,$siigo,$centro_costo,$tipoinforme,2,1);

            $infResDic2 = app(\App\Services\InformeEstadoResultadosServices::class)
                ->ejecutar($fechaInicio,$empresaasociada,$siigo,$centro_costo,$tipoinforme,2,1);

            $operador = $operador;

            $informeResultadosdiciembre = $this->consolidador->combinarEstadoResultados(
                $infResDic1,
                $infResDic2,
                $operador
            );
            // =============================
            // UTILIDADES
            // =============================

            $utilidadanio1 = number_format(
                $informeResultadosdiciembre['informeData']['Utilidad (Perdida) Neta del periodo'][$aniototal],
                0,'.',','
            );

            $utilidadanio2 = number_format(
                $informeResultadosdiciembre['informeData']['Utilidad (Perdida) Neta del periodo'][$aniototal-1],
                0,'.',','
            );

            $utilidadanio1pdf = number_format(
                $informeResultadosdiciembre['informeData']['Utilidad (Perdida) Neta del periodo']['variacion$'],
                0, '.', ','
            );
            $utilidadanio2pdf = number_format(
                $informeResultadosdiciembre['informeData']['Utilidad (Perdida) Neta del periodo'][$aniototal-1],
                0, '.', ','
            );


            // =============================
            // ESTADO RESULTADOS PDF
            // =============================

            $infRes1 = app(\App\Services\InformeEstadoResultadosServices::class)
                ->ejecutar($fechaInicio,$nit,$siigo,$centro_costo,$tipoinforme,2,0);

            $infRes2 = app(\App\Services\InformeEstadoResultadosServices::class)
                ->ejecutar($fechaInicio,$empresaasociada,$siigo,$centro_costo,$tipoinforme,2,0);

            $informeResultados = $this->consolidador->combinarEstadoResultados(
                $infRes1,
                $infRes2,
                $operador
            );


            // =============================
            // SITUACION FINANCIERA
            // =============================

            $infSF1 = app(\App\Services\InformeSituacionFinancieraServices::class)
                ->ejecutar($fechaInicio,$nit,$siigo,$centro_costo,2,$tipoinforme,$utilidadanio1,$utilidadanio2);

            $infSF2 = app(\App\Services\InformeSituacionFinancieraServices::class)
                ->ejecutar($fechaInicio,$empresaasociada,$siigo,$centro_costo,2,$tipoinforme,$utilidadanio1,$utilidadanio2);


            $informedetallado = $this->consolidador->combinarSituacionFinanciera(
                $infSF1,
                $infSF2,
                $operador
            );

        }else{

            // =============================
            // EMPRESA NORMAL
            // =============================

            $informeResultadosdiciembre = app(\App\Services\InformeEstadoResultadosServices::class)
                ->ejecutar($fechaInicio,$nit,$siigo,$centro_costo,$tipoinforme,2,1);

            $utilidadanio1 = number_format(
                $informeResultadosdiciembre['informeData']['Utilidad (Perdida) Neta del periodo'][$aniototal],
                0,'.',','
            );

            $utilidadanio2 = number_format(
                $informeResultadosdiciembre['informeData']['Utilidad (Perdida) Neta del periodo'][$aniototal-1],
                0,'.',','
            );
            $utilidadanio1pdf = number_format(
                $informeResultadosdiciembre['informeData']['Utilidad (Perdida) Neta del periodo']['variacion$'],
                0, '.', ','
            );
            $utilidadanio2pdf = number_format(
                $informeResultadosdiciembre['informeData']['Utilidad (Perdida) Neta del periodo'][$aniototal-1],
                0, '.', ','
            );

            $informeResultados = app(\App\Services\InformeEstadoResultadosServices::class)
                ->ejecutar($fechaInicio,$nit,$siigo,$centro_costo,$tipoinforme,2,0);

            $informedetallado = app(\App\Services\InformeSituacionFinancieraServices::class)
                ->ejecutar($fechaInicio,$nit,$siigo,$centro_costo,2,$tipoinforme,$utilidadanio1,$utilidadanio2);
        }
        if($verpatrimonioyflujo == 1){
           
            if($empresaasociada){
                $flujo1 = $this->obtenerFlujoPorNit($nit, $siigo, $centro_costo, $tipoinforme, $fechapatrimonio, $aniototal, $aniototal-1);
                $flujo2 = $this->obtenerFlujoPorNit($empresaasociada, $siigo, $centro_costo, $tipoinforme, $fechapatrimonio, $aniototal, $aniototal-1);
                $informeestadoflujoefectivo = $this->consolidador->combinarFlujoEfectivo($flujo1,$flujo2,$aniototal,$aniototal-1,$operador);

                $patrimonio1 = app(\App\Services\InformeEstadoPatrimonioServices::class)
                ->ejecutar($fechapatrimonio, $nit, $siigo, $centro_costo, $tipoinforme, $aniototal, $aniototal-1);    
                $patrimonio2 = app(\App\Services\InformeEstadoPatrimonioServices::class)
                ->ejecutar( $fechapatrimonio, $empresaasociada,$siigo, $centro_costo,3,$aniototal,$aniototal-1 );
                $informecambiopatrimonio = $this->consolidador->combinarCambioPatrimonio($patrimonio1,$patrimonio2,$operador);
            }else{
                $informecambiopatrimonio = app(\App\Services\InformeEstadoPatrimonioServices::class)
                ->ejecutar($fechapatrimonio, $nit, $siigo, $centro_costo, $tipoinforme, $aniototal, $aniototal-1);
                $informeestadoflujoefectivo =$this->obtenerFlujoPorNit($nit, $siigo, $centro_costo, $tipoinforme, $fechapatrimonio, $aniototal, $aniototal-1);
                
            }
        }else{
            $informecambiopatrimonio = [];
            $informeestadoflujoefectivo = [];
        }
       
        if($empresaasociada){

            $notas1 = $this->consultaporcuentanotaspdf(
                $nit,
                $fechaInicio,
                $numeroCuenta = null
            );

            $notas2 = $this->consultaporcuentanotaspdf(
                $empresaasociada,
                $fechaInicio,
                $numeroCuenta = null
            );

            $cuentasnotas = $this->consolidador->combinarNotasSituacion(
                $notas1,
                $notas2,
                $operador
            );

        }else{

            $cuentasnotas = $this->consultaporcuentanotaspdf(
                $nit,
                $fechaInicio,
                $numeroCuenta = null
            );
        }
        $informeData=$informeResultados['informeData'];
        $anio=$informeResultados['anio'];
        $anioAnterior=$informeResultados['anioAnterior'];
        $mes = $informeResultados['mes'];
        function numero_a_palabras($numero) {
            $numeros = [
                1 => 'uno',
                2 => 'dos',
                3 => 'tres',
                4 => 'cuatro',
                5 => 'cinco',
                6 => 'seis',
                7 => 'siete',
                8 => 'ocho',
                9 => 'nueve',
                10 => 'diez',
                11 => 'once',
                12 => 'doce',
                13 => 'trece',
                14 => 'catorce',
                15 => 'quince',
                16 => 'dieciséis',
                17 => 'diecisiete',
                18 => 'dieciocho',
                19 => 'diecinueve',
                20 => 'veinte',
                21 => 'veintiuno',
                22 => 'veintidós',
                23 => 'veintitrés',
                24 => 'veinticuatro',
                25 => 'veinticinco',
                26 => 'veintiséis',
                27 => 'veintisiete',
                28 => 'veintiocho',
                29 => 'veintinueve',
                30 => 'treinta',
                31 => 'treinta y uno'
            ];
        
            return $numeros[$numero];
        }
        // Establecer la localización a español
        Carbon::setLocale('es');
        $fecha = Carbon::parse($fechareal); // Utiliza la fecha que tienes
        
        $dia_palabras = numero_a_palabras($fecha->day);
        $dia_numero = $fecha->day;
        $mes3 =$fecha->month;
        $mes2 = $fecha->translatedFormat('F'); // Esto obtiene el mes en españoL
        if (is_numeric($mes)) {
            // Si la variable contiene solo números
            $numeroMes = $mes;
            $textoMes = '';
        } elseif (preg_match('/^corte\s*(\d+)$/i', $mes, $matches)) {
            // Si la variable contiene "corte" seguido de un número
            $numeroMes = $matches[1];  // Captura el número después de "corte"
            $textoMes = 'Corte';
        } else {
            // Si no es ni solo números ni "corte X", puedes manejar el caso como quieras
            $numeroMes = null;
            $textoMes = 'Desconocido';
        }
        if($numeroMes){
            $meses = [
                1 => 'Enero',
                2 => 'Febrero',
                3 => 'Marzo',
                4 => 'Abril',
                5 => 'Mayo',
                6 => 'Junio',
                7 => 'Julio',
                8 => 'Agosto',
                9 => 'Septiembre',
                10 => 'Octubre',
                11 => 'Noviembre',
                12 => 'Diciembre'
            ];
            
            $mes = $meses[$numeroMes];
        }else{
            $mes=0;
        }
        ini_set('max_execution_time', 300); // 300 segundos = 5 minutos
        // Obtener la imagen de la firma del contador
        $base64Imagefirmacontador = $this->getBase64Image($datoscontador['firma'] ?? null, 'storage/users_firma');
        // Obtener la firma del representante legal
        $representantelegalfirma = $this->getBase64Image($representantelegal['firmarepresentante'] ?? null, 'storage/representante_firma');
        // Obtener la firma del revisor fiscal
        $revisorfiscalfirma = $this->getBase64Image($representantelegal['firmarevisorfiscal'] ?? null, 'storage/revisor_firma');
        // Obtener el logo cliente
        $logocliente = $this->getBase64Image($representantelegal['logocliente'] ?? null, 'storage/logo_cliente');
        $agrupacionesniif2 = [];
        if($representantelegal['gruponiif'] == null){
            $representantelegal['gruponiif'] = '3'; // Asignar un valor por defecto si es null
        }
        if($representantelegal['gruponiif'] == '2'){
              $agrupacionesBD = AgrupacionesNIIF::where('gruponiif', $representantelegal['gruponiif'])->get();

            // Array tipo: $agrupaciones
            $agrupacionesniif2 = $agrupacionesBD->mapWithKeys(function ($item) {
                return [
                    $item->codigo => ['descripcion' => $item->descripcion],
                ];
            })->toArray();
        }
        // Cargar la vista
        $html = view('admin.estadosfinancieros.pdf-estados-resultados', compact('nit','dia_palabras','dia_numero','mes2','mes3','informeData','informedetallado', 'anio', 'anioAnterior', 'mes','datoscontador','base64Imagefirmacontador','representantelegal','agrupacionesniif2','mensajes','representantelegalfirma','revisorfiscalfirma','logocliente','informecambiopatrimonio','informeestadoflujoefectivo','cuentasnotas','verpatrimonioyflujo','siigo','dictamenFiscal'))->render();

        // Cargar el HTML en Dompdf
        $dompdf->loadHtml($html);

        // (Opcional) Configurar el tamaño de papel y la orientación
        $dompdf->setPaper('A4', 'portrait');

        // Renderizar el PDF
        $dompdf->render();
        // Descargar el PDF
        
        return $dompdf->stream('certificacion_estados_financieros.pdf',[
            'Attachment' => false // Cambiado a false para abrir en otra pestaña
        ]);
    }

    private function getBase64Image($imageName, $path, $defaultImage = 'default.jpg', $maxWidth = 800, $maxHeight = 600) 
    {
        
         // ⛔ Si viene null, vacío o es la imagen por defecto
        if (empty($imageName) || $imageName === $defaultImage) {
            return '*';
        }

        $imagePath = public_path($path . '/' . $imageName);
    
        if (file_exists($imagePath)) {
            // Obtener las dimensiones de la imagen
            list($width, $height, $type) = getimagesize($imagePath);
    
            // Calcular nuevas dimensiones manteniendo la relación de aspecto
            $ratio = min($maxWidth / $width, $maxHeight / $height);
            $newWidth = (int)($width * $ratio);
            $newHeight = (int)($height * $ratio);
    
            // Crear una nueva imagen en blanco
            $newImage = imagecreatetruecolor($newWidth, $newHeight);
    
            // Manejar la transparencia para PNG y GIF
            if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_GIF) {
                // Para PNG y GIF, preservar la transparencia
                imagealphablending($newImage, false); // Desactivar la mezcla de píxeles
                imagesavealpha($newImage, true); // Habilitar la preservación de la transparencia
                $transparentColor = imagecolorallocatealpha($newImage, 255, 255, 255, 127); // Color transparente
                imagefill($newImage, 0, 0, $transparentColor); // Rellenar con color transparente
            } else {
                // Para JPG, usar un fondo blanco
                $white = imagecolorallocate($newImage, 255, 255, 255); // Color de fondo blanco para JPG
                imagefill($newImage, 0, 0, $white); // Rellenar con color blanco
            }
    
            // Crear una imagen desde el archivo original
            switch ($type) {
                case IMAGETYPE_JPEG:
                    $sourceImage = imagecreatefromjpeg($imagePath);
                    break;
                case IMAGETYPE_PNG:
                    $sourceImage = imagecreatefrompng($imagePath);
                    imagealphablending($sourceImage, true); // Permitir mezcla de píxeles
                    imagesavealpha($sourceImage, true); // Guardar la información de alpha
                    break;
                case IMAGETYPE_GIF:
                    $sourceImage = imagecreatefromgif($imagePath);
                    break;
                default:
                    return '*'; // Tipo de imagen no soportado
            }
    
            // Redimensionar la imagen
            imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
    
            // Guardar la imagen en un recurso temporal como PNG
            $tempImagePath = tempnam(sys_get_temp_dir(), 'img') . '.png'; // Cambiar a .png
            imagepng($newImage, $tempImagePath); // Guarda la imagen como PNG
    
            // Leer el contenido del archivo temporal
            $imageData = file_get_contents($tempImagePath);
    
            // Limpiar
            unlink($tempImagePath);
            imagedestroy($newImage);
            imagedestroy($sourceImage);
    
            return base64_encode($imageData); // Retorna la imagen en formato base64
        }
    
        return '*'; // Retorna '*' si el archivo no existe
    }

    public function pdfgeneral(Request $request){
        
        $datos = json_decode($request->input('tableData'), true);
        $compania =$request->input('compania');
        $fecha = $request->input('fecha');
        $tipoinforme = $request->input('tipopdf');
        $nit = Empresa::where('razon_social', $compania)->value('NIT');
        $operador = Empresa::where('NIT', $nit)->value('operador') ?? '+';
        $empresaasociada = Empresa::where('NIT', $nit)->value('empresaasociada');
        if($tipoinforme == 3){
            $data = [
                'tableData' => $datos,
                'totales'  => json_decode($request->input('totales'), true),
                'compania'  => $compania,
                'fecha'     => $fecha,
                'nit'       => $nit
            ];
            return Excel::download(
                new InformeGeneralExport($data),
                'Estado de resultados-'.$compania.'-'.$fecha.'.xlsx'
            );
        }
        if($tipoinforme == 5){
            $siigo = Empresa::where('Nit', $nit)->value('tipo');
            $informe1 =app(\App\Services\InformeCostosMesaMesService::class)
            ->ejecutar(
                $nit,
                $fecha,
                $siigo,
                $datos2= null,
                $centro_costo= null,
            );
             if($empresaasociada){
                $informe2 =app(\App\Services\InformeCostosMesaMesService::class)
                ->ejecutar(
                    $empresaasociada,
                    $fecha,
                    $siigo,
                    $datos2= null,
                    $centro_costo= null,
                );
                $tableData = $this->consolidador->combinarInformesCostosMes(
                    $informe1,
                    $informe2,
                    $operador
                );
             }else{
                $tableData = $informe1;
             }
            $imagePathLogo = public_path("images/logos/logo_contable.png");
            $imageDataLogo = file_get_contents($imagePathLogo);
            $logo = base64_encode($imageDataLogo);
            return Excel::download(
                new InformeCostosGastosExport($tableData, $fecha,$compania,$nit,$logo),
                'Estado de Costos-Gastos-'.$compania.'-'.$fecha.'.xlsx'
            );
        }
        if($tipoinforme==0){
            // Ordena los datos para que 'ACTIVOS DE MENOR CUANTIA' aparezca al final
            // Convierte el array a una colección
            $tableData = collect($datos)->sort(function ($a, $b) {
                if ($a['nombre_orden_informes'] === 'ACTIVOS DE MENOR CUANTIA') {
                    return 1; // 'ACTIVOS DE MENOR CUANTIA' va al final
                }
                if ($b['nombre_orden_informes'] === 'ACTIVOS DE MENOR CUANTIA') {
                    return -1; // Todos los demás van antes
                }
                return 0; // Mantiene el orden original si ninguno es 'ACTIVOS DE MENOR CUANTIA'
            });
            $informe='Balance General';
            $totales = '0';
        }elseif($tipoinforme==4){
            $siigo = Empresa::where('Nit', $nit)->value('tipo');

            $informe1 =app(\App\Services\InformeCostosMesaMesService::class)
            ->ejecutar(
                $nit,
                $fecha,
                $siigo,
                $datos2= null,
                $centro_costo= null,
            );
             if($empresaasociada){
                $informe2 =app(\App\Services\InformeCostosMesaMesService::class)
                ->ejecutar(
                    $empresaasociada,
                    $fecha,
                    $siigo,
                    $datos2= null,
                    $centro_costo= null,
                );
                $tableData = $this->consolidador->combinarInformesCostosMes(
                    $informe1,
                    $informe2,
                    $operador
                );
             }else{
                $tableData = $informe1;
             }
            $totales =  json_decode($request->input('totales'), true);
            $informe='Estado de resultados ingresos y gastos';

        }else{
            $tableData =$datos;
            $totales =  json_decode($request->input('totales'), true);
            $informe='Informe Estado de resultados acumulado';
        }
        
        //Lógica para generar el PDF con Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true); // Enable PHP in Dompdf if needed
        $dompdf = new Dompdf($options);
        $imagePathLogo = public_path("images/logos/logo_contable.png");
        $imageDataLogo = file_get_contents($imagePathLogo);
        $base64ImageLogo = base64_encode($imageDataLogo);
        $html = view('admin.estadosfinancieros.pdf_informes', compact('tableData','base64ImageLogo','compania','fecha','tipoinforme','informe','totales','nit'))->render(); // Reemplaza 'admin.cotizador.pdf' por la ruta real de tu vista
        // Logo encabezado
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A3', 'landscape'); // Cambiado a 'landscape'
        
        $dompdf->render();
        $dompdf->stream('Informe.pdf', [
            'Attachment' => false // Cambiado a false para abrir en otra pestaña
        ]);
    }

    public function pdfmesames(Request $request){
            
        $datos = json_decode($request->input('tableData'), true);
        
        $fecha = $request->input('fechareal');
        $tipoinforme = $request->input('tipopdf');
        $nit = $request->input('nit');
        $compania = Empresa::where('Nit',$nit)->value('razon_social');
        $siigo = $request->input('siigo');
        $centro_costo = $request->input('centro_costo');
        $tipoinforme = $request->input('tipoinforme');
        $tipoinformeresultados = $request->input('tipoinforme2');
        $informe='Estado situacion financiera  mes a mes';
        $informeData2=app(\App\Services\InformeSituacionFinancieraMesServices::class)->ejecutar($fecha,$nit,$siigo,$centro_costo,$tipoinforme,$tipoinformeresultados);
        //Lógica para generar el PDF con Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true); // Enable PHP in Dompdf if needed
        $dompdf = new Dompdf($options);
        $imagePathLogo = public_path("images/logos/logo_contable.png");
        $imageDataLogo = file_get_contents($imagePathLogo);
        $base64ImageLogo = base64_encode($imageDataLogo);
        $html = view('admin.estadosfinancieros.pdf_mesames', compact('informeData2','base64ImageLogo','compania','fecha','tipoinforme','informe','nit'))->render(); // Reemplaza 'admin.cotizador.pdf' por la ruta real de tu vista
        // Logo encabezado
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A3', 'landscape'); // Cambiado a 'landscape'
        
        $dompdf->render();
        $dompdf->stream('Informe.pdf', [
            'Attachment' => false // Cambiado a false para abrir en otra pestaña
        ]);
    }

    public function InformacionpycPDF($siigo,$nit,$fecha){
        // Obtiene la orden de la compañía (asumiendo que solo hay un registro por NIT)
        $cuentas = orden_compania_informes::select('orden')->where('nit', $nit)->get();
        $fechaInicio = Carbon::parse($fecha)->firstOfMonth();
        $anio = $fechaInicio->year;
        $anioAnterior = $anio - 1;
        $mesn='0';
        $anioActual = $anio;
        $orden = $cuentas->pluck('orden')->first();
        $ordenArray = array_unique(json_decode($orden, true));
        $ordenArray = json_decode($orden, true); // Decodifica el JSON a un array
        if($siigo!= 'CONTAPYME'){
              // Limitar los valores a un máximo de 4 dígitos
            $ordenArray = array_map(function($codigo) {
                return substr($codigo, 0, 4); // Tomar los primeros 4 caracteres
            }, $ordenArray);
        }
        $descripciones = DB::table('ordeninformes')
            ->select('agrupador_cuenta', 'nombre')
            ->whereIn('agrupador_cuenta', $ordenArray)
            ->get();
        // Fecha seleccionada por el usuario
        $fechaInicio = Carbon::parse($fecha); // Asegúrate de que $fechaSeleccionada tenga la fecha en un formato que Carbon pueda parsear
        // Fecha final, un año antes
        $fechaFinal = $fechaInicio->copy()->subYear();
        if ($siigo == 'NUBE') {
            $informeQuery = DB::table('clientesmovimientos')
                    ->selectRaw('CASE 
                                    WHEN SUBSTRING(clientesmovimientos.codigo_contable_sw, 1, 2) = "42" THEN "42" 
                                    WHEN SUBSTRING(clientesmovimientos.codigo_contable_sw, 1, 2) = "53" THEN "53" 
                                    WHEN SUBSTRING(clientesmovimientos.codigo_contable_sw, 1, 4) = "4180" THEN "4135"
                                    WHEN SUBSTRING(clientesmovimientos.codigo_contable_sw, 1, 4) = "5250" THEN "5245" 
                                    WHEN SUBSTRING(clientesmovimientos.codigo_contable_sw, 1, 4) = "6225" THEN "42" 
                                    WHEN SUBSTRING(clientesmovimientos.codigo_contable_sw, 1, 2) = "54" THEN "54"
                                    ELSE SUBSTRING(clientesmovimientos.codigo_contable_sw, 1, 4)
                                END AS cuenta')
                    ->selectRaw('CASE 
                                    WHEN SUBSTRING(clientesmovimientos.codigo_contable_sw, 1, 2) = "42" THEN "Otros Ingresos" 
                                    WHEN SUBSTRING(clientesmovimientos.codigo_contable_sw, 1, 2) = "71" THEN "COSTO MERCANCIA VENDIDA"
                                    WHEN SUBSTRING(clientesmovimientos.codigo_contable_sw, 1, 2) = "53" THEN "Otros Egresos"
                                    WHEN SUBSTRING(clientesmovimientos.codigo_contable_sw, 1, 4) = "4180" THEN "VENTAS" 
                                    WHEN SUBSTRING(clientesmovimientos.codigo_contable_sw, 1, 4) = "5250" THEN "Mantto. Ed. Y Equipos "
                                    WHEN SUBSTRING(clientesmovimientos.codigo_contable_sw, 1, 4) = "6225" THEN "Otros Ingresos"
                                    WHEN SUBSTRING(clientesmovimientos.codigo_contable_sw, 1, 2) = "54" THEN "Impuesto de renta"
                                    WHEN SUBSTRING(clientesmovimientos.codigo_contable_sw, 1, 2) = "54" THEN "Impuesto de renta"
                                    WHEN SUBSTRING(clientesmovimientos.codigo_contable_sw, 1, 2) = "71" THEN "COSTO MERCANCIA VENDIDA"
                                    WHEN SUBSTRING(clientesmovimientos.codigo_contable_sw, 1, 2) = "54" THEN "Impuesto de renta"
                                    WHEN SUBSTRING(clientesmovimientos.codigo_contable_sw, 1, 2) = "71" THEN "COSTO MERCANCIA VENDIDA"
                                    ELSE (SELECT MAX(oi.nombre) FROM ordeninformes oi WHERE SUBSTRING(clientesmovimientos.codigo_contable_sw, 1, 4) = oi.agrupador_cuenta) 
                                END AS descripcionct')
                    ->selectRaw('TRIM(TRAILING ".00" FROM FORMAT(
                                    CASE 
                                        WHEN SUBSTRING(clientesmovimientos.codigo_contable_sw, 1, 1) = "4" THEN SUM(IFNULL(clientesmovimientos.credito_sw, 0) - IFNULL(clientesmovimientos.debito_sw, 0)) 
                                        WHEN SUBSTRING(clientesmovimientos.codigo_contable_sw, 1, 4) = "6225" THEN SUM(IFNULL(clientesmovimientos.credito_sw, 0) - IFNULL(clientesmovimientos.debito_sw, 0)) 
                                        ELSE SUM(IFNULL(clientesmovimientos.debito_sw, 0) - IFNULL(clientesmovimientos.credito_sw, 0)) 
                                    END, 2)) AS total_mes')
                    ->where('clientesmovimientos.Nit', $nit)
                    ->where(function ($query) use ($orden) {
                        $ordenArray = json_decode($orden, true); // Decodifica el JSON a un array
                        foreach ($ordenArray as $codigo) {
                            if (strlen($codigo) == 2) {
                                $query->orWhereRaw('SUBSTRING(clientesmovimientos.codigo_contable_sw, 1, 2) = ?', $codigo);
                            } else {
                                $query->orWhereRaw('SUBSTRING(clientesmovimientos.codigo_contable_sw, 1, 4) = ?', $codigo);
                            }
                        }
                    })
                    ->whereBetween('clientesmovimientos.fecha_reporte_sw', [$fechaFinal->format('Y-m-d'), $fechaInicio->format('Y-m-d')]) // Filtra por rango de fechas
                    ->groupBy('descripcionct', 'fecha_reporte_sw', 'clientesmovimientos.codigo_contable_sw', 'clientesmovimientos.centro_costo_sw')
                    ->orderBy('cuenta', 'asc');
                 
                    // Ejecutar la consulta
                    $informe = $informeQuery->get();
                    
        } else if($siigo == 'PYME'){
            $informeQuery = DB::table(DB::raw('(SELECT 
                            CASE
                                WHEN SUBSTRING(clientesmovimientos.cuenta, 1, 2) = "42" THEN "42"
                                WHEN SUBSTRING(clientesmovimientos.cuenta, 1, 4) = "6225" THEN "42"
                                WHEN SUBSTRING(clientesmovimientos.cuenta, 1, 2) = "53" THEN "53"
                                WHEN SUBSTRING(clientesmovimientos.cuenta, 1, 4) = "5250" THEN "5245"
                                WHEN SUBSTRING(clientesmovimientos.cuenta, 1, 2) = "54" THEN "54"
                                ELSE SUBSTRING(clientesmovimientos.cuenta, 1, 4)
                            END AS cuenta,
                            CASE
                                WHEN SUBSTRING(clientesmovimientos.cuenta, 1, 2) = "42" THEN "Otros Ingresos"
                                WHEN SUBSTRING(clientesmovimientos.cuenta, 1, 2) = "53" THEN "Otros Egresos"
                                WHEN SUBSTRING(clientesmovimientos.cuenta, 1, 4) = "5250" THEN "Mantto. Ed. Y Equipos"
                                WHEN SUBSTRING(clientesmovimientos.cuenta, 1, 4) = "6225" THEN "Otros Ingresos"
                                WHEN SUBSTRING(clientesmovimientos.cuenta, 1, 2) = "54" THEN "Impuesto de renta"
                                WHEN SUBSTRING(clientesmovimientos.cuenta, 1, 2) = "71" THEN "COSTO MERCANCIA VENDIDA"
                                ELSE (SELECT MAX(oi.nombre) FROM ordeninformes oi WHERE SUBSTRING(clientesmovimientos.cuenta, 1, 4) = oi.agrupador_cuenta)
                            END AS descripcionct,
                            COALESCE(
                                CASE
                                    WHEN SUBSTRING(clientesmovimientos.cuenta, 1, 1) = "4" THEN SUM(IFNULL(clientesmovimientos.creditos, 0) - IFNULL(clientesmovimientos.debitos, 0))
                                    WHEN SUBSTRING(clientesmovimientos.cuenta, 1, 4) = "6225" THEN SUM(IFNULL(clientesmovimientos.creditos, 0) - IFNULL(clientesmovimientos.debitos, 0))
                                    ELSE SUM(IFNULL(clientesmovimientos.debitos, 0) - IFNULL(clientesmovimientos.creditos, 0))
                                END, 0
                            ) AS total_mes
                        FROM 
                            clientesmovimientos 
                        WHERE 
                            clientesmovimientos.Nit = ?
                            AND (
                                ' . implode(' OR ', array_map(function($codigo) {
                                    return 'SUBSTRING(clientesmovimientos.cuenta, 1, ' . (strlen($codigo) === 2 ? '2' : '4') . ') = ?';
                                }, $ordenArray)) . '
                            )
                            AND clientesmovimientos.fecha_reporte BETWEEN ? AND ?
                        GROUP BY 
                            cuenta, descripcionct
                        ) AS subquery'))
            ->selectRaw('cuenta, descripcionct, FORMAT(SUM(COALESCE(total_mes, 0)), 2) AS total_mes')
            ->groupBy('cuenta', 'descripcionct')
            ->orderBy('cuenta', 'asc')
            ->setBindings(array_merge([$nit], $ordenArray, [$fechaFinal->format('Y-m-d'), $fechaInicio->format('Y-m-d')]));
            // Ejecutar la consulta
            $informe = $informeQuery->get();
            
        }else{
                   // Ordenar $ordenArray por longitud de cuenta de mayor a menor
            usort($ordenArray, function($a, $b) {
                return strlen($b) - strlen($a);
            });
            $informeQuery = DB::table(DB::raw('(SELECT 
                            CASE
                                ' . implode(' ', array_map(function($cuenta) {
                                    return 'WHEN SUBSTRING(contapyme.cuenta, 1, ' . strlen($cuenta) . ') = "' . $cuenta . '" THEN "' . $cuenta . '"';
                                }, $ordenArray)) . '
                                ELSE SUBSTRING(contapyme.cuenta, 1, 4)
                            END AS cuenta,
                            CASE
                                ' . implode(' ', array_map(function($cuenta) use ($descripciones) {
                                    $descripcion = $descripciones->where('agrupador_cuenta', $cuenta)->first();
                                    return 'WHEN SUBSTRING(contapyme.cuenta, 1, ' . strlen($cuenta) . ') = "' . $cuenta . '" THEN "' . ($descripcion ? $descripcion->nombre : '') . '"';
                                }, $ordenArray)) . '
                                ELSE (SELECT MAX(oi.nombre) FROM ordeninformes oi WHERE SUBSTRING(contapyme.cuenta, 1, 4) = oi.agrupador_cuenta)
                            END AS descripcionct,
                            ROUND(
                                CASE 
                                    WHEN YEAR(contapyme.fechareporte) = ? THEN
                                        CASE 
                                            WHEN SUBSTRING(contapyme.cuenta, 1, 1) = "1" THEN SUM(IFNULL(contapyme.debitos, 0) - IFNULL(contapyme.creditos, 0))
                                            WHEN SUBSTRING(contapyme.cuenta, 1, 3) = "159" THEN IFNULL(contapyme.creditos, 0)
                                            WHEN SUBSTRING(contapyme.cuenta, 1, 1) = "2" THEN SUM(IFNULL(contapyme.creditos, 0) - IFNULL(contapyme.debitos, 0))
                                            WHEN SUBSTRING(contapyme.cuenta, 1, 1) = "3" THEN SUM(IFNULL(contapyme.creditos, 0) - IFNULL(contapyme.debitos, 0))
                                            WHEN SUBSTRING(contapyme.cuenta, 1, 1) = "4" THEN SUM(IFNULL(contapyme.creditos, 0) - IFNULL(contapyme.debitos, 0))
                                            WHEN SUBSTRING(contapyme.cuenta, 1, 4) = "4175" THEN SUM(IFNULL(contapyme.debitos, 0) - IFNULL(contapyme.creditos, 0))
                                            ELSE SUM(IFNULL(contapyme.debitos, 0) - IFNULL(contapyme.creditos, 0)) 
                                        END
                                    ELSE 0
                                END, 2) AS totalaño1,
                            ROUND(
                                CASE 
                                    WHEN YEAR(contapyme.fechareporte) = ? THEN
                                        CASE 
                                            WHEN SUBSTRING(contapyme.cuenta, 1, 1) = "1" THEN SUM(IFNULL(contapyme.debitos, 0) - IFNULL(contapyme.creditos, 0))
                                            WHEN SUBSTRING(contapyme.cuenta, 1, 3) = "159" THEN IFNULL(contapyme.creditos, 0)
                                            WHEN SUBSTRING(contapyme.cuenta, 1, 1) = "2" THEN SUM(IFNULL(contapyme.creditos, 0) - IFNULL(contapyme.debitos, 0))
                                            WHEN SUBSTRING(contapyme.cuenta, 1, 1) = "3" THEN SUM(IFNULL(contapyme.creditos, 0) - IFNULL(contapyme.debitos, 0))
                                            WHEN SUBSTRING(contapyme.cuenta, 1, 1) = "4" THEN SUM(IFNULL(contapyme.creditos, 0) - IFNULL(contapyme.debitos, 0))
                                            WHEN SUBSTRING(contapyme.cuenta, 1, 4) = "4175" THEN SUM(IFNULL(contapyme.debitos, 0) - IFNULL(contapyme.creditos, 0))
                                            ELSE SUM(IFNULL(contapyme.debitos, 0) - IFNULL(contapyme.creditos, 0)) 
                                        END
                                    ELSE 0
                                END, 2) AS totalaño2
                        FROM 
                            contapyme 
                        WHERE 
                            contapyme.Nit = ?
                            AND (
                                ' . implode(' OR ', array_map(function($codigo) {
                                    return 'SUBSTRING(contapyme.cuenta, 1, ' . (strlen($codigo) === 1 ? '1' : '4') . ') = ?';
                                }, $ordenArray)) . '
                            )
                        GROUP BY 
                            cuenta, descripcionct
                        ) AS subquery'))
            ->selectRaw('cuenta, descripcionct, 
                         FORMAT(SUM(COALESCE(totalaño1, 0)), 2) AS totalaño1, 
                         FORMAT(SUM(COALESCE(totalaño2, 0)), 2) AS totalaño2')
            ->groupBy('cuenta', 'descripcionct')
            ->orderBy('cuenta', 'asc')
            ->setBindings(array_merge([$anio, $anioAnterior, $nit], $ordenArray));
            // Ejecutar la consulta
            $informe = $informeQuery->get();
        }

        return $informe;
    }
   
    
    public function consultaporcuenta(Request $request)
    {
        
        $nit = $request->input('nit');
        $fechaInicio = $request->input('fecha_inicio');
        $numeroCuenta = $request->input('numero_cuenta');
        $fechaInicio = Carbon::parse($fechaInicio);
        $companias = Empresa::where('nit', $request->input('nit'))->first();
        $siigo = $companias->tipo;
       
        $movimientos =[];
        if ($siigo == 'NUBE') {
            $consulta = Clientes::where('Nit', $nit)
                    ->select('Nit','codigo_cuenta_contable_ga AS cuenta',
                    'nombre_cuenta_contable_ga AS descripcion','saldo_inicial_ga AS saldo_anterior',
                    'movimiento_debito_ga AS debitos','movimiento_credito_ga AS creditos','saldo_final_ga as nuevo_saldo','fechareporte_ga as fechareporte')
                    ->where(function($query) use ($numeroCuenta) {
                        $longitud = strlen($numeroCuenta);
                        if($longitud === 1){
                            $query->whereRaw('SUBSTRING(clientes.codigo_cuenta_contable_ga, 1, 1) = ?', [$numeroCuenta]);
                        }
                        else if ($longitud === 2) {
                            $query->whereRaw('SUBSTRING(clientes.codigo_cuenta_contable_ga, 1, 2) = ?', [$numeroCuenta]);
                        }else if($longitud === 4){
                            $query->whereRaw('SUBSTRING(clientes.codigo_cuenta_contable_ga, 1, 4) = ?', [$numeroCuenta]);
                        }else{
                            $query->whereRaw('SUBSTRING(clientes.codigo_cuenta_contable_ga, 1, 6) = ?', [intval($numeroCuenta)]);
                        }
                    })
                    ->whereYear('fechareporte_ga', $fechaInicio->year)
                    ->whereMonth('fechareporte_ga', $fechaInicio->month)
                    ->get();
        } else if($siigo == "PYME") {
           $consulta = Clientes::selectRaw("
                REPLACE(CONCAT(
                    TRIM(IFNULL(grupo, '')),
                    TRIM(IFNULL(cuenta, '')),
                    TRIM(IFNULL(subcuenta, ''))
                ), ' ', '') AS cuenta
            ")
            ->selectRaw("MAX(TRIM(IFNULL(descripcion, ''))) AS descripcion")
            ->selectRaw("ROUND(SUM(saldo_anterior), 0) AS saldo_anterior")
            ->selectRaw("ROUND(SUM(debitos), 0) AS debitos")
            ->selectRaw("ROUND(SUM(creditos), 0) AS creditos")
            ->selectRaw("ROUND(SUM(nuevo_saldo), 0) AS nuevo_saldo")
            ->where('Nit', $nit)
            ->whereYear('fechareporte', date('Y', strtotime($fechaInicio)))
            ->whereMonth('fechareporte', date('m', strtotime($fechaInicio)))
            ->whereRaw("
                REPLACE(CONCAT(
                    TRIM(IFNULL(grupo, '')),
                    TRIM(IFNULL(cuenta, '')),
                    TRIM(IFNULL(subcuenta, ''))
                ), ' ', '') LIKE ?
            ", [$numeroCuenta . '%'])
            ->groupByRaw("
                REPLACE(CONCAT(
                    TRIM(IFNULL(grupo, '')),
                    TRIM(IFNULL(cuenta, '')),
                    TRIM(IFNULL(subcuenta, ''))
                ), ' ', '')
            ")
            ->orderBy('cuenta', 'ASC')
            ->get();

        }else if($siigo == "CONTAPYME") {
            $consulta = ContapymeCompleto::where('Nit', $nit)
            ->select('Nit','cuenta','descripcion','saldo_anterior',
                    'debitos','creditos','nuevo_saldo','fechareporte'
            )
            ->where(function($query) use ($numeroCuenta) {
                $longitud = strlen($numeroCuenta);
                if ($longitud === 1) {
                    $query->whereRaw('SUBSTRING(cuenta, 1, 1) = ?', [$numeroCuenta]);
                }else if ($longitud === 2) {
                    $query->whereRaw('SUBSTRING(cuenta, 1, 2) = ?', [$numeroCuenta]);
                } else if ($longitud === 4){
                    $query->whereRaw('SUBSTRING(cuenta, 1, 4) = ?', [intval($numeroCuenta)]);
                }else {
                    $query->whereRaw('SUBSTRING(cuenta, 1, 6) = ?', [intval($numeroCuenta)]);
                }
            })
            ->whereYear('fechareporte', $fechaInicio->year)
            ->whereMonth('fechareporte', $fechaInicio->month)
            ->get();
        }else if($siigo == "LOGGRO") {
            $consulta = loggro::where('Nit', $nit)
            ->select('Nit','cuenta','descripcion','saldo_anterior',
                    'debitos','creditos','saldo_final AS nuevo_saldo','fechareporte'
            )
            ->where(function($query) use ($numeroCuenta) {
                $longitud = strlen($numeroCuenta);
                if ($longitud === 1) {
                    $query->whereRaw('SUBSTRING(cuenta, 1, 1) = ?', [$numeroCuenta]);
                }else if ($longitud === 2) {
                    $query->whereRaw('SUBSTRING(cuenta, 1, 2) = ?', [$numeroCuenta]);
                } else if ($longitud === 4){
                    $query->whereRaw('SUBSTRING(cuenta, 1, 4) = ?', [intval($numeroCuenta)]);
                }else {
                    $query->whereRaw('SUBSTRING(cuenta, 1, 6) = ?', [intval($numeroCuenta)]);
                }
            })
            ->whereYear('fechareporte', $fechaInicio->year)
            ->whereMonth('fechareporte', $fechaInicio->month)
            ->get();

        }else if($siigo == "BEGRANDA") {
            $consulta = Begranda::where('Nit', $nit)
            ->select('Nit','cuenta','descripcion','saldo_anterior',
                    'debitos','creditos','saldo_final AS nuevo_saldo','fechareporte'
            )
            ->where(function($query) use ($numeroCuenta) {
                $longitud = strlen($numeroCuenta);
                if ($longitud === 1) {
                    $query->whereRaw('SUBSTRING(cuenta, 1, 1) = ?', [$numeroCuenta]);
                }else if ($longitud === 2) {
                    $query->whereRaw('SUBSTRING(cuenta, 1, 2) = ?', [$numeroCuenta]);
                } else if ($longitud === 4){
                    $query->whereRaw('SUBSTRING(cuenta, 1, 4) = ?', [intval($numeroCuenta)]);
                }else {
                    $query->whereRaw('SUBSTRING(cuenta, 1, 6) = ?', [intval($numeroCuenta)]);
                }
            })
            ->whereYear('fechareporte', $fechaInicio->year)
            ->whereMonth('fechareporte', $fechaInicio->month)
            ->get();
        }else {
            $consulta = InformesGenericos::where('Nit', $nit)
            ->select('Nit','cuenta','descripcion','saldo_anterior',
                    'debitos','creditos','saldo_final AS nuevo_saldo','fechareporte'
            )
            ->where(function($query) use ($numeroCuenta) {
                $longitud = strlen($numeroCuenta);
                if ($longitud === 1) {
                    $query->whereRaw('SUBSTRING(cuenta, 1, 1) = ?', [$numeroCuenta]);
                }else if ($longitud === 2) {
                    $query->whereRaw('SUBSTRING(cuenta, 1, 2) = ?', [$numeroCuenta]);
                } else if ($longitud === 4){
                    $query->whereRaw('SUBSTRING(cuenta, 1, 4) = ?', [intval($numeroCuenta)]);
                }else {
                    $query->whereRaw('SUBSTRING(cuenta, 1, 6) = ?', [intval($numeroCuenta)]);
                }
            })
            ->whereYear('fechareporte', $fechaInicio->year)
            ->whereMonth('fechareporte', $fechaInicio->month)
            ->get();
        }
           
        if ($request->input('contexto') === 'descargar') {
             // Exportar a Excel si el contexto es "descargar"
             return Excel::download(new ConsultaPorCuentasExport($consulta, $siigo), 'Estados_por_cuenta.xlsx');
        }else{
            // Devolver los datos como JSON si el contexto es "mostrar"
            return response()->json([
                'resultados' => $consulta,
                'siigo' => $siigo,
                'movimientos' => $movimientos
            ]);
        }
        // // Devolver la respuesta en formato JSON con el contenido de la vista
        // return view('admin.estadosfinancieros.tabla-busqueda', ['resultados' => $consulta,'siggo'=>$siigo,'movimientos' => $movimientos]);
    }

      public function consultaporcuentanotaspdf($nit,$fechaInicio,$numeroCuenta)
    {
        
        $fechaInicio = Carbon::parse($fechaInicio);
        $companias = Empresa::where('nit', $nit)->first();
        $siigo = $companias->tipo;
        $anioActual = date('Y', strtotime($fechaInicio));
        $anioAnterior = $anioActual - 1;
        $mes = date('m', strtotime($fechaInicio));
        $movimientos =[];
        if ($siigo == 'NUBE') {

            $consulta = Clientes::selectRaw("
                    TRIM(REPLACE(codigo_cuenta_contable_ga, ' ', '')) AS cuenta
                ")
                ->selectRaw("MAX(TRIM(nombre_cuenta_contable_ga)) AS descripcion")
                ->selectRaw("ROUND(SUM(CASE 
                    WHEN YEAR(fechareporte_ga) = ? AND MONTH(fechareporte_ga) = ? 
                    THEN saldo_final_ga ELSE 0 END), 0) AS saldo_anio_actual", [$anioActual, $mes])
                ->selectRaw("ROUND(SUM(CASE 
                    WHEN YEAR(fechareporte_ga) = ? AND MONTH(fechareporte_ga) = ? 
                    THEN saldo_final_ga ELSE 0 END), 0) AS saldo_anio_anterior", [$anioAnterior, 12])
                ->where('Nit', $nit)
                ->groupByRaw("TRIM(REPLACE(codigo_cuenta_contable_ga, ' ', ''))")
                ->orderBy('cuenta', 'ASC')
                ->get();
        } else if($siigo == "PYME") {

            $consulta = Clientes::selectRaw("
                    REPLACE(CONCAT(
                        TRIM(IFNULL(grupo, '')),
                        TRIM(IFNULL(cuenta, '')),
                        TRIM(IFNULL(subcuenta, ''))
                    ), ' ', '') AS cuenta
                ")
                ->selectRaw("MAX(TRIM(IFNULL(descripcion, ''))) AS descripcion")
                ->selectRaw("ROUND(SUM(CASE 
                    WHEN YEAR(fechareporte) = ? AND MONTH(fechareporte) = ? 
                    THEN nuevo_saldo ELSE 0 END), 0) AS saldo_anio_actual", [$anioActual, $mes])
                ->selectRaw("ROUND(SUM(CASE 
                    WHEN YEAR(fechareporte) = ? AND MONTH(fechareporte) = ? 
                    THEN nuevo_saldo ELSE 0 END), 0) AS saldo_anio_anterior", [$anioAnterior, 12])
                ->where('Nit', $nit)
                ->groupByRaw("
                    REPLACE(CONCAT(
                        TRIM(IFNULL(grupo, '')),
                        TRIM(IFNULL(cuenta, '')),
                        TRIM(IFNULL(subcuenta, ''))
                    ), ' ', '')
                ")
                ->orderBy('cuenta', 'ASC')
                ->get();


        }else if($siigo == "CONTAPYME") {
           

            $consulta = ContapymeCompleto::selectRaw("TRIM(REPLACE(cuenta, ' ', '')) AS cuenta")
                ->selectRaw("MAX(TRIM(descripcion)) AS descripcion")
                ->selectRaw("ROUND(SUM(CASE 
                    WHEN YEAR(fechareporte) = ? AND MONTH(fechareporte) = ? 
                    THEN nuevo_saldo ELSE 0 END), 0) AS saldo_anio_actual", [$anioActual, $mes])
                ->selectRaw("ROUND(SUM(CASE 
                    WHEN YEAR(fechareporte) = ? AND MONTH(fechareporte) = ? 
                    THEN nuevo_saldo ELSE 0 END), 0) AS saldo_anio_anterior", [$anioAnterior, 12])
                ->where('Nit', $nit)
                ->groupByRaw("TRIM(REPLACE(cuenta, ' ', ''))")
                ->orderBy('cuenta', 'ASC')
                ->get();
        }else if($siigo == "LOGGRO") {
            $consulta = loggro::selectRaw("TRIM(REPLACE(cuenta, ' ', '')) AS cuenta")
            ->selectRaw("MAX(TRIM(descripcion)) AS descripcion")
            ->selectRaw("ROUND(SUM(CASE 
                WHEN YEAR(fechareporte) = ? AND MONTH(fechareporte) = ? 
                THEN saldo_final ELSE 0 END), 0) AS saldo_anio_actual", [$anioActual, $mes])
            ->selectRaw("ROUND(SUM(CASE 
                WHEN YEAR(fechareporte) = ? AND MONTH(fechareporte) = ? 
                THEN saldo_final ELSE 0 END), 0) AS saldo_anio_anterior", [$anioAnterior, 12])
            ->where('Nit', $nit)
            ->groupByRaw("TRIM(REPLACE(cuenta, ' ', ''))")
            ->orderBy('cuenta', 'ASC')
            ->get();

        }else if($siigo == "BEGRANDA") {
            $consulta = Begranda::selectRaw("TRIM(REPLACE(cuenta, ' ', '')) AS cuenta")
            ->selectRaw("MAX(TRIM(descripcion)) AS descripcion")
            ->selectRaw("ROUND(SUM(CASE 
                WHEN YEAR(fechareporte) = ? AND MONTH(fechareporte) = ? 
                THEN saldo_final ELSE 0 END), 0) AS saldo_anio_actual", [$anioActual, $mes])
            ->selectRaw("ROUND(SUM(CASE 
                WHEN YEAR(fechareporte) = ? AND MONTH(fechareporte) = ? 
                THEN saldo_final ELSE 0 END), 0) AS saldo_anio_anterior", [$anioAnterior, 12])
            ->where('Nit', $nit)
            ->groupByRaw("TRIM(REPLACE(cuenta, ' ', ''))")
            ->orderBy('cuenta', 'ASC')
            ->get();
        }else{
            $consulta = InformesGenericos::selectRaw("TRIM(REPLACE(cuenta, ' ', '')) AS cuenta")
            ->selectRaw("MAX(TRIM(descripcion)) AS descripcion")
            ->selectRaw("ROUND(SUM(CASE 
                WHEN YEAR(fechareporte) = ? AND MONTH(fechareporte) = ? 
                THEN saldo_final ELSE 0 END), 0) AS saldo_anio_actual", [$anioActual, $mes])
            ->selectRaw("ROUND(SUM(CASE 
                WHEN YEAR(fechareporte) = ? AND MONTH(fechareporte) = ? 
                THEN saldo_final ELSE 0 END), 0) AS saldo_anio_anterior", [$anioAnterior, 12])
            ->where('Nit', $nit)
            ->groupByRaw("TRIM(REPLACE(cuenta, ' ', ''))")
            ->orderBy('cuenta', 'ASC')
            ->get();
        }
        return $consulta; // o solo $consulta si ya es una colección
    }

    public function consultaporcuentanotasEDRpdf($nit, $fechaInicio, $numeroCuenta)
    {
        $fechaInicio = Carbon::parse($fechaInicio);
        $companias = Empresa::where('nit', $nit)->first();
        $siigo = $companias->tipo;
        $anioActual = date('Y', strtotime($fechaInicio));
        $anioAnterior = $anioActual - 1;
        $mes = date('m', strtotime($fechaInicio));

        if ($siigo == 'NUBE') {
            $consulta = Clientes::selectRaw("TRIM(REPLACE(codigo_cuenta_contable_ga, ' ', '')) AS cuenta")
                ->selectRaw("MAX(TRIM(nombre_cuenta_contable_ga)) AS descripcion")
                ->selectRaw("ROUND(SUM(CASE 
                    WHEN YEAR(fechareporte_ga) = ? AND MONTH(fechareporte_ga) = ? 
                    THEN saldo_final_ga ELSE 0 END), 0) AS saldo_anio_actual", [$anioActual, $mes])
                ->selectRaw("ROUND(SUM(CASE 
                    WHEN YEAR(fechareporte_ga) = ? AND MONTH(fechareporte_ga) = ? 
                    THEN saldo_final_ga ELSE 0 END), 0) AS saldo_anio_anterior", [$anioAnterior, $mes])
                ->where('Nit', $nit)
                ->groupByRaw("TRIM(REPLACE(codigo_cuenta_contable_ga, ' ', ''))")
                ->havingRaw("LENGTH(cuenta) = 4 AND LEFT(cuenta,1) IN ('4','5','6')")
                ->orderBy('cuenta', 'ASC')
                ->get();
        } else if ($siigo == "PYME") {
            $consulta = Clientes::selectRaw("
                    REPLACE(CONCAT(
                        TRIM(IFNULL(grupo, '')),
                        TRIM(IFNULL(cuenta, '')),
                        TRIM(IFNULL(subcuenta, ''))
                    ), ' ', '') AS cuenta
                ")
                ->selectRaw("MAX(TRIM(IFNULL(descripcion, ''))) AS descripcion")
                ->selectRaw("ROUND(SUM(CASE 
                    WHEN YEAR(fechareporte) = ? AND MONTH(fechareporte) = ? 
                    THEN nuevo_saldo ELSE 0 END), 0) AS saldo_anio_actual", [$anioActual, $mes])
                ->selectRaw("ROUND(SUM(CASE 
                    WHEN YEAR(fechareporte) = ? AND MONTH(fechareporte) = ? 
                    THEN nuevo_saldo ELSE 0 END), 0) AS saldo_anio_anterior", [$anioAnterior, $mes])
                ->where('Nit', $nit)
                ->groupByRaw("
                    REPLACE(CONCAT(
                        TRIM(IFNULL(grupo, '')),
                        TRIM(IFNULL(cuenta, '')),
                        TRIM(IFNULL(subcuenta, ''))
                    ), ' ', '')
                ")
                ->havingRaw("LENGTH(cuenta) = 4 AND LEFT(cuenta,1) IN ('4','5','6')")
                ->orderBy('cuenta', 'ASC')
                ->get();
        } else if ($siigo == "CONTAPYME") {
            $consulta = ContapymeCompleto::selectRaw("TRIM(REPLACE(cuenta, ' ', '')) AS cuenta")
                ->selectRaw("MAX(TRIM(descripcion)) AS descripcion")
                ->selectRaw("ROUND(SUM(CASE 
                    WHEN YEAR(fechareporte) = ? AND MONTH(fechareporte) = ? 
                    THEN nuevo_saldo ELSE 0 END), 0) AS saldo_anio_actual", [$anioActual, $mes])
                ->selectRaw("ROUND(SUM(CASE 
                    WHEN YEAR(fechareporte) = ? AND MONTH(fechareporte) = ? 
                    THEN nuevo_saldo ELSE 0 END), 0) AS saldo_anio_anterior", [$anioAnterior, $mes])
                ->where('Nit', $nit)
                ->groupByRaw("TRIM(REPLACE(cuenta, ' ', ''))")
                ->havingRaw("LENGTH(cuenta) = 4 AND LEFT(cuenta,1) IN ('4','5','6')")
                ->orderBy('cuenta', 'ASC')
                ->get();
        } else if ($siigo == "LOGGRO") {
            $consulta = loggro::selectRaw("TRIM(REPLACE(cuenta, ' ', '')) AS cuenta")
                ->selectRaw("MAX(TRIM(descripcion)) AS descripcion")
                ->selectRaw("ROUND(SUM(CASE 
                    WHEN YEAR(fechareporte) = ? AND MONTH(fechareporte) = ? 
                    THEN saldo_final ELSE 0 END), 0) AS saldo_anio_actual", [$anioActual, $mes])
                ->selectRaw("ROUND(SUM(CASE 
                    WHEN YEAR(fechareporte) = ? AND MONTH(fechareporte) = ? 
                    THEN saldo_final ELSE 0 END), 0) AS saldo_anio_anterior", [$anioAnterior, $mes])
                ->where('Nit', $nit)
                ->groupByRaw("TRIM(REPLACE(cuenta, ' ', ''))")
                ->havingRaw("LENGTH(cuenta) = 4 AND LEFT(cuenta,1) IN ('4','5','6')")
                ->orderBy('cuenta', 'ASC')
                ->get();
        } else if ($siigo == "BEGRANDA") {
            $consulta = Begranda::selectRaw("TRIM(REPLACE(cuenta, ' ', '')) AS cuenta")
                ->selectRaw("MAX(TRIM(descripcion)) AS descripcion")
                ->selectRaw("ROUND(SUM(CASE 
                    WHEN YEAR(fechareporte) = ? AND MONTH(fechareporte) = ? 
                    THEN saldo_final ELSE 0 END), 0) AS saldo_anio_actual", [$anioActual, $mes])
                ->selectRaw("ROUND(SUM(CASE 
                    WHEN YEAR(fechareporte) = ? AND MONTH(fechareporte) = ? 
                    THEN saldo_final ELSE 0 END), 0) AS saldo_anio_anterior", [$anioAnterior, $mes])
                ->where('Nit', $nit)
                ->groupByRaw("TRIM(REPLACE(cuenta, ' ', ''))")
                ->havingRaw("LENGTH(cuenta) = 4 AND LEFT(cuenta,1) IN ('4','5','6')")
                ->orderBy('cuenta', 'ASC')
                ->get();
        } else {
            $consulta = InformesGenericos::selectRaw("TRIM(REPLACE(cuenta, ' ', '')) AS cuenta")
                ->selectRaw("MAX(TRIM(descripcion)) AS descripcion")
                ->selectRaw("ROUND(SUM(CASE 
                    WHEN YEAR(fechareporte) = ? AND MONTH(fechareporte) = ? 
                    THEN saldo_final ELSE 0 END), 0) AS saldo_anio_actual", [$anioActual, $mes])
                ->selectRaw("ROUND(SUM(CASE 
                    WHEN YEAR(fechareporte) = ? AND MONTH(fechareporte) = ? 
                    THEN saldo_final ELSE 0 END), 0) AS saldo_anio_anterior", [$anioAnterior, $mes])
                ->where('Nit', $nit)
                ->groupByRaw("TRIM(REPLACE(cuenta, ' ', ''))")
                ->havingRaw("LENGTH(cuenta) = 4 AND LEFT(cuenta,1) IN ('4','5','6')")
                ->orderBy('cuenta', 'ASC')
                ->get();
        }

        return $consulta;
    }

  
    //seccion para el validador nota por nota 
    public function validarAgrupadores($nit, $fechaInicio, $informeData2, $cuentasPorNota, $agrupaciones)
    {
        // 1) Traer movimientos UNA sola vez (ya vienen ambos años)
        // $movimientos = $this->consultaporcuentanotaspdf($nit, $fechaInicio, null);
        $empresaasociada = Empresa::where('NIT', $nit)->value('empresaasociada');
        if($empresaasociada){
            $operador = Empresa::where('NIT', $nit)->value('operador') ?? '+';
            $notas1 = $this->consultaporcuentanotaspdf(
                $nit,
                $fechaInicio,
                $numeroCuenta = null
            );

            $notas2 = $this->consultaporcuentanotaspdf(
                $empresaasociada,
                $fechaInicio,
                $numeroCuenta = null
            );

            $movimientos = $this->consolidador->combinarNotasSituacion(
                $notas1,
                $notas2,
                $operador
            );

        }else{

            $movimientos = $this->consultaporcuentanotaspdf(
                $nit,
                $fechaInicio,
                $numeroCuenta = null
            );
        }
        // 2) Indexar por cuenta para acceso O(1)
        $byCuenta = [];
        foreach ($movimientos as $m) {
            $codigo = (string) $m->cuenta;
            $byCuenta[$codigo] = [
                'a1' => (float) $m->saldo_anio_actual,
                'a2' => (float) $m->saldo_anio_anterior,
            ];
        }

        // Helpers
        $getVal = function($code, $year) use ($byCuenta) {
            return $byCuenta[$code][$year] ?? 0.0;
        };
        $sumNormal = function(array $codes, $year) use ($getVal) {
            $s = 0.0;
            foreach ($codes as $c) $s += (float) $getVal($c, $year);
            return $s;
        };
        $sumAbs = function(array $codes, $year) use ($getVal) {
            $s = 0.0;
            foreach ($codes as $c) $s += abs((float) $getVal($c, $year));
            return $s;
        };
        $parseInforme = function($fila, $key) {
            if (!$fila || !isset($fila[$key])) return 0.0;
            $txt = str_replace([',',' '], '', (string)$fila[$key]);
            // Si vienen paréntesis como negativo, opcional:
            if (preg_match('/^\((.*)\)$/', $txt, $m)) $txt = '-'.$m[1];
            return (float) $txt;
        };

        $resultados = [];
        $posPorAgrupador = [];        // para ubicar luego la fila 61 y ajustarla con utilidad
        $valorUtilidadA1 = 0.0;       // utilidad del ejercicio (A1) con signo del informe
        $valorUtilidadA2 = 0.0;       // utilidad del ejercicio (A2) con signo del informe
        
        $reglasSignoPatrimonio = [
            '50' => 'negativo',   // Superavit de capital
            '51' => 'invertido',  // Reservas
            '53' => 'negativo',
            '54' => 'negativo',
            '56' => 'negativo',   // Resultado del ejercicio
            '57' => 'negativo',
            '58' => 'negativo',
        ];

        $aplicarSignoPatrimonio = function ($valor, $tipo) {
            return match ($tipo) {
                'directo'   => $valor < 0 ? -abs($valor) :  abs($valor),
                'invertido' => $valor * -1,
                'negativo'  => $valor < 0 ?  abs($valor) : -abs($valor),
                default     => $valor,
            };
        }; 

        foreach ($cuentasPorNota as $agrupador => $subcuentas) {
            $subcuentas = array_map('strval', collect($subcuentas)->flatten()->toArray());

            $totalA1 = 0.0;
            $totalA2 = 0.0;

            // --- CASOS ESPECIALES ---
            if ((int)$agrupador === 40) {
                // Nota 40:  valor(28) - sum(otros)  (todo en abs)
                $valor28A1 = abs($getVal('28','a1'));
                $valor28A2 = abs($getVal('28','a2'));
                $otrosA1 = 0.0; $otrosA2 = 0.0;
                foreach ($subcuentas as $c) {
                    if ($c === '28') continue;
                    $otrosA1 += abs($getVal($c,'a1'));
                    $otrosA2 += abs($getVal($c,'a2'));
                }
                $totalA1 = $valor28A1 - $otrosA1;
                $totalA2 = $valor28A2 - $otrosA2;

            } elseif ((int)$agrupador === 56) {
                // Nota 56: si existe 362, su valor = valor de 36 (no 362), + demás subcuentas normales (excepto 362)
                $saldo36A1 = (float) $getVal('36','a1');
                $saldo36A2 = (float) $getVal('36','a2');

                if (in_array('362', $subcuentas, true)) {
                    $totalA1 += $saldo36A1;
                    $totalA2 += $saldo36A2;
                }
                foreach ($subcuentas as $c) {
                    if ($c === '362') continue;
                    $totalA1 += (float) $getVal($c,'a1');
                    $totalA2 += (float) $getVal($c,'a2');
                }

            } elseif ((string)$agrupador === '36') {
                // Nota 36: utilidad/pérdida = sum cuentas 4,5,6,7; ajustar signo para que coincida con el informe
                $cuentasUtilidad = ['4','5','6','7'];
                $totalA1 = $sumNormal($cuentasUtilidad, 'a1');
                $totalA2 = $sumNormal($cuentasUtilidad, 'a2');

                $filaInfUtil = collect($informeData2)->firstWhere('descripcion', 'Utilidad y/o perdidas del ejercicio');
                $infA1 = $parseInforme($filaInfUtil, 'totalaño1');
                $infA2 = $parseInforme($filaInfUtil, 'totalaño2');

                if ($filaInfUtil) {
                    $signInfA1 = $infA1 < 0 ? -1 : 1;
                    $signInfA2 = $infA2 < 0 ? -1 : 1;
                    $signTotalA1 = $totalA1 < 0 ? -1 : 1;
                    $signTotalA2 = $totalA2 < 0 ? -1 : 1;
                    if ($signInfA1 !== $signTotalA1) $totalA1 *= -1;
                    if ($signInfA2 !== $signTotalA2) $totalA2 *= -1;

                    // Guardar utilidad con el signo del informe (para aplicar en 61 después)
                    $valorUtilidadA1 = abs($totalA1) * $signInfA1;
                    $valorUtilidadA2 = abs($totalA2) * $signInfA2;
                }

            } elseif (in_array((int)$agrupador, [59,60,61], true)) {
                if ((int)$agrupador === 61) {

                        $totalA1 = 0;
                        $totalA2 = 0;

                        foreach ($subcuentas as $c) {

                            $valorA1 = (float) $getVal($c,'a1');
                            $valorA2 = (float) $getVal($c,'a2');

                            $tipo = $reglasSignoPatrimonio[$c] ?? 'negativo';

                            $totalA1 += $aplicarSignoPatrimonio($valorA1, $tipo);
                            $totalA2 += $aplicarSignoPatrimonio($valorA2, $tipo);
                        }
                    } else {
                    // 59/60: sumar todo en ABS
                    $totalA1 = $sumAbs($subcuentas, 'a1');
                    $totalA2 = $sumAbs($subcuentas, 'a2');
                }

            } else {
                // --- LÓGICA NORMAL ---
                $totalA1 = $sumNormal($subcuentas, 'a1');
                $totalA2 = $sumNormal($subcuentas, 'a2');
            }

            // 3) Descripción
            $descripcion = $agrupaciones[$agrupador]['descripcion'] ?? "Agrupador {$agrupador}";

            // 4) Tomar valores del informe (A1/A2)
            $filaInforme = collect($informeData2)->firstWhere('descripcion', $descripcion);
            $valorInfA1 = $parseInforme($filaInforme, 'totalaño1');
            $valorInfA2 = $parseInforme($filaInforme, 'totalaño2');

            // Para 59/60/61: informe en positivo
            if (in_array((int)$agrupador, [59,60,61], true)) {
                $valorInfA1 = abs($valorInfA1);
                $valorInfA2 = abs($valorInfA2);
            }

            // 5) Cuadrado con tolerancia ±5 por año
            $cuadradoA1 = abs(abs($totalA1) - abs($valorInfA1)) <= 5;
            $cuadradoA2 = abs(abs($totalA2) - abs($valorInfA2)) <= 5;

            // 6) Guardar fila
            $posPorAgrupador[(string)$agrupador] = count($resultados);
            $resultados[] = [
                'agrupador'        => (string)$agrupador,
                'descripcion'      => $descripcion,
                'totalCalculadoA1' => $totalA1,
                'valorInformeA1'   => $valorInfA1,
                'cuadradoA1'       => $cuadradoA1,
                'totalCalculadoA2' => $totalA2,
                'valorInformeA2'   => $valorInfA2,
                'cuadradoA2'       => $cuadradoA2,
            ];
        }

        // 7) Ajustar Nota 61 con la utilidad (ya calculada en Nota 36)
        if (isset($posPorAgrupador['61'])) {
            $i = $posPorAgrupador['61'];
            $baseA1 = (float) $resultados[$i]['totalCalculadoA1'];
            $baseA2 = (float) $resultados[$i]['totalCalculadoA2'];

            $calc61A1 = $baseA1 + ($valorUtilidadA1 < 0 ? -abs($valorUtilidadA1) : $valorUtilidadA1);
            $calc61A2 = $baseA2 + ($valorUtilidadA2 < 0 ? -abs($valorUtilidadA2) : $valorUtilidadA2);

            $resultados[$i]['totalCalculadoA1'] = $calc61A1;
            $resultados[$i]['totalCalculadoA2'] = $calc61A2;

            // Recalcular estado contra informe (ya en positivo)
            $valorInfA1 = (float) $resultados[$i]['valorInformeA1'];
            $valorInfA2 = (float) $resultados[$i]['valorInformeA2'];
            $resultados[$i]['cuadradoA1'] = abs(abs($calc61A1) - abs($valorInfA1)) <= 5;
            $resultados[$i]['cuadradoA2'] = abs(abs($calc61A2) - abs($valorInfA2)) <= 5;
        }

        // 8) Ecuación Contable (comparar Total Activo vs Pasivo + Patrimonio)

        // Buscar calculados
        $filaTotalActivo   = collect($resultados)->firstWhere('descripcion', 'Total activo');
        $filaTotalPasivo   = collect($resultados)->firstWhere('descripcion', 'Total Pasivo');
        $filaTotalPatrimonio = collect($resultados)->firstWhere('descripcion', 'Total patrimonio');

        // Buscar en informe
        $filaTotalActivoInf   = collect($informeData2)->firstWhere('descripcion', 'Total activo');
        $filaTotalPasivoInf   = collect($informeData2)->firstWhere('descripcion', 'Total Pasivo');
        $filaTotalPatrimonioInf = collect($informeData2)->firstWhere('descripcion', 'Total patrimonio');

        // Valores calculados
        $totActA1 = $filaTotalActivo['totalCalculadoA1'] ?? 0;
        $totActA2 = $filaTotalActivo['totalCalculadoA2'] ?? 0;
        $totPyPA1 = ($filaTotalPasivo['totalCalculadoA1'] ?? 0) + ($filaTotalPatrimonio['totalCalculadoA1'] ?? 0);
        $totPyPA2 = ($filaTotalPasivo['totalCalculadoA2'] ?? 0) + ($filaTotalPatrimonio['totalCalculadoA2'] ?? 0);

        // Valores del informe
        $valorActInfA1 = $parseInforme($filaTotalActivoInf, 'totalaño1');
        $valorActInfA2 = $parseInforme($filaTotalActivoInf, 'totalaño2');
        $valorTPyPA1   = $parseInforme($filaTotalPasivoInf, 'totalaño1') + $parseInforme($filaTotalPatrimonioInf, 'totalaño1');
        $valorTPyPA2   = $parseInforme($filaTotalPasivoInf, 'totalaño2') + $parseInforme($filaTotalPatrimonioInf, 'totalaño2');

        // Comparar calculados
        $ecuacionCalcA1 = abs($totActA1 - $totPyPA1) <= 5;
        $ecuacionCalcA2 = abs($totActA2 - $totPyPA2) <= 5;

        // Comparar informe
        $ecuacionInfA1  = abs($valorActInfA1 - $valorTPyPA1) <= 5;
        $ecuacionInfA2  = abs($valorActInfA2 - $valorTPyPA2) <= 5;

        // Guardar fila ecuación
        $resultados[] = [
            'agrupador'        => 'ecuacion',
            'descripcion'      => 'Ecuación Contable',
            'totalCalculadoA1' => $totPyPA1,
            'valorInformeA1'   => $valorTPyPA1,
            'cuadradoA1'       => $ecuacionCalcA1 && $ecuacionInfA1,
            'totalCalculadoA2' => $totPyPA2,
            'valorInformeA2'   => $valorTPyPA2,
            'cuadradoA2'       => $ecuacionCalcA2 && $ecuacionInfA2,
        ];


        return $resultados;
    }
    public function getAcumulado($anio, $mes, $cuenta, $siigo, $nit = null) 
    {
        // Definir tabla y columnas según el tipo de siigo
        if ($siigo == 'CONTAPYME') {
            $tabla = 'contapyme_completo';
            $campoCuenta = 'contapyme_completo.cuenta';
            $campoDebitos = 'contapyme_completo.debitos';
            $campoCreditos = 'contapyme_completo.creditos';
            $campoFecha = 'contapyme_completo.fechareporte';
        } elseif ($siigo == 'NUBE') {
            $tabla = 'clientes';
            $campoCuenta = 'clientes.codigo_cuenta_contable_ga';
            $campoDebitos = 'clientes.movimiento_debito_ga';
            $campoCreditos = 'clientes.movimiento_credito_ga';
            $campoFecha = 'clientes.fechareporte_ga';
        } elseif($siigo == 'PYME'){
            $cuentaCompuesta = DB::raw("REPLACE(CONCAT(
                TRIM(IFNULL(clientes.grupo, '')),
                TRIM(IFNULL(clientes.cuenta, '')),
                TRIM(IFNULL(clientes.subcuenta, ''))
            ), ' ', '')");
            $tabla = 'clientes';
            $campoCuenta = $cuentaCompuesta;
            $campoDebitos = 'clientes.debitos';
            $campoCreditos = 'clientes.creditos';
            $campoFecha = 'clientes.fechareporte';
        }elseif($siigo == 'LOGGRO'){
            $tabla = 'loggro';
            $campoCuenta = 'loggro.cuenta';
            $campoDebitos = 'loggro.debitos';
            $campoCreditos = 'loggro.creditos';
            $campoFecha = 'loggro.fechareporte';
        }elseif($siigo == 'BEGRANDA'){
            $tabla = 'begranda';
            $campoCuenta = 'begranda.cuenta';
            $campoDebitos = 'begranda.debitos';
            $campoCreditos = 'begranda.creditos';
            $campoFecha = 'begranda.fechareporte';
        }else {
            $tabla = 'informesgenericos';
            $campoCuenta = 'informesgenericos.cuenta';
            $campoDebitos = 'informesgenericos.debitos';
            $campoCreditos = 'informesgenericos.creditos';
            $campoFecha = 'informesgenericos.fechareporte';
        }

          // Query dinámica: créditos - débitos
        $query = DB::table($tabla)
            ->selectRaw('
                ? as cuenta,
                "Acumulado" as descripcionct,
                ROUND(SUM(COALESCE(' . $campoCreditos . ', 0) - COALESCE(' . $campoDebitos . ', 0)), 2) as total_acumulado
            ', [$cuenta])
            ->where($campoCuenta, $cuenta)
            ->whereYear($campoFecha, $anio)
            ->whereMonth($campoFecha, '<=', $mes); // acumulado hasta mes

        if ($nit) {
            $query->where('nit', $nit);
        }
        return $query->first();
    }

    public function validarTodasNotas($nit, $fechaInicio, Request $request)
    {
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
            '301' => ['descripcion' => 'Hechos posteriores'],
            '31' => ['descripcion' => 'Nota'],
            '32' => ['descripcion' => 'Aprobación de los Estados Financieros '],
            '27' => ['descripcion' => 'Ingresos'],
            '50' => ['descripcion' => 'Superavit de capital'],
            '51' => ['descripcion' => 'Reservas'],
            '53' => ['descripcion' => 'Ganancias acumuladas - Adopcion por primera vez'],
            '54' => ['descripcion' => 'Dividendos o participacion'],
            '56' => ['descripcion' => 'Resultado del ejercicio'],
            '57' => ['descripcion' => 'Utilidad y/o perdidas acumuladas'],
            '58' => ['descripcion' => 'Superavit de Capital Valorizacion'],
            '59' =>['descripcion' => 'Total activo'],
            '60'=>['descripcion' => 'Total Pasivo'],
            '61' =>['descripcion' => 'Total patrimonio'],
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
                    ['1699'],['1692']
                ],
                '13' => [ // Nota 13
                    ['17'],['1730']
                ],
                '14' => [ // Nota 14
                    ['21'],['210517']
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
                    ['2605'],['2635'],
                ],
                '23' => [ // Nota 23
                    ['272505'],
                ],
                '40' => [ // Nota 40
                    ['28'],['2820'],['2825'],['2830'],['2835'],['2840'],['2850'],['2855'],['2865'],['2870'],['2895'],['2805'],['2810'],['2815']
                ],
                '41' => [ // Nota 41
                    ['29'],
                ],
                '50' => [['32']],
                '51' => [['33']],
                '53' => [['34']],
                '54' => [['35']],
                '56' => [['362']],
                '57' => [['37']],
                '58' => [['38']],
                '36' => [['36']],
                '24' => [['31']],
                '59' => [['1']],
                '60' => [['2']],
                '61' => [['3']],
            ];
          

        // Recibir el array desde el fetch
        $informeData2 = $request->input('informeData');

        $resultados = $this->validarAgrupadores($nit, $fechaInicio, $informeData2, $cuentasPorNota, $agrupaciones);
        // ==========================================================
        // 🔹 NUEVA PARTE - VALIDAR ESTADO DE RESULTADOS
        // ==========================================================
        $siigo = Empresa::where('nit', $nit)->value('tipo');
        $empresaasociada = Empresa::where('NIT', $nit)->value('empresaasociada');
        if($empresaasociada){
            $operador = Empresa::where('NIT', $nit)->value('operador') ?? '+';
            // =============================
            // ESTADO RESULTADOS DICIEMBRE
            // =============================

            $infResDic1 = app(\App\Services\InformeEstadoResultadosServices::class)
                ->ejecutar($fechaInicio, $nit, $siigo, $centro_costo=null, 2, $tipoinformepdf = 2,$valorparautilidad=1);

            $infResDic2 = app(\App\Services\InformeEstadoResultadosServices::class)
                ->ejecutar($fechaInicio, $empresaasociada, $siigo, $centro_costo=null, 2, $tipoinformepdf = 2,$valorparautilidad=1);

            $operador = $operador;

            $estadoResultados = $this->consolidador->combinarEstadoResultados(
                $infResDic1,
                $infResDic2,
                $operador
            );

        }else{
            // =============================
            // EMPRESA NORMAL
            // ============================
          $estadoResultados = app(\App\Services\InformeEstadoResultadosServices::class)
            ->ejecutar($fechaInicio, $nit, $siigo, $centro_costo=null, 2, $tipoinformepdf = 2,$valorparautilidad=1);
        }
        
        $anioSeleccionado = (int) date('Y', strtotime($fechaInicio));
        // Indexar por cuenta los movimientos (igual que arriba)
        // $movimientos = $this->consultaporcuentanotaspdf($nit, $fechaInicio, null);
        if($empresaasociada){

            $notas1 = $this->consultaporcuentanotaspdf(
                $nit,
                $fechaInicio,
                $numeroCuenta = null
            );

            $notas2 = $this->consultaporcuentanotaspdf(
                $empresaasociada,
                $fechaInicio,
                $numeroCuenta = null
            );

            $movimientos = $this->consolidador->combinarNotasSituacion(
                $notas1,
                $notas2,
                $operador
            );

        }else{

            $movimientos = $this->consultaporcuentanotaspdf(
                $nit,
                $fechaInicio,
                $numeroCuenta = null
            );
        }

        $byCuenta = [];
        foreach ($movimientos as $m) {
            $codigo = (string) $m->cuenta;
            $byCuenta[$codigo] = [
                'a1' => (float) $m->saldo_anio_actual,
                'a2' => (float) $m->saldo_anio_anterior,
            ];
        }

        $getVal = function($code, $year) use ($byCuenta) {
            return $byCuenta[$code][$year] ?? 0.0;
        };

        // Mapeo de cuentas del Estado de Resultados
        $cuentasEstado = [
            '41'   => 'Ingresos de actividades ordinarias',
            '6+7'  => 'Costos de venta',   // 🔹 Se suman las cuentas 6 y 7
            '4210' => 'Ingresos financieros',
            '42-4210'   => 'Otros ingresos',
            '51'   => 'Gastos de administración',
            '52'   => 'Gastos de ventas',
            '5305' => 'Gastos financieros',
            '53-5305'   => 'Otros gastos',
            '54' => 'Gastos impuesto de renta y cree',
            '61'   => 'Utilidad Bruta',
            '62'   => 'Utilidad (Pérdida) operativa',
            '63'   => 'Utilidad (Pérdida) antes de impuestos de renta',
            '64'   => 'Utilidad (Perdida) Neta del periodo',

        ];
        foreach ($cuentasEstado as $cuenta => $descripcion) {
            $ingresos = (float)$getVal('41', 'a1');
            $costos1 = (float)$getVal('6', 'a1');
            $costos2 = (float)$getVal('7', 'a1');
            $costos = $costos1 + $costos2;
            $ingresos2 = (float)$getVal('41', 'a2');
            $costosa21 = (float)$getVal('6', 'a2');
            $costosa22 = (float)$getVal('7', 'a2');
            $costos2 = $costosa21 + $costosa22;
            $otrosingresossincalcular = (float)$getVal('42', 'a1');
            $otrosingresos2sincalcular = (float)$getVal('42', 'a2');
            $ingresosFinancierosA1 = (float)$getVal('4210', 'a1');
            $ingresosFinancierosA2 = (float)$getVal('4210', 'a2');
            $otrosIngresosA1 = $otrosingresossincalcular - $ingresosFinancierosA1;
            $otrosIngresosA2 = $otrosingresos2sincalcular - $ingresosFinancierosA2;
            $otrosgastossincalcular = (float)$getVal('53', 'a1');
            $gastosFinancierosA1 = (float)$getVal('5305', 'a1');
            $otrosgastos2sincalcular = (float)$getVal('53', 'a2');
            $gastosFinancierosA2 = (float)$getVal('5305', 'a2');
            $otrosGastosA1 = $otrosgastossincalcular - $gastosFinancierosA1;
            $otrosGastosA2 = $otrosgastos2sincalcular - $gastosFinancierosA2;
            $gastosAdminA2 = (float)$getVal('51', 'a2');
            $gastosVentaA2 = (float)$getVal('52', 'a2');
            $gastosAdminA1 = (float)$getVal('51', 'a1');
            $gastosVentaA1 = (float)$getVal('52', 'a1');
            $utilidadBrutaA1 = abs($ingresos) - abs($costos);
            $utilidadBrutaA2 = abs($ingresos2) - abs($costos2);
            $utilidadOperativaA1 = $utilidadBrutaA1 -  $gastosAdminA1 - $gastosVentaA1;
            $utilidadOperativaA2 = $utilidadBrutaA2 -  $gastosAdminA2 - $gastosVentaA2;
            $gastosImpuestoA1 = (float)$getVal('54', 'a1');
            $gastosImpuestoA2 = (float)$getVal('54', 'a2');
            $otrosingresos = $nit == '901246963' ? $otrosIngresosA1*-1 : abs($otrosIngresosA1); 
            $otrosingresosa2 = $nit == '901246963' ? $otrosIngresosA2*-1 : abs($otrosIngresosA2);
            // Calcular valores
            if ($cuenta === '6+7') {
                $totalA1 = (float)$getVal('6', 'a1') + (float)$getVal('7', 'a1');
                $totalA2 = (float)$getVal('6', 'a2') + (float)$getVal('7', 'a2');
            }elseif ($cuenta === '42-4210') {
                // Otros ingresos = 42 - 4210
                $totalA1 = (float)$getVal('42', 'a1') - (float)$getVal('4210', 'a1');
                $totalA2 = (float)$getVal('42', 'a2') - (float)$getVal('4210', 'a2');
            } elseif ($cuenta === '53-5305') {
                // Otros gastos = 53 - 5305
                $totalA1 = (float)$getVal('53', 'a1') - (float)$getVal('5305', 'a1');
                $totalA2 = (float)$getVal('53', 'a2') - (float)$getVal('5305', 'a2');
            }elseif ($descripcion === 'Utilidad Bruta') {
                // 'Utilidad Bruta' = abs(Ingresos de actividades ordinarias) - abs(costos de ventas)
                $totalA1 = abs($ingresos) - abs($costos);
                $totalA2 = abs($ingresos2) - abs($costos2);

            } elseif ($descripcion === 'Utilidad (Pérdida) operativa') {
                // 'Utilidad (Pérdida) operativa' = utilidad bruta - gastos de administracion - gastos de venta
                $totalA1 = $utilidadBrutaA1 - $gastosAdminA1 - $gastosVentaA1;
                $totalA2 = $utilidadBrutaA2 - $gastosAdminA2 - $gastosVentaA2;

            } elseif ($descripcion === 'Utilidad (Pérdida) antes de impuestos de renta') {
                // 'Utilidad (Pérdida) antes de impuestos de renta'
                $totalA1 = $utilidadOperativaA1 + $otrosingresos + abs($ingresosFinancierosA1) - $otrosGastosA1 - $gastosFinancierosA1;
                $totalA2 = $utilidadOperativaA2 + $otrosingresosa2 + abs($ingresosFinancierosA2) - $otrosGastosA2 - $gastosFinancierosA2;

            } elseif ($descripcion === 'Utilidad (Perdida) Neta del periodo') {
                // 'Utilidad (Perdida) Neta del periodo'
                $utilidadAntesImpA1 = $utilidadOperativaA1 + $otrosingresos + abs($ingresosFinancierosA1) - $otrosGastosA1 - $gastosFinancierosA1;
                $utilidadAntesImpA2 = $utilidadOperativaA2 + $otrosingresosa2 + abs($ingresosFinancierosA2) - $otrosGastosA2 - $gastosFinancierosA2;
                $totalA1 = $utilidadAntesImpA1 - $gastosImpuestoA1;
                $totalA2 = $utilidadAntesImpA2 - $gastosImpuestoA2;
            }else {
                $totalA1 = (float)$getVal($cuenta, 'a1');
                $totalA2 = (float)$getVal($cuenta, 'a2');
            }

            // Buscar en informeData (usa la descripción como clave)
            $filaInforme = $estadoResultados['informeData'][$descripcion] ?? null;

            $valorInfA1 = (float) ($filaInforme[$anioSeleccionado] ?? 0);
            $valorInfA2 = (float) ($filaInforme[$anioSeleccionado - 1] ?? 0);

            // Comparar con tolerancia ±5
            $cuadradoA1 = abs(abs($totalA1) - abs($valorInfA1)) <= 5;
            $cuadradoA2 = abs(abs($totalA2) - abs($valorInfA2)) <= 5;

            // Agregar al array final
            $resultados[] = [
                'agrupador'        => $cuenta,
                'descripcion'      => $descripcion,
                'totalCalculadoA1' => $totalA1,
                'valorInformeA1'   => $valorInfA1,
                'cuadradoA1'       => $cuadradoA1,
                'totalCalculadoA2' => $totalA2,
                'valorInformeA2'   => $valorInfA2,
                'cuadradoA2'       => $cuadradoA2,
            ];
        }
        // === Agregar comparación con getAcumulado ===
        $anioSeleccionado = (int) date('Y', strtotime($fechaInicio));
        $compania = Empresa::where('nit', $nit)->first();
        $siigo = $compania->tipo;
        $ventasAcumuladas = $this->getAcumulado($anioSeleccionado, (int)date('m', strtotime($fechaInicio)), '41', $siigo, $nit);
        $ventasAcumuladas2 = $this->getAcumulado($anioSeleccionado-1, 12, '41', $siigo, $nit);

        // Valor que viene del informe para cuenta 41
        $valorInforme41A1 = (float)($estadoResultados['informeData']['Ingresos de actividades ordinarias'][$anioSeleccionado] ?? 0);
        $valorInforme41A2 = (float)($estadoResultados['informeData']['Ingresos de actividades ordinarias'][$anioSeleccionado - 1] ?? 0);

        // Comparar con tolerancia ±5
        $cuadrado41A1 = abs(abs($ventasAcumuladas->total_acumulado ?? 0) - abs($valorInforme41A1)) <= 5;
        $cuadrado41A2 = abs(abs($ventasAcumuladas2->total_acumulado ?? 0) - abs($valorInforme41A2)) <= 5;
        if ($ventasAcumuladas && $valorInforme41A1 != 0) {
            $signo = $valorInforme41A1 < 0 ? -1 : 1;
            $totalCalculadoA1 = abs($ventasAcumuladas->total_acumulado) * $signo;
        }
        $totalCalculadoA2 = 0;
        if ($ventasAcumuladas2 && $valorInforme41A2 != 0) {
            $signo2 = $valorInforme41A2 < 0 ? -1 : 1;
            $totalCalculadoA2 = abs($ventasAcumuladas2->total_acumulado) * $signo2;
        }
        // Agregar fila extra
        $resultados[] = [
            'agrupador'        => '41-ACUM',
            'descripcion'      => 'Ingresos de actividades ordinarias (Acumulado)',
            'totalCalculadoA1' => $totalCalculadoA1 ?? 0,
            'valorInformeA1'   => $valorInforme41A1,
            'cuadradoA1'       => $cuadrado41A1,
            'totalCalculadoA2' => $totalCalculadoA2, // opcional: puedes traer acumulado del año anterior si lo necesitas
            'valorInformeA2'   => $valorInforme41A2,
            'cuadradoA2'       => $cuadrado41A2,
        ];
        return response()->json($resultados);
    }

    //validador estados de resultados
    public function validarComparacion(Request $request)
    {
        $informeData = $request->input('informeMes');
        $fechaInicio   = $request->input('fechaInicio');
        $nit           = $request->input('nit');
        $siigo         = $request->input('siigo');
        $centro_costo  = $request->input('centro_costo');
        $empresaasociada = Empresa::where('NIT', $nit)->value('empresaasociada');
        if($empresaasociada){
            $operador = Empresa::where('NIT', $nit)->value('operador') ?? '+';
            // =============================
            // ESTADO RESULTADOS DICIEMBRE
            // =============================

            $infResDic1 = app(\App\Services\InformeEstadoResultadosServices::class)
                ->ejecutar($fechaInicio, $nit, $siigo, $centro_costo, 2, null, null);

            $infResDic2 = app(\App\Services\InformeEstadoResultadosServices::class)
                ->ejecutar($fechaInicio, $empresaasociada, $siigo, $centro_costo=null, 2, null, null);

            $operador = $operador;

            $estadoResultados = $this->consolidador->combinarEstadoResultados(
                $infResDic1,
                $infResDic2,
                $operador
            );

        }else{
            // =============================
            // EMPRESA NORMAL
            // ============================
          $estadoResultados = app(\App\Services\InformeEstadoResultadosServices::class)
            ->ejecutar($fechaInicio, $nit, $siigo, $centro_costo, 2, null, null);
        }
        $anioSeleccionado = (int) date('Y', strtotime($fechaInicio));

        $mapaComparacion = [
            "Total Ventas Netas"             => "Ingresos de actividades ordinarias",
            "Margen Bruto ventas"            => "Utilidad Bruta",
            "Gastos Operacionales"           => "Gastos de administración",
            "Gastos de ventas"               => "Gastos de ventas",
            "Utilidad Operacional"           => "Utilidad (Pérdida) operativa",
            "Otros Ingresos"                 => "Otros ingresos",
            "Ingresos financieros"           => "Ingresos financieros",
            "Otros Egresos"                  => "Otros gastos",
            "Gastos financieros"             => "Gastos financieros",
            "Utilidad antes de Impuestos"    => "Utilidad (Pérdida) antes de impuestos de renta",
            "Utilidad/perdida neta"          => "Utilidad (Perdida) Neta del periodo",
            "Provision impuesto de renta"    => "Gastos impuesto de renta y cree",
        ];

        $informeMapeado = [];
        foreach ($informeData as $row) {
            // 1️⃣ Eliminamos puntos de miles y convertimos coma decimal a punto
            $valorLimpio = str_replace(['.', ','], ['', '.'], $row['total']);

            // 2️⃣ Convertimos a float real
            $informeMapeado[$row['descripcion']] = (float) $valorLimpio;
            // $informeMapeado[$row['descripcion']] = (float) str_replace([','], ['.'], $row['total']);
        }

        $resultadoComparacion = [];
        foreach ($mapaComparacion as $clave => $nombreEstado) {
            $valorCalculado = $informeMapeado[$clave] ?? 0;
            $valorEsperado  = $estadoResultados['informeData'][$nombreEstado][$anioSeleccionado] ?? 0;
            if ($valorEsperado < 0) {
                $valorCalculado = -abs($valorCalculado);
            } else {
                $valorCalculado = abs($valorCalculado);
            }
            $resultadoComparacion[] = [
                'concepto'   => $clave,
                'calculado'  => $valorCalculado,
                'esperado'   => number_format($valorEsperado, 0, ',', '.'),   // mismo formato
                'diferencia' => $valorCalculado - $valorEsperado,
                'coincide' => abs($valorCalculado - $valorEsperado) <= 5,
            ];
        }

        return response()->json([
            'comparacion' => $resultadoComparacion,
        ]);
    }

    
    public function consultarOtrosIngresos($nit, $fecha)
    {
        $companias = Empresa::where('nit', $nit)->first();
        $siigo = $companias->tipo;
        // Definir la tabla según el tipo de siigo
        if ($siigo == 'CONTAPYME') {
            $tablaconsulta = 'contapyme_completo';
        } elseif ($siigo == 'LOGGRO') {
            $tablaconsulta = 'loggro';
        } elseif ($siigo == 'BEGRANDA') {
            $tablaconsulta = 'begranda';
        }  elseif($siigo == 'PYME') {
            $tablaconsulta = 'clientes';
        }else{
            $tablaconsulta = 'informesgenericos';
        }

       $isCuentaRaw = false;

        if ($siigo == 'NUBE') {
            $columnaCuenta = "$tablaconsulta.codigo_cuenta_contable_ga";
        } elseif ($siigo == 'PYME') {
            $isCuentaRaw = true;
            $columnaCuenta = DB::raw("REPLACE(CONCAT(
                TRIM(IFNULL(`$tablaconsulta`.`grupo`, '')),
                TRIM(IFNULL(`$tablaconsulta`.`cuenta`, '')),
                TRIM(IFNULL(`$tablaconsulta`.`subcuenta`, ''))
            ), ' ', '')");
        }else {
            $columnaCuenta = "cuenta";
        }

        $columnaDebito = $siigo == 'NUBE' ? 'movimiento_debito_ga' : 'debitos';
        $columnaCredito = $siigo == 'NUBE' ? 'movimiento_credito_ga' : 'creditos';
        $columnaFecha = $siigo == 'NUBE' ? 'fechareporte_ga' : 'fechareporte';
    
        // Paso 1: Obtener el total de todas las cuentas que empiezan por 42
        $totalTodasCuentas42 = DB::table($tablaconsulta)
            ->select(DB::raw("SUM(IFNULL($columnaCredito, 0) - IFNULL($columnaDebito, 0)) AS total"))
            ->where('Nit', '=', $nit)
            ->where($columnaCuenta, '=', '42')
            ->whereYear($columnaFecha, date('Y', strtotime($fecha)))
            ->whereMonth($columnaFecha, date('m', strtotime($fecha)))
            ->value('total');
    
        // Paso 2: Obtener el total de la cuenta 4210
        $totalCuenta4210 = DB::table($tablaconsulta)
            ->select(DB::raw("SUM(IFNULL($columnaCredito, 0) - IFNULL($columnaDebito, 0)) AS total"))
            ->where('Nit', '=', $nit)
            ->where($columnaCuenta, '=', '4210')
            ->whereYear($columnaFecha, date('Y', strtotime($fecha)))
            ->whereMonth($columnaFecha, date('m', strtotime($fecha)))
            ->value('total');
        if($siigo == 'PYME'){
            // Paso 3: Obtener el total de las cuentas específicas en la consulta
            $resultados = DB::table(DB::raw('(
                SELECT "421005" AS cuenta, "INTERESES" AS nombre_especifico UNION ALL
                SELECT "421040", "DESCUENTOS COMERCIALES" UNION ALL
                SELECT "4220", "ARRENDAMIENTOS" UNION ALL
                SELECT "4230", "FLETES" UNION ALL
                SELECT "4235", "SERVICIOS" UNION ALL
                SELECT "4250", "RECUPERACIONES" UNION ALL
                SELECT "429545", "BONIFICACION GRAVADA" UNION ALL
                SELECT "622505", "DESCUENTOS CONDICIONADOS EN COMPRAS"
            ) AS grupos'))
            ->leftJoin($tablaconsulta, function ($join) use ($nit, $fecha, $columnaCuenta, $columnaDebito, $columnaCredito, $columnaFecha, $tablaconsulta, $isCuentaRaw) {
                $cuentaExpr = $isCuentaRaw 
                    ? "SUBSTRING(" . $columnaCuenta->getValue(DB::connection()->getQueryGrammar()) . ", 1, LENGTH(grupos.cuenta))"
                    : "SUBSTRING($columnaCuenta, 1, LENGTH(grupos.cuenta))";

                $join->on(DB::raw($cuentaExpr), '=', 'grupos.cuenta')
                    ->where("$tablaconsulta.Nit", '=', $nit)
                    ->whereYear("$tablaconsulta.$columnaFecha", date('Y', strtotime($fecha)))
                    ->whereMonth("$tablaconsulta.$columnaFecha", date('m', strtotime($fecha)));
            })
            ->select('grupos.cuenta AS grupo_cuenta', 'grupos.nombre_especifico', DB::raw("
                COALESCE(FORMAT(SUM(IFNULL($tablaconsulta.$columnaCredito, 0) - IFNULL($tablaconsulta.$columnaDebito, 0)), 0), '-') AS saldo_total"))
            ->groupBy('grupos.cuenta', 'grupos.nombre_especifico')
            ->orderBy('grupo_cuenta')
            ->get();
        }else{
             // Paso 3: Obtener el total de las cuentas específicas en la consulta
            $resultados = DB::table(DB::raw('(
                SELECT "421005" AS cuenta, "INTERESES" AS nombre_especifico UNION ALL
                SELECT "421040", "DESCUENTOS COMERCIALES" UNION ALL
                SELECT "4220", "ARRENDAMIENTOS" UNION ALL
                SELECT "4230", "FLETES" UNION ALL
                SELECT "4235", "SERVICIOS" UNION ALL
                SELECT "4250", "RECUPERACIONES" UNION ALL
                SELECT "429545", "BONIFICACION GRAVADA" UNION ALL
                SELECT "622505", "DESCUENTOS CONDICIONADOS EN COMPRAS"
            ) AS grupos'))
            ->leftJoin($tablaconsulta, function ($join) use ($nit, $fecha, $columnaCuenta, $columnaDebito, $columnaCredito, $columnaFecha, $tablaconsulta) {
                $join->on(DB::raw("SUBSTRING($tablaconsulta.$columnaCuenta, 1, LENGTH(grupos.cuenta))"), '=', 'grupos.cuenta')
                    ->where("$tablaconsulta.Nit", '=', $nit)
                    ->whereYear("$tablaconsulta.$columnaFecha", date('Y', strtotime($fecha)))
                    ->whereMonth("$tablaconsulta.$columnaFecha", date('m', strtotime($fecha)));
            })
            ->select('grupos.cuenta AS grupo_cuenta', 'grupos.nombre_especifico', DB::raw("
                COALESCE(FORMAT(SUM(IFNULL($tablaconsulta.$columnaCredito, 0) - IFNULL($tablaconsulta.$columnaDebito, 0)), 0), '-') AS saldo_total"))
            ->groupBy('grupos.cuenta', 'grupos.nombre_especifico')
            ->orderBy('grupo_cuenta')
            ->get();
        }
    
        // Paso 4: Sumar los totales de las cuentas específicas
        $sumaTotalResultados = 0;
        foreach ($resultados as $resultado) {
            $sumaTotalResultados += floatval(str_replace(',', '', $resultado->saldo_total));
        }
    
        // Paso 5: Calcular el total para la cuenta 42 restando la cuenta 4210
        $totalCuenta42 = $totalTodasCuentas42 - $sumaTotalResultados - $totalCuenta4210;
    
        // Agregar la cuenta 42 a los resultados
        $resultados->push((object) [
            'grupo_cuenta' => '42',
            'nombre_especifico' => 'Otros ingresos',
            'saldo_total' => number_format($totalCuenta42, 0)
        ]);
    
        // Agregar la suma total de todas las cuentas en el array de resultados
        $resultados->push((object) [
            'grupo_cuenta' => 'TOTAL',
            'nombre_especifico' => ' ',
            'saldo_total' => number_format($sumaTotalResultados + $totalCuenta42, 0)
        ]);
    
        return $resultados;
    }
    

    public function consultarOtrosEgresos($nit, $fecha)
    {
        $companias = Empresa::where('nit', $nit)->first();
        $siigo = $companias->tipo;
        // Definir columnas dinámicamente según el tipo de empresa
        $isCuentaRaw = false;
        // Definir la tabla según el tipo de siigo
        if ($siigo == 'CONTAPYME') {
            $tablaconsulta = 'contapyme_completo';
        }else if ($siigo=='LOGGRO'){
            $tablaconsulta = 'loggro';
        }else if ($siigo=='BEGRANDA'){
            $tablaconsulta = 'begranda';
        }else if($siigo == 'PYME') {
            $tablaconsulta = 'clientes';
        }else {
            $tablaconsulta = 'informesgenericos';
        }
        if ($siigo == 'NUBE') {
            $columnaCuenta = "$tablaconsulta.codigo_cuenta_contable_ga";
        } elseif ($siigo == 'PYME') {
            $isCuentaRaw = true;
            $columnaCuenta = DB::raw("REPLACE(CONCAT(
                TRIM(IFNULL(`$tablaconsulta`.`grupo`, '')),
                TRIM(IFNULL(`$tablaconsulta`.`cuenta`, '')),
                TRIM(IFNULL(`$tablaconsulta`.`subcuenta`, ''))
            ), ' ', '')");
        }else {
            $columnaCuenta = "cuenta";
        }
        $columnaDebito = $siigo == 'NUBE' ? 'movimiento_debito_ga' : 'debitos';
        $columnaCredito = $siigo == 'NUBE' ? 'movimiento_credito_ga' : 'creditos';
        $columnaFecha = $siigo == 'NUBE' ? 'fechareporte_ga' : 'fechareporte';
        
        // Paso 1: Obtener el total de todas las cuentas que empiezan por 53
        $queryTotal53 = DB::table($tablaconsulta)
        ->select(DB::raw("SUM(IFNULL($columnaCredito, 0) - IFNULL($columnaDebito, 0)) AS total"))
        ->where('Nit', '=', $nit)
        ->where($columnaCuenta, '=', '53')
        ->whereYear($columnaFecha, date('Y', strtotime($fecha)))
        ->whereMonth($columnaFecha, date('m', strtotime($fecha)));

        // restar el valor de la cuenta 5305
        $queryTotal5305 = DB::table($tablaconsulta)
            ->select(DB::raw("SUM(IFNULL($columnaCredito, 0) - IFNULL($columnaDebito, 0)) AS total"))
            ->where('Nit', '=', $nit)
            ->where($columnaCuenta, '=', '5305')
            ->whereYear($columnaFecha, date('Y', strtotime($fecha)))
            ->whereMonth($columnaFecha, date('m', strtotime($fecha)))
            ->value('total');

        $totalTodasCuentas53 = $queryTotal53->value('total') - $queryTotal5305;
        

        // Paso 2: Obtener el total de las cuentas específicas en la tabla clientes
        if($siigo == 'PYME'){
            $resultados = DB::table(DB::raw('(
                SELECT "530505" AS cuenta, "GASTOS BANCARIOS" AS nombre_especifico UNION ALL
                SELECT "530515", "COMISIONES" UNION ALL
                SELECT "530520", "INTERESES" UNION ALL
                SELECT "530535", "DESCUENTOS COMERCIALES CONDICIONADOS" UNION ALL
                SELECT "531515", "COSTOS Y GASTOS DE EJERCICIOS ANTERIORES" UNION ALL
                SELECT "531520", "IMPUESTOS ASUMIDOS" UNION ALL
                SELECT "539525", "DONACIONES" UNION ALL
                SELECT "539545", "BONIFICACIONES" UNION ALL
                SELECT "539595", "OTROS"
            ) AS grupos'))
            ->leftJoin($tablaconsulta, function ($join) use ($nit, $fecha, $columnaCuenta, $columnaDebito, $columnaCredito, $columnaFecha, $tablaconsulta, $isCuentaRaw) {
                $cuentaExpr = $isCuentaRaw 
                    ? "SUBSTRING(" . $columnaCuenta->getValue(DB::connection()->getQueryGrammar()) . ", 1, LENGTH(grupos.cuenta))"
                    : "SUBSTRING($columnaCuenta, 1, LENGTH(grupos.cuenta))";

                $join->on(DB::raw($cuentaExpr), '=', 'grupos.cuenta')
                    ->where("$tablaconsulta.Nit", '=', $nit)
                    ->whereYear("$tablaconsulta.$columnaFecha", date('Y', strtotime($fecha)))
                    ->whereMonth("$tablaconsulta.$columnaFecha", date('m', strtotime($fecha)));
            })
            ->select('grupos.cuenta AS grupo_cuenta', 'grupos.nombre_especifico', DB::raw("
                CASE 
                    WHEN grupos.cuenta = '53' THEN FORMAT(COALESCE(SUM(IFNULL($tablaconsulta.$columnaCredito, 0) - IFNULL($tablaconsulta.$columnaDebito, 0)), '-'), 0)
                    ELSE COALESCE(FORMAT(SUM(IFNULL($tablaconsulta.$columnaCredito, 0) - IFNULL($tablaconsulta.$columnaDebito, 0)), 0), '-')
                END AS saldo_total"))
            ->where('grupos.cuenta', '<>', '53')
            ->groupBy('grupos.cuenta', 'grupos.nombre_especifico')
            ->orderByRaw("CASE WHEN grupos.cuenta = '53' THEN 1 ELSE 0 END, grupo_cuenta")
            ->get();
        }else{
            $resultados = DB::table(DB::raw('(
                SELECT "530505" AS cuenta, "GASTOS BANCARIOS" AS nombre_especifico UNION ALL
                SELECT "530515", "COMISIONES" UNION ALL
                SELECT "530520", "INTERESES" UNION ALL
                SELECT "530535", "DESCUENTOS COMERCIALES CONDICIONADOS" UNION ALL
                SELECT "531515", "COSTOS Y GASTOS DE EJERCICIOS ANTERIORES" UNION ALL
                SELECT "531520", "IMPUESTOS ASUMIDOS" UNION ALL
                SELECT "539525", "DONACIONES" UNION ALL
                SELECT "539545", "BONIFICACIONES" UNION ALL
                SELECT "539595", "OTROS"
            ) AS grupos'))
            ->leftJoin($tablaconsulta, function ($join) use ($nit, $fecha, $columnaCuenta, $columnaDebito, $columnaCredito, $columnaFecha,$tablaconsulta) {
                $join->on(DB::raw("SUBSTRING($tablaconsulta.$columnaCuenta, 1, LENGTH(grupos.cuenta))"), '=', 'grupos.cuenta')
                    ->where('Nit', '=', $nit)
                    ->whereYear("$tablaconsulta.$columnaFecha", date('Y', strtotime($fecha)))
                    ->whereMonth("$tablaconsulta.$columnaFecha", date('m', strtotime($fecha)));
            })
            ->select('grupos.cuenta AS grupo_cuenta', 'grupos.nombre_especifico', DB::raw("
                CASE 
                    WHEN grupos.cuenta = '53' THEN FORMAT(COALESCE(SUM(IFNULL($tablaconsulta.$columnaCredito, 0) - IFNULL($tablaconsulta.$columnaDebito, 0)), '-'), 0)
                    ELSE COALESCE(FORMAT(SUM(IFNULL($tablaconsulta.$columnaCredito, 0) - IFNULL($tablaconsulta.$columnaDebito, 0)), 0), '-')
                END AS saldo_total"))
            ->where('grupos.cuenta', '<>', '53')
            ->groupBy('grupos.cuenta', 'grupos.nombre_especifico')
            ->orderByRaw("CASE WHEN grupos.cuenta = '53' THEN 1 ELSE 0 END, grupo_cuenta")
            ->get();

        }

       // Paso 3: Sumar los totales de las cuentas específicas
       $sumaTotalResultados = 0;
       foreach ($resultados as $resultado) {
        $sumaTotalResultados += floatval(str_replace(',', '', $resultado->saldo_total));
       }

       // Paso 4: Calcular el total para la cuenta 53
       $totalCuenta53 = $totalTodasCuentas53 - $sumaTotalResultados;

       // Agregar la cuenta 53 a los resultados
       $resultados->push((object) [
       'grupo_cuenta' => '53',
       'nombre_especifico' => 'OTROS EGRESOS',
       'saldo_total' => number_format($totalCuenta53, 0)
       ]);

       // Calcular la suma total de todas las cuentas en el array de resultados
       $sumaTotalResultadosConCuenta53 = $sumaTotalResultados + $totalCuenta53;
       $resultados->push((object) [
       'grupo_cuenta' => 'TOTAL',
       'nombre_especifico' => ' ',
       'saldo_total' => number_format($sumaTotalResultadosConCuenta53, 0)
       ]);
       return $resultados;
    }


    public function guardarOrden(CreateOrdenInformesRequest $request)
    {
        $nit = $request->input('compania');
        $orden = $request->input('orden');
        $nuevoElemento = $request->input('nuevo_elemento');
        $cuenta = $request->input('cuenta');
        // Guarda el orden personalizado en la tabla orden_compania
        orden_compania_informes::updateOrCreate(
            ['nit' => $nit],
            ['orden' => json_encode($orden)]
        );

        // Si se proporcionó un nuevo elemento, guárdalo en la tabla de compañías (ordeninformes)
        if (!empty($nuevoElemento) && !empty($cuenta)) {
            $tipoSiggo = Empresa::where('NIT', $nit)->value('tipo');
            $nuevoCompania = new OrdenInformes();
            $nuevoCompania->agrupador_cuenta = $cuenta;
            $nuevoCompania->nombre = $nuevoElemento;
            $nuevoCompania->nit = $nit;
            $nuevoCompania->tipo =$tipoSiggo;
            $nuevoCompania->save();
        }

        return redirect()->back()->with('success', 'Orden personalizado guardado correctamente');
    }

    public function findCentroCosto($compania)
    {

        $compania_cc = Empresa::where('nit', $compania)->first();

        $centroCosto = CentroCosto::select('id','codigo', 'nombre')->where('compania_id', $compania_cc->id)
            ->whereNot('estado', [0])->get();

        return json_encode($centroCosto);
    }

    public function graficoEstadosFinancieros(CreateEstadosFinancierosRequest $request)
    {
        // Verifica si el usuario tiene permisos para ver informes, de lo contrario, muestra un error 403 Forbidden
        abort_if(Gate::denies('VER_INFORMES'), Response::HTTP_UNAUTHORIZED);

        // Obtiene la información de la compañía basada en el NIT proporcionado en la solicitud
        $companias = Empresa::where('nit', $request->input('compania'))->first();
        $nit = $companias->NIT;
        $siigo = $companias->tipo;
        $fecha = $request->input('fechareporte');
        $estado = $request->estado;
        $tipoinforme = $request->tipoinforme;
        $centro_costo = request('centro_costo');

        // Obtiene la orden de la compañía (asumiendo que solo hay un registro por NIT)
        $cuentas = orden_compania_informes::select('orden')->where('nit', $nit)->get();
        $orden = $cuentas->pluck('orden')->first();

        // Si el estado es 1 en la pagina de infromes donde se selecciona informe PYG, muestra el informe PYG local
        if ($request->estado == 1) {
            // Si no hay orden de datos que se encuentra en la ventana principal informes boton orden informes, muestra un mensaje de alerta
            if (empty($orden)) {
                return redirect()->back()->with('message2', 'No existen datos de ordenamiento para esta compañía. Comunícate con el administrador')->with('color', 'warning');
            }

            // Obtiene datos para el informe PYC local
            list($datos, $compania, $fecha) = $this->pyc($nit, $fecha);
            return view('admin.estadosfinancieros.pyclocal', compact('datos', 'compania', 'fecha'));
        } else {
            // Si el estado no es 1 , muestra un mensaje de alerta si no hay orden
            if (empty($orden)) {
                return redirect()->back()->with('message2', 'No existen datos de ordenamiento para esta compañía. Comunícate con el administrador')->with('color', 'warning');
            }

            // Obtiene datos para el informe general
            if ($tipoinforme == 2) {
                $datos2 = $this->Modificacion($companias->NIT, $companias->id, $request->input('fechareporte'));
            } else {
                $datos2 = $this->Modificacion($companias->NIT, $companias->id, $fecha);
            }

            $informeResultados = $this->informegeneralcuentas($companias->NIT, $request->input('fechareporte'), $companias->tipo, $datos2, $centro_costo);
            
            $compania = $companias->razon_social;
            $fecha = Carbon::parse($request->input('fechareporte'))->locale('es_ES');
            $fecha = $fecha->isoFormat('MMMM-YYYY');

            // Obtiene el total general de los resultados
            $totalGeneral = $informeResultados['totalGeneral'];
            // Obtiene el informe por mes
            $informePorMes = $informeResultados['informePorMes'];
            // Obtiene totales del informe
            $totales = $informeResultados['totales'];

            $informePorMesMerged = [];
            //Guardo todos los datos por cuenta y por mes
            foreach ($informePorMes['descripcionct'] as $index => $data) {
                if ($data !== 'Total Mes') {
                    $data = str_replace(' ', '_', $data);
                    $data = str_replace('ó', 'o', $data);
                    $data = str_replace('.', '_', $data);
            
                    //guarda la informacion por cuenta
                    // Verificar si $siigo es 'CONTAPYME' y cambiar la descripción específica
                    if ($siigo === 'CONTAPYME' && $informePorMes['descripcionct'][$index] === 'Gastos operacionales') {
                        $data = 'Operacionales de venta'; // Cambiar la descripción a la nueva
                    }
                    // $informePorMesMerged[$data] = [];
                    if($siigo ==='CONTAPYME'){

                        foreach (array_keys($informePorMes) as $mes) {
                            if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta' && $index < 18) {
                                $informePorMesMerged[$data][$mes] = floatval(str_replace(['.', ','], ['', '.'], $informePorMes[$mes][$index] ?? 0));
                            }
                        }
                    }else{
                        foreach (array_keys($informePorMes) as $mes) {
                            if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta' && $index < 16) {
                                $informePorMesMerged[$data][$mes] = floatval(str_replace(['.', ','], ['', '.'], $informePorMes[$mes][$index] ?? 0));
                            }
                        }
                    }
                    
                }
            }
            $meses = [];
            //tomo los meses
            foreach (array_keys($informePorMes) as $mes) {
                if ($mes !== 'descripcionct' && $mes !== 'Total Mes' && $mes !== 'Cuenta') {
                    $meses[] = $mes;
                }
            }

            $meses = json_encode($meses);
            $centrocostov = CentroCosto::select('codigo', 'nombre')->where('id', $centro_costo)
            ->whereNot('estado', [0])->first();
            // Muestra la vista del informe general
            return view('admin.estadosfinancieros.estadogeneral-grafico', compact(
                'totalGeneral',
                'compania',
                'fecha',
                'centrocostov',
                'totales',
                'informePorMesMerged',
                'meses'
            ));
        }
    }


    public function pdfestadocambiopatrimonio(Request $request){
        $compania = $request->input('compania');
        $fecha = $request->input('fecha');
        $titulo = $request->input('titulo');
        $tipoinforme = $request->input('tipoinforme');
        $fecha_real=$fecha;
        $fecha = Carbon::parse($fecha)->firstOfMonth();
        Carbon::setLocale('es');
        $anio = $fecha->year;
        $anioAnterior = $anio - 1;
        // Obtiene el nombre del mes en español y lo convierte a mayúsculas
        $mes = mb_strtoupper($fecha->translatedFormat('F'), 'UTF-8');
        $companias = Empresa::where('razon_social', $compania)->first();
        
        if (!$companias) {
            return back()->with('error', 'Empresa no encontrada.');
        }
        $nit = $companias->NIT;
        $siigo = $companias->tipo;
        $empresaasociada = Empresa::where('NIT', $nit)->value('empresaasociada');
        $operador = Empresa::where('NIT', $nit)->value('operador') ?? '+';
        if($tipoinforme == 1){
             if($empresaasociada){
                    
                    $informe1 = app(\App\Services\InformeEstadoPatrimonioServices::class)
                        ->ejecutar($fecha,$nit,$siigo,$centro_costo = null,3,$anio,$anioAnterior);

                    $informe2 = app(\App\Services\InformeEstadoPatrimonioServices::class)
                        ->ejecutar($fecha,$empresaasociada,$siigo,$centro_costo = null,3,$anio,$anioAnterior);
                    $informe = $this->consolidador->combinarCambioPatrimonio($informe1,$informe2,$operador);
            }else{

                 $informe = app(\App\Services\InformeEstadoPatrimonioServices::class)
                ->ejecutar($fecha,$nit,$siigo,$centro_costo = null,3,$anio,$anioAnterior);
            }
        }else{
            if($empresaasociada){
                    $operador = Empresa::where('NIT', $nit)->value('operador') ?? '+';
                    $flujo1 =$this->obtenerFlujoPorNit($nit, $siigo, $centro_costo = null, $tipoinforme, $fecha, $anio, $anioAnterior);
                    $flujo2 =$this->obtenerFlujoPorNit($empresaasociada, $siigo, $centro_costo = null, $tipoinforme, $fecha, $anio, $anioAnterior);
                
                    $informe = $this->consolidador->combinarFlujoEfectivo($flujo1,$flujo2,$anio,$anioAnterior,$operador);
            }else{

                $informe = $this->obtenerFlujoPorNit($nit, $siigo, $centro_costo = null, $tipoinforme, $fecha, $anio, $anioAnterior);
            }
        }
        
        $contadorid = Empresa::where('NIT', $nit)->value('contador');
        if (!$contadorid) {
           $contadorid =1;
        }
        $datoscontador=User::select('nombres','apellidos','tarje_profesional','firma')->find($contadorid);
        $representantelegal=Empresa::select('Cedula','representantelegal','razon_social','tipo','actividadeconomica','firmarepresentante','firmarevisorfiscal','logocliente','revisorfiscal','cedularevisor')->where('NIT',$nit)->first();
        // Obtener la imagen de la firma del contador
        $base64Imagefirmacontador = $this->getBase64Image($datoscontador['firma'] ?? null, 'storage/users_firma');
        // Obtener la firma del representante legal
        $representantelegalfirma = $this->getBase64Image($representantelegal['firmarepresentante'] ?? null, 'storage/representante_firma');
        // Obtener la firma del revisor fiscal
        $revisorfiscalfirma = $this->getBase64Image($representantelegal['firmarevisorfiscal'] ?? null, 'storage/revisor_firma');
        // Obtener el logo cliente
        $logocliente = $this->getBase64Image($representantelegal['logocliente'] ?? null, 'storage/logo_cliente');
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        // Cargar la vista
        $html = view('admin.estadosfinancieros.pdfestadocambiopatrimonio', compact('informe', 'compania', 'fecha_real','datoscontador','base64Imagefirmacontador','representantelegal','representantelegalfirma','revisorfiscalfirma','logocliente','titulo','tipoinforme','mes','anio', 'nit'))->render();

         // Cargar el HTML en Dompdf
        $dompdf->loadHtml($html);
        if($tipoinforme==1){
            $dompdf->setPaper('A4', 'landscape');
        }else{
            $dompdf->setPaper('A4', 'portrait');
        }
        // Renderizar el PDF
        $dompdf->render();
        $nombreArchivo = str_replace(' ', '_', $titulo) . '.pdf';

        return $dompdf->stream($nombreArchivo, [
            'Attachment' => false // Se abre en otra pestaña
        ]);
    }


    public function construirCaseCuenta($campoCuenta, $ordenArray) {
        $caseCuenta = "CASE ";
        foreach ($ordenArray as $codigo) {
            $caseCuenta .= "WHEN {$campoCuenta} = '{$codigo}' THEN '{$codigo}' ";
        }
        return $caseCuenta . "ELSE NULL END AS cuenta";
    }

    public function exportEstadoResultados(Request $request)
    {
        $informeData = json_decode($request->input('informeData'), true);
        $anio = $request->input('anio');
        $anioAnterior = $request->input('anioAnterior');
        $mes = $request->input('mes');
        $tipo= $request->input('tipo');
        $compania = $request->input('compania');
        
        
        if($tipo == 1 || $tipo == 2 || $tipo == 4){
            $nombreArchivo = $tipo == 1 ? 'Estado_Resultados_' : 'Estado_Situacion_financiera_';
            $mensual = '';
            if($tipo == 4){
                $mensual = 'Mensual_';
            }
            return Excel::download(
                new InformeEstadoResultadosExport($informeData, $anio, $anioAnterior, $mes,$tipo,$compania),
                $nombreArchivo.$mensual.$anio.'.xlsx'
            );
        }else if($tipo == 3){
            try {
                return Excel::download(
                    new InformeEstadoPatrimonioExport($informeData, $anio, $anioAnterior, $mes, $tipo,$compania),
                    "Informe_Estado_Patrimonio_{$anio}.xlsx"
                );
            } catch (\Throwable $e) {
                dd($e->getMessage(), $e->getTraceAsString());
            }
        }else{
            return Excel::download(
                new InformeFlujoEfectivoExport($informeData, $anio, $anioAnterior, $mes,$compania),
                "Informe_Flujo_Efectivo_{$anio}.xlsx"
            );
        }
    }

  
}
