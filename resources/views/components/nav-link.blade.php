@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-[var(--portal-text-primary)] focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-[var(--portal-text-secondary)] hover:text-[var(--portal-text-primary)] hover:border-[var(--portal-border)] focus:outline-none focus:text-[var(--portal-text-primary)] focus:border-[var(--portal-border)] transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
