<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user()->loadMissing('department');

        $pendingRequests = LeaveRequest::query()
            ->where('user_id', $user->id)
            ->where('status', 'Pending')
            ->count();

        $approvedRequests = LeaveRequest::query()
            ->where('user_id', $user->id)
            ->where('status', 'Approved')
            ->count();

        $latestAttendance = Attendance::query()
            ->where('user_id', $user->id)
            ->latest('attendance_date')
            ->first();

        $today = now()->startOfDay();

        $checkedInToday = $latestAttendance
            && $latestAttendance->attendance_date->isSameDay($today)
            && $latestAttendance->checked_in_at !== null;

        $checkedInDays = Attendance::query()
            ->where('user_id', $user->id)
            ->whereNotNull('checked_in_at')
            ->whereBetween('attendance_date', [$today->copy()->startOfMonth(), $today->copy()->endOfMonth()])
            ->pluck('attendance_date')
            ->map(fn (Carbon $date) => $date->day)
            ->all();

        $dashboardCards = [
            [
                'label' => __('Employee Name'),
                'value' => $user->name,
                'note' => __('From authenticated session'),
            ],
            [
                'label' => __('Department'),
                'value' => $user->department?->name ?? __('Not assigned'),
                'note' => __('From profile assignment'),
            ],
            [
                'label' => __('Work Mode'),
                'value' => $user->work_mode ? __($user->work_mode) : __('Not set'),
                'note' => __('From profile settings'),
            ],
            [
                'label' => __('Approved Requests'),
                'value' => (string) $approvedRequests,
                'note' => __('Total approved leave requests'),
            ],
            [
                'label' => __('Pending Requests'),
                'value' => (string) $pendingRequests,
                'note' => __('Awaiting review'),
            ],
            [
                'label' => __('Latest Attendance'),
                'value' => $latestAttendance?->status ?? __('No record yet'),
                'note' => $latestAttendance?->attendance_date?->format('M j, Y') ?? __('Check in below to record it'),
            ],
        ];

        return view('dashboard', [
            'dashboardCards' => $dashboardCards,
            'checkedInToday' => $checkedInToday,
            'calendarMonthLabel' => $today->translatedFormat('F Y'),
            'calendarDaysInMonth' => $today->daysInMonth,
            'calendarLeadingBlanks' => $today->copy()->startOfMonth()->dayOfWeekIso - 1,
            'calendarToday' => $today->day,
            'checkedInDays' => $checkedInDays,
        ]);
    }
}
