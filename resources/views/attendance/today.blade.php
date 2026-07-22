<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold leading-tight text-white">Today's Workforce</h2>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-5">
        @if (session('status'))
            <div class="rounded-xl border border-green-600/40 bg-green-900/20 px-4 py-3 text-sm text-green-300">
                {{ session('status') }}
            </div>
        @endif

        <div class="grid gap-4 sm:grid-cols-3 lg:grid-cols-5">
            @foreach ($summary as $status => $count)
                <div class="rounded-xl border border-[#1F1F1F] bg-[#111111] p-4">
                    <p class="text-xs uppercase tracking-wide text-[#A1A1AA]">{{ $status }}</p>
                    <p class="mt-2 text-2xl font-semibold text-white">{{ $count }}</p>
                </div>
            @endforeach
        </div>

        <div class="rounded-2xl border border-[#1F1F1F] bg-[#111111] p-5">
            <h3 class="text-sm font-semibold text-white">Update My Status ({{ $today }})</h3>
            <p class="mt-1 text-xs text-[#A1A1AA]">
                @if ($myTodayAttendance?->checked_in_at)
                    Checked in today at {{ $myTodayAttendance->checked_in_at->format('H:i') }}.
                @else
                    You haven't checked in today yet.
                @endif
            </p>
            <form method="POST" action="{{ route('workforce.status.update') }}" class="mt-4 flex flex-wrap items-center gap-3">
                @csrf
                @method('PUT')
                <select name="status" class="w-56 rounded-lg border border-[#1F1F1F] bg-[#0A0A0A] px-3 py-2 text-sm text-white focus:border-[#DC2626] focus:ring-[#DC2626]">
                    @foreach ($workStatuses as $status)
                        <option value="{{ $status }}" @selected(old('status', $myTodayAttendance?->status) === $status)>{{ $status }}</option>
                    @endforeach
                </select>
                <button type="submit" class="rounded-lg bg-[#DC2626] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#B91C1C]">
                    Save Status
                </button>
                <button type="submit" formaction="{{ route('workforce.checkin') }}" class="rounded-lg border border-green-700/60 px-4 py-2 text-sm font-semibold text-green-300 transition hover:bg-green-900/30">
                    Check In
                </button>
            </form>
            @error('status')
                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="overflow-hidden rounded-2xl border border-[#1F1F1F] bg-[#111111]">
            <div class="border-b border-[#1F1F1F] px-4 py-3">
                <h3 class="text-sm font-semibold text-white">Tracked Employees Today</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[#1F1F1F] text-sm">
                    <thead class="bg-[#0A0A0A]">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-[#A1A1AA]">Employee</th>
                            <th class="px-4 py-3 text-left font-medium text-[#A1A1AA]">Department</th>
                            <th class="px-4 py-3 text-left font-medium text-[#A1A1AA]">Status</th>
                            <th class="px-4 py-3 text-left font-medium text-[#A1A1AA]">Checked In</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#1F1F1F]">
                        @forelse ($todayRecords as $record)
                            <tr>
                                <td class="px-4 py-3 text-white">{{ $record->user?->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-[#A1A1AA]">{{ $record->user?->department?->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-[#A1A1AA]">{{ $record->status }}</td>
                                <td class="px-4 py-3 text-[#A1A1AA]">{{ $record->checked_in_at?->format('H:i') ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-[#A1A1AA]">No attendance entries for today yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-[#1F1F1F] bg-[#111111]">
            <div class="border-b border-[#1F1F1F] px-4 py-3">
                <h3 class="text-sm font-semibold text-white">Employees Without Entry Today</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[#1F1F1F] text-sm">
                    <thead class="bg-[#0A0A0A]">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-[#A1A1AA]">Employee</th>
                            <th class="px-4 py-3 text-left font-medium text-[#A1A1AA]">Department</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#1F1F1F]">
                        @forelse ($employeesWithoutEntry as $employee)
                            <tr>
                                <td class="px-4 py-3 text-white">{{ $employee->name }}</td>
                                <td class="px-4 py-3 text-[#A1A1AA]">{{ $employee->department?->name ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-4 py-8 text-center text-[#A1A1AA]">All employees have updated status today.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
