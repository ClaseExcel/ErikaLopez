<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
    use HasFactory;
    public $table = 'clientes';
    protected $fillable = [
        'Nit',
        'cuenta',
        'subcuenta',
        'auxiliar',
        'subauxiliar',
        'sucursal',
        'descripcion',
        'ultimo_movimiento',
        'saldo_anterior',
        'debitos',
        'creditos',
        'nuevo_saldo',
        'fechareporte',
        'nivel_ga',
        'transacional_ga',
        'codigo_cuenta_contable_ga',
        'nombre_cuenta_contable_ga',
        'saldo_inicial_ga',
        'movimiento_debito_ga',
        'movimiento_credito_ga',
        'saldo_final_ga',
        'fechareporte_ga',
    ];
}
