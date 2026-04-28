<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ActionButtonTrait;
use App\Http\Requests\MassDestroyPermissionRequest;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Models\Permission;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class PermissionsController extends Controller
{
    use ActionButtonTrait;
    
    public function index(Request $request)
    {
        abort_if(Gate::denies('ACCEDER_'), Response::HTTP_UNAUTHORIZED);

        if($request->ajax())
        {
            $permissions = Permission::select('id','title');
            return DataTables::of($permissions)
                        ->addColumn('actions', function ($permissions){       
                            return $this->getActionButtons('admin.permissions','PERMISOS',$permissions->id);
                        })
                        ->rawColumns(['actions'])//para que se renderice el codigo html
                        ->make(true);
        }       

        return view('admin.permissions.index');
    }

    public function create()
    {
        abort_if(Gate::denies('CREAR_'), Response::HTTP_UNAUTHORIZED);

        return view('admin.permissions.create');
    }

    public function store(StorePermissionRequest $request)
    {
        $permission = Permission::create($request->all());

        return redirect()->route('admin.permissions.index');
    }

    public function edit(Permission $permission)
    {
        abort_if(Gate::denies('EDITAR_'), Response::HTTP_UNAUTHORIZED);

        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        $permission->update($request->all());

        return redirect()->route('admin.permissions.index');
    }

    public function show(Permission $permission)
    {
        abort_if(Gate::denies('VER_'), Response::HTTP_UNAUTHORIZED);

        return view('admin.permissions.show', compact('permission'));
    }

    public function destroy(Permission $permission)
    {
        abort_if(Gate::denies('ELIMINAR_'), Response::HTTP_UNAUTHORIZED);

        $permission->delete();

        return back();
    }

    public function massDestroy(MassDestroyPermissionRequest $request)
    {
        Permission::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
