<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FechasPorEmpresaCT extends Model
{
    public $timestamps = false;
    use HasFactory;
    protected $fillable = [
        'fecha_vencimiento',
        'nombre',
        'codigo_tributario',
        'NIT',
        'fecha_revision',
        'observacion',
        'detalle_tributario',
        'nombre_detalle'
    ];
    protected $table = 'fechas_por_empresa_calendario_tributario';

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'NIT', 'NIT');
    }
}
