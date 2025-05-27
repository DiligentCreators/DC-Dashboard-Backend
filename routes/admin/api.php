<?php

use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\ProfileController;
use App\Http\Controllers\Api\Admin\RoleController;
use Illuminate\Support\Facades\Route;

// Example admin routes
Route::get('dashboard', DashboardController::class)->name('dashboard');

Route::get('profile', [ProfileController::class, 'view'])->name('profile');
Route::post('profile', [ProfileController::class, 'update'])->name('profile');

// Example admin routes for roles
Route::apiResource('roles', RoleController::class);

Route::get('roles/trashed', [RoleController::class, 'trashed']);
Route::post('roles/{role}/restore', [RoleController::class, 'restore']);
Route::delete('roles/{role}/forceDelete', [RoleController::class, 'forceDelete']);
