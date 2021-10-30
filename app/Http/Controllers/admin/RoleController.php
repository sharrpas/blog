<?php

namespace App\Http\Controllers\admin;

use App\Exceptions\AdminExceptin;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class RoleController extends Controller
{

    public function index()
    {
        $role = Role::with('permissions')->get();
        return response()->json($role);
    }

    public function store(Request $request)
    {
        $request->validate([
            'role' => 'required',
            'permissions' => 'exists:App\Models\Permission,id'
        ]);
        DB::beginTransaction();
        try {
            $role = Role::query()->create(['name' => $request->role]);
            $role->permissions()->attach($request->permissions);

        } catch (\Exception $exception) {
            DB::rollBack();
           return $this->response(0,'ERROR');
        }
        DB::commit();
        return $this->response(1,'Role created');
    }

    public function show($role)
    {
        $Role = Role::with('permissions')->find($role);
        return response()->json($Role);
    }

    public function update(Role $role, Request $request)
    {
        $request->validate([
            'name' => 'required',
            'permissions' => 'exists:App\Models\Permission,id'
        ]);
        if ($role->name == 'super_admin') {
            return \response()->json('you can not change the SUPER_ADMIN role');
        } else {
            $role->update([
                'name' => $request->name,
            ]);
            $role->permissions()->sync($request->permissions);

            $Role = Role::with('permissions')->find($role);
            return response()->json($Role);
        }
    }

    public function delete(Role $role)
    {
        throw_if($role->name == 'super_admin',
            new AdminExceptin('you can not delete the SUPER_ADMIN role'));

        $role->permissions()->detach();
        $role->delete();
        return $this->response(1,'role deleted');
    }


}
