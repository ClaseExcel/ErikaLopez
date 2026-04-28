<?php

namespace App\Imports;

use App\Services\MetasEmpresaService;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToArray;

class MetasMasivoImport implements ToArray
{
    protected int $empresaId;
    protected int $anio;
    protected MetasEmpresaService $service;

    public function __construct(int $empresaId, int $anio, MetasEmpresaService $service)
    {
        $this->empresaId = $empresaId;
        $this->anio = $anio;
        $this->service = $service;
    }

    public function array(array $rows)
    {
        $filas = [];

        foreach ($rows as $index => $row) {
            if ($index < 2) {
                continue;
            }

            $cuenta = trim((string) ($row[0] ?? ''));
            $nombre = trim((string) ($row[1] ?? ''));

            if ($cuenta === '' && $nombre === '') {
                continue;
            }

            if ($cuenta === '' || $nombre === '') {
                throw ValidationException::withMessages([
                    'archivo' => 'Fila ' . ($index + 1) . ': la cuenta y la descripción son obligatorias.',
                ]);
            }

            $values = [];
            for ($col = 2; $col < 14; $col++) {
                $values[] = $this->service->normalizeExcelNumber($row[$col] ?? 0);
            }

            $filas[] = [
                'cuenta' => $cuenta,
                'nombre' => $nombre,
                'values' => $values,
            ];
        }

        if (empty($filas)) {
            throw ValidationException::withMessages([
                'archivo' => 'El archivo no contiene filas válidas.',
            ]);
        }

        $this->service->saveYear($this->empresaId, $this->anio, $filas);
    }
}
