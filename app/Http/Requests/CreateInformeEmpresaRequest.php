<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CreateInformeEmpresaRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function rules()
    {
        return [
            // 'empresa' => ['required', 'not_in:0'],
            'fecha_inicio' => ['required', 'not_in:0'],
            'fecha_fin' => ['required', 'not_in:0']
        ];
    }


    public function messages()
    {
        return [
            // 'empresa.required' => __('Selecciona una empresa.'),   
            'fecha_inicio.required' => __('Selecciona una fecha de inicio.'),  
            'fecha_fin.required' => __('Seleccion una fecha final.'),    
            'empresa.not_in' => __('Selecciona una empresa.'),
            'fecha_inicio.not_in' => __('Selecciona una fecha de inicio.'),
            'fecha_fin.not_in' => __('Seleccion una fecha final.'),
        ];
    }
}
