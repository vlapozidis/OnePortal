<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-white">Portal Login</h1>
        <p class="text-sm text-[#A1A1AA] mt-2">Sign in to access the Portal</p>
    </div>

    <!-- Email/Password Login Form -->
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-[#E4E4E7] mb-2">
                Email
            </label>
            <input id="email" class="block w-full rounded-none border border-[#333333] bg-[#1F1F1F] px-4 py-2 text-white placeholder-[#717171] focus:border-blue-500 focus:ring-blue-500" 
                type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
            @error('email')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-[#E4E4E7] mb-2">
                Password
            </label>
            <input id="password" class="block w-full rounded-none border border-[#333333] bg-[#1F1F1F] px-4 py-2 text-white placeholder-[#717171] focus:border-blue-500 focus:ring-blue-500" 
                type="password" name="password" required autocomplete="current-password" />
            @error('password')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input id="remember_me" type="checkbox" class="rounded-none border border-[#333333] bg-[#1F1F1F] text-blue-600" name="remember">
            <label for="remember_me" class="ml-2 text-sm text-[#A1A1AA]">
                Remember me
            </label>
        </div>

        <!-- Login Button -->
        <button type="submit" style="background-color: #DC2626;" class="w-full hover:opacity-90 text-white font-semibold py-2 px-4 rounded-none transition duration-200">
            Sign In
        </button>
    </form>

    @if (Route::has('auth.microsoft'))
        <div class="mt-6">
            <a href="{{ route('auth.microsoft') }}" class="flex w-full items-center justify-center gap-3 rounded-none border border-[#333333] bg-[#1F1F1F] px-4 py-2 font-semibold text-white transition duration-200 hover:border-[#DC2626] hover:bg-[#2A2A2A]">
                <svg class="h-5 w-5" viewBox="0 0 23 23" fill="currentColor" aria-hidden="true">
                    <rect x="1" y="1" width="9" height="9" fill="currentColor" />
                    <rect x="13" y="1" width="9" height="9" fill="currentColor" />
                    <rect x="1" y="13" width="9" height="9" fill="currentColor" />
                    <rect x="13" y="13" width="9" height="9" fill="currentColor" />
                </svg>
                Sign in with Microsoft
            </a>
        </div>
    @endif

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="mt-4 p-4 bg-red-900 bg-opacity-20 border border-red-500 rounded-none">
            <p class="text-red-400 text-sm font-semibold">Login Error</p>
            @foreach ($errors->all() as $error)
                <p class="text-red-400 text-sm mt-1">{{ $error }}</p>
            @endforeach
        </div>
    @endif
</x-guest-layout>
