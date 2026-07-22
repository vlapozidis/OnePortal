<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center rounded-none border border-transparent bg-[#B91C1C] px-4 py-2 text-xs font-semibold text-white transition hover:bg-[#991B1B] focus:outline-none focus:ring-2 focus:ring-[#DC2626] focus:ring-offset-2 focus:ring-offset-[#111111] active:bg-[#7F1D1D]']) }}>
    {{ $slot }}
</button>
