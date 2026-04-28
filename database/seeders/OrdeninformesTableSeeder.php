<?php

namespace Database\Seeders;

use App\Models\EstadosFinancieros;
use App\Models\OrdenInformes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrdeninformesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                
                'agrupador_cuenta' => 4135,
                'nombre' => 'VENTAS',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 4175,
                'nombre' => 'Devoluciones en ventas',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 6135,
                'nombre' => 'COSTO  MERCANCIA VENDIDA',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 5205,
                'nombre' => 'Gastos de Personal',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 5210,
                'nombre' => 'Honorarios',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 5215,
                'nombre' => 'Impuestos Indirectos',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 5220,
                'nombre' => 'Arrendamientos',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 5225,
                'nombre' => 'Contribuciones y afiliaciones',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 5230,
                'nombre' => 'Seguros',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 5235,
                'nombre' => 'Servicios',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 5240,
                'nombre' => 'Gastos Legales',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 5245,
                'nombre' => 'Mantto. Ed. Y Equipos ',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 5250,
                'nombre' => 'Mantto. Ed. Y Equipos ',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 5255,
                'nombre' => 'Gatos de viaje',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 531596,
                'nombre' => 'Gatos de viaje',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 5260,
                'nombre' => 'Depreciacion',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 5295,
                'nombre' => 'Diversos',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 42,
                'nombre' => 'OTROS INGRESOS',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 613541,
                'nombre' => 'OTROS INGRESOS',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 53,
                'nombre' => 'OTROS EGRESOS',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 531596,
                'nombre' => 'OTROS EGRESOS',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 523505,
                'nombre' => 'Aseo y Vigilancia',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 523525,
                'nombre' => 'Acueducto y alcantarillado',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 523530,
                'nombre' => 'Teléfono',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 523540,
                'nombre' => 'Propaganda,correos y portes',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 523560,
                'nombre' => 'Propaganfa,correos y portes',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 523530,
                'nombre' => 'Teléfono',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 523550,
                'nombre' => 'Fletes',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 523551,
                'nombre' => 'Fletes',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 523552,
                'nombre' => 'Fletes',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 421005,
                'nombre' => 'Intereses',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 421040,
                'nombre' => 'Descuentos Comerciales',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 423582,
                'nombre' => 'Fletes',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 423580,
                'nombre' => 'Fletes',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 4250,
                'nombre' => 'Recuperaciones',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 613541,
                'nombre' => 'Bonificacion',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 429546,
                'nombre' => 'Bonificacion',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 4295,
                'nombre' => 'Otros Ingresos',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 530505,
                'nombre' => 'Gatos bancarios',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 530515,
                'nombre' => 'Comisiones',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 530520,
                'nombre' => 'Intereses',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 530535,
                'nombre' => 'DESCUENTOS COMERCIALES CONDICIONADOS',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 531515,
                'nombre' => 'COSTOS Y GASTOS DE EJERCICIOS ANTERIORES',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 539525,
                'nombre' => 'Donaciones',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 539595,
                'nombre' => 'Otros',
                'nit' => 9017429812,
            ],
            [
                
                'agrupador_cuenta' => 531595,
                'nombre' => 'Otros',
                'nit' => 9017429812,
            ],



        ];

        OrdenInformes::insert($roles);
    }
}
