<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AgendaEmpleado;
use App\Models\AgendaEmpresa;
use App\Models\EmpleadoCliente;
use App\Models\Cita;
use App\Models\Modalidad;
use App\Http\Requests\CreateCitaRequest;
use App\Http\Requests\UpdateCitaRequest;

use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class CitaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('ACCEDER_CITAS_CLIENTE'), Response::HTTP_UNAUTHORIZED);

        //trae la agenda para el empleado cliente ingresado
        $usuario_cliente = EmpleadoCliente::where('user_id', Auth::user()->id)->first();

        if (!$usuario_cliente) {
            session(['message' => 'No puedes ingresar a este sitio', 'color' => 'info', 'icon' => '<i class="fa-solid fa-circle-info"></i>']);
            return redirect()->back();
        }

        $citas = Cita::all();

        $events = [];
        $addedAgendaIds = [];
        $modalidades = Modalidad::orderBy('nombre')->get();

        $agenda_empresa = AgendaEmpresa::all();
        $empresaIds = collect(json_decode($usuario_cliente->empresas_secundarias))->push($usuario_cliente->empresa_id)->unique();

        foreach ($agenda_empresa as $agenda_empresa) {
            if ($empresaIds->contains($agenda_empresa->empresa_id)) {
                $agendas = AgendaEmpresa::where('empresa_id', $agenda_empresa->empresa_id)->get();

                foreach ($agendas as $agenda) {
                    if (in_array($agenda->agenda_id, $addedAgendaIds)) {
                        continue;
                    }

                    if ($agenda->estado == '1') {
                        $color = '#1e7b7f';
                        $estado = 'Cancelado por el cliente';
                    } else if ($agenda->estado == '2') {
                        $color = '#33d1d6';
                        $estado = 'Cancelado por la empresa';
                    } else if ($agenda->estado == '3') {
                        $color = '#fbbf6d';
                        $estado = 'Reprogramada por el cliente';
                    } else if ($agenda->estado == '4') {
                        $color = '#bf9352';
                        $estado = 'Reprogramada por la empresa';
                    } else if ($agenda->estado == '5') {
                        $color = '#585956';
                        $estado = 'Realizada';
                    } else if ($agenda->estado == '6') {
                        $color = '#7e807b';
                        $estado = 'Programada';
                    } else {
                        $color = '#0075F6';
                        $estado = 'Reservado';
                    }

                    $events[] = [
                        'id' => $agenda->agenda_id,
                        'start' => $agenda->agenda->fecha_disponibilidad . ' ' . $agenda->agenda->hora_inicio,
                        'end' => $agenda->agenda->fecha_disponibilidad . ' ' . $agenda->agenda->hora_fin,
                        'allDay' => false,
                        'empresa' => $agenda->empresa->razon_social,
                        'empleado' => $agenda->agenda->usuarios->nombres . ' ' . $agenda->agenda->usuarios->apellidos,
                        'estado' => $estado,
                        'backgroundColor' => $color,
                        'borderColor' => $color,
                        'textColor' => '#fff'
                    ];

                    $addedAgendaIds[] = $agenda->agenda_id;
                }
            }
        }

        return view('admin.citas.calendario', compact('usuario_cliente', 'modalidades', 'citas'), ['cita' => new Cita])->with('events', $events);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCitaRequest $request)
    {
        abort_if(Gate::denies('CREAR_CITAS_CLIENTE'), Response::HTTP_UNAUTHORIZED);

        Cita::create($request->all());
        return response()->json(['success' => 300]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if(Gate::denies('EDITAR_CITAS_CLIENTE'), Response::HTTP_UNAUTHORIZED);

        $cita = Cita::find($id);
        $cliente_logged = EmpleadoCliente::where('user_id', Auth::user()->id)->first();

        if ($cita->empleado_cliente_id == $cliente_logged->id) {
            return $cita;
        }

        return 0;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCitaRequest $request, $id)
    {
        $cita = Cita::find($id);
        $cita->update($request->all());

        return true;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort_if(Gate::denies('ELIMINAR_CITAS_CLIENTE'), Response::HTTP_UNAUTHORIZED);

        $cita = Cita::find($id);
        $cliente_logged = EmpleadoCliente::where('user_id', Auth::user()->id)->first();

        if ($cita->empleado_cliente_id == $cliente_logged->id) {
            $cita->update(['estados' => 3]);
            return true;
        }


        return 0;
    }
}
