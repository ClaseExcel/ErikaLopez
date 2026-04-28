<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Clientes;
use App\Models\Compania;
use App\Models\Empresa;
use App\Models\FechasExistentesIC;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ExcelClientesBatchImport implements ShouldQueue
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
        set_time_limit(300);
        ini_set('memory_limit', '2048M');
        $uploadedFile = new UploadedFile(
            $this->filePath,
            basename($this->filePath),
            mime_content_type($this->filePath),
            null,
            true
        );

        $nit = $this->nit; 
        $fecha =$this->fechareporte;
        $spreadsheet = IOFactory::load($uploadedFile);
        $sheet = $spreadsheet->getActiveSheet();
        $compania = Empresa::where('nit',$nit)->first();
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
            if ($columnTitle !== '') {
                $columnTitles[] = $columnTitle;
            }
        }
        if($compania->tipo === 'NUBE'){
             // Definir los títulos válidos
             // Posibles combinaciones válidas de columnas
                $validColumnsSet1 = ['Nivel', 'Transaccional', 'Códigocuentacontable', 'Nombrecuentacontable' ,'Identificación','Sucursal','Nombretercero', 'Saldoinicial', 'Movimientodébito', 'Movimientocrédito', 'Saldofinal'];
                $validColumnsSet2 = ['Nivel', 'Transaccional', 'Códigocuentacontable', 'Nombrecuentacontable', 'Saldoinicial', 'Movimientodébito', 'Movimientocrédito', 'Saldofinal'];
                //  $validColumns = ['Nivel', 'Transaccional', 'Códigocuentacontable', 'Nombrecuentacontable', 'Saldoinicial', 'Movimientodébito', 'Movimientocrédito', 'Saldofinal'];
            // Detectar qué set corresponde
            if ($columnTitles === $validColumnsSet1) {
                $columnMapping = [
                    'A' => 'nivel_ga',
                    'B' => 'transacional_ga',
                    'C' => 'codigo_cuenta_contable_ga',
                    'D' => 'nombre_cuenta_contable_ga',
                    'H' => 'saldo_inicial_ga',
                    'I' => 'movimiento_debito_ga',
                    'J' => 'movimiento_credito_ga',
                    'K' => 'saldo_final_ga',
                ];
                $columnafinal = 'K';
            } elseif ($columnTitles === $validColumnsSet2) {
                $columnMapping = [
                    'A' => 'nivel_ga',
                    'B' => 'transacional_ga',
                    'C' => 'codigo_cuenta_contable_ga',
                    'D' => 'nombre_cuenta_contable_ga',
                    'E' => 'saldo_inicial_ga',
                    'F' => 'movimiento_debito_ga',
                    'G' => 'movimiento_credito_ga',
                    'H' => 'saldo_final_ga',
                ];
                $columnafinal = 'H';
            } else {
                // Mostrar error si no coincide con ninguna de las dos
                $errorMessage = "El archivo Excel no tiene los títulos esperados. Se esperaba una de las siguientes combinaciones:\n\n" .
                                implode(', ', $validColumnsSet1) . "\n\nO bien:\n\n" . implode(', ', $validColumnsSet2);
                Session::flash('message2', $errorMessage);
                Session::flash('color', 'danger');
                return;
            }
            $fechareporte ='fechareporte_ga';
            $inicia=8;

        }else{
             // Definir los títulos válidos
             $validColumns = ['GRUPO', 'CUENTA', 'SUBCUENT', 'AUXILIAR', 'SUBAUXIL', 'SUCURSAL', 'DESCRIPCION', 'ULT.MOV.', 'SALDOANTERIOR', 'DEBITOS','CREDITOS','NUEVOSALDO'];
            // Mapeo de nombres de columna del Excel a los nombres del modelo
            $columnMapping = [
                'A' => 'grupo', 
                'B' => 'cuenta',
                'C' => 'subcuenta',
                'D' => 'auxiliar',
                'E' => 'subauxiliar',
                'G' => 'sucursal',
                'K' => 'descripcion',
                'L' => 'ultimo_movimiento',
                'M' => 'saldo_anterior',
                'N' => 'debitos',
                'O' => 'creditos',
                'P' => 'nuevo_saldo',
            ];
            $columnafinal='P';
            $fechareporte ='fechareporte';
            $inicia=7;
                  // Verificar si los títulos coinciden
            $invalidColumns = array_diff($validColumns, $columnTitles);
            if (!empty($invalidColumns)) {
                $errorMessage = "El archivo Excel no tiene los títulos esperados: " . implode(', ', $validColumns);
                Session::flash('message2',$errorMessage);
                Session::flash('color', 'danger');
                return;
            }
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
            foreach ($sheet->getRowIterator(4) as $row) {
                // Obtiene el valor de la cuarta columna (columna A)
                $cellValue = $sheet->getCell('A' . $row->getRowIndex())->getValue();
                // Utiliza una expresión regular para extraer el número
                $numeroNIT = explode(' ', $cellValue);
               // Busca el número NIT en el arreglo
                foreach ($numeroNIT as $value) {
                    // Utiliza una expresión regular para extraer el número
                    if (preg_match('/^\d+$/', $value)) {
                        // Si encontramos un valor que consiste solo en dígitos, consideramos que es el número NIT
                        $numeroNIT = $value;
                        break; // Terminamos el bucle después de encontrar el número
                    }
                }
                if ($numeroNIT != $nit){
                    $errorMessage = "Verifica el archivo el NIT que seleccionates en la pagina es: ".$nit. " y el NIT del excel es: ".$numeroNIT;
                                Session::flash('message2',$errorMessage);
                                Session::flash('color', 'danger');
                                return;
                }
                break;

            }
        }
        

        $data = [];
        $skipFirstRow = true;
        foreach ($sheet->getRowIterator($inicia) as $row) {

            if ($skipFirstRow) {
                $skipFirstRow = false;
                continue; // Saltar la primera iteración
            }

            $rowData = [];
            foreach ($row->getCellIterator() as $cell) {
                $columnName = $cell->getColumn();
                if (isset($columnMapping[$columnName]) && $columnName <= $columnafinal) {
                    $columnNameModel = $columnMapping[$columnName];
                    if($nit === '900300651'  || $nit === '901364757'){
                        $rowData[$columnNameModel] = $cell->getValue();
                    }else{
                        if ($columnName === 'L') {
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
            }
            $data[] = $rowData;
        }
        foreach ($data as &$record) {
            $record['Nit'] = $nit;
            $record[$fechareporte] = $fecha;
        }

        set_time_limit(300);
        if($compania->tipo === 'NUBE'){
            Clientes::where('Nit', $nit)
            ->whereRaw('MONTH(fechareporte_ga) = MONTH(?) AND YEAR(fechareporte_ga) = YEAR(?)', [$fecha, $fecha])
            ->delete();
        }else{
            Clientes::where('Nit', $nit)
            ->whereRaw('MONTH(fechareporte) = MONTH(?) AND YEAR(fechareporte) = YEAR(?)', [$fecha, $fecha])
            ->delete();
        }
        
        $data = array_chunk($data,200);
        ini_set('memory_limit', '2048M');
        foreach ($data as $chunk) {
            $insertData = [];
            foreach ($chunk as $record) {
                 // Verificar si el campo "grupo" no está vacío antes de insertar los datos
                 if($compania->tipo === 'NUBE'){
                    if(!empty($record['transacional_ga'])){
                        $insertData[] = $record;
                    }
                    
                 }else{
                    if (!empty($record['grupo'])) {
                        $insertData[] = $record;
                    }
                 }
                
            }
            if (!empty($insertData)) {
                Clientes::insert($insertData);
            }
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
        // Si la importación se realizó con éxito
        Session::flash('message2', 'Importación exitosa');
        Session::flash('color', 'success');
    }
}
