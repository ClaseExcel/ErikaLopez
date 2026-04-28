<?php

namespace App\Livewire;

use App\Models\ActividadCliente;
use App\Models\EmpleadoCliente;
use App\Models\Empresa;
use App\Models\Role;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;

class ReasignarActividad extends Component
{

    public $selectedRows = [];
    public $selectedEmpresa = [];
    public $empresa;
    public $empresa_id;
    public $empleado_id;
    public $empleados = [];
    public $actividades = [];
    public $isEnable = 'disabled';

    public $authReasignar = 'none';

    public $boton;
    
    public function render()
    {
        $autorizado = auth()->user()->role->id;

        if( $autorizado == Role::ROLE_SUPERADMIN){
            $this->authReasignar = 'inline';
        }
        
        return view('livewire.reasignar-actividad');
    }

    #[On('getIdsReasignarActividad')] 
    public function getIds($empresa, $ids = []){

        $this->selectedEmpresa = $empresa;
        $this->selectedRows = $ids;

        //obtener las actividades seleccionadas
        $this->actividades = ActividadCliente::find($ids);
        

        //buscar la empresa seleccionada en la base de datos por razon_social
        $this->empresa = Empresa::where('razon_social', $this->selectedEmpresa)->where('estado', 1)->first();

        //obtener los empleados de la empresa seleccionada
        // $empleados = EmpleadoCliente::with('usuarios')->select('id', 'nombres', 'apellidos','empresa_id','user_id')->where('empresa_id', $this->empresa->id )->get();
        $empleados = User::select('id', 'nombres', 'apellidos')->whereNotIn('role_id', [7])->get();
        $this->empleados = $empleados;
        // dd($empleados );

        // dd($this->empresa);

        $this->dispatch('show-reasignar-modal');         
    }

    public function habilitarBotonGuardar(){
        if($this->empleado_id != ''){
            $this->isEnable = '';
        }else{
            $this->isEnable = 'disabled';
        }
    }

    public function reasignar()
    {    

        if($this->empleado_id == null){
            $this->dispatch('close-reasignar-modal');
            $this->dispatch('actualizarTablaActividades');
            $this->dispatch('Error');
            return;
        }

        //reasignar las actividades seleccionadas
        foreach ($this->selectedRows as $id) {
            $actividad = ActividadCliente::find($id);
            $actividad->usuario_id = $this->empleado_id;
            $actividad->save();
        }

        $this->dispatch('actualizarTablaActividades');
        $this->dispatch('close-reasignar-modal');

    }

    public function closeModal(){
        $this->dispatch('actualizarTablaActividades');
    }
}
