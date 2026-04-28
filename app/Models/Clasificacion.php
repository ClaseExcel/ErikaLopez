<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clasificacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'anio',
        'regimen_simple_tributacion',
        'ingresos_gravados',
        'ingresos_exentos',
        'ingresos_excluidos',
        'ingresos_no_gravados',
        'devoluciones',
        'total_ingresos',
        'actividad_1',
        'actividad_2',
        'actividad_3',
        'actividad_4',
        'operaciones_excentas',
        'actividades_exp_imp',
        'gran_contribuyente',
        'ingresos_brutos_fiscales_anio_anterior',
        'formato_conciliacion_fiscal',
        'activos_brutos_diciembre_anio_anterior',
        'ingreso_brutos_diciembre_anio_anterior',
        'revisor_fiscal',
        'patrimonio_brutos_diciembre_anio_anterior',
        'ingreso_brutos_tributario_diciembre_anio_anterior',
        'declaracion_tributaria_firma_contador',
        'empresa_id'
    ];

    protected $table = 'clasificaciones';

    public function empresas()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
}
