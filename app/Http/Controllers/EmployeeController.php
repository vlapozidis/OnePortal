<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(Request $request): View
    {
        $search = (string) $request->string('search');
        $departmentId = $request->integer('department_id');
        $workMode = (string) $request->string('work_mode');

        $employees = User::query()
            ->visible()
            ->with('department')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($departmentId > 0, fn ($query) => $query->where('department_id', $departmentId))
            ->when(in_array($workMode, ['Remote', 'On Site', 'Hybrid'], true), fn ($query) => $query->where('work_mode', $workMode))
            ->orderBy('name', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('employees', [
            'employees' => $employees,
            'departments' => Department::query()->orderBy('name', 'asc')->get(),
            'workModes' => ['Remote', 'On Site', 'Hybrid'],
            'filters' => [
                'search' => $search,
                'department_id' => $departmentId,
                'work_mode' => $workMode,
            ],
        ]);
    }
}
