<?php

namespace App\Jobs;

use App\Models\Empresa;
use App\Models\FechasMunicipalesCT;
use App\Models\FechasOtrasEntidadesCT;
use App\Models\FechasPorEmpresaCT;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;

class GenerarInformeTributario implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $fechaInicio;
    protected $fechaFin;
    protected $tipo;
    protected $id_empresa;

    public function __construct($fechaInicio, $fechaFin, $tipo,$id_empresa)
    {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
        $this->tipo = $tipo;
        $this->id_empresa = $id_empresa;
    }

    public function handle()
    {
        $fechasEmpresa = collect(); // Asegura que sea una colección
        $detallesTributarios = collect();
        $fechasMunicipales = collect();
        if($this->tipo=='fechas'){
            $fechaInicio = Carbon::parse($this->fechaInicio)->startOfDay();
            $fechaFin = Carbon::parse($this->fechaFin)->endOfDay();
            // Obtener todas las empresas
            $empresas = Empresa::pluck('razon_social', 'Nit');

            // FechasPorEmpresaCT
            FechasPorEmpresaCT::whereBetween('fecha_vencimiento', [$fechaInicio, $fechaFin])
                ->with(['empresa:id,NIT,razon_social'])
                ->orderBy('fecha_vencimiento')
                ->chunk(500, function ($fechas) use (&$fechasEmpresa) {
                    foreach ($fechas as $fecha) {
                        $fechasEmpresa->push($fecha);
                    }
                });

            // FechasOtrasEntidadesCT
            FechasOtrasEntidadesCT::whereBetween('fecha_vencimiento', [$fechaInicio, $fechaFin])
                ->with(['empresa:id,NIT,razon_social'])
                ->orderBy('fecha_vencimiento')
                ->chunk(500, function ($fechas) use (&$detallesTributarios) {
                    foreach ($fechas as $fecha) {
                        $detallesTributarios->push($fecha);
                    }
                });

            // FechasMunicipalesCT
            FechasMunicipalesCT::whereBetween('fecha_vencimiento', [$fechaInicio, $fechaFin])
                ->with(['empresa:id,NIT,razon_social'])
                ->orderBy('fecha_vencimiento')
                ->chunk(500, function ($fechas) use (&$fechasMunicipales) {
                    foreach ($fechas as $fecha) {
                        $fechasMunicipales->push($fecha);
                    }
                });
        }else{
            // Obtener la empresa específica
            $empresa = Empresa::find($this->id_empresa);
            $empresas = Empresa::find($this->id_empresa)->pluck('razon_social', 'Nit');
            if (!$empresa) {
                return response()->json(['error' => 'Empresa no encontrada'], 404);
            }
            // Definir el rango del año en curso
            $inicioAño = Carbon::now()->startOfYear()->toDateString();
            $finAño = Carbon::now()->endOfYear()->toDateString();

            $fechaInicio = $inicioAño;
            $fechaFin = $finAño;
             // Mantener la estructura original pero filtrar por NIT de la empresa seleccionada
            FechasPorEmpresaCT::whereBetween('fecha_vencimiento', [$inicioAño, $finAño])
            ->whereHas('empresa', function ($query) use ($empresa) {
                $query->where('NIT', $empresa->NIT);
            })
            ->with(['empresa:id,NIT,razon_social'])
            ->orderBy('fecha_vencimiento')
            ->chunk(500, function ($fechas) use (&$fechasEmpresa) {
                foreach ($fechas as $fecha) {
                    $fechasEmpresa->push($fecha);
                }
            });

            FechasOtrasEntidadesCT::whereBetween('fecha_vencimiento', [$inicioAño, $finAño])
                ->whereHas('empresa', function ($query) use ($empresa) {
                    $query->where('NIT', $empresa->NIT);
                })
                ->with(['empresa:id,NIT,razon_social'])
                ->orderBy('fecha_vencimiento')
                ->chunk(500, function ($fechas) use (&$detallesTributarios) {
                    foreach ($fechas as $fecha) {
                        $detallesTributarios->push($fecha);
                    }
                });

            FechasMunicipalesCT::whereBetween('fecha_vencimiento', [$inicioAño, $finAño])
            ->whereHas('empresa', function ($query) use ($empresa) {
                $query->where('NIT', $empresa->NIT);
            })
            ->with(['empresa:id,NIT,razon_social'])
            ->orderBy('fecha_vencimiento')
            ->chunk(500, function ($fechas) use (&$fechasMunicipales) {
                foreach ($fechas as $fecha) {
                    $fechasMunicipales->push($fecha);
                }
            });
        }
      
    
        if ($fechasEmpresa->isEmpty() && $detallesTributarios->isEmpty() && $fechasMunicipales->isEmpty()) {
            return back()->with('error', 'No se encontraron datos para el periodo seleccionado.');
        }
    
        // Logo en Base64
        $imagePathLogo = public_path("images/logos/logo_contable.png");
        $base64ImageLogo = file_exists($imagePathLogo) ? 'data:image/png;base64,' . base64_encode(file_get_contents($imagePathLogo)) : null;
    
        $data = [
            'fechasEmpresa' => $fechasEmpresa,
            'detallesTributarios' => $detallesTributarios,
            'fechasMunicipales' => $fechasMunicipales,
            'base64ImageLogo' => $base64ImageLogo,
            'empresas' => $empresas,
            'fechainicio' => $fechaInicio,
            'fechafin' => $fechaFin
        ];
    
        $html = View::make('admin.calendariotributario.informe_tributario', $data)->render();
    
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
    
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
    
        return $dompdf->stream("informe_tributario.pdf", ['Attachment' => true]);
    }
}
