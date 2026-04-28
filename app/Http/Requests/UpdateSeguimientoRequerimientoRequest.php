<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateSeguimientoRequerimientoRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('EDITAR_SEGUIMIENTO_REQ');
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function rules()
    {
        return [
            'estado_requerimiento_id' => ['required', 'not_in:0'],
            'observacion' => ['required', 'string'],
            'fecha_vencimiento' => ['required_if:estado_requerimiento_id,1,2'],
            'user_id' => ['required_if:estado_requerimiento_id,2'],
        ];
    }


    public function messages()
    {
        return [
            'estado_requerimiento_id.required' => __('Cambia el estado del requerimiento.'),
            'estado_requerimiento_id.not_in' => __('Cambia el estado del requerimiento.'),
            'observacion.required' => __('La observación es requerida.'),
            'fecha_vencimiento.required' => __('La fecha de vencimiento es requerida.'),
            'user_id.required_if' => __('El responsable es requerido.'),
        ];
    }
}
