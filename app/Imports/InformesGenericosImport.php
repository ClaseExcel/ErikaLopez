<?php

namespace App\Imports;

use App\Exceptions\NitMismatchException;
use App\Models\Empresa;
use App\Models\FechasExistentesIC;
use App\Models\InformesGenericos;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class InformesGenericosImport implements ToCollection
{
    protected $requiredHeadings = [
        'Cta Contable', 'Nombre Cuenta', 'Saldo Anterior', 'Debitos', 'Creditos', 'Saldo Final'
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
        // Validar encabezados y NIT
        $firstRow = $rows->slice(5); // Encabezados están en fila 6 (índice 5)
        $this->validateHeadings($firstRow);

        // $nitCell = $this->getNitFromFirstCell($rows);
        // $this->validateNit($nitCell);
        $nitCell = $this->expectedNit;

        foreach ($rows->slice(6) as $row) { // slice(6) para comenzar desde la fila 7
            $rowArray = array_values($row->toArray());
            $cuenta = trim($rowArray[0] ?? '');

            $descripcionesNoDeseadas = [
                'TOTAL GENERAL',
            ];

            if (!empty($cuenta) && !in_array(trim($rowArray[1]), $descripcionesNoDeseadas)) {
                InformesGenericos::create([
                    'Nit' => $nitCell,
                    'cuenta' => $cuenta ?? null,
                    'descripcion' => $rowArray[1] ?? null,
                    'saldo_anterior' => $rowArray[2] ?? null,
                    'debitos' => $rowArray[3] ?? null,
                    'creditos' => $rowArray[4] ?? null,
                    'saldo_final' => $rowArray[5] ?? null,
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

    protected function getNitFromFirstCell(Collection $rows)
    {
        if ($rows->count() > 1 && $rows[1] instanceof Collection) {
            $secondRowArray = $rows[1]->toArray();

            foreach ($secondRowArray as $cellValue) {
                $trimmedValue = trim($cellValue);

                // Buscar primero el formato con guión (ej: 123456789-0)
                if (preg_match('/\d{6,}\s*-\s*\d/', $trimmedValue, $matches)) {
                    preg_match('/\d{6,}/', $matches[0], $nitOnly);
                    return $nitOnly[0] ?? null;
                }

                // Buscar formato sin guión (ej: "NIT 909928191" o "ni 909928191")
                if (preg_match('/(?:NIT|ni)\s*(\d{6,})/i', $trimmedValue, $matches)) {
                    return $matches[1];
                }

                // Buscar directamente un número de al menos 6 cifras
                if (preg_match('/\b\d{6,}\b/', $trimmedValue, $matches)) {
                    return $matches[0];
                }
            }
        }

        return null;
    }


    protected function validateNit($nitCell)
    {
        if ($nitCell != $this->expectedNit) {
            throw new NitMismatchException('El NIT en el archivo no coincide con el NIT proporcionado.');
        }
    }

    protected function validateHeadings(Collection $firstRow)
    {

        $headings = $firstRow->get(5) ? array_values($firstRow->get(5)->toArray()) : [];
        if (empty($headings[0]) || empty($headings[1]) || empty($headings[2]) || empty($headings[3]) || empty($headings[4]) || empty($headings[5])) {
            throw new NitMismatchException('El archivo no tiene la estructura esperada. Verifique que los encabezados estén en las posiciones correctas.');
        }
        
        // Limpiar y normalizar encabezados
        $combinedHeadings = [
            strtolower(trim($headings[0] ?? '')),
            strtolower(trim($headings[1] ?? '')),
            strtolower(trim($headings[2] ?? '')),
            strtolower(trim($headings[3] ?? '')),
            strtolower(trim($headings[4] ?? '')),
            strtolower(trim($headings[5] ?? '')),
        ];
        

        $normalizedHeadings = array_map(function ($value) {
            $map = [
                'cta contable' => 'Cta Contable',
                'nombre cuenta' => 'Nombre Cuenta',
                'saldo anterior' => 'Saldo Anterior',
                'debitos' => 'Debitos',
                'creditos' => 'Creditos',
                'saldo final' => 'Saldo Final'
            ];
            return $map[$value] ?? $value;
        }, $combinedHeadings);
        
        $missingHeadings = array_diff($this->requiredHeadings, $normalizedHeadings);
        if (count($missingHeadings)) {
            throw new NitMismatchException("Faltan los siguientes encabezados: " . implode(', ', $missingHeadings));
        }
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ',',
            'enclosure' => '"',
            'escape_character' => '\\',
        ];
    }
}

