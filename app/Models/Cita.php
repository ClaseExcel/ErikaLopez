<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    const vigente = 1;
    const reservado = 2;
    const cancelado = 3;

    public $timestamps = false; 

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'motivo',
        'fecha_inicio',
        'fecha_fin',
        'direccion',
        'link',
        'observacion',
        'estados',
        // 'empleado_cliente_id',
        'modalidad_id',
        'agenda_id',
    ];

    protected $table = 'citas';

    public function empleadoCliente()
    {
        return $this->belongsTo(EmpleadoCliente::class, 'empleado_cliente_id');
    }

    public function modalidades()
    {
        return $this->belongsTo(Modalidad::class, 'modalidad_id');
    }

    public function agenda_empresa()
    {
        return $this->belongsTo(AgendaEmpresa::class, 'agenda_id');
    }
}
