<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetasEmpresa extends Model
{
    use HasFactory;

    protected $table = 'metas_empresas';

    protected $fillable = [
        'periodo',
        'empresa_id',
        'valor',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
}
