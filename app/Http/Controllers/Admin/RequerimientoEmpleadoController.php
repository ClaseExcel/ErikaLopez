<?php

namespace App\Http\Controllers\admin;

use App\Exports\RequerimientosExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SeguimientoRequerimiento;
use App\Models\EstadoRequerimiento;
use App\Models\TipoRequerimiento;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\seguimientoRequerimientos;
use App\Mail\asociadoRequerimiento;
use App\Http\Requests\UpdateSeguimientoRequerimientoRequest;
use App\Models\Documento;
use App\Models\EmpleadoCliente;
use App\Notifications\ActualizacionRequerimientoNotification;
use App\Notifications\MessageSent;
use App\Notifications\SolicitudRequerimientoNotification;

use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Traits\ActionButtonTrait;
use Maatwebsite\Excel\Facades\Excel;

class RequerimientoEmpleadoController extends Controller
{

    use ActionButtonTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('ACCEDER_REQ_EMPLEADOS'), Response::HTTP_UNAUTHORIZED);

        if ($request->ajax()) {

            //Si el rol del usuario es supoer administrador, administrador y contador senior mostrar todas las actividades
            $requerimientos = SeguimientoRequerimiento::with('requerimientos', 'usuario_responsable', 'requerimientos.tipo_requerimientos', 'estado_requerimientos', 'empresa')
                ->whereNotIn('estado_requerimiento_id', [6]);

            return DataTables::of($requerimientos)
                ->addColumn('actions', function ($requerimientos) {
                    // Lógica para generar las acciones para cada registro de empleados;
                    $ver_requerimiento = '';

                    if (Gate::allows('VER_MIS_REQUERIMIENTOS')) {
                        $ver_requerimiento = '<a class="btn-ver px-2 py-0" href="' . route('admin.requerimientos.cliente.show', $requerimientos->id) . '" title="Ver más información"><i class="fas fa-eye"></i></a>';
                    }

                    if ($requerimientos->estado_requerimiento_id == 5 || $requerimientos->estado_requerimiento_id == 3) {
                        return $ver_requerimiento;
                    } else {
                        return $this->getActionButtons('admin.requerimientos.empleado', 'REQ_EMPLEADOS', $requerimientos->id) . $ver_requerimiento;
                    }
                })
                ->rawColumns(['actions']) //para que se muestre el codigo html en la tabla
                ->make(true);
        }

        return view('admin.requerimiento-empleado.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if(Gate::denies('EDITAR_REQ_EMPLEADOS'), Response::HTTP_UNAUTHORIZED);

        $requerimiento = SeguimientoRequerimiento::where('requerimiento_id', $id)->first();

        $estados = EstadoRequerimiento::whereNotIn('id', [1, 4, 5, 6])->get();
        $tipo_requerimientos = TipoRequerimiento::orderBy('nombre')->select('nombre', 'id')->get();
        $responsables = User::whereNotIn('role_id', [7, 8])->orderBy('nombres')->get();
        $documento = Documento::where('requerimiento_id', $id)->first();

        return view('admin.requerimiento-empleado.edit', compact('requerimiento', 'estados', 'tipo_requerimientos', 'responsables', 'documento'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSeguimientoRequerimientoRequest $request, $id)
    {
        $requerimiento = SeguimientoRequerimiento::find($id);

        $responsable = User::find($request->user_id);
        $estado = EstadoRequerimiento::find($request->estado_requerimiento_id);

        $requerimiento->update([
            'observacion' => $request->observacion,
            'fecha_vencimiento' => $request->fecha_vencimiento,
            'user_id' => $request->user_id,
            'estado_requerimiento_id' => $request->estado_requerimiento_id,
        ]);

        $correo_cliente = $requerimiento->requerimientos->empleado_clientes->correo_electronico;
        $usuario_cliente = $requerimiento->requerimientos->empleado_clientes->nombres . ' ' . $requerimiento->requerimientos->empleado_clientes->apellidos;

        //Envia una notificación para el empleado asignado y el cliente 
        try {
            $this->sendNotification(
                $request->user_id,
                $requerimiento->requerimientos->empleado_clientes->user_id,
                $requerimiento->requerimientos->id,
                $requerimiento->requerimientos->consecutivo,
                $estado->nombre
            );

            if ($request->user_id) {
                Mail::to($responsable->email)
                    ->send(new asociadoRequerimiento(
                        $requerimiento->requerimientos->consecutivo,
                        $usuario_cliente,
                        $requerimiento->requerimientos->tipo_requerimientos->nombre
                    ));
            }

            Mail::to($correo_cliente)
                ->send(new seguimientoRequerimientos(
                    $requerimiento->requerimientos->consecutivo,
                    $request->observacion,
                    $request->estado_requerimiento_id,
                    $usuario_cliente,
                    $estado->nombre
                ));
        } catch (\Exception $e) {
                Log::error($e);
            session(['message' => 'El requerimiento ha sido actualizado exitosamente, pero hubo problemas al enviar algunas notificaciones.', 'color' => 'warning']);
            return redirect()->route('admin.requerimientos.empleado.index');
        }

        session(['message' => 'Requerimiento actualizado exitosamente.', 'color' => 'success']);
        return redirect()->route('admin.requerimientos.empleado.index');
    }


    private function sendNotification($empleado, $cliente, $requerimiento, $consecutivo, $estado)
    {

        if ($empleado) {
            $empleado = User::find($empleado);
            $nombrePlantilla = 'asignacion_requerimiento';

            $dataEmpleado = [
                'subject' => 'Se te ha asignado un requerimiento',
                'notifiable_id' => $empleado,
                'actividad_id' =>  $requerimiento,
                'url' => route('admin.requerimientos.cliente.show', $requerimiento),
                'message' => 'Te notificamos que se ha asignado un requerimiento con el consecutivo registrado <strong>' . $consecutivo . '</strong> para que se de su desarrollo.',
            ];

            $empleado->notify(new MessageSent($dataEmpleado));

            if ($empleado->numero_contacto) {
                $numero = $empleado->numero_contacto;
                $empleado->notify(new SolicitudRequerimientoNotification($consecutivo, $requerimiento, $nombrePlantilla, $numero));
            }
        }

        $cliente = User::find($cliente);
        $nombrePlantilla = 'seguimiento_requerimiento';
        $numero = EmpleadoCliente::where('user_id', $cliente->id)->first();

        $dataCliente = [
            'subject' => 'Se ha actualizado tu requerimiento',
            'notifiable_id' => $cliente,
            'actividad_id' =>  $requerimiento,
            'url' => route('admin.requerimientos.cliente.show', $requerimiento),
            'message' => 'Nos complace informarte que el estado de tu requerimiento esta <strong>' . $estado . '</strong>, su consecutivo es <strong>' . $consecutivo . '</strong>.  
            Da clic si deseas ver más información.',
        ];

        $cliente->notify(new MessageSent($dataCliente));
        $cliente->notify(new ActualizacionRequerimientoNotification($consecutivo, $estado, $requerimiento, $nombrePlantilla, $numero->numero_contacto));
    }

    public function requerimientoExport()
    {
        $seguimientos = SeguimientoRequerimiento::with('requerimientos', 'usuario_responsable', 'requerimientos.tipo_requerimientos', 'estado_requerimientos', 'empresa')
            ->whereNotIn('estado_requerimiento_id', [6])->get();

        return Excel::download(new RequerimientosExport($seguimientos), 'Lista de requerimientos.xlsx');
    }
}
