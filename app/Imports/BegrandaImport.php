<?php

namespace App\Imports;

use App\Exceptions\MissingHeadingsException;
use App\Exceptions\NitMismatchException;
use App\Models\Begranda;
use App\Models\Empresa;
use App\Models\FechasExistentesIC;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BegrandaImport implements ToCollection
{
    protected $requiredHeadings = [
        'Cuenta', 'Nombre', 'Saldo Anterior', 'Débito', 'Crédito', 'Saldo Final' // Lista de títulos requeridos
    ];

    protected $expectedNit, $fecha;
    protected $estadoSituacionFinancieraKey;

    public function __construct($expectedNit, $fecha)
    {
        $this->expectedNit = $expectedNit;
        $this->fecha = $fecha;
    }
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        // Extraer los encabezados de la primera fila y validar
        $firstRow = $rows->slice(4);
        $this->validateHeadings($firstRow);
         // Validar el NIT directamente desde la primera celda
         $nitCell = $this->getNitFromFirstCell($rows);
         $this->validateNit($nitCell);
         // Identificar la clave para 'estado de situacion financiera' basándose en la posición
        foreach ($rows->slice(5) as $row) { // slice(6) para comenzar desde la fila 7
            $rowArray = array_values($row->toArray());
            $cuentaKey = $this->findCuentaKey($rowArray);
            $cuenta = trim($rowArray[$cuentaKey] ?? '');
            // Array de descripciones que no se deben guardar
            $descripcionesNoDeseadas = [
                'T O T A L E S',
            ];

            if (!empty($cuenta) && !in_array(trim($rowArray[1]), $descripcionesNoDeseadas)) {
                // Guardar cada fila en OtraTabla sin aplicar ningún filtro
                Begranda::create([
                    'Nit' => $nitCell,
                    'cuenta' => $cuenta ?? null,
                    'descripcion' => $rowArray[1] ?? null,
                    'saldo_anterior' => $rowArray[4] ?? null,
                    'debitos' => $rowArray[5] ?? null,
                    'creditos' => $rowArray[6] ?? null,
                    'saldo_final' => $rowArray[7] ?? null,
                    'fechareporte' => $this->fecha,
                ]);
            }
        }
        // Validar que tenga el formato correcto (opcional)
        if (!Carbon::hasFormat($this->fecha, 'Y-m-d')) {
            throw new \Exception('Formato de fecha no válido.');
        }
        // Obtener el NIT de la primera fila
        $nit = $this->expectedNit;
        $compania = Empresa::where('NIT',$nit)->first();
        // Guardar
        $fechaExistente = new FechasExistentesIC();
        $fechaExistente->fecha_creacion = $this->fecha; // ya es 'Y-m-d'
        $fechaExistente->user_crea_id = auth()->id();
        $fechaExistente->empresa_id = $compania->id;
        $fechaExistente->save();
    }

    protected function findCuentaKey($row)
    {
        foreach ($row as $key => $value) {
            if (is_numeric($value)) {
                return $key;
            }
        }

        return null;
    }

    public function headingRow(): int
    {
        return 1;
    }

    protected function getNitFromFirstCell(Collection $rows)
    {
        if ($rows->count() > 1 && $rows[1] instanceof Collection) {
            $secondRowArray = $rows[2]->toArray();
            
            // Iterar sobre los elementos para buscar el NIT
            foreach ($secondRowArray as $cellValue) {
                // Depurar el valor exacto de cada celda
                if ($cellValue) {
                    $trimmedValue = trim($cellValue); // Elimina espacios antes y después
                    if (preg_match('/\d{6,}\s*-\s*\d/', $trimmedValue, $matches)) {
                        // Extraer solo los números antes del guion
                        preg_match('/\d{6,}/', $matches[0], $nitOnly);
                        // Retornar el NIT encontrado en el formato esperado
                        return $nitOnly[0] ?? null;
                    }
                }
            }
        }
    
        return null; // Si no encuentra el NIT, retornar null
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ',',
            'enclosure' => '"',
            'escape_character' => '\\',
        ];
    }

    protected function validateNit($nitCell)
    {
        if ($nitCell != $this->expectedNit) {
            throw new NitMismatchException('El NIT en el archivo no coincide con el NIT proporcionado.');
        }
    }

    protected function validateHeadings(Collection $firstRow)
    {
            // Extraer los encabezados en las posiciones esperadas
            $headings = $firstRow->get(4) ? array_values($firstRow->get(4)->toArray()) : [];
            // Verificar que las posiciones específicas en $headings y $headings2 no estén vacías y contengan los valores esperados
            if (empty($headings[0]) || empty($headings[1]) || empty($headings[4]) || empty($headings[5]) || empty($headings[6]) || empty($headings[7])) {
                throw new NitMismatchException('El archivo no tiene la estructura esperada. Verifique que los encabezados estén en las posiciones correctas.');
            }
    
            // Combinar y limpiar encabezados según las posiciones específicas
            $combinedHeadings = [
                strtolower(trim($headings[0] ?? '')),           // 'cuenta'
                strtolower(trim($headings[1] ?? '')),           // 'descripcion'
                strtolower(trim($headings[4] ?? '')),          // 'saldo_anterior'
                strtolower(trim($headings[5] ?? '')),          // 'debitos'
                strtolower(trim($headings[6] ?? '')),          // 'creditos'
                strtolower(trim($headings[7] ?? '')),         // 'saldo'
            ];
            
            // Limpiar valores nulos o vacíos
            $cleanedHeadings = array_filter($combinedHeadings, fn($value) => !empty($value));
         
            // Renombrar valores a nombres uniformes para la estructura deseada
            $normalizedHeadings = array_map(function ($value) {
                
                $map = [
                    'cuenta' => 'Cuenta',
                    'nombre' => 'Nombre',
                    'saldo anterior' => 'Saldo Anterior',
                    'dÉbito' => 'Débito',
                    'crÉdito' => 'Crédito',
                    'saldo final' => 'Saldo Final',
                ];
                return $map[$value] ?? $value;
            }, $cleanedHeadings);
            
            // Comparar los encabezados normalizados con los encabezados requeridos
            $missingHeadings = array_diff($this->requiredHeadings, $normalizedHeadings);
            // Si faltan encabezados, lanzar excepción con los nombres de los encabezados faltantes
            if (count($missingHeadings)) {
                throw new NitMismatchException("Faltan los siguientes encabezados: " . implode(', ', $missingHeadings));
            }

    }
}
