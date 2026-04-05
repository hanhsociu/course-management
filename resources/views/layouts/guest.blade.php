<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700&family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-900">
        <div class="guest-shell">
            <div class="relative z-10 flex flex-col items-center w-full max-w-md pt-8 sm:pt-0">
                <a href="/" wire:navigate class="mb-8 inline-flex rounded-2xl border border-slate-200/80 bg-white/70 p-4 shadow-soft backdrop-blur-sm transition hover:border-indigo-200/80 hover:shadow-card">
                    <x-application-logo class="h-12 w-auto fill-current text-indigo-600" />
                </a>

                <div class="guest-card">
                    {{ $slot }}
                </div>

                <p class="relative z-10 mt-8 text-center text-sm text-slate-500">
                    <a href="/" wire:navigate class="font-medium text-indigo-600 hover:text-indigo-500 transition">← Về trang chủ</a>
                </p>
            </div>
        </div>
    </body>
</html>
