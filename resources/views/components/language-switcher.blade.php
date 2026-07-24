@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'flex items-center gap-1 '.$class]) }}>
    <i class="bi bi-globe2 mr-1 text-[var(--portal-text-secondary)]"></i>
    @foreach (['en' => 'EN', 'el' => 'ΕΛ'] as $code => $label)
        <form method="POST" action="{{ route('locale.switch', $code) }}">
            @csrf
            @method('PUT')
            <button
                type="submit"
                class="rounded-none border px-2 py-1 text-xs font-semibold transition {{ app()->getLocale() === $code ? 'border-[var(--portal-primary)] bg-[var(--portal-primary)]/20 text-white' : 'border-[var(--portal-border)] text-[var(--portal-text-secondary)] hover:border-[var(--portal-text-secondary)] hover:text-[var(--portal-text-primary)]' }}"
                @if (app()->getLocale() === $code) aria-current="true" @endif
            >
                {{ $label }}
            </button>
        </form>
    @endforeach
</div>
