<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function index(): View
    {
        $departments = Department::query()
            ->orderBy('name', 'asc')
            ->paginate(10);

        return view('departments.index', [
            'departments' => $departments,
        ]);
    }

    public function create(): View
    {
        return view('departments.create');
    }

    public function store(StoreDepartmentRequest $request): RedirectResponse
    {
        Department::create($request->validated());

        return redirect()
            ->route('departments.index')
            ->with('status', __('Department created successfully.'));
    }

    public function edit(Department $department): View
    {
        return view('departments.edit', [
            'department' => $department,
        ]);
    }

    public function update(UpdateDepartmentRequest $request, Department $department): RedirectResponse
    {
        $department->update($request->validated());

        return redirect()
            ->route('departments.index')
            ->with('status', __('Department updated successfully.'));
    }

    public function destroy(Department $department): RedirectResponse
    {
        Department::destroy($department->id);

        return redirect()
            ->route('departments.index')
            ->with('status', __('Department deleted successfully.'));
    }
}
