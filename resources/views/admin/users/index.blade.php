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
                        class="mt-4 space-y-2"
                        onsubmit="return classterConfirmPasswordsMatch(this)"
                    >
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="reset_user_id" value="{{ $user->id }}">
                        <div class="flex gap-2">
                            <input type="password" name="password" placeholder="{{ __('New password') }}" required minlength="8" class="w-full rounded-none border border-[#1F1F1F] bg-[#0A0A0A] px-2 py-1.5 text-xs text-white placeholder:text-[#71717A] focus:border-[#DC2626] focus:ring-[#DC2626]">
                            <input type="password" name="password_confirmation" placeholder="{{ __('Confirm') }}" required minlength="8" class="w-full rounded-none border border-[#1F1F1F] bg-[#0A0A0A] px-2 py-1.5 text-xs text-white placeholder:text-[#71717A] focus:border-[#DC2626] focus:ring-[#DC2626]">
                        </div>
                        <p class="classter-password-mismatch hidden text-xs text-red-400">
                            <i class="bi bi-exclamation-circle mr-1"></i>{{ __('Passwords do not match.') }}
                        </p>
                        @if ((int) old('reset_user_id') === $user->id)
                            @error('password')
                                <p class="text-xs text-red-400"><i class="bi bi-exclamation-circle mr-1"></i>{{ $message }}</p>
                            @enderror
                        @endif
                        <div class="flex items-center justify-between gap-2">
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
                        </div>
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

    <script>
        function classterConfirmPasswordsMatch(form) {
            const mismatchNotice = form.querySelector('.classter-password-mismatch');

            if (form.password.value !== form.password_confirmation.value) {
                mismatchNotice.classList.remove('hidden');
                return false;
            }

            mismatchNotice.classList.add('hidden');
            return true;
        }
    </script>
</x-app-layout>
