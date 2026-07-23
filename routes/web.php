<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminLeaveApprovalController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StatisticsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::put('/locale/{locale}', function (string $locale) {
    abort_unless(in_array($locale, App\Http\Middleware\SetLocale::SUPPORTED_LOCALES, true), 404);

    session(['locale' => $locale]);

    return back();
})->name('locale.switch');

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/workforce/today', [AttendanceController::class, 'today'])->name('workforce.today');
    Route::put('/workforce/today/check-in', [AttendanceController::class, 'checkIn'])->name('workforce.checkin');
    Route::put('/workforce/today/check-out', [AttendanceController::class, 'checkOut'])->name('workforce.checkout');
    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics.index');
    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::resource('leave-requests', LeaveRequestController::class)->only(['index', 'create', 'store']);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function (): void {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::patch('/leave-requests/{leaveRequest}/approve', [AdminLeaveApprovalController::class, 'approve'])
            ->name('leave-requests.approve');
        Route::patch('/leave-requests/{leaveRequest}/reject', [AdminLeaveApprovalController::class, 'reject'])
            ->name('leave-requests.reject');

        Route::resource('users', AdminUserController::class)
            ->only(['index', 'create', 'store', 'destroy']);
        Route::patch('/users/{user}/reset-password', [AdminUserController::class, 'resetPassword'])
            ->name('users.reset-password');
    });

Route::middleware(['auth', 'admin'])->group(function (): void {
    Route::resource('departments', DepartmentController::class)
        ->except(['index', 'show']);
});

require __DIR__.'/auth.php';
