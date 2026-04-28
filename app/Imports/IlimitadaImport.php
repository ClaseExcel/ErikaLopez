<?php

namespace App\Imports;

use App\Exceptions\NitMismatchException;
use App\Models\Empresa;
use App\Models\FechasExistentesIC;
use App\Models\InformesGenericos;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Str;

class IlimitadaImport implements ToCollection
{
    protected $requiredHeadings = [
        'Cuenta', 'Equivalencia', 'Nombre', 'NIT', 'Nombre NIT', 'Saldo Anterior', 'Débitos', 'Créditos', 'Nuevo Saldo'
    ];

    protected $expectedNit, $fecha;

    public function __construct($expectedNit, $fecha)
    {
        $this->expectedNit = $expectedNit;
        $this->fecha = $fecha;
    }

    public function collection(Collection $rows)
    {
        $this->validateHeadings($rows->get(2)); // Fila 3 (índice 2)
        $nitCell = $this->getNitFromFirstCell($rows);
        
        $this->validateNit($nitCell);
        foreach ($rows->slice(3) as $row) { // A partir de la fila 4
            $rowArray = array_values($row->toArray());
            $cuenta = trim($rowArray[0] ?? '');
            // Validar que no sea fila vacía o basura
            $esFilaValida = $cuenta !== '' && $cuenta !== '#N/A' && is_numeric($cuenta);

            if ($esFilaValida) {
                InformesGenericos::create([
                    'Nit'            => $nitCell,
                    'cuenta'         => $cuenta,
                    'descripcion'    => $rowArray[2] ?? null,
                    'tercero'        => $rowArray[3] ?? null,
                    'nombre'         => $rowArray[4] ?? null,
                    'saldo_anterior' => $this->cleanNumeric($rowArray[5] ?? null),
                    'debitos'        => $this->cleanNumeric($rowArray[6] ?? null),
                    'creditos'       => $this->cleanNumeric($rowArray[7] ?? null),
                    'saldo_final'    => $this->cleanNumeric($rowArray[8] ?? null),
                    'fechareporte'   => $this->fecha,
                ]);
            }
        }

        if (!Carbon::hasFormat($this->fecha, 'Y-m-d')) {
            throw new \Exception('Formato de fecha no válido.');
        }

        $empresa = Empresa::where('NIT', $this->expectedNit)->first();

        FechasExistentesIC::create([
            'fecha_creacion' => $this->fecha,
            'user_crea_id'   => auth()->id(),
            'empresa_id'     => $empresa->id,
        ]);
    }

    protected function cleanNumeric($value)
    {
        if (is_null($value) || $value === '' || $value === '#N/A') {
            return 0;
        }

        $value = str_replace(',', '', $value);

        return is_numeric($value) ? $value : 0;
    }

    protected function getNitFromFirstCell(Collection $rows)
    {
        // Extrae el NIT antes del guión y elimina puntos
         $firstCell = $rows[0][0] ?? '';

        if (strpos($firstCell, '-') !== false) {
            $partes = explode('-', $firstCell);

            if (isset($partes[1])) {
                $nitConPuntos = trim($partes[1]); // Esto te da: "901.333.569"
                $nitLimpio = str_replace('.', '', $nitConPuntos); // Resultado: "901333569"
                return $nitLimpio;
            }
        }

        return null;
    }

    protected function validateNit($nitCell)
    {
        if ($nitCell != $this->expectedNit) {
            throw new NitMismatchException("El NIT en el archivo ($nitCell) no coincide con el NIT proporcionado ({$this->expectedNit}).");
        }
    }

    protected function validateHeadings(Collection $headerRow)
    {
        $headings = array_map(function ($value) {
            return Str::of($value)->trim()->__toString();
        }, $headerRow->toArray());

        $missing = array_diff($this->requiredHeadings, $headings);
        if (count($missing)) {
            throw new \Exception("Faltan los siguientes encabezados: " . implode(', ', $missing));
        }
    }
}
