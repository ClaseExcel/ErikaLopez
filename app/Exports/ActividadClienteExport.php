<?php

namespace App\Exports;

use App\Models\Actividad;
use App\Models\EmpleadoCliente;
use App\Models\Empresa;
use App\Models\EstadoActividad;
use App\Models\Responsable;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Maatwebsite\Excel\Concerns\FromArray;

class ActividadClienteExport implements FromArray
{
    public function __construct()
    {
    }

    public function array(): array
    {
        $datosParaExcel = $this->datosparaplantilla();
        // Ruta del archivo Excel existente

        
        $rutaExcelExistente = public_path('data/ActividadCliente/MasivoActividades.xlsx');

        // Carga el archivo Excel existente
        $spreadsheet = IOFactory::load($rutaExcelExistente);

        // Obtiene la hoja "listas" del Excel
        $hojaListas = $spreadsheet->getSheetByName('listas');

        // Llena la hoja "listas" con los nuevos datos
        $this->llenarHojaConDatos($hojaListas, $datosParaExcel);

        // Guarda el Excel modificado
        $writer = new Xlsx($spreadsheet);
        $writer->save($rutaExcelExistente);

        // Devuelve los datos como un array, pero no es necesario usarlos aquí.
        return [];
    }

    public function llenarHojaConDatos($hoja, $datos)
    {
         // Borra los valores existentes en todas las celdas de la hoja
        $hoja->removeColumn('A');
        $hoja->fromArray([], null, 'A1');

        $rowIndex = 1;
        foreach ($datos as $clave => $valores) {
            // Escribe la clave en la primera columna
            $hoja->setCellValue('A' . $rowIndex, $clave);

            // Escribe los valores en las siguientes columnas
            $columnIndex = 2;
            foreach ($valores as $valor) {
                $hoja->setCellValueByColumnAndRow($columnIndex, $rowIndex, $valor);
                $columnIndex++;
            }

            $rowIndex++;
        }
        
    
    }

    private function datosparaplantilla(){
        $actividades = Actividad::pluck('nombre','id')->toArray();
        $responsables = Responsable::pluck('nombre','id')->toArray();
        if (Auth::user()->role_id == 1) {
            $empresas = Empresa::pluck('razon_social','id')->toArray();
        } else {
            $empresas = Empresa::whereJsonContains('empleados', (string)Auth::user()->id)->pluck('razon_social','id')->toArray();
        }
        $users = User::selectRaw('CONCAT(nombres, " ", apellidos) as nombre_completo, id')->pluck('nombre_completo', 'id')->toArray();
        $clientes = Empresa::pluck('razon_social','id')->toArray();
        $estados = EstadoActividad::pluck('nombre','id')->toArray();
        // Combina los datos de todas las tablas en un solo array con subarrays
        $datosParaExcel = [
            'actividades' => [],
            'responsables' => [],
            'empresas' => [],
            'clientes' => [],
            'users' => [],
            'estados' => [],
        ];

        foreach ($actividades as $id => $nombre) {
            $datosParaExcel['actividades'][] = "$id-$nombre";
        }

        foreach ($responsables as $id => $nombre) {
            $datosParaExcel['responsables'][] = "$id-$nombre";
        }

        foreach ($empresas as $id => $razonSocial) {
            $datosParaExcel['empresas'][] = "$id-$razonSocial";
        }

        foreach ($clientes as $id => $nombres) {
            $datosParaExcel['clientes'][] = "$id-$nombres";
        }

        foreach ($users as $id => $nombres) {
            $datosParaExcel['users'][] = "$id-$nombres";
        }

        foreach ($estados as $id => $nombre) {
            $datosParaExcel['estados'][] = "$id-$nombre";
        }

        return $datosParaExcel;
   }
}