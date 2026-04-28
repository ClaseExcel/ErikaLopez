<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateActividadClienteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('CREAR_USUARIOS');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // return [
        //     'actividad_id' => 'required|numeric',
        //     'progreso' => 'required|numeric',
        //     'fecha_vencimiento' => 'required|date',
        //     'periodicidad' => $this->input('actividad_id') == 1 ? 'required|numeric' : '',
        //     // 'periodicidad_corte' => $this->input('actividad_id') == 1 ? 'required|numeric' : '',
        //     'recordatorio' => 'required|numeric',
        //     'recordatorio_distancia' => 'required|numeric',
        //     'responsable_id' => 'required|numeric',
        //     'cliente_id' => 'required|numeric',
        //     'usuario_id' => 'required|numeric',
        // ];
    }

    // public function messages()
    // {
        // return [
            // 'actividad_id.required' => __('El campo actividad es obligatorio.'),
            // 'progreso.required' => __('El campo progreso es obligatorio.'),
            // 'fecha_vencimiento.required' => __('El campo fecha vencimiento es obligatorio.'),
            // 'periodicidad.required' => __('El campo periodicidad es obligatorio.'),
            // 'periodicidad_corte.required' => __('El campo fecha finalización periodicidad es obligatorio.'),
            // 'recordatorio.required' => __('El campo recordatorio es obligatorio.'),
            // 'recordatorio_distancia.required' => __('El campo cantidad recordatorio es obligatorio.'),
            // 'responsable_id.required' => __('El campo responsable es obligatorio.'),
            // 'cliente_id.required' => __('El campo cliente es obligatorio.'),
            // 'usuario_id.required' => __('El campo usuario es obligatorio.'),
        // ];
    // }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $fileFields = [
                'documento_1' => 'documento 1',
                'documento_2' => 'documento 2',
                'documento_3' => 'documento 3',
                'documento_4' => 'documento 4',
                'documento_5' => 'documento 5',
            ];
            $this->validateFiles($validator, $fileFields);
        });
    }

    public function validateFiles($validator, $fileFields)
    {
        foreach ($fileFields as $fileName => $fileDescription) {
            if ($this->hasFile($fileName)) {
                $allowedMimes = ['jpg', 'jpeg', 'gif', 'png', 'pdf', 'doc', 'docx'];
                if (!in_array($this->file($fileName)->getClientOriginalExtension(), $allowedMimes)) {
                    $validator->errors()->add($fileName, "El campo $fileDescription debe ser un archivo de tipo: " . implode(', ', $allowedMimes) . '.');
                }
            }
        }
    }
}
