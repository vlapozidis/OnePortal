<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold leading-tight text-white">Users</h2>
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center rounded-lg bg-[#DC2626] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#B91C1C]">
            Add User
        </a>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-4">
        @if (session('status'))
            <div class="rounded-xl border border-green-600/40 bg-green-900/20 px-4 py-3 text-sm text-green-300">
                {{ session('status') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-[#1F1F1F] bg-[#111111]">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[#1F1F1F] text-sm">
                    <thead class="bg-[#0A0A0A]">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-[#A1A1AA]">Name</th>
                            <th class="px-4 py-3 text-left font-medium text-[#A1A1AA]">Email</th>
                            <th class="px-4 py-3 text-left font-medium text-[#A1A1AA]">Role</th>
                            <th class="px-4 py-3 text-left font-medium text-[#A1A1AA]">Department</th>
                            <th class="px-4 py-3 text-right font-medium text-[#A1A1AA]">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#1F1F1F]">
                        @forelse ($users as $user)
                            <tr>
                                <td class="px-4 py-3 text-white">{{ $user->name }}</td>
                                <td class="px-4 py-3 text-[#A1A1AA]">{{ $user->email }}</td>
                                <td class="px-4 py-3 text-[#A1A1AA]">{{ ucfirst($user->role) }}</td>
                                <td class="px-4 py-3 text-[#A1A1AA]">{{ $user->department?->name ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-col items-end gap-2">
                                        <form method="POST" action="{{ route('admin.users.reset-password', $user) }}" class="flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <input type="password" name="password" placeholder="New password" required minlength="8" class="w-36 rounded-lg border border-[#1F1F1F] bg-[#0A0A0A] px-2 py-1 text-xs text-white placeholder:text-[#71717A] focus:border-[#DC2626] focus:ring-[#DC2626]">
                                            <input type="password" name="password_confirmation" placeholder="Confirm" required minlength="8" class="w-28 rounded-lg border border-[#1F1F1F] bg-[#0A0A0A] px-2 py-1 text-xs text-white placeholder:text-[#71717A] focus:border-[#DC2626] focus:ring-[#DC2626]">
                                            <button type="submit" class="rounded-lg border border-[#1F1F1F] px-3 py-1.5 text-xs font-semibold text-white transition hover:border-[#B91C1C]">
                                                Reset
                                            </button>
                                        </form>

                                        @if ($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-lg border border-red-700/60 px-3 py-1.5 text-xs font-semibold text-red-300 transition hover:bg-red-900/30">
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-[#A1A1AA]">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-[#1F1F1F] bg-[#0A0A0A] px-4 py-3 text-[#A1A1AA]">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
