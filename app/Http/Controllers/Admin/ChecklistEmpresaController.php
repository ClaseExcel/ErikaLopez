<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ActionButtonTrait;
use App\Http\Requests\StoreChecklistRequest;
use App\Http\Requests\UpdateChecklistRequest;
use App\Models\ActividadesChecklist;
use App\Models\ChecklistEmpresa;
use App\Models\Empresa;
use App\Models\Seguimiento;
use App\Models\SeguimientoChecklistEmpresa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ChecklistEmpresaController extends Controller
{
    use ActionButtonTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('ACCEDER_CHECKLIST_CONTABLE'), Response::HTTP_UNAUTHORIZED);
        if ($request->ajax()) {

            $filter = $request->input('filter', []);

            if ($filter) {
                // $empresas = Empresa::orderBy('razon_social')->select('id', 'razon_social')->whereJsonContains('empleados', (string)$filter)->get();
                $checklist = ChecklistEmpresa::with('empresa', 'user_act')
                    // ->whereIn('empresa_id', $empresas->pluck('id'))
                    ->where('user_actualiza_id', $filter)
                    ->select('checklist_empresas.*');
            } else {
                if (Auth::user()->role_id == 1) {
                    $checklist = ChecklistEmpresa::with('empresa', 'user_act')->select('checklist_empresas.*');
                } else {
                    $empresas = Empresa::orderBy('razon_social')->select('id', 'razon_social')->whereJsonContains('empleados', (string)Auth::user()->id)->get();
                    $checklist = ChecklistEmpresa::with('empresa', 'user_act')
                        ->whereIn('empresa_id', $empresas->pluck('id'))
                        ->select('checklist_empresas.*');
                }
            }

            return DataTables::of($checklist)
                ->addColumn('actions', function ($checklist) {

                    $reportar = '';

                    if (Gate::allows('SEGUIMIENTO_CHECKLIST_CONTABLE')) {
                        $reportar = '<a href="' . route("admin.seguimiento_checklist.create", $checklist->id) . '" title="Reportar seguimiento" class="btn-eliminar px-2">
                        <i class="fa-solid fa-clipboard"></i>
                        </a>';
                    }

                    return $this->getActionButtons('admin.checklist_empresas', 'CHECKLIST_CONTABLE', $checklist->id) . $reportar;
                })
                ->addColumn('mes', function ($checklist) {
                    $seguimiento = SeguimientoChecklistEmpresa::where('checklist_empresa_id', $checklist->id)
                        ->orderByDesc('id')
                        ->first();

                    return $seguimiento ? $seguimiento->mes : null;
                })
                ->rawColumns(['actions', 'mes'])
                ->make(true);
        }

        $mesesMap = [
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre',
        ];

        $responsables = User::select('id', 'nombres', 'apellidos')->orderBy('nombres')->get();
        $empresas = Empresa::select('id', 'razon_social')->orderBy('razon_social')->get();

        return view('admin.checklists.index', compact('mesesMap', 'responsables', 'empresas'));
    }

    public function indexActividadesRealizadas(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->input('filter', []);
            $periodicidad = $request->input('periodicidad');

            // Ahora armar lista completa con presentados y no presentados
            $resultado = [];

            if ($periodicidad && $id) {
                $checklist = ChecklistEmpresa::with('empresa', 'user_act')
                    ->where('id', $id)
                    ->select('checklist_empresas.*')->first();

                $seguimientos = SeguimientoChecklistEmpresa::where('checklist_empresa_id', $checklist->id)->get();
                $actividadesRaw = json_decode($checklist->actividades, true);

                // Filtrar actividades por periodicidad
                $actividadesIds = [];
                foreach ($actividadesRaw as $actividad) {
                    $id = $actividad[0];
                    $per = $actividad[1];

                    if ($per == $periodicidad) {
                        $actividadesIds[] = $id;
                    }
                }

                $actividades = ActividadesChecklist::select('id', 'nombre')
                    ->whereIn('id', $actividadesIds)
                    ->get();

                $mesesMap = [
                    '01' => 'Enero',
                    '02' => 'Febrero',
                    '03' => 'Marzo',
                    '04' => 'Abril',
                    '05' => 'Mayo',
                    '06' => 'Junio',
                    '07' => 'Julio',
                    '08' => 'Agosto',
                    '09' => 'Septiembre',
                    '10' => 'Octubre',
                    '11' => 'Noviembre',
                    '12' => 'Diciembre',
                ];

                $mapaSeguimientos = [];

                foreach ($seguimientos as $seguimiento) {
                    $actividadesPresentadas = json_decode($seguimiento->actividades_presentadas, true) ?? [];

                    foreach ($actividadesPresentadas as $actividadId) {
                        $nombreActividad = $actividades->firstWhere('id', $actividadId)?->nombre;
                        if (!$nombreActividad) continue;

                        if (!isset($mapaSeguimientos[$nombreActividad])) {
                            $mapaSeguimientos[$nombreActividad] = [];
                        }

                        $mesNumero = substr($seguimiento->mes, 5, 2);

                        if (!empty($nombreActividad) && isset($mesesMap[$mesNumero])) {
                            $mapaSeguimientos[$nombreActividad][] = $mesesMap[$mesNumero];
                        }
                    }
                }

                foreach ($actividades as $actividad) {
                    $nombre = $actividad->nombre;
                    $realizados = $mapaSeguimientos[$nombre] ?? [];

                    $fila = ['actividad' => $nombre];

                    foreach ($mesesMap as $numMes => $mesNombre) {
                        $fila[strtolower($mesNombre)] = in_array($mesNombre, $realizados);
                    }

                    $resultado[] = $fila;
                }
            } elseif ($id) {
                $checklist = ChecklistEmpresa::with('empresa', 'user_act')
                    // ->whereIn('empresa_id', $empresas->pluck('id'))
                    ->where('id', $id)
                    ->select('checklist_empresas.*')->first();

                $seguimientos = SeguimientoChecklistEmpresa::where('checklist_empresa_id', $checklist->id)->get();
                $actividadesIds = json_decode($checklist->actividades);

                foreach ($actividadesIds as $key => $actividad) {
                    $actividadesIds[$key] = $actividad[0];
                }

                $actividades = ActividadesChecklist::select('id', 'nombre')->whereIn('id', $actividadesIds)->get();

                // Mapeo de número de mes a nombre
                $mesesMap = [
                    '01' => 'Enero',
                    '02' => 'Febrero',
                    '03' => 'Marzo',
                    '04' => 'Abril',
                    '05' => 'Mayo',
                    '06' => 'Junio',
                    '07' => 'Julio',
                    '08' => 'Agosto',
                    '09' => 'Septiembre',
                    '10' => 'Octubre',
                    '11' => 'Noviembre',
                    '12' => 'Diciembre',
                ];

                $mapaSeguimientos = [];

                foreach ($seguimientos as $seguimiento) {
                    // Decodificar las actividades presentadas en el seguimiento
                    $actividadesPresentadas = json_decode($seguimiento->actividades_presentadas, true) ?? [];

                    // Mapear las actividades presentadas a los meses correspondientes
                    foreach ($actividadesPresentadas as $actividadId) {
                        $nombreActividad = $actividades->firstWhere('id', $actividadId)?->nombre;
                        // Inicializar el array si no existe
                        if (!isset($mapaSeguimientos[$nombreActividad])) {
                            $mapaSeguimientos[$nombreActividad] = [];
                        }

                        // Obtener el número de mes del seguimiento
                        $mesNumero = substr($seguimiento->mes, 5, 2); // "01"

                        // Agregar el nombre del mes al array correspondiente a la actividad
                        if (!empty($nombreActividad) && isset($mesesMap[$mesNumero])) {
                            $mapaSeguimientos[$nombreActividad][] = $mesesMap[$mesNumero];
                        }
                    }
                }

                foreach ($actividades as $actividad) {
                    $nombre = $actividad->nombre;
                    $realizados = $mapaSeguimientos[$nombre] ?? [];

                    $fila = ['actividad' => $nombre];

                    foreach ($mesesMap as $numMes => $mesNombre) {
                        $fila[strtolower($mesNombre)] = in_array($mesNombre, $realizados);
                    }

                    $resultado[] = $fila;
                }
            }

            return DataTables::of($resultado)
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('CREAR_CHECKLIST_CONTABLE'), Response::HTTP_UNAUTHORIZED);
        $actividades = ActividadesChecklist::orderBy('nombre')->get();
        if (Auth::user()->role_id == 1) {
            $empresas = Empresa::orderBy('razon_social')->get();
        } else {
            $empresas = Empresa::orderBy('razon_social')->whereJsonContains('empleados', (string)Auth::user()->id)->get();
        }

        return view('admin.checklists.create', compact('actividades', 'empresas'), ['checklist' => new ChecklistEmpresa()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreChecklistRequest $request)
    {
        if ($request->actividades == null) {
            return redirect()->back()->withInput()->with('message', 'No puedes crear un checklist sin actividades, por favor crea el checklist.')->with('color', 'danger');
        }

        $parts = explode('-', $request->empresa_id);
        $value = trim($parts[0]);

        $checklist = ChecklistEmpresa::where('empresa_id', $value)
            ->where('año', $request->año)
            ->first();

        if ($checklist) {
            return redirect()->back()->with('message', 'Ya existe un checklist contable para la empresa y año seleccionados.')->with('color', 'danger');
        }

        $request->merge(['empresa_id' => $value, 'user_actualiza_id' => Auth::user()->id]);

        ChecklistEmpresa::create($request->all());

        return redirect()->route('admin.checklist_empresas.index')->with('message', 'Checklist contable creado correctamente.')->with('color', 'success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort_if(Gate::denies('VER_CHECKLIST_CONTABLE'), Response::HTTP_UNAUTHORIZED);

        $checklist = ChecklistEmpresa::find($id);
        $seguimientos = SeguimientoChecklistEmpresa::where('checklist_empresa_id', $id)
            ->orderByDesc('mes')
            ->get();

        $rawActividades = json_decode($checklist->actividades, true);
        $ids = array_map(fn($item) => $item[0], $rawActividades);
        $periodicidad = array_map(fn($item) => $item[1], $rawActividades);

        $actividades = ActividadesChecklist::select('id', 'nombre')
            ->whereIn('id', $ids)
            ->get();

        $conteoEstados = [];

        foreach ($ids as $actividad) {
            $conteoEstados[$actividad] = 0; // Inicializar conteo para cada actividad
        }

        foreach ($seguimientos as $seguimiento) {
            $estados = $seguimiento->actividades_presentadas;
            if (is_null($estados) || $estados === "null") {
                $estados = [];
            } else {
                $estados = json_decode($estados, true);
                if (!is_array($estados)) {
                    $estados = [];
                }
            }
            foreach ($conteoEstados as $actividadId => $conteo) {
                // Si la actividad está presentada, sumar, si no, dejar en 0
                if (in_array($actividadId, $estados)) {
                    $conteoEstados[$actividadId]++;
                }
            }
            // Si no tiene actividades presentadas, no se suma nada (queda en 0)
        }

        $porcentaje = [];

        foreach ($conteoEstados as $actividadId => $conteo) {
            // Buscar el índice de la actividad en $ids para obtener su periodicidad
            $index = array_search($actividadId, $ids);
            $periodicidadActividad = isset($periodicidad[$index]) ? $periodicidad[$index] : 12; // Valor por defecto 12 si no existe

            $porcentaje[$actividadId] = round(($conteo / $periodicidadActividad) * 100);
        }

        return view('admin.checklists.show', compact('checklist', 'seguimientos', 'actividades', 'porcentaje'));
    }

    public function filtroActividades(Request $request)
    {
        $periodicidadSeleccionada = $request->periodicidad;
        $id = $request->id_checklist;

        $checklist = ChecklistEmpresa::find($id);

        $seguimientos = SeguimientoChecklistEmpresa::where('checklist_empresa_id', $id)
            ->orderByDesc('mes')
            ->get();


        // Decodificar actividades
        $rawActividades = json_decode($checklist->actividades, true);

        // Filtrar por periodicidad seleccionada
        $ids = collect($rawActividades)
            ->filter(function ($actividad) use ($periodicidadSeleccionada) {
                return $actividad[1] == $periodicidadSeleccionada;
            })
            ->map(function ($actividad) {
                return $actividad[0]; // solo el id
            })
            ->values()
            ->toArray();

        // Mapeo de periodicidades por índice
        $periodicidadesMap = array_map(fn($item) => $item[1], $rawActividades);

        // Traer actividades desde la BD
        $actividades = ActividadesChecklist::select('id', 'nombre')
            ->whereIn('id', $ids)
            ->get();

        // Inicializar conteo
        $conteoEstados = [];
        foreach ($ids as $actividad) {
            $conteoEstados[$actividad] = 0;
        }

        // Procesar seguimientos
        foreach ($seguimientos as $seguimiento) {
            $estados = $seguimiento->actividades_presentadas;

            if (is_null($estados) || $estados === "null") {
                $estados = [];
            } else {
                $estados = json_decode($estados, true);
                if (!is_array($estados)) {
                    $estados = [];
                }
            }

            foreach ($conteoEstados as $actividadId => $conteo) {
                if (in_array($actividadId, $estados)) {
                    $conteoEstados[$actividadId]++;
                }
            }
        }

        // Calcular porcentajes
        $porcentaje = [];
        foreach ($conteoEstados as $actividadId => $conteo) {
            $index = array_search($actividadId, $ids);
            $periodicidadActividad = isset($periodicidadesMap[$index]) ? $periodicidadesMap[$index] : 12;

            $porcentaje[$actividadId] = round(($conteo / $periodicidadActividad) * 100);
        }

        if ($actividades->count() == 0) {
            $previous = route('admin.checklist_empresas.show', $id);

            return redirect($previous)
                ->with('message', 'No hay información con el criterio seleccionado.')
                ->with('color', 'danger');
        }

        return view('admin.checklists.show', compact('checklist', 'seguimientos', 'actividades', 'porcentaje'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if(Gate::denies('EDITAR_CHECKLIST_CONTABLE'), Response::HTTP_UNAUTHORIZED);
        $checklist = ChecklistEmpresa::find($id);
        $actividades = ActividadesChecklist::orderBy('nombre')->get();
        if (Auth::user()->role_id == 1) {
            $empresas = Empresa::orderBy('razon_social')->get();
        } else {
            $empresas = Empresa::orderBy('razon_social')->whereJsonContains('empleados', (string)Auth::user()->id)->get();
        }

        return view('admin.checklists.edit', compact('checklist', 'actividades', 'empresas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateChecklistRequest $request, $id)
    {
        if ($request->actividades == null) {
            return redirect()->back()->withInput()->with('message', 'No puedes crear un checklist sin actividades, por favor crea el checklist.')->with('color', 'danger');
        }

        $checklist = ChecklistEmpresa::find($id);
        $parts = explode('-', $request->empresa_id);
        $value = trim($parts[0]);

        $checklist_exist = ChecklistEmpresa::where('empresa_id', $value)
            ->where('año', $request->año)
            ->where('id', '!=', $id) // Excluir el mismo registro que se esta editando
            ->first();

        if ($checklist_exist) {
            return redirect()->back()
                ->with('message', 'Ya existe un checklist contable para la empresa y año seleccionados.')
                ->with('color', 'danger');
        }

        $request->merge(['empresa_id' => $value, 'user_actualiza_id' => Auth::user()->id]);

        $checklist->update($request->all());

        return redirect()->route('admin.checklist_empresas.index')->with('message', 'Checklist contable actualizado correctamente.')->with('color', 'success');
    }
}
