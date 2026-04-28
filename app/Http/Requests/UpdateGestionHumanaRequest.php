<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateGestionHumanaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('EDITAR_GESTIÓN_HUMANA');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'empresa_id' => 'required',
            'cedula' => 'required|between:6,10',
            'nombres' => 'required',
            'fecha_nacimiento' => 'required',
            'direccion' => 'required',
            'municipio_residencia' => 'required',
            'salario' => 'required',
            'cargo' => 'required',
            'fecha_ingreso' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'empresa_id.required' => __('Selecciona la empresa a la que pertenece el empleado.'),
            'cedula.required' => __('Ingresa una cédula.'),
            'cedula.digits' => 'La cédula no debe tener menos de 6 caracteres.',
            'nombres.required' => __('Ingresa el nombre completo del empleado.'),
            'fecha_nacimiento.required' => __('Selecciona la fecha de nacimiento.'),
            'direccion.required' => __('Ingresa la direccón.'),
            'municipio_residencia.required' => __('Ingresa el municipio de residencia.'),
            'salario.required' => __('Ingresa un valor.'),
            'cargo.required' => __('Por favor ingresa el cargo del empleado.'),
            'fecha_ingreso.required' => __('Selecciona la fecha en que ingreso el empleado.'),
        ];
    }
}
