<?php

use App\Models\User;

test('guests cannot view the dashboard', function () {
    $this->get(route('dashboard'))->assertRedirect(route('login'));
});

test('authenticated users can view their dashboard', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('dashboard'))->assertOk();
});
