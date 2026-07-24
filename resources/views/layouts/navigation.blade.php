<nav x-data="{ open: false }" class="border-b border-[var(--portal-border)] bg-[var(--portal-surface)] lg:min-h-screen lg:w-72 lg:border-b-0 lg:border-r">
    <div class="flex h-16 items-center justify-between px-4 lg:hidden">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 font-semibold tracking-wide text-[var(--portal-text-primary)]">
            <img src="{{ asset('images/logofree.png') }}" alt="Logo" class="h-8 w-auto object-contain">
            <span>OnePortal</span>
        </a>
        <button @click="open = !open" class="rounded-none border border-[var(--portal-border)] p-2 text-[var(--portal-text-secondary)] hover:text-[var(--portal-text-primary)]">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16" />
                <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 6l12 12M6 18L18 6" />
            </svg>
        </button>
    </div>

    <div :class="open ? 'block' : 'hidden'" class="hidden border-t border-[var(--portal-border)] px-3 py-4 lg:block lg:border-t-0 lg:px-4 lg:py-6">
        <a href="{{ route('dashboard') }}" class="hidden flex items-center gap-3 pb-6 text-xl font-semibold tracking-wide text-[var(--portal-text-primary)] lg:flex">
            <img src="{{ asset('images/logofree.png') }}" alt="Logo" class="h-10 w-auto max-w-none object-contain">
            <span>OnePortal</span>
        </a>

        <a href="{{ route('profile.edit') }}" class="mb-6 flex items-center gap-3 rounded-none border border-[var(--portal-border)] bg-[var(--portal-bg)] p-3 transition hover:border-[var(--portal-primary)]">
            <x-user-avatar :user="Auth::user()" size="md" />
            <div class="min-w-0">
                <p class="truncate text-sm font-medium text-[var(--portal-text-primary)]">{{ Auth::user()->name }}</p>
                <p class="truncate text-xs text-[var(--portal-text-secondary)]">{{ Auth::user()->email }}</p>
                @if (Auth::user()->isEntraConnected())
                    <x-entra-badge :user="Auth::user()" class="mt-1" />
                @endif
            </div>
        </a>

        <x-language-switcher class="mb-6" />

        <div class="space-y-1">
            <a
                href="{{ route('dashboard') }}"
                class="block rounded-none px-3 py-2 text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-[var(--portal-primary)]/20 text-[var(--portal-text-primary)]' : 'text-[var(--portal-text-secondary)] hover:bg-[var(--portal-border)] hover:text-[var(--portal-text-primary)]' }}"
            >
                <i class="bi bi-speedometer2 mr-2"></i>{{ __('Dashboard') }}
            </a>

            <a
                href="{{ route('employees.index') }}"
                class="block rounded-none px-3 py-2 text-sm font-medium transition {{ request()->routeIs('employees.*') ? 'bg-[var(--portal-primary)]/20 text-[var(--portal-text-primary)]' : 'text-[var(--portal-text-secondary)] hover:bg-[var(--portal-border)] hover:text-[var(--portal-text-primary)]' }}"
            >
                <i class="bi bi-people mr-2"></i>{{ __('Employees') }}
            </a>

            <a
                href="{{ route('departments.index') }}"
                class="block rounded-none px-3 py-2 text-sm font-medium transition {{ request()->routeIs('departments.*') ? 'bg-[var(--portal-primary)]/20 text-[var(--portal-text-primary)]' : 'text-[var(--portal-text-secondary)] hover:bg-[var(--portal-border)] hover:text-[var(--portal-text-primary)]' }}"
            >
                <i class="bi bi-diagram-3 mr-2"></i>{{ __('Departments') }}
            </a>

            <a
                href="{{ route('leave-requests.index') }}"
                class="block rounded-none px-3 py-2 text-sm font-medium transition {{ request()->routeIs('leave-requests.*') ? 'bg-[var(--portal-primary)]/20 text-[var(--portal-text-primary)]' : 'text-[var(--portal-text-secondary)] hover:bg-[var(--portal-border)] hover:text-[var(--portal-text-primary)]' }}"
            >
                <i class="bi bi-calendar2-check mr-2"></i>{{ __('Leave Requests') }}
            </a>

            <a
                href="{{ route('workforce.today') }}"
                class="block rounded-none px-3 py-2 text-sm font-medium transition {{ request()->routeIs('workforce.*') ? 'bg-[var(--portal-primary)]/20 text-[var(--portal-text-primary)]' : 'text-[var(--portal-text-secondary)] hover:bg-[var(--portal-border)] hover:text-[var(--portal-text-primary)]' }}"
            >
                <i class="bi bi-clock-history mr-2"></i>{{ __("Today's Workforce") }}
            </a>

            <a
                href="{{ route('statistics.index') }}"
                class="block rounded-none px-3 py-2 text-sm font-medium transition {{ request()->routeIs('statistics.*') ? 'bg-[var(--portal-primary)]/20 text-[var(--portal-text-primary)]' : 'text-[var(--portal-text-secondary)] hover:bg-[var(--portal-border)] hover:text-[var(--portal-text-primary)]' }}"
            >
                <i class="bi bi-bar-chart mr-2"></i>{{ __('Statistics') }}
            </a>

            @if (Auth::user()->isAdmin())
                <a
                    href="{{ url('/control-panel') }}"
                    target="_blank"
                    rel="noopener"
                    class="block rounded-none px-3 py-2 text-sm font-medium text-[var(--portal-text-secondary)] transition hover:bg-[var(--portal-border)] hover:text-[var(--portal-text-primary)]"
                >
                    <i class="bi bi-box-arrow-up-right mr-2"></i>{{ __('Control Panel') }}
                </a>
            @endif

            <a
                href="{{ route('profile.edit') }}"
                class="block rounded-none px-3 py-2 text-sm font-medium transition {{ request()->routeIs('profile.*') ? 'bg-[var(--portal-primary)]/20 text-[var(--portal-text-primary)]' : 'text-[var(--portal-text-secondary)] hover:bg-[var(--portal-border)] hover:text-[var(--portal-text-primary)]' }}"
            >
                <i class="bi bi-gear mr-2"></i>{{ __('Settings') }}
            </a>
        </div>

        <div
            x-data="{ dark: document.documentElement.classList.contains('dark') }"
            class="mt-6 border-t border-[var(--portal-border)] pt-4"
        >
            <button
                type="button"
                @click="
                    dark = !dark;
                    document.documentElement.classList.toggle('dark', dark);
                    fetch('{{ route('theme.update') }}', {
                        method: 'PATCH',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: new URLSearchParams({ theme: dark ? 'dark' : 'light' }),
                    });
                "
                class="flex w-full items-center justify-between rounded-none border border-[var(--portal-border)] px-3 py-2 text-sm font-medium text-[var(--portal-text-secondary)] transition hover:text-[var(--portal-text-primary)]"
            >
                <span x-text="dark ? '{{ __('Dark Mode') }}' : '{{ __('Light Mode') }}'"></span>
                <i class="bi" :class="dark ? 'bi-moon-stars' : 'bi-sun'"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button type="submit" class="w-full rounded-none border border-[var(--portal-border)] px-3 py-2 text-left text-sm font-medium text-[var(--portal-text-secondary)] transition hover:border-[var(--portal-primary-hover)] hover:text-[var(--portal-text-primary)]">
                <i class="bi bi-box-arrow-right mr-2"></i>{{ __('Log Out') }}
            </button>
        </form>
    </div>
</nav>
