<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(): View
    {
        $users = User::query()
            ->visible()
            ->with('department')
            ->orderBy('name', 'asc')
            ->paginate(15);

        return view('admin.users.index', [
            'users' => $users,
        ]);
    }

    public function create(): View
    {
        return view('admin.users.create', [
            'departments' => Department::query()->orderBy('name', 'asc')->get(),
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        User::create([
            ...$request->validated(),
            'auth_provider' => 'local',
            'email_verified_at' => now(),
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('status', __('User created successfully.'));
    }

    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        $temporaryPassword = Str::password(12, symbols: false);

        $user->update([
            'password' => $temporaryPassword,
            'must_change_password' => true,
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('temporaryPasswordFor', $user->name)
            ->with('temporaryPassword', $temporaryPassword);
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return redirect()
                ->route('admin.users.index')
                ->with('status', __('You cannot delete your own account.'));
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('status', __('User deleted successfully.'));
    }
}
