<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObligacionDian extends Model
{
    use HasFactory;
    protected $fillable = [
        'codigo',
        'nombre',
    ];
    protected $table = 'obligacionesdian';
}
