<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoRequerimiento;

class TipoRequerimientoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipo_requerimientos = [
            [
                'id'   => 1,
                'nombre'  => 'Novedades software contable',
            ],
            [
                'id'   => 2,
                'nombre'  => 'Asesoria contable',
            ],
            [
                'id'   => 3,
                'nombre'  => 'Asesoria tributaria y/o aduanera',
            ],
            [
                'id'   => 4,
                'nombre'  => 'Asesoria comercial y/o societaria',
            ],  
            [
                'id'   => 5,
                'nombre'  => 'Revisoria fiscal y/o auditoria',
            ],
            [
                'id'   => 6,
                'nombre'  => 'Otro',
            ],
        ];

        TipoRequerimiento::insert($tipo_requerimientos);
    }
}
