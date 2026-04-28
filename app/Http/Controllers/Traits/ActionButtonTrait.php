<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Support\Facades\Gate;

trait ActionButtonTrait
{
    /**
     * Trait para la creacion de los botones de actions de las tablas serverside
     * @param string $route nombre de la ruta
     * @param string $permission palabra final del permiso ejm. ACCEDER_USUARIOS se pone USUARIOS en mayuscula
     * @param mix $id id del botón
     * 
     * @return string retorna el HTML con los botones y sus respectivos permisos
     */
    public function getActionButtons($route,$permission, $id, $estado = null)
    {
        //html por default (No autorizado)
        $ver ='';
        $editar = '';
        $eliminar = '';


        //Validar permisos
        if(Gate::allows('VER_'.$permission))
        {
            $ver = '<a class="btn-ver px-2 py-0" href="' . route( $route.'.show', $id ) . '" title="Ver más información"><i class="fas fa-eye"></i></a>';
        }

        if(Gate::allows('EDITAR_'.$permission))
        {
            $editar = '<a class="btn-editar px-2 py-0  " href="' . route($route.'.edit', $id) . '" title="Editar registro"><i class="fas fa-pencil-alt"></i></a>';
        }

        if(Gate::allows('ELIMINAR_'.$permission))
        {
            if($permission == 'USUARIOS' || $permission == 'EMPLEADOS') {
                $titulo = $estado === 'ACTIVO' ? "Inactivar usuario" : "Activar usuario";
                $eliminar = '<button type="button" class="btn-eliminar px-2 py-0 " title="'. $titulo .'" onclick="eliminar('. $id .', \''. $estado .'\')"><i class="fa-solid fa-user-xmark"></i></button>';
            } else {
                $eliminar = '<button type="button" class="btn-eliminar px-2 py-0 " title="Eliminar registro" onclick="eliminar('. $id .')"> <i class="fas fa-minus-circle"></i></button>';
            }
            
        }

        //HTML completo con los permisos correspondientes
        $actions = $ver.' '.$editar.' '.$eliminar;


        return $actions;
    }
}