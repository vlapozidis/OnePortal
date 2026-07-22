<?php

use App\Models\Department;
use App\Models\LeaveRequest;
use App\Models\User;

test('a user only sees their own leave requests', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    LeaveRequest::factory()->for($user)->create();
    LeaveRequest::factory()->for($otherUser)->create();

    $response = $this->actingAs($user)->get(route('leave-requests.index'));

    $response->assertOk();
    expect($response->viewData('leaveRequests'))->toHaveCount(1);
});

test('a user can submit a leave request', function () {
    $user = User::factory()->create();
    $department = Department::factory()->create();

    $response = $this->actingAs($user)->post(route('leave-requests.store'), [
        'department_id' => $department->id,
        'start_date' => now()->addDay()->toDateString(),
        'end_date' => now()->addDays(3)->toDateString(),
        'reason' => 'Family trip',
    ]);

    $response->assertRedirect(route('leave-requests.index'));

    $this->assertDatabaseHas('leave_requests', [
        'user_id' => $user->id,
        'department_id' => $department->id,
        'status' => 'Pending',
    ]);
});

test('a submitted status is always forced to pending regardless of input', function () {
    $user = User::factory()->create();
    $department = Department::factory()->create();

    $this->actingAs($user)->post(route('leave-requests.store'), [
        'department_id' => $department->id,
        'start_date' => now()->addDay()->toDateString(),
        'end_date' => now()->addDays(3)->toDateString(),
        'reason' => 'Trying to self approve',
        'status' => 'Approved',
    ]);

    $this->assertDatabaseHas('leave_requests', [
        'user_id' => $user->id,
        'status' => 'Pending',
    ]);
});

test('end date cannot be before start date', function () {
    $user = User::factory()->create();
    $department = Department::factory()->create();

    $this->actingAs($user)->post(route('leave-requests.store'), [
        'department_id' => $department->id,
        'start_date' => now()->addDays(5)->toDateString(),
        'end_date' => now()->addDays(1)->toDateString(),
        'reason' => 'Bad dates',
    ])->assertSessionHasErrors('end_date');
});

test('a department must exist to submit a leave request against it', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('leave-requests.store'), [
        'department_id' => 999999,
        'start_date' => now()->addDay()->toDateString(),
        'end_date' => now()->addDays(3)->toDateString(),
        'reason' => 'Ghost department',
    ])->assertSessionHasErrors('department_id');
});
