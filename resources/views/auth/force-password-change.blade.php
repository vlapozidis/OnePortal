<x-guest-layout>
    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-white">{{ __('Set a new password') }}</h1>
        <p class="text-sm text-[#A1A1AA] mt-2">{{ __('Your password was reset by an administrator. Choose a new password to continue.') }}</p>
    </div>

    <form method="POST" action="{{ route('password.force-change.update') }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="password" class="block text-sm font-medium text-[#E4E4E7] mb-2">
                {{ __('New password') }}
            </label>
            <input id="password" class="block w-full rounded-none border border-[#333333] bg-[#1F1F1F] px-4 py-2 text-white placeholder-[#717171] focus:border-blue-500 focus:ring-blue-500"
                type="password" name="password" required autocomplete="new-password" autofocus />
            @error('password')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-[#E4E4E7] mb-2">
                {{ __('Confirm new password') }}
            </label>
            <input id="password_confirmation" class="block w-full rounded-none border border-[#333333] bg-[#1F1F1F] px-4 py-2 text-white placeholder-[#717171] focus:border-blue-500 focus:ring-blue-500"
                type="password" name="password_confirmation" required autocomplete="new-password" />
        </div>

        <button type="submit" style="background-color: #DC2626;" class="w-full hover:opacity-90 text-white font-semibold py-2 px-4 rounded-none transition duration-200">
            <i class="bi bi-check-circle mr-2"></i>{{ __('Set password') }}
        </button>
    </form>
</x-guest-layout>
