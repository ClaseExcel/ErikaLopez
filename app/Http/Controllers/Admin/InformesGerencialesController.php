<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\EmpleadoCliente;
use App\Services\InformeGerencialService;
use App\Services\MarkdownService;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Yajra\DataTables\Facades\DataTables;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class InformesGerencialesController extends Controller
{
    protected $markdownService;
    protected $InformeGerencialService;
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

    public function __construct(MarkdownService $markdownService, InformeGerencialService $InformeGerencialService)
    {
        $this->markdownService = $markdownService;
        $this->InformeGerencialService = $InformeGerencialService;
    }


    public function index(Request $request)
    {
        abort_if(Gate::denies('VER_INFORMES'), Response::HTTP_UNAUTHORIZED);

        if ($request->ajax()) {
            $dataInforme = []; //ENVIAR LA DATA VACIA PARA QUE NO DE ERROR
            return DataTables::of($dataInforme)->make(true);
        }

        //obtener lista de empresas para el select
        $empresas = $this->obtenerListaDeEmpresa();
        return view('admin.informesgerenciales.index', compact('empresas'));
    }

    /**
     * Obtiene la lista de empresas según el rol para el select(datalist)
     */
    private function obtenerListaDeEmpresa()
    {
        $companias = [];
        $autorizado = Auth::user()->role->id;

        if (
            $autorizado == Role::ROLE_SUPERADMIN ||
            $autorizado == Role::ROLE_ADMINISTRADOR ||
            $autorizado == Role::ROLE_CONTADORSENIOR ||
            $autorizado == Role::ROLE_CONTADORJUNIOR ||
            $autorizado == Role::ROLE_GENERICO
        ) {
            $companias = Empresa::orderBy('razon_social')->pluck('razon_social', 'Nit', 'tipo');
        }

        if ($autorizado == Role::ROLE_CLIENTE) {
            $empresaPrincipal = EmpleadoCliente::select('empresa_id')->where('user_id', $autorizado)->first();
            $empresasAsociadas = EmpleadoCliente::select('empresas_secundarias')->where('user_id', $autorizado)->get()->toArray();

            $empresasSecundarias = array_map(function ($item) {
                return json_decode($item['empresas_secundarias']);
            }, $empresasAsociadas);

            $empresasUsuario = array_map(function ($item) {
                return intval($item);
            }, $empresasSecundarias[0]);

            //agregar empresaPrincipal a empresasUsuario
            if (!in_array($empresaPrincipal->empresa_id, $empresasUsuario)) {
                array_push($empresasUsuario, $empresaPrincipal->empresa_id);
            }

            $companias = Empresa::whereIn('id', $empresasUsuario)->orderBy('razon_social')->pluck('razon_social', 'Nit', 'tipo');
        }

        return $companias;
    }


    /**
     * Genera el informe según la empresa seleccionada
     * 
     * Es ejecutado por el boton de generar informe en la vista de informes gerenciales
     */
    public function generarInforme(Request $request)
    {
        // dd($request->all());
        //obtener los datos de la empresa seleccionada y las fechas
        $compania = $request->compania;
        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin;
        $checklist = array_filter(array_map('trim', explode(',', $request->datos)));

        if ($request->ajax()) {
            //obtener tipo de siigo
            $tipoSiigo = Empresa::where('NIT', $compania)->select('tipo')->value('tipo');
            Debugbar::info("Tipo de Siigo: " . $tipoSiigo);
            $dataInforme = [];

            if ($tipoSiigo == 'PYME') {
                $dataInforme = $this->consultaSiigoPyme($compania, $fecha_inicio, $fecha_fin, $checklist);
            }

            if ($tipoSiigo == 'NUBE') {
                $dataInforme = $this->consultaSiigoNube($compania, $fecha_inicio, $fecha_fin, $checklist);
            }

            if (in_array($tipoSiigo, $this->tiposGenericos)) {
                $dataInforme = $this->consultaContaPyme($compania, $fecha_inicio, $fecha_fin, $tipoSiigo, $checklist);
            }

            //resulatdo de la consulta
            return DataTables::of($dataInforme)->make(true);
        }
    }

    private function consultaSiigoPyme($compania, $fecha_inicio, $fecha_fin, $checklist)
    {
        $mes = date('m', strtotime($fecha_fin));
        $anio = date('Y', strtotime($fecha_fin));


        $ordenArray = $this->obtenerOrdenArray('NUBE');

        $caseCuenta = "CASE ";

        // Concatenar las columnas de cuenta, subcuenta y grupo
        $cuentaSQL = "REPLACE( CONCAT(
            TRIM(IFNULL(clientes.grupo, '')),
            TRIM(IFNULL(clientes.cuenta, '')),
            TRIM(IFNULL(clientes.subcuenta, ''))
        ), ' ', ' ')";


        $cuentaSQLWithAlias = $cuentaSQL . " AS cuenta_concat";

        foreach ($ordenArray as $codigo) {
            $length = strlen($codigo);
            $caseCuenta .= "WHEN {$cuentaSQL} = '{$codigo}' THEN '{$codigo}' ";
        }

        $caseCuenta .= "ELSE NULL END AS cuenta";

        $caseCuentaTotal = 'ROUND(
                CASE 
                    WHEN SUBSTRING(' . $cuentaSQL . ', 1, 1) IN ("1","2", "3","31","32","33","34","35","36","37","38") THEN SUM(COALESCE(nuevo_saldo, 0))
                    WHEN SUBSTRING(' . $cuentaSQL . ', 1, 2) IN ("31","32","33","34","35","36","37","38") THEN SUM(COALESCE(nuevo_saldo, 0))
                    WHEN SUBSTRING(' . $cuentaSQL . ', 1, 1) IN ("4") THEN SUM(COALESCE(creditos, 0) - COALESCE(debitos, 0))
                    WHEN SUBSTRING(' . $cuentaSQL . ', 1, 4) = "4175" THEN SUM(COALESCE(debitos, 0) - COALESCE(creditos, 0))
                    ELSE SUM(COALESCE(debitos, 0) - COALESCE(creditos, 0)) 
                END, 2)';

        $finalQuery = [];



        $informeQuery = DB::table(
            DB::raw(
                "(SELECT 
                    $caseCuenta, $cuentaSQLWithAlias,
                    ROUND(
                        CASE 
                            WHEN YEAR(fechareporte) = ? AND MONTH(fechareporte) = ? THEN 
                                $caseCuentaTotal
                            ELSE 0
                        END, 2
                    ) AS total_mes
                FROM clientes
                WHERE Nit = ?
                GROUP BY cuenta_concat, YEAR(fechareporte), MONTH(fechareporte)
                HAVING total_mes != 0
                ) AS subquery"
            )
        )
            ->selectRaw('cuenta, SUM(COALESCE(total_mes, 0)) AS total_mes')
            ->whereNotNull('total_mes')
            ->groupByRaw('cuenta')
            ->orderBy('cuenta', 'asc')
            ->setBindings([$anio, $mes, $compania]);

        // guardar el resultado de la consulta
        $finalQuery[] = $informeQuery->get()->keyBy('cuenta');
        // unir los resultados de las consultas
        $informeQuery = collect();
        foreach ([0, 1, 2, 3, 4, 5] as $index) {
            if (isset($finalQuery[$index])) {
                $informeQuery = $informeQuery->merge($finalQuery[$index]);
            }
        }
        $informeQuery = $informeQuery->keyBy('cuenta');

        // mapear informe query con ordenarray y si no está en informe query agregarlo con total_mes 0
        foreach ($ordenArray as $codigo) {
            if (!isset($informeQuery[$codigo])) {
                $informeQuery[$codigo] = (object) ['cuenta' => $codigo, 'total_mes' => 0];
            }
        }

        $informeQuery = $this->InformeGerencialService->CalcularCuentas($informeQuery, $fecha_inicio, $fecha_fin, $compania, $checklist);

        //    dd($informeQuery);
        return $informeQuery;
    }

    private function consultaSiigoNube($compania, $fecha_inicio, $fecha_fin, $checklist)
    {
        $mes = date('m', strtotime($fecha_fin));
        $anio = date('Y', strtotime($fecha_fin));
        $ordenArray = $this->obtenerOrdenArray('NUBE');

        $caseCuenta = "CASE "; //1
        // $caseCuenta .= "WHEN cuenta IN ( '240801' , '240802','240805','240809','240810') THEN SUM(IFNULL(saldo_anterior, 0)) ";
        foreach ($ordenArray as $codigo) {
            $caseCuenta .= "WHEN codigo_cuenta_contable_ga = '{$codigo}' THEN '{$codigo}' ";
        }
        $caseCuenta .= "ELSE NULL END AS cuenta";

        $finalQuery = [];

        $informeQuery = DB::table(DB::raw("(SELECT 
            $caseCuenta,
            ROUND(
                CASE 
                    WHEN SUBSTRING(codigo_cuenta_contable_ga, 1, 1) IN ('1','2','3') THEN SUM(COALESCE(saldo_final_ga, 0))
                    WHEN SUBSTRING(codigo_cuenta_contable_ga, 1, 2) IN ('31','32','33','34','35','36','37','38') THEN SUM(COALESCE(saldo_final_ga, 0))
                    WHEN SUBSTRING(codigo_cuenta_contable_ga, 1, 1) IN ('4') THEN SUM(COALESCE(movimiento_credito_ga, 0) - COALESCE(movimiento_debito_ga, 0))
                    WHEN SUBSTRING(codigo_cuenta_contable_ga, 1, 4) = '4175' THEN SUM(COALESCE(movimiento_debito_ga, 0) - COALESCE(movimiento_credito_ga, 0))
                    ELSE SUM(COALESCE(movimiento_debito_ga, 0) - COALESCE(movimiento_credito_ga, 0)) 
                END, 2
            ) AS total_mes
            FROM clientes
            WHERE Nit = ?
            AND fechareporte_ga BETWEEN ? AND ?
            GROUP BY codigo_cuenta_contable_ga
            ) AS subquery"))
            ->selectRaw('cuenta, FORMAT(SUM(COALESCE(total_mes, 0)), 2) AS total_mes')
            ->whereNotNull('cuenta')
            ->groupBy('cuenta')
            ->orderBy('cuenta', 'asc')
            ->setBindings([$compania, $fecha_inicio, $fecha_fin]);

        // guardar el resultado de la consulta
        $finalQuery[] = $informeQuery->get()->keyBy('cuenta');
        // unir los resultados de las consultas
        $informeQuery = collect();
        foreach ([0, 1, 2, 3, 4, 5] as $index) {
            if (isset($finalQuery[$index])) {
                $informeQuery = $informeQuery->merge($finalQuery[$index]);
            }
        }
        $informeQuery = $informeQuery->keyBy('cuenta');

        // mapear informe query con ordenarray y si no está en informe query agregarlo con total_mes 0
        foreach ($ordenArray as $codigo) {
            if (!isset($informeQuery[$codigo])) {
                $informeQuery[$codigo] = (object) ['cuenta' => $codigo, 'total_mes' => 0];
            }
        }

        $informeQuery = $this->InformeGerencialService->CalcularCuentas($informeQuery, $fecha_inicio, $fecha_fin, $compania, $checklist);


        return $informeQuery;
    }

    private function consultaContaPyme($compania, $fecha_inicio, $fecha_fin, $tipo, $checklist)
    {

        //orden de las cuentas
        // if($tipo == 'CONTAPYME' || $tipo == 'ILIMITADA'){
        $ordenArray = $this->obtenerOrdenArray();
        // }else if($tipo == 'LOGGRO' || $tipo == 'BEGRANDA' || $tipo == 'WIMAX'){
        //     $ordenArray = $this->obtenerOrdenArray('LOGGRO');
        // }

        $caseCuentaUnDigito = "CASE "; //1
        $caseCuentaUnDigito .= "WHEN cuenta IN ( '240801' , '240802','240805','240809','240810') THEN SUM(IFNULL(saldo_anterior, 0)) ";
        // $caseCuenta = "CASE ";
        foreach ($ordenArray as $codigo) {

            $caseCuentaUnDigito .= "WHEN cuenta = '{$codigo}' THEN '{$codigo}' ";
        }

        $caseCuentaUnDigito .= "ELSE NULL END AS cuenta";

        if ($tipo == 'CONTAPYME') {
            //informe con las cuentas de un digito
            $informeQueryUnDigito = DB::table(DB::raw("(SELECT 
                    $caseCuentaUnDigito,                        
                        ROUND(
                            CASE 
                                WHEN SUBSTRING(cuenta, 1, 6) IN ('240801', '240802','240805','240809','240810') THEN SUM(IFNULL(creditos, 0) - IFNULL(debitos, 0))
                                WHEN SUBSTRING(cuenta, 1, 1) IN ('1','2') THEN SUM(IFNULL(nuevo_saldo, 0))   
                                WHEN SUBSTRING(cuenta, 1, 1) IN ('31','32','33','34','35','36','37','38') THEN SUM(IFNULL(nuevo_saldo, 0))
                                WHEN cuenta = '3' THEN (
                                    SELECT nuevo_saldo 
                                    FROM contapyme_completo 
                                    WHERE Nit = ? 
                                        AND cuenta = 3 
                                        AND fechareporte = ? 
                                    ORDER BY fechareporte DESC 
                                    LIMIT 1
                                ) 
                                ELSE 
                                    SUM(IFNULL(creditos, 0) - IFNULL(debitos, 0))
                            END , 2
                        ) AS total_mes
                    FROM contapyme_completo
                    WHERE Nit = ?
                    AND fechareporte BETWEEN ? AND ?
                    GROUP BY cuenta
                    ) AS subquery"))
                ->selectRaw('cuenta, 
                    FORMAT(SUM(COALESCE(total_mes, 0)), 2) AS total_mes')
                ->whereNotNull('cuenta') // Excluir cuentas que no están en la lista CASE
                ->groupBy('cuenta')
                ->orderBy('cuenta', 'asc')
                ->setBindings([$compania, $fecha_fin, $compania, $fecha_inicio, $fecha_fin]);
        } else if ($tipo == 'LOGGRO') {
            //informe con las cuentas de un digito
            $informeQueryUnDigito = DB::table(DB::raw("(SELECT 
            $caseCuentaUnDigito,
                ROUND(
                    CASE 
                        WHEN SUBSTRING(cuenta, 1, 1) IN ('1','2','3') THEN SUM(IFNULL(nuevo_saldo, 0))    
                        WHEN SUBSTRING(cuenta, 1, 1) IN ('31','32','33','34','35','36','37','38') THEN SUM(IFNULL(nuevo_saldo, 0))                         
                        ELSE 
                            SUM(IFNULL(saldo_final, 0))
                    END , 2
                ) AS total_mes
            FROM loggro
            WHERE Nit = ?
            AND fechareporte BETWEEN ? AND ?
            GROUP BY cuenta
            ) AS subquery"))
                ->selectRaw('cuenta, 
                FORMAT(SUM(COALESCE(total_mes, 0)), 2) AS total_mes')
                ->whereNotNull('cuenta') // Excluir cuentas que no están en la lista CASE
                ->groupBy('cuenta')
                ->orderBy('cuenta', 'asc')
                ->setBindings([$compania, $fecha_inicio, $fecha_fin]);
        } elseif ($tipo == 'BEGRANDA') {
            //informe con las cuentas de un digito
            $informeQueryUnDigito = DB::table(DB::raw("(SELECT 
            $caseCuentaUnDigito,
                ROUND(
                    CASE 
                        WHEN SUBSTRING(cuenta, 1, 1) IN ('1','2','3') THEN SUM(IFNULL(nuevo_saldo, 0))    
                        WHEN SUBSTRING(cuenta, 1, 1) IN ('31','32','33','34','35','36','37','38') THEN SUM(IFNULL(nuevo_saldo, 0))                       
                        ELSE 
                            SUM(IFNULL(saldo_final, 0))
                    END , 2
                ) AS total_mes
            FROM begranda
            WHERE Nit = ?
            AND fechareporte BETWEEN ? AND ?
            GROUP BY cuenta
            ) AS subquery"))
                ->selectRaw('cuenta, 
                FORMAT(SUM(COALESCE(total_mes, 0)), 2) AS total_mes')
                ->whereNotNull('cuenta') // Excluir cuentas que no están en la lista CASE
                ->groupBy('cuenta')
                ->orderBy('cuenta', 'asc')
                ->setBindings([$compania, $fecha_inicio, $fecha_fin]);
        } elseif ($this->tiposGenericos) {
            //informe con las cuentas de un digito
            $informeQueryUnDigito = DB::table(DB::raw("(SELECT 
                $caseCuentaUnDigito, saldo_anterior,
                    ROUND(
                        CASE 
                            WHEN SUBSTRING(cuenta, 1, 1) IN ('1','2','3') THEN SUM(IFNULL(saldo_final, 0))    
                            WHEN SUBSTRING(cuenta, 1, 1) IN ('31','32','33','34','35','36','37','38') THEN SUM(IFNULL(saldo_final, 0))     
                            WHEN cuenta IN ( '240801' , '240802','240805','240809','240810') THEN SUM(IFNULL(saldo_anterior, 0))                   
                            ELSE 
                                SUM(IFNULL(saldo_final, 0))
                        END , 2
                    ) AS total_mes
                FROM informesgenericos
                WHERE Nit = ?
                AND MONTH(fechareporte) = MONTH(?)
                AND YEAR(fechareporte) = YEAR(?)
                GROUP BY cuenta
                ) AS subquery"))
                ->selectRaw('cuenta, 
                    FORMAT(SUM(COALESCE(total_mes, 0)), 2) AS total_mes')
                ->whereNotNull('cuenta') // Excluir cuentas que no están en la lista CASE
                ->groupBy('cuenta')
                ->orderBy('cuenta', 'asc')
                ->setBindings([$compania, $fecha_fin, $fecha_fin]);
        }




        //comparar con las cuentas de ordenArray y si no existe agregarla con total_mes 0
        $informeQueryUnDigito = $informeQueryUnDigito->get()->keyBy('cuenta');
        foreach ($ordenArray as $codigo) {
            if (!isset($informeQueryUnDigito[$codigo])) {
                $informeQueryUnDigito[$codigo] = (object) ['cuenta' => $codigo, 'total_mes' => 0];
            }
        }

        $informeQuery = $informeQueryUnDigito;

        $informeQuery = $this->InformeGerencialService->CalcularCuentas($informeQuery, $fecha_inicio, $fecha_fin, $compania, $checklist); //la magia

        return $informeQuery;
    }

    public function enhanceText(Request $request)
    {
        try {
            $response = Http::withHeaders([
                "Content-Type" => "application/json",
                "Authorization" => "Bearer " . env('CHAT_GPT_KEY')
            ])->post('https://api.openai.com/v1/chat/completions', [
                "model" => env('CHAT_GPT_MODEL'),
                "messages" => [
                    [
                        "role" => "system",
                        "content" => "You are a helpful assistant that improves text. Do not include any questions or extra text like  ¿Puedo ayudarte con algo más? or  ¿Hay algo más en lo que pueda ayudarte? or  ¡Espero que esta información te sea útil!, only the improved text. begin with the data. min 2000 characters. max 2000 characters."
                    ],
                    [
                        "role" => "user",
                        "content" =>  $request['text']
                    ],
                ],
                "temperature" => 0.8,
                "max_tokens" => 150
            ])->json();

            $enhancedText = $response['choices'][0]['message']['content'];

            return response()->json(['enhancedText' => $enhancedText]);
        } catch (Throwable $e) {
            // return $e->getMessage();
            return 'Ocurrió un error al intentar obtener la respuesta. Por favor, inténtelo de nuevo recargando la página.';
        }
    }

    public function spellingChecker(Request $request)
    {
        try {
            $response = Http::withHeaders([
                "Content-Type" => "application/json",
                "Authorization" => "Bearer " . env('chat_gpt_key')
            ])->post('https://api.openai.com/v1/chat/completions', [
                "model" => env('chat_gpt_model'),
                "messages" => [
                    [
                        "role" => "system",
                        "content" => "You are a helpful assistant that corrects spelling mistakes. Do not include any questions or extra text like  ¿Puedo ayudarte con algo más? or  ¿Hay algo más en lo que pueda ayudarte? or  ¡Espero que esta información te sea útil!, only the text to correct. max 500 characters."
                    ],
                    [
                        "role" => "user",
                        "content" =>  $request['text']
                    ],
                ],
                "temperature" => 0.8,
                "max_tokens" => 150
            ])->json();

            $correctedText = $response['choices'][0]['message']['content'];

            return response()->json(['correctedText' => $correctedText]);
        } catch (Throwable $e) {
            // return $e->getMessage();
            return 'Ocurrió un error al intentar obtener la respuesta. Por favor, inténtelo de nuevo recargando la página.';
        }
    }

    public function guardarHistorialInforme(Request $request)
    {
        $data = $request->all();

        // Dar formato a las fechas
        $fechaInicial = \Carbon\Carbon::parse($data['fecha_inicial'])->format('Y-m-d');
        $fechaFinal = \Carbon\Carbon::parse($data['fecha_final'])->format('Y-m-d');
        // Obtener el id_empresa a partir del nit
        $empresaId = Empresa::where('NIT', $data['id_empresa'])->value('id');
        // Decodificar las secciones
        $secciones = json_decode($data['secciones'], true);

        // Recorrer cada sección y guardar en la base de datos
        foreach ($secciones as $seccion) {
            // Preparar datos
            $nombreSeccion = $seccion['seccion'] ?? null;
            $valor = $seccion['valor'] ?? '';
            $textoImagen = $seccion['textoImagen'] ?? '';
            $urlImagen = null;

            // Buscar si hay imagen en el request para esta sección
            $inputImagen = 'imagen_' . strtolower($nombreSeccion);

            if ($request->hasFile($inputImagen) && $request->file($inputImagen)->isValid()) {
                $file = $request->file($inputImagen);
                $extension = $file->getClientOriginalExtension();
                $fileName = uniqid() . '.' . $extension;
                $directory = storage_path('app/public/images/informe_historial');
                if (!file_exists($directory)) {
                    mkdir($directory, 0775, true);
                }
                // Si el nombre de la imagen que llega en $data es igual al que está en la base de datos, no reemplazar
                $nombreImagenEnData = $file->getClientOriginalName();
                $imagenEnBD = \App\Models\HistorialInformeGerencial::where('id_empresa', Empresa::where('NIT', $data['id_empresa'])->value('id'))
                    ->where('fecha_inicial', $fechaInicial)
                    ->where('fecha_final', $fechaFinal)
                    ->where('seccion', $nombreSeccion)
                    ->value('url_imagen');

                if ($imagenEnBD && $nombreImagenEnData === $imagenEnBD) {
                    // Saltar el reemplazo de imagen
                    $urlImagen = $imagenEnBD;
                } else {
                    $file->storeAs('images/informe_historial', $fileName, 'public');
                    // Guardar solo el nombre y la extensión
                    $urlImagen = $fileName;
                }
            }

            // Buscar si ya existe un registro con esas fechas, empresa y sección
            $historial = \App\Models\HistorialInformeGerencial::where('id_empresa', $empresaId)
                ->where('fecha_inicial', $fechaInicial)
                ->where('fecha_final', $fechaFinal)
                ->where('seccion', $nombreSeccion)
                ->first();

            // Si la sección está vacía (sin imagen, textoImagen y valor), eliminar el registro si existe
            if (empty($urlImagen)  && empty($textoImagen) && empty($valor)) {
                if ($historial) {
                    $historial->delete();
                }
                continue;
            }

            // Si no existe, crear uno nuevo
            if (!$historial) {
                $historial = new \App\Models\HistorialInformeGerencial();
                $historial->id_empresa = $empresaId;
                $historial->fecha_inicial = $fechaInicial;
                $historial->fecha_final = $fechaFinal;
                $historial->seccion = $nombreSeccion;
            }

            // Eliminar la imagen anterior si existe
            $imagenAnterior = \App\Models\HistorialInformeGerencial::where('id_empresa', $empresaId)
                ->where('fecha_inicial', $fechaInicial)
                ->where('fecha_final', $fechaFinal)
                ->where('seccion', $nombreSeccion)
                ->value('url_imagen');

            // Reemplazar la url_imagen solo si hay una nueva imagen subida
            if (!empty($urlImagen)) {
                if (!empty($imagenAnterior) && $imagenAnterior !== $urlImagen) {
                    $rutaImagenAnterior = storage_path('app/public/images/informe_historial/' . $imagenAnterior);
                    if (file_exists($rutaImagenAnterior)) {
                        unlink($rutaImagenAnterior);
                    }
                }
                $historial->url_imagen = $urlImagen;
            }

            //si la imagen se borro del frontend y no se subió una nueva, eliminar la imagen anterior
            if (!$request->hasFile($inputImagen) && empty($urlImagen)) {
                if (!empty($imagenAnterior)) {
                    $rutaImagenAnterior = storage_path('app/public/images/informe_historial/' . $imagenAnterior);
                    if (file_exists($rutaImagenAnterior)) {
                        unlink($rutaImagenAnterior);
                    }
                }
                $historial->url_imagen = null;
            }

            $historial->descripcion = $textoImagen;
            $historial->observaciones = $valor;
            $historial->save();
        }

        // Limpiar cualquier salida previa
        if (ob_get_level()) {
            ob_end_clean();
        }

        return response()->json(['message' => 'Información extra guardada correctamente.'], 200);
    }


    //cargarHistorialInforme
    public function cargarHistorialInforme(Request $request)
    {
        $data = $request->all();
        // Obtener el id_empresa a partir del nit
        $empresaId = Empresa::where('NIT', $data['id_empresa'])->value('id');

        // Dar formato a las fechas
        $fechaInicial = \Carbon\Carbon::parse($data['fecha_inicial'])->format('Y-m-d');
        $fechaFinal = \Carbon\Carbon::parse($data['fecha_final'])->format('Y-m-d');

        // Obtener todos los registros que coincidan con las fechas y empresa
        $historiales = \App\Models\HistorialInformeGerencial::where('id_empresa', $empresaId)
            ->where('fecha_inicial', $fechaInicial)
            ->where('fecha_final', $fechaFinal)
            ->get();

        $resultado = [];

        foreach ($historiales as $historial) {
            $resultado[] = [
                'seccion' => $historial->seccion,
                'valor' => $historial->observaciones,
                'textoImagen' => $historial->descripcion,
                'imagen' => $historial->url_imagen ? asset('storage/images/informe_historial/' . $historial->url_imagen) : null,
            ];
        }

        // Limpiar cualquier salida previa
        if (ob_get_level()) {
            ob_end_clean();
        }

        return response()->json(['historial' => $resultado]);
    }

    private function obtenerOrdenArray()
    {
        $ordenArray = [
            "1",
            "2",
            "3",
            "4",
            "5",
            "6",
            "7",
            "11",
            "12",
            "13",
            "14",
            "15",
            "16",
            "17",
            "18",
            "19",
            "21",
            "22",
            "23",
            "24",
            "25",
            "26",
            "27",
            "28",
            "31",
            "32",
            "33",
            "34",
            "35",
            "37",
            "38",
            "41",
            "42",
            "51",
            "52",
            "53",
            "54",
            "173",
            "1245",
            "1290",
            "1305",
            "1330",
            "1355",
            "1592",
            "1698",
            "1730",
            "2105",
            "2305",
            "2310",
            "2315",
            "2320",
            "2330",
            "2335",
            "2340",
            "2345",
            "2350",
            "2355",
            "2357",
            "2360",
            "2365",
            "2367",
            "2368",
            "2369",
            "2370",
            "2380",
            "2610",
            "2615",
            "2635",
            "2640",
            "2805",
            "2810",
            "2815",
            "4175",
            "4210",
            "5305",
            "5315",
            "135517",
            "210517",
            "240801",
            "240802",
            "240805",
            "240809",
            "240810"
        ];

        return $ordenArray;
    }
}
