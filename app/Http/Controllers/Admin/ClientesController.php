<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\MissingHeadingsException;
use App\Exceptions\NitMismatchException;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ActionButtonTrait;
use App\Imports\ContapymeBalanceImport;
use App\Jobs\ExcelClientesBatchImport;
use App\Models\Clientes;
use App\Models\Compania;
use App\Models\ContapymeCompleto;
use App\Models\Empresa;
use App\Models\FechasExistentesIC;
use App\Models\InformesGenericos;
use App\Models\loggro;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClientesController extends Controller
{
    use ActionButtonTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request  $request)
    {
        abort_if(Gate::denies('GESTIONAR_CLIENTES'), Response::HTTP_UNAUTHORIZED);
        $companias = Empresa::orderBy('razon_social')->select('NIT', 'razon_social', 'tipo')->get();
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
        return view('admin.clientes.create', compact('companias','fechasExistentes'));
        
    }

    public function siigoweb(Request  $request)
    {
        abort_if(Gate::denies('GESTIONAR_CLIENTES'), Response::HTTP_UNAUTHORIZED);

        //Consulto los nit de los clientes
        $listas =  Clientes::select('Nit')->groupBy('Nit')->where('nivel_ga', '<>', 'null')->get();
        $empresas = [];

        foreach ($listas as $lista) {
            $compania = Empresa::select('razon_social')->where('nit', $lista->Nit)->first();
            //guardo las companias con su Nit y razon social
            $empresas[] = [
                'Nit' => $lista->Nit,
                'razon_social' => $compania->razon_social
            ];
        }

        $clientes = Clientes::select(
            'id',
            'Nit',
            'nivel_ga',
            'transacional_ga',
            'codigo_cuenta_contable_ga',
            'nombre_cuenta_contable_ga',
            'saldo_inicial_ga',
            'movimiento_debito_ga',
            'movimiento_credito_ga',
            'saldo_final_ga',
            'fechareporte_ga'
        )->where('nivel_ga', '<>', 'null')->get();

        //recorro las empresas y los clientes para agregar la razon social a cada cliente
        foreach ($empresas as $empresa) {
            foreach ($clientes as $cliente) {
                //si el nit del cliente coincide con la de la empresa agrego su razon social
                if ($cliente->Nit == $empresa['Nit']) {
                    $cliente->razon_social = $empresa['razon_social'];
                }
            }
        }

        if ($request->ajax()) {

            return DataTables::of($clientes)
                ->make(true);
        }
        return view('admin.clientes.siigoweb');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('CREAR_CLIENTES'), Response::HTTP_UNAUTHORIZED);
        $companias = Empresa::orderBy('razon_social')->select('NIT', 'razon_social', 'tipo')->get();
           // Asumiendo que obtienes las fechas existentes de la base de datos
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
        return view('admin.clientes.create', compact('companias','fechasExistentes'));
    }

     public function fechasExistentes()
    {
        return view('admin.clientesmovimiento.fechasexistentes');
    }
    public function fechasExistentesData(Request $request)
    {
        if ($request->ajax()) {
            $fechas = FechasExistentesIC::with(['empresa', 'creador'])->get();
            return DataTables::of($fechas)
                ->addColumn('NIT', function ($row) {
                    return $row->empresa->NIT ?? 'N/A';
                })
                ->addColumn('razon_social', function ($row) {
                    return $row->empresa->razon_social ?? 'N/A';
                })
                ->addColumn('usuario', function ($row) {
                    if ($row->creador) {
                        return $row->creador->nombres . ' ' . $row->creador->apellidos;
                    }
                    return 'N/A';
                })
                ->addColumn('year_reporte', function ($row) {
                    return optional($row->fecha_creacion)->format('Y');
                })
                ->addColumn('month_reporte', function ($row) {
                    $meses = [
                        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                    ];
                    $numeroMes = optional($row->fecha_creacion)->format('n'); // 1-12
                    return $meses[(int) $numeroMes] ?? 'N/A';
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : 'N/A';
                })
                ->make(true);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cliente' => 'required',
            'fechareporte' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->with('message2', 'Los campos marcados con * son requeridos.')->with('color', 'warning');
        }
        //obtener el archivo  
        $file = $request->file('file');
        //validar si se cargo el archivo
        if (!isset($file)) {
            return back()->with('message2', 'Por favor, cargue el archivo correspondiente para continuar.')->with('color', 'warning');
        }
        $tipoEmpresa = $request->input('tipo-empresa');
        $nit = $request->input('cliente');
        $fecha = $request->input('fechareporte');
        if($tipoEmpresa === 'CONTAPYME'){
            try {
                // Importar el archivo Excel
                Excel::import(new ContapymeBalanceImport($nit,$fecha), $file);
                return back()->with('message', 'Archivo importado exitosamente')->with('color', 'success');
            } catch (MissingHeadingsException $e) {
                return back()->with('message2', $e->getMessage())->with('color', 'danger');
            } catch (NitMismatchException $e) {
                return back()->with('message2', $e->getMessage())->with('color', 'danger');
            }
        }
        $job = new ExcelClientesBatchImport($file->getRealPath(), $request->cliente, $request->fechareporte);
        dispatch($job);
        Session::flash('file_upload_completed', true);
        // Redireccionar a la vista de importación con un mensaje de éxito
        return back();
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
                Clientes::where('Nit', $nit)
                    ->whereRaw('MONTH(fechareporte_ga) = MONTH(?) AND YEAR(fechareporte_ga) = YEAR(?)', [$fecha, $fecha])
                    ->delete();
            }elseif ($compania[0]->tipo === 'PYME') {
                Clientes::where('Nit', $nit)
                    ->whereRaw('MONTH(fechareporte) = MONTH(?) AND YEAR(fechareporte) = YEAR(?)', [$fecha, $fecha])
                    ->delete();
            }elseif ($compania[0]->tipo === 'CONTAPYME') {
                ContapymeCompleto::where('Nit', $nit)
                    ->whereRaw('MONTH(fechareporte) = MONTH(?) AND YEAR(fechareporte) = YEAR(?)', [$fecha, $fecha])
                    ->delete();
            }elseif ($compania[0]->tipo === 'LOGGRO') {
                loggro::where('Nit', $nit)
                    ->whereRaw('MONTH(fechareporte) = MONTH(?) AND YEAR(fechareporte) = YEAR(?)', [$fecha, $fecha])
                    ->delete();
            } else{
                InformesGenericos::where('Nit', $nit)
                    ->whereRaw('MONTH(fechareporte) = MONTH(?) AND YEAR(fechareporte) = YEAR(?)', [$fecha, $fecha])
                    ->delete();
            }
             // Validar formato
            if (!Carbon::hasFormat($fecha, 'Y-m-d')) {
                throw new \Exception('Formato de fecha no válido.');
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
                Log::error($e);
            return redirect()->back()->with('message2', 'Error al eliminar movimientos: ' . $e->getMessage())->with('color', 'danger');
        }
    }
    public function destroy($id)
    {
        //
    }
}
