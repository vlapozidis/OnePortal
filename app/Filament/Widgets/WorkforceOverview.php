<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Attendances\AttendanceResource;
use App\Filament\Resources\LeaveRequests\LeaveRequestResource;
use App\Filament\Resources\Users\UserResource;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class WorkforceOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $pendingCount = LeaveRequest::where('status', 'Pending')->count();
        $approvedThisMonth = LeaveRequest::where('status', 'Approved')
            ->whereYear('reviewed_at', now()->year)
            ->whereMonth('reviewed_at', now()->month)
            ->count();
        $checkedInToday = Attendance::whereDate('attendance_date', today())
            ->whereNotNull('checked_in_at')
            ->count();

        return [
            Stat::make(__('Employees'), User::visible()->count())
                ->description(__('Total active employees'))
                ->icon('bi-people')
                ->color('gray')
                ->url(UserResource::getUrl('index')),

            Stat::make(__('Pending Leave Requests'), $pendingCount)
                ->description(__('Awaiting review'))
                ->icon('bi-hourglass-split')
                ->color($pendingCount > 0 ? 'warning' : 'success')
                ->url(LeaveRequestResource::getUrl('index', ['tableFilters' => ['status' => ['value' => 'Pending']]])),

            Stat::make(__('Approved This Month'), $approvedThisMonth)
                ->description(__('Leave requests approved'))
                ->icon('bi-check-circle')
                ->color('success')
                ->url(LeaveRequestResource::getUrl('index')),

            Stat::make(__('Checked In Today'), $checkedInToday)
                ->description(__('Employees checked in'))
                ->icon('bi-clock-history')
                ->color('primary')
                ->url(AttendanceResource::getUrl('index')),
        ];
    }
}
