<?php

namespace App\Models;

use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialActividades extends Model
{
    use HasFactory;
    public $timestamps = false;

     /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'reporte_actividades_id',
        'estado',
        'justificacion',
        'modalidad',
        'fecha_creacion',
    ];

    protected $table = 'historial_actividades';
}
