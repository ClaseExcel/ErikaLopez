<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CreateEmpresaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('CREAR_EMPRESAS');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'NIT' => ['required', 'string', 'max:255', 'unique:empresas'],
            'razon_social' => ['required', 'string', 'max:255'],
            'numero_contacto' => ['required'],
            'correo_electronico' => ['required'],
            'correos_secundarios' => [
                function ($attribute, $value, $fail) {
                    if ($value) { // Verifica si el valor no está vacío
                        $emails = explode(',', $value);
                        foreach ($emails as $email) {
                            if (!filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
                                $fail("El $attribute contiene un correo inválido: $email");
                            }
                        }
                    }
                },
            ],
            'direccion_fisica' => ['required'],
            'frecuencia_id' => ['required', 'not_in:0'],
            'rut' => ['required_if:tipocliente,empresa', 'file', 'mimes:pdf'],
            'telefono_contacto' => ['required'],
            'nombre_contacto' => ['required'],
            'camaracomercio_id' => ['required_if:tipocliente,empresa'],
            'contrato' => ['file', 'mimes:pdf'],
            'empleados' => ['required'],
            'tipocliente' => ['required'],
            'codigo_obligacionmunicipal' => ['required'],
            'otras_entidades' =>['required'],
        ];
    }


    public function messages()
    {
        return [
            'NIT.required' => __('Debes ingresar un NIT.'),
            'nombre_contacto.required' => __('Debes ingresar un nombre de contacto.'),
            'telefono_contacto.required' => __('Debes ingresar un número de contacto.'),
            'NIT.unique' => __('El NIT de esta empresa ya existe.'),
            'razon_social.required' => __('Debes ingresar una razón social.'),
            'direccion_fisica.required' => __('Debes ingresar una dirección.'),
            'frecuencia_id.not_in' => __('Debes seleccionar su frecuencia.'),
            'numero_contacto.required' => __('El numero de contacto es requerido.'),
            'correo_electronico.required' => __('El correo électronico es requerido.'),
            'rut.required_if' => __('Debes adjuntar un archivo PDF.'),
            'rut.mimes' => __('El archivo adjunto debe ser un PDF.'),
            // 'obligacionesmunicipales.required_if' => __('Debes agregar mínimo un Departamento y un municipio'),
            'camaracomercio_id.required_if' => __('Debe seleccionar cámara y comercio'),
            'empleados' => __('Debe seleccionar un empleado'),
            'tipocliente' => __('Debe seleccionar un tipo cliente'),
            'codigo_obligacionmunicipal' => __('Debe seleccionar minimo una obligación municipal'),
            'otras_entidades' =>__('Debe seleccionar minimo una'),
            'correos_secundarios' =>__('Debe ingresar los correos separados por comas y correctamente escritos'),
        ];
    }
}
