@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-none border border-[var(--portal-border)] bg-[var(--portal-bg)] text-[var(--portal-text-primary)] shadow-sm placeholder:text-[var(--portal-text-secondary)]/70 focus:border-[var(--portal-primary)] focus:ring-[var(--portal-primary)]']) }}>
