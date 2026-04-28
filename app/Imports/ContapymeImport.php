<?php

namespace App\Imports;

use App\Exceptions\MissingHeadingsException;
use App\Exceptions\NitMismatchException;
use App\Models\Contapyme;
use App\Models\ContapymeCompleto;
use App\Models\Empresa;
use App\Models\FechasExistentesIC;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Validators\ValidationException;
use Illuminate\Support\Facades\Log;


class ContapymeImport implements ToCollection, WithHeadingRow, WithCustomCsvSettings
{
    protected $requiredHeadings = [
        'cuenta', 'descripcion', 'saldo_anterior', 'debitos', 'creditos', 'saldo' // Lista de títulos requeridos
    ];

    protected $expectedNit, $fecha;
    protected $estadoSituacionFinancieraKey;

    public function __construct($expectedNit, $fecha)
    {
        $this->expectedNit = $expectedNit;
        $this->fecha = $fecha;
    }

    public function collection(Collection $rows)
    {
        $firstRow = $rows->slice(3);
        $this->validateHeadings($firstRow);
        // Validar el NIT directamente desde la primera celda
        $nitCell = $this->getNitFromFirstCell($rows);
        $this->validateNit($nitCell);
        // Identificar la clave para 'estado de situacion financiera' basándose en la posición
        $firstRow = $rows->first();
        $firstRowArray = $firstRow->toArray();
        $this->estadoSituacionFinancieraKey = array_keys($firstRowArray)[8];
        
        foreach ($rows->slice(6) as $row) { // slice(6) para comenzar desde la fila 7
            $cuentaKey = $this->findCuentaKey($row);
            $cuenta = trim($row[$cuentaKey] ?? '');
            // Array de descripciones que no se deben guardar
            $descripcionesNoDeseadas = [
                'TOTAL ACTIVO :',
                'TOTAL PASIVO :',
                'TOTAL PATRIMONIO :',
                'PASIVO + PATRIMONIO + UT.:',
                'Total Ingresos :',
                'Total Egresos :',
            ];
            if (!empty($cuenta) && !in_array(trim($row[2]), $descripcionesNoDeseadas)) {
                // Guardar cada fila en OtraTabla sin aplicar ningún filtro
               ContapymeCompleto::create([
                    'Nit'            => $nitCell,
                    'cuenta'         => $cuenta ?? null,
                    'descripcion'    => $row[2] ?? null,
                    'saldo_anterior' => $this->cleanNumber($row[$this->estadoSituacionFinancieraKey] ?? null),
                    'debitos'        => $this->cleanNumber($row[10] ?? null),
                    'creditos'       => $this->cleanNumber($row[12] ?? null),
                    'nuevo_saldo'    => $this->cleanNumber($row[15] ?? null),
                    'fechareporte'   => $this->fecha,
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

    private function cleanNumber($value)
    {
        if ($value === null) {
            return null;
        }

        $value = trim($value);

        // Eliminar símbolo de moneda y espacios
        $value = str_replace(['$', ' '], '', $value);

        // Validar que solo quede un "-" y al inicio
        if (substr_count($value, '-') > 1) {
            $value = ltrim($value, '-');
        }
        if (strpos($value, '-') > 0) {
            $value = '-' . str_replace('-', '', $value);
        }

        return $value;
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
        // Obtener el valor de la primera celda
        $firstRow = $rows->first();
        $firstRowArray = $firstRow->toArray(); // Convertir a arreglo
        $firstCellValue = reset($firstRowArray); // Obtener el primer valor del arreglo
        if ($firstCellValue && stripos($firstCellValue, 'Nit') !== false) {
            preg_match('/\d+/', $firstCellValue, $matches);
            return isset($matches[0]) ? $matches[0] : null;
        }

        return null;
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
            throw new NitMismatchException('El NIT en el archivo'.$nitCell.' no coincide con el NIT proporcionado. '.$this->expectedNit);
        }
    }

    protected function validateHeadings(Collection $firstRow)
    {
        // Extraer los encabezados en las posiciones esperadas
        $headings = $firstRow->get(4) ? array_values($firstRow->get(4)->toArray()) : [];
        $headings2 = $firstRow->get(3) ? array_values($firstRow->get(3)->toArray()) : [];

        // Verificar que las posiciones específicas en $headings y $headings2 no estén vacías y contengan los valores esperados
        if (empty($headings[0]) || empty($headings[2]) || empty($headings2[8]) || empty($headings[10]) || empty($headings[12]) || empty($headings2[15])) {
            throw new NitMismatchException('El archivo no tiene la estructura esperada. Verifique que los encabezados estén en las posiciones correctas.');
        }

        // Combinar y limpiar encabezados según las posiciones específicas
        $combinedHeadings = [
            strtolower(trim($headings[0] ?? '')),           // 'cuenta'
            strtolower(trim($headings[2] ?? '')),           // 'descripcion'
            strtolower(trim($headings2[8] ?? '')),          // 'saldo_anterior'
            strtolower(trim($headings[10] ?? '')),          // 'debitos'
            strtolower(trim($headings[12] ?? '')),          // 'creditos'
            strtolower(trim($headings2[15] ?? '')),         // 'saldo'
        ];

        // Limpiar valores nulos o vacíos
        $cleanedHeadings = array_filter($combinedHeadings, fn($value) => !empty($value));

        // Renombrar valores a nombres uniformes para la estructura deseada
        $normalizedHeadings = array_map(function ($value) {
            $map = [
                'cuenta' => 'cuenta',
                'descripción' => 'descripcion',
                'saldo anterior' => 'saldo_anterior',
                'debitos' => 'debitos',
                'creditos' => 'creditos',
                'saldo' => 'saldo',
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
