<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientesMovimientos extends Model
{
    use HasFactory;
    public $table = 'clientesmovimientos';
    protected $fillable = [
        //movimientos siigo local
        'Nit',
        'cuenta_descripcion',
        'cuenta',
        'descripcionct',
        'saldoinicial',
        'comprobante',
        'fecha',
        'nit_sl',
        'nombre',
        'descripcion',
        'inventario_cruce_cheque',
        'base',
        'cc_scc',
        'debitos',
        'creditos',
        'saldo_mov',
        'observacion_sl',
        'fecha_reporte',
        //movimientos siigo web
        'codigo_contable_sw',
        'cuenta_contable_sw',
        'comprobante_sw',
        'secuencia_sw',
        'fecha_elaboracion_sw',
        'identificacion_sw',
        'suc_sw',
        'nombre_tercero_sw',
        'descripcion_sw',
        'detalle_sw',
        'centro_costo_sw',
        'saldo_inicial_sw',
        'debito_sw',
        'credito_sw',
        'saldo_movimiento_sw',
        'salto_total_cuenta_sw',
        'fecha_reporte_sw',
    ];
}
