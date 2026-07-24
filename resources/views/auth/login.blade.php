<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    @if (session('success'))
        <div
            x-data="{ show: true }"
            x-show="show"
            x-transition
            x-init="setTimeout(() => show = false, 3000)"
            class="mb-4 flex items-center justify-between gap-3 text-sm font-medium text-green-400"
        >
            <span><i class="bi bi-check-circle mr-1"></i>{{ session('success') }}</span>
            <button type="button" @click="show = false" class="text-green-400 hover:text-green-200"><i class="bi bi-x-lg"></i></button>
        </div>
    @endif

    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-[var(--portal-text-primary)]">{{ __('Portal Login') }}</h1>
        <p class="text-sm text-[var(--portal-text-secondary)] mt-2">{{ __('Sign in to access the Portal') }}</p>
    </div>

    <!-- Email/Password Login Form -->
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-[var(--portal-text-secondary)] mb-2">
                {{ __('Email') }}
            </label>
            <input id="email" class="block w-full rounded-none border border-[var(--portal-border)] bg-[var(--portal-border)] px-4 py-2 text-[var(--portal-text-primary)] placeholder-[var(--portal-text-secondary)] focus:border-blue-500 focus:ring-blue-500"
                type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
            @error('email')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-[var(--portal-text-secondary)] mb-2">
                {{ __('Password') }}
            </label>
            <input id="password" class="block w-full rounded-none border border-[var(--portal-border)] bg-[var(--portal-border)] px-4 py-2 text-[var(--portal-text-primary)] placeholder-[var(--portal-text-secondary)] focus:border-blue-500 focus:ring-blue-500"
                type="password" name="password" required autocomplete="current-password" />
            @error('password')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input id="remember_me" type="checkbox" class="rounded-none border border-[var(--portal-border)] bg-[var(--portal-border)] text-blue-600" name="remember">
            <label for="remember_me" class="ml-2 text-sm text-[var(--portal-text-secondary)]">
                {{ __('Remember me') }}
            </label>
        </div>

        <!-- Login Button -->
        <button type="submit" style="background-color: var(--portal-primary);" class="w-full hover:opacity-90 text-white font-semibold py-2 px-4 rounded-none transition duration-200">
            <i class="bi bi-box-arrow-in-right mr-2"></i>{{ __('Sign In') }}
        </button>
    </form>

    @if (Route::has('auth.microsoft'))
        <div class="mt-6">
            <a href="{{ route('auth.microsoft') }}" class="flex w-full items-center justify-center gap-3 rounded-none border border-[var(--portal-border)] bg-[var(--portal-border)] px-4 py-2 font-semibold text-[var(--portal-text-primary)] transition duration-200 hover:border-[var(--portal-primary)] hover:bg-[var(--portal-bg)]">
                <svg class="h-5 w-5" viewBox="0 0 23 23" fill="currentColor" aria-hidden="true">
                    <rect x="1" y="1" width="9" height="9" fill="currentColor" />
                    <rect x="13" y="1" width="9" height="9" fill="currentColor" />
                    <rect x="1" y="13" width="9" height="9" fill="currentColor" />
                    <rect x="13" y="13" width="9" height="9" fill="currentColor" />
                </svg>
                {{ __('Sign in with Microsoft') }}
            </a>
        </div>
    @endif

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="mt-4 p-4 bg-red-900 bg-opacity-20 border border-red-500 rounded-none">
            <p class="text-red-400 text-sm font-semibold">{{ __('Login Error') }}</p>
            @foreach ($errors->all() as $error)
                <p class="text-red-400 text-sm mt-1">{{ $error }}</p>
            @endforeach
        </div>
    @endif
</x-guest-layout>
