<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CodeVerificationController extends Controller
{
    public function verification(Request $request)
    {
        $user = Auth::user();

        if (! $user) {
            // Todo: Redirect to login page
        }

        // Example: Check if the user is already verified
        if (! is_null($user->email_verified_at)) {
            // Todo: Redirect to dashboard
        }

        // Todo: Redirect to verification page
    }

    public function store(Request $request)
    {
        $codeInput = implode('', $request->input('code')); // Combine the 4 digits into one string
        $user = Auth::user();

        if ($user->verification_code == $codeInput) {
            // Mark the user as verified
            $user->email_verified_at = now();
            /** @var \App\Models\User $user */
            $user->save();

            // Todo: Send Welcome Email

            if ($user->is_admin) {
                // Todo: Rdirect to admin dashboard
            } else {
                // Todo: Redirect to user dashboard
            }
        }
        // Todo: Redirect to verification page

    }

    public function verificationCode(Request $request)
    {
        if ($request->email && $request->code) {
            $user = User::where([
                'email' => $request->email,
                'verification_code' => $request->code,
            ])->first();

            if ($user) {
                Auth::login($user);

                return redirect()->route('verification');
            }
        }
    }

    public function resendVerificationCode(Request $request)
    {
        $user = Auth::user();

        // Todo: Resend Verification Code via Event
    }
}
