<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold leading-tight text-white">{{ __('Leave History') }}</h2>
        <a href="{{ route('leave-requests.create') }}" class="inline-flex items-center rounded-none bg-[#DC2626] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#B91C1C]">
            <i class="bi bi-plus-lg mr-2"></i>{{ __('New Leave Request') }}
        </a>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-4">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($leaveRequests as $leaveRequest)
                <div class="rounded-none border border-[#1F1F1F] bg-[#111111] p-5">
                    <div class="flex items-start justify-between gap-2">
                        <p class="text-base font-semibold text-white">{{ $leaveRequest->department?->name ?? __('No department') }}</p>
                        <span class="inline-flex shrink-0 rounded-none px-3 py-1 text-xs font-semibold {{ $leaveRequest->status === 'Approved' ? 'bg-green-900/40 text-green-300' : ($leaveRequest->status === 'Rejected' ? 'bg-red-900/40 text-red-300' : 'bg-yellow-900/40 text-yellow-300') }}">
                            {{ __($leaveRequest->status) }}
                        </span>
                    </div>

                    <p class="mt-3 text-sm text-[#A1A1AA]">
                        {{ optional($leaveRequest->start_date)->format('Y-m-d') }} &rarr; {{ optional($leaveRequest->end_date)->format('Y-m-d') }}
                    </p>

                    <p class="mt-3 text-sm text-white">{{ $leaveRequest->reason }}</p>
                </div>
            @empty
                <div class="col-span-full rounded-none border border-[#1F1F1F] bg-[#111111] p-8 text-center text-[#A1A1AA]">
                    {{ __('No leave requests submitted yet.') }}
                </div>
            @endforelse
        </div>

        <div class="rounded-none border border-[#1F1F1F] bg-[#111111] px-4 py-3 text-[#A1A1AA]">
            {{ $leaveRequests->links() }}
        </div>
    </div>
</x-app-layout>
