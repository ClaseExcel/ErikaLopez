<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Carbon\Carbon;
use App\Models\InformesGenericos;
use App\Models\Empresa;
use App\Models\FechasExistentesIC;

class SagImport implements ToCollection
{
    protected $requiredHeadings = [
        'k_sc_codigo_cuenta',
        'sc_naturaleza',
        'sc_nombre_cuenta',
        'n_saldo_anterior',
        'n_valor_debito',
        'n_valor_credito',
        'n_saldo_actual'
    ];

    protected $expectedNit, $fecha;

    public function __construct($expectedNit, $fecha)
    {
        $this->expectedNit = $expectedNit;
        $this->fecha = $fecha;
    }

    public function collection(Collection $rows)
    {
        if ($rows->isEmpty()) {
            throw new \Exception('El archivo está vacío.');
        }

        // ✅ Validar encabezados (fila 1)
        $this->validateHeadings($rows->first());

        // ✅ Validar formato fecha
        if (!Carbon::hasFormat($this->fecha, 'Y-m-d')) {
            throw new \Exception('Formato de fecha no válido.');
        }

        // ✅ Insertar datos desde fila 2
        foreach ($rows->skip(1) as $row) {

            $rowArray = array_values($row->toArray());
            $cuenta = trim($rowArray[0] ?? '');

            if (!empty($cuenta)) {
                InformesGenericos::create([
                    'Nit' => $this->expectedNit,
                    'cuenta' => $cuenta,
                    'descripcion' => $rowArray[2] ?? null,
                    'saldo_anterior' => $rowArray[3] ?? 0,
                    'debitos' => $rowArray[4] ?? 0,
                    'creditos' => $rowArray[5] ?? 0,
                    'saldo_final' => $rowArray[6] ?? 0,
                    'fechareporte' => $this->fecha,
                ]);
            }
        }

        // Guardar fecha en tabla de control
        $compania = Empresa::where('NIT', $this->expectedNit)->first();

        FechasExistentesIC::create([
            'fecha_creacion' => $this->fecha,
            'user_crea_id' => auth()->id(),
            'empresa_id' => $compania->id
        ]);
    }

    protected function validateHeadings($firstRow)
    {
        $headings = array_map('trim', array_values($firstRow->toArray()));

        $missingHeadings = array_diff($this->requiredHeadings, $headings);

        if (count($missingHeadings)) {
            throw new \Exception(
                "Faltan los siguientes encabezados: " . implode(', ', $missingHeadings)
            );
        }
    }
}
