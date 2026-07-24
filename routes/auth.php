<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EntraIDController;
use App\Http\Controllers\Auth\ForcePasswordChangeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Microsoft Entra ID Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function (): void {
    Route::get('login', [EntraIDController::class, 'show'])->name('login');
    Route::middleware('check-entra-config')->group(function (): void {
        Route::get('auth/microsoft/redirect', [EntraIDController::class, 'redirect'])->name('auth.microsoft');
        Route::get('auth/entra/callback', [EntraIDController::class, 'callback'])->name('entra.callback');
    });
});

/*
|--------------------------------------------------------------------------
| Local Authentication Routes (Email/Password Login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function (): void {
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function (): void {
    // Logout route
    Route::post('logout', [EntraIDController::class, 'logout'])->name('logout');

    // Forced password change (after an admin-triggered reset)
    Route::get('force-password-change', [ForcePasswordChangeController::class, 'edit'])
        ->name('password.force-change');
    Route::put('force-password-change', [ForcePasswordChangeController::class, 'update'])
        ->name('password.force-change.update');
});
