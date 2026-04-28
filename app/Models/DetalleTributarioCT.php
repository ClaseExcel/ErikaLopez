<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleTributarioCT extends Model
{
    use HasFactory;
    protected $fillable = [
        'codigo',
        'nombre',
    ];
    protected $table = 'detalles_tributario';
}
