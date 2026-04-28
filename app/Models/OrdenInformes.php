<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenInformes extends Model
{
    use HasFactory;
    public $table = 'ordeninformes';
    protected $fillable = [
        'agrupador_cuenta',
        'nombre',
        'nit',
        'tipo',
    ];
}
