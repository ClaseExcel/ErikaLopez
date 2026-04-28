<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;
    
    public $timestamps = false; 

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'documentos',
        'requerimiento_id',
    ];

    protected $table = 'documentos';

    public function requerimientos()
    {
        return $this->belongsTo(Requerimiento::class, 'requerimiento_id');
    }
}
