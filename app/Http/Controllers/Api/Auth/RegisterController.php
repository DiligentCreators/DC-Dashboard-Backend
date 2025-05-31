<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Services\ResponseService;
use Exception;
use Illuminate\Database\QueryException;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(RegisterRequest $request)
    {

        try {
            $validated = $request->validated();

            $user = User::create($validated);

            // Todo: Send verification email

            return ResponseService::success($user, 'User registered successfully', 201);
        } catch (QueryException $e) {
            return ResponseService::error('Database error: '.$e->getMessage(), 500);
        } catch (Exception $e) {
            return ResponseService::error('Registration failed: '.$e->getMessage(), 500);
        }
    }
}
