<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Begranda extends Model
{
    use HasFactory;
    public $table = 'begranda';
    protected $fillable = [
        'Nit',
        'cuenta',
        'descripcion',
        'saldo_anterior',
        'debitos',
        'creditos',
        'saldo_final',
        'fechareporte',
    ];
}
