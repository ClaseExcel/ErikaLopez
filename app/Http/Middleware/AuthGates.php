<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthGates
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return $next($request);
        }

        $roles = Role::with('permissions')->get();  
        
        //guarda el nombre del permiso y los roles que tienen ese permiso
        $permissionsArray = [];

        foreach ($roles as $role) {
            foreach ($role->permissions as $permissions) {
                $permissionsArray[$permissions->title][] = $role->id;
            }
        }
        
        foreach ($permissionsArray as $title => $roles) {
            Gate::define($title, function ($user) use ($roles) {                
                return in_array($user->role_id,$roles);
            });
        }

        return $next($request);
    }
}
