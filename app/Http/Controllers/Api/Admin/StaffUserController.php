<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminUpdateUserPasswordRequest;
use App\Http\Requests\Admin\StoreStaffUserRequest;
use App\Http\Requests\Admin\UpdateStaffUserRequest;
use App\Http\Resources\Api\Admin\StaffUserResource;
use App\Models\Permission;
use App\Models\User;
use App\Services\ResponseService;
use Illuminate\Support\Facades\Auth;

class StaffUserController extends Controller
{
    public function index()
    {

        $this->authorizePermission('clients.list');

        $users = User::with(['profile'])
            ->withTrashed()
            ->where('is_admin', true)
            ->where('id', '!=', Auth::id())
            ->get();

        return ResponseService::success(
            StaffUserResource::collection($users),
            'Users retrieved successfully'
        );
    }


    public function store(StoreStaffUserRequest $request)
    {
        $this->authorizePermission('clients.create');

        $validated = $request->validated();

        unset($validated['email_verified_at']);

        $validated['email_verified_at'] = $request->input('email_verified_at') === 'yes'
            ? now()
            : null;

        $user = User::create($validated);

        return ResponseService::success(
            new StaffUserResource($user),
            'Staff User created successfully'
        );
    }

    public function show($id)
    {
        $this->authorizePermission('clients.read');


        $user = User::with(['roles', 'profile'])->find($id);

        if (!$user) {
            return ResponseService::error('User not found', 404);
        }


        return ResponseService::success(
            new StaffUserResource($user),
            'User retrieved successfully'
        );
    }

  public function update(UpdateStaffUserRequest $request, string $id)
{
    $validated = $request->validated();
    $user = User::with('profile')->findOrFail($id);

    if ($request->hasFile('profile')['avatar_file']) {
        $path = $request->file('profile')['avatar_file']
                        ->store('avatars', 'public');
        $validated['profile']['avatar'] = basename($path);
    }

    $user->update($validated);

    if (!empty($validated['profile'])) {
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $validated['profile']
        );
    }

    if (!empty($validated['roles'])) {
        $user->syncRoles($validated['roles']);
    }

    return ResponseService::success(
        new StaffUserResource($user->load(['roles', 'profile'])),
        'Staff updated successfully'
    );
}



    public function destroy(User $user)
    {
        $this->authorizePermission('clients.delete');

        $user->delete();

        return ResponseService::success(
            new StaffUserResource($user),
            'Staff User deleted successfully'
        );
    }

    public function trashed()
    {
        $this->authorizePermission('clients.list');

        $users = User::onlyTrashed()->get();

        return ResponseService::success(
            StaffUserResource::collection($users),
            'Trashed users fetched successfully'
        );
    }

    public function restore(User $user)
    {
        $this->authorizePermission('clients.restore');

        $user->restore();

        return ResponseService::success(
            new StaffUserResource($user),
            'Staff user restored successfully'
        );
    }

     public function forceDelete($userId)
        {
             $this->authorizePermission('clients.force.delete');
            $user = User::withTrashed()->findOrFail($userId);

            // Permanently delete
            $user->forceDelete();

            return ResponseService::success(
                'User permanently deleted'
            );
        }

    public function updatePassword(AdminUpdateUserPasswordRequest $request, User $user)
    {
        $this->authorizePermission('clients.password.update');

        $validated = $request->validated();
        $user->update($validated);

        return ResponseService::success('Password updated successfully.');
    }

    public function email_verified(User $user)
    {
        $user->email_verified_at = $user->email_verified_at ? null : now();
        $user->save();

        return ResponseService::success(new StaffUserResource($user), 'Lock status updated.');
    }
    public function toggleLocked(User $user)
    {
        $user->is_locked = ! $user->is_locked;
        $user->save();

        return ResponseService::success(new StaffUserResource($user), 'Lock status updated.');
    }

    public function toggleActive(User $user)
    {
        $user->is_active = ! $user->is_active;
        $user->save();

        return ResponseService::success(
            new StaffUserResource($user),
            'User active status updated successfully'
        );
    }

    public function toggleSuspended(User $user)
    {
        $user->is_suspended = ! $user->is_suspended;
        $user->save();

        return ResponseService::success(new StaffUserResource($user), 'Suspended status updated.');
    }

    protected function authorizePermission(string $permission)
    {
        if (!auth()->user()->can($permission)) {
            abort(403, 'Unauthorized.');
        }
    }
}
