<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CreateRequerimientoRequest extends FormRequest
{
    
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('CREAR_SOLICITAR_REQUERIMIENTOS');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'tipo_requerimiento_id' => ['required', 'not_in:0'],
            'empresa_id' => ['required', 'not_in:0'],
            'descripcion' => ['required', 'string', 'max:255'],
            'documentos' => ['max:25000']
        ];
    }


    public function messages()
    {
        return [
            'tipo_requerimiento_id.not_in' => __('Selecciona tu tipo de requerimiento.'),
            'empresa_id.required' => __('Selecciona la empresa.'),
            'empresa_id.not_in' => __('Selecciona la empresa.'),
            'descripcion.required' => __('La descripción es requerida.'),
            'documentos.max' => __('El documento o los documentos no deben ser mayores a 25MB.'),
        ];
    }
}
