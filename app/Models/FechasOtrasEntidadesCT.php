<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FechasOtrasEntidadesCT extends Model
{
    public $timestamps = false;
    use HasFactory;
    protected $fillable = [
        'fecha_vencimiento',
        'nombre',
        'codigo_otraentidad',
        'codigo_tributario',
        'detalle_tributario',
        'nombre_detalle',
        'NIT',
        'fecha_revision',
        'observacion'
    ];
    protected $table = 'fechas_detalles_tributario';

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'NIT', 'NIT');
    }
}
