<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoRequerimiento extends Model
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

    protected $table = 'tipo_requerimientos';

    public function requerimientos()
    {
        return $this->belongsTo(Requerimiento::class);
    }
}
