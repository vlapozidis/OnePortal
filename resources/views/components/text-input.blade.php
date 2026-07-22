@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-none border border-[#1F1F1F] bg-[#0A0A0A] text-white shadow-sm placeholder:text-[#71717A] focus:border-[#DC2626] focus:ring-[#DC2626]']) }}>
