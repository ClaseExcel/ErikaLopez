<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Traits\ActionButtonTrait;
use App\Http\Requests\StoreCentroCostoRequest;
use App\Http\Requests\UpdateCentroCostoRequest;
use App\Models\CentroCosto;
use App\Models\Empresa;

class CentrosCostosController extends Controller
{
    use ActionButtonTrait;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('ACCEDER_CENTROS_COSTOS'), Response::HTTP_UNAUTHORIZED);
        if($request->ajax())
        {
            $centroCosto = CentroCosto::with('compania')->select('centros_costos.*');
            return DataTables::of($centroCosto)
                ->addColumn('actions', function ($centroCosto) {
                    return $this->getActionButtons('admin.centros_costos', 'CENTROS_COSTOS', $centroCosto->id);
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('admin.centroscostos.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('CREAR_CENTROS_COSTOS'), Response::HTTP_UNAUTHORIZED);
        $compania = Empresa::orderBy('razon_social')->pluck('razon_social', 'id');
        return view('admin.centroscostos.create', compact('compania'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCentroCostoRequest $request)
    {
        $request->request->add(['estado' => 1]);
        CentroCosto::create($request->all());
        return back()->with('message2', 'Centro de costo creado exitosamente.')->with('color', 'success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort_if(Gate::denies('VER_CENTROS_COSTOS'), Response::HTTP_UNAUTHORIZED);
        $centroCosto = CentroCosto::find($id);
        return view('admin.centroscostos.show', compact('centroCosto'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if(Gate::denies('EDITAR_CENTROS_COSTOS'), Response::HTTP_UNAUTHORIZED);
        $centroCosto = CentroCosto::find($id);
        return view('admin.centroscostos.edit', compact('centroCosto'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCentroCostoRequest $request, $id)
    {
        if ($request->has('estado')) {
            $request->request->add(['estado' => 1]);
        }
        else{
            $request->request->add(['estado' => 0]);
        }
        $centroCosto = CentroCosto::find($id);
        $centroCosto->update($request->all());
        return view('admin.centroscostos.index')->with('message2', 'Centro de costo actualizado exitosamente.')->with('color', 'success');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CentroCosto $centroCosto)
    {
        abort_if(Gate::denies('ELIMINAR_CENTROS_COSTOS'), Response::HTTP_UNAUTHORIZED);
        // $centroCosto->delete();
        return back();
    }
}
