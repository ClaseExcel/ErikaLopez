<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Traits\CalendarioActividades;

use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class HomeController
{
    use CalendarioActividades;
    

    public function index()
    {
        abort_if(Gate::denies('ACCEDER_DASHBOARD'), Response::HTTP_UNAUTHORIZED);

        return view('home');
    }

    
}
