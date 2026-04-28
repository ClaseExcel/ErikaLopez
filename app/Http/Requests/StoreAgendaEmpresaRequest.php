<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Gate;

class StoreAgendaEmpresaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('CREAR_ASIGNAR_AGENDA');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'agenda_id' => ['required', 'not_in:0'],
            'empresa_id' => ['required','not_in:0'],
        ];
    }


    public function messages()                                      
    {
        return [
            'empresa_id.required' => __('Debes seleccionar una agenda.'),
            'empresa_id.required' => __('Selecciona un cliente.'),
            'agenda_id.not_in' => __('Debes seleccionar una agenda.'),
            'empresa_id.not_in' => __('Selecciona un cliente.'),
        ];
    }
}
