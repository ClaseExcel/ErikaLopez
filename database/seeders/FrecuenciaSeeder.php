<?php

namespace Database\Seeders;

use App\Models\Frecuencia;
use Illuminate\Database\Seeder;

class FrecuenciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $frecuencias = [
            [
                'id'   => 1,
                'nombre'  => 'Recurrente',
            ],
            [
                'id'   => 2,
                'nombre'  => 'Ocasional',
            ]
        ];

        Frecuencia::insert($frecuencias);
    }
}
