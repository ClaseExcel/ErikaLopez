<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EstadoRequerimiento;

class EstadoRequerimientoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $estado = [
            [
                'id'   => 1,
                'nombre'  => 'Enviado',
            ],
            [
                'id'   => 2,
                'nombre'  => 'Aceptado',
            ],
            [
                'id'   => 3,
                'nombre'  => 'Rechazado',
            ],
            [
                'id'   => 4,
                'nombre'  => 'En proceso',
            ],  
            [
                'id'   => 5,
                'nombre'  => 'Finalizado',
            ],
            [
                'id'   => 6,
                'nombre'  => 'Desistió',
            ],
            [
                'id'   => 7,
                'nombre'  => 'Vencido',
            ],
        ];

        EstadoRequerimiento::insert($estado);
    }
}
