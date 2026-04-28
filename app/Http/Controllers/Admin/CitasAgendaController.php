<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgendaEmpleado;
use App\Models\AgendaEmpresa;
use App\Models\Cita;
use App\Models\Empresa;
use App\Models\Modalidad;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CitasAgendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {

        $cantEvents =  'vacio';
        $nombreempresa = 'vacio';
        $nombreresponsable = 'vacio';

        if (Auth::user()->role_id == 1) {
            $agenda =  AgendaEmpleado::select('*')->get();
        } else {
            $agenda = AgendaEmpleado::select('*')->where('user_id', $id)->get();
        }

        $citas = Cita::all();
        $modalidades = Modalidad::orderBy('nombre')->get();

        $events = [];

        foreach ($agenda as $agenda) {

            $empresa = AgendaEmpresa::where('agenda_id', $agenda->id)->first();
            if ($empresa != null) {
                if ($empresa->estado == '1') {
                    $color = '#1e7b7f';
                    $estado = 'Cancelado por el cliente';
                } else if ($empresa->estado == '2') {
                    $color = '#33d1d6';
                    $estado = 'Cancelado por la empresa';
                } else if ($empresa->estado == '3') {
                    $color = '#fbbf6d';
                    $estado = 'Reprogramada por el cliente';
                } else if ($empresa->estado == '4') {
                    $color = '#bf9352';
                    $estado = 'Reprogramada por la empresa';
                } else if ($empresa->estado == '5') {
                    $color = '#585956';
                    $estado = 'Realizada';
                } else if ($empresa->estado == '6') {
                    $color = '#7e807b';
                    $estado = 'Programada';
                } else {
                    $color = '#0075F6';
                    $estado = 'Reservado';
                }
                $events[] =
                    [
                        'id' => $agenda->id,
                        'start' => $agenda->fecha_disponibilidad . ' ' . $agenda->hora_inicio,
                        'end' => $agenda->fecha_disponibilidad . ' ' . $agenda->hora_fin,
                        'empresa' => $empresa->empresa->razon_social,
                        'empleado' => $agenda->usuarios->nombres . ' ' . $agenda->usuarios->apellidos,
                        'estado' => $estado,
                        'allDay' => false,
                        'backgroundColor' => $color,
                        'borderColor' => $color,
                        'textColor' => '#fff'
                    ];
            }
        }

        return view('admin.agenda.calendario', compact('modalidades', 'citas', 'events', 'cantEvents', 'nombreempresa', 'nombreresponsable'), ['cita' => new Cita]);
    }

    public function filtroAgenda(Request $request)
    {
        $empresa_id = $request->input('empresa_id');
        $user_id = $request->input('user_id');
        $empresa_id = explode('-', $empresa_id)[0];
        $user_id = explode('-', $user_id)[0];
        $nombreempresa = '';
        $nombreresponsable = '';
        $modalidades = Modalidad::orderBy('nombre')->get();

        if ($user_id && $empresa_id) {
            $agenda_empresa = AgendaEmpresa::with('agenda')->whereHas('agenda', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })->where('empresa_id', $empresa_id)->get();
        } else if ($user_id) {
            $agenda_empresa = AgendaEmpresa::with('agenda')->whereHas('agenda', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })->get();
        } else if ($empresa_id) {
            $agenda_empresa = AgendaEmpresa::with('agenda')->where('empresa_id', $empresa_id)->get();
        } else {
            $agenda_empresa =   $agenda_empresa = AgendaEmpresa::all();
        }

        if ($empresa_id) {
            $nombreempresa = Empresa::select('razon_social')->where('id', $empresa_id)->first();
        }

        if ($user_id) {
            $nombreresponsable = User::withTrashed()->select('nombres', 'apellidos')->where('id', $user_id)->first();
        }

        $cantEvents =  count($agenda_empresa);

        $citas = Cita::all();

        $events = [];

        foreach ($agenda_empresa as $agenda) {
            if ($agenda->estado == '1') {
                $color = '#1e7b7f';
            } else if ($agenda->estado == '2') {
                $color = '#33d1d6';
            } else if ($agenda->estado == '3') {
                $color = '#fbbf6d';
            } else if ($agenda->estado == '4') {
                $color = '#bf9352';
            } else if ($agenda->estado == '5') {
                $color = '#585956';
            } else if ($agenda->estado == '6') {
                $color = '#7e807b';
            } else {
                $color = '#0075F6';
            }
            $events[] =
                [
                    'id' => $agenda->agenda->id,
                    'start' => $agenda->agenda->fecha_disponibilidad . ' ' . $agenda->agenda->hora_inicio,
                    'end' => $agenda->agenda->fecha_disponibilidad . ' ' . $agenda->agenda->hora_fin,
                    'empresa' => $agenda->empresa->razon_social,
                    'empleado' => $agenda->agenda->usuarios->nombres . ' ' . $agenda->agenda->usuarios->apellidos,
                    'allDay' => false,
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'textColor' => '#fff'
                ];
        }

        //enviar un mensaje con la cantidad de eventos encontrados
        return view('admin.agenda.calendario', compact('modalidades', 'citas', 'events', 'cantEvents', 'nombreempresa', 'nombreresponsable'), ['cita' => new Cita]);
    }
}
