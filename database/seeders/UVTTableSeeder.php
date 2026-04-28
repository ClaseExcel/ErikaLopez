<?php

namespace Database\Seeders;

use App\Models\UVT;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UVTTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $uvt = [
            [
                'anio'            => '2024',
                'valor_pesos'     => 47065,
                'salario_minimo'  => 1300000
            ],
            [
                'anio'            => '2025',
                'valor_pesos'     => 49799,
                'salario_minimo'  => 1380600
            ],
        ];    

        UVT::insert($uvt);
    }
}
