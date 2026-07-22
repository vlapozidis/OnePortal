<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold leading-tight text-white">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="mx-auto max-w-7xl">
        <div class="rounded-none border border-[#1F1F1F] bg-[#111111] p-6 shadow-lg shadow-black/20">
            <h3 class="text-lg font-semibold text-white">Welcome back, {{ Auth::user()->name }}.</h3>
            <p class="mt-2 text-sm text-[#A1A1AA]">
                Internal employee snapshot with live profile and activity metrics.
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
    </div>
</x-app-layout>
