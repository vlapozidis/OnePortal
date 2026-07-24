@props(['user'])

@if ($user->isEntraConnected())
    <span
        {{ $attributes->merge(['class' => 'inline-flex items-center gap-1 rounded-none border border-blue-500/40 bg-blue-500/10 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-blue-400']) }}
    >
        <i class="bi bi-microsoft"></i>{{ __('Connected via Entra ID') }}
    </span>
@endif
