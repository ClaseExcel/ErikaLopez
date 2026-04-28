<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificacionesGestion extends Model
{
    protected $table = 'notificacionesgestion'; // nombre correcto de la tabla

    protected $fillable = [
        'gestion_id',
        'usuario_id',
        'fecha_envio',
        'observacion',
    ];

    // Relación con Gestión
    public function gestion()
    {
        return $this->belongsTo(Gestion::class);
    }

    // Relación con Usuario
    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
}
