<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateTodayAttendanceRequest;
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

    public function updateMyStatus(UpdateTodayAttendanceRequest $request): RedirectResponse
    {
        Attendance::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'attendance_date' => now()->toDateString(),
            ],
            [
                'status' => $request->validated('status'),
            ]
        );

        return redirect()
            ->route('workforce.today')
            ->with('status', 'Today status updated successfully.');
    }

    public function checkIn(UpdateTodayAttendanceRequest $request): RedirectResponse
    {
        Attendance::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'attendance_date' => now()->toDateString(),
            ],
            [
                'status' => $request->validated('status'),
                'checked_in_at' => now(),
            ]
        );

        return redirect()
            ->route('workforce.today')
            ->with('status', 'Checked in successfully.');
    }
}
