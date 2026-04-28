<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\FestivosTrait;
use App\Http\Requests\CreateAgendaRequest;
use App\Http\Requests\UpdateAgendaRequest;
use App\Models\AgendaEmpleado;
use App\Models\EmpleadoCliente;
use App\Models\Empresa;
use Carbon\Carbon;
use App\Models\Cita;
use App\Models\Modalidad;
use Barryvdh\Debugbar\Facades\Debugbar;
use Barryvdh\Debugbar\Twig\Extension\Debug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Traits\ActionButtonTrait;
use App\Models\AgendaEmpresa;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\eventCreatedNotification;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AgendaController extends Controller
{
    use ActionButtonTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        abort_if(Gate::denies('GESTIONAR_AGENDA'), Response::HTTP_UNAUTHORIZED);

        if ($request->ajax()) {
            if (Auth::user()->role_id == 1) {
                $agendas =  AgendaEmpresa::with('empresa', 'agenda')->whereHas('empresa', function ($query) {
                    $query->where('estado', 1);
                })->select('agenda_empresas.*');
            } else {
                $agendas =  AgendaEmpresa::with('empresa', 'agenda')
                    ->whereHas('agenda', function ($query) {
                        $query->where('user_id', Auth::user()->id);
                    })->whereHas('empresa', function ($query) {
                        $query->where('estado', 1);
                    })
                    ->select('agenda_empresas.*');
            }

            return DataTables::of($agendas)
                ->addColumn('actions', function ($agendas) {
                    // Lógica para generar las acciones para cada registro de empleados
                    $reportar = '<a href="' . route("admin.estadocitas", $agendas->id) . '" title="Estado cita" class="btn-eliminar px-2 py-0">
                    <i class="fas fa-file-alt"></i>
                    </a>';

                    return $this->getActionButtons('admin.agendas', 'DISPONIBILIDAD', $agendas->agenda->id) . $reportar;
                    // return $this->getActionButtons('admin.agendas', 'DISPONIBILIDAD', $agendas->agenda->id);
                })
                ->rawColumns(['actions']) //para que se muestre el codigo html en la tabla
                ->make(true);
        }

        return view('admin.agenda.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('CREAR_DISPONIBILIDAD'), Response::HTTP_UNAUTHORIZED);

        $modalidades = Modalidad::all();
        $clientes = Empresa::orderBy('razon_social')->select('id', 'razon_social')->where('estado', 1)->whereJsonContains('empleados', (string)Auth::user()->id)->get();

        if (Auth::user()->role_id == 1) {
            $clientes = Empresa::orderBy('razon_social')->select('id', 'razon_social')->where('estado', 1)->get();
        }

        return view('admin.agenda.create', compact('clientes', 'modalidades'), ['agenda' => new Cita]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateAgendaRequest $request)
    {

        $fecha_disponibilidad = Carbon::parse($request->fecha_disponibilidad)->format('Y-m-d');
        $empresa = Empresa::where('razon_social', $request->empresa)->first();

        $exist = AgendaEmpleado::where('fecha_disponibilidad', $fecha_disponibilidad)
            ->where('user_id', Auth::user()->id)
            ->get();

        if ($request->fecha_disponibilidad <= Carbon::now()->format('Y-m-d')) {
            return redirect()->route('admin.agendas.create')->withInput()->with('message', 'No puedes crear la agenda con una fecha anterior o igual a la actual.')->with('color', 'danger');
        }

        if ($request->hora_fin <= $request->hora_inicio) {
            return redirect()->route('admin.agendas.create')->withInput()->with('message', 'No puedes crear la hora final con una hora anterior o igual a la inicial.')->with('color', 'danger');
        }

        foreach ($exist as $exist) {
            if (Carbon::parse($request->hora_inicio)->format('H:i:s') >= $exist->hora_inicio && Carbon::parse($request->hora_fin)->format('H:i:s') <= $exist->hora_fin) {
                return redirect()->route('admin.agendas.create')->withInput()->with('message', 'Ya existe una agenda con ese horario.')->with('color', 'danger');
            }
        }

        $request = $request->merge(['empresa_id' => $empresa->id]);
        $request = $request->merge(['fecha_disponibilidad' => $fecha_disponibilidad]);
        $request = $request->merge(['user_id' => Auth::user()->id]);

        $agenda = AgendaEmpleado::create($request->all());

        $request = $request->merge(['agenda_id' => $agenda->id]);

        $agenda_empresa = AgendaEmpresa::create($request->all());

        $request = $request->merge(['fecha_inicio' => $fecha_disponibilidad . ' ' . $request->hora_inicio]);
        $request = $request->merge(['fecha_fin' => $fecha_disponibilidad . ' ' . $request->hora_fin]);
        $request = $request->merge(['agenda_id' => $agenda_empresa->id]);

        $cita = Cita::create($request->all());
        $user = User::find(Auth::user()->id);

        try {
            Notification::send($empresa, new eventCreatedNotification($cita, $user, $empresa));
            Notification::send($user, new eventCreatedNotification($cita, $user, $empresa));

            session(['message' => 'La agenda se ha creado exitosamente.', 'color' => 'success']);
            return redirect()->route('admin.agendas.index');
        } catch (\Exception $e) {
                Log::error($e);
            session(['message' => 'La agenda se ha creado exitosamente, pero hubo un problema al enviar el correo.', 'color' => 'warning']);
            return redirect()->route('admin.agendas.index');
        }
    }


    public function empleadoCliente($id, $agenda)
    {

        $empleado = EmpleadoCliente::where('id', $id)->first();
        $agenda = AgendaEmpresa::where('agenda_id', $agenda)->first();

        $info = [
            'nombres' => $empleado->nombres . ' ' . $empleado->apellidos,
            'empresa' => $agenda->empresa->razon_social
        ];

        return $info;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if(Gate::denies('EDITAR_DISPONIBILIDAD'), Response::HTTP_UNAUTHORIZED);

        $agenda_empleado = AgendaEmpleado::find($id);
        $modalidades = Modalidad::orderBy('nombre')->get();
        $agenda_empresa = AgendaEmpresa::where('agenda_id', $agenda_empleado->id)->first();
        $agenda = Cita::with('agenda_empresa')->where('agenda_id', $agenda_empresa->agenda_id)->first();
        $clientes = Empresa::orderBy('razon_social')->where('estado', 1)->select('id', 'razon_social')->whereJsonContains('empleados', (string)Auth::user()->id)->get();

        if (Auth::user()->role_id == 1) {
            $clientes = Empresa::orderBy('razon_social')->select('id', 'razon_social')->where('estado', 1)->get();
        } 

        return view('admin.agenda.edit', compact('agenda', 'clientes', 'modalidades'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAgendaRequest $request, $id)
    {
        $agenda = AgendaEmpleado::find($id);

        $fecha_disponibilidad =  $request['fecha_disponibilidad'];
        $empresa = Empresa::where('razon_social', $request->empresa)->first();
        $agenda_empresa = AgendaEmpresa::where('agenda_id', $agenda->id)->first();
        $cita = Cita::where('agenda_id', $agenda_empresa->id)->first();

        $exist = AgendaEmpleado::where('fecha_disponibilidad', $fecha_disponibilidad)
            ->where('hora_inicio', $request['hora_inicio'])
            ->where('hora_fin', $request['hora_fin'])
            ->first();

        if ($request->fecha_disponibilidad <= Carbon::now()->format('Y-m-d')) {
            return redirect()->route('admin.agendas.edit', compact('agenda'))->withInput()->with('message', 'No puedes actualizar la agenda con una fecha anterior o igual a la actual.')->with('color', 'danger');
        }

        if ($exist && (intval($id) != $exist->id)) {
            return redirect()->route('admin.agendas.edit', compact('agenda'))->withInput()->with('message', 'Ya existe una agenda con ese horario.')->with('color', 'danger');
        }

        $request = $request->merge(['empresa_id' => $empresa->id]);
        $request = $request->merge(['fecha_disponibilidad' => $fecha_disponibilidad]);

        $agenda->update($request->all());
        $agenda_empresa->update($request->all());

        $request = $request->merge(['fecha_inicio' => $fecha_disponibilidad . ' ' . $request->hora_inicio]);
        $request = $request->merge(['fecha_fin' => $fecha_disponibilidad . ' ' . $request->hora_fin]);

        $cita->update($request->all());

        return redirect()->route('admin.agendas.index')->with('message', 'La agenda se ha actualizado exitosamente.')->with('color', 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort_if(Gate::denies('ELIMINAR_DISPONIBILIDAD'), Response::HTTP_UNAUTHORIZED);

        $agenda = AgendaEmpleado::find($id);
        $agendaEmpresa = AgendaEmpresa::where('agenda_id', $agenda->id)->first();
        $cita = Cita::where('agenda_id', $agendaEmpresa->id)->first();

        $cita->forceDelete();
        $agendaEmpresa->forceDelete();
        $agenda->forceDelete();

        return true;
    }

    public function cancelarCita($id)
    {
        $cita = Cita::find($id);

        if ($cita->estados == 3) {
            return 0;
        }

        $cita->update(['estados' => 3]);

        return true;
    }

    public function estadoCitas($id)
    {
        $agenda = AgendaEmpresa::where('agenda_empresas.agenda_id', $id)
            ->join('citas', 'citas.agenda_id', '=', 'agenda_empresas.agenda_id')
            ->join('empresas', 'empresas.id', '=', 'agenda_empresas.empresa_id')
            ->select('agenda_empresas.id', 'agenda_empresas.created_at', 'agenda_empresas.estado', 'citas.motivo', 'empresas.razon_social') // Selecciona las columnas que deseas
            ->first();
        return view('admin.agenda.estado-citas', compact('agenda'));
    }

    public function estadosUpdate(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required',
        ]);
        // Buscar la agenda por su ID
        $agenda = AgendaEmpresa::findOrFail($id);
        // Actualizar solo el estado
        $agenda->estado = $request->estado;
        $agenda->save();
        // Redirigir con un mensaje de éxito
        return redirect()->route('admin.agendas.index')->with('message', 'Estado actualizado correctamente.')->with('color', 'success');
    }
}
