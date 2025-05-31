<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProfileUpdateRequest;
use App\Http\Requests\Admin\updatePassword;
use App\Models\Profile;
use App\Models\User;
use App\Services\ResponseService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function view(Request $request)
    {
        return $request->user();
    }

    /*  public function store(Request $request)
     {
         // Update the user's profile
     } */

    /**
     * Update the authenticated user's profile.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProfileUpdateRequest $request)
    {
        $user = auth()->user();
        $validated = $request->validated();

        //  update user table fields
        $user->update(collect($validated)->only(['name', 'email', 'username'])->toArray());

        // Profile table update
        $profile = $user->profile;
        if ($profile) {
            $profile->update(collect($validated)->except(['name', 'email', 'username'])->toArray());
        } else {
            return ResponseService::error('User profile does not exist.', 404);
        }

        return ResponseService::success(
            'Profile updated successfully'
        );
    }

    public function updatePassword(updatePassword $request)
    {
        $user = auth()->user();
        $validated = $request->validated();
        $user->update($validated);

        return ResponseService::success('Password updated successfully.');
    }
}
