<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeaveRequestRequest;
use App\Models\Department;
use App\Models\LeaveRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class LeaveRequestController extends Controller
{
    public function index(Request $request): View
    {
        $leaveRequests = LeaveRequest::query()
            ->with(['department'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $viewedMonth = $request->query('month');
        $monthStart = ($viewedMonth && preg_match('/^\d{4}-\d{2}$/', $viewedMonth))
            ? Carbon::createFromFormat('Y-m', $viewedMonth)->startOfMonth()
            : now()->startOfMonth();
        $monthEnd = $monthStart->copy()->endOfMonth();

        return view('leave-requests.index', [
            'leaveRequests' => $leaveRequests,
            'calendarMonthLabel' => $monthStart->translatedFormat('F Y'),
            'calendarDaysInMonth' => $monthStart->daysInMonth,
            'calendarLeadingBlanks' => $monthStart->dayOfWeekIso - 1,
            'calendarMonthStart' => $monthStart,
            'calendarPrevMonthUrl' => route('leave-requests.index', ['month' => $monthStart->copy()->subMonth()->format('Y-m')]),
            'calendarNextMonthUrl' => route('leave-requests.index', ['month' => $monthStart->copy()->addMonth()->format('Y-m')]),
            'leaveDayStatuses' => LeaveRequest::dayStatusesForMonth($request->user()->id, $monthStart, $monthEnd),
        ]);
    }

    public function create(Request $request): View
    {
        $validDate = fn (?string $date) => $date !== null && \DateTime::createFromFormat('Y-m-d', $date) !== false
            ? $date
            : null;

        $prefillStart = $validDate($request->query('start_date') ?? $request->query('date'));
        $prefillEnd = $validDate($request->query('end_date') ?? $request->query('date'));

        return view('leave-requests.create', [
            'departments' => Department::query()
                ->orderBy('name', 'asc')
                ->get(),
            'userDepartmentId' => $request->user()->department_id,
            'prefillStart' => $prefillStart,
            'prefillEnd' => $prefillEnd,
        ]);
    }

    public function store(StoreLeaveRequestRequest $request): RedirectResponse
    {
        LeaveRequest::create([
            ...$request->validated(),
            'user_id' => $request->user()->id,
            'status' => 'Pending',
        ]);

        return redirect()
            ->route('leave-requests.index')
            ->with('status', __('Leave request submitted successfully.'));
    }
}
