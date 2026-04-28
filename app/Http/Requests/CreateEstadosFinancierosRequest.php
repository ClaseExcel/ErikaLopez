<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEstadosFinancierosRequest extends FormRequest
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
            'compania'   => 'required',
            'estado'     => 'required',
            'fechareporte' =>'required'
        ];
    }

    public function attributes()
    {
        return [
            'compania'     => 'Compañía',
            'estado'       => 'Estado Financiero',
            'fechareporte' => 'Fecha de Reporte'
        ];
    }

    public function messages()
    {
        return [
            'compania.required'     => 'El campo :attribute es obligatorio.',
            'estado.required'       => 'El campo :attribute es obligatorio.',
            'fechareporte.required' => 'El campo :attribute es obligatorio.'
        ];
    }
}
