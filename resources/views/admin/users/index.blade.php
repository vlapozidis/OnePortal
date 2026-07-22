<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold leading-tight text-white">Users</h2>
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center rounded-none bg-[#DC2626] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#B91C1C]">
            Add User
        </a>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-4">
        @if (session('status'))
            <div class="rounded-none border border-green-600/40 bg-green-900/20 px-4 py-3 text-sm text-green-300">
                {{ session('status') }}
            </div>
        @endif

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($users as $user)
                <div class="rounded-none border border-[#1F1F1F] bg-[#111111] p-5">
                    <p class="text-base font-semibold text-white">{{ $user->name }}</p>
                    <p class="truncate text-sm text-[#A1A1AA]">{{ $user->email }}</p>

                    <div class="mt-3 flex flex-wrap gap-2 text-xs">
                        <span class="rounded-none border border-[#1F1F1F] px-3 py-1 text-[#A1A1AA]">{{ ucfirst($user->role) }}</span>
                        <span class="rounded-none border border-[#1F1F1F] px-3 py-1 text-[#A1A1AA]">{{ $user->department?->name ?? 'No department' }}</span>
                    </div>

                    <form method="POST" action="{{ route('admin.users.reset-password', $user) }}" class="mt-4 space-y-2">
                        @csrf
                        @method('PATCH')
                        <div class="flex gap-2">
                            <input type="password" name="password" placeholder="New password" required minlength="8" class="w-full rounded-none border border-[#1F1F1F] bg-[#0A0A0A] px-2 py-1.5 text-xs text-white placeholder:text-[#71717A] focus:border-[#DC2626] focus:ring-[#DC2626]">
                            <input type="password" name="password_confirmation" placeholder="Confirm" required minlength="8" class="w-full rounded-none border border-[#1F1F1F] bg-[#0A0A0A] px-2 py-1.5 text-xs text-white placeholder:text-[#71717A] focus:border-[#DC2626] focus:ring-[#DC2626]">
                        </div>
                        <div class="flex items-center justify-between gap-2">
                            <button type="submit" class="rounded-none border border-[#1F1F1F] px-3 py-1.5 text-xs font-semibold text-white transition hover:border-[#B91C1C]">
                                Reset password
                            </button>

                            @if ($user->id !== auth()->id())
                                <button
                                    type="submit"
                                    form="delete-user-{{ $user->id }}"
                                    class="rounded-none border border-red-700/60 px-3 py-1.5 text-xs font-semibold text-red-300 transition hover:bg-red-900/30"
                                >
                                    Delete
                                </button>
                            @endif
                        </div>
                    </form>

                    @if ($user->id !== auth()->id())
                        <form id="delete-user-{{ $user->id }}" method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user?');" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endif
                </div>
            @empty
                <div class="col-span-full rounded-none border border-[#1F1F1F] bg-[#111111] p-8 text-center text-[#A1A1AA]">
                    No users found.
                </div>
            @endforelse
        </div>

        <div class="rounded-none border border-[#1F1F1F] bg-[#111111] px-4 py-3 text-[#A1A1AA]">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>
