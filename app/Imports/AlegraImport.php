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

class AlegraImport implements ToCollection
{
    protected $expectedNombreEmpresa, $fecha,$nit;

    public function __construct($expectedNombreEmpresa, $fecha,$nit)
    {
        $this->expectedNombreEmpresa = Str::upper(trim($expectedNombreEmpresa));
        $this->fecha = $fecha;
        $this->nit = $nit;
    }

    // public function collection(Collection $rows)
    // {
    //     $nombreEmpresaEnExcel = Str::upper(trim($rows[0][0] ?? ''));

    //     if ($nombreEmpresaEnExcel !== $this->expectedNombreEmpresa) {
    //         throw new \Exception("El nombre de empresa en el archivo ({$nombreEmpresaEnExcel}) no coincide con el esperado ({$this->expectedNombreEmpresa}).");
    //     }

    //     // Fila de encabezados esperada: fila 5 (índice 4)
    //     $headerRow = $rows[4] ?? collect();
    //     dd($headerRow);
    //     foreach ($rows->slice(5) as $row) { // A partir de la fila 6
    //         $rowArray = array_values($row->toArray());

    //         $cuenta = trim($rowArray[1] ?? '');
    //         $descripcion = $rowArray[2] ?? null;

    //         $esFilaValida = $cuenta !== '' && $cuenta !== '#N/A' && is_numeric($cuenta);
    //         if (!$esFilaValida) continue;

    //         // Calcula saldos: saldo anterior = débito - crédito de columnas 3-4
    //         $saldoAnterior = $this->cleanNumeric($rowArray[3] ?? 0) - $this->cleanNumeric($rowArray[4] ?? 0);
    //         $saldoFinal    = $this->cleanNumeric($rowArray[7] ?? 0) - $this->cleanNumeric($rowArray[8] ?? 0);

    //         InformesGenericos::create([
    //             'Nit'            => null, // No viene NIT
    //             'cuenta'         => $cuenta,
    //             'descripcion'    => $descripcion,
    //             'tercero'        => null,
    //             'nombre'         => null,
    //             'saldo_anterior' => $saldoAnterior,
    //             'debitos'        => $this->cleanNumeric($rowArray[5] ?? 0),
    //             'creditos'       => $this->cleanNumeric($rowArray[6] ?? 0),
    //             'saldo_final'    => $saldoFinal,
    //             'fechareporte'   => $this->fecha,
    //         ]);
    //     }

    //     if (!Carbon::hasFormat($this->fecha, 'Y-m-d')) {
    //         throw new \Exception('Formato de fecha no válido.');
    //     }

    //     $empresa = Empresa::whereRaw('UPPER(nombre) = ?', [$this->expectedNombreEmpresa])->first();

    //     if (!$empresa) {
    //         throw new \Exception("Empresa no encontrada en la base de datos con nombre: {$this->expectedNombreEmpresa}");
    //     }

    //     FechasExistentesIC::create([
    //         'fecha_creacion' => $this->fecha,
    //         'user_crea_id'   => auth()->id(),
    //         'empresa_id'     => $empresa->id,
    //     ]);
    // }
    public function collection(Collection $rows)
    {
        $nombreEmpresaEnExcel = Str::upper(trim($rows[0][0] ?? ''));

        if ($nombreEmpresaEnExcel !== $this->expectedNombreEmpresa) {
            throw new \Exception("El nombre de empresa en el archivo ({$nombreEmpresaEnExcel}) no coincide con el esperado ({$this->expectedNombreEmpresa}).");
        }

        $this->validateHeadings($rows[4]); // Validar títulos en la fila 5 (índice 4)

        foreach ($rows->slice(5) as $row) {
            $rowArray = array_values($row->toArray());

            $cuenta = trim($rowArray[1] ?? '');
            $descripcion = $rowArray[2] ?? null;

            $esFilaValida = $cuenta !== '' && $cuenta !== '#N/A' && is_numeric($cuenta);
            if (!$esFilaValida) continue;
            
            $saldoAnterior = $this->cleanNumeric($rowArray[3] ?? 0) - $this->cleanNumeric($rowArray[4] ?? 0);
            $saldoFinal    = $this->cleanNumeric($rowArray[7] ?? 0) - $this->cleanNumeric($rowArray[8] ?? 0);
            InformesGenericos::create([
                'Nit'            => $this->nit,
                'cuenta'         => $cuenta,
                'descripcion'    => $descripcion,
                'tercero'        => null,
                'nombre'         => null,
                'saldo_anterior' => $saldoAnterior,
                'debitos'        => $this->cleanNumeric($rowArray[5] ?? 0),
                'creditos'       => $this->cleanNumeric($rowArray[6] ?? 0),
                'saldo_final'    => $saldoFinal,
                'fechareporte'   => $this->fecha,
            ]);
        }

        if (!Carbon::hasFormat($this->fecha, 'Y-m-d')) {
            throw new \Exception('Formato de fecha no válido.');
        }

        $empresa = Empresa::where('NIT', $this->nit)->first();

        if (!$empresa) {
            throw new \Exception("Empresa no encontrada en la base de datos con nombre: {$this->expectedNombreEmpresa}");
        };

        FechasExistentesIC::create([
            'fecha_creacion' => $this->fecha,
            'user_crea_id'   => auth()->id(),
            'empresa_id'     => $empresa->id,
        ]);
    }

    protected function validateHeadings(Collection $headerRow)
    {
        $expected = [
            'Nivel',
            'Número de cuenta',
            'Cuenta contable',
            'Débito',
            'Crédito',
            'Débito',
            'Crédito',
            'Débito',
            'Crédito',
        ];

        $actual = array_map(function ($value) {
            return Str::of($value)->trim()->__toString();
        }, array_slice($headerRow->toArray(), 0, count($expected))); // Ignorar columnas nulas al final

        if ($actual !== $expected) {
            throw new \Exception("Los encabezados no coinciden con el formato esperado. Se esperaban: " . implode(' | ', $expected));
        }
    }


    protected function cleanNumeric($value)
    {
        if (is_null($value) || $value === '' || $value === '#N/A') {
            return 0;
        }

        $value = str_replace(',', '', $value);
        return is_numeric($value) ? (float)$value : 0;
    }
}

