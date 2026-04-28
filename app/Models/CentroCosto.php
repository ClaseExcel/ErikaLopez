<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentroCosto extends Model
{
    public $table = 'centros_costos';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'codigo',
        'nombre',
        'estado',
        'compania_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function compania()
    {
        return $this->belongsTo(Empresa::class, 'compania_id');
    }
}
