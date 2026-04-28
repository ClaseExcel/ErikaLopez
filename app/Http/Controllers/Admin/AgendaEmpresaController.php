<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AgendaEmpleado;
use App\Models\AgendaEmpresa;
use App\Models\Empresa;
use App\Http\Requests\StoreAgendaEmpresaRequest;

use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Traits\ActionButtonTrait;
use App\Http\Requests\UpdateAgendaEmpresaRequest;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class AgendaEmpresaController extends Controller
{
    use ActionButtonTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('ACCEDER_ASIGNAR_AGENDA'), Response::HTTP_UNAUTHORIZED);

        if($request->ajax())
        {
            $agendas =  AgendaEmpresa::with('empresa', 'agenda')->select('agenda_empresas.*');
            return DataTables::of($agendas)
                ->addColumn('actions', function ($agendas) {
                    // Lógica para generar las acciones para cada registro de agenda empresas
                    return $this->getActionButtons('admin.asignar.agenda','DISPONIBILIDAD',$agendas->id);
                })
                ->rawColumns(['actions'])//para que se muestre el codigo html en la tabla
                ->make(true);
        }

        return view('admin.agenda.index-asignar-agenda');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('CREAR_ASIGNAR_AGENDA'), Response::HTTP_UNAUTHORIZED);

        $agendas = AgendaEmpleado::select('id', 'fecha_disponibilidad', 'hora_inicio', 'hora_fin')->orderBy('fecha_disponibilidad')->get();
        $clientes = Empresa::select('id', 'razon_social')->whereNotIn('id', [1])->orderBy('razon_social')->get();

        return view('admin.agenda.create-asignar-agenda', compact('agendas', 'clientes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAgendaEmpresaRequest $request)
    {
        //busco si ya se ha asignado la agenda a un cliente
        $exist = AgendaEmpresa::select('agenda_id', 'empresa_id')
        ->where('agenda_id', $request['agenda_id'])
        ->where('empresa_id', $request['empresa_id'])->first();

        if($exist) {
            return redirect()->back()->with('message', 'Ya se ha asignado la agenda para el cliente seleccionado.')->with('color', 'danger');
        }

        AgendaEmpresa::create($request->all());

        return redirect()->route('admin.asignar.agenda.index')->with('message', 'La agenda se ha asignado exitosamente.')->with('color', 'success');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if(Gate::denies('EDITAR_ASIGNAR_AGENDA'), Response::HTTP_UNAUTHORIZED);

        $agendaEmpresa = AgendaEmpresa::find($id);
        $agendas = AgendaEmpleado::select('id', 'fecha_disponibilidad', 'hora_inicio', 'hora_fin')->orderBy('fecha_disponibilidad')->get();
        $clientes = Empresa::select('id', 'razon_social')->whereNotIn('id', [1])->orderBy('razon_social')->get();
     
        return view('admin.agenda.edit-asignar-agenda', compact('agendas', 'clientes', 'agendaEmpresa'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAgendaEmpresaRequest $request, $id)
    {
        $agenda = AgendaEmpresa::find($id);
        //busco si ya se ha asignado la agenda a un cliente
        $exist = AgendaEmpresa::select('agenda_id', 'empresa_id')
        ->where('agenda_id', $request['agenda_id'])
        ->where('empresa_id', $request['empresa_id'])->first();

        if($exist) {
            return redirect()->back()->with('message', 'Ya se ha asignado la agenda para el cliente seleccionado.')->with('color', 'danger');
        }

        $agenda->update($request->all());
        
        return redirect()->route('admin.asignar.agenda.index')->with('message', 'La agenda se ha editado exitosamente.')->with('color', 'success');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AgendaEmpresa $agenda, $id)
    {
        abort_if(Gate::denies('ELIMINAR_ASIGNAR_AGENDA'), Response::HTTP_UNAUTHORIZED);

        $agenda->where('id', $id)->delete();

        return redirect()->route('admin.asignar.agenda.index')->with('message', 'La agenda se ha eliminado exitosamente.')->with('color', 'success');

    }
}
