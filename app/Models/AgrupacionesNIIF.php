<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgrupacionesNIIF extends Model
{
    use HasFactory;
    protected $table = 'agrupaciones';

    protected $fillable = [
        'codigo',
        'descripcion',
        'mensaje',
        'gruponiif',
    ];
}
