<?php

namespace App\Http\Requests;

use App\Rules\ModificacionRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreModificacionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('CREAR_MODIFICACIONES');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $modificacionRule = new ModificacionRule($this);

        return [
            'compania_id'       => ['required', 'numeric', $modificacionRule],
            'periodo'           => ['required', 'date', $modificacionRule],
            'movimiento'        => ['required', 'numeric', $modificacionRule],
            'campo_modificado'  => [''],
            'valor_ajustado'    => [''],
        ];
    }
}
