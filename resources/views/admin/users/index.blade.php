<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold leading-tight text-[var(--portal-text-primary)]">{{ __('Users') }}</h2>
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center rounded-none bg-[var(--portal-primary)] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[var(--portal-primary-hover)]">
            <i class="bi bi-person-plus mr-2"></i>{{ __('Add User') }}
        </a>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-4">
        @if (session('temporaryPassword'))
            <div class="rounded-none border border-[var(--portal-primary)] bg-[var(--portal-primary)]/10 p-5">
                <p class="text-sm font-semibold text-[var(--portal-text-primary)]">{{ __('Temporary password for :name:', ['name' => session('temporaryPasswordFor')]) }}</p>
                <p class="mt-2 select-all rounded-none border border-[var(--portal-border)] bg-[var(--portal-bg)] px-4 py-2 font-mono text-lg tracking-wider text-[var(--portal-text-primary)]">
                    {{ session('temporaryPassword') }}
                </p>
                <p class="mt-2 text-xs text-[var(--portal-text-secondary)]">
                    <i class="bi bi-exclamation-triangle mr-1"></i>
                    {{ __('This is shown only once. Share it securely with the user — they will be required to set a new password on next login.') }}
                </p>
            </div>
        @endif

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($users as $user)
                <div class="rounded-none border border-[var(--portal-border)] bg-[var(--portal-surface)] p-5">
                    <div class="flex items-center gap-3">
                        <x-user-avatar :user="$user" size="md" />
                        <div class="min-w-0">
                            <p class="truncate text-base font-semibold text-[var(--portal-text-primary)]">{{ $user->name }}</p>
                            <p class="truncate text-sm text-[var(--portal-text-secondary)]">{{ $user->email }}</p>
                        </div>
                    </div>

                    @if ($user->isEntraConnected())
                        <x-entra-badge :user="$user" class="mt-3" />
                    @endif

                    <div class="mt-3 flex flex-wrap gap-2 text-xs">
                        <span class="rounded-none border border-[var(--portal-border)] px-3 py-1 text-[var(--portal-text-secondary)]">{{ __(ucfirst($user->role)) }}</span>
                        <span class="rounded-none border border-[var(--portal-border)] px-3 py-1 text-[var(--portal-text-secondary)]">{{ $user->department?->name ?? __('No department') }}</span>
                    </div>

                    <form
                        method="POST"
                        action="{{ route('admin.users.reset-password', $user) }}"
                        class="mt-4 flex items-center justify-between gap-2"
                        onsubmit="return confirm('{{ __('Reset :name\'s password? A new temporary password will be generated for you to share with them.', ['name' => $user->name]) }}')"
                    >
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="rounded-none border border-[var(--portal-border)] px-3 py-1.5 text-xs font-semibold text-[var(--portal-text-primary)] transition hover:border-[var(--portal-primary-hover)]">
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
                <div class="col-span-full rounded-none border border-[var(--portal-border)] bg-[var(--portal-surface)] p-8 text-center text-[var(--portal-text-secondary)]">
                    {{ __('No users found.') }}
                </div>
            @endforelse
        </div>

        <div class="rounded-none border border-[var(--portal-border)] bg-[var(--portal-surface)] px-4 py-3 text-[var(--portal-text-secondary)]">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>
