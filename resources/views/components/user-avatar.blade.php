@props(['user', 'size' => 'md'])

@php
    $sizes = [
        'xs' => 'h-6 w-6 text-[10px]',
        'sm' => 'h-8 w-8 text-xs',
        'md' => 'h-10 w-10 text-sm',
        'lg' => 'h-16 w-16 text-lg',
        'xl' => 'h-24 w-24 text-2xl',
    ];
    $sizeClasses = $sizes[$size] ?? $sizes['md'];
@endphp

@if ($user->avatar_url)
    <img
        src="{{ $user->avatar_url }}"
        alt="{{ $user->name }}"
        {{ $attributes->merge(['class' => "inline-block $sizeClasses shrink-0 rounded-full object-cover ring-1 ring-[var(--portal-border)]"]) }}
    />
@else
    <span
        {{ $attributes->merge(['class' => "inline-flex $sizeClasses shrink-0 items-center justify-center overflow-hidden rounded-full ring-1 ring-[var(--portal-border)]"]) }}
        role="img"
        aria-label="{{ $user->name }}"
    >
        <svg viewBox="0 0 200 200" class="h-full w-full">
            <defs>
                <clipPath id="user-avatar-clip-{{ $user->id }}-{{ $size }}">
                    <circle cx="100" cy="100" r="100" />
                </clipPath>
            </defs>
            <circle cx="100" cy="100" r="100" fill="black" />
            <g clip-path="url(#user-avatar-clip-{{ $user->id }}-{{ $size }})">
                <circle cx="100" cy="78" r="38" fill="white" />
                <ellipse cx="100" cy="205" rx="72" ry="62" fill="white" />
            </g>
        </svg>
    </span>
@endif
