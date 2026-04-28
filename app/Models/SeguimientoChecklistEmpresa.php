<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeguimientoChecklistEmpresa extends Model
{
    use HasFactory;

    protected $table = 'seguimiento_checklist_empresas';

    protected $fillable = [
        'checklist_empresa_id',
        'observaciones',
        'actividades_presentadas',
        'mes',
    ];

      public function checklist_empresa()
    {
        return $this->belongsTo(ChecklistEmpresa::class, 'checklist_empresa_id');
    }
}
