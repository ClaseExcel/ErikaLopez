<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformesGenericos extends Model
{
    use HasFactory;
    public $table = 'informesgenericos';
    protected $fillable = [
        'Nit',
        'cuenta',
        'descripcion',
        'tercero',
        'nombre',
        'saldo_anterior',
        'debitos',
        'creditos',
        'saldo_final',
        'fechareporte',
    ];
}
