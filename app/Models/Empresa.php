<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;

class Empresa extends Model
{
    use HasFactory;
    use Notifiable;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'NIT',
        'razon_social',
        'correo_electronico',
        'correos_secundarios',
        'notifica_calendario',
        'numero_contacto',
        'direccion_fisica',
        'frecuencia_id',
        'rut',
        'obligaciones',
        'obligacionesmunicipales',
        'camaracomercio_id',
        'contrato',
        'empleados',
        'tipocliente',
        'Cedula',
        'ciiu',
        'dv',
        'contrasenadian',
        'preguntadian',
        'firmadian',
        'camaracomercioclaveportal',
        'firmacamaracomercio',
        'usuario_ica',
        'icaclaveportal',
        'arl',
        'clavearl',
        'aportes',
        'ccf',
        'usuario_clave_eps',
        'usuario_clave_ugpp',
        'usuario_fac_nomina',
        'clave_fact_nomina',
        'usuario_sistema_contable',
        'clave_sistema_contable',
        'codigo_obligacionmunicipal',
        'otras_entidades',
        'estado',
        'camara_comercio_establecimientos',
        'sigla',
        'ciiu_municipios',
        'nombre_contacto',
        'telefono_contacto',
        'telefonos_secundarios',
        'representantelegal',
        'contador',
        'firmarepresentante',
        'revisorfiscal',
        'cedularevisor',
        'firmarevisorfiscal',
        'logocliente',
        'actividadeconomica',
        'iva_generado_codigo_1',
        'iva_generado_codigo_2',
        'iva_generado_codigo_3',
        'iva_descontable_codigo_1',
        'iva_descontable_codigo_2',
        'iva_descontable_codigo_3',
        'iva_descontable_codigo_4',
        'tipo',
        'gruponiif',
        'empresaasociada', //campo para fusionar informes contables de dos empresas
        'operador', //define si los valores se restan o se suman con los de la empresa asociada 
    ];

    protected $table = 'empresas';

    public function usuarios()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function frecuencia()
    {
        return $this->belongsTo(Frecuencia::class, 'frecuencia_id');
    }

    public function agenda()
    {
        return $this->belongsToMany(AgendaEmpresa::class, 'agenda_empresas');
    }

    // public function getEstadoAttribute($value)
    // {
    //     return $value == 1 ? 'Activo' : 'Inactivo';
    // }

    /**
     * Route notifications for the mail channel.
     *
     * @return  array<string, string>|string
     */
    public function routeNotificationForMail(Notification $notification): array|string
    {
        // Return email address only...
        return $this->correo_electronico;
    }

    /**
     * Route notifications for the WhatsApp channel.
     *
     * @return  string
     */
    public function routeNotificationForWhatsApp(Notification $notification): string
    {
        // Return phone number in the format required by the WhatsApp channel
        return '57' . $this->numero_contacto; // Assuming the number is stored without the country code
    }
}
