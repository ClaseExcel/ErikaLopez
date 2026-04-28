<?php

namespace App\Services;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MetasEmpresaExport;
use App\Models\Empresa;

class MetasExportExcelService
{
    /**
     * Genera y retorna la descarga del Excel.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
     */
    public function execute(Request $request)
    {
        $empresaId = $request->input('empresa_id');
        $nit = $request->input('nit');
        $fecha = $request->input('fecha') ?? null;

        if (!$empresaId) {
            return redirect()->back()->with('message2', 'Falta empresa para exportar')->with('color', 'warning');
        }

        $informePorMesRaw = $request->input('informePorMes', []);
        $metasComparative = app(MetasComparativeService::class)->execute($empresaId, $informePorMesRaw, $fecha);

        $empresa = Empresa::select('razon_social', 'logocliente')->where('id', $empresaId)->orWhere('NIT', $nit)->first();

        $fileName = 'Presupuesto ' . ($empresa->razon_social ?? $nit ?? $empresaId) . ' ' . date('Y-m-d') . '.xlsx';
        return Excel::download(new MetasEmpresaExport($metasComparative, [
            'empresa' => $nit ?? $empresaId,
            'fecha' => $fecha,
            'logo' => $empresa->logocliente ?? null,
            'compania' => $empresa->razon_social ?? null,
        ]), $fileName);
    }
}
