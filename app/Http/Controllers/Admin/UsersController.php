<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ActionButtonTrait;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Mail;
use App\Mail\restablecerContrasena;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UsersController extends Controller
{

    use ActionButtonTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('GESTIONAR_USUARIOS'), Response::HTTP_UNAUTHORIZED);

        if ($request->ajax()) {
            $users =  User::with('role')->select('users.*')->whereNotIn('role_id', [7])->withTrashed();

            return DataTables::of($users)
                ->addColumn('actions', function ($users) {
                    // Lógica para generar las acciones para cada registro de users
                    return $this->getActionButtons('admin.users', 'USUARIOS', $users->id, $users->estado);
                })
                ->rawColumns(['actions']) //para que se muestr el codigo html en la tabla
                ->make(true);
        }

        return view('admin.users.index');
    }

    public function create()
    {
        abort_if(Gate::denies('CREAR_USUARIOS'), Response::HTTP_UNAUTHORIZED);

        $roles = Role::orderBy('title')->whereNotIn('id', [7])->get();
        return view('admin.users.create', compact('roles'), ['user' => new User]);
    }

    public function store(StoreUserRequest $request)
    {
        $firma = $request->file('firma');
        
        if ($firma == null) {
            $firmaName = 'default.jpg';
            $request->merge(['firma' => $firmaName]);
        } else {
            // Generar un nombre único para el archivo
            $firmaName = Str::uuid() . '.' . $firma->extension();
            $firma->storeAs('users_firma', $firmaName, 'public');
            $request['firma'] = $firmaName;
        }
        try {
            Mail::to($request->email)->send(new restablecerContrasena($request->email, $request->password));
            $user = User::create($request->except(['firma']));
            $user->firma = $firmaName;
            $user->save();

            session(['message' => 'El usuario se ha creado exitosamente.', 'color' => 'success']);
            return redirect()->route('admin.users.index');
        } catch (\Exception $e) {
                Log::error($e);
            session(['message' => 'Hubo un problema al enviar el correo, el usuario no ha sido creado', 'color' => 'warning']);
            return redirect()->route('admin.users.index');
        }
    }

    public function edit($id)
    {

        abort_if(Gate::denies('EDITAR_USUARIOS'), Response::HTTP_UNAUTHORIZED);

        $user = User::withTrashed()->find($id);
        $roles = Role::orderBy('title')->whereNotIn('id', [7])->get();

        $user->load('role');

        return view('admin.users.edit', compact('roles', 'user'));
    }

    public function update(UpdateUserRequest $request, $id)
    {
        try {
            $user = User::withTrashed()->find($id);
            $firma = $request->file('firma');
            if ($firma != null) {
                //obtener el nombre del firma del usuario
                $firmaName = $user->firma;

                //eliminar el firma anterior
                if ($firmaName != 'default.jpg') {
                    //unlink de //storage public users-firma
                    unlink(public_path('storage/users_firma/' . $firmaName));
                }
                // Generar un nombre único para el archivo
                $firmaName = Str::uuid() . '.' . $firma->extension();
                $firma->storeAs('users_firma', $firmaName, 'public');

                //reemplazar el firma  
                $user->firma = $firmaName;

                //actualizar el firma del usuario
                $user->update();
            }
            $user->update($request->except(['firma']));

            session(['message' => 'El usuario se ha actualizado exitosamente.', 'color' => 'success']);
            return redirect()->route('admin.users.index');
        } catch (\Exception $e) {
                Log::error($e);
            session(['message' => 'Hubo un problema al actualizar el usuario', 'color' => 'warning']);
            return redirect()->route('admin.users.edit', $id);
        }
    }

    public function show($id)
    {
        abort_if(Gate::denies('VER_USUARIOS'), Response::HTTP_UNAUTHORIZED);

        $user = User::withTrashed()->find($id);
        $user->load('role');

        return view('admin.users.show', compact('user'));
    }

    public function destroy($id)
    {
        abort_if(Gate::denies('ELIMINAR_USUARIOS'), Response::HTTP_UNAUTHORIZED);

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

    public function massDestroy(MassDestroyUserRequest $request)
    {
        User::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
