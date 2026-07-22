<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LeaveRequest>
 */
class LeaveRequestFactory extends Factory
{
    protected $model = LeaveRequest::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('now', '+1 month');
        $endDate = (clone $startDate)->modify('+'.fake()->numberBetween(1, 5).' days');

        return [
            'user_id' => User::factory(),
            'department_id' => Department::factory(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'reason' => fake()->sentence(),
            'status' => 'Pending',
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Approved',
            'reviewed_by' => User::factory()->admin(),
            'reviewed_at' => now(),
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Rejected',
            'reviewed_by' => User::factory()->admin(),
            'reviewed_at' => now(),
        ]);
    }
}
