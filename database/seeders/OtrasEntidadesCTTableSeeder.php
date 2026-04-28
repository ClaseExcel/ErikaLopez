<?php

namespace Database\Seeders;

use App\Models\OtrasEntidadesCT;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OtrasEntidadesCTTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $impuestos = [
            ['codigo' => 900, 'nombre' => 'SUPERSOCIEDADES - INFORME 01 - 01A'],
            ['codigo' => 901, 'nombre' => 'SUPERSOCIEDADES - INFORME 01-30/60'],
            ['codigo' => 902, 'nombre' => 'SUPERSOCIEDADES - INFORME 01-35/65'],
            ['codigo' => 903, 'nombre' => 'DANE'],
            ['codigo' => 904, 'nombre' => 'MINDEPORTES'],
            ['codigo' => 905, 'nombre' => 'CÁMARA DE COMERCIO'],
            ['codigo' => 906, 'nombre' => 'PAGO SEGURIDAD SOCIAL'],
            ['codigo' => 907, 'nombre' => 'SUPERSOCIEDADES - INFORME 04 - ENTIDADES QUE NO CUMPLEN HNM'],
            ['codigo' => 999, 'nombre' => 'NO APLICA'],
            ['codigo' => 908, 'nombre' => 'REPORTE OPERACIONES - BANREPÚBLICA'],
            ['codigo' => 909, 'nombre' => 'INFORMACIÓN FINANCIERA - SUPERINTENDENCIA DE VIGILANCIA'],
            ['codigo' => 910, 'nombre' => 'FONTUR'],
            ['codigo' => 911, 'nombre' => 'ASOHOFRUCOL'],
            ['codigo' => 917, 'nombre' => 'NOMINA ELECTRÓNICA'],
            ['codigo' => 918, 'nombre' => 'GT 010 COMPOSICIÓN PATRIMONIAL SUPERSALUD'],
            ['codigo' => 919, 'nombre' => 'CIRCULAR 030 SUPERSALUD'],
            ['codigo' => 920, 'nombre' => 'CIRCULAR 016 SUPERSALUD'],
            ['codigo' => 921, 'nombre' => 'CIRCULAR 014 SUPERSALUD'],
            ['codigo' => 922, 'nombre' => 'CONTRIBUCIÓN ANUAL SUPERSALUD'],
            ['codigo' => 923, 'nombre' => 'FORMATO 2596 DE CONCILIACIÓN FISCAL'],
            ['codigo' => 924, 'nombre' => 'REPORTE ANUAL DE AUTOEVALUACIÓN DE ESTÁNDARES MÍNIMOS DEL SISTEMA DE GESTION DE SEGURIDAD'],
            ['codigo' => 925, 'nombre' => 'ACTUALIZACIÓN REGISTRO NACIONAL DE BASE DE DATOS'],
            ['codigo' => 926, 'nombre' => 'CERTIFICACIÓN CUMPLIMIENTO PROGRAMA DE SEGURIDAD VIAL'],
            ['codigo' => 927, 'nombre' => ' ACTUALIZACIÓN Y PRESENTACIÓN DE LA MEMORIA ECONÓMICA DEL RÉGIMEN TRIBUTARIO ESPECIAL'],
            ['codigo' => 928, 'nombre' => 'INFORMES FINANCIEROS'],
            ['codigo' => 929, 'nombre' => 'INFORME LOCAL DE PRECIOS DE TRANSFERENCIA'],
            ['codigo' => 930, 'nombre' => 'SUPERSOCIEDADES - ESTADO CUATRIMESTRAL'],
            ['codigo' => 931, 'nombre' => 'RÉGIMEN TRIBUTARIO ESPECIAL'],
            ['codigo' => 932, 'nombre' => 'SUPERSOCIEDADES - INFORME 02 - ESTADOS FINANCIEROS CÁMARAS DE COMERCIO'],
            ['codigo' => 933, 'nombre' => 'SUPERSOCIEDADES - 03A-10 INFORMACIÓN DE ACUERDOS DE RECUPERACIÓN'],
            ['codigo' => 934, 'nombre' => 'RENOVACIÓN REGISTRO ÚNICO DE PROPONENTES - RUP']
        ];
        OtrasEntidadesCT::insert($impuestos);
    }
}
