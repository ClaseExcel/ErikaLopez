<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CamarasComercio extends Model
{
    use HasFactory;
    protected $table = 'camarascomercio';
    protected $fillable = [
        'codigodane',
        'nombre',
    ];
}
