<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeguimientoRequerimiento extends Model
{
    use HasFactory;
    
    public $timestamps = false; 

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'requerimiento_id',
        'user_id',
        'observacion',
        'documento',
        'fecha_vencimiento',
        'estado_requerimiento_id',
        'empresa_id'
    ];

    protected $table = 'seguimiento_requerimientos';

    public function requerimientos()
    {
        return $this->belongsTo(Requerimiento::class,'requerimiento_id');
    }

    public function estado_requerimientos()
    {
        return $this->belongsTo(EstadoRequerimiento::class, 'estado_requerimiento_id');
    }

    public function usuario_responsable()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
}
