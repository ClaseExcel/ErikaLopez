<?php

namespace Database\Seeders;

use App\Models\Actividad;
use Illuminate\Database\Seeder;

class ActividadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $actividad = [
            [
                'nombre' => 'Cierre del mes',
            ],
            [
                'nombre' => 'Solicitudes',
            ],
            [
                'nombre' => 'Prioritario',
            ],
            [
                'nombre' => 'Otro',
            ],
        ];

        Actividad::insert($actividad);
    }
}
