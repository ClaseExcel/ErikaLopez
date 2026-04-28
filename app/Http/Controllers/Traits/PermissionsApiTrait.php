<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;

trait PermissionsApiTrait
{
    public function getPermissionsApi($empresa_id)
    {
        // $response = Http::get('software.helpdigital.com.co/api/control-clientes/' . $empresa_id);
        // $permissionsApi = [];
        // if ($response->successful()) {
        //     $permissionsApi = $response->json();
        // }
        // return $permissionsApi;
    }
}