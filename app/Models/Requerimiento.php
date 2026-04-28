<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requerimiento extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'consecutivo',
        'descripcion',
        'tipo_requerimiento_id',
        'empleado_cliente_id'
    ];

    public static function generarCodigo()
    {
        $ultimoRegistro = self::orderBy('created_at', 'desc')->first();
        $ultimoNumero = $ultimoRegistro ? intval(substr($ultimoRegistro->consecutivo, 2)) : 0;
        $nuevoNumero = $ultimoNumero + 1;

        return 'EC' . str_pad($nuevoNumero, 7, '0', STR_PAD_LEFT);
    }

    protected $table = 'requerimientos';

    public function tipo_requerimientos()
    {
        return $this->belongsTo(TipoRequerimiento::class, 'tipo_requerimiento_id');
    }

    public function seguimiento_requerimiento()
    {
        return $this->belongsTo(SeguimientoRequerimiento::class);
    }

    public function empleado_clientes()
    {
        return $this->belongsTo(EmpleadoCliente::class, 'empleado_cliente_id');
    }
}
