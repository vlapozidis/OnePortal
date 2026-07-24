<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center rounded-none border border-[var(--portal-border)] bg-[var(--portal-bg)] px-4 py-2 text-xs font-semibold text-[var(--portal-text-primary)] transition hover:border-[var(--portal-primary)] hover:bg-[var(--portal-surface)] focus:outline-none focus:ring-2 focus:ring-[var(--portal-primary)] focus:ring-offset-2 focus:ring-offset-[var(--portal-elevated)] disabled:opacity-60']) }}>
    {{ $slot }}
</button>
