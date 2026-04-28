<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Traits\ActionButtonTrait;
use App\Http\Requests\UpdateSeguimientoRequerimientoRequest;
use App\Mail\seguimientoRequerimientos;
use App\Models\Documento;
use App\Models\EmpleadoCliente;
use App\Models\EstadoRequerimiento;
use App\Models\SeguimientoRequerimiento;
use App\Models\TipoRequerimiento;
use App\Models\User;
use App\Notifications\ActualizacionRequerimientoNotification;
use App\Notifications\MessageSent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

class SeguimientoRequerimientoController extends Controller
{
    use ActionButtonTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('ACCEDER_SEGUIMIENTO_REQ'), Response::HTTP_UNAUTHORIZED);

        if ($request->ajax()) {

            //Si el rol del usuario es supoer administrador, administrador y contador senior mostrar todas las actividades
            $requerimientos = SeguimientoRequerimiento::with('requerimientos', 'usuario_responsable', 'requerimientos.tipo_requerimientos', 'estado_requerimientos', 'empresa')
            ->whereNotIn('estado_requerimiento_id', [6])->where('user_id', Auth::user()->id);

            return DataTables::of($requerimientos)
                ->addColumn('actions', function ($requerimientos) {
                    // Lógica para generar las acciones para cada registro de empleados

                    $ver_requerimiento = '';

                    if (Gate::allows('VER_MIS_REQUERIMIENTOS')) {
                        $ver_requerimiento = '<a class="btn-ver px-2 py-0" href="' . route('admin.requerimientos.cliente.show',$requerimientos->requerimiento_id) . '" title="Ver más información"><i class="fas fa-eye"></i></a>';
                    }

                    if($requerimientos->estado_requerimiento_id == 5 || $requerimientos->estado_requerimiento_id == 3) {
                        return $ver_requerimiento;
                    } else {
                        return $this->getActionButtons('admin.seguimientos.cliente', 'SEGUIMIENTO_REQ', $requerimientos->requerimiento_id) . $ver_requerimiento;
                    }
                })
                ->rawColumns(['actions']) //para que se muestre el codigo html en la tabla
                ->make(true);
        }


        return view('admin.requerimiento-empleado.seguimiento.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        abort_if(Gate::denies('EDITAR_SEGUIMIENTO_REQ'), Response::HTTP_UNAUTHORIZED);

        $requerimiento = SeguimientoRequerimiento::where('requerimiento_id',$id)->first();

        if ($requerimiento->estado_requerimiento_id == 4) {
            $estados = EstadoRequerimiento::whereNotIn('id', [1, 2, 3, 4, 6])->get();
        } else {
            $estados = EstadoRequerimiento::whereNotIn('id', [1, 2, 3, 6])->get();
        }

        $tipo_requerimientos = TipoRequerimiento::orderBy('nombre')->select('nombre', 'id')->get();
        $documento = Documento::where('requerimiento_id', $id)->first();

        return view('admin.requerimiento-empleado.seguimiento.edit', compact('requerimiento', 'estados', 'tipo_requerimientos', 'documento'));
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
        $correo_cliente = $requerimiento->requerimientos->empleado_clientes->correo_electronico;
        $usuario_cliente = $requerimiento->requerimientos->empleado_clientes->nombres . ' ' . $requerimiento->requerimientos->empleado_clientes->apellidos;
        $estado = EstadoRequerimiento::find($request['estado_requerimiento_id']);

        if ($requerimiento->estado_requerimiento_id == 2 && $request['estado_requerimiento_id'] == 5) {
            return redirect()->back()->with('message', 'No puedes finalizar un requerimiento sin haber estado en proceso.')->with('color', 'danger');
        }

        $urlPathDocument = null;

        if ($request->file('documento')) {
            $archivo = $request->file('documento');

            $documento = $archivo->getClientOriginalName();
            $urlPathDocument = 'storage/seguimiento_requerimiento/' . $archivo->getClientOriginalName();

            Storage::disk('seguimiento_requerimiento')->put($documento, File::get($archivo));
        }

        if ($request->hasFile('documento_extra')) {
            foreach ($request->file('documento_extra') as $file) {
                if ($file) {
                    $documento = $file->getClientOriginalName();
                    $urlPathDocumentExtra = 'storage/seguimiento_requerimiento/' . $file->getClientOriginalName();

                    Storage::disk('seguimiento_requerimiento')->put($documento, File::get($file));

                    Documento::create([
                        'requerimiento_id' => $requerimiento->requerimiento_id,
                        'documentos' => $urlPathDocumentExtra,
                    ]);
                }
            }
        }

        if($request->estado_requerimiento_id == 5) {
            $request = $request->merge(['fecha_fin' => Carbon::now()->format('d-m-Y h:i:s')]);
        }

        $requerimiento->update([
            'observacion' => $request['observacion'],
            'estado_requerimiento_id' => $request['estado_requerimiento_id'],
            'documento' => $urlPathDocument
        ]);

        //Envia una notificación para el cliente 
        try {
            $this->sendNotification(
                $requerimiento->requerimientos->empleado_clientes->user_id,
                $requerimiento->requerimientos->id,
                $requerimiento->requerimientos->consecutivo,
                $estado->nombre
            );
    
            Mail::to($correo_cliente)->send(new seguimientoRequerimientos($requerimiento->requerimientos->consecutivo, $request['observacion'], $request['estado_requerimiento_id'], $usuario_cliente, $estado->nombre));
        } catch (\Throwable $th) {
            session(['message' => 'El requerimiento ha sido actualizado exitosamente, pero hubo problemas al enviar algunas notificaciones.', 'color' => 'warning']);
            return redirect()->route('admin.seguimientos.cliente.index');
        }
        
        session(['message' => 'Requerimiento actualizado exitosamente.', 'color' => 'success']);
        return redirect()->route('admin.seguimientos.cliente.index');
    }

    private function sendNotification($cliente, $requerimiento, $consecutivo, $estado)
    {    
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
}
