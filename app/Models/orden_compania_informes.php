<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class orden_compania_informes extends Model
{
    use HasFactory;
    protected $table = 'ordeninformescompania';
    protected $fillable = [
        'nit', // Asegúrate de incluir 'nit' aquí
        'orden',
    ];
}
