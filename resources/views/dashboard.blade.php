<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold leading-tight text-white">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="mx-auto max-w-7xl">
        <div class="rounded-none border border-[#1F1F1F] bg-[#111111] p-6 shadow-lg shadow-black/20">
            <h3 class="text-lg font-semibold text-white">{{ __('Welcome back, :name.', ['name' => Auth::user()->name]) }}</h3>
            <p class="mt-2 text-sm text-[#A1A1AA]">
                {{ __('Internal employee snapshot with live profile and activity metrics.') }}
            </p>

            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($dashboardCards as $card)
                    <div class="rounded-none border border-[#1F1F1F] bg-[#0A0A0A] p-5 transition hover:border-[#B91C1C]">
                        <p class="text-xs uppercase tracking-wide text-[#A1A1AA]">{{ $card['label'] }}</p>
                        <p class="mt-3 text-2xl font-semibold text-white">{{ $card['value'] }}</p>
                        <p class="mt-2 text-xs text-[#A1A1AA]">{{ $card['note'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-6 grid items-start gap-4 lg:grid-cols-[auto_1fr]">
            <div class="flex w-full flex-col items-start gap-3 self-start rounded-none border border-[#1F1F1F] bg-[#111111] p-6 lg:w-64">
                <h3 class="text-sm font-semibold text-white">{{ __('Check In') }}</h3>

                @if ($checkedInToday)
                    <p class="text-sm text-green-300"><i class="bi bi-check-circle-fill mr-1"></i>{{ __('You have checked in today.') }}</p>
                @else
                    <form method="POST" action="{{ route('workforce.checkin') }}">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="rounded-none bg-[#DC2626] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#B91C1C]">
                            <i class="bi bi-check2-square mr-2"></i>{{ __('Check In') }}
                        </button>
                    </form>
                @endif
            </div>

            <div class="rounded-none border border-[#1F1F1F] bg-[#111111] p-6">
                <h3 class="mb-3 text-sm font-semibold text-white">
                    <i class="bi bi-calendar3 mr-2"></i>{{ $calendarMonthLabel }}
                </h3>

                <div class="grid grid-cols-7 gap-1 text-center text-xs">
                    @foreach ([__('Mon'), __('Tue'), __('Wed'), __('Thu'), __('Fri'), __('Sat'), __('Sun')] as $dayLabel)
                        <div class="py-1 font-semibold uppercase text-[#71717A]">{{ $dayLabel }}</div>
                    @endforeach

                    @for ($i = 0; $i < $calendarLeadingBlanks; $i++)
                        <div></div>
                    @endfor

                    @for ($day = 1; $day <= $calendarDaysInMonth; $day++)
                        <div
                            class="flex aspect-square items-center justify-center rounded-none border text-sm {{ $day === $calendarToday ? 'border-[#DC2626] text-white' : 'border-[#1F1F1F] text-[#A1A1AA]' }}"
                        >
                            <span class="relative">
                                {{ $day }}
                                @if (in_array($day, $checkedInDays, true))
                                    <span class="absolute -right-2 -top-2 text-xs font-bold text-green-400">X</span>
                                @endif
                            </span>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
