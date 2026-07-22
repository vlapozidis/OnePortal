<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold leading-tight text-white">Departments</h2>
        @if (auth()->user()?->isAdmin())
            <a href="{{ route('departments.create') }}" class="inline-flex items-center rounded-lg bg-[#DC2626] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#B91C1C]">
                Add Department
            </a>
        @endif
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-4">
        @if (session('status'))
            <div class="rounded-xl border border-green-600/40 bg-green-900/20 px-4 py-3 text-sm text-green-300">
                {{ session('status') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-[#1F1F1F] bg-[#111111]">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[#1F1F1F] text-sm">
                    <thead class="bg-[#0A0A0A]">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-[#A1A1AA]">Name</th>
                            <th class="px-4 py-3 text-left font-medium text-[#A1A1AA]">Description</th>
                            @if (auth()->user()?->isAdmin())
                                <th class="px-4 py-3 text-right font-medium text-[#A1A1AA]">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#1F1F1F]">
                        @forelse ($departments as $department)
                            <tr>
                                <td class="px-4 py-3 text-white">{{ $department->name }}</td>
                                <td class="px-4 py-3 text-[#A1A1AA]">{{ $department->description ?: '-' }}</td>
                                @if (auth()->user()?->isAdmin())
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('departments.edit', $department) }}" class="rounded-lg border border-[#1F1F1F] px-3 py-1.5 text-xs font-semibold text-white transition hover:border-[#B91C1C]">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('departments.destroy', $department) }}" onsubmit="return confirm('Delete this department?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-lg border border-red-700/60 px-3 py-1.5 text-xs font-semibold text-red-300 transition hover:bg-red-900/30">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()?->isAdmin() ? 3 : 2 }}" class="px-4 py-6 text-center text-[#A1A1AA]">No departments found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-[#1F1F1F] bg-[#0A0A0A] px-4 py-3 text-[#A1A1AA]">
                {{ $departments->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
