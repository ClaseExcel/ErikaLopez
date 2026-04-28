<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GestionHumana extends Model
{
    use HasFactory;

    protected $table = 'gestion_humana';
    use SoftDeletes;

    protected $fillable = [
        'empresa_id',
        'documento_cedula',
        'certificado_eps',
        'documento_contrato',
        'nombres',
        'cedula',
        'cargo',
        'salario',
        'auxilio_transporte',
        'bonificacion',
        'direccion',
        'municipio_residencia',
        'telefono',
        'correo_electronico',
        'tipo_contrato',
        'fecha_ingreso',
        'fecha_finalizacion',
        'eps',
        'afp',
        'cesantias',
        'arl',
        'fecha_nacimiento',
        'estado_civil',
        'numero_beneficiarios',
        //
        'certificado_ccf',
        'certificado_arl',
        'certificado_afp',
        'ccf',
        //
        'tipo_identificacion',
        'estado',
        //
        'certificado_retiro_arl',
        'certificado_retiro_caja',
        'liquidacion',
    ];

        public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
}
