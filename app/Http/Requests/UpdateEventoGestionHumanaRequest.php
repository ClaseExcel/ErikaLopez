<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateEventoGestionHumanaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('EDITAR_EVENTO_GESTIÓN_HUMANA');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
      public function rules()
    {
        return [
            'fecha_inicio' => 'required',
            'fecha_fin' => 'required',
            'concepto_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'fecha_inicio.required' => __('Por favor ingresa una fecha de inicio.'),
            'fecha_fin.required' => __('Por favor ingresa una fecha fin.'),
            'concepto_id.required' => __('Selecciona el concepto del evento.'),
        ];
    }
}
