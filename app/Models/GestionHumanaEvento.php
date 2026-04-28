<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GestionHumanaEvento extends Model
{
    use HasFactory;

    protected $table = 'gestion_humana_eventos';

    protected $fillable = [
        'gestion_humana_id',
        'concepto_id',
        'fecha_inicio',
        'fecha_fin',
        'observacion',
    ];

    public function concepto()
    {
        return $this->belongsTo(Concepto::class, 'concepto_id');
    }

    public function gestionHumana()
    {
        return $this->belongsTo(GestionHumana::class, 'gestion_humana_id')->withTrashed();
    }
}
