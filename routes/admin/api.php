    <?php

    use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\ProfileController;
use App\Http\Controllers\Api\Admin\RoleController;
use App\Http\Controllers\Api\Admin\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin API Routes
|--------------------------------------------------------------------------
*/

// Dashboard route
Route::get('dashboard', DashboardController::class)->name('dashboard');

// Profile routes
Route::get('profile', [ProfileController::class, 'view'])->name('profile');
Route::post('profile', [ProfileController::class, 'update'])->name('profile.update');
Route::put('profile/password', [ProfileController::class, 'updatePassword']);
// Role routes
Route::apiResource('roles', RoleController::class)->except(['show']);

// Soft Delete Features under 'roles' prefix
Route::prefix('roles')->group(function () {
    Route::get('trashed', [RoleController::class, 'trashed'])->name('roles.trashed');
    Route::post('{role}/restore', [RoleController::class, 'restore'])->name('roles.restore');
    Route::delete('{role}/force', [RoleController::class, 'forceDelete'])->name('roles.forceDelete');
});

// User routes
Route::apiResource('users', UserController::class);

// Soft Delete Features under 'users' prefix
Route::prefix('users')->group(function () {
    Route::get('trashed', [UserController::class, 'trashed'])->name('users.trashed');
    Route::post('{user}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::delete('{user}/force', [UserController::class, 'forceDelete'])->name('users.forceDelete');
});
