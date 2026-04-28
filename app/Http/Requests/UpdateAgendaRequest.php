<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Gate;

class UpdateAgendaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('EDITAR_DISPONIBILIDAD');
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
        ];
    }


    public function messages()                                      
    {
        return [
            'fecha_disponibilidad.required' => __('Debes seleccionar una fecha.'),
            'hora_inicio.required' => __('Selecciona una hora de inicio.'),
            'hora_fin.required' => __('Seleciona una hora final.'),
        ];
    }
}
