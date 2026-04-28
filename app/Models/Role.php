<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory;
    
    const ROLE_SUPERADMIN = 1;
    const ROLE_ADMINISTRADOR = 2;
    const ROLE_CONTADORSENIOR = 3;
    const ROLE_CONTADORJUNIOR = 4;
    const ROLE_ANALISTA = 5;
    const ROLE_AUDITOR = 6;
    const ROLE_CLIENTE = 7;
    const ROLE_GENERICO = 8;

    public $table = 'roles';
    public $timestamps = false;

    protected $fillable = [
        'title',
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
