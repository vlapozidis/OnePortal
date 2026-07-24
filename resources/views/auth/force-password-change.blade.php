<x-guest-layout>
    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-[var(--portal-text-primary)]">{{ __('Set a new password') }}</h1>
        <p class="text-sm text-[var(--portal-text-secondary)] mt-2">{{ __('Your password was reset by an administrator. Choose a new password to continue.') }}</p>
    </div>

    <form method="POST" action="{{ route('password.force-change.update') }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="password" class="block text-sm font-medium text-[var(--portal-text-secondary)] mb-2">
                {{ __('New password') }}
            </label>
            <input id="password" class="block w-full rounded-none border border-[var(--portal-border)] bg-[var(--portal-border)] px-4 py-2 text-[var(--portal-text-primary)] placeholder-[var(--portal-text-secondary)] focus:border-blue-500 focus:ring-blue-500"
                type="password" name="password" required autocomplete="new-password" autofocus />
            @error('password')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-[var(--portal-text-secondary)] mb-2">
                {{ __('Confirm new password') }}
            </label>
            <input id="password_confirmation" class="block w-full rounded-none border border-[var(--portal-border)] bg-[var(--portal-border)] px-4 py-2 text-[var(--portal-text-primary)] placeholder-[var(--portal-text-secondary)] focus:border-blue-500 focus:ring-blue-500"
                type="password" name="password_confirmation" required autocomplete="new-password" />
        </div>

        <button type="submit" style="background-color: var(--portal-primary);" class="w-full hover:opacity-90 text-white font-semibold py-2 px-4 rounded-none transition duration-200">
            <i class="bi bi-check-circle mr-2"></i>{{ __('Set password') }}
        </button>
    </form>
</x-guest-layout>
