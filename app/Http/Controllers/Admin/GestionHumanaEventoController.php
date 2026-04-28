<?php

namespace App\Http\Controllers\Admin;

use App\Exports\GestionHumanaEventosExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventoGestionHumanaRequest;
use App\Http\Requests\UpdateEventoGestionHumanaRequest;
use App\Models\Concepto;
use App\Models\GestionHumana;
use App\Models\GestionHumanaEvento;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use PhpParser\Node\Expr\FuncCall;
use Yajra\DataTables\Facades\DataTables;

class GestionHumanaEventoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $gestion_humana_id = $request->gestion_humana_id;
            $eventos =  GestionHumanaEvento::with('concepto', 'gestionHumana')->select('gestion_humana_eventos.*')->where('gestion_humana_id', $gestion_humana_id);
            return DataTables::of($eventos)
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        abort_if(Gate::denies('CREAR_EVENTO_GESTIÓN_HUMANA'), Response::HTTP_UNAUTHORIZED);

        $gestion_humana = GestionHumana::findOrFail($id);
        $conceptos = Concepto::orderBy('nombre')->get();
        return view('admin.gestion-humana.eventos.create', compact('conceptos', 'gestion_humana'), ['evento' => new GestionHumanaEvento()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEventoGestionHumanaRequest $request)
    {
        $gestion_humana = GestionHumana::findOrFail($request->gestion_humana_id);
        $retiro_arl = $request->file('certificado_arl');
        $retiro_caja = $request->file('certificado_caja');
        $liquidacion = $request->file('liquida');

        $filename = null;

        if ($retiro_arl) { // Verifica si se ha subido un archivo
            $filename = 'certificado-retiro-arl-' . $gestion_humana->cedula . '.' . $retiro_arl->getClientOriginalExtension();

            // Verifica si el archivo ya existe y lo elimina
            if (file_exists(storage_path('app/public/certificado-arl-empleados/' . $filename))) {
                unlink(storage_path('app/public/certificado-arl-empleados/' . $filename));
            }

            // Mueve el archivo subido a la ubicación deseada
            $retiro_arl->move(storage_path('app/public/certificado-arl-empleados/'), $filename);
        }

        $filename_caja = null;

        if ($retiro_caja) { // Verifica si se ha subido un archivo
            $filename_caja = 'certificado-retiro-caja-' . $gestion_humana->cedula . '.' . $retiro_caja->getClientOriginalExtension();

            // Verifica si el archivo ya existe y lo elimina
            if (file_exists(storage_path('app/public/certificado-ccf-empleados/' . $filename_caja))) {
                unlink(storage_path('app/public/certificado-ccf-empleados/' . $filename_caja));
            }

            // Mueve el archivo subido a la ubicación deseada
            $retiro_caja->move(storage_path('app/public/certificado-ccf-empleados/'), $filename_caja);
        }

        $filename_liquidacion = null;

        if ($liquidacion) { // Verifica si se ha subido un archivo
            $filename_liquidacion = 'liquidación-' . $gestion_humana->cedula . '.' . $liquidacion->getClientOriginalExtension();

            // Verifica si el archivo ya existe y lo elimina
            if (file_exists(storage_path('app/public/liquidaciones-empleados/' . $filename_liquidacion))) {
                unlink(storage_path('app/public/liquidaciones-empleados/' . $filename_liquidacion));
            }

            // Mueve el archivo subido a la ubicación deseada
            $liquidacion->move(storage_path('app/public/liquidaciones-empleados/'), $filename_liquidacion);
        }

        GestionHumanaEvento::create($request->all());
        $gestion_humana->update([
            'certificado_retiro_arl' => $filename,
            'certificado_retiro_caja' => $filename_caja,
            'liquidacion' => $filename_liquidacion,
        ]);

        return redirect()->route('admin.gestion-humana.index')->with('message', 'Novedad creada exitosamente')->with('color', 'success');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if(Gate::denies('EDITAR_EVENTO_GESTIÓN_HUMANA'), Response::HTTP_UNAUTHORIZED);

        $evento = GestionHumanaEvento::findOrFail($id);
        $gestion_humana = GestionHumana::findOrFail($evento->gestion_humana_id);
        $conceptos = Concepto::orderBy('nombre')->get();
        return view('admin.gestion-humana.eventos.edit', compact('conceptos', 'gestion_humana', 'evento'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEventoGestionHumanaRequest $request, $id)
    {
        $evento = GestionHumanaEvento::findOrFail($id);

        $gestion_humana = GestionHumana::findOrFail($evento->gestion_humana_id);
        $retiro_arl = $request->file('certificado_arl');
        $retiro_caja = $request->file('certificado_caja');
        $liquidacion = $request->file('liquida');

        if ($retiro_arl) { // Verifica si se ha subido un archivo
            $filename = 'certificado-retiro-arl-' . $evento->gestionHumana->cedula . '.' . $retiro_arl->getClientOriginalExtension();

            // Verifica si el archivo ya existe y lo elimina
            if (file_exists(storage_path('app/public/certificado-arl-empleados/' . $filename))) {
                unlink(storage_path('app/public/certificado-arl-empleados/' . $filename));
            }

            // Mueve el archivo subido a la ubicación deseada
            $retiro_arl->move(storage_path('app/public/certificado-arl-empleados/'), $filename);
        } else {
            $filename = $evento->gestionHumana->certificado_retiro_arl; // Mantiene el nombre del archivo existente si no se sube uno nuevo
        }

        if ($retiro_caja) { // Verifica si se ha subido un archivo
            $filename_caja = 'certificado-retiro-caja-' . $evento->gestionHumana->cedula . '.' . $retiro_caja->getClientOriginalExtension();

            // Verifica si el archivo ya existe y lo elimina
            if (file_exists(storage_path('app/public/certificado-ccf-empleados/' . $filename_caja))) {
                unlink(storage_path('app/public/certificado-ccf-empleados/' . $filename_caja));
            }

            // Mueve el archivo subido a la ubicación deseada
            $retiro_caja->move(storage_path('app/public/certificado-ccf-empleados/'), $filename_caja);
        } else {
            $filename_caja = $evento->gestionHumana->certificado_retiro_caja; // Mantiene el nombre del archivo existente si no se sube uno nuevo
        }

        if ($liquidacion) { // Verifica si se ha subido un archivo
            $filename_liquidacion = 'liquidación-' . $evento->gestionHumana->cedula . '.' . $liquidacion->getClientOriginalExtension();

            // Verifica si el archivo ya existe y lo elimina
            if (file_exists(storage_path('app/public/liquidaciones-empleados/' . $filename_liquidacion))) {
                unlink(storage_path('app/public/liquidaciones-empleados/' . $filename_liquidacion));
            }

            // Mueve el archivo subido a la ubicación deseada
            $liquidacion->move(storage_path('app/public/liquidaciones-empleados/'), $filename_liquidacion);
        } else {
            $filename_liquidacion = $evento->gestionHumana->liquidacion; // Mantiene el nombre del archivo existente si no se sube uno nuevo
        }

        $gestion_humana->update([
            'certificado_retiro_arl' => $filename,
            'certificado_retiro_caja' => $filename_caja,
            'liquidacion' => $filename_liquidacion,
        ]);

        $evento->update($request->all());
      

        return redirect()->route('admin.gestion-humana.show', $request->gestion_humana_id)->with('message', 'Novedad actualizada exitosamente')->with('color', 'success');
    }

    public function exportarEventos(Request $request)
    {
        // abort_if(Gate::denies('EXPORTAR_EVENTOS_GESTIÓN_HUMANA'), Response::HTTP_UNAUTHORIZED);

        $fecha_inicio = $request->input('fecha_inicio');
        $fecha_fin = $request->input('fecha_fin');

        $eventos = GestionHumanaEvento::with('concepto', 'gestionHumana')
            ->whereBetween('fecha_inicio', [$fecha_inicio, $fecha_fin])
            ->orderBy('fecha_inicio', 'asc')
            ->get();

        $fileName = 'novedades_gestion_humana_' . now()->format('Y_m_d_H_i_s') . '.xlsx';

        return Excel::download(new GestionHumanaEventosExport($eventos), $fileName);
    }
}
