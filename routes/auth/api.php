<?php

use App\Http\Controllers\Api\Auth\CodeVerificationController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;

Route::post('login', LoginController::class)->name('login');

Route::post('register', RegisterController::class)->name('register');

Route::post('forgot-password', ForgotPasswordController::class)->name('forgot-password');

Route::post('reset-password', ResetPasswordController::class)->name('reset-password');

Route::middleware('auth:sanctum')->group(function () {

    // Verification
    Route::get('/verification', [CodeVerificationController::class, 'verification']);
    Route::post('/verification', [CodeVerificationController::class, 'store'])->name('verification');
    Route::get('/verification/{email}/{code}', [CodeVerificationController::class, 'verificationCode'])->name('verification.code');
    // Send Verification Code
    Route::post('/resend-verification', [CodeVerificationController::class, 'resendVerificationCode'])->name('resend.verification');

    Route::post('logout', LogoutController::class)->name('logout')->middleware('auth:sanctum');
});
