<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center rounded-none border border-[#1F1F1F] bg-[#111111] px-4 py-2 text-xs font-semibold text-white transition hover:border-[#DC2626] hover:bg-[#0A0A0A] focus:outline-none focus:ring-2 focus:ring-[#DC2626] focus:ring-offset-2 focus:ring-offset-[#111111] disabled:opacity-60']) }}>
    {{ $slot }}
</button>
