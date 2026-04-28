<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Actividad;
use App\Models\ActividadesChecklist;
use App\Models\ChecklistEmpresa;
use App\Models\SeguimientoChecklistEmpresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class SeguimientoChecklistController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        abort_if(Gate::denies('SEGUIMIENTO_CHECKLIST_CONTABLE'), Response::HTTP_UNAUTHORIZED);
        $checklist = ChecklistEmpresa::find($id);
        $rawActividades = json_decode($checklist->actividades, true);
        $ids = array_map(fn($item) => $item[0], $rawActividades);
        $actividades = ActividadesChecklist::select('id', 'nombre')->whereIn('id', $ids)->get();
        $observaciones = [];

        return view('admin.checklists.seguimiento.create', compact('checklist', 'actividades', 'observaciones'), ['seguimiento' => new SeguimientoChecklistEmpresa()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if(Gate::denies('SEGUIMIENTO_CHECKLIST_CONTABLE'), Response::HTTP_UNAUTHORIZED);

        $seguimiento = SeguimientoChecklistEmpresa::where('checklist_empresa_id', $request->checklist_empresa_id)
            ->where('mes', $request->mes)
            ->first();

        $checklist = ChecklistEmpresa::find($request->checklist_empresa_id);

        $actividades_presentadas = json_encode($request->actividades_presentadas);
        $observaciones = json_encode($request->observaciones);

        $request->merge([
            'actividades_presentadas' => $actividades_presentadas,
            'observaciones' => $observaciones,
        ]);

        if ($seguimiento) {
            $seguimiento->update($request->all());
        } else {
            SeguimientoChecklistEmpresa::create($request->all());
        }

        $checklist->update([
            'user_actualiza_id' => Auth::user()->id,
        ]);


        return redirect()->route('admin.checklist_empresas.index')->with('message', 'Seguimiento registrado correctamente.')->with('color', 'success');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if(Gate::denies('SEGUIMIENTO_CHECKLIST_CONTABLE'), Response::HTTP_UNAUTHORIZED);

        $seguimiento = SeguimientoChecklistEmpresa::find($id);
        $checklist = ChecklistEmpresa::find($seguimiento->checklist_empresa_id);
        $rawActividades = json_decode($checklist->actividades, true);
        $ids = array_map(fn($item) => $item[0], $rawActividades);
        $actividades = ActividadesChecklist::select('id', 'nombre')
            ->whereIn('id', $ids)
            ->get();

        $observaciones = json_decode($seguimiento->observaciones, true);

        return view('admin.checklists.seguimiento.edit', compact('seguimiento', 'checklist', 'actividades', 'observaciones'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        abort_if(Gate::denies('SEGUIMIENTO_CHECKLIST_CONTABLE'), Response::HTTP_UNAUTHORIZED);

        $seguimiento = SeguimientoChecklistEmpresa::find($id);
        $checklist = ChecklistEmpresa::find($seguimiento->checklist_empresa_id);

        $actividades_presentadas = json_encode($request->actividades_presentadas);
        $observaciones = json_encode($request->observaciones);

        $request->merge([
            'actividades_presentadas' => $actividades_presentadas,
            'observaciones' => $observaciones,
        ]);

        $seguimiento->update($request->all());

        $checklist->update([
            'user_actualiza_id' => Auth::user()->id,
        ]);

        return redirect()->route('admin.checklist_empresas.show', $checklist->id)->with('message', 'Seguimiento actualizado correctamente.')->with('color', 'success');
    }

    public function mesExistente($checklist_id, $mes)
    {
        $seguimiento = SeguimientoChecklistEmpresa::where('checklist_empresa_id', $checklist_id)
            ->where('mes', $mes)
            ->first();

        return response()->json(['seguimiento' => $seguimiento ? $seguimiento : null]);
    }
}
