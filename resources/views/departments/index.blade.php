<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold leading-tight text-white">Departments</h2>
        @if (auth()->user()?->isAdmin())
            <a href="{{ route('departments.create') }}" class="inline-flex items-center rounded-none bg-[#DC2626] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#B91C1C]">
                Add Department
            </a>
        @endif
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-4">
        @if (session('status'))
            <div class="rounded-none border border-green-600/40 bg-green-900/20 px-4 py-3 text-sm text-green-300">
                {{ session('status') }}
            </div>
        @endif

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($departments as $department)
                <div class="rounded-none border border-[#1F1F1F] bg-[#111111] p-5">
                    <p class="text-base font-semibold text-white">{{ $department->name }}</p>
                    <p class="mt-2 text-sm text-[#A1A1AA]">{{ $department->description ?: 'No description' }}</p>

                    @if (auth()->user()?->isAdmin())
                        <div class="mt-4 flex items-center gap-2">
                            <a href="{{ route('departments.edit', $department) }}" class="rounded-none border border-[#1F1F1F] px-3 py-1.5 text-xs font-semibold text-white transition hover:border-[#B91C1C]">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('departments.destroy', $department) }}" onsubmit="return confirm('Delete this department?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-none border border-red-700/60 px-3 py-1.5 text-xs font-semibold text-red-300 transition hover:bg-red-900/30">
                                    Delete
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @empty
                <div class="col-span-full rounded-none border border-[#1F1F1F] bg-[#111111] p-8 text-center text-[#A1A1AA]">
                    No departments found.
                </div>
            @endforelse
        </div>

        <div class="rounded-none border border-[#1F1F1F] bg-[#111111] px-4 py-3 text-[#A1A1AA]">
            {{ $departments->links() }}
        </div>
    </div>
</x-app-layout>
