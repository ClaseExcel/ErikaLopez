<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CreateInformeActividadesRequest extends FormRequest
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

    public function rules()
    {
        return [
            // 'usuario' => ['required', 'not_in:0'],
            'fecha_inicio' => ['required', 'not_in:0'],
            'fecha_fin' => ['required', 'not_in:0']
        ];
    }


    public function messages()
    {
        return [
            'fecha_inicio.required' => __('Selecciona una fecha de inicio.'),  
            'fecha_fin.required' => __('Selecciona una fecha final.'), 
            // 'usuario.required' => __('Selecciona un usuario.'),    
            // 'usuario.not_in' => __('Selecciona un usuario.'),   
            'fecha_inicio.not_in' => __('Selecciona una fecha de inicio.'),
            'fecha_fin.not_in' => __('Selecciona una fecha final.'),
        ];
    }
}
