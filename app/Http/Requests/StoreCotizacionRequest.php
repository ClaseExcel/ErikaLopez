<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreCotizacionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('CREAR_COTIZACIONES');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'fecha_envio' => 'required',
            // 'fecha_vigencia' => 'required',
            'cliente' => 'required',
            'responsable_id' => 'required',
            'nombre_contacto' => 'required',
            'telefono_contacto' => 'required',
            'servicio_cotizado' => 'required',
            'precio_venta' => 'required',
            'fecha_primer_seguimiento' => 'required',
            'observacion_primer_seguimiento' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'fecha_envio.required' => __('Por favor ingresa una fecha de envio.'),
            // 'fecha_vigencia.required' => __('Por favor ingresa una fecha de vigencia.'),
            'cliente.required' => __('Ingresa un cliente.'),
            'responsable_id.required' => __('Selecciona un responsable.'),
            'nombre_contacto.required' => __('El cliente debe tener un nombre del contacto.'),
            'telefono_contacto.required' => __('El cliente debe tener un número del contacto.'),
            'servicio_cotizado.required' => __('Ingresa el servicio que esta cotizando el cliente.'),
            'precio_venta.required' => __('Ingresa el precio de la cotización.'),
            'fecha_primer_seguimiento.required' => __('Selecciona la fecha del primer seguimiento.'),
            'observacion_primer_seguimiento.required' => __('Ingresa la observación del primer seguimiento.'),
        ];
    }
}
