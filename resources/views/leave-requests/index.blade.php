<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold leading-tight text-[var(--portal-text-primary)]">{{ __('Leave History') }}</h2>
        <a href="{{ route('leave-requests.create') }}" class="inline-flex items-center rounded-none bg-[var(--portal-primary)] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[var(--portal-primary-hover)]">
            <i class="bi bi-plus-lg mr-2"></i>{{ __('New Leave Request') }}
        </a>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-4">
        <x-leave-calendar
            :month-label="$calendarMonthLabel"
            :days-in-month="$calendarDaysInMonth"
            :leading-blanks="$calendarLeadingBlanks"
            :month-start="$calendarMonthStart"
            :prev-month-url="$calendarPrevMonthUrl"
            :next-month-url="$calendarNextMonthUrl"
            :leave-day-statuses="$leaveDayStatuses"
        />

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($leaveRequests as $leaveRequest)
                <div class="rounded-none border border-[var(--portal-border)] bg-[var(--portal-surface)] p-5">
                    <div class="flex items-start justify-between gap-2">
                        <p class="text-base font-semibold text-[var(--portal-text-primary)]">{{ $leaveRequest->department?->name ?? __('No department') }}</p>
                        <span class="inline-flex shrink-0 rounded-none px-3 py-1 text-xs font-semibold {{ $leaveRequest->status === 'Approved' ? 'bg-green-900/40 text-green-300' : ($leaveRequest->status === 'Rejected' ? 'bg-red-900/40 text-red-300' : 'bg-yellow-900/40 text-yellow-300') }}">
                            {{ __($leaveRequest->status) }}
                        </span>
                    </div>

                    <p class="mt-3 text-sm text-[var(--portal-text-secondary)]">
                        {{ optional($leaveRequest->start_date)->format('Y-m-d') }} &rarr; {{ optional($leaveRequest->end_date)->format('Y-m-d') }}
                    </p>

                    <p class="mt-3 text-sm text-[var(--portal-text-primary)]">{{ $leaveRequest->reason }}</p>
                </div>
            @empty
                <div class="col-span-full rounded-none border border-[var(--portal-border)] bg-[var(--portal-surface)] p-8 text-center text-[var(--portal-text-secondary)]">
                    {{ __('No leave requests submitted yet.') }}
                </div>
            @endforelse
        </div>

        <div class="rounded-none border border-[var(--portal-border)] bg-[var(--portal-surface)] px-4 py-3 text-[var(--portal-text-secondary)]">
            {{ $leaveRequests->links() }}
        </div>
    </div>
</x-app-layout>
