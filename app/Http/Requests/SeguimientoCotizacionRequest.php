<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class SeguimientoCotizacionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('SEGUIMIENTO_COTIZACIONES');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'estado_cotizacion' => 'required',
            'observaciones' => 'required',
            'observacion_proximo_seguimiento' => [
                function ($attribute, $value, $fail) {
                    $fecha = $this->input('fecha_proximo_seguimiento');
                        if ($fecha) {
                            if ($value === "null" || empty($value)) {
                            $fail("Ingresa una observación para el próximo seguimiento.");
                            }
                        }
                },
            ],
        ];
    }

    public function messages()
    {
        return [
            'estado_cotizacion.required' => __('Selecciona un estado para la cotización.'),
            'observaciones.required' => __('Ingresa una observación.'),
        ];
    }
}
