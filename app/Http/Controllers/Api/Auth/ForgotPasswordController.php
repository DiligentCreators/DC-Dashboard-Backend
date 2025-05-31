<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Services\ResponseService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ForgotPasswordRequest $request)
    {
        $email = $request->input('email');
        $token = Str::random(64);
        $now = Carbon::now();

        // Insert or update the token in password_resets table
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => $token,
                'created_at' => $now,
            ]
        );

        // Todo: Send email with the token
        logger('Email Sent with Token: '.$token);

        return ResponseService::success($token, 'Password reset token sent to your email.');
    }
}
