<nav x-data="{ open: false }" class="border-b border-[#1F1F1F] bg-[#111111] lg:min-h-screen lg:w-72 lg:border-b-0 lg:border-r">
    <div class="flex h-16 items-center justify-between px-4 lg:hidden">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 font-semibold tracking-wide text-white">
            <img src="{{ asset('images/logofree.png') }}" alt="Logo" class="h-8 w-auto object-contain">
            <span>Classter Portal</span>
        </a>
        <button @click="open = !open" class="rounded-none border border-[#1F1F1F] p-2 text-[#A1A1AA] hover:text-white">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16" />
                <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 6l12 12M6 18L18 6" />
            </svg>
        </button>
    </div>

    <div :class="open ? 'block' : 'hidden'" class="hidden border-t border-[#1F1F1F] px-3 py-4 lg:block lg:border-t-0 lg:px-4 lg:py-6">
        <a href="{{ route('dashboard') }}" class="hidden flex items-center gap-3 pb-6 text-xl font-semibold tracking-wide text-white lg:flex">
            <img src="{{ asset('images/logofree.png') }}" alt="Logo" class="h-10 w-auto max-w-none object-contain">
            <span>Classter Portal</span>
        </a>

        <div class="mb-6 rounded-none border border-[#1F1F1F] bg-[#0A0A0A] p-3">
            <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
            <p class="text-xs text-[#A1A1AA]">{{ Auth::user()->email }}</p>
        </div>

        <div class="space-y-1">
            <a
                href="{{ route('dashboard') }}"
                class="block rounded-none px-3 py-2 text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-[#DC2626]/20 text-white' : 'text-[#A1A1AA] hover:bg-[#1F1F1F] hover:text-white' }}"
            >
                Dashboard
            </a>

            <a
                href="{{ route('employees.index') }}"
                class="block rounded-none px-3 py-2 text-sm font-medium transition {{ request()->routeIs('employees.*') ? 'bg-[#DC2626]/20 text-white' : 'text-[#A1A1AA] hover:bg-[#1F1F1F] hover:text-white' }}"
            >
                Employees
            </a>

            <a
                href="{{ route('departments.index') }}"
                class="block rounded-none px-3 py-2 text-sm font-medium transition {{ request()->routeIs('departments.*') ? 'bg-[#DC2626]/20 text-white' : 'text-[#A1A1AA] hover:bg-[#1F1F1F] hover:text-white' }}"
            >
                Departments
            </a>

            <a
                href="{{ route('leave-requests.index') }}"
                class="block rounded-none px-3 py-2 text-sm font-medium transition {{ request()->routeIs('leave-requests.*') ? 'bg-[#DC2626]/20 text-white' : 'text-[#A1A1AA] hover:bg-[#1F1F1F] hover:text-white' }}"
            >
                Leave Requests
            </a>

            <a
                href="{{ route('workforce.today') }}"
                class="block rounded-none px-3 py-2 text-sm font-medium transition {{ request()->routeIs('workforce.*') ? 'bg-[#DC2626]/20 text-white' : 'text-[#A1A1AA] hover:bg-[#1F1F1F] hover:text-white' }}"
            >
                Today's Workforce
            </a>

            <a
                href="{{ route('statistics.index') }}"
                class="block rounded-none px-3 py-2 text-sm font-medium transition {{ request()->routeIs('statistics.*') ? 'bg-[#DC2626]/20 text-white' : 'text-[#A1A1AA] hover:bg-[#1F1F1F] hover:text-white' }}"
            >
                Statistics
            </a>

            @if (Auth::user()->isAdmin())
                <a
                    href="{{ route('admin.dashboard') }}"
                    class="block rounded-none px-3 py-2 text-sm font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-[#DC2626]/20 text-white' : 'text-[#A1A1AA] hover:bg-[#1F1F1F] hover:text-white' }}"
                >
                    Admin Dashboard
                </a>

                <a
                    href="{{ route('admin.users.index') }}"
                    class="block rounded-none px-3 py-2 text-sm font-medium transition {{ request()->routeIs('admin.users.*') ? 'bg-[#DC2626]/20 text-white' : 'text-[#A1A1AA] hover:bg-[#1F1F1F] hover:text-white' }}"
                >
                    Manage Users
                </a>
            @endif

            <a
                href="{{ route('profile.edit') }}"
                class="block rounded-none px-3 py-2 text-sm font-medium transition {{ request()->routeIs('profile.*') ? 'bg-[#DC2626]/20 text-white' : 'text-[#A1A1AA] hover:bg-[#1F1F1F] hover:text-white' }}"
            >
                Profile Settings
            </a>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="mt-6 border-t border-[#1F1F1F] pt-4">
            @csrf
            <button type="submit" class="w-full rounded-none border border-[#1F1F1F] px-3 py-2 text-left text-sm font-medium text-[#A1A1AA] transition hover:border-[#B91C1C] hover:text-white">
                Log Out
            </button>
        </form>
    </div>
</nav>
