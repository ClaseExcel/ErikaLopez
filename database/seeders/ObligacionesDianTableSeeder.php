<?php

namespace Database\Seeders;

use App\Models\ObligacionDian;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ObligacionesDianTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $impuestos =
            [
                ['codigo' => 1, 'nombre' => 'Aporte especial para la administración de justicia'],
                ['codigo' => 2, 'nombre' => 'Gravamen a los Movimientos Financieros (GMF)'],
                ['codigo' => 3, 'nombre' => 'Impuesto al Patrimonio'],
                ['codigo' => 4, 'nombre' => 'Impuesto Sobre la Renta y Complementarios Régimen Tributario Especial'],
                ['codigo' => 5, 'nombre' => 'Impuesto Sobre la Renta y Complementarios Régimen Ordinario - empresas'],
                ['codigo' => 6, 'nombre' => 'Ingresos y patrimonio'],
                ['codigo' => 7, 'nombre' => 'Retención en la Fuente a título de renta'],
                ['codigo' => 8, 'nombre' => 'Retención Timbre Nacional'],
                ['codigo' => 9, 'nombre' => 'Retención en la Fuente en el Impuesto Sobre las Ventas'],
                ['codigo' => 10, 'nombre' => 'Obligado aduanero'],
                ['codigo' => 13, 'nombre' => 'Gran contribuyente'],
                ['codigo' => 14, 'nombre' => 'Informante de Exógena'],
                ['codigo' => 1400, 'nombre' => 'Informante de Exógena - Grandes Contribuyentes'],
                ['codigo' => 15, 'nombre' => 'Autorretenedor'],
                ['codigo' => 16, 'nombre' => 'Obligación de facturar por ingresos de bienes y/o servicios excluidos'],
                ['codigo' => 17, 'nombre' => 'Profesionales de compra y venta de divisas'],
                ['codigo' => 18, 'nombre' => 'Precios de Transferencia'],
                ['codigo' => 19, 'nombre' => 'Productor y/o exportador de bienes exentos'],
                ['codigo' => 20, 'nombre' => 'Obtención NIT'],
                ['codigo' => 21, 'nombre' => 'Declarar el ingreso o salida del país de divisas o moneda legal colombiana'],
                ['codigo' => 22, 'nombre' => 'Obligado a cumplir deberes formales a nombre de terceros'],
                ['codigo' => 23, 'nombre' => 'Agente de retención en el impuesto sobre las ventas'],
                ['codigo' => 24, 'nombre' => 'Declaración Informativa Consolidada Precios de transferencia'],
                ['codigo' => 26, 'nombre' => 'Declaración Informativa Individual Precios de transferencia'],
                ['codigo' => 32, 'nombre' => 'Impuesto Nacional a la Gasolina y al ACPM'],
                ['codigo' => 33, 'nombre' => 'Impuesto Nacional al Consumo'],
                ['codigo' => 36, 'nombre' => 'Establecimiento Permanente'],
                ['codigo' => 39, 'nombre' => 'Proveedor de Servicios Tecnológicos (PST)'],
                ['codigo' => 41, 'nombre' => 'Declaración anual de activos en el exterior - empresas'],
                ['codigo' => 42, 'nombre' => 'Obligado a llevar contabilidad'],
                ['codigo' => 45, 'nombre' => 'Autorretenedor de rendimientos financieros'],
                ['codigo' => 46, 'nombre' => 'IVA Prestadores de Servicios desde el Exterior'],
                ['codigo' => 47, 'nombre' => 'RST - Declaración anual y pago'],
                ['codigo' => 48, 'nombre' => 'Impuesto sobre las ventas (IVA) cuatrimestral'],
                ['codigo' => 481, 'nombre' => 'Impuesto sobre las ventas (IVA) bimestral'],
                ['codigo' => 482, 'nombre' => 'Impuesto sobre las ventas (IVA) anual'],
                ['codigo' => 49, 'nombre' => 'No responsable de IVA'],
                ['codigo' => 50, 'nombre' => 'No responsable de Consumo restaurantes y bares'],
                ['codigo' => 52, 'nombre' => 'Facturador Electrónico'],
                ['codigo' => 53, 'nombre' => 'Persona Jurídica No Responsable de IVA'],
                ['codigo' => 54, 'nombre' => 'Intercambio Automático de Información CRS'],
                ['codigo' => 55, 'nombre' => 'Informante de Beneficiarios Finales'],
                ['codigo' => 56, 'nombre' => 'Impuesto al Carbono'],
                ['codigo' => 58, 'nombre' => 'Intercambio Automático de Información FATCA'],
                ['codigo' => 59, 'nombre' => 'Autorretención especial renta'],
                ['codigo' => 60, 'nombre' => 'Autorretención por concepto de intereses y rendimientos financieros de entidades vigiladas por la Superintendencia Financiera de Colombia'],
                ['codigo' => 61, 'nombre' => 'Autorretención por concepto de comisiones de entidades vigiladas por la Superintendencia Financiera de Colombia'],
                ['codigo' => 471, 'nombre' => 'RST - Anticipo bimestral - Declaración y pago'],
                ['codigo' => 51, 'nombre' => 'Impuesto Sobre la Renta y Complementarios Régimen Ordinario - personas'],
                ['codigo' => 510, 'nombre' => 'Impuesto Sobre la Renta y Complementarios Régimen Ordinario - Grandes contribuyentes'],
                ['codigo' => 62, 'nombre' => 'IMPUESTO NACIONAL SOBRE PRODUCTOS PLÁSTICOS DE UN SOLO USO '],
                ['codigo' => 63, 'nombre' => 'IMPUESTO A LAS BEBIDAS ULTRA PROCESADAS'],
                ['codigo' => 64, 'nombre' => 'IMPUESTO PRODUCTO COMESTIBLE ULTRA PROCESADO INDUSTRIAL'],
                ['codigo' => 600, 'nombre' => 'MEDIOS CAMBIARIOS TRIMESTRAL - INFORMACIÓN EXÓGENA CAMBIARIA'],
                ['codigo' => 411, 'nombre' => 'Declaración anual de activos en el exterior - personas'],
                ['codigo' => 999, 'nombre' => 'No Aplica'],
                ['codigo' => 4001, 'nombre' => 'Declaración anual de activos en el exterior régimen simple de tributación - personas'],
                ['codigo' => 4002, 'nombre' => 'Declaración anual de activos en el exterior régimen simple de tributación - empresas'],
                ['codigo' => 5000, 'nombre' => 'Pago segunda cuota Impuesto Sobre la Renta y Complementarios Régimen Ordinario - empresas'],
                ['codigo' => 4003, 'nombre' => 'Segunda cuota impuesto al patrimonio'],
            ];
        ObligacionDian::insert($impuestos);
    }
}
