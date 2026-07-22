<?php

use App\Models\Attendance;
use App\Models\User;

test('a user can update todays work status', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->put(route('workforce.status.update'), [
        'status' => 'Remote',
    ])->assertRedirect(route('workforce.today'));

    $this->assertDatabaseHas('attendances', [
        'user_id' => $user->id,
        'status' => 'Remote',
    ]);
});

test('an invalid status is rejected', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->put(route('workforce.status.update'), [
        'status' => 'Napping',
    ])->assertSessionHasErrors('status');
});

test('checking in stamps a checked in time independent of status', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->put(route('workforce.checkin'), [
        'status' => 'Leave',
    ])->assertRedirect(route('workforce.today'));

    $attendance = Attendance::where('user_id', $user->id)->first();

    expect($attendance->status)->toBe('Leave');
    expect($attendance->checked_in_at)->not->toBeNull();
});

test('todays workforce page summarizes statuses correctly', function () {
    $viewer = User::factory()->create();

    Attendance::factory()->create(['status' => 'Remote']);
    Attendance::factory()->create(['status' => 'On Site']);
    Attendance::factory()->create(['status' => 'On Site', 'checked_in_at' => now()]);

    $response = $this->actingAs($viewer)->get(route('workforce.today'));

    $response->assertOk();
    expect($response->viewData('summary'))->toMatchArray([
        'Remote' => 1,
        'On Site' => 2,
        'Hybrid' => 0,
        'Leave' => 0,
        'Checked In' => 1,
    ]);
});
