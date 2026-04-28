<?php

namespace App\Livewire;

use App\Models\ActividadCliente;
use App\Models\EstadoActividad;
use App\Models\HistorialActividades;
use App\Models\ReporteActividad;
use App\Models\Role;
use Barryvdh\Debugbar\Facades\Debugbar;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class CambiarEstadoActividad extends Component
{

    use WithFileUploads;

    public $estados;
    public $idEstado;
    public $selectedRows = [];
    public $modalidades = ['Virtual', 'Presencial'];


    public $isProgreso = false;
    public $isJustificacion = false;
    public $isFechaVencimiento = false;
    public $isRecomendacion = false;
    public $isEnable = false;
    public $isModalidad = false;


    public $justificacion;
    public $progreso;
    public $fechaVencimiento;
    public $recomendacion;
    public $modalidad;

    //validaciones para habilitar el boton de guardar
    public $isProgresoValido = false;
    public $isJustificacionValido = false;
    public $isFechaVencimientoValido = false;
    public $isRecomendacionValido = false;
    public $isModalidadValido = false;

    public $authCambiarEstado = 'none';

    public function render()
    {
        $autorizado = auth()->user()->role->id;

        if( $autorizado == Role::ROLE_SUPERADMIN){
            $this->authCambiarEstado = 'inline';
        }
        
        return view('livewire.cambiar-estado-actividad');
    }

    public function mount(){

        //obtener los estados de actividad de la base de datos
        $this->estados = EstadoActividad::whereNotIn('id', [1,5,6])->get();
    }

    #[On('getIdsCambiarEstado')] 
    public function getIds($ids = []){
        $this->selectedRows = $ids;  
        $this->dispatch('show-actividad-modal');         
    }

    public function cambiarEstado()
    {
        //obtener los ids de las actividades seleccionadas
        $ids = $this->selectedRows;

        //cambiar el estado de las actividades seleccionadas
        foreach($ids as $id){
            $actividad = ActividadCliente::find($id);

            //programado
            if($this->idEstado == 1){
                $actividad->update([
                    'progreso' => 0,
                    'user_update_act_id' => auth()->user()->id
                ]);

                ReporteActividad::find($actividad->reporte_actividad_id)->update([
                    'estado_actividad_id' => $this->idEstado,
                    'fecha_inicio' => NULL,                    
                ]);
            }

            //en proceso
            if($this->idEstado == 2){
                $actividad->update([
                    'progreso' => 0,
                    'user_update_act_id' => auth()->user()->id
                ]);

                ReporteActividad::find($actividad->reporte_actividad_id)->update([
                    'estado_actividad_id' => $this->idEstado,
                    'fecha_inicio' => date('Y-m-d H:i:s'),
                ]);
            }

            //pausado  reactivado
            if($this->idEstado == 3 || $this->idEstado == 9){    

                $actividad->update([
                    'progreso' => $this->progreso,
                    'user_update_act_id' => auth()->user()->id
                ]);

                ReporteActividad::find($actividad->reporte_actividad_id)->update([
                    'estado_actividad_id' => $this->idEstado,
                    'justificacion' => $this->justificacion,
                ]);

                HistorialActividades::create([
                    'reporte_actividades_id' => $actividad->reporte_actividad_id,
                    'estado' => $this->idEstado,
                    'justificacion' => $this->justificacion,
                    'modalidad' =>  $this->modalidad,
                    'fecha_creacion' => date('Y-m-d H:i:s')
                ]);
            }

            //cancelado
            if( $this->idEstado == 4){    

                $actividad->update([
                    'progreso' => $this->progreso,
                    'user_update_act_id' => auth()->user()->id
                ]);

                ReporteActividad::find($actividad->reporte_actividad_id)->update([
                    'estado_actividad_id' => $this->idEstado,
                    'justificacion' => $this->justificacion,
                ]);
            }





            //finalizado y cumplido
            if($this->idEstado == 7 || $this->idEstado == 8){
                
                $actividad->update([
                    'progreso' => 100,
                    'user_update_act_id' => auth()->user()->id
                ]);

                ReporteActividad::find($actividad->reporte_actividad_id)->update([
                    'estado_actividad_id' => $this->idEstado,
                    'justificacion' => $this->justificacion,
                    'recomendacion' => $this->recomendacion,
                    'fecha_fin' => date('Y-m-d H:i:s'),
                ]);
            }

            //reprogramado
            if($this->idEstado == 10){
                $actividad->update([     
                    'progreso' => 0,      
                    'user_update_act_id' => auth()->user()->id,   
                    'fecha_vencimiento' => $this->fechaVencimiento
                ]);

                ReporteActividad::find($actividad->reporte_actividad_id)->update([
                    'estado_actividad_id' => $this->idEstado,
                    'fecha_inicio' => NULL,
                ]);
            }
                
        }

        // //limpiar el array de actividades seleccionadas
        $this->selectedRows = [];
        //limpiar los campos
        $this->idEstado = null;
        $this->justificacion = null;
        $this->progreso = null;
        $this->recomendacion = null;
        $this->modalidad = null;
        
        $this->fechaVencimiento = null;   
        $this->isJustificacion = false;
        $this->isProgreso = false;
        $this->isFechaVencimiento = false;    
        $this->isRecomendacion = false; 
        $this->isModalidad = false;
        

        //cerrar el modal
        $this->dispatch('close-actividad-modal');

        //emitir evento para actualizar la tabla de actividades
        $this->dispatch('actualizarTablaActividades');

        

    }

    public function camposExtra(){

        $this->isEnable = false;
        $this->justificacion = null;
        $this->progreso = null;
        $this->fechaVencimiento = null;   
        $this->recomendacion = null;
        $this->modalidad = null;
        
        $this->isJustificacion = false;
        $this->isProgreso = false;
        $this->isFechaVencimiento = false;
        $this->isProgresoValido = false;
        $this->isJustificacionValido = false;
        $this->isFechaVencimientoValido = false;
        $this->isRecomendacionValido = false;
        $this->isRecomendacion = false;  
        $this->isModalidadValido = false;
        $this->isModalidad = false;


        //ningun estado
        if($this->idEstado == 0){
            $this->isJustificacion = false;
            $this->isProgreso = false;
            $this->isFechaVencimiento = false;
            $this->isRecomendacion = false;     
            $this->isModalidad = false;         
            $this->isEnable = false;
        }

        //programado y en proceso
        if($this->idEstado == 1 || $this->idEstado == 2){
            $this->isJustificacion = false;
            $this->isProgreso = false;
            $this->isFechaVencimiento = false;
            $this->isRecomendacion = false;    
            $this->isModalidad = false;                 
            $this->habilitarBotonGuardar();
        }

        // pausado y reactivado
        if( $this->idEstado == 3 || $this->idEstado == 9){            
            $this->isJustificacion = true;
            $this->isProgreso = true;
            $this->isFechaVencimiento = false;
            $this->isRecomendacion = false; 
            $this->isModalidad = true;             
        }

        //cancelado
        if( $this->idEstado == 4 ){            
            $this->isJustificacion = true;
            $this->isProgreso = true;
            $this->isFechaVencimiento = false;
            $this->isModalidad = false;       
        }

        //finalizado y cumplido
        if($this->idEstado == 7 || $this->idEstado == 8){
            $this->isJustificacion = true;
            $this->isProgreso = false;
            $this->isFechaVencimiento = false;
            $this->isRecomendacion = true;   
            $this->isModalidad = false;                
        }


        //reprogramado
        if($this->idEstado == 10){
            $this->isJustificacion = false;
            $this->isProgreso = false;
            $this->isFechaVencimiento = true;
            $this->isRecomendacion = false;        
            $this->isModalidad = false;             
        }        

        $this->dispatch('show-actividad-modal');  

    }

    public function habilitarBotonGuardar(){

        if($this->idEstado == 1 || $this->idEstado == 2){
            $this->isEnable = true;
            return;
        }

        //pausado
        if($this->idEstado == 3 || $this->idEstado == 9 ){
            if($this->isProgresoValido && $this->isJustificacionValido && $this->isModalidadValido)
            {
                $this->isEnable = true;
                return;
            }
        }

        //cancelado
        if( $this->idEstado == 4){
            if($this->isProgresoValido && $this->isJustificacionValido)
            {
                $this->isEnable = true;
                return;
            }
        }
        
        //finalizado y cumplido
        if($this->idEstado == 7 || $this->idEstado == 8 && $this->isJustificacionValido && $this->isRecomendacionValido){
            if($this->isJustificacionValido){
                $this->isEnable = true;
                return;
            }
        }
        
        $this->isEnable = false;

    }

    //validar modalidad
    public function validarModalidad(){
        if($this->modalidad != null){
            $this->isModalidadValido = true;
        }else{
            $this->isModalidadValido = false;
        }
        $this->habilitarBotonGuardar();
    }


    public function validarProgreso(){

        if($this->progreso != ''){
            $this->isProgresoValido = true;
        }else{
            $this->isProgresoValido = false;
        }
        
        $this->habilitarBotonGuardar();
    }

    //validar justificación
    public function validarJustificacion(){

        if(trim($this->justificacion) != ''){
            $this->isJustificacionValido = true;
        }else{
            $this->isJustificacionValido = false;
        }
        $this->habilitarBotonGuardar();

    }

    //validar recomendación
    public function validarRecomendacion(){
        if(trim($this->recomendacion) != ''){
            $this->isRecomendacionValido = true;
        }else{
            $this->isRecomendacionValido = false;
        }
        $this->habilitarBotonGuardar();
    }

    //validar fecha de vencimiento
    public function validarFechaVencimiento(){
        if($this->fechaVencimiento != null){
            $this->isFechaVencimientoValido = true;
            $this->habilitarBotonGuardar();
        }else{
            $this->isFechaVencimientoValido = false;
        }

    }

    //cerrar modal
    public function closeModal(){
        $this->selectedRows = [];
        //limpiar los campos
        $this->idEstado = null;
        $this->justificacion = null;
        $this->progreso = null;
        $this->fechaVencimiento = null;   
        $this->recomendacion = null;
        $this->modalidad = null;
        
        $this->isJustificacion = false;
        $this->isProgreso = false;
        $this->isFechaVencimiento = false;  
        $this->isRecomendacion = false;
        $this->isModalidad = false;
        
        $this->isEnable = false; 
        
        $this->dispatch('actualizarTablaActividades');
    }

}
