<?php

namespace App\Livewire;

use Livewire\Component;

class EstadoComponent extends Component
{

    public $estado;

    public function render()
    {
        return view('livewire.estado-component');
    }

    public function cambiarEstado()
    {
        //cambiar el estado, los estados son Activo e Inactivo
        if ($this->estado == '1') {
            $this->estado = '0';
        } else {
            $this->estado = '1';
        }
    }
}
