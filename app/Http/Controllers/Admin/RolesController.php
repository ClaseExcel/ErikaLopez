<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ActionButtonTrait;
use App\Http\Requests\MassDestroyRoleRequest;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RolesController extends Controller
{  
    use ActionButtonTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('ACCEDER_ROL'), Response::HTTP_UNAUTHORIZED);

        if ($request->ajax()) {
            $roles = Role::with(['permissions']);

            return DataTables::of($roles)
                ->addColumn('actions', function ($roles) {
                    return $this->getActionButtons('admin.roles', 'ROL', $roles->id);
                })
                ->rawColumns(['actions']) //para que se muestr el codigo html en la tabla
                ->make(true);
        }

        return view('admin.roles.index');
    }

    public function create()
    {
        abort_if(Gate::denies('CREAR_ROL'), Response::HTTP_UNAUTHORIZED);

        $permissions = Permission::pluck('title', 'id');

        return view('admin.roles.create', compact('permissions'));
    }

    public function store(StoreRoleRequest $request)
    {
        $role = Role::create($request->all());
        $role->permissions()->sync($request->input('permissions', []));

        return redirect()->route('admin.roles.index');
    }

    public function edit(Role $role)
    {
        abort_if(Gate::denies('EDITAR_ROL'), Response::HTTP_UNAUTHORIZED);

        $permissions = Permission::pluck('title', 'id');

        $role->load('permissions');

        return view('admin.roles.edit', compact('permissions', 'role'));
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        $role->update($request->all());
        $role->permissions()->sync($request->input('permissions', []));

        return redirect()->route('admin.roles.index');
    }

    public function show(Role $role)
    {
        abort_if(Gate::denies('VER_ROL'), Response::HTTP_UNAUTHORIZED);

        $role->load('permissions');

        return view('admin.roles.show', compact('role'));
    }

    public function destroy(Role $role)
    {
        abort_if(Gate::denies('ELIMINAR_ROL'), Response::HTTP_UNAUTHORIZED);

        $role->delete();

        return true;
    }

    public function massDestroy(MassDestroyRoleRequest $request)
    {
        Role::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
