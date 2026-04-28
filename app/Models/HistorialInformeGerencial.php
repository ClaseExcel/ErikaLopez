<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialInformeGerencial extends Model
{
    use HasFactory;
    protected $table = 'historial_informe_gerencial';
    public $timestamps = false;

    protected $fillable = [
        'id_empresa',
        'fecha_inicial',
        'fecha_final',
        'seccion',
        'url_imagen',
        'descripcion',
        'observaciones',
    ];
}
