<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CotizacionesExport;
use App\Http\Controllers\Controller;
use App\Models\Cotizacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Maatwebsite\Excel\Facades\Excel;

class InformeCRMController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('ACCEDER_INFORME_CRM'), Response::HTTP_UNAUTHORIZED );

        $fechaInicial = Cotizacion::select('fecha_envio')->orderBy('fecha_envio', 'ASC')->get()->first();
        $fechaFinal = Cotizacion::select('fecha_envio')->orderBy('fecha_envio', 'DESC')->get()->first();

        return view('admin.informes.informe-cotizaciones', compact('fechaInicial', 'fechaFinal'));
    }

    public function getCotizaciones(Request $request) {
        $cotizacion = Cotizacion::whereBetween('fecha_envio', [$request->fecha_inicio, $request->fecha_fin])->orderBy('fecha_envio')->get();
        return Excel::download(new CotizacionesExport($cotizacion), 'INFORME_COTIZACIONES_' . $request->fecha_inicio . '_' . $request->fecha_fin. '.xlsx');
    }

   

 
}
