<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateEmpleadoClienteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('EDITAR_EMPLEADOS');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cedula'  => 'between:6,10',
            'nombres' => 'required', 'string',
            'apellidos' => 'required', 'string',
            'numero_contacto' => 'required',
            'email'  => 'required' ,
            'cargo_id' => 'required', 'not_in:0',
            'role_id' => 'required', 'not_in:0',
            'empresa_id' => 'required', 'not_in:0',
        ];
    }


    public function messages()
    {
        return [
            'cedula.digits' => 'La cédula no debe tener menos de 6 caracteres.',
            'numero_contacto.required' => __('Debes ingresar un número de contacto.'),
            'nombres.required' => __('Debes ingresar un nombre.'),
            'apellidos.required' => __('Debes ingresar un apellido.'),
            'email.required' => __('El correo eléctronico es requerido.'),
            'cargo_id.required' => __('Debes seleccionar un cargo.'),
            'cargo_id.not_in' => __('Debes seleccionar un cargo.'),
            'role_id.not_in' => __('Debes seleccionar un rol.'),
            'role_id.required' => __('Debes seleccionar un rol.'),
            'empresa_id.not_in' => __('Debes seleccionar una empresa.'),
        ];
    }
}
