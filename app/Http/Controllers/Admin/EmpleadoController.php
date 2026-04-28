<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateUserPasswordRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Cargo;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use App\Mail\restablecerContraseña;
use App\Models\Role;

use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class EmpleadoController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $empleados = User::whereNotIn('role_id', [7,8])->paginate(10);

        return view('admin.empleados.index', compact('empleados'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cargos = Cargo::whereNotIn('nombre', ['Cliente'])->pluck('nombre', 'id')->prepend('Selecciona un cargo');
        $roles = Role::whereNotIn('id', [7,8])->get();

        return view('admin.empleados.create', compact('cargos', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(CreateUserRequest $request)
    // {
    //     $empleado = User::create([
    //         'cedula' => $request['cedula'],
    //         'nombres'  => $request['nombres'],
    //         'apellidos' => $request['apellidos'],
    //         'email' => $request['email'],
    //         'cargo_id' => $request['cargo_id'],
    //         'rol_id' => $request['rol_id'],
    //         'password' => Hash::make($request['password']),
    //     ]);

    //     $token = Password::createToken($empleado);
    //     Mail::to($request['email'])->send(new restablecerContraseña($request['email'], $request['password'], $token));

    //     return redirect()->route('admin.empleados.index')->with('message', 'El usuario se ha creado exitosamente.')->with('color', 'success');
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $empleado = User::find($id);
        $cargos = Cargo::pluck('nombre', 'id')->prepend('Selecciona un cargo');
        $roles = Role::all();

        return view('admin.empleados.edit', compact('empleado', 'cargos', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $empleado = User::find($id);

        $empleado->update($request->all());

        return redirect()->route('admin.empleados.index')->with('message', 'El usuario se ha actualizado exitosamente.')->with('color', 'success');
    }

    /**
     * Acualiza la contraseña del usuario
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = User::where('email', $request['email'])->first();

        if($user){
        //verifica si las contraseñas son similares
        if ($request['password'] == $request['password_confirmation']) {
            $user->update([
                'password' => Hash::make($request['password_confirmation'])
            ]);

            return redirect()->route('login')->with('message', 'La contraseña se ha actualizado exitosamente.')->with('color', 'success');
        }  
        return redirect()->back()->with('message', 'No coinciden las contraseñas.')->with('color', 'danger');
    }

    return redirect()->back()->with('message', 'No existe un usuario con este correo electronico.')->with('color', 'danger');

    }

    public function destroy(User $user)
    {
        abort_if(Gate::denies('ELIMINAR_USUARIOS'), Response::HTTP_UNAUTHORIZED);

        $user->delete();

        return back();
    }


    /**
     *
     * Cambia estado del usuario
     * 
     * @param  int  $id
     */
    public function statusUser($id)
    {
        $empleado = User::find($id);

        $admin = Auth::user()->rol_id;

        if($admin == $id) {
            return redirect()->route('admin.empleados.index')->with('message', 'No puedes inactivarte.')->with('color', 'danger');
        }

        if($empleado->estado == 1) {

            $empleado->estado = "0";
            $empleado->save();

            if($empleado->rol_id == 6 || $empleado->rol_id == 7) {
                return redirect()->route('admin.empleado-clientes.index')->with('message', 'El empleado ha sido inactivado.')->with('color', 'success');
            }
            
            return redirect()->route('admin.empleados.index')->with('message', 'El usuario ha sido inactivado.')->with('color', 'success');
        }

        $empleado->estado = "1";
        $empleado->save();

        if($empleado->rol_id == 6 || $empleado->rol_id == 7) {
            return redirect()->route('admin.empleado-clientes.index')->with('message', 'El empleado ha sido activado.')->with('color', 'success');
        }

        return redirect()->route('admin.empleados.index')->with('message', 'El usuario ha sido activado.')->with('color', 'success');
    }
}
