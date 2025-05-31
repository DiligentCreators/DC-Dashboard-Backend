<?php

use App\Http\Controllers\Api\User\DashboardController;
use App\Http\Controllers\Api\User\ProfileController;
use Illuminate\Support\Facades\Route;

// Example user routes
Route::get('dashboard', DashboardController::class)->name('dashboard');

Route::get('profile', [ProfileController::class, 'view'])->name('profile');
Route::post('profile', [ProfileController::class, 'store'])->name('profile');
Route::put('profile', [ProfileController::class, 'update'])->name('profile');
Route::put('profile/password', [ProfileController::class, 'updatePassword']);
