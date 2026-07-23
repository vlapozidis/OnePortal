<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $auditStatus = $request->query('audit_status', '');
        $auditDepartmentId = (int) $request->query('audit_department_id', 0);

        $pendingRequests = LeaveRequest::query()
            ->with(['user', 'department'])
            ->where('status', 'Pending')
            ->orderBy('start_date', 'asc')
            ->paginate(8, ['*'], 'pending_page');

        $recentAudits = LeaveRequest::query()
            ->with(['user', 'department', 'reviewer'])
            ->whereIn('status', ['Approved', 'Rejected'])
            ->when(in_array($auditStatus, ['Approved', 'Rejected'], true), fn ($query) => $query->where('status', $auditStatus))
            ->when($auditDepartmentId > 0, fn ($query) => $query->where('department_id', $auditDepartmentId))
            ->orderBy('reviewed_at', 'desc')
            ->paginate(9, ['*'], 'audit_page')
            ->withQueryString();

        return view('admin.dashboard', [
            'pendingRequests' => $pendingRequests,
            'recentAudits' => $recentAudits,
            'departments' => Department::query()->orderBy('name', 'asc')->get(),
            'auditFilters' => [
                'status' => $auditStatus,
                'department_id' => $auditDepartmentId,
            ],
            'pendingCount' => LeaveRequest::query()->where('status', 'Pending')->get()->count(),
            'approvedCount' => LeaveRequest::query()->where('status', 'Approved')->get()->count(),
            'rejectedCount' => LeaveRequest::query()->where('status', 'Rejected')->get()->count(),
        ]);
    }
}
