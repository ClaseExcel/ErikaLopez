<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreCentroCostoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('CREAR_CENTROS_COSTOS');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            // 'codigo'        => 'required|unique:centros_costos,codigo|string',
            'codigo'        => 'required|string',
            'nombre'        => 'required|string',
            'compania_id'   => 'required|numeric',
        ];
    }
}
