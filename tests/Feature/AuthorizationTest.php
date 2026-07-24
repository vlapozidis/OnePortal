<?php

use App\Models\Department;
use App\Models\User;

test('guests are redirected to login from protected pages', function () {
    $this->get('/dashboard')->assertRedirect('/login');
    $this->get('/departments')->assertRedirect('/login');
    $this->get('/workforce/today')->assertRedirect('/login');
});

test('regular users cannot create a department, even by visiting the url directly', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('departments.create'))->assertForbidden();
    $this->actingAs($user)->post(route('departments.store'), ['name' => 'Finance'])->assertForbidden();

    expect(Department::where('name', 'Finance')->exists())->toBeFalse();
});

test('regular users cannot edit or delete an existing department by guessing its id', function () {
    $user = User::factory()->create();
    $department = Department::factory()->create(['name' => 'Original Name']);

    $this->actingAs($user)->get(route('departments.edit', $department))->assertForbidden();
    $this->actingAs($user)->put(route('departments.update', $department), ['name' => 'Renamed'])->assertForbidden();
    $this->actingAs($user)->delete(route('departments.destroy', $department))->assertForbidden();

    expect($department->fresh()->name)->toBe('Original Name');
});

