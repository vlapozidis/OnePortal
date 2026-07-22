<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
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

        $dashboardCards = [
            [
                'label' => 'Employee Name',
                'value' => $user->name,
                'note' => 'From authenticated session',
            ],
            [
                'label' => 'Department',
                'value' => $user->department?->name ?? 'Not assigned',
                'note' => 'From profile assignment',
            ],
            [
                'label' => 'Work Mode',
                'value' => $user->work_mode ?? 'Not set',
                'note' => 'From profile settings',
            ],
            [
                'label' => 'Approved Requests',
                'value' => (string) $approvedRequests,
                'note' => 'Total approved leave requests',
            ],
            [
                'label' => 'Pending Requests',
                'value' => (string) $pendingRequests,
                'note' => 'Awaiting review',
            ],
            [
                'label' => 'Latest Attendance',
                'value' => $latestAttendance?->status ?? 'No record yet',
                'note' => $latestAttendance?->attendance_date?->format('M j, Y') ?? 'Update your status in Workforce Today',
            ],
        ];

        return view('dashboard', [
            'dashboardCards' => $dashboardCards,
        ]);
    }
}
