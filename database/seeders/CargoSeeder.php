<?php

namespace Database\Seeders;

use App\Models\Cargo;
use Illuminate\Database\Seeder;

class CargoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cargos = [
            [
                'id'   => 1,
                'nombre'  => 'Contador senior',
            ],
            [
                'id'   => 2,
                'nombre'  => 'Contador junior',
            ],
            [
                'id'   => 3,
                'nombre'  => 'Auxiliar contable',
            ],
            [
                'id'   => 4,
                'nombre'  => 'Asistente Contable',
            ],
            [
                'id'   => 5,
                'nombre'  => 'Cliente',
            ],
        ];

        Cargo::insert($cargos);
    }
}
