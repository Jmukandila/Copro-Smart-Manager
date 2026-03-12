<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Laravel'))</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
            <footer class="mt-12 border-t border-slate-200/80 bg-gradient-to-b from-white to-slate-50/80">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex h-9 px-3 items-center justify-center rounded-full bg-slate-900 text-white text-[10px] font-black tracking-wide">JKANINDA</span>
                        <div class="text-[10px] font-black uppercase tracking-[0.35em] text-slate-400">Signature</div>
                    </div>
                    <div class="text-center sm:text-right">
                        <p class="text-sm sm:text-base font-black text-slate-400">MADE WITH 💞 BY JOSH KANINDA</p>
                        <p class="text-xs font-semibold text-slate-400">Mars 2026</p>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
