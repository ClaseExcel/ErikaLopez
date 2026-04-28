<?php

namespace Database\Seeders;

use App\Models\Responsable;
use Illuminate\Database\Seeder;

class ResponsableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $responsable = [
            [
                'nombre' => 'Externa',
            ],
            [
                'nombre' => 'Interna',
            ],
        ];

        Responsable::insert($responsable);
    }
}
