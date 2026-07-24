<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold leading-tight text-[var(--portal-text-primary)]">{{ __("Today's Workforce") }}</h2>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-5">
        <div class="grid gap-4 sm:grid-cols-3 lg:grid-cols-5">
            @foreach ($summary as $status => $count)
                <div class="rounded-none border border-[var(--portal-border)] bg-[var(--portal-surface)] p-4">
                    <p class="text-xs uppercase tracking-wide text-[var(--portal-text-secondary)]">{{ __($status) }}</p>
                    <p class="mt-2 text-2xl font-semibold text-[var(--portal-text-primary)]">{{ $count }}</p>
                </div>
            @endforeach
        </div>

        <div class="rounded-none border border-[var(--portal-border)] bg-[var(--portal-surface)] p-5">
            <h3 class="text-sm font-semibold text-[var(--portal-text-primary)]">{{ __('My Status') }} ({{ $today }})</h3>
            <p class="mt-1 text-xs text-[var(--portal-text-secondary)]">
                @if ($myTodayAttendance?->checked_in_at)
                    {{ __('Checked in today at :time.', ['time' => $myTodayAttendance->checked_in_at->format('H:i')]) }}
                @else
                    {{ __("You haven't checked in today yet.") }}
                @endif
            </p>
            <p class="mt-2 text-xs text-[var(--portal-text-secondary)]">
                <i class="bi bi-info-circle mr-1"></i>
                {{ __('Check in and manage your work mode from') }}
                <a href="{{ route('profile.edit') }}" class="underline hover:text-[var(--portal-text-primary)]">{{ __('Settings') }}</a>.
            </p>
        </div>

        <div class="space-y-3">
            <h3 class="text-sm font-semibold text-[var(--portal-text-primary)]">{{ __('Tracked Employees Today') }}</h3>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($todayRecords as $record)
                    <div class="rounded-none border border-[var(--portal-border)] bg-[var(--portal-surface)] p-5">
                        <div class="flex items-center gap-3">
                            @if ($record->user)
                                <x-user-avatar :user="$record->user" size="sm" />
                            @endif
                            <div class="min-w-0">
                                <p class="truncate text-base font-semibold text-[var(--portal-text-primary)]">{{ $record->user?->name ?? __('Unknown') }}</p>
                                <p class="truncate text-sm text-[var(--portal-text-secondary)]">{{ $record->user?->department?->name ?? __('No department') }}</p>
                            </div>
                        </div>
                        <div class="mt-3 flex flex-wrap gap-2 text-xs">
                            <span class="rounded-none border border-[var(--portal-border)] px-3 py-1 text-[var(--portal-text-secondary)]">{{ __($record->status) }}</span>
                            <span class="rounded-none border border-[var(--portal-border)] px-3 py-1 text-[var(--portal-text-secondary)]">
                                {{ $record->checked_in_at?->format('H:i') ? __('Checked in :time', ['time' => $record->checked_in_at->format('H:i')]) : __('Not checked in') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full rounded-none border border-[var(--portal-border)] bg-[var(--portal-surface)] p-8 text-center text-[var(--portal-text-secondary)]">
                        {{ __('No attendance entries for today yet.') }}
                    </div>
                @endforelse
            </div>
        </div>

        <div class="space-y-3">
            <h3 class="text-sm font-semibold text-[var(--portal-text-primary)]">{{ __('Employees Without Entry Today') }}</h3>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($employeesWithoutEntry as $employee)
                    <div class="rounded-none border border-[var(--portal-border)] bg-[var(--portal-surface)] p-5">
                        <div class="flex items-center gap-3">
                            <x-user-avatar :user="$employee" size="sm" />
                            <div class="min-w-0">
                                <p class="truncate text-base font-semibold text-[var(--portal-text-primary)]">{{ $employee->name }}</p>
                                <p class="truncate text-sm text-[var(--portal-text-secondary)]">{{ $employee->department?->name ?? __('No department') }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full rounded-none border border-[var(--portal-border)] bg-[var(--portal-surface)] p-8 text-center text-[var(--portal-text-secondary)]">
                        {{ __('All employees have updated status today.') }}
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
