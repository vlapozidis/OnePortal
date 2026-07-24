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

        $checkedOutToday = $checkedInToday && $latestAttendance->checked_out_at !== null;

        $canCheckOut = now()->hour >= Attendance::CHECK_OUT_AVAILABLE_FROM_HOUR;

        $viewedMonth = $request->query('month');
        $monthStart = ($viewedMonth && preg_match('/^\d{4}-\d{2}$/', $viewedMonth))
            ? Carbon::createFromFormat('Y-m', $viewedMonth)->startOfMonth()
            : $today->copy()->startOfMonth();
        $monthEnd = $monthStart->copy()->endOfMonth();

        $checkedInDays = Attendance::query()
            ->where('user_id', $user->id)
            ->whereNotNull('checked_in_at')
            ->whereBetween('attendance_date', [$monthStart, $monthEnd])
            ->pluck('attendance_date')
            ->map(fn (Carbon $date) => $date->day)
            ->all();

        $leaveDayStatuses = LeaveRequest::dayStatusesForMonth($user->id, $monthStart, $monthEnd);

        $onLeaveToday = LeaveRequest::query()
            ->where('user_id', $user->id)
            ->where('status', 'Approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->exists();

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
            'checkedOutToday' => $checkedOutToday,
            'checkedOutAt' => $checkedOutToday ? $latestAttendance->checked_out_at : null,
            'canCheckOut' => $canCheckOut,
            'onLeaveToday' => $onLeaveToday,
            'calendarMonthLabel' => $monthStart->translatedFormat('F Y'),
            'calendarDaysInMonth' => $monthStart->daysInMonth,
            'calendarLeadingBlanks' => $monthStart->dayOfWeekIso - 1,
            'calendarMonthStart' => $monthStart,
            'calendarPrevMonthUrl' => route('dashboard', ['month' => $monthStart->copy()->subMonth()->format('Y-m')]),
            'calendarNextMonthUrl' => route('dashboard', ['month' => $monthStart->copy()->addMonth()->format('Y-m')]),
            'leaveDayStatuses' => $leaveDayStatuses,
            'checkedInDays' => $checkedInDays,
        ]);
    }
}
