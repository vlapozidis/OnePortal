<?php

use App\Models\LeaveRequest;
use App\Models\User;

test('admin can approve a pending leave request', function () {
    $admin = User::factory()->admin()->create();
    $leaveRequest = LeaveRequest::factory()->create(['status' => 'Pending']);

    $response = $this->actingAs($admin)->patch(route('admin.leave-requests.approve', $leaveRequest), [
        'admin_comment' => 'Approved, enjoy!',
    ]);

    $response->assertRedirect();
    $leaveRequest->refresh();

    expect($leaveRequest->status)->toBe('Approved');
    expect($leaveRequest->reviewed_by)->toBe($admin->id);
    expect($leaveRequest->admin_comment)->toBe('Approved, enjoy!');
});

test('admin can reject a pending leave request', function () {
    $admin = User::factory()->admin()->create();
    $leaveRequest = LeaveRequest::factory()->create(['status' => 'Pending']);

    $this->actingAs($admin)->patch(route('admin.leave-requests.reject', $leaveRequest));

    expect($leaveRequest->fresh()->status)->toBe('Rejected');
});

test('an already reviewed leave request cannot be reviewed again', function () {
    $admin = User::factory()->admin()->create();
    $leaveRequest = LeaveRequest::factory()->create(['status' => 'Approved']);

    $this->actingAs($admin)
        ->patch(route('admin.leave-requests.approve', $leaveRequest))
        ->assertForbidden();
});

test('non admins cannot approve or reject leave requests', function () {
    $user = User::factory()->create();
    $leaveRequest = LeaveRequest::factory()->create(['status' => 'Pending']);

    $this->actingAs($user)
        ->patch(route('admin.leave-requests.approve', $leaveRequest))
        ->assertForbidden();

    expect($leaveRequest->fresh()->status)->toBe('Pending');
});
