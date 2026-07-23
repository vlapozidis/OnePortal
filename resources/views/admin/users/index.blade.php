<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold leading-tight text-white">{{ __('Users') }}</h2>
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center rounded-none bg-[#DC2626] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#B91C1C]">
            <i class="bi bi-person-plus mr-2"></i>{{ __('Add User') }}
        </a>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-4">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($users as $user)
                <div class="rounded-none border border-[#1F1F1F] bg-[#111111] p-5">
                    <p class="text-base font-semibold text-white">{{ $user->name }}</p>
                    <p class="truncate text-sm text-[#A1A1AA]">{{ $user->email }}</p>

                    <div class="mt-3 flex flex-wrap gap-2 text-xs">
                        <span class="rounded-none border border-[#1F1F1F] px-3 py-1 text-[#A1A1AA]">{{ __(ucfirst($user->role)) }}</span>
                        <span class="rounded-none border border-[#1F1F1F] px-3 py-1 text-[#A1A1AA]">{{ $user->department?->name ?? __('No department') }}</span>
                    </div>

                    <form
                        method="POST"
                        action="{{ route('admin.users.reset-password', $user) }}"
                        class="mt-4 flex items-center justify-between gap-2"
                        onsubmit="return confirm('{{ __('Reset :name\'s password? A new temporary password will be emailed to them.', ['name' => $user->name]) }}')"
                    >
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="rounded-none border border-[#1F1F1F] px-3 py-1.5 text-xs font-semibold text-white transition hover:border-[#B91C1C]">
                            <i class="bi bi-arrow-repeat mr-1"></i>{{ __('Reset password') }}
                        </button>

                        @if ($user->id !== auth()->id())
                            <button
                                type="button"
                                x-data=""
                                x-on:click.prevent="$dispatch('open-modal', 'delete-user-{{ $user->id }}')"
                                class="rounded-none border border-red-700/60 px-3 py-1.5 text-xs font-semibold text-red-300 transition hover:bg-red-900/30"
                            >
                                <i class="bi bi-trash mr-1"></i>{{ __('Delete') }}
                            </button>
                        @endif
                    </form>

                    @if ($user->id !== auth()->id())
                        <x-delete-confirm-modal
                            :name="'delete-user-'.$user->id"
                            :action="route('admin.users.destroy', $user)"
                            :title="__('Are you sure you want to delete this user?')"
                            :message="__('This will permanently remove :name. This action cannot be undone.', ['name' => $user->name])"
                        />
                    @endif
                </div>
            @empty
                <div class="col-span-full rounded-none border border-[#1F1F1F] bg-[#111111] p-8 text-center text-[#A1A1AA]">
                    {{ __('No users found.') }}
                </div>
            @endforelse
        </div>

        <div class="rounded-none border border-[#1F1F1F] bg-[#111111] px-4 py-3 text-[#A1A1AA]">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>
