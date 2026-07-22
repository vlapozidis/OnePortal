<?php

use App\Models\Department;
use App\Models\User;

test('any authenticated user can view the department list', function () {
    Department::factory()->count(3)->create();
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('departments.index'))->assertOk();
});

test('admin can create a department', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->post(route('departments.store'), [
        'name' => 'Engineering',
        'description' => 'Builds the product',
    ]);

    $response->assertRedirect(route('departments.index'));
    expect(Department::where('name', 'Engineering')->exists())->toBeTrue();
});

test('department name must be unique', function () {
    Department::factory()->create(['name' => 'Engineering']);
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('departments.store'), ['name' => 'Engineering'])
        ->assertSessionHasErrors('name');
});

test('admin can update and delete a department', function () {
    $admin = User::factory()->admin()->create();
    $department = Department::factory()->create();

    $this->actingAs($admin)
        ->put(route('departments.update', $department), ['name' => 'Renamed Dept'])
        ->assertRedirect(route('departments.index'));

    expect($department->fresh()->name)->toBe('Renamed Dept');

    $this->actingAs($admin)->delete(route('departments.destroy', $department));

    expect(Department::find($department->id))->toBeNull();
});
