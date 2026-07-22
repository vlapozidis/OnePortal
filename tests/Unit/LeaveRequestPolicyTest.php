<?php

use App\Models\LeaveRequest;
use App\Models\User;
use App\Policies\LeaveRequestPolicy;

test('only admins can review leave requests', function () {
    $policy = new LeaveRequestPolicy;
    $admin = new User(['role' => 'admin']);
    $employee = new User(['role' => 'employee']);
    $pending = new LeaveRequest(['status' => 'Pending']);

    expect($policy->review($admin, $pending))->toBeTrue();
    expect($policy->review($employee, $pending))->toBeFalse();
});

test('a leave request can only be reviewed while pending', function () {
    $policy = new LeaveRequestPolicy;
    $admin = new User(['role' => 'admin']);
    $approved = new LeaveRequest(['status' => 'Approved']);

    expect($policy->review($admin, $approved))->toBeFalse();
});
