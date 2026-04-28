<?php

namespace App\Imports;

use App\Models\ContapymeBalance;
use App\Exceptions\MissingHeadingsException;
use App\Exceptions\NitMismatchException;
use App\Models\Empresa;
use App\Models\FechasExistentesIC;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class ContapymeBalanceImport implements ToCollection, WithMultipleSheets, WithHeadingRow, WithCustomCsvSettings
{
    protected $requiredHeadings = [
        'codcuentanivel1', 'codcuentanivel2', 'codcuentanivel3', 'codcuentanivel4', 'codcuentanivel5',
        'saldoinicialdet5', 'totaldebitosdet5', 'totalcreditosdet5', 'saldofinaldet5', 'cuenta_o_tercero',
        'saldoinicialdet', 'totaldebitosdet', 'totalcreditosdet', 'saldofinaldet' // Lista de títulos requeridos
    ];

    protected $expectedNit, $fecha;

    public function __construct($expectedNit, $fecha)
    {
        $this->expectedNit = $expectedNit;
        $this->fecha = $fecha;
    }

    public function sheets(): array
    {
        return [
            0 => new NitSheetImport($this->expectedNit),
            1 => $this,
        ];
    }

    public function collection(Collection $rows)
    {
        $this->validateHeadings($rows->first()->keys()->toArray());

        foreach ($rows as $row) {
            ContapymeBalance::create([
                'Nit' => $this->expectedNit,
                'CodCuentaNivel1' => $row['codcuentanivel1'],
                'CodCuentaNivel2' => $row['codcuentanivel2'],
                'CodCuentaNivel3' => $row['codcuentanivel3'],
                'CodCuentaNivel4' => $row['codcuentanivel4'],
                'CodCuentaNivel5' => $row['codcuentanivel5'],
                'SaldoInicialDet5' => $row['saldoinicialdet5'],
                'TotalDebitosDet5' => $row['totaldebitosdet5'],
                'TotalCreditosDet5' => $row['totalcreditosdet5'],
                'SaldoFinalDet5' => $row['saldofinaldet5'],
                'Cuenta_o_Tercero' => $row['cuenta_o_tercero'],
                'SaldoInicialDet' => $row['codcuentanivel5'],
                'TotalDebitosDet' => $row['codcuentanivel5'],
                'TotalCreditosDet' => $row['codcuentanivel5'],
                'SaldoFinalDet' => $row['codcuentanivel5'],
                'fechareporte' => $this->fecha,
            ]);
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

    public function headingRow(): int
    {
        return 1;
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ',',
            'enclosure' => '"',
            'escape_character' => '\\',
        ];
    }

    protected function validateHeadings(array $headings)
    {
        $missingHeadings = array_diff($this->requiredHeadings, $headings);
        if (count($missingHeadings)) {
            throw new MissingHeadingsException($missingHeadings);
        }
    }
}

class NitSheetImport implements ToCollection
{
    protected $expectedNit;

    public function __construct($expectedNit)
    {
        $this->expectedNit = $expectedNit;
    }

    public function collection(Collection $rows)
    {
        $nitCell = $this->findNitCell($rows);
        $nitCell = $this->findNitCell($rows->slice(1, 1)); // Buscar en la primera fila
        $this->validateNit($nitCell);
    }

    protected function findNitCell(Collection $rows)
    {
        // Recorrer las celdas de la primera fila para encontrar el valor del NIT
        foreach ($rows->first() as $key => $value) {
            if (stripos($value, 'Nit') !== false) {
                // Extraer solo los números del NIT
                preg_match('/\d+/', $value, $matches);
                return isset($matches[0]) ? $matches[0] : null;
            }
        }

        return null; // Devolver null si no se encuentra el NIT
    }

    protected function validateNit($nitCell)
    {
        if ($nitCell != $this->expectedNit) {
            throw new NitMismatchException('El NIT en el archivo no coincide con el NIT proporcionado.');
        }
    }
}
