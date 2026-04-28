<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FechasExistentesIC extends Model
{
    protected $table = 'fechas_existentes_i_c_s'; // Si no lo pones, Laravel lo infiere, pero por claridad puede ayudar
    use HasFactory;
    protected $fillable = ['fecha_creacion', 'user_crea_id', 'empresa_id'];
    protected $casts = [
        'fecha_creacion' => 'date',
    ];
    // Relación con el usuario que creó el registro
    public function creador()
    {
        return $this->belongsTo(User::class, 'user_crea_id');
    }

    // Relación con la empresa
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
}
