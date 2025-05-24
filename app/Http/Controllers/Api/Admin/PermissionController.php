<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index()
    {
        return response()->json(Permission::all());
    }

    /**
     * Store a newly created resource in storage.
     */
     public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);

        $permission = Permission::create(['name' => $request->name,'guard_name' => 'api']);

        return response()->json($permission, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $permission = permission::findOrFail($id);
        return response()->json($permission);
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:permissions,name,' . $id,
        ]);

        $permission->update(['name' => $request->name]);

        return response()->json($permission);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return response()->json(null, 204);
    }
}
