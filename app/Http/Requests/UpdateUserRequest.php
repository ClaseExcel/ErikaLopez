<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('EDITAR_USUARIOS');
    }

    public function rules()
    {

        return [
            'cedula'    => 'between:6,10',
            'nombres'   => 'required|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/|string',
            'apellidos' => 'required|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/|string',
            'email'     => 'required|unique:users,email,' . request()->route('user'),
            'role_id'   => 'required', 'not_in:0',
        ];
    }

    public function messages()
    {
        return [
            'cedula.digits' => 'La cédula no debe tener menos de 6 caracteres.',
            'email.unique'  => 'Se encontró un usuario registrado con el mismo correo electrónico.',
        ];
    }
}
