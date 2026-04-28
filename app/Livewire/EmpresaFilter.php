<?php

namespace App\Livewire;

use App\Models\EmpleadoCliente;
use App\Models\Empresa;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class EmpresaFilter extends Component
{
    public $empresa, $responsable;
    public $todasLasEmpresas = false;
    public $empresas = [] , $responsables = [];

    public function mount()
    {
        if (Auth::user()->role_id == 1) {
            $this->empresas = Empresa::orderBy('razon_social')->select('id', 'razon_social')->where('estado', 1)->get();
        } else {
            $this->empresas = Empresa::orderBy('razon_social')->select('id', 'razon_social')->whereJsonContains('empleados', (string)Auth::user()->id)->get();
        }

        $this->responsables = collect();
    }

   
    public function updatedEmpresa($value)
    {
        $parts = explode('-', $value);
        $value = trim($parts[0]);
        if ($value == 1) {
            // Si el valor seleccionado es 1, cargue los datos de los responsables sin seleccionar automáticamente el primero
            $this->loadResponsablesWithoutSelection();
        } elseif($value === ''){
             // Si se selecciona "Todas las empresas"
            $this->responsables = User::select('id','id as user_id', 'nombres', 'apellidos')->get();
        }else {
            // Si no es 1, carga los responsables como lo haces normalmente
            $this->loadResponsables($value);
        }
    }

    protected function loadResponsablesWithoutSelection()
    {
        $this->responsables = User::select('id', 'id as user_id', 'nombres', 'apellidos')->whereNotIn('role_id', [7, 8])->get();
        $this->responsable = null; // Establecer responsable como null
    }

    protected function loadResponsables($value)
    {
        // Carga los responsables como lo haces normalmente
        $this->responsables = EmpleadoCliente::select('id', 'user_id', 'nombres', 'apellidos')
            ->where('empresa_id', $value)
            ->orWhere(function ($query) use ($value) {
                $query->whereIn('user_id', function ($subquery) use ($value) {
                    $subquery->select('usuario_id')
                        ->from('actividad_cliente')
                        ->where('cliente_id', $value);
                });
            })
            ->get();
    }

    
    
    public function render()
    {
        
        return view('livewire.empresa-filter');
    }

}
