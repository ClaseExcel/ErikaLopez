<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContapymeBalance extends Model
{
    use HasFactory;
    public $table = 'contapyme_balance';
    protected $fillable = [
            'Nit',
            'CodCuentaNivel1',
            'CodCuentaNivel2',
            'CodCuentaNivel3',
            'CodCuentaNivel4',
            'CodCuentaNivel5',
            'SaldoInicialDet5',
            'TotalDebitosDet5',
            'TotalCreditosDet5',
            'SaldoFinalDet5',
            'Cuenta_o_Tercero',
            'SaldoInicialDet',
            'TotalDebitosDet',
            'TotalCreditosDet',
            'SaldoFinalDet',
            'fechareporte',
    ];
}
