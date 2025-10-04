<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <!-- Skip to content for keyboard users -->
        <a href="#main-content"
           class="sr-only focus:not-sr-only focus:fixed focus:top-2 focus:left-2 focus:z-50 focus:outline-none focus:ring-2 focus:ring-purple-600 bg-white text-purple-700 px-3 py-2 rounded-md shadow">
            Lewati ke konten utama
        </a>
        <div class="min-h-screen bg-gray-100">
            @include('layouts.partials.navbar')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main id="main-content" role="main" tabindex="-1">
                {{ $slot }}
            </main>

            @include('layouts.partials.footer')
        </div>
            @livewireScripts
            @stack('scripts')
    </body>
</html>
