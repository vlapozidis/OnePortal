@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'flex items-center gap-1 '.$class]) }}>
    <i class="bi bi-globe2 mr-1 text-[#A1A1AA]"></i>
    @foreach (['en' => 'EN', 'el' => 'ΕΛ'] as $code => $label)
        <form method="POST" action="{{ route('locale.switch', $code) }}">
            @csrf
            @method('PUT')
            <button
                type="submit"
                class="rounded-none border px-2 py-1 text-xs font-semibold transition {{ app()->getLocale() === $code ? 'border-[#DC2626] bg-[#DC2626]/20 text-white' : 'border-[#1F1F1F] text-[#A1A1AA] hover:border-[#333333] hover:text-white' }}"
                @if (app()->getLocale() === $code) aria-current="true" @endif
            >
                {{ $label }}
            </button>
        </form>
    @endforeach
</div>
