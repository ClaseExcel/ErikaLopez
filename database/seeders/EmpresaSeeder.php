<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empresa;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      
        $empresa = [
            [
                'NIT'                => '1111111',
                'razon_social'       => 'Erika Lopez',
                'numero_contacto'    => '311 3581313',
                'correo_electronico' => 'info@erikalopez.com.co',
                'direccion_fisica'   => 'Cali - Colombia.',
                'frecuencia_id'      => 1
            ],             
        ];

        Empresa::insert($empresa);
    }
}
