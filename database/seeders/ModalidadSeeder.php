<?php

namespace Database\Seeders;

use App\Models\Modalidad;
use Illuminate\Database\Seeder;

class ModalidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modalidades = [
            [
                'id'   => 1,
                'nombre'  => 'Virtual',
            ],
            [
                'id'   => 2,
                'nombre'  => 'Presencial',
            ],
        ];

        Modalidad::insert($modalidades);
    }
}
