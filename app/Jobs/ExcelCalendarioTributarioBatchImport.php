<?php

namespace App\Jobs;

use App\Models\calendario_tributario;
use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Session;

class ExcelCalendarioTributarioBatchImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
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

  
        
       // Obtener los títulos de las primeras ocho columnas (A a H) de la primera fila
        $firstRow = $sheet->getRowIterator(1)->current();
        $columnTitles = [];
        foreach ($firstRow->getCellIterator('A', 'H') as $cell) {
            $columnTitles[] = $cell->getValue();
        }

        // Definir los títulos válidos
        $validColumns = [
            'CODIGO RESPONSABILIDAD TRIBUTARIA',
            'DETALLE RESPONSABILIDAD TRIBUTARIA',
            'ULTIMOS 2 DIGITOS', 
            'ULTIMO DIGITO', 
            'RANGO INICIAL', 
            'RANGO FINAL', 
            'FECHA DE VENCIMIENTO', 
            'CODIGO MUNICIPIO'
        ];
         // Verificar si los títulos coinciden
         $invalidColumns = array_diff($validColumns, $columnTitles);
         if (!empty($invalidColumns)) {
             $errorMessage = "El archivo Excel no tiene los títulos esperados por favor verifica el archivo ";
             Session::flash('message2',$errorMessage);
             Session::flash('color', 'danger');
             return;
         }

          // Mapeo de nombres de columna del Excel a los nombres del modelo
        $columnMapping = [
            'A' => 'codigo_tributario',
            'B' => 'detalle_tributario',
            'C' => 'ultimos_digitos',
            'D' => 'ultimo_digito',
            'E' => 'rango_inicial',
            'F' => 'rango_final',
            'G' => 'fecha_vencimiento',
            'H' => 'codigo_municipio',
        ];

        $data = [];
        $skipFirstRow = true;
        foreach ($sheet->getRowIterator() as $row) {

            if ($skipFirstRow) {
                $skipFirstRow = false;
                continue; // Saltar la primera iteración
            }

            $rowData = [];
            foreach ($row->getCellIterator() as $cell) {
                $columnName = $cell->getColumn();
                if (isset($columnMapping[$columnName]) && $columnName <= 'H') {
                    $columnNameModel = $columnMapping[$columnName];
                    $value = $cell->getValue();
                    if ($columnName === 'G') {
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
                }
            }
            $data[] = $rowData;
        }
        set_time_limit(300);

        $data = array_chunk($data,200);
        try{
            ini_set('memory_limit', '2048M');
            calendario_tributario::truncate();
            foreach ($data as $chunk) {
                $insertData = [];
                foreach ($chunk as $record) {
                    $insertData[] = $record;
                }
                calendario_tributario::insert($insertData);
            }
            // Si la importación se realizó con éxito
            Session::flash('message2', 'Importación exitosa');
            Session::flash('color', 'success');
        }catch (\Exception $e) {
            // Si se produce una excepción durante la importación, captúrala y muestra un mensaje de error
            Session::flash('message2', 'Se ha producido un error en la importación de datos. Por favor, revisa los datos utilizando la plantilla proporcionada y asegúrate de que los datos estén formateados correctamente.');
            Session::flash('color', 'danger');
        }
    }
}
