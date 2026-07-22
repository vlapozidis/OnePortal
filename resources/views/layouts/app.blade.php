<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @php
            $routeName = request()->route()?->getName();

            $pageTitle = match (true) {
                request()->routeIs('dashboard') => 'Dashboard',
                request()->routeIs('employees.*') => 'Employees',
                request()->routeIs('departments.index') => 'Departments',
                request()->routeIs('departments.create') => 'Create Department',
                request()->routeIs('departments.edit') => 'Edit Department',
                request()->routeIs('departments.*') => 'Departments',
                request()->routeIs('leave-requests.index') => 'Leave History',
                request()->routeIs('leave-requests.create') => 'Submit Leave Request',
                request()->routeIs('leave-requests.*') => 'Leave Requests',
                request()->routeIs('workforce.*') => "Today's Workforce",
                request()->routeIs('statistics.*') => 'Leave Statistics',
                request()->routeIs('admin.dashboard') => 'Admin Dashboard',
                request()->routeIs('admin.*') => 'Admin',
                request()->routeIs('profile.*') => 'Profile Settings',
                ! empty($routeName) => \Illuminate\Support\Str::of($routeName)->replace(['.', '-'], ' ')->title()->toString(),
                default => config('app.name', 'Classter'),
            };
        @endphp

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/png" href="{{ asset('images/logofree.png') }}">
        <link rel="shortcut icon" href="{{ asset('images/logofree.png') }}">

        <title>{{ $pageTitle }} | {{ config('app.name', 'Classter') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body
        class="font-sans antialiased bg-[#0A0A0A] text-white"
        data-toast-status="{{ session('status') }}"
        data-toast-success="{{ session('success') }}"
        data-toast-error="{{ session('error') }}"
        data-toast-errors='@json($errors->all())'
    >
        <div class="min-h-screen lg:flex">
            @include('layouts.navigation')

            <div class="flex-1">
                <header class="border-b border-[#1F1F1F] bg-[#111111]/90 backdrop-blur">
                    <div class="flex w-full items-center justify-between gap-4 px-6 py-5 lg:px-10">
                        @isset($header)
                            {{ $header }}
                        @else
                            <h1 class="text-2xl font-semibold leading-tight text-white">{{ $pageTitle }}</h1>
                        @endisset
                    </div>
                </header>

                <main class="px-4 py-6 sm:px-6 lg:px-10 lg:py-8">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
