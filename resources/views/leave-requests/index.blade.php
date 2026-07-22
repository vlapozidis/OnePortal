<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold leading-tight text-white">Leave History</h2>
        <a href="{{ route('leave-requests.create') }}" class="inline-flex items-center rounded-lg bg-[#DC2626] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#B91C1C]">
            New Leave Request
        </a>
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
                            <th class="px-4 py-3 text-left font-medium text-[#A1A1AA]">Department</th>
                            <th class="px-4 py-3 text-left font-medium text-[#A1A1AA]">Start Date</th>
                            <th class="px-4 py-3 text-left font-medium text-[#A1A1AA]">End Date</th>
                            <th class="px-4 py-3 text-left font-medium text-[#A1A1AA]">Reason</th>
                            <th class="px-4 py-3 text-left font-medium text-[#A1A1AA]">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#1F1F1F]">
                        @forelse ($leaveRequests as $leaveRequest)
                            <tr>
                                <td class="px-4 py-3 text-white">{{ $leaveRequest->department?->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-[#A1A1AA]">{{ optional($leaveRequest->start_date)->format('Y-m-d') }}</td>
                                <td class="px-4 py-3 text-[#A1A1AA]">{{ optional($leaveRequest->end_date)->format('Y-m-d') }}</td>
                                <td class="px-4 py-3 text-[#A1A1AA]">{{ $leaveRequest->reason }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $leaveRequest->status === 'Approved' ? 'bg-green-900/40 text-green-300' : ($leaveRequest->status === 'Rejected' ? 'bg-red-900/40 text-red-300' : 'bg-yellow-900/40 text-yellow-300') }}">
                                        {{ $leaveRequest->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-[#A1A1AA]">No leave requests submitted yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-[#1F1F1F] bg-[#0A0A0A] px-4 py-3 text-[#A1A1AA]">
                {{ $leaveRequests->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
