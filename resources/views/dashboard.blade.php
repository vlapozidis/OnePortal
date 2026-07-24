<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold leading-tight text-[var(--portal-text-primary)]">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="mx-auto max-w-7xl">
        <div class="rounded-none border border-[var(--portal-border)] bg-[var(--portal-surface)] p-6 shadow-lg shadow-black/20">
            <div class="flex items-center gap-4">
                <x-user-avatar :user="Auth::user()" size="lg" />
                <div>
                    <h3 class="text-lg font-semibold text-[var(--portal-text-primary)]">{{ __('Welcome back, :name.', ['name' => Auth::user()->name]) }}</h3>
                    <p class="mt-2 text-sm text-[var(--portal-text-secondary)]">
                        {{ __('Internal employee snapshot with live profile and activity metrics.') }}
                    </p>
                    @if (Auth::user()->isEntraConnected())
                        <x-entra-badge :user="Auth::user()" class="mt-2" />
                    @endif
                </div>
            </div>

            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($dashboardCards as $card)
                    <div class="rounded-none border border-[var(--portal-border)] bg-[var(--portal-bg)] p-5 transition hover:border-[var(--portal-primary-hover)]">
                        <p class="text-xs uppercase tracking-wide text-[var(--portal-text-secondary)]">{{ $card['label'] }}</p>
                        <p class="mt-3 text-2xl font-semibold text-[var(--portal-text-primary)]">{{ $card['value'] }}</p>
                        <p class="mt-2 text-xs text-[var(--portal-text-secondary)]">{{ $card['note'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-6 grid items-start gap-4 lg:grid-cols-[auto_1fr]">
            <div class="flex w-full flex-col items-start gap-3 self-start rounded-none border border-[var(--portal-border)] bg-[var(--portal-surface)] p-6 lg:w-64">
                <h3 class="text-sm font-semibold text-[var(--portal-text-primary)]">{{ __('Check In') }}</h3>

                @if ($onLeaveToday)
                    <p class="text-sm text-[var(--portal-primary)]"><i class="bi bi-airplane-fill mr-1"></i>{{ __('You are on approved leave today.') }}</p>
                @elseif (! $checkedInToday)
                    <form method="POST" action="{{ route('workforce.checkin') }}">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="rounded-none bg-[var(--portal-primary)] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[var(--portal-primary-hover)]">
                            <i class="bi bi-check2-square mr-2"></i>{{ __('Check In') }}
                        </button>
                    </form>
                @else
                    <p class="text-sm text-green-300"><i class="bi bi-check-circle-fill mr-1"></i>{{ __('You have checked in today.') }}</p>

                    @if ($checkedOutToday)
                        <p class="text-sm text-green-300">
                            <i class="bi bi-check-circle-fill mr-1"></i>
                            {{ __('Checked out at :time.', ['time' => $checkedOutAt->format('H:i')]) }}
                        </p>
                    @else
                        <form method="POST" action="{{ route('workforce.checkout') }}">
                            @csrf
                            @method('PUT')
                            <button
                                type="submit"
                                @disabled(! $canCheckOut)
                                class="rounded-none bg-[var(--portal-primary)] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[var(--portal-primary-hover)] disabled:cursor-not-allowed disabled:opacity-40 disabled:hover:bg-[var(--portal-primary)]"
                            >
                                <i class="bi bi-box-arrow-right mr-2"></i>{{ __('Check Out') }}
                            </button>
                        </form>

                        @unless ($canCheckOut)
                            <p class="text-xs text-[var(--portal-text-secondary)]">{{ __('Available from :hour:00.', ['hour' => \App\Models\Attendance::CHECK_OUT_AVAILABLE_FROM_HOUR]) }}</p>
                        @endunless
                    @endif
                @endif
            </div>

            <x-leave-calendar
                :month-label="$calendarMonthLabel"
                :days-in-month="$calendarDaysInMonth"
                :leading-blanks="$calendarLeadingBlanks"
                :month-start="$calendarMonthStart"
                :prev-month-url="$calendarPrevMonthUrl"
                :next-month-url="$calendarNextMonthUrl"
                :leave-day-statuses="$leaveDayStatuses"
                :checked-in-days="$checkedInDays"
            />
        </div>
    </div>
</x-app-layout>
