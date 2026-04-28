<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\EmpleadoCliente;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ChangePasswordController extends Controller
{
    public function edit()
    {
        abort_if(Gate::denies('CAMBIAR_PASSWORD'), Response::HTTP_UNAUTHORIZED);

        return view('auth.passwords.edit');
    }

    public function update(UpdatePasswordRequest $request)
    {
        auth()->user()->update($request->validated());

        return redirect()->route('profile.password.edit')->with('message', __('Contraseña actualizada exitosamente.'))->with('color', 'success');
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = Auth::user();

        if(auth()->user()->role_id == 7){
            $empleado = EmpleadoCliente::where('user_id', Auth::user()->id)->first();
            $empleado->update([
                'nombres' => request('nombres'),
                'apellidos' => request('apellidos'),
                'correo_electronico' => request('email')
            ]);
        }

        $user->update($request->validated());

        return redirect()->route('profile.password.edit')->with('message', __('Perfil actualizado exitosamente.'))->with('color', 'success');
    }

    public function destroy()
    {
        $user = Auth::user();

        $user->update([
            'email' => time() . '_' . $user->email,
        ]);

        $user->delete();

        return redirect()->route('login')->with('message', __('Cuenta eliminada exitosamente'))->with('color', 'success');
    }
}
