<?php

namespace App\Services;

use App\Models\Empresa;
use Dompdf\Dompdf;
use Dompdf\Options;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MetasExportPdfService
{
    protected MetasComparativeService $metasService;

    public function __construct(MetasComparativeService $metasService)
    {
        $this->metasService = $metasService;
    }

    /**
     * Genera y retorna el stream del PDF (Dompdf) para preview o descarga.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function execute(Request $request)
    {
        $empresaId = $request->input('empresa_id');
        $nit = $request->input('nit');
        $fecha = $request->input('fecha') ?? null;
        $empresa = Empresa::select('razon_social', 'logocliente')->where('id', $empresaId)->orWhere('NIT', $nit)->first();

        if (!$empresaId) {
            return redirect()->back()->with('message2', 'Falta empresa para exportar')->with('color', 'warning');
        }

        // Decodificar informePorMes RAW (no hace falta decodificar aquí, lo procesa el service)
        $informePorMesRaw = $request->input('informePorMes', []);
        $metasComparative = $this->metasService->execute($empresaId, $informePorMesRaw, $fecha);

        // Construir grupos de meses para tablas (máximo 7 meses por tabla) y detectar si hay columna 'Total' para agregarla al final
        $maxMonthsPerTable = 7;
        $allMonths = array_keys($metasComparative);

        // Detecto si el último mes es 'Total' (ignorando mayúsculas) y lo separo para agregarlo al final de la última tabla
        $hasTotal = false;
        if (!empty($allMonths) && end($allMonths) === 'Total') {
            $hasTotal = true;
            array_pop($allMonths);
        }

        // Construyo un nuevo array de meses con su label formateado (si el mes es una fecha válida, lo formateo como "Mes Año", sino lo dejo tal cual)
        $monthsWithLabel = [];
        foreach ($allMonths as $k) {
            $label = $k;
            if (preg_match('/^\d{4}-\d{2}(-\d{2})?$/', $k)) {
                try {
                    $dt = Carbon::createFromFormat(strlen($k) === 7 ? 'Y-m' : 'Y-m-d', $k);
                    $label = ucfirst($dt->isoFormat('MMMM YYYY'));
                } catch (\Throwable $e) {
                    $label = ucfirst($k);
                }
            } else {
                $label = ucfirst(mb_strtolower($k));
            }
            $monthsWithLabel[$k] = $label;
        }

        // Agrupo los meses en subarrays de máximo $maxMonthsPerTable meses (sin perder el orden original) para luego renderizar cada grupo en una tabla diferente
        $rawMonthGroups = array_chunk($monthsWithLabel, $maxMonthsPerTable, true);

        // Si había columna 'Total', la agrego al final del último grupo (si el último grupo ya tiene 7 meses, creo un nuevo grupo solo para 'Total')
        if ($hasTotal) {
            $lastIdx = count($rawMonthGroups) - 1;
            if ($lastIdx < 0) {
                $rawMonthGroups[] = ['Total' => 'Total'];
            } else {
                $rawMonthGroups[$lastIdx]['Total'] = 'Total';
            }
        }

        // Definir papel, orientación y filas por página para calcular saltos de página (asumiendo un máximo de 48 filas por página en A3 landscape, incluyendo header y footer)
        $paper = 'A3';
        $orientation = 'landscape';
        $rowsPerPage = ($paper === 'A3' && $orientation === 'landscape') ? 48 : 28;

        // Calcular cuántas filas ocuparía cada grupo de meses (asumiendo un máximo de 1 fila por cuenta, más 4 filas extra para header y footer) y determinar si se necesita un salto de página antes de cada grupo
        $monthGroups = [];
        $currentUsedRows = 0;
        $rowsPerDataRow = 1;
        $headerFooterExtra = 4;

        // Para calcular el número de filas que ocuparía cada grupo, necesito saber cuántas cuentas hay en total (asumiendo que cada cuenta ocupa una fila). Tomo el primer mes del informe para contar las cuentas (si no hay meses, asumo 0 cuentas).
        $primer = reset($metasComparative);
        $numDataRows = isset($primer['cuentas']) ? count($primer['cuentas']) : 0;

        // Recorro cada grupo de meses, calculo su número estimado de filas y determino si necesito un salto de página antes de renderizar ese grupo
        foreach ($rawMonthGroups as $group) {
            $groupEstimatedRows = ($numDataRows * $rowsPerDataRow) + $headerFooterExtra;

            // Si el grupo ocupa más filas que las disponibles por página, le asigno un salto de página antes (aunque eso signifique que la tabla quedará partida en dos páginas). Si el grupo cabe en una página pero no cabe junto con el grupo anterior, también le asigno un salto de página antes.
            if ($groupEstimatedRows >= $rowsPerPage) {
                $needPageBreak = $currentUsedRows > 0;
                $currentUsedRows = $groupEstimatedRows % $rowsPerPage;
            } else {
                if ($currentUsedRows + $groupEstimatedRows > $rowsPerPage) {
                    $needPageBreak = true;
                    $currentUsedRows = $groupEstimatedRows;
                } else {
                    $needPageBreak = false;
                    $currentUsedRows += $groupEstimatedRows;
                }
            }

            // Guardo el grupo de meses junto con la información de si se necesita un salto de página antes de renderizarlo
            $monthGroups[] = [
                'months' => $group,
                'pageBreakBefore' => $needPageBreak,
            ];
        }

        // Intento cargar el logo y convertirlo a base64 para incluirlo en el PDF (si no se puede cargar, dejo el valor como null y la vista debería manejar ese caso)
        $imagePathLogo = $empresa->logocliente 
        ? storage_path('app/public/logo_cliente/' . $empresa->logocliente) 
        : public_path('images/logos/logo_contable.png');
        $base64ImageLogo = null;
        if (file_exists($imagePathLogo)) {
            try {
                $imageDataLogo = file_get_contents($imagePathLogo);
                $base64ImageLogo = base64_encode($imageDataLogo);
            } catch (\Throwable $e) {
                $base64ImageLogo = null;
            }
        }

        // Configuro Dompdf para permitir HTML5, PHP y recursos remotos (como imágenes) y genero el PDF a partir de la vista, pasando toda la información necesaria (datos comparativos, grupos de meses, logo en base64, etc). Luego retorno el stream del PDF para que se muestre en el navegador (Attachment false) o se descargue (Attachment true).
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

        $html = view('exports.pdf-metas-empresa', compact(
            'metasComparative',
            'base64ImageLogo',
            'monthGroups',
            'nit',
            'empresaId',
            'fecha',
            'empresa'
        ))->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A3', 'landscape');
        $dompdf->render();

        $fileName = 'Presupuesto ' . ($empresa->razon_social ?? $nit ?? $empresaId) . ' ' . now()->format('Y-m-d') . '.pdf';
        return $dompdf->stream($fileName, ["Attachment" => false]);
    }
}
