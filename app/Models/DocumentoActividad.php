<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentoActividad extends Model
{
    use HasFactory;
    public $timestamps = false; 
    protected $table = 'documentos_actividades';
    protected $fillable = [
        'nombre',
        'actividad_id',
    ];
    public function actividad()
    {
        return $this->belongsTo(ActividadCliente::class, 'actividad_id');
    }
}
