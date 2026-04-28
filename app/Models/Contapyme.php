<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contapyme extends Model
{
    use HasFactory;
    public $table = 'contapyme';
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
