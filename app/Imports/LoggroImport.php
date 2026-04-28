<?php

namespace App\Imports;

use App\Exceptions\MissingHeadingsException;
use App\Exceptions\NitMismatchException;
use App\Models\Empresa;
use App\Models\FechasExistentesIC;
use App\Models\loggro;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LoggroImport implements ToCollection, WithHeadingRow, WithCustomCsvSettings
{

    protected $requiredHeadings = [
        'Cuenta', 'Nombre Cuenta', 'Saldo Anterior', 'Débito', 'Crédito','Neto', 'Saldo Final' // Lista de títulos requeridos
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
        $headings = $firstRow ? array_values($firstRow[4]->toArray()) : [];
        $this->validateHeadings($headings);
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
                'TOTAL ACTIVO :',
                'TOTAL PASIVO :',
                'TOTAL PATRIMONIO :',
                'PASIVO + PATRIMONIO + UT.:',
                'Total Ingresos :',
                'Total Egresos :',
                'Resultado ejercicio :',
            ];

            if (!empty($cuenta) && !in_array(trim($rowArray[1]), $descripcionesNoDeseadas)) {
                // Guardar cada fila en OtraTabla sin aplicar ningún filtro
                loggro::create([
                    'Nit' => $nitCell,
                    'cuenta' => $cuenta ?? null,
                    'descripcion' => $rowArray[1] ?? null,
                    'saldo_anterior' => $rowArray[2] ?? null,
                    'debitos' => $rowArray[3] ?? null,
                    'creditos' => $rowArray[4] ?? null,
                    'neto' => $rowArray[5] ?? null,
                    'saldo_final' => $rowArray[6] ?? null,
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
        // Obtener la segunda fila del archivo
        $secondRow = $rows->first(); // Saltamos la primera fila y tomamos la segunda
       
        if ($secondRow) {
            $secondRowArray = $secondRow->toArray(); // Convertimos a arreglo
            // Iteramos sobre las celdas de la segunda fila para encontrar el NIT
            foreach ($secondRowArray as $cellValue) {
                if ($cellValue && preg_match('/\b\d{6,}-\d\b/', $cellValue, $matches)) {
                     // Extraer solo los números antes del guion
                    preg_match('/\d{6,}/', $matches[0], $nitOnly);
                    // Retornar el NIT encontrado en el formato esperado
                    return $nitOnly[0] ?? null;
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

    protected function validateHeadings(array $headings)
    {
       
        $missingHeadings = array_diff($this->requiredHeadings, $headings);
        if (count($missingHeadings)) {
            throw new MissingHeadingsException($missingHeadings);
        }
    }
}
