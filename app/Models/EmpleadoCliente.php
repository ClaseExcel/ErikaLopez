<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpleadoCliente extends Model
{
    use HasFactory;

    public $timestamps = false; 

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'nombres',
        'apellidos',
        'correo_electronico',
        'correos_secundarios',
        'numero_contacto',
        'user_id',
        'empresa_id',
        'empresas_secundarias'
    ];

    protected $table = 'empleado_clientes';

    public function usuarios()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function empresas()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function citas()
    {
        return $this->belongsTo(Cita::class);
    }
}
