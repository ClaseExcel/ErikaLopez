<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'numero_cotizacion',
        'fecha_envio',
        // 'fecha_vigencia',
        // 'cliente_id',
        'cliente',
        'responsable_id',
        'nombre_contacto',
        'telefono_contacto',
        'servicio_cotizado',
        'precio_venta',
        'observaciones',
        'cotizacion_adjunta',
        'fecha_primer_seguimiento',
        'observacion_primer_seguimiento',
        'estado_cotizacion',
        'prospecto_cliente',
        'correo_contacto',
        'linea_negocio'
    ];

    protected $table = 'cotizaciones';

    // public function cliente()
    // {
    //     return $this->belongsTo(Empresa::class, 'cliente_id');
    // }

    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }
}
