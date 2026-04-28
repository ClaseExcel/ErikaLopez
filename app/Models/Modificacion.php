<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modificacion extends Model
{
    use HasFactory;

    public $table = 'modificaciones';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'compania_id',
        'periodo',
        'movimiento',
        'valor_ajustado',
        'created_at',
        'updated_at',
        'deleted_at',
        'saldo_original',
        'campo_modificado'
    ];

    public function compania()
    {
        return $this->belongsTo(Empresa::class, 'compania_id');
    }
}
