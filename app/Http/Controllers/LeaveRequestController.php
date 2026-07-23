<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeaveRequestRequest;
use App\Models\Department;
use App\Models\LeaveRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        return view('leave-requests.index', [
            'leaveRequests' => $leaveRequests,
        ]);
    }

    public function create(Request $request): View
    {
        return view('leave-requests.create', [
            'departments' => Department::query()
                ->orderBy('name', 'asc')
                ->get(),
            'userDepartmentId' => $request->user()->department_id,
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
