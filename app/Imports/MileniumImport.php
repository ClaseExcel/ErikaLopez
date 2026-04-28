<?php

namespace App\Imports;

use App\Exceptions\NitMismatchException;
use App\Models\Empresa;
use App\Models\FechasExistentesIC;
use App\Models\InformesGenericos;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class MileniumImport implements ToCollection
{

    protected $requiredHeadings = [
        'código', 'descripción', 'saldo anterior', 'débito', 'crédito', 'nuevo saldo'
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
        $firstRow = $rows->slice(0); // Encabezados están en fila 1 (índice 1)
        $this->validateHeadings($firstRow);

        $nitCell = $this->expectedNit;

        foreach ($rows->slice(2) as $row) { // slice(6) para comenzar desde la fila 7
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


    protected function mergeHeaderRows($row1, $row2)
    {
        $headers = [];

        for ($i = 0; $i < max(count($row1), count($row2)); $i++) {
            $part1 = trim((string) ($row1[$i] ?? ''));
            $part2 = trim((string) ($row2[$i] ?? ''));

            $combined = trim($part1 . ' ' . $part2); // Junta ambos (evita nulos)
            $headers[] = strtolower($combined);     // todo en minúsculas
        }

        return $headers;
    }

    protected function validateHeadings(Collection $firstRow)
    {

         // Suponiendo que las filas 6 y 7 (índices 5 y 6) son los encabezados combinados
        $row1 = $firstRow->get(0) ? array_values($firstRow->get(0)->toArray()) : [];
        $row2 = $firstRow->get(1) ? array_values($firstRow->get(1)->toArray()) : [];

        $headings = $this->mergeHeaderRows($row1, $row2); // ahora es un array plano
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
                'cuenta código' => 'código',
                'cuenta còdigo' => 'código',
                'cuenta codigo' => 'código',
                'descripciòn' => 'descripción',
                'cuenta descripción' => 'descripción',
                'saldo anterior' => 'saldo anterior',
                'movimiento débito' => 'débito',
                'movimiento crédito' => 'crédito',
                'nuevo saldo' => 'nuevo saldo',
            ];
            return $map[strtolower(trim($value))] ?? strtolower(trim($value));
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
