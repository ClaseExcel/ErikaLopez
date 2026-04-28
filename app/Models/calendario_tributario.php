<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class calendario_tributario extends Model
{
    use HasFactory;
    protected $table = 'calendario_tributario';
    protected $fillable = [
        'codigo_tributario',
        'detalle_tributario',
        'ultimos_digitos',
        'ultimo_digito',
        'rango_inicial',
        'rango_final',
        'fecha_vencimiento',
        'codigo_municipio',
    ];
}
