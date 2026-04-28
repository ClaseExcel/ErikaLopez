<?php

namespace App\Livewire;

use App\Models\Empresa;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FiltroAgendas extends Component
{

    public $empresa;
    public $usuario;
    public $empresas = [] , $usuarios = [];

    public function render()
    {
        return view('livewire.filtro-agendas');
    }

    public function mount()
    {
        if (Auth::user()->role_id == 1) {
            $this->empresas = Empresa::orderBy('razon_social')->select('id', 'razon_social')->where('estado', 1)->get();
        } else {
            $this->empresas = Empresa::orderBy('razon_social')->select('id', 'razon_social')->whereJsonContains('empleados', (string)Auth::user()->id)->get();
        }

        $this->usuarios = User::select('id','nombres', 'apellidos')->whereNotIn('role_id', [7, 8])->where('estado', 'ACTIVO')->get();
    }
}
