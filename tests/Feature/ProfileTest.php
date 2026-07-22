<?php

use App\Models\User;

test('a user can update their profile information', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->patch(route('profile.update'), [
        'name' => 'Updated Name',
        'email' => $user->email,
        'work_mode' => 'Hybrid',
    ]);

    $response->assertRedirect(route('profile.edit'));
    expect($user->fresh()->name)->toBe('Updated Name');
    expect($user->fresh()->work_mode)->toBe('Hybrid');
});

test('an invalid work mode is rejected', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->patch(route('profile.update'), [
        'name' => $user->name,
        'email' => $user->email,
        'work_mode' => 'From the beach',
    ])->assertSessionHasErrors('work_mode');
});

test('changing email requires re-verification', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->patch(route('profile.update'), [
        'name' => $user->name,
        'email' => 'new-address@example.com',
    ]);

    expect($user->fresh()->email_verified_at)->toBeNull();
});

test('a user can delete their own account with the correct password', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->delete(route('profile.destroy'), ['password' => 'password'])
        ->assertRedirect('/');

    expect(User::find($user->id))->toBeNull();
});

test('deleting the account requires the correct current password', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->delete(route('profile.destroy'), ['password' => 'wrong-password'])
        ->assertSessionHasErrors('password', null, 'userDeletion');

    expect(User::find($user->id))->not->toBeNull();
});
