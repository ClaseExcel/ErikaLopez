<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Database\Seeder;

class PermissionRoleTableSeeder extends Seeder
{
    public function run()
    {
        $admin_permissions = Permission::all();

        //super administrador
        Role::findOrFail(1)->permissions()->sync($admin_permissions->pluck('id'));

        // Asignar al Especialista en la regional los permisos diferentes a crear editar y ver 
        // $usuarios = $admin_permissions->filter(function ($permission) {
        //     return substr($permission->title, 0, 6) != 'CREAR_' && substr($permission->title, 0, 7) != 'EDITAR_' && substr($permission->title, 0, 9) != 'ELIMINAR_';
        // });

        // Role::findOrFail(2)->permissions()->sync($usuarios);



    }
}
