<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center rounded-none border border-transparent bg-[var(--portal-primary)] px-4 py-2 text-xs font-semibold text-white transition hover:bg-[var(--portal-primary-hover)] focus:bg-[var(--portal-primary-hover)] focus:outline-none focus:ring-2 focus:ring-[var(--portal-primary)] focus:ring-offset-2 focus:ring-offset-[var(--portal-surface)] active:bg-[var(--portal-primary-hover)] disabled:opacity-60']) }}>
    {{ $slot }}
</button>
