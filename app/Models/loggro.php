<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class loggro extends Model
{
    use HasFactory;
    public $table = 'loggro';
    protected $fillable = [
        'Nit',
        'cuenta',
        'descripcion',
        'saldo_anterior',
        'debitos',
        'creditos',
        'neto',
        'saldo_final',
        'fechareporte',
    ];
}
