<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gestion extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'fecha_visita',
        'tipo_visita',
        'detalle_visita',
        'compromisos',
        'compromisos_cliente',
        'asistentes_help',
        'asistentes_cliente',
        'hallazgos',
        'user_create_gestion_id',
        'user_update_gestion_id',
        'cliente_id',
        'documento_uno',
        'documento_dos',
        'documento_tres',
        'documento_cuatro',
        'documento_cinco',
        'documento_seis'
    ];

    protected $table = 'gestiones';

    public function usuario_create()
    {
        return $this->belongsTo(User::class, 'user_create_gestion_id')->withTrashed();
    }

    public function usuario_update()
    {
        return $this->belongsTo(User::class, 'user_update_gestion_id')->withTrashed();
    }

    public function cliente()
    {
        return $this->belongsTo(Empresa::class, 'cliente_id');
    }

}
