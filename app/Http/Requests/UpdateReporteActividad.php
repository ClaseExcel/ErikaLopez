<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\RequiredIf;

class UpdateReporteActividad extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'estado_actividad_id' => ['required'],
            'justificacion' => [
                'max:900',  function ($attribute, $value, $fail) {
                    $estadoActividad = $this->input('estado_actividad_id');

                    if ($value == null) {
                        if ($estadoActividad == 3 || $estadoActividad == 9) {
                            $fail("Ingresa una justificación");
                        }
                    }
                },
            ],
            'modalidad' => ['not_in:0', function ($attribute, $value, $fail) {
                $estadoActividad = $this->input('estado_actividad_id');
                if ($value == null) {
                    if ($estadoActividad == 3 || $estadoActividad == 9) {
                        $fail("Selecciona una modalidad");
                    }
                }
            },]
        ];
    }

    public function messages()
    {
        return [
            'estado_actividad_id.required' => __('Debes seleccionar un estado.'),
            'justificacion.required_if' => __('Ingresa una justificación.'),
            'justificacion.max' => __('El máximo de cáracteres es 900.'),
            'modalidad.required_if' => __('Selecciona una modalidad.'),
        ];
    }
}
