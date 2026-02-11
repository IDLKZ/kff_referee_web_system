<?php

use App\Constants\PermissionConstants;
use App\Constants\RoleConstants;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocaleController;
use App\Livewire\Admin\CityManagement;
use App\Livewire\Admin\CountryManagement;
use App\Livewire\Admin\RoleManagement;
use App\Livewire\Admin\PermissionManagement;
use App\Livewire\Admin\RolePermissionManagement;
use App\Livewire\Admin\JudgeCityManagement;
use App\Livewire\Admin\JudgeTypeManagement;
use App\Livewire\Admin\JudgeRequirementManagement;
use App\Livewire\Admin\MatchManagement;
use App\Livewire\Admin\TournamentManagement;
use App\Livewire\Admin\UserManagement;
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
            Route::get('/permissions', PermissionManagement::class)->middleware('permission:' . PermissionConstants::PERMISSIONS_INDEX)->name('admin.permissions');
            Route::get('/role-permissions', RolePermissionManagement::class)->middleware('permission:' . PermissionConstants::ROLE_PERMISSIONS_INDEX)->name('admin.role-permissions');
            Route::get('/users', UserManagement::class)->middleware('permission:' . PermissionConstants::USERS_INDEX)->name('admin.users');
            Route::get('/countries', CountryManagement::class)->middleware('permission:' . PermissionConstants::COUNTRIES_INDEX)->name('admin.countries');
            Route::get('/cities', CityManagement::class)->middleware('permission:' . PermissionConstants::CITIES_INDEX)->name('admin.cities');
            Route::get('/judge-types', JudgeTypeManagement::class)->middleware('permission:' . PermissionConstants::JUDGE_TYPES_INDEX)->name('admin.judge-types');
            Route::get('/judge-cities', JudgeCityManagement::class)->middleware('permission:' . PermissionConstants::JUDGE_CITIES_INDEX)->name('admin.judge-cities');
            Route::get('/judge-requirements', JudgeRequirementManagement::class)->middleware('permission:' . PermissionConstants::JUDGE_REQUIREMENTS_INDEX)->name('admin.judge-requirements');
            Route::get('/tournaments', TournamentManagement::class)->middleware('permission:' . PermissionConstants::TOURNAMENTS_INDEX)->name('admin.tournaments');
            Route::get('/matches', MatchManagement::class)->middleware('permission:' . PermissionConstants::MATCHES_INDEX)->name('admin.matches');
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
