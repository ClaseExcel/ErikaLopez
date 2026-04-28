<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreGestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('CREAR_GESTION');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'fecha_visita' => ['required'],
            'detalle_visita' => ['required'],
            'tipo_visita' => ['required', 'not_in:0'],
            'cliente_id' => ['required', 'not_in:0'],
        ];
    }

    public function messages()                                      
    {
        return [
            'fecha_visita.required' => __('Debes seleccionar una fecha.'),
            'detalle_visita.required' => __('Ingresa el detalle de la visita.'),
            'tipo_visita.required' => __('Selecciona un tipo de visita.'),
            'tipo_visita.not_in' => __('Selecciona un tipo de visita.'),
            'cliente_id.required' => __('Selecciona un cliente.'),
            'cliente_id.not_in' => __('Selecciona un cliente.'),
        ];
    }
}
