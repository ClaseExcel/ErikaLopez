<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // [
            //     'title' => 'ACCEDER_DASHBOARD',
            // ],
            [
                'title' => 'GESTIONAR_USUARIOS', //Botón principal
            ],
            //USUARIOS
            [
                'title' => 'ACCEDER_USUARIOS', //Botón de usuarios
            ],
            [
                'title' => 'CREAR_USUARIOS',
            ],
            [
                'title' => 'EDITAR_USUARIOS',
            ],
            [
                'title' => 'VER_USUARIOS',
            ],
            [
                'title' => 'ELIMINAR_USUARIOS',
            ],
            //ASIGNAR ROLES
            [
                'title' => 'ACCEDER_ROL', //Botón de roles
            ],
            [
                'title' => 'CREAR_ROL',
            ],
            [
                'title' => 'EDITAR_ROL',
            ],
            [
                'title' => 'ELIMINAR_ROL',
            ],
            //PERMISOS  DESHABILITADO   
            [                
                'title' => 'ACCEDER_PERMISOS',
            ],
            [                
                'title' => 'CREAR_PERMISOS',
            ],
            [                
                'title' => 'EDITAR_PERMISOS',
            ],
            [                
                'title' => 'VER_PERMISOS',
            ],
            [                
                'title' => 'ELIMINAR_PERMISOS',
            ],            

            //GESTIONAR CLIENTES
            [
                'title' => 'GESTIONAR_CLIENTES',
            ],
            //EMPRESAS
            [
                'title' => 'ACCEDER_EMPRESAS', //Botón de empresas
            ],
            [
                'title' => 'CREAR_EMPRESAS',
            ],
            [
                'title' => 'EDITAR_EMPRESAS',
            ],
            [
                'title' => 'VER_EMPRESAS',
            ],
            //EMPLEADOS
            [
                'title' => 'ACCEDER_EMPLEADOS', //Botón de empresas
            ],
            [
                'title' => 'CREAR_EMPLEADOS',
            ],
            [
                'title' => 'EDITAR_EMPLEADOS',
            ],
            [
                'title' => 'VER_EMPLEADOS',
            ],
            [
                'title' => 'ELIMINAR_EMPLEADOS',
            ],
            //GESTION
            // [
            //     'title' => 'ACCEDER_GESTION', //Botón de Gestion
            // ],
            // [
            //     'title' => 'CREAR_GESTION',
            // ],
            // [
            //     'title' => 'EDITAR_GESTION',
            // ],
            // [
            //     'title' => 'VER_GESTION',
            // ],
            //GESTIONAR AGENDA
            // [
            //     'title' => 'GESTIONAR_AGENDA', //Botón principal
            // ],
            //DISPONIBILIDAD
            // [
            //     'title' => 'ACCEDER_DISPONIBILIDAD', //Botón de DISPONIBILIDAD
            // ],
            // [
            //     'title' => 'CREAR_DISPONIBILIDAD',
            // ],
            // [
            //     'title' => 'EDITAR_DISPONIBILIDAD',
            // ],
            // [
            //     'title' => 'ELIMINAR_DISPONIBILIDAD',
            // ],
            //ASIGNAR AGENDA
            // [
            //     'title' => 'ACCEDER_ASIGNAR_AGENDA', //Botón de ASIGNAR_AGENDA
            // ],
            // [
            //     'title' => 'CREAR_ASIGNAR_AGENDA',
            // ],
            // [
            //     'title' => 'EDITAR_ASIGNAR_AGENDA',
            // ],
            // [
            //     'title' => 'ELIMINAR_ASIGNAR_AGENDA',
            // ],
            //CITAS AGENDA
            // [
            //     'title' => 'ACCEDER_CITAS_AGENDA', //Botón de CITAS_AGENDA
            // ],
            // [
            //     'title' => 'ELIMINAR_CITAS_AGENDA',
            // ],
            //CITAS CLIENTE
            // [
            //     'title' => 'ACCEDER_CITAS_CLIENTE', //Botón de CITAS_CLIENTE
            // ],
            // [
            //     'title' => 'CREAR_CITAS_CLIENTE',
            // ],
            // [
            //     'title' => 'EDITAR_CITAS_CLIENTE',
            // ],
            // [
            //     'title' => 'ELIMINAR_CITAS_CLIENTE',
            // ],
            //GESTIONAR REQUERIMIENTOS
            // [
            //     'title' => 'GESTIONAR_REQUERIMIENTOS', //Botón principal
            // ],
            //SOLICITAR REQUERIMIENTOS
            // [
            //     'title' => 'ACCEDER_SOLICITAR_REQUERIMIENTOS', //Botón de SOLICITAR_REQUERIMIENTOS
            // ],
            // [
            //     'title' => 'CREAR_SOLICITAR_REQUERIMIENTOS',
            // ],
            //MIS REQUERIMIENTOS
            // [
            //     'title' => 'ACCEDER_MIS_REQUERIMIENTOS', //Botón de MIS_REQUERIMIENTOS
            // ],
            // [
            //     'title' => 'VER_MIS_REQUERIMIENTOS',
            // ],
            // [
            //     'title' => 'DESISTIR_MIS_REQUERIMIENTOS',
            // ],
            // //REQ. EMPLEADOS
            // [
            //     'title' => 'ACCEDER_REQ_EMPLEADOS', //Botón de REQ_EMPLEADOS
            // ],
            // [
            //     'title' => 'EDITAR_REQ_EMPLEADOS',
            // ],
            // //SEGUIMIENTO REQ
            // [
            //     'title' => 'ACCEDER_SEGUIMIENTO_REQ', //Botón de SEGUIMIENTO_REQ
            // ],
            // [
            //     'title' => 'EDITAR_SEGUIMIENTO_REQ',
            // ],
            // //ACTIVIDADES
            // [
            //     'title' => 'ACCEDER_ACTIVIDADES', //Botón de ACTIVIDADES
            // ],
            // [
            //     'title' => 'VER_ACTIVIDADES',
            // ],
            // [
            //     'title' => 'CREAR_ACTIVIDADES',
            // ],
            // [
            //     'title' => 'EDITAR_ACTIVIDADES',
            // ],
            // //CALENDARIO_TRIBUTARIO
            // [
            //     'title' => 'ACCEDER_CALENDARIO_TRIBUTARIO', //Botón de CALENDARIO_TRIBUTARIO
            // ],
            // [
            //     'title' => 'CREAR_CALENDARIO_TRIBUTARIO',
            // ],
            //GESTIONAR INFORME ACTIVIDADES
            // [
            //     'title' => 'GESTIONAR_INFORME_ACTIVIDADES', //Botón principal
            // ],
            // //INFORME EMPRESA
            // [
            //     'title' => 'ACCEDER_INFORME_POR_EMPRESA', //Botón de INFORME_EMPRESA
            // ],
            // //INFORME EMPRESA USUARIO
            // [
            //     'title' => 'ACCEDER_INFORME_POR_EMPRESA_USUARIO', //Botón de INFORME_POR_EMPRESA_USUARIO
            // ],
            //INFORME  USUARIO
            // [
            //     'title' => 'ACCEDER_INFORME_USUARIO', //Botón de INFORME_USUARIO
            // ],
            //INFORME ACTIVIDADES POR USUARIO
            // [
            //     'title' => 'ACCEDER_INFORME_ACTIVIDADES_POR_USUARIO', //Botón de INFORME_ACTIVIDADES_POR_USUARIO
            // ],
            //CAMBIAR PASSWORD                   
            [
                'title' => 'CAMBIAR_PASSWORD',
            ],
            //FILTRAR ACTIVIDADES                 
            // [
            //     'title' => 'FILTRAR_ACTIVIDADES',
            // ],
            // [
            //     'title' => 'FILTRAR_AGENDA',
            // ],
            // [
            //     'title' => 'ACCEDER_CALENDARIO_ACTIVIDADES'
            // ],
            //CRM
            // [
            //     'title' => 'ACCEDER_COTIZACIONES', //Botón de COTIZACIONES
            // ],
            // [
            //     'title' => 'CREAR_COTIZACIONES',
            // ],
            // [
            //     'title' => 'VER_COTIZACIONES',
            // ],
            // [
            //     'title' => 'EDITAR_COTIZACIONES',
            // ],
            // [
            //     'title' => 'SEGUIMIENTO_COTIZACIONES',
            // ],
            // [
            //     'title' => 'ACCEDER_INFORME_CRM',
            // ],
            // [
            //     'title' => 'ACCEDER_INFORME_ACTIVIDADES_POR_EMPRESA'
            // ],
            //Reporte actividades
            // [
            //     'title' => 'ACCEDER_REPORTE_ACTIVIDADES', //Botón de INFORME_ACTIVIDADES_POR_USUARIO
            // ],
            //CHECKLIST CONTABLE EMPRESAS
            // [
            //     'title' => 'ACCEDER_CHECKLIST_CONTABLE', //Botón de CHECKLIST CONTABLE
            // ],
            // [
            //     'title' => 'CREAR_CHECKLIST_CONTABLE',
            // ],
            // [
            //     'title' => 'VER_CHECKLIST_CONTABLE',
            // ],
            // [
            //     'title' => 'EDITAR_CHECKLIST_CONTABLE',
            // ],
            // [
            //     'title' => 'SEGUIMIENTO_CHECKLIST_CONTABLE',
            // ],
               //app contable 
            //MODIFICACIONES
            [
                'title' => 'ACCEDER_MODIFICACIONES',
            ],
            [
                'title' => 'CREAR_MODIFICACIONES',
            ],
            [
                'title' => 'EDITAR_MODIFICACIONES',
            ],
            [
                'title' => 'VER_MODIFICACIONES',
            ],
            [

                'title' => 'ELIMINAR_MODIFICACIONES',
            ],
            //CENTROS DE COSTOS
            [
                'title' => 'ACCEDER_CENTROS_COSTOS',
            ],
            [
                'title' => 'CREAR_CENTROS_COSTOS',
            ],
            [
                'title' => 'EDITAR_CENTROS_COSTOS',
            ],
            [
                'title' => 'VER_CENTROS_COSTOS',
            ],
            // [

            //     'title' => 'ELIMINAR_CENTROS_COSTOS', 
            // ],  
            //ESTADOS FINANCIEROS
            [
                'title' => 'GESTIONAR_INFORMES', //Botón principal
            ],
            [
                'title' => 'ACCEDER_INFORMES',
            ],
            [
                'title' => 'CREAR_INFORMES',
            ],
            [
                'title' => 'EDITAR_INFORMES',
            ],
            [
                'title' => 'VER_INFORMES',
            ],
            [
                'title' => 'ELIMINAR_INFORMES',
            ],
                 //GESTIÓN HUMANA
            // [
            //     'title' => 'ACCEDER_GESTIÓN_HUMANA', //Botón de GESTIÓN HUMANA
            // ],
            // [
            //     'title' => 'CREAR_GESTIÓN_HUMANA',
            // ],
            // [
            //     'title' => 'VER_GESTIÓN_HUMANA',
            // ],
            // [
            //     'title' => 'EDITAR_GESTIÓN_HUMANA',
            // ],
            // [
            //     'title' => 'ELIMINAR_GESTIÓN_HUMANA',
            // ],
            // [
            //     'title' => 'CREAR_EVENTO_GESTIÓN_HUMANA',
            // ],
            // [
            //     'title' => 'EDITAR_EVENTO_GESTIÓN_HUMANA',
            // ],

            //METAS EMPRESAS
            [
                'title' => 'ACCEDER_METAS_EMPRESAS',
            ],
            [
                'title' => 'CREAR_METAS_EMPRESAS',
            ],
            [
                'title' => 'EDITAR_METAS_EMPRESAS',
            ],
        ];

        // Permission::insert($permissions);
        // Permission::insert($permissions);
         foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['title' => $permission['title']],
                $permission
            );
        }
    }
}
