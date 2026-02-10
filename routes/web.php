<?php

use App\Constants\PermissionConstants;
use App\Constants\RoleConstants;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocaleController;
use App\Livewire\Admin\RoleManagement;
use App\Livewire\Auth\Login;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Locale
|--------------------------------------------------------------------------
*/
Route::get('locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth.active')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Root — redirect to role-specific dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Administrator dashboard
    Route::prefix('admin')
        ->middleware('role:' . RoleConstants::ADMINISTRATOR_GROUP)
        ->group(function () {
            Route::get('/dashboard', fn () => view('admin.dashboard'))->name('admin.dashboard');
            Route::get('/roles', RoleManagement::class)->middleware('permission:' . PermissionConstants::ROLES_INDEX)->name('admin.roles');
        });

    // KFF / PFLK dashboard
    Route::prefix('kff')
        ->middleware('role:' . RoleConstants::KFF_PFLK_GROUP)
        ->group(function () {
            Route::get('/dashboard', fn () => view('kff.dashboard'))->name('kff.dashboard');
        });

    // Referee dashboard
    Route::prefix('referee')
        ->middleware('role:' . RoleConstants::REFEREE_GROUP)
        ->group(function () {
            Route::get('/dashboard', fn () => view('referee.dashboard'))->name('referee.dashboard');
        });
});
