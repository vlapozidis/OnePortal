<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
        <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}">

        @php
            $pageTitle = match (true) {
                request()->routeIs('login') => __('Login'),
                request()->routeIs('register') => __('Register'),
                request()->routeIs('password.request') => __('Forgot Password'),
                request()->routeIs('password.reset') => __('Reset Password'),
                request()->routeIs('verification.notice') => __('Verify Email'),
                request()->routeIs('password.confirm') => __('Confirm Password'),
                default => null,
            };
        @endphp

        <title>{{ config('app.name', 'Classter') }}{{ $pageTitle ? ' | '.$pageTitle : '' }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#0A0A0A] text-white">
        <div class="relative min-h-screen overflow-hidden">
            <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_10%_10%,rgba(220,38,38,0.15),transparent_40%),radial-gradient(circle_at_85%_20%,rgba(185,28,28,0.1),transparent_45%)]"></div>

            <x-language-switcher class="absolute right-4 top-4 z-10" />

            <div class="relative flex min-h-screen flex-col items-center justify-center px-4 py-8 lg:flex-row lg:gap-32">
                <!-- Left Side: Logo with Glow (Desktop Only) -->
                <div class="hidden -ml-16 items-center justify-center lg:flex">
                    <div class="relative inline-block">
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(220,38,38,0.6),transparent_60%)] blur-2xl animate-pulse"></div>
                        <img src="{{ asset('images/PortalLogin.png') }}" alt="Classter" class="relative h-64 w-64 drop-shadow-[0_0_30px_rgba(220,38,38,0.6)]">
                    </div>
                </div>

                <!-- Right Side: Login Form -->
                <div class="w-full max-w-md lg:flex-shrink-0">
                    <!-- Mobile: Logo and Title -->
                    <div class="mb-6 text-center lg:hidden">
                        <div class="relative mb-4 inline-block">
                            <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(220,38,38,0.6),transparent_60%)] blur-2xl animate-pulse"></div>
                            <img src="{{ asset('images/PortalLogin.png') }}" alt="Classter" class="relative h-32 w-32 drop-shadow-[0_0_20px_rgba(220,38,38,0.5)]">
                        </div>
                        <a href="/">
                            <span class="text-2xl font-semibold tracking-wide text-white">Classter Portal</span>
                        </a>
                    </div>

                    <div class="overflow-hidden rounded-none border border-[#1F1F1F] bg-[#111111] px-6 py-6 shadow-2xl shadow-black/30">
                    {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
