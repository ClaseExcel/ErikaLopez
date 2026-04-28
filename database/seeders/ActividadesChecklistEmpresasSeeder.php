<?php

namespace Database\Seeders;

use App\Models\ActividadesChecklist;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActividadesChecklistEmpresasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $actividad = [
            ['nombre' => 'SOLICITUD PAPELERÍA'],
            ['nombre' => 'RECIBO DE PAPELERÍA'],
            ['nombre' => 'TOKEN DIAN'],
            ['nombre' => 'REPORTE DE IMPORTACIONES'],
            ['nombre' => 'REPORTE DE EXPORTACIONES'],
            ['nombre' => 'CAUSACIÓN INGRESOS'],
            ['nombre' => 'CAUSACIÓN COMPRAS Y GASTOS'],
            ['nombre' => 'RETENCIÓN SALARIOS'],
            ['nombre' => 'EXTRACTO BANCARIO CUENTAS DE AHORROS'],
            ['nombre' => 'EXTRACTO BANCARIO CUENTAS CORRIENTES'],
            ['nombre' => 'EXTRACTO CRÉDITOS BANCARIOS'],
            ['nombre' => 'EXTRACTO FIDUCUENTA'],
            ['nombre' => 'EXTRACTO TARJETAS DE CRÉDITO'],
            ['nombre' => 'EXTRACTO CDT'],
            ['nombre' => 'EXTRACTOS FONDOS DE INVERSIÓN'],
            ['nombre' => 'CONCILIACIÓN BANCARIA CUENTAS DE AHORROS'],
            ['nombre' => 'CONCILIACIÓN BANCARIA CUENTAS CORRIENTES'],
            ['nombre' => 'CONCILIACIÓN CRÉDITOS BANCARIOS'],
            ['nombre' => 'CONCILIACIÓN FIDUCUENTA'],
            ['nombre' => 'CONCILIACIÓN TARJETAS DE CRÉDITO'],
            ['nombre' => 'CONCILIACIÓN CDT'],
            ['nombre' => 'CONCILIACIÓN FONDOS DE INVERSIÓN'],
            ['nombre' => 'ANEXO IMPUESTOS SISTEMA CONTABLE'],
            ['nombre' => 'ANEXO 350 RETENCIÓN EN LA FUENTE'],
            ['nombre' => 'ANEXO 300 IVA'],
            ['nombre' => 'CAUSACIÓN DE IMPUESTOS'],
            ['nombre' => 'PAGO DE IMPUESTOS'],
            ['nombre' => 'CAUSACIÓN NÓMINA Y PROVISIÓN LABORAL'],
            ['nombre' => 'LIQUIDACIÓN DE EMPLEADOS'],
            ['nombre' => 'REPORTE CUENTAS POR COBRAR'],
            ['nombre' => 'REPORTE CUENTAS POR COBRAR A EMPLEADOS'],
            ['nombre' => 'REPORTE CUENTAS POR PAGAR'],
            ['nombre' => 'REPORTE DE MOVIMIENTOS BANCARIOS PENDIENTES POR IDENTIFICAR'],
            ['nombre' => 'CONCILIACIÓN SEGURIDAD SOCIAL'],
            ['nombre' => 'AMORTIZACIONES'],
            ['nombre' => 'DEPRECIACIONES'],
            ['nombre' => 'COSTEO DE VENTAS'],
            ['nombre' => 'PROVISIONES (RENTA E ICA)'],
            ['nombre' => 'CONCILIACIÓN CAJAS'],
            ['nombre' => 'REVISIÓN BALANCE DE PRUEBA'],
            ['nombre' => 'REVISIÓN CONTADOR'],
            ['nombre' => 'PRESENTACIÓN DE IMPUESTOS'],
            ['nombre' => 'INFORMES MES'],
            ['nombre' => 'ESTADOS FINANCIEROS'],
            ['nombre' => 'PROYECCIÓN FINANCIERA Y FISCAL'],
            ['nombre' => 'TALENTO HUMANO'],
            ['nombre' => 'SOLICITAR DOCUMENTOS AFILIACIÓN'],
            ['nombre' => 'REALIZAR AFILIACIÓN'],
            ['nombre' => 'SOLICITAR NOVEDADES NÓMINA'],
            ['nombre' => 'REALIZAR NÓMINA'],
            ['nombre' => 'ENVIAR NÓMINA'],
            ['nombre' => 'EMITIR NÓMINA ELECTRÓNICA'],
            ['nombre' => 'SOLICITAR NOVEDADES PLANILLA'],
            ['nombre' => 'REALIZAR PLANILLA'],
            ['nombre' => 'ENVIAR PLANILLA'],
            ['nombre' => 'LIQUIDACIÓN DE CONTRATOS DE EMPLEADOS'],
            ['nombre' => 'TRANSCRIPCIÓN Y RADICACIÓN DE INCAPACIDADES'],
            ['nombre' => 'LIQUIDACIÓN DE PRESTACIONES SOCIALES'],
            ['nombre' => 'PUBLICACIÓN DE VACANTES'],
            ['nombre' => 'CERTIFICADOS LABORALES'],
            ['nombre' => 'CREACIÓN DE PORTALES ADMINISTRADORAS'],
            ['nombre' => 'ENVÍO DE COMUNICADOS (ACTUALIZACIONES NORMATIVIDAD)'],
            ['nombre' => 'RETIRO DE EMPLEADOS A SEGURIDAD SOCIAL'],
            ['nombre' => 'JURÍDICA'],
            ['nombre' => 'REALIZAR CONTRATOS DE VINCULACIÓN LABORAL'],
            ['nombre' => 'REALIZAR CONTRATOS CIVIL COMERCIAL'],
            ['nombre' => 'PROCESOS JURÍDICOS EN DERECHO PRIVADO'],
            ['nombre' => 'PROCESOS DE DESCARGOS'],
            ['nombre' => 'CREACIÓN DE EMPRESAS ANTE CÁMARA DE COMERCIO'],
            ['nombre' => 'REGISTRO INFORMACIÓN TRIBUTARIA (RIT ALCALDÍA)'],
            ['nombre' => 'RENOVACIONES CÁMARA DE COMERCIO'],
            ['nombre' => 'MUTACIONES DE CÁMARA DE COMERCIO'],
            ['nombre' => 'ASAMBLEAS ORDINARIAS'],
            ['nombre' => 'ASAMBLEAS EXTRAORDINARIAS'],
            ['nombre' => 'RECUPERACIÓN DE CARTERA'],
            ['nombre' => 'PROCESOS JURÍDICOS EN DERECHO PÚBLICO (ADMINISTRATIVO)'],
            ['nombre' => 'VENCIMIENTOS DE CONTRATOS Y ENVÍO DE PREAVISOS'],
            ['nombre' => 'SOPORTE EN REDACCIÓN DE DOCUMENTOS'],
        ];

        ActividadesChecklist::insert($actividad);
    }
}
