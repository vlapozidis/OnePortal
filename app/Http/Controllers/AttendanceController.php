<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function today(Request $request): View
    {
        $today = now()->toDateString();

        $todayRecords = Attendance::query()
            ->with(['user.department'])
            ->whereHas('user', fn ($query) => $query->visible())
            ->whereDate('attendance_date', $today)
            ->orderBy('status', 'asc')
            ->orderBy('user_id', 'asc')
            ->get();

        $employeesWithoutEntry = User::query()
            ->visible()
            ->whereNotIn('id', $todayRecords->pluck('user_id')->all(), 'and')
            ->with('department')
            ->orderBy('name', 'asc')
            ->get();

        return view('attendance.today', [
            'today' => $today,
            'workStatuses' => Attendance::WORK_STATUSES,
            'myTodayAttendance' => Attendance::query()
                ->where('user_id', $request->user()->id)
                ->whereDate('attendance_date', '=', $today, 'and')
                ->first(),
            'todayRecords' => $todayRecords,
            'employeesWithoutEntry' => $employeesWithoutEntry,
            'summary' => [
                'Remote' => $todayRecords->where('status', 'Remote')->count(),
                'On Site' => $todayRecords->where('status', 'On Site')->count(),
                'Hybrid' => $todayRecords->where('status', 'Hybrid')->count(),
                'Leave' => $todayRecords->where('status', 'Leave')->count(),
                'Checked In' => $todayRecords->whereNotNull('checked_in_at')->count(),
            ],
        ]);
    }

    public function checkIn(Request $request): RedirectResponse
    {
        $status = in_array($request->user()->work_mode, Attendance::WORK_STATUSES, true)
            ? $request->user()->work_mode
            : 'On Site';

        Attendance::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'attendance_date' => now()->toDateString(),
            ],
            [
                'status' => $status,
                'checked_in_at' => now(),
            ]
        );

        return redirect()
            ->back()
            ->with('status', __('Checked in successfully.'));
    }

    public function checkOut(Request $request): RedirectResponse
    {
        abort_unless(
            now()->hour >= Attendance::CHECK_OUT_AVAILABLE_FROM_HOUR,
            403,
            __('Check-out is only available from :hour:00 onward.', ['hour' => Attendance::CHECK_OUT_AVAILABLE_FROM_HOUR])
        );

        $attendance = Attendance::query()
            ->where('user_id', $request->user()->id)
            ->whereDate('attendance_date', now()->toDateString())
            ->whereNotNull('checked_in_at')
            ->first();

        abort_unless($attendance, 404, __("You haven't checked in today."));

        $attendance->update(['checked_out_at' => now()]);

        return redirect()
            ->back()
            ->with('status', __('Checked out successfully.'));
    }
}
