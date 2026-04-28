<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreChecklistRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('CREAR_CHECKLIST_CONTABLE');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'empresa_id' => ['required', 'not_in:0'],
            'año' => ['required','not_in:0'],
            'actividades' => ['required', 'not_in:0'],
        ];
    }


    public function messages()                                      
    {
        return [
        
            'empresa_id.required' => 'Selecciona una empresa.',
            'empresa_id.not_in' => 'Debe seleccionar una empresa válida.',
            'año.required' => 'Selecciona un año.',
            'año.not_in' => 'Debe seleccionar un año válido.',
            'actividades.required' => 'Selecciona uno o varias actividades.',
            'actividades.not_in' => 'Debe seleccionar al menos una actividad.',
        ];
    }
}
