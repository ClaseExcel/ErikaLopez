<?php

namespace Database\Seeders;

use App\Models\Concepto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConceptoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $concepto = [
            ['nombre' => 'AUSENCIA SIN JUSTIFICAR'],
            ['nombre' => 'CALAMIDAD'],
            ['nombre' => 'CAMBIO DE IBC'],
            ['nombre' => 'INCA. COMÚN'],
            ['nombre' => 'INCA. LABORAL'],
            ['nombre' => 'INGRESOS'],
            ['nombre' => 'LIC. MATERNIDAD'],
            ['nombre' => 'LIC. NO REMUNERADA'],
            ['nombre' => 'LIC. PATERNIDAD'],
            ['nombre' => 'LIC. REMUNERADA'],
            ['nombre' => 'LIC. POR LUTO'],
            ['nombre' => 'RETIROS'],
            ['nombre' => 'SUSPENSION'],
            ['nombre' => 'VACACIONES'],
            ['nombre' => 'OTROS']
        ];

        Concepto::insert($concepto);
    }
}
