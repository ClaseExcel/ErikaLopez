<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ReporteActividad extends Model
{
    use HasFactory;

    public $timestamps = false; 

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'actividad_cliente_id',
        'estado_actividad_id',
        'fecha_inicio',
        'justificacion',
        'recomendacion',
        'documento',
        'fecha_fin',
    ];

    protected $table = 'reporte_actividades';

    public function actividad_clientes()
    {
        return $this->belongsTo(ActividadCliente::class, 'actividad_cliente_id');
    }

    public function estado_actividades()
    {
        return $this->belongsTo(EstadoActividad::class, 'estado_actividad_id');
    }

    // public function getFechaInicioAttribute($fecha_inicio){
    //     $fechaFormateada = Carbon::parse($fecha_inicio)->format('d-m-Y');
    //     return $fechaFormateada;
    // }

}
