<?php

namespace App\Jobs;

use App\Models\ClientesMoviemientos;
use App\Models\ClientesMovimientos;
use App\Models\Compania;
use App\Models\Empresa;
use App\Models\FechasExistentesIC;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\UploadedFile;
// use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ExcelMovimientosBatchImport implements ShouldQueue,WithBatchInserts
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    protected $nit;
    protected $fechareporte;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($filePath,$nit,$fechareporte)
    {
        $this->filePath = $filePath;
        $this->nit = $nit;
        $this->fechareporte = $fechareporte;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        set_time_limit(0);
        ini_set('memory_limit', '512M');    
        $reader = IOFactory::createReaderForFile($this->filePath);
        $reader->setReadDataOnly(true);
        // Abrir el archivo en modo lectura
        $spreadsheet = $reader->load($this->filePath);
        unset($reader);
        $chunkSize = 1000; // Tamaño del chunk
        $sheet = $spreadsheet->getActiveSheet();
        $totalRows = $sheet->getHighestRow();
        $currentRow = 1;
        $nit = $this->nit; 
        $fecha =$this->fechareporte;
        $compania = Empresa::where('NIT',$nit)->first();
        // dd($compania);
        // Verificar si la fila 7 está vacía y si es así, usar la fila 8
        if ($compania->tipo === 'NUBE') {
            $firstRow = $sheet->getRowIterator(8)->current();
        }else{
            $firstRow = $sheet->getRowIterator(7)->current();
        }

        $columnTitles = [];
        foreach ($firstRow->getCellIterator() as $cell) {
            $columnTitle = $cell->getValue();
            $columnTitle = str_replace(' ', '', $columnTitle); // Elimina espacios en blanco
            $columnTitle = str_replace('.', '', $columnTitle); // Elimina puntos
            if ($columnTitle !== '') {
                $columnTitles[] = $columnTitle;
            }
            unset($cell);
        }
        if($compania->tipo === 'NUBE'){
            // Definir los títulos válidos
            $validColumns = ['Códigocontable', 'Cuentacontable', 'Comprobante', 'Secuencia', 
            'Fechaelaboración', 'Identificación', 'Suc', 'Nombredeltercero',
            'Descripción','Detalle','Centrodecosto','Saldoinicial',
            'Débito','Crédito','SaldoMovimiento','Saldototalcuenta'];
           // Mapeo de nombres de columna del Excel a los nombres del modelo
           $columnMapping = [
               'A' => 'codigo_contable_sw',
               'B' => 'cuenta_contable_sw',
               'C' => 'comprobante_sw',
               'D' => 'secuencia_sw',
               'E' => 'fecha_elaboracion_sw',
               'F' => 'identificacion_sw',
               'G' => 'suc_sw',
               'H' => 'nombre_tercero_sw',
               'I' => 'descripcion_sw',
               'J' => 'detalle_sw',
               'K' => 'centro_costo_sw',
               'L' => 'saldo_inicial_sw',
               'M' => 'debito_sw',
               'N' => 'credito_sw',
               'O' => 'saldo_movimiento_sw',
               'P' => 'salto_total_cuenta_sw',
           ];
           $columnafinal='P';
           $fechareporte ='fecha_reporte_sw';
           $inicia=8;

        }else{
            // Definir los títulos válidos
            $validColumns = ['CUENTADESCRIPCION', 'CUENTA', 'DESCRIPCION', 'SALDOINICIAL', 
            'COMPROBANTE', 'FECHA', 'NIT', 'NOMBRE',
            'DESCRIPCION','INVENTARIO-CRUCE-CHEQUE','BASE',
            'CCSCC','DEBITOS','CREDITOS','SALDOMOV','OBSERVACION'];
           // Mapeo de nombres de columna del Excel a los nombres del modelo
           $columnMapping = [
               'A' => 'cuenta_descripcion', 
               'B' => 'cuenta',
               'C' => 'descripcionct',
               'D' => 'saldoinicial',
               'E' => 'comprobante',
               'F' => 'fecha',
               'G' => 'nit_sl',
               'H' => 'nombre',
               'I' => 'descripcion',
               'J' => 'inventario_cruce_cheque',
               'K' => 'base',
               'L' => 'cc_scc',
               'M' => 'debitos',
               'N' => 'creditos',
               'O' => 'saldo_mov',
               'P' => 'observacion_sl',
           ];
           $columnafinal='P';
           $fechareporte ='fecha_reporte';
           $inicia=7;
        }
        // Verificar si los títulos coinciden
        $invalidColumns = array_diff($validColumns, $columnTitles);
        if (!empty($invalidColumns)) {
            $errorMessage = "El archivo Excel no tiene los títulos esperados: " . implode(', ', $validColumns);
            Session::flash('message2',$errorMessage);
            Session::flash('color', 'danger');
            return;
        }

         //verifica los nit que coincidan con el del excel
         if ($compania->tipo === 'NUBE') {
            // Itera a través de las filas
            foreach ($sheet->getRowIterator(4) as $row) {
               // Obtiene el valor de la cuarta columna (columna A)
               $cellValue = $sheet->getCell('A' . $row->getRowIndex())->getValue();
               // Utiliza una expresión regular para extraer el número
               $numeroNIT = explode('-', $cellValue);
              // Busca el número NIT en el arreglo
               foreach ($numeroNIT as $value) {
                   // Utiliza una expresión regular para extraer el número
                   if (preg_match('/^\d+$/', $value)) {
                       // Si encontramos un valor que consiste solo en dígitos, consideramos que es el número NIT
                       $numeroNIT = $value;
                       break; // Terminamos el bucle después de encontrar el número
                       unset($velue);
                   }
               }
               if ($numeroNIT != $nit){
                   $errorMessage = "Verifica el archivo el NIT que seleccionates en la pagina es: ".$nit. " y el NIT del excel es: ".$numeroNIT;
                               Session::flash('message2',$errorMessage);
                               Session::flash('color', 'danger');
                               return;
               }
               // Si solo deseas la primera coincidencia, puedes agregar un break aquí
               break;

           }
       }else{
           // Itera a través de las filas
           foreach ($sheet->getRowIterator(1) as $row) {
               // Obtiene el valor de la cuarta columna (columna A)
               $cellValue = $sheet->getCell('A' . $row->getRowIndex())->getValue();
               // Dividir la cadena en función del separador " - "
                $parts = explode(' - ', $cellValue);
                
                // Obtener el último elemento del array como el nombre deseado
                $nombre = end($parts);
           
               if ($nombre != $compania->razon_social){
                   $errorMessage = "Verifica el archivo el nombre de empresa que seleccionates en la pagina es: ".$compania->razon_social. " y el nombre del excel es: ".$nombre;
                               Session::flash('message2',$errorMessage);
                               Session::flash('color', 'danger');
                               return;
               }
               break;
               unset($row);
           }
       }
        $data = [];
        $chunkSize = 1000; // Tamaño del chunk
        $currentRow = 1; // Inicializar la fila actual en 1
        $skipFirstRow = true; // Inicializar para saltar la primera fila

        // Iterar a través del archivo en chunks hasta que hayamos leído todas las filas
        while ($currentRow <= $sheet->getHighestRow()) {
            // Inicializar un array para almacenar los datos del chunk actual
            $chunkData = [];     
            // Leer un chunk de filas del archivo
            for ($i = 0; $i < $chunkSize && $currentRow <= $sheet->getHighestRow(); $i++) {
                $row = $sheet->getRowIterator($currentRow)->current(); // Obtener la fila actual
                $rowData = [];
                foreach ($row->getCellIterator() as $cell) {
                    $columnName = $cell->getColumn();
                    if (isset($columnMapping[$columnName]) && $columnName <= $columnafinal) {
                        $columnNameModel = $columnMapping[$columnName];

                        if($compania->tipo === 'NUBE'){
                            if ($columnName === 'E') {
                                // Convertir el valor de Excel a una fecha normal en formato 'Y-m-d'
                                $excelDate = $cell->getValue();
                                // Intentar analizar la fecha en el formato personalizado "dd/mm/yyyy"
                                $dateParts = explode('/', $excelDate);
                                
                                if (count($dateParts) === 3) {
                                    list($day, $month, $year) = $dateParts;
                                    
                                    // Construir la fecha en el formato 'd-m-Y'
                                    $formattedDate = sprintf('%02d-%02d-%04d', $day, $month, $year);
                                    $rowData[$columnNameModel] = $formattedDate;
                                } else {
                                    // Manejar las fechas que no se pueden analizar correctamente
                                    $rowData[$columnNameModel] = '0';
                                }
                            
                            } else {
                                $rowData[$columnNameModel] = $cell->getValue();
                            }
                        }else{
                            if ($columnName === 'F') {
                                // Convertir el valor de Excel a una fecha normal en formato 'Y-m-d'
                                $excelDate = $cell->getValue();
                                if (is_numeric($excelDate)) {
                                    $dateTimestamp = ($excelDate - 25569) * 86400; // Convertir de Excel a timestamp
                                    $phpDate = date('Y-m-d', $dateTimestamp);
                                    $rowData[$columnNameModel] = $phpDate;
                                } else {
                                    // Manejar el caso cuando $excelDate no es numérico (puedes asignar un valor predeterminado)
                                    $rowData[$columnNameModel] = '0';
                                }
                            } else {
                                $rowData[$columnNameModel] = $cell->getValue();
                            }
                        }
                    }
                    unset($cell);
                }

                $chunkData[] = $rowData; // Agregar los datos de la fila al chunk actual
                $currentRow++; // Pasar a la siguiente fila
            }

            // Agregar el chunk de datos al array principal
            $data = array_merge($data, $chunkData);
        }

    
        foreach ($data as &$record) {
            $record['Nit'] = $nit;
            $record[$fechareporte] = $fecha;
        }
        
        $data = array_chunk($data, 100);
        
        
        $insertData = [];
        foreach ($data as $chunk) {
            foreach ($chunk as $record) {
                if ($compania->tipo === 'NUBE') {
                    if (!empty($record['cuenta_contable_sw'])) {
                        // $record['saldo_inicial_sw'] = $saldoinicial;
                        $insertData[] = $record;
                    }
                } else {
                    if (!empty($record['cuenta'])) {
                        $insertData[] = $record;
                    }
                }
                unset($record);
            }
        
            if (!empty($insertData)) {
                
                
                $insertChunks = array_chunk($insertData, 100);
                foreach ($insertChunks as $insertChunk) {
                    ClientesMovimientos::insert($insertChunk);
                }
                // Liberar memoria después de la inserción
                unset($insertData);
                unset($insertChunks);
            }
        
            // Liberar memoria del chunk actual después de procesarlo
            unset($chunk);
        }
           // Validar que tenga el formato correcto (opcional)
        if (!Carbon::hasFormat($fecha, 'Y-m-d')) {
            throw new \Exception('Formato de fecha no válido.');
        }

        // Guardar
        $fechaExistente = new FechasExistentesIC();
        $fechaExistente->fecha_creacion = $fecha; // ya es 'Y-m-d'
        $fechaExistente->user_crea_id = auth()->id();
        $fechaExistente->empresa_id = $compania->id;
        $fechaExistente->save();
        // Liberar memoria del arreglo de chunks de datos original
        unset($data);
        // Si la importación se realizó con éxito
        Session::flash('message2', 'Importación exitosa');
        Session::flash('color', 'success');
        
    }

    public function batchSize(): int
    {
        return 1000;
    }

}