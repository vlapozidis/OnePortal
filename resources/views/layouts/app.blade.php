<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ auth()->user()?->theme === 'dark' ? 'dark' : '' }}">
    <head>
        @php
            $routeName = request()->route()?->getName();

            $pageTitle = match (true) {
                request()->routeIs('dashboard') => __('Dashboard'),
                request()->routeIs('employees.*') => __('Employees'),
                request()->routeIs('departments.index') => __('Departments'),
                request()->routeIs('departments.create') => __('Create Department'),
                request()->routeIs('departments.edit') => __('Edit Department'),
                request()->routeIs('departments.*') => __('Departments'),
                request()->routeIs('leave-requests.index') => __('Leave History'),
                request()->routeIs('leave-requests.create') => __('Submit Leave Request'),
                request()->routeIs('leave-requests.*') => __('Leave Requests'),
                request()->routeIs('workforce.*') => __("Today's Workforce"),
                request()->routeIs('statistics.*') => __('Leave Statistics'),
                request()->routeIs('profile.*') => __('Settings'),
                ! empty($routeName) => \Illuminate\Support\Str::of($routeName)->replace(['.', '-'], ' ')->title()->toString(),
                default => config('app.name', 'OnePortal'),
            };
        @endphp

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
        <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}">

        <title>{{ $pageTitle }} | {{ config('app.name', 'OnePortal') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[var(--portal-bg)] text-[var(--portal-text-primary)]">
        <div class="min-h-screen lg:flex">
            @include('layouts.navigation')

            <div class="flex-1">
                <header class="border-b border-[var(--portal-border)] bg-[var(--portal-surface)]/90 backdrop-blur">
                    <div class="flex w-full items-center justify-between gap-4 px-6 py-5 lg:px-10">
                        @isset($header)
                            {{ $header }}
                        @else
                            <h1 class="text-2xl font-semibold leading-tight text-[var(--portal-text-primary)]">{{ $pageTitle }}</h1>
                        @endisset
                    </div>
                </header>

                <x-flash-messages class="px-4 pt-4 sm:px-6 lg:px-10" />

                <main class="px-4 py-6 sm:px-6 lg:px-10 lg:py-8">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
