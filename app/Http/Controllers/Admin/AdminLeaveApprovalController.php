<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminReviewLeaveRequestRequest;
use App\Models\LeaveRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class AdminLeaveApprovalController extends Controller
{
    public function approve(AdminReviewLeaveRequestRequest $request, LeaveRequest $leaveRequest): RedirectResponse
    {
        Gate::authorize('review', $leaveRequest);

        $leaveRequest->update([
            'status' => 'Approved',
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
            'admin_comment' => $request->input('admin_comment'),
        ]);

        return back()->with('status', __('Leave request approved.'));
    }

    public function reject(AdminReviewLeaveRequestRequest $request, LeaveRequest $leaveRequest): RedirectResponse
    {
        Gate::authorize('review', $leaveRequest);

        $leaveRequest->update([
            'status' => 'Rejected',
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
            'admin_comment' => $request->input('admin_comment'),
        ]);

        return back()->with('status', __('Leave request rejected.'));
    }
}
