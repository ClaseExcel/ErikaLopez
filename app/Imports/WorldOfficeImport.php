<?php

namespace App\Imports;

use App\Exceptions\NitMismatchException;
use App\Models\Empresa;
use App\Models\FechasExistentesIC;
use App\Models\InformesGenericos;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class WorldOfficeImport implements ToCollection
{
    protected $requiredHeadings = [
        'CODIGO', 'NOMBRE CUENTA','TERCERO', 'SALDO INICIAL', 'DEBITOS', 'CREDITOS', 'SALDO FINAL'
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
        $firstRow = $rows->slice(4); // Encabezados están en fila 5 (índice 4)
        $this->validateHeadings($firstRow);
        $nitCell = $this->getNitFromFirstCell($rows);
        $this->validateNit($nitCell);
        foreach ($rows->slice(5) as $row) { // slice(6) para comenzar desde la fila 7
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
                    'saldo_anterior' => $rowArray[3] ?? null,
                    'debitos' => $rowArray[4] ?? null,
                    'creditos' => $rowArray[5] ?? null,
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

        $headings = $firstRow->get(4) ? array_values($firstRow->get(4)->toArray()) : [];
       
        if (empty($headings[0]) || empty($headings[1]) || empty($headings[2]) || empty($headings[3]) || empty($headings[4]) || empty($headings[5]) || empty($headings[6])) {
            throw new NitMismatchException('El archivo no tiene la estructura esperada. Verifique que los encabezados estén en las posiciones correctas.');
        }
        
        // Limpiar y normalizar encabezados
        $combinedHeadings = [
            trim($headings[0] ?? ''),
            trim($headings[1] ?? ''),
            trim($headings[2] ?? ''),
            trim($headings[3] ?? ''),
            trim($headings[4] ?? ''),
            trim($headings[5] ?? ''),
            trim($headings[6] ?? ''),
        ];
        
         
        $normalizedHeadings = array_map(function ($value) {
            $map = [
                'CODIGO' => 'CODIGO',
                'NOMBRE CUENTA' => 'NOMBRE CUENTA',
                'TERCERO' => 'TERCERO',
                'SALDO INICIAL' => 'SALDO INICIAL',
                'DEBITOS' => 'DEBITOS',
                'CREDITOS' => 'CREDITOS',
                'SALDO FINAL' => 'SALDO FINAL'
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

