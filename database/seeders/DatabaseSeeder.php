<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(DepartmentSeeder::class);

        $departmentIds = Department::query()->pluck('id')->all();

        User::updateOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'password' => Hash::make('password'),
            'role' => 'employee',
        ]);

        User::updateOrCreate([
            'email' => 'admin@oneportal.local',
        ], [
            'name' => 'Admin User',
            'password' => Hash::make('password'),
            'department_id' => $departmentIds[0] ?? null,
            'work_mode' => 'On Site',
            'role' => 'admin',
        ]);

        $sampleEmployees = [
            ['name' => 'Maria Stefanou', 'email' => 'maria.stefanou@oneportal.local', 'work_mode' => 'Remote'],
            ['name' => 'Nikos Andreou', 'email' => 'nikos.andreou@oneportal.local', 'work_mode' => 'On Site'],
            ['name' => 'Elena Georgiou', 'email' => 'elena.georgiou@oneportal.local', 'work_mode' => 'Hybrid'],
            ['name' => 'Petros Mavris', 'email' => 'petros.mavris@oneportal.local', 'work_mode' => 'Remote'],
            ['name' => 'Irene Kyriakou', 'email' => 'irene.kyriakou@oneportal.local', 'work_mode' => 'On Site'],
            ['name' => 'Andreas Zeniou', 'email' => 'andreas.zeniou@oneportal.local', 'work_mode' => 'Hybrid'],
        ];

        foreach ($sampleEmployees as $index => $employee) {
            User::updateOrCreate(
                ['email' => $employee['email']],
                [
                    'name' => $employee['name'],
                    'password' => Hash::make('password'),
                    'department_id' => $departmentIds[$index % max(count($departmentIds), 1)] ?? null,
                    'work_mode' => $employee['work_mode'],
                    'role' => 'employee',
                ]
            );
        }
    }
}
