<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartamentosCiudades extends Model
{
    use HasFactory;
    protected $table = 'municipiosciudades';
    protected $fillable = [
        'region',
        'c_digo_dane_del_departamento',
        'departamento',
        'c_digo_dane_del_municipio',
        'municipio',
    ];
}
