<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Actividad>
 */
class ActividadClienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nombre' => 'Omnis quo excepturi',
            'actividad_id' =>  '3',
            'progreso' => '0',
            'prioridad' =>  'NO',
            'fecha_vencimiento' => '2023-11-23',
            'periodicidad' =>  'NULL',
            'periodicidad_corte' => '0',
            'recordatorio' =>  '49',
            'recordatorio_distancia' => '15',
            'nota' => 'Do quia provident e',
            'responsable_id' => '2',
            'cliente_id' => '1',
            'usuario_id' => '1',
            'reporte_actividad_id' =>'1',
            'empresa_asociada_id' => '1', 
            'file_documento_1' => 'NULL',
            'file_documento_2' => 'NULL',
            'file_documento_3' => 'NULL',
            'file_documento_4' => 'NULL',
            'file_documento_5' => 'NULL',
        ];
    }
}
