<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('CREAR_USUARIOS');
    }

    public function rules()
    {
        return [
            'cedula'        => 'required|unique:users,cedula|between:6,10',
            'nombres'       => 'required|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/|string',
            'apellidos'     => 'required|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/|string',
            'email'         => 'required|unique:users',
            'password'      => 'required',
            'role_id'       => 'integer',
        ];
    }

    public function messages()
    {
        return [
            'cedula.digits' => 'La cédula no debe tener menos de 6 caracteres.',
            'cedula.unique' => 'Se encontró un usuario registrado con el mismo número de cédula.',
            'email.unique'  => 'Se encontró un usuario registrado con el mismo correo electrónico.',
        ];
    }
}
