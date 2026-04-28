<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AgendaEmpleado extends Model
{
    use HasFactory;

    public $timestamps = false; 

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'fecha_disponibilidad',
        'hora_inicio',
        'hora_fin',
        'user_id',
        // 'empresa_id',
    ];

    protected $table = 'agenda_empleados';

    public function usuarios()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    // public function clientes()
    // {
    //     return $this->belongsTo(Empresa::class, 'empresa_id');
    // }


    public function empresas()
    {
        return $this->belongsToMany(Empresa::class, 'agenda_empresas');
    }

    public function citas()
    {
        return $this->belongsTo(Cita::class);
    }

    // public function getFechaDisponibilidadAttribute($fecha_disponibilidad){
    //     $fechaFormateada = Carbon::parse($fecha_disponibilidad)->format('d-m-Y');
    //     return $fechaFormateada;
    // }

     public function getHoraInicioAttribute($hora_inicio){
        $horaFormateada = Carbon::parse($hora_inicio)->format('h:i a');
        return $horaFormateada;
    }

    public function getHoraFinAttribute($hora_fin){
        $horaFormateada = Carbon::parse($hora_fin)->format('h:i a');
        return $horaFormateada;
    }

}
