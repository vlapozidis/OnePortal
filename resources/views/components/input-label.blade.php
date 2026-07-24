@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-medium text-[var(--portal-text-secondary)]']) }}>
    {{ $value ?? $slot }}
</label>
