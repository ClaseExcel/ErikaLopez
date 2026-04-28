<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CreateEmpleadoClienteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('CREAR_USUARIOS');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cedula'        => 'required|unique:users,cedula|between:6,10',
            'nombres'       => 'required|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/|string',
            'apellidos'     => 'required|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/|string',
            'numero_contacto' => ['required', 'string', 'max:255'],
            'email'           => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'        => ['required'],
            'cargo_id'        => ['required', 'not_in:0'],
            'role_id'          => ['required', 'not_in:0'],
            'empresa_id'      => ['required', 'not_in:0'],
        ];
    }


    public function messages()
    {
        return [
            'cedula.required' => 'La cédula es requerida.',
            'cedula.digits' => 'La cédula no debe tener menos de 6 caracteres.',
            'cedula.unique' => 'Se encontró un usuario registrado con el mismo número de cédula.',
            'numero_contacto.required' => 'Debes ingresar un número de contacto.',
            'nombres.required'         => 'Debes ingresar un nombre.',
            'apellidos.required'       => 'Debes ingresar un apellido.',
            'email.required'           => 'El correo eléctronico es requerido.',
            'email.unique'             => 'Se encontró un usuario registrado con el mismo correo electrónico.',
            'role_id.required'          => 'Debes seleccionar un rol.',
            'cargo_id.required'          => 'Debes seleccionar un cargo.',
            'cargo_id.not_in'          => 'Debes seleccionar un cargo.',
            'role_id.not_in'            => 'Debes seleccionar un rol.',
            'empresa_id.not_in'        => 'Debes seleccionar una empresa.',
            'empresa_id.required'      => 'Debes seleccionar una empresa.',
            'password.required'        => 'Ingresa una constraseña.'
        ];
    }
}
