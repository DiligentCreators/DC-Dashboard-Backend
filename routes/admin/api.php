<?php

use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\EmailBuilder\EmailTemplateController;
use App\Http\Controllers\Api\Admin\EmailBuilder\GlobalEmailTemplateController;
use App\Http\Controllers\Api\Admin\ImpersonationController;
use App\Http\Controllers\Api\Admin\PermissionController;
use App\Http\Controllers\Api\Admin\ProfileController;
use App\Http\Controllers\Api\Admin\RoleController;
use App\Http\Controllers\Api\Admin\StaffUserController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin API Routes
|--------------------------------------------------------------------------
*/

// Dashboard route
Route::get('dashboard', DashboardController::class)->name('dashboard');
 Route::get('toggleAccountLock', [ProfileController::class, 'toggleAccountLock']);

// Profile routes


Route::get('profile', [ProfileController::class, 'view'])
    ->middleware(['auth', 'impersonate'])
    ->name('profile');

Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
Route::put('profile/password', [ProfileController::class, 'updatePassword']);
// Role routes
Route::prefix('roles')->group(function () {
    Route::get('trashed', [RoleController::class, 'trashed'])->name('roles.trashed');
    Route::post('{role}/restore', [RoleController::class, 'restore'])->name('roles.restore');
    Route::delete('{role}/force-delete', [RoleController::class, 'forceDelete'])->name('roles.forceDelete');
    Route::patch('/{role}/toggle-active', [RoleController::class, 'toggleActive']);

});
Route::apiResource('roles', RoleController::class);

// permissions
Route::prefix('permissions')->group(function () {
    Route::get('/', [PermissionController::class, 'index']);
});
// User routes
Route::prefix('users')->group(function () {
    Route::get('trashed', [UserController::class, 'trashed'])->name('users.trashed');
    Route::post('{user}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::delete('{user}/force-delete', [UserController::class, 'forceDelete'])->name('users.force-delete');
    Route::put('/{user}/update-password', [UserController::class, 'updatePassword']);
});

Route::apiResource('users', UserController::class);

// Account status toggles
Route::patch('/{user}/toggle-active', [StaffUserController::class, 'toggleActive']);
Route::patch('/{user}/toggle-locked', [StaffUserController::class, 'toggleLocked']);
Route::patch('/{user}/toggle-suspended', [StaffUserController::class, 'toggleSuspended']);
Route::patch('/{user}/toggle-email-verified', [StaffUserController::class, 'email_verified']);

// Staff routes

Route::prefix('staff')->group(function () {
    Route::get('trashed', [StaffUserController::class, 'trashed'])->name('staff.trashed');
    Route::post('{user}/restore', [StaffUserController::class, 'restore'])->name('staff.restore');
    Route::delete('{user}/force-delete', [StaffUserController::class, 'forceDelete'])->name('staff.forceDelete');
    Route::put('/{user}/update-password', [UserController::class, 'updatePassword']);
});
Route::apiResource('staff', StaffUserController::class);

// Global Email Templates
Route::apiResource('global-email-templates', GlobalEmailTemplateController::class);

// Email Templates
Route::apiResource('email-templates', EmailTemplateController::class);

// Impersonation routes (require authentication)
Route::middleware(['auth'])->group(function () {
    // add just
    Route::get('/impersonate/stop', [ImpersonationController::class, 'stopImpersonate'])->name('impersonate.stop');
    Route::get('/impersonate/{user}', [ImpersonationController::class, 'impersonate'])->name('impersonate.start');

});
