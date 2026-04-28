<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistEmpresa extends Model
{
    use HasFactory;

    protected $table = 'checklist_empresas';

    protected $fillable = [
        'empresa_id',
        'actividades',
        'año',
        'user_actualiza_id',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

        public function user_act()
    {
        return $this->belongsTo(User::class, 'user_actualiza_id');
    }
}
