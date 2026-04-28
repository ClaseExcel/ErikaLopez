<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodigoCiiu extends Model
{
    use HasFactory;
    protected $fillable = [
        'codigo',
        'nombre',
    ];
    protected $table = 'codigo_ciiu';
}
