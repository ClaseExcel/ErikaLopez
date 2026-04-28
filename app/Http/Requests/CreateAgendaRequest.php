<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Gate;

class CreateAgendaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('CREAR_DISPONIBILIDAD');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'fecha_disponibilidad' => ['required'],
            'hora_inicio' => ['required','date_format:H:i'],
            'hora_fin' => ['required', 'date_format:H:i'],
            'motivo' => ['required'],
            'empresa' => ['required', 'not_in:0'],
            'modalidad_id' => ['required', 'not_in:0'],
            'link' => ['required_if:modalidad_id,1'],
            'direccion' => ['required_if:modalidad_id,2'],
        ];
    }


    public function messages()                                      
    {
        return [
            'fecha_disponibilidad.required' => __('Debes seleccionar una fecha.'),
            'empresa.not_in' => __('Debes seleccionar un cliente.'),
            'hora_inicio.required' => __('Selecciona una hora de inicio.'),
            'hora_fin.required' => __('Seleciona una hora final.'),
            'motivo.required' => __('El motivo es requerido'),
            'modalidad_id.required' => __('Selecciona una modalidad.'),
            'link.required_if' => __('Ingresa un link.'),
            'direccion.required_if' => __('Ingresa una dirección de encuentro.'),
        ];
    }
}
