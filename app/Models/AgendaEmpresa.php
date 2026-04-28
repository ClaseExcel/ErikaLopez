<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgendaEmpresa extends Model
{
    use HasFactory;

        /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'agenda_id',
        'empresa_id',
    ];

    protected $table = 'agenda_empresas';


    public function agenda()
    {
        return $this->belongsTo(AgendaEmpleado::class, 'agenda_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
}
