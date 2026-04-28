<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class   ActividadCliente extends Model
{
    use HasFactory;

    protected $table = 'actividad_cliente';

    protected $fillable = [
        'nombre',
        'actividad_id',
        'progreso',
        'prioridad',
        'fecha_vencimiento',
        'periodicidad',
        'periodicidad_corte',
        'recordatorio',
        'recordatorio_distancia',
        'nota',
        'responsable_id',
        'cliente_id',
        'usuario_id',
        'reporte_actividad_id',
        'empresa_asociada_id',
        'file_documento_1',
        'file_documento_2',
        'file_documento_3',
        'file_documento_4',
        'file_documento_5',
        'user_crea_act_id',
        'user_update_act_id',
        'notificado',
        'documentos_notificacion'
    ];

    // public function getFechaVencimientoAttribute($fecha_vencimiento){
    //     $fechaFormateada = Carbon::parse($fecha_vencimiento)->format('d-m-Y');
    //     return $fechaFormateada;
    // }

    public function actividad()
    {
        return $this->belongsTo(Actividad::class);
    }

    public function responsable()
    {
        return $this->belongsTo(Responsable::class, 'responsable_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Empresa::class, 'cliente_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function reporte_actividades()
    {
        return $this->belongsTo(ReporteActividad::class, 'reporte_actividad_id');
    }

    public function estado_actividades()
    {
        return $this->belongsTo(EstadoActividad::class);
    }
    
    public function empresa_asociada()
    {
        return $this->belongsTo(Empresa::class, 'empresa_asociada_id');
    }

    public function usuario_crea_act()
    {
        return $this->belongsTo(User::class,'user_crea_act_id');
    }

    public function usuario_update_act()
    {
        return $this->belongsTo(User::class,'user_update_act_id');
    }


 


}
