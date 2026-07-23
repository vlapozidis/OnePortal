@props(['class' => ''])

@php
    $internalStatusFlags = ['profile-updated', 'password-updated', 'verification-link-sent'];
    $statusMessage = session('status');
    $showStatus = $statusMessage && ! in_array($statusMessage, $internalStatusFlags, true);
@endphp

@if ($showStatus || session('success') || session('error'))
    <div {{ $attributes->merge(['class' => 'space-y-2 '.$class]) }}>
        @if ($showStatus)
            <div
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 5000)"
                class="flex items-center justify-between gap-3 rounded-none border border-green-600/40 bg-green-900/20 px-4 py-3 text-sm text-green-300"
            >
                <span><i class="bi bi-check-circle mr-2"></i>{{ $statusMessage }}</span>
                <button type="button" @click="show = false" class="text-green-300 hover:text-white"><i class="bi bi-x-lg"></i></button>
            </div>
        @endif

        @if (session('success'))
            <div
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 5000)"
                class="flex items-center justify-between gap-3 rounded-none border border-green-600/40 bg-green-900/20 px-4 py-3 text-sm text-green-300"
            >
                <span><i class="bi bi-check-circle mr-2"></i>{{ session('success') }}</span>
                <button type="button" @click="show = false" class="text-green-300 hover:text-white"><i class="bi bi-x-lg"></i></button>
            </div>
        @endif

        @if (session('error'))
            <div
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 5000)"
                class="flex items-center justify-between gap-3 rounded-none border border-red-600/40 bg-red-900/20 px-4 py-3 text-sm text-red-300"
            >
                <span><i class="bi bi-exclamation-circle mr-2"></i>{{ session('error') }}</span>
                <button type="button" @click="show = false" class="text-red-300 hover:text-white"><i class="bi bi-x-lg"></i></button>
            </div>
        @endif
    </div>
@endif
