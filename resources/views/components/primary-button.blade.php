<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center rounded-none border border-transparent bg-[#DC2626] px-4 py-2 text-xs font-semibold text-white transition hover:bg-[#B91C1C] focus:bg-[#B91C1C] focus:outline-none focus:ring-2 focus:ring-[#DC2626] focus:ring-offset-2 focus:ring-offset-[#111111] active:bg-[#991B1B] disabled:opacity-60']) }}>
    {{ $slot }}
</button>
