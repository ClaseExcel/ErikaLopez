<?php

namespace App\Http\Controllers\admin;

use App\Exports\MetasMasivoExport;
use App\Http\Controllers\Controller;
use App\Imports\MetasMasivoImport;
use App\Models\Empresa;
use App\Models\MetasEmpresa;
use App\Services\MetasEmpresaService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class MetasEmpresaController extends Controller
{
    protected MetasEmpresaService $service;

    public function __construct(MetasEmpresaService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('ACCEDER_METAS_EMPRESAS'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            $query = MetasEmpresa::with('empresa')->select('metas_empresas.*');

            if ($request->filled('empresa_id')) {
                $query->where('empresa_id', $request->empresa_id);
            }
            if ($request->filled('fecha_inicio')) {
                $query->whereDate('periodo', '>=', Carbon::createFromFormat('Y-m', $request->fecha_inicio)->startOfMonth());
            }
            if ($request->filled('fecha_fin')) {
                $query->whereDate('periodo', '<=', Carbon::createFromFormat('Y-m', $request->fecha_fin)->endOfMonth());
            }

            return DataTables::of($query)
                ->addColumn('empresa', fn(MetasEmpresa $me) => $me->empresa->razon_social ?? '-')
                ->addColumn('periodo', fn(MetasEmpresa $me) => $me->periodo ? Carbon::parse($me->periodo)->format('Y-m') : '')
                ->addColumn('valor', function (MetasEmpresa $me) {
                    return '
                        <button class="btn btn-sm btn-outline-save btn-view-valor" data-id="' . $me->id . '">
                            <i class="fas fa-tasks"></i> Mostrar cuentas
                        </button>';
                })
                ->addColumn('actions', function (MetasEmpresa $me) {
                    $buttons = '';
                    // botón editar (solo si el usuario puede editar)
                    if (Gate::allows('EDITAR_METAS_EMPRESAS')) {
                        $buttons .= '
                            <button class="btn px-2 py-0 btn-edit-target" data-id="' . $me->id . '" style="color: #575757;" title="Editar">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                        ';
                    }
                    // botón copiar (solo si el usuario puede crear)
                    if (Gate::allows('CREAR_METAS_EMPRESAS')) {
                        $buttons .= '
                            <button class="btn px-2 py-0 btn-copy-target" data-id="' . $me->id . '" style="color: #575757;" title="Copiar registro">
                                <i class="fas fa-copy"></i>
                            </button>
                        ';
                    }
                    return $buttons;
                })
                ->rawColumns(['actions', 'valor'])
                ->make(true);
        }
        $empresas = Empresa::orderBy('razon_social')->where('estado', 1)->pluck('razon_social', 'id');
        return view('admin.empresas.metas-index', compact('empresas'));
    }

    public function empresaCuentas($empresaId)
    {
        return response()->json([
            'data' => $this->service->cuentasTemplate()
        ]);
    }

    public function show($id)
    {
        // devuelve el registro con valor decodificado para editar/ver
        $meta = MetasEmpresa::findOrFail($id);
        $payload = $meta->toArray();
        $payload['periodo'] = $meta->periodo ? Carbon::parse($meta->periodo)->format('Y-m') : null;
        $payload['valor'] = json_decode($meta->valor, true) ?: [];

        return response()->json($payload);
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('CREAR_METAS_EMPRESAS'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $this->validateData($request);
        $data['periodo'] = Carbon::createFromFormat('Y-m', $data['periodo'])->startOfMonth();

        // normalizar valor a JSON string
        $data['valor'] = $this->normalizeValor($data['valor']);

        $meta = MetasEmpresa::create($data);

        return response()->json([
            'message' => 'Meta creada correctamente.',
            'data'    => $meta
        ]);
    }

    public function update(Request $request, $id)
    {
        abort_if(Gate::denies('EDITAR_METAS_EMPRESAS'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $meta = MetasEmpresa::findOrFail($id);
        $data = $this->validateData($request);
        $data['periodo'] = Carbon::createFromFormat('Y-m', $data['periodo'])->startOfMonth();

        $data['valor'] = $this->normalizeValor($data['valor']);

        $meta->update($data);

        return response()->json([
            'message' => 'Meta actualizada correctamente.',
            'data'    => $meta
        ]);
    }

    public function masiva()
    {
        abort_if(Gate::denies('CREAR_METAS_EMPRESAS'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $empresas = Empresa::orderBy('razon_social')
            ->where('estado', 1)
            ->pluck('razon_social', 'id');
        return view('admin.empresas.metas-masivo', compact('empresas'));
    }

    public function exportMasiva(Request $request)
    {
        abort_if(Gate::denies('CREAR_METAS_EMPRESAS'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $data = $request->validate([
            'empresa_id' => ['required', 'exists:empresas,id'],
            'anio'       => ['required', 'integer', 'min:2000', 'max:2100'],
        ]);
        $empresa = Empresa::find($data['empresa_id']);
        $filename = 'Plantilla presupuesto ' . $data['anio'] . ' empresa ' . $empresa->razon_social . '.xlsx';
        return Excel::download(
            new MetasMasivoExport((int) $data['empresa_id'], (int) $data['anio'], $this->service),
            $filename
        );
    }

    public function importMasiva(Request $request)
    {
        abort_if(Gate::denies('CREAR_METAS_EMPRESAS'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $data = $request->validate([
            'empresa_id' => ['required', 'exists:empresas,id'],
            'anio'       => ['required', 'integer', 'min:2000', 'max:2100'],
            'archivo'    => ['required', 'file', 'mimes:xlsx,xls,xlsm'],
        ]);
        Excel::import(
            new MetasMasivoImport((int) $data['empresa_id'], (int) $data['anio'], $this->service),
            $request->file('archivo')
        );
        return response()->json([
            'message' => 'Archivo importado correctamente. Se guardaron los 12 meses del presupuesto.',
        ]);
    }

    protected function validateData(Request $request)
    {
        return $request->validate([
            'periodo'     => ['required', 'date_format:Y-m'],
            'empresa_id'  => ['required', 'exists:empresas,id'],
            'valor'       => ['required'], // recibimos JSON string o array (lo normalizamos)
        ]);
    }

    protected function normalizeValor($valor)
    {
        // Si viene como string JSON lo validamos; si viene array lo codificamos.
        if (is_string($valor)) {
            $decoded = json_decode($valor, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return json_encode($decoded);
            }
            // si viene como string no JSON (ej: textarea), intentamos no romper: lo guardamos como JSON vacío
            return json_encode([]);
        }

        if (is_array($valor)) {
            return json_encode($valor);
        }

        return json_encode([]);
    }
}
