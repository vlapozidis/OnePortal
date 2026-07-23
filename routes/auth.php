<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\EntraIDController;
use App\Http\Controllers\Auth\ForcePasswordChangeController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
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
| Local Authentication Routes (Commented Out - Using Entra ID Only)
|--------------------------------------------------------------------------
|
| These routes have been replaced with Entra ID authentication.
| Uncomment the routes below to re-enable local authentication.
|
*/

/*
Route::middleware('guest')->group(function (): void {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});
*/

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

    // Email verification routes (commented out if using Entra ID for all verification)
    /*
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
    */
});
