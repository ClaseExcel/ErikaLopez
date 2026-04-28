<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\MissingHeadingsException;
use App\Exceptions\NitMismatchException;
use App\Exports\FormatoBalanceExport;
use App\Http\Controllers\Controller;
use App\Imports\AlegraImport;
use App\Imports\BegrandaImport;
use App\Imports\ContapymeImport;
use App\Imports\IlimitadaImport;
use App\Imports\InformesGenericosImport;
use App\Imports\LoggroImport;
use App\Imports\MileniumImport;
use App\Imports\MileniumImportImport;
use App\Imports\SagImport;
use App\Imports\WimaxImport;
use App\Imports\WorldOfficeImport;
use App\Jobs\ExcelMovimientosBatchImport;
use App\Models\Begranda;
use App\Models\ClientesMoviemientos;
use App\Models\ClientesMovimientos;
use App\Models\Compania;
use App\Models\Contapyme;
use App\Models\ContapymeCompleto;
use App\Models\Empresa;
use App\Models\FechasExistentesIC;
use App\Models\InformesGenericos;
use App\Models\loggro;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class ClientesMovimientosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request  $request)
    {
        abort_if(Gate::denies('GESTIONAR_CLIENTES'), Response::HTTP_UNAUTHORIZED);
        $companias = Empresa::select('NIT', 'razon_social', 'tipo')->orderBy('razon_social', 'asc')->get();
        $fechasExistentes = FechasExistentesIC::with('empresa')
            ->select('fecha_creacion', 'empresa_id') // Puedes dejar solo estos si solo se usan
            ->get()
            ->map(function ($item) {
            return [
                'Nit' => $item->empresa->NIT ?? 'N/A',
                'razon_social' => $item->empresa->razon_social ?? 'N/A',
                'year_reporte' => optional($item->fecha_creacion)->format('Y'),
                'month_reporte' => optional($item->fecha_creacion)->format('n'),
            ];
        });
        return view('admin.clientesmovimiento.create', compact('companias','fechasExistentes'));
        
    }

    public function msiigoweb(Request  $request)
    {
        abort_if(Gate::denies('GESTIONAR_CLIENTES'), Response::HTTP_UNAUTHORIZED);
        $companias = Empresa::select('NIT', 'razon_social', 'tipo')->orderBy('razon_social', 'asc')->get();
        $fechasExistentes = FechasExistentesIC::with('empresa')
        ->select('fecha_creacion', 'empresa_id') // Puedes dejar solo estos si solo se usan
        ->get()
        ->map(function ($item) {
            return [
                'Nit' => $item->empresa->NIT ?? 'N/A',
                'razon_social' => $item->empresa->razon_social ?? 'N/A',
                'year_reporte' => optional($item->fecha_creacion)->format('Y'),
                'month_reporte' => optional($item->fecha_creacion)->format('n'),
            ];
        });
        return view('admin.clientesmovimiento.create', compact('companias','fechasExistentes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('CREAR_CLIENTES'), Response::HTTP_UNAUTHORIZED);
        $companias = Empresa::select('NIT', 'razon_social', 'tipo')->orderBy('razon_social', 'asc')->get();
        $fechasExistentes = FechasExistentesIC::with('empresa')
        ->select('fecha_creacion', 'empresa_id') // Puedes dejar solo estos si solo se usan
        ->get()
        ->map(function ($item) {
            return [
                'Nit' => $item->empresa->NIT ?? 'N/A',
                'razon_social' => $item->empresa->razon_social ?? 'N/A',
                'year_reporte' => optional($item->fecha_creacion)->format('Y'),
                'month_reporte' => optional($item->fecha_creacion)->format('n'),
            ];
        });

        return view('admin.clientesmovimiento.create', compact('companias','fechasExistentes'));
    }

    public function showFechasExistentes()
    {
        // Asumiendo que obtienes las fechas existentes de la base de datos
        $fechasExistentes = ClientesMovimientos::selectRaw('clientesmovimientos.Nit, YEAR(clientesmovimientos.fecha_reporte) as year_reporte, MONTH(clientesmovimientos.fecha_reporte) as month_reporte, YEAR(clientesmovimientos.fecha_reporte_sw) as year_reporte_sw, MONTH(clientesmovimientos.fecha_reporte_sw) as month_reporte_sw, empresas.razon_social')
        ->join('empresas', 'clientesmovimientos.Nit', '=', 'empresas.Nit')
        ->union(
            DB::table('contapyme_completo as contapyme')
                ->selectRaw('contapyme.Nit, YEAR(contapyme.fechareporte) as year_reporte, MONTH(contapyme.fechareporte) as month_reporte, YEAR(contapyme.fechareporte) as year_reporte_sw, MONTH(contapyme.fechareporte) as month_reporte_sw, empresas.razon_social')
                ->join('empresas', 'contapyme.Nit', '=', 'empresas.Nit')
        )
        ->union(
            DB::table('loggro')
                ->selectRaw('loggro.Nit, YEAR(loggro.fechareporte) as year_reporte, MONTH(loggro.fechareporte) as month_reporte, YEAR(loggro.fechareporte) as year_reporte_sw, MONTH(loggro.fechareporte) as month_reporte_sw, empresas.razon_social')
                ->join('empresas', 'loggro.Nit', '=', 'empresas.Nit')
        )->union(
            DB::table('begranda')
                ->selectRaw('begranda.Nit, YEAR(begranda.fechareporte) as year_reporte, MONTH(begranda.fechareporte) as month_reporte, YEAR(begranda.fechareporte) as year_reporte_sw, MONTH(begranda.fechareporte) as month_reporte_sw, empresas.razon_social')
                ->join('empresas', 'begranda.Nit', '=', 'empresas.Nit')
        )
        ->union(
            DB::table('informesgenericos')
                ->selectRaw('informesgenericos.Nit, YEAR(informesgenericos.fechareporte) as year_reporte, MONTH(informesgenericos.fechareporte) as month_reporte, YEAR(informesgenericos.fechareporte) as year_reporte_sw, MONTH(informesgenericos.fechareporte) as month_reporte_sw, empresas.razon_social')
                ->join('empresas', 'informesgenericos.Nit', '=', 'empresas.Nit')
        )
        ->distinct()
        ->get();

        // Pasar los datos a la vista
        return view('admin.clientesmovimiento.fechasexistentes', compact('fechasExistentes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        set_time_limit(0);
        $tipoEmpresa = $request->input('tipo-empresa');
        if ($tipoEmpresa === 'NUBE') {
            $validator = Validator::make($request->all(), [
                'cliente' => 'required',
                'fechareporte' => 'required',
            ]);
            if ($validator->fails()) {
                return back()->with('message2', 'Los campos marcados con * son requeridos.')->with('color', 'warning');
            }
            // Obtener el archivo  
            $file = $request->file('file');
            // Validar si se cargó el archivo
            if (!isset($file)) {
                return back()->with('message2', 'Por favor, cargue el archivo correspondiente para continuar.')->with('color', 'warning');
            }
            $job = new ExcelMovimientosBatchImport($file->getRealPath(), $request->cliente, $request->fechareporte);
            dispatch($job);
            Session::flash('file_upload_completed', true);
            // Redireccionar a la vista de importación con un mensaje de éxito
            return back();
        }else if($tipoEmpresa === 'CONTAPYME'){
            $validator = Validator::make($request->all(), [
                'cliente' => 'required',
                'fechareporte' => 'required',
            ]);
            if ($validator->fails()) {
                return back()->with('message2', 'Los campos marcados con * son requeridos.')->with('color', 'warning');
            }
            // Obtener el archivo  
            $file = $request->file('file');
            $nit = $request->input('cliente');
            $fecha = $request->input('fechareporte');
            // Validar si se cargó el archivo
            if (!isset($file)) {
                return back()->with('message2', 'Por favor, cargue el archivo correspondiente para continuar.')->with('color', 'warning');
            }
            try {
                // Importar el archivo Excel
                Excel::import(new ContapymeImport($nit,$fecha), $file);
                return back()->with('message', 'Archivo importado exitosamente')->with('color', 'success');
            } catch (MissingHeadingsException $e) {
                return back()->with('message2', $e->getMessage())->with('color', 'danger');
            } catch (NitMismatchException $e) {
                return back()->with('message2', $e->getMessage())->with('color', 'danger');
            }
            return back()->with('message', 'Archivo importado exitosamente')->with('color', 'success');
        }else if($tipoEmpresa === 'LOGGRO'){
            $validator = Validator::make($request->all(), [
                'cliente' => 'required',
                'fechareporte' => 'required',
            ]);
            if ($validator->fails()) {
                return back()->with('message2', 'Los campos marcados con * son requeridos.')->with('color', 'warning');
            }
            // Obtener el archivo  
            $file = $request->file('file');
            $nit = $request->input('cliente');
            $fecha = $request->input('fechareporte');
            // Validar si se cargó el archivo
            if (!isset($file)) {
                return back()->with('message2', 'Por favor, cargue el archivo correspondiente para continuar.')->with('color', 'warning');
            }
            try {
                // Importar el archivo Excel
                Excel::import(new LoggroImport($nit,$fecha), $file);
                return back()->with('message', 'Archivo importado exitosamente')->with('color', 'success');
            } catch (MissingHeadingsException $e) {
                return back()->with('message2', $e->getMessage())->with('color', 'danger');
            } catch (NitMismatchException $e) {
                return back()->with('message2', $e->getMessage())->with('color', 'danger');
            }
            return back()->with('message', 'Archivo importado exitosamente')->with('color', 'success');
        }else if($tipoEmpresa === 'BEGRANDA'){
            $validator = Validator::make($request->all(), [
                'cliente' => 'required',
                'fechareporte' => 'required',
            ]);
            if ($validator->fails()) {
                return back()->with('message2', 'Los campos marcados con * son requeridos.')->with('color', 'warning');
            }
            // Obtener el archivo  
            $file = $request->file('file');
            $nit = $request->input('cliente');
            $fecha = $request->input('fechareporte');
            // Validar si se cargó el archivo
            if (!isset($file)) {
                return back()->with('message2', 'Por favor, cargue el archivo correspondiente para continuar.')->with('color', 'warning');
            }
            try {
                // Importar el archivo Excel
                Excel::import(new BegrandaImport($nit,$fecha), $file);
                return back()->with('message', 'Archivo importado exitosamente')->with('color', 'success');
            } catch (MissingHeadingsException $e) {
                return back()->with('message2', $e->getMessage())->with('color', 'danger');
            } catch (NitMismatchException $e) {
                return back()->with('message2', $e->getMessage())->with('color', 'danger');
            }
            return back()->with('message', 'Archivo importado exitosamente')->with('color', 'success');
        }else if($tipoEmpresa === 'WIMAX'){
            $validator = Validator::make($request->all(), [
                'cliente' => 'required',
                'fechareporte' => 'required',
            ]);
            if ($validator->fails()) {
                return back()->with('message2', 'Los campos marcados con * son requeridos.')->with('color', 'warning');
            }
            // Obtener el archivo  
            $file = $request->file('file');
            $nit = $request->input('cliente');
            $fecha = $request->input('fechareporte');
            // Validar si se cargó el archivo
            if (!isset($file)) {
                return back()->with('message2', 'Por favor, cargue el archivo correspondiente para continuar.')->with('color', 'warning');
            }
            try {
                // Importar el archivo Excel
                Excel::import(new WimaxImport($nit,$fecha), $file);
                return back()->with('message', 'Archivo importado exitosamente')->with('color', 'success');
            } catch (MissingHeadingsException $e) {
                return back()->with('message2', $e->getMessage())->with('color', 'danger');
            } catch (NitMismatchException $e) {
                return back()->with('message2', $e->getMessage())->with('color', 'danger');
            }
            return back()->with('message', 'Archivo importado exitosamente')->with('color', 'success');
        }else if($tipoEmpresa === 'GENERICO'){
            $validator = Validator::make($request->all(), [
                'cliente' => 'required',
                'fechareporte' => 'required',
            ]);
            if ($validator->fails()) {
                return back()->with('message2', 'Los campos marcados con * son requeridos.')->with('color', 'warning');
            }
            // Obtener el archivo  
            $file = $request->file('file');
            $nit = $request->input('cliente');
            $fecha = $request->input('fechareporte');
            // Validar si se cargó el archivo
            if (!isset($file)) {
                return back()->with('message2', 'Por favor, cargue el archivo correspondiente para continuar.')->with('color', 'warning');
            }
            try {
                // Importar el archivo Excel
                Excel::import(new InformesGenericosImport($nit,$fecha), $file);
                return back()->with('message', 'Archivo importado exitosamente')->with('color', 'success');
            } catch (MissingHeadingsException $e) {
                return back()->with('message2', $e->getMessage())->with('color', 'danger');
            } catch (NitMismatchException $e) {
                return back()->with('message2', $e->getMessage())->with('color', 'danger');
            }
            return back()->with('message', 'Archivo importado exitosamente')->with('color', 'success');
        }else if($tipoEmpresa === 'ILIMITADA'){
            $validator = Validator::make($request->all(), [
                'cliente' => 'required',
                'fechareporte' => 'required',
            ]);
            if ($validator->fails()) {
                return back()->with('message2', 'Los campos marcados con * son requeridos.')->with('color', 'warning');
            }
            // Obtener el archivo  
            $file = $request->file('file');
            $nit = $request->input('cliente');
            $fecha = $request->input('fechareporte');
            // Validar si se cargó el archivo
            if (!isset($file)) {
                return back()->with('message2', 'Por favor, cargue el archivo correspondiente para continuar.')->with('color', 'warning');
            }
            try {
                // Importar el archivo Excel
                Excel::import(new IlimitadaImport($nit,$fecha), $file);
                return back()->with('message', 'Archivo importado exitosamente')->with('color', 'success');
            } catch (MissingHeadingsException $e) {
                return back()->with('message2', $e->getMessage())->with('color', 'danger');
            } catch (NitMismatchException $e) {
                return back()->with('message2', $e->getMessage())->with('color', 'danger');
            }
            return back()->with('message', 'Archivo importado exitosamente')->with('color', 'success');
        }else if($tipoEmpresa === 'MILENIUM'){
            $validator = Validator::make($request->all(), [
                'cliente' => 'required',
                'fechareporte' => 'required',
            ]);
            if ($validator->fails()) {
                return back()->with('message2', 'Los campos marcados con * son requeridos.')->with('color', 'warning');
            }
            // Obtener el archivo  
            $file = $request->file('file');
            $nit = $request->input('cliente');
            $fecha = $request->input('fechareporte');
            // Validar si se cargó el archivo
            if (!isset($file)) {
                return back()->with('message2', 'Por favor, cargue el archivo correspondiente para continuar.')->with('color', 'warning');
            }
            try {
                // Importar el archivo Excel
                Excel::import(new MileniumImport($nit,$fecha), $file);
                return back()->with('message', 'Archivo importado exitosamente')->with('color', 'success');
            } catch (MissingHeadingsException $e) {
                return back()->with('message2', $e->getMessage())->with('color', 'danger');
            } catch (NitMismatchException $e) {
                return back()->with('message2', $e->getMessage())->with('color', 'danger');
            }
            return back()->with('message', 'Archivo importado exitosamente')->with('color', 'success');
        }else if($tipoEmpresa === 'ALEGRA'){
            $validator = Validator::make($request->all(), [
                'cliente' => 'required',
                'fechareporte' => 'required',
            ]);
            if ($validator->fails()) {
                return back()->with('message2', 'Los campos marcados con * son requeridos.')->with('color', 'warning');
            }
            // Obtener el archivo  
            $file = $request->file('file');
            $nit = $request->input('cliente');
            $nombreempresa = Empresa::where('NIT', $nit)->value('razon_social');
            $fecha = $request->input('fechareporte');
            // Validar si se cargó el archivo
            if (!isset($file)) {
                return back()->with('message2', 'Por favor, cargue el archivo correspondiente para continuar.')->with('color', 'warning');
            }
            try {
                // Importar el archivo Excel
                Excel::import(new AlegraImport($nombreempresa,$fecha,$nit), $file);
                return back()->with('message', 'Archivo importado exitosamente')->with('color', 'success');
            } catch (MissingHeadingsException $e) {
                return back()->with('message2', $e->getMessage())->with('color', 'danger');
            } catch (NitMismatchException $e) {
                return back()->with('message2', $e->getMessage())->with('color', 'danger');
            }
            return back()->with('message', 'Archivo importado exitosamente')->with('color', 'success');
        }else if($tipoEmpresa === 'WORLD'){
             $validator = Validator::make($request->all(), [
                'cliente' => 'required',
                'fechareporte' => 'required',
            ]);
            if ($validator->fails()) {
                return back()->with('message2', 'Los campos marcados con * son requeridos.')->with('color', 'warning');
            }
            // Obtener el archivo  
            $file = $request->file('file');
            $nit = $request->input('cliente');
            $nombreempresa = Empresa::where('NIT', $nit)->value('razon_social');
            $fecha = $request->input('fechareporte');
            // Validar si se cargó el archivo
            if (!isset($file)) {
                return back()->with('message2', 'Por favor, cargue el archivo correspondiente para continuar.')->with('color', 'warning');
            }
            try {
                // Importar el archivo Excel
                Excel::import(new WorldOfficeImport($nit,$fecha), $file);
                return back()->with('message', 'Archivo importado exitosamente')->with('color', 'success');
            } catch (MissingHeadingsException $e) {
                return back()->with('message2', $e->getMessage())->with('color', 'danger');
            } catch (NitMismatchException $e) {
                return back()->with('message2', $e->getMessage())->with('color', 'danger');
            }
            return back()->with('message', 'Archivo importado exitosamente')->with('color', 'success');
        }else if($tipoEmpresa === 'SAG'){
             $validator = Validator::make($request->all(), [
                'cliente' => 'required',
                'fechareporte' => 'required',
            ]);
            if ($validator->fails()) {
                return back()->with('message2', 'Los campos marcados con * son requeridos.')->with('color', 'warning');
            }
            // Obtener el archivo  
            $file = $request->file('file');
            $nit = $request->input('cliente');
            $nombreempresa = Empresa::where('NIT', $nit)->value('razon_social');
            $fecha = $request->input('fechareporte');
            // Validar si se cargó el archivo
            if (!isset($file)) {
                return back()->with('message2', 'Por favor, cargue el archivo correspondiente para continuar.')->with('color', 'warning');
            }
            try {

                $mime = $file->getMimeType();

                $readerType = null;

                if ($mime === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
                    $readerType = \Maatwebsite\Excel\Excel::XLSX;
                }

                Excel::import(
                    new SagImport($nit, $fecha),
                    $file,
                    null,
                    $readerType
                );

                return back()
                    ->with('message', 'Archivo importado exitosamente')
                    ->with('color', 'success');

            } catch (\Throwable $e) {

                return back()
                    ->with('message2', 'Error al procesar el archivo: ' . $e->getMessage())
                    ->with('color', 'danger');
            }
            return back()->with('message', 'Archivo importado exitosamente')->with('color', 'success');
        }else {
            try {
                // Verificar si se recibieron datos JSON
                if ($request->isJson()) {
                    $data = $request->json()->all();
                    // Verificar si se recibieron datos válidos
                    if (!empty($data)) {
                        // Iterar sobre los datos y guardarlos de mil en mil
                        $chunkSize = 1000;
                        foreach (array_chunk($data, $chunkSize) as $chunk) {
                            ClientesMovimientos::insert($chunk);
                            unset($chunk); // Liberar la memoria del chunk
                            gc_collect_cycles(); // Forzar la recolección de basura
                        }
                        return response()->json(['message' => 'Datos guardados correctamente'], 200);
                    } else {
                        return response()->json(['error' => 'No se han recibido datos válidos'], 400);
                    }
                } else {
                    return response()->json(['error' => 'No se han recibido datos JSON'], 400);
                }
            } catch (\Exception $e) {
                return response()->json(['error' => 'Error interno del servidor', 'details' => $e->getMessage()], 500);
            }
        }
    }
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }


    public function descargarFormato()
    {
        $nombreArchivo = 'formato_generico.xlsx';
        return Excel::download(new FormatoBalanceExport, $nombreArchivo);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $request->validate([
            'delete_cliente' => 'required',
            'delete_fechareporte' => 'required',
        ]);

        $nit = $request->input('delete_cliente');
        $fecha = $request->input('delete_fechareporte');
        $compania=Empresa::where('nit',$nit)->get();
        // Eliminar registros de manera eficiente
        try {
            if ($compania[0]->tipo === 'NUBE') {
                ClientesMovimientos::where('Nit', $nit)
                    ->whereRaw('MONTH(fecha_reporte_sw) = MONTH(?) AND YEAR(fecha_reporte_sw) = YEAR(?)', [$fecha, $fecha])
                    ->delete();
            }else if($compania[0]->tipo === 'CONTAPYME'){
                ContapymeCompleto::where('Nit', $nit)
                ->whereRaw('MONTH(fechareporte) = MONTH(?) AND YEAR(fechareporte) = YEAR(?)', [$fecha, $fecha])
                ->delete();
            }else if($compania[0]->tipo === 'LOGGRO'){
                loggro::where('Nit', $nit)
                ->whereRaw('MONTH(fechareporte) = MONTH(?) AND YEAR(fechareporte) = YEAR(?)', [$fecha, $fecha])
                ->delete();
            }else if($compania[0]->tipo === 'BEGRANDA'){
                Begranda::where('Nit', $nit)
                ->whereRaw('MONTH(fechareporte) = MONTH(?) AND YEAR(fechareporte) = YEAR(?)', [$fecha, $fecha])
                ->delete();
            }else if($compania[0]->tipo === 'PYME'){
                ClientesMovimientos::where('Nit', $nit)
                    ->whereRaw('MONTH(fecha_reporte) = MONTH(?) AND YEAR(fecha_reporte) = YEAR(?)', [$fecha, $fecha])
                    ->delete();
            }else {
                InformesGenericos::where('Nit', $nit)
                ->whereRaw('MONTH(fechareporte) = MONTH(?) AND YEAR(fechareporte) = YEAR(?)', [$fecha, $fecha])
                ->delete();
            }
            $fechaCarbon = Carbon::parse($fecha);
            $mes = $fechaCarbon->month;
            $anio = $fechaCarbon->year;

            // Obtener la empresa por NIT
            $companiadelete = Empresa::where('NIT', $nit)->first();

            if (!$companiadelete) {
                throw new \Exception('Empresa no encontrada.');
            }

            // Eliminar registros por mes y año
            FechasExistentesIC::whereMonth('fecha_creacion', $mes)
                ->whereYear('fecha_creacion', $anio)
                ->where('empresa_id', $companiadelete->id)
                ->delete();

            return redirect()->back()->with('message2', 'Movimientos eliminados exitosamente')->with('color', 'success');
        } catch (\Exception $e) {
            return redirect()->back()->with('message2', 'Error al eliminar movimientos: ' . $e->getMessage())->with('color', 'danger');
        }
    }
}
