<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProfileUpdateRequest;
use App\Http\Resources\Api\Admin\ProfileResource;
use App\Models\Profile;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $user = Auth::user();

        $validated = $request->validated();

        $profile = Profile::updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );

        return ResponseService::success(
            new ProfileResource($profile),
            'Profile updated successfully'
        );
    }
}
