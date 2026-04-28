<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ActionButtonTrait;
use App\Http\Requests\CreateEmpleadoClienteRequest;
use App\Http\Requests\UpdateEmpleadoClienteRequest;
use App\Mail\restablecerContrasena;
use App\Models\Cargo;
use App\Models\EmpleadoCliente;
use App\Models\Empresa;
use App\Models\Rol;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Yajra\DataTables\Facades\DataTables;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class EmpleadoClienteController extends Controller
{
    use ActionButtonTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('ACCEDER_EMPLEADOS'), Response::HTTP_UNAUTHORIZED);

        if ($request->ajax()) {
            $empleados = EmpleadoCliente::with(['empresas', 'usuarios' => function ($query) {
                $query->withTrashed();
            }])
            ->select('empleado_clientes.*');

            
            return DataTables::of($empleados)
                ->addColumn('actions', function ($empleados) {
                    // Lógica para generar las acciones para cada registro de empleados
                    return $this->getActionButtons('admin.empleados', 'EMPLEADOS', $empleados->user_id, $empleados->usuarios->estado);
                })
                ->rawColumns(['actions']) //para que se muestr el codigo html en la tabla
                ->make(true);
        }

        return view('admin.empleados.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('CREAR_EMPLEADOS'), Response::HTTP_UNAUTHORIZED);

        //obtener el id y el nombre del cargo cliente
        $cargo = Cargo::orderBy('nombre')->pluck('nombre', 'id');
        $roles = Role::orderBy('title')->whereIn('id', [7, 8])->pluck('title', 'id');
        $empresas = Empresa::orderBy('razon_social')->where('estado', 1)->pluck('razon_social', 'id');

        return view('admin.empleados.create', compact('roles', 'cargo', 'empresas'), ['empleado' => new EmpleadoCliente]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateEmpleadoClienteRequest $request)
    {
        $empresas_secundarias = json_encode($request['empresas_secundarias']);
        $request = $request->merge(['correo_electronico' => $request->email]);
        $request = $request->merge(['empresas_secundarias' => $empresas_secundarias]);

        try {
            Mail::to($request->email)->send(new restablecerContrasena($request->email, $request->password));

            $crearUsuario = User::create($request->all());
            $request = $request->merge(['user_id' => $crearUsuario->id]);
            EmpleadoCliente::create($request->all());

            session(['message' => 'El usuario se ha creado exitosamente.', 'color' => 'success']);
            return redirect()->route('admin.empleados.index');
        } catch (\Exception $e) {
                Log::error($e);
            session(['message' => 'Hubo un problema al enviar el correo, el usuario no ha sido creado', 'color' => 'warning']);
            return redirect()->route('admin.empleados.index');
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
        $empleado_cliente = EmpleadoCliente::where('user_id', $id)->first();
        $empresas = [];
        if($empleado_cliente->empresas_secundarias != null && $empleado_cliente->empresas_secundarias != "null") {
            $empresas = Empresa::orderBy('razon_social')->where('estado', 1)->select('id', 'razon_social')->whereIn('id', json_decode($empleado_cliente->empresas_secundarias))->get();
        }  

        return view('admin.empleados.show', compact('empleado_cliente', 'empresas'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if(Gate::denies('EDITAR_EMPLEADOS'), Response::HTTP_UNAUTHORIZED);

        $empleado = EmpleadoCliente::with(['usuarios' => function ($query) {
            $query->withTrashed();
        }])->where('user_id', $id)->first();

        $roles = Role::orderBy('title')->whereIn('id', [7, 8])->pluck('title', 'id');
        $cargo = Cargo::orderBy('nombre')->pluck('nombre', 'id');
        $empresas = Empresa::orderBy('razon_social')->where('estado', 1)->pluck('razon_social', 'id');

        return view('admin.empleados.edit', compact('empleado', 'roles', 'cargo', 'empresas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEmpleadoClienteRequest $request, $id)
    {
        try {
            $empleado_cliente = EmpleadoCliente::where('user_id', $id)->first();
            $user = User::withTrashed()->find($empleado_cliente->user_id);
            $empresas_secundarias = json_encode($request['empresas_secundarias']);
    
            $request = $request->merge(['empresas_secundarias' => $empresas_secundarias]);
            $request = $request->merge(['correo_electronico' => $request->email]);
    
            $empleado_cliente->update($request->all());
            $user->update($request->all());
    
            session(['message' => 'El usuario se ha actualizado exitosamente.', 'color' => 'success']);
            return redirect()->route('admin.empleados.index');
        } catch (\Exception $e) {
                Log::error($e);
            session(['message' => 'Hubo un problema al actualizar el usuario.', 'color' => 'warning']);
            return redirect()->route('admin.empleados.edit', $id);
        }
    }

    public function destroy($id)
    {
        abort_if(Gate::denies('ELIMINAR_EMPLEADOS'), Response::HTTP_UNAUTHORIZED);

        $user = User::withTrashed()->find($id);

        $admin = Auth::user()->id;

        if ($admin == $id) {
            return response()->json(['message' => 'No puedes inactivarte.', 'color' => 'danger'], Response::HTTP_BAD_REQUEST);
        }

        //si esta activo inactivar usuario
        if ($user->estado == 'ACTIVO') {
            $user->estado = 'INACTIVO';
            $user->delete();
            $user->save();


            return true;
        }

        $user->estado = 'ACTIVO';
        $user->restore();
        $user->save();

        return true;
    }
}
