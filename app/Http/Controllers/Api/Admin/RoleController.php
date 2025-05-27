<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoleRequest;
use App\Http\Requests\Admin\UpdateRoleRequest;
use App\Http\Resources\Api\Admin\RoleResource;
use App\Models\Role;
use App\Services\ResponseService;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();

        return ResponseService::success(
            RoleResource::collection($roles),
            'Roles retrieved successfully'
        );
    }

    public function store(StoreRoleRequest $request)
    {
        $validated = $request->validated();

        $role = Role::create($validated);

        return ResponseService::success(
            new RoleResource($role),
            'Role created successfully'
        );
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        $validated = $request->validated();
        $role->update($validated);

        if (isset($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        // Return with permissions loaded and wrapped in resource
        return ResponseService::success(
            new RoleResource($role->load('permissions')),
            'Role updated successfully'
        );
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return ResponseService::success(
            new RoleResource($role),
            'Role deleted successfully'
        );
    }

    public function trashed()
    {
        $roles = Role::onlyTrashed()->get();

        return ResponseService::success(
            RoleResource::collection($roles),
            'Trashed roles fetched successfully'
        );
    }

    public function restore(Role $role)
    {
        return $role;
        $role->withTrashed()->restore();

        return ResponseService::success(
            new RoleResource($role),
            'Role restored successfully'
        );
    }

    public function forceDelete(Role $role)
    {
        $role->forceDelete();

        return ResponseService::success('Role permanently deleted');
    }
}
