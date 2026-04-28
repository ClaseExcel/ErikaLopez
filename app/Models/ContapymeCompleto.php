<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContapymeCompleto extends Model
{
    use HasFactory;
    public $table = 'contapyme_completo';
    protected $fillable = [
        'Nit',
        'cuenta',
        'descripcion',
        'saldo_anterior',
        'debitos',
        'creditos',
        'nuevo_saldo',
        'fechareporte',
    ];
}
