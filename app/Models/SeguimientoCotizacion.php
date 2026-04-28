<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeguimientoCotizacion extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'cotizacion_id',
        'seguimiento_id',
        'fecha_proximo_seguimiento',
        'observacion_proximo_seguimiento',
    ];

    protected $table = 'seguimiento_cotizaciones';

    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class, 'cotizacion_id');
    }

    public function seguimiento()
    {
        return $this->belongsTo(Seguimiento::class, 'seguimiento_id');
    }
}
