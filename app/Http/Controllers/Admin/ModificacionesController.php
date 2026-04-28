<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ActionButtonTrait;
use App\Http\Requests\StoreModificacionRequest;
use App\Http\Requests\UpdateModificacionRequest;
use App\Models\Begranda;
use App\Models\Clientes;
use App\Models\ClientesMoviemientos;
use App\Models\ClientesMovimientos;
use App\Models\Compania;
use App\Models\Contapyme;
use App\Models\ContapymeCompleto;
use App\Models\Empresa;
use App\Models\InformesGenericos;
use App\Models\loggro;
use App\Models\Modificacion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;

class ModificacionesController extends Controller
{
    use ActionButtonTrait;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('ACCEDER_MODIFICACIONES'), Response::HTTP_UNAUTHORIZED);
        if($request->ajax())
        {
            $modificaciones = Modificacion::with('compania')->select('modificaciones.*');
            return DataTables::of($modificaciones)
                ->addColumn('actions', function ($modificaciones) {
                    return $this->getActionButtons('admin.modificaciones', 'MODIFICACIONES', $modificaciones->id);
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('admin.modificaciones.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('CREAR_MODIFICACIONES'), Response::HTTP_UNAUTHORIZED);
        $compania = Empresa::orderBy('razon_social')->pluck('razon_social', 'id');
        return view('admin.modificaciones.create', compact('compania'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreModificacionRequest $request)
    {
        $empresa=Empresa::find($request->compania_id);
        $nit=$empresa->NIT;
        $siigo = $empresa->tipo;
        $campo_modificado = $request->campo_modificado; // El campo que el usuario selecciona
        $valor_ajustado = $request->valor_ajustado;
        $saldo_original = null;
        if ($siigo == 'NUBE') {
            $saldo_original = $this->editMovimientosNube($request->movimiento, $nit, $valor_ajustado, $request->periodo, $campo_modificado);
        } else if ($siigo == 'PYME') {
            $saldo_original = $this->editMovimientosPyme($request->movimiento, $nit, $valor_ajustado, $request->periodo, $campo_modificado);
        } else if ($siigo == 'CONTAPYME') {
            $saldo_original = $this->editMovimientosContapyme($request->movimiento, $nit, $valor_ajustado, $request->periodo, $campo_modificado);
        } else {
            $saldo_original = $this->editMovimientosLoggro($request->movimiento, $nit, $valor_ajustado, $request->periodo, $campo_modificado,$siigo);
        }
        $request->merge([
            'saldo_original' => $saldo_original,
            'campo_modificado' => $campo_modificado, // Agregar el campo modificado
        ]);
         // Guardar la modificación
        $modificacion = Modificacion::create($request->all());
        // Redirigir a la vista de listado sin volver a crear una modificación
        return redirect()->route('admin.modificaciones.index')->with('success', 'Modificación creada exitosamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort_if(Gate::denies('VER_MODIFICACIONES'), Response::HTTP_UNAUTHORIZED);
        $modificacion = Modificacion::find($id);
        return view('admin.modificaciones.show', compact('modificacion'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if(Gate::denies('EDITAR_MODIFICACIONES'), Response::HTTP_UNAUTHORIZED);
        $modificacion = Modificacion::find($id);
        return view('admin.modificaciones.edit', compact('modificacion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateModificacionRequest $request, $id)
    {
        $modificacion = Modificacion::find($id);
        $empresa=Empresa::find($modificacion->compania_id);
        $nit=$empresa->NIT;
        $siigo = $empresa->tipo;
        $saldo_original=null;
        if($siigo=='NUBE'){
            $saldo_original = $this->editMovimientosNube($modificacion->movimiento,$nit,$request->valor_ajustado,$modificacion->periodo,$modificacion->campo_modificado);
        }else if($siigo=='PYME'){
            $saldo_original = $this->editMovimientosPyme($modificacion->movimiento,$nit,$request->valor_ajustado,$modificacion->periodo,$modificacion->campo_modificado);
        }else if($siigo=='CONTAPYME'){
            $saldo_original = $this->editMovimientosContapyme($modificacion->movimiento,$nit,$request->valor_ajustado,$modificacion->periodo,$modificacion->campo_modificado);
        }else{
            $saldo_original = $this->editMovimientosLoggro($modificacion->movimiento,$nit,$request->valor_ajustado,$modificacion->periodo,$modificacion->campo_modificado,$siigo);
        }
        $modificacion->update($request->all());
        return redirect()->route('admin.modificaciones.index')->with('success', 'Modificación actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort_if(Gate::denies('ELIMINAR_MODIFICACIONES'), Response::HTTP_UNAUTHORIZED);
        $modificacion = Modificacion::find($id);
        if (!$modificacion) {
            return back()->with('error', 'Modificación no encontrada');
        }
        $empresa = Empresa::find($modificacion->compania_id);
        if (!$empresa) {
            return back()->with('error', 'Empresa no encontrada');
        }
        $nit = $empresa->NIT;
        $siigo = $empresa->tipo;
        // Determinar la tabla correspondiente y revertir el saldo
        if ($siigo === 'NUBE' || $siigo === 'PYME') {
            $this->revertirCambios($modificacion, $nit, $siigo);
        } elseif ($siigo == 'CONTAPYME') {
            $this->revertMovimientosContapyme($modificacion->movimiento, $nit, $modificacion->saldo_original,$modificacion->periodo,$modificacion->campo_modificado);
        } else {
            $this->revertMovimientosLoggro($modificacion->movimiento, $nit, $modificacion->saldo_original,$modificacion->periodo,$modificacion->campo_modificado,$siigo);
        }
        // Eliminar el registro de la tabla modificaciones
        $modificacion->delete();

         return response()->json(['message' => 'Modificación eliminada y saldo revertido correctamente']);
    }

    public function getMovimientos(Request $request)
    {
       // Obtener la compañía según el ID proporcionado
        $compania = Empresa::find($request->id);

        // Verificar el tipo de compañía
        if ($compania->tipo == 'PYME') {
            $resultados = ClientesMovimientos::select('cuenta','saldoinicial','creditos','debitos','saldo_mov')
                ->where('Nit', $compania->NIT)
                ->whereRaw('YEAR(clientesmovimientos.fecha_reporte) = YEAR(?)', [$request->periodo])
                ->whereRaw('MONTH(clientesmovimientos.fecha_reporte) = MONTH(?)', [$request->periodo])
                ->whereNotNull('cuenta')
                ->groupBy('cuenta');

            $movimientos = Clientes::select('cuenta','saldo_anterior as saldoinicial','debitos','creditos','nuevo_saldo as saldo_mov')
                ->where('Nit', $compania->NIT)
                ->whereRaw('YEAR(fechareporte) = YEAR(?)', [$request->periodo])
                ->whereRaw('MONTH(fechareporte) = MONTH(?)', [$request->periodo])
                ->whereNotNull('cuenta')
                ->groupBy('cuenta')
                ->union($resultados) // Combina los resultados de la primera consulta
                ->distinct() // Elimina duplicados
                ->get();
        } elseif ($compania->tipo == 'NUBE') {
            $movimientosSW = ClientesMovimientos::select(DB::raw('
                TRIM(codigo_contable_sw) as cuenta,
                saldo_inicial_sw as saldoinicial,
                debito_sw as debitos,
                credito_sw as creditos,
                saldo_movimiento_sw as saldo_mov'))
            ->where('Nit', $compania->NIT)
            ->whereRaw('YEAR(clientesmovimientos.fecha_reporte_sw) = YEAR(?)', [$request->periodo])
            ->whereRaw('MONTH(clientesmovimientos.fecha_reporte_sw) = MONTH(?)', [$request->periodo])
            ->whereNotNull('codigo_contable_sw');

            $movimientosGA = Clientes::select(DB::raw('
                TRIM(codigo_cuenta_contable_ga) as cuenta,
                saldo_inicial_ga as saldoinicial,
                movimiento_debito_ga as debitos, 
                movimiento_credito_ga as creditos,
                saldo_final_ga as saldo_mov')) 
            ->where('Nit', $compania->NIT)
            ->whereRaw('YEAR(fechareporte_ga) = YEAR(?)', [$request->periodo])
            ->whereRaw('MONTH(fechareporte_ga) = MONTH(?)', [$request->periodo])
            ->whereNotNull('codigo_cuenta_contable_ga');

            // Combina los resultados de ambas consultas y elimina duplicados
            $movimientos = $movimientosGA
                ->union($movimientosSW) // Combina las dos consultas
                ->distinct() // Elimina duplicados
                ->get();
        }elseif ($compania->tipo == 'CONTAPYME') {
            $movimientos = ContapymeCompleto::select(DB::raw('TRIM(cuenta) as cuenta,
                saldo_anterior as saldoinicial,
                debitos,
                creditos,
                nuevo_saldo as saldo_mov'
            ))
            ->where('Nit', $compania->NIT)
            ->whereRaw('YEAR(fechareporte) = YEAR(?)', [$request->periodo])
            ->whereRaw('MONTH(fechareporte) = MONTH(?)', [$request->periodo])
            ->whereNotNull('cuenta')
            ->groupBy('cuenta')
            ->get();
        }elseif ($compania->tipo == 'LOGGRO') {
            $movimientos = loggro::select(DB::raw('TRIM(cuenta) as cuenta,
                saldo_anterior as saldoinicial,
                debitos,
                creditos,
                saldo_final as saldo_mov'
            ))
            ->where('Nit', $compania->NIT)
            ->whereRaw('YEAR(fechareporte) = YEAR(?)', [$request->periodo])
            ->whereRaw('MONTH(fechareporte) = MONTH(?)', [$request->periodo])
            ->whereNotNull('cuenta')
            ->groupBy('cuenta')
            ->get();
        }elseif ($compania->tipo == 'BEGRANDA'){
            $movimientos = Begranda::select(DB::raw('TRIM(cuenta) as cuenta,
                saldo_anterior as saldoinicial,
                debitos,
                creditos,
                saldo_final as saldo_mov'
            ))
            ->where('Nit', $compania->NIT)
            ->whereRaw('YEAR(fechareporte) = YEAR(?)', [$request->periodo])
            ->whereRaw('MONTH(fechareporte) = MONTH(?)', [$request->periodo])
            ->whereNotNull('cuenta')
            ->groupBy('cuenta')
            ->get();
        }else{
            $movimientos = InformesGenericos::select(DB::raw('TRIM(cuenta) as cuenta,
                saldo_anterior as saldoinicial,
                debitos,
                creditos,
                saldo_final as saldo_mov'
            ))
            ->where('Nit', $compania->NIT)
            ->whereRaw('YEAR(fechareporte) = YEAR(?)', [$request->periodo])
            ->whereRaw('MONTH(fechareporte) = MONTH(?)', [$request->periodo])
            ->whereNotNull('cuenta')
            ->groupBy('cuenta')
            ->get();
        }

        return $movimientos->map(function ($movimiento) {
            return [
                'cuenta' => $movimiento->cuenta,
                'saldoinicial' => $movimiento->saldoinicial ?? 0,
                'debitos' => $movimiento->debitos ?? 0,
                'creditos' => $movimiento->creditos ?? 0,
                'saldo_mov' => $movimiento->saldo_mov ?? 0,
            ];
        });
    }

    public function editMovimientosNube($movimiento, $nit, $valor_ajustado, $fecha, $campo_modificado){
        // Variables para seguimiento de ajustes
        $movimientos = null;
        $movimientoss = null;
        $saldo_original = null;
        $fecha = Carbon::parse($fecha);
        $anio = $fecha->year;
        $mes = $fecha->month;
        // Mapeo de los nombres de los campos del frontend a los nombres de columnas en la base de datos
        $mapaCamposClientesMovimientos = [
            'saldoinicial' => 'saldo_inicial_sw',
            'debitos' => 'debito_sw',
            'creditos' => 'credito_sw',
            'saldo_mov' => 'saldo_movimiento_sw',
        ];

        $mapaCamposClientes = [
            'saldoinicial' => 'saldo_inicial_ga', // Ejemplo de mapeo
            'debitos' => 'movimiento_debito_ga', // Ejemplo de mapeo
            'creditos' => 'movimiento_credito_ga', // Ejemplo de mapeo
            'saldo_mov' => 'saldo_final_ga', // Ejemplo de mapeo
        ];
        // Intentar en la primera tabla (ClientesMovimientos)
        $movimientos = ClientesMovimientos::where('Nit', $nit)
        ->where('codigo_contable_sw', $movimiento)
        ->whereYear('fecha_reporte_sw', $anio)  // Filtra por año
        ->whereMonth('fecha_reporte_sw', $mes)  // Filtra por mes
        ->first();
        
        
        if ($movimientos) {
            $campoBD = $mapaCamposClientesMovimientos[$campo_modificado] ?? null;

            if ($campoBD) {
                // Obtener el valor original dependiendo del campo modificado
                $saldo_original = $movimientos->$campoBD;
                // Actualizar el valor ajustado
                $movimientos->$campoBD = (string) $valor_ajustado;
                $movimientos->save();
            }
        }
        // Intentar en la segunda tabla (Clientes)
        $movimientoss = Clientes::where('Nit', $nit)
            ->where('codigo_cuenta_contable_ga', $movimiento)
            ->whereYear('fechareporte_ga', $anio)  // Filtra por año
            ->whereMonth('fechareporte_ga', $mes)  // Filtra por mes
        ->first();
        
        if ($movimientoss) {
            // Obtener el nombre de la columna correspondiente en la tabla Clientes
            $campoBD = $mapaCamposClientes[$campo_modificado] ?? null;
            
            if ($campoBD) {
                // Obtener el valor original dependiendo del campo modificado
                $saldo_original = $movimientoss->$campoBD;
                // Actualizar el valor ajustado
                $movimientoss->$campoBD = (string) $valor_ajustado;
                $movimientoss->save();
            }
        }
        return $saldo_original;
    }

    public function editMovimientosPyme($movimiento, $nit, $valor_ajustado, $fecha, $campo_modificado){
        // Variables para seguimiento de ajustes
        $movimientos = null;
        $movimientoss = null;
        $saldo_original = null;
        $fecha = Carbon::parse($fecha);
        $anio = $fecha->year;
        $mes = $fecha->month;
         // Mapeo de los nombres de los campos del frontend a los nombres de columnas en la base de datos
         $mapaCamposClientesMovimientos = [
            'saldoinicial' => 'saldoinicial',
            'debitos' => 'debitos',
            'creditos' => 'creditos',
            'saldo_mov' => 'saldo_mov',
        ];
        $mapaCamposClientes = [
            'saldoinicial' => 'saldo_anterior', // Ejemplo de mapeo
            'debitos' => 'debitos', // Ejemplo de mapeo
            'creditos' => 'creditos', // Ejemplo de mapeo
            'saldo_mov' => 'nuevo_saldo', // Ejemplo de mapeo
        ];
        // Intentar en la primera tabla (ClientesMovimientos)
        $movimientos = ClientesMovimientos::where('Nit', $nit)
            ->where('cuenta', $movimiento)
            ->whereYear('fecha_reporte', $anio)  // Filtra por año
            ->whereMonth('fecha_reporte', $mes)  // Filtra por mes
            ->first();

        if ($movimientos) {
            $campoBD = $mapaCamposClientesMovimientos[$campo_modificado] ?? null;
            if ($campoBD) {
                // Obtener el valor original dependiendo del campo modificado
                $saldo_original = $movimientos->$campoBD;
                // Actualizar el valor ajustado
                $movimientos->$campoBD = (string) $valor_ajustado;
                $movimientos->save();
            }
        }
        // Intentar en la segunda tabla (Clientes)
        $movimientoss = Clientes::where('Nit', $nit)
            ->where('cuenta', $movimiento)
            ->whereYear('fechareporte', $anio)  // Filtra por año
            ->whereMonth('fechareporte', $mes)  // Filtra por mes
        ->first();
        if ($movimientoss) {
            // Obtener el nombre de la columna correspondiente en la tabla Clientes
            $campoBD = $mapaCamposClientes[$campo_modificado] ?? null;
            if ($campoBD) {
                // Obtener el valor original dependiendo del campo modificado
                $saldo_original = $movimientoss->$campoBD;
                // Actualizar el valor ajustado
                $movimientoss->$campoBD = (string) $valor_ajustado;
                $movimientoss->save();
            }

        }
      return (string) $saldo_original;
    }

    public function editMovimientosContapyme($movimiento,$nit,$valor_ajustado,$fecha,$campo_modificado){
        // Variables para seguimiento de ajustes
        $movimientoscp = null;
        $saldo_original = null;
        $fecha = Carbon::parse($fecha);
        $anio = $fecha->year;
        $mes = $fecha->month;
        $mapaCamposContapyme = [
            'saldoinicial' => 'saldo_anterior', // Ejemplo de mapeo
            'debitos' => 'debitos', // Ejemplo de mapeo
            'creditos' => 'creditos', // Ejemplo de mapeo
            'saldo_mov' => 'nuevo_saldo', // Ejemplo de mapeo
        ];
        $movimientoscp = ContapymeCompleto::where('Nit', $nit)
        ->where('cuenta','=', $movimiento)
        ->whereYear('fechareporte', $anio)  // Filtra por año
        ->whereMonth('fechareporte', $mes)  // Filtra por mes
        ->first();
        

        if ($movimientoscp) {
            $campoBD = $mapaCamposContapyme[$campo_modificado] ?? null;
            // Obtener el valor original dependiendo del campo modificado
            if ($campoBD) {
                // Obtener el valor original dependiendo del campo modificado
                $saldo_original = $movimientoscp->$campoBD;
                // Actualizar el valor ajustado
                $movimientoscp->$campoBD = (string) $valor_ajustado;
                $movimientoscp->save();
            }

        }
        return (string) $saldo_original;
    }
    public function editMovimientosLoggro($movimiento,$nit,$valor_ajustado,$fecha,$campo_modificado,$siigo){
        // Variables para seguimiento de ajustes
        $movimientoslg = null;
        $saldo_original = null;
        $fecha = Carbon::parse($fecha);
        $anio = $fecha->year;
        $mes = $fecha->month;
        $mapaCamposLoggro = [
            'saldoinicial' => 'saldo_anterior', // Ejemplo de mapeo
            'debitos' => 'debitos', // Ejemplo de mapeo
            'creditos' => 'creditos', // Ejemplo de mapeo
            'saldo_mov' => 'saldo_final', // Ejemplo de mapeo
        ];
         // 🔹 Determinar modelo según SIIGO
        if ($siigo === 'LOGGRO') {
            $modelo = Loggro::class;
        } elseif ($siigo === 'BEGRANDA') {
            $modelo = Begranda::class;
        } else {
            $modelo = InformesGenericos::class;
        }
        $movimientoslg = $modelo::where('Nit', $nit)
        ->where('cuenta','=', $movimiento)
        ->whereYear('fechareporte', $anio)  // Filtra por año
        ->whereMonth('fechareporte', $mes)  // Filtra por mes
        ->first();
      

        if ($movimientoslg) {
            $campoBD = $mapaCamposLoggro[$campo_modificado] ?? null;
            // Obtener el valor original dependiendo del campo modificado
            if ($campoBD) {
                // Obtener el valor original dependiendo del campo modificado
                $saldo_original = $movimientoslg->$campoBD;
                
                // Actualizar el valor ajustado
                $movimientoslg->$campoBD = (string) $valor_ajustado;
                $movimientoslg->save();
            }

        }
      return (string) $saldo_original;
    }

    private function revertirCambios($modificacion, $nit, $siigo)
    {
        $movimiento = $modificacion->movimiento;
        $saldo_original = $modificacion->saldo_original;
        $fecha = $modificacion->periodo;
        // Si la variable $fecha es un string de fecha
        $fecha = Carbon::parse($fecha);
        // Obtener el año y el mes
        $anio = $fecha->year;
        $mes = $fecha->month;
        
        // Mapeo de los nombres de los campos del frontend a los nombres de columnas en la base de datos para NUBE
        $mapaCamposNubeClientesMovimientos = [
            'saldoinicial' => 'saldo_inicial_sw',
            'debitos' => 'debito_sw',
            'creditos' => 'credito_sw',
            'saldo_mov' => 'saldo_movimiento_sw',
        ];
        $mapaCamposNubeClientes = [
            'saldoinicial' => 'saldo_inicial_ga',
            'debitos' => 'movimiento_debito_ga',
            'creditos' => 'movimiento_credito_ga',
            'saldo_mov' => 'saldo_final_ga',
        ];

        // Mapeo de los nombres de los campos del frontend a los nombres de columnas en la base de datos para PYME
        $mapaCamposPymeClientesMovimientos = [
            'saldoinicial' => 'saldoinicial',
            'debitos' => 'debitos',
            'creditos' => 'creditos',
            'saldo_mov' => 'saldo_mov',
        ];
        $mapaCamposPymeClientes = [
            'saldoinicial' => 'saldo_anterior',
            'debitos' => 'debitos',
            'creditos' => 'creditos',
            'saldo_mov' => 'nuevo_saldo',
        ];

        // Revertir en ClientesMovimientos
        if ($siigo === 'NUBE' || $siigo === 'PYME') {
            // Determinar el campo de cuenta y la fecha de reporte según el tipo de sistema (NUBE o PYME)
            $campoCuenta = $siigo === 'NUBE' ? 'codigo_contable_sw' : 'cuenta';
            $campofechareportee = $siigo === 'NUBE' ? 'fecha_reporte_sw' : 'fecha_reporte';
            
            // Mapear el campo modificado según el tipo de sistema (NUBE o PYME)
            $campoModificado = ($siigo === 'NUBE') 
                ? $mapaCamposNubeClientesMovimientos[$modificacion->campo_modificado] 
                : $mapaCamposPymeClientesMovimientos[$modificacion->campo_modificado];

            // Buscar el movimiento correspondiente en ClientesMovimientos
            $movimientos = ClientesMovimientos::where('Nit', $nit)
                ->where($campoCuenta, $movimiento)
                ->whereYear($campofechareportee, $anio)  // Filtra por año
                ->whereMonth($campofechareportee, $mes)  // Filtra por mes
                ->first();

            if ($movimientos) {
                // Revertir el valor al original
                $movimientos->$campoModificado = $saldo_original;  
                $movimientos->save();
                return;
            }
        }

        // Revertir en Clientes
        $campoCuentaClientes = $siigo === 'NUBE' ? 'codigo_cuenta_contable_ga' : 'cuenta';
        $campofechareporte = $siigo === 'NUBE' ? 'fechareporte_ga' : 'fechareporte';
        
        // Mapear el campo modificado para la tabla Clientes según el tipo de sistema (NUBE o PYME)
        $campoModificadoClientes = ($siigo === 'NUBE') 
            ? $mapaCamposNubeClientes[$modificacion->campo_modificado] 
            : $mapaCamposPymeClientes[$modificacion->campo_modificado];

        // Buscar el movimiento correspondiente en Clientes
        $movimientos = Clientes::where('Nit', $nit)
            ->where($campoCuentaClientes, $movimiento)
            ->whereYear($campofechareporte, $anio)  // Filtra por año
            ->whereMonth($campofechareporte, $mes)  // Filtra por mes
            ->first();

        if ($movimientos) {
            // Revertir el valor al original
            $movimientos->$campoModificadoClientes = $saldo_original;  
            $movimientos->save();
        }
    }


    public function revertMovimientosContapyme($movimiento, $nit, $saldo_original, $fechao,$campo_modificado)
    {
        // Si la variable $fecha es un string de fecha
        $fecha = Carbon::parse($fechao);
        // Obtener el año y el mes
        $anio = $fecha->year;
        $mes = $fecha->month;
        
        // Definir el mapeo de los campos
        $mapaCamposContapyme = [
            'saldoinicial' => 'saldo_anterior',
            'debitos' => 'debitos',
            'creditos' => 'creditos',
            'saldo_mov' => 'nuevo_saldo',
        ];
        
        // Mapeo del campo modificado
        $campoModificado = $mapaCamposContapyme[$campo_modificado];

        // Buscar el movimiento correspondiente en ContapymeCompleto
        $movimientos = ContapymeCompleto::where('Nit', $nit)
            ->where('cuenta', $movimiento)
            ->whereYear('fechareporte', $anio)  // Filtra por año
            ->whereMonth('fechareporte', $mes)  // Filtra por mes
            ->first();

        if ($movimientos) {
            // Revertir el valor al original
            $movimientos->$campoModificado = $saldo_original;
            $movimientos->save();
        }
    }


    public function revertMovimientosLoggro($movimiento, $nit, $saldo_original,$fechao,$campo_modificado,$siigo)
    {
        // Si la variable $fecha es un string de fecha
        $fecha = Carbon::parse($fechao);
        // Obtener el año y el mes
        $anio = $fecha->year;
        $mes = $fecha->month;
        $mapaCamposLoggro = [
            'saldoinicial' => 'saldo_anterior', // Ejemplo de mapeo
            'debitos' => 'debitos', // Ejemplo de mapeo
            'creditos' => 'creditos', // Ejemplo de mapeo
            'saldo_mov' => 'saldo_final', // Ejemplo de mapeo
        ];
        // Mapeo del campo modificado
        $campoModificado = $mapaCamposLoggro[$campo_modificado];

         // 🔹 Determinar modelo según SIIGO
        if ($siigo === 'LOGGRO') {
            $modelo = Loggro::class;
        } elseif ($siigo === 'BEGRANDA') {
            $modelo = Begranda::class;
        } else {
            $modelo = InformesGenericos::class;
        }

        $movimientos = $modelo::where('Nit', $nit)
            ->where('cuenta', $movimiento)
            ->whereYear('fechareporte', $anio)  // Filtra por año
            ->whereMonth('fechareporte', $mes)  // Filtra por mes
            ->first();

        if ($movimientos) {
            $movimientos->$campoModificado = $saldo_original;
            $movimientos->save();
        }
    }
}
