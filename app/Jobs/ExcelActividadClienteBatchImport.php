<?php

namespace App\Jobs;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\UploadedFile;
use App\Models\ActividadCliente;
use App\Models\ReporteActividad;
use DateTime;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ExcelActividadClienteBatchImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function handle()
    {
        set_time_limit(300);
        ini_set('memory_limit', '2048M');
        $uploadedFile = new UploadedFile(
            $this->filePath,
            basename($this->filePath),
            mime_content_type($this->filePath),
            null,
            true
        );

        $spreadsheet = IOFactory::load($uploadedFile);
        // toma la primera hoja del excel
        $sheetIndex = 0; // Índice de la hoja que deseas cargar 
        $sheet = $spreadsheet->getSheet($sheetIndex);



        // Obtener los títulos de la primera fila
        $firstRow = $sheet->getRowIterator(1)->current();
        $columnTitles = [];
        foreach ($firstRow->getCellIterator() as $cell) {
            $columnTitles[] = $cell->getValue();
        }



        // Definir los títulos válidos
        $validColumns = ['Nombre', 'Tipo actividad', 'Progreso', 'Prioridad alta', 'Fecha vencimiento', 'Periodicidad', 'Cantidad recordatorios', 'Cantidad días entre recordatorios', 'Observación', 'Responsable Actividad', 'Empresa', 'Responsable', 'Cliente', 'Estado'];

        // Verificar si los títulos coinciden
        $invalidColumns = array_diff($validColumns, $columnTitles);
        if (!empty($invalidColumns)) {
            $errorMessage = "El archivo Excel no tiene los títulos esperados por favor verifica con la plantilla ";
            Session::flash('message2', $errorMessage);
            Session::flash('color', 'danger');
            return;
        }

        // Mapeo de nombres de columna del Excel a los nombres del modelo
        $columnMapping = [
            'A' => 'nombre',
            'B' => 'actividad_id',
            'C' => 'progreso',
            'D' => 'prioridad',
            'E' => 'fecha_vencimiento',
            'F' => 'periodicidad',
            'G' => 'recordatorio',
            'H' => 'recordatorio_distancia',
            'I' => 'nota',
            'J' => 'responsable_id',
            'K' => 'cliente_id',
            'L' => 'usuario_id',
            'M' => 'empresa_asociada_id',
            'N' => 'estado_actividad_id',
        ];

        $data = [];
        $skipFirstRow = true;
        foreach ($sheet->getRowIterator() as $row) {

            if ($skipFirstRow) {
                $skipFirstRow = false;
                continue; // Saltar la primera iteración
            }

            $rowData = [];
            $hasData = false; // Variable para rastrear si la fila contiene dat
            foreach ($row->getCellIterator() as $cell) {
                $columnName = $cell->getColumn();
                if (isset($columnMapping[$columnName]) && $columnName <= 'N') {
                    $columnNameModel = $columnMapping[$columnName];
                    $value = $cell->getValue();

                    // Verifica si el valor contiene un guion "-"
                    if (strpos($value, '-') !== false) {
                        // Divide el valor por el guion "-" y toma la primera parte
                        $value = explode('-', $value)[0];
                    }
                    // Verifica si la columna actual es E y luego convierte la fecha numérica
                    if ($columnName === 'E') {
                        // Intenta analizar la fecha en formato dd/mm/yyyy
                        $date = DateTime::createFromFormat('d/m/Y', $value);

                        if (!$date) {
                            // Si no se pudo analizar, intenta analizar en formato yyyy-mm-dd
                            $date = DateTime::createFromFormat('Y-m-d', $value);
                        }

                        if ($date) {
                            // Convierte el objeto DateTime a una cadena en formato "Y-m-d"
                            $fecha = $date->format('Y-m-d');
                            $rowData[$columnNameModel] = $fecha;
                        } else {
                            // Si no se pudo analizar en ninguno de los formatos, guarda el valor original
                            $fecha = date('Y-m-d', strtotime('1899-12-30 +' . $value . ' days'));
                            $rowData[$columnNameModel] = $fecha;
                        }
                    } else {
                        $rowData[$columnNameModel] = $value;
                    }
                    // Verifica si la celda contiene datos
                    if (!empty($value)) {
                        $hasData = true;
                    }
                }
            }
            // Si la fila contiene datos, agrégala a $data
            if ($hasData) {
                $data[] = $rowData;
            }
        }
        try {
            // Itera a través de tus datos
            foreach ($data as $datos) {
                $camposNoVacios = array_filter($datos, function ($campo) {
                    return !empty($campo);
                });

                // Verifica si al menos un campo no está vacío
                if (!empty($camposNoVacios)) {
                    // Crea un nuevo registro en ReporteActividad
                    if (isset($datos['estado_actividad_id'])) {
                        $estadoActividadId = $datos['estado_actividad_id'];
                    } else {
                        // Asigna un valor por defecto o realiza alguna lógica para determinar el valor
                        $estadoActividadId = 1; // Ejemplo de valor por defecto
                    }

                    $reporteActividad = ReporteActividad::create([
                        'estado_actividad_id' => $estadoActividadId,
                    ]);

                    // Verifica si actividad_id está presente en los datos
                    if (isset($datos['actividad_id'])) {
                        $actividadId = $datos['actividad_id'];
                    } else {
                        // Asigna un valor por defecto o realiza alguna lógica para determinar el valor
                        $actividadId = 1; // Ejemplo de valor por defecto
                    }

                    // Asegúrate de que $record sea un array asociativo
                    $record = [
                        'nombre' => $datos['nombre'],
                        'actividad_id' => $actividadId,
                        'progreso' => $datos['progreso'],
                        'prioridad' => $datos['prioridad'],
                        'fecha_vencimiento' => $datos['fecha_vencimiento'],
                        'periodicidad' => $datos['periodicidad'],
                        'recordatorio' => $datos['recordatorio'],
                        'recordatorio_distancia' => $datos['recordatorio_distancia'],
                        'nota' => $datos['nota'],
                        'responsable_id' => $datos['responsable_id'],
                        'cliente_id' => $datos['cliente_id'],
                        'usuario_id' => $datos['usuario_id'],
                        'reporte_actividad_id' => $reporteActividad->id,
                        'empresa_asociada_id' => $datos['empresa_asociada_id'],
                        'user_crea_act_id'  => Auth::user()->id,
                    ];

                    // Inserta el registro en la tabla ActividadCliente
                    ActividadCliente::create($record);
                }
            }
            // Si la importación se realizó con éxito
            Session::flash('message2', 'Importación exitosa');
            Session::flash('color', 'success');
        } catch (\Exception $e) {
                Log::error($e);
            // Si se produce una excepción durante la importación, captúrala y muestra un mensaje de error
            Session::flash('message2', 'Se ha producido un error en la importación de datos. Por favor, revisa los datos utilizando la plantilla proporcionada o descárgala de nuevo y asegúrate de que los datos estén formateados correctamente.');
            Session::flash('color', 'danger');
        }
    }
}
