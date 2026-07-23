<?php

use App\Models\Attendance;
use App\Models\User;

test('checking in stamps a checked in time using the profile work mode', function () {
    $user = User::factory()->create(['work_mode' => 'Hybrid']);

    $this->actingAs($user)->put(route('workforce.checkin'))->assertRedirect();

    $attendance = Attendance::where('user_id', $user->id)->first();

    expect($attendance->status)->toBe('Hybrid');
    expect($attendance->checked_in_at)->not->toBeNull();
});

test('checking in falls back to on site when no work mode is set', function () {
    $user = User::factory()->create(['work_mode' => null]);

    $this->actingAs($user)->put(route('workforce.checkin'))->assertRedirect();

    $attendance = Attendance::where('user_id', $user->id)->first();

    expect($attendance->status)->toBe('On Site');
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
