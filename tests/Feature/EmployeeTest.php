<?php

use App\Models\Department;
use App\Models\User;

test('employees index lists all employees to any authenticated user', function () {
    User::factory()->count(3)->create();
    $viewer = User::factory()->create();

    $this->actingAs($viewer)->get(route('employees.index'))->assertOk();
});

test('employees can be filtered by department', function () {
    $viewer = User::factory()->create();
    $engineering = Department::factory()->create(['name' => 'Engineering']);
    $sales = Department::factory()->create(['name' => 'Sales']);

    $engineer = User::factory()->create(['department_id' => $engineering->id]);
    User::factory()->create(['department_id' => $sales->id]);

    $response = $this->actingAs($viewer)->get(route('employees.index', ['department_id' => $engineering->id]));

    $response->assertOk();
    $employees = $response->viewData('employees');

    expect($employees)->toHaveCount(1);
    expect($employees->getCollection()->pluck('id'))->toContain($engineer->id);
});
