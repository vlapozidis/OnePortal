@props([
    'monthLabel',
    'daysInMonth',
    'leadingBlanks',
    'monthStart',
    'leaveDayStatuses' => [],
    'checkedInDays' => [],
    'prevMonthUrl' => null,
    'nextMonthUrl' => null,
])

@php $today = \Illuminate\Support\Carbon::today(); @endphp

<div
    x-data="{
        start: JSON.parse(localStorage.getItem('onePortalLeaveSelection') || 'null')?.start ?? null,
        end: JSON.parse(localStorage.getItem('onePortalLeaveSelection') || 'null')?.end ?? null,
        persist() {
            localStorage.setItem('onePortalLeaveSelection', JSON.stringify({ start: this.start, end: this.end }));
        },
        select(date) {
            if (! this.start || this.end) {
                this.start = date;
                this.end = null;
            } else if (date === this.start) {
                this.end = date;
            } else if (date < this.start) {
                this.end = this.start;
                this.start = date;
            } else {
                this.end = date;
            }
            this.persist();
        },
        clear() {
            this.start = null;
            this.end = null;
            this.persist();
        },
        inRange(date) {
            if (! this.start) return false;
            if (! this.end) return date === this.start;
            return date >= this.start && date <= this.end;
        },
        confirm() {
            if (! this.start) return;
            const start = this.start;
            const end = this.end ?? this.start;
            this.clear();
            window.location.href = '{{ route('leave-requests.create') }}?start_date=' + start + '&end_date=' + end;
        },
    }"
    {{ $attributes->merge(['class' => 'rounded-none border border-[var(--portal-border)] bg-[var(--portal-surface)] p-6']) }}
>
    <div class="mb-3 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-[var(--portal-text-primary)]">
            <i class="bi bi-calendar3 mr-2"></i>{{ $monthLabel }}
        </h3>

        <div class="flex items-center gap-1">
            <a
                href="{{ $prevMonthUrl }}"
                title="{{ __('Previous month') }}"
                class="flex h-7 w-7 items-center justify-center rounded-none border border-[var(--portal-border)] text-[var(--portal-text-secondary)] transition hover:border-[var(--portal-primary)] hover:text-[var(--portal-text-primary)]"
            >
                <i class="bi bi-chevron-left"></i>
            </a>
            <a
                href="{{ $nextMonthUrl }}"
                title="{{ __('Next month') }}"
                class="flex h-7 w-7 items-center justify-center rounded-none border border-[var(--portal-border)] text-[var(--portal-text-secondary)] transition hover:border-[var(--portal-primary)] hover:text-[var(--portal-text-primary)]"
            >
                <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    </div>

    <div class="mb-2 flex min-h-[2rem] flex-wrap items-center justify-between gap-2 text-xs text-[var(--portal-text-secondary)]">
        <span>
            <template x-if="start">
                <span>
                    {{ __('Selected') }}:
                    <span class="font-semibold text-[var(--portal-text-primary)]" x-text="end ? (start + ' → ' + end) : start"></span>
                    <button type="button" class="ml-1 underline hover:text-[var(--portal-text-primary)]" @click="clear()">{{ __('Clear') }}</button>
                </span>
            </template>
            <template x-if="! start">
                <span>{{ __('Click the dates you want off. Selections stay when you switch months.') }}</span>
            </template>
        </span>

        <button
            type="button"
            x-show="start"
            x-cloak
            @click="confirm()"
            class="shrink-0 rounded-none bg-[var(--portal-primary)] px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-[var(--portal-primary-hover)]"
        >
            <i class="bi bi-send-check mr-1"></i>{{ __('Request Leave for Selected Dates') }}
        </button>
    </div>

    <div class="grid grid-cols-7 gap-1 text-center text-xs">
        @foreach ([__('Mon'), __('Tue'), __('Wed'), __('Thu'), __('Fri'), __('Sat'), __('Sun')] as $dayLabel)
            <div class="py-1 font-semibold uppercase text-[var(--portal-text-secondary)]">{{ $dayLabel }}</div>
        @endforeach

        @for ($i = 0; $i < $leadingBlanks; $i++)
            <div></div>
        @endfor

        @php $leaveStatusLabels = ['Approved' => __('On Leave'), 'Pending' => __('Pending review'), 'Rejected' => __('Rejected')]; @endphp

        @for ($day = 1; $day <= $daysInMonth; $day++)
            @php
                $cellDate = $monthStart->copy()->addDays($day - 1);
                $cellDateString = $cellDate->toDateString();
                $isPastDay = $cellDate->lt($today);
                $isToday = $cellDate->isSameDay($today);
                $leaveStatus = $leaveDayStatuses[$day] ?? null;
                $isSelectable = ! $isPastDay && ! $leaveStatus;
                $cellClasses = match (true) {
                    $leaveStatus === 'Approved' => 'border-[var(--portal-primary)] bg-[var(--portal-primary)]/10 text-[var(--portal-text-primary)]',
                    $isToday => 'border-[var(--portal-primary)] text-[var(--portal-text-primary)]',
                    default => 'border-[var(--portal-border)] text-[var(--portal-text-secondary)]',
                };
            @endphp

            @if ($isSelectable)
                <button
                    type="button"
                    @click="select('{{ $cellDateString }}')"
                    :class="inRange('{{ $cellDateString }}') ? 'border-[var(--portal-primary)] bg-[var(--portal-primary)]/20' : '{{ $cellClasses }}'"
                    title="{{ __('Select this day for leave') }}"
                    class="flex aspect-square w-full flex-col items-center justify-center rounded-none border text-sm transition hover:border-[var(--portal-primary)]"
                >
            @else
                <div
                    title="{{ $leaveStatus ? $leaveStatusLabels[$leaveStatus] : '' }}"
                    class="flex aspect-square flex-col items-center justify-center rounded-none border text-sm {{ $cellClasses }}"
                >
            @endif
                    <span class="relative">
                        {{ $day }}
                        @if ($leaveStatus === 'Pending')
                            <span class="absolute -right-3 -top-2 text-xs font-bold text-yellow-400">?</span>
                        @elseif ($leaveStatus === 'Rejected')
                            <span class="absolute -right-3 -top-2 text-xs font-bold text-red-400">X</span>
                        @elseif (! $leaveStatus && in_array($day, $checkedInDays, true))
                            <span class="absolute -right-3 -top-2 text-xs font-bold text-green-400">X</span>
                        @endif
                    </span>
                    @if ($leaveStatus === 'Approved')
                        <span class="block text-[8px] font-bold uppercase leading-none text-[var(--portal-primary)]">{{ __('On Leave') }}</span>
                    @endif
            @if ($isSelectable)
                </button>
            @else
                </div>
            @endif
        @endfor
    </div>
</div>
