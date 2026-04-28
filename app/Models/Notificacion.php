<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_empresa', 
        'fecha_vencimiento', 
        'obligacion', 
        'observacion_correo', 
        'notificacion_pdf',
        'usuario',
        'correos'
    ];
    protected $table = 'notificaciones';

    public function user()
    {
        return $this->belongsTo(User::class, 'usuario');
    }
}
