<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserStoreRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Http\Resources\Api\Admin\UserResource;
use App\Models\User;
use App\Services\ResponseService;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = User::with('profile')->get();

        return ResponseService::success(
            UserResource::collection($roles),
            'users retrieved successfully'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request)
    {
        $validated = $request->validated();
        $role = User::create($validated);

        return ResponseService::success(
            new UserResource($role),
            'User created successfully'
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return ResponseService::success(
            new UserResource($user->load('roles')),
            'User retrieved successfully'
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, string $id)
    {

        $validated = $request->validated();

        //  Find the user
        $user = User::findOrFail($id);

        //  Update basic user data
        $user->update($validated);

        if (isset($validated['role'])) {
            $user->syncRoles([$validated['role']]);
        }

        return ResponseService::success(
            new UserResource($user->load('roles')),
            'User updated successfully'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {

        $user->delete();

        return ResponseService::success(
            new UserResource($user),
            'User deleted successfully'
        );
    }

    public function trashed()
    {
        $user = User::onlyTrashed()->get();

        return ResponseService::success(
            UserResource::collection($user),
            'Trashed user fetched successfully'
        );
    }

    public function restore(User $user)
    {
        $user->restore();

        return ResponseService::success(
            new UserResource($user),
            'user restored successfully'
        );
    }

    public function forceDelete(User $user)
    {
        $user->forceDelete();

        return ResponseService::success('user permanently deleted');
    }
}
