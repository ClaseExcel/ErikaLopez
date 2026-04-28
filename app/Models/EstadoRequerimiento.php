<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoRequerimiento extends Model
{
    use HasFactory;

    public $timestamps = false; 

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'nombre',
    ];

    protected $table = 'estado_requerimientos';

    public function seguimiento_requerimientos()
    {
        return $this->belongsTo(SeguimientoRequerimiento::class);
    }
}
