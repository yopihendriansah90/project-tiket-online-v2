<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Daftar Event</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">

    <div class="container mx-auto px-4 py-12">
        <h1 class="text-4xl font-bold text-center text-gray-800 mb-10">Event Terbaru</h1>

        @if($events->isEmpty())
            <div class="text-center py-16">
                <p class="text-xl text-gray-500">Belum ada event yang akan datang. Silakan cek kembali nanti!</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($events as $event)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden transform hover:-translate-y-2 transition-transform duration-300">
                        <a href="{{ route('events.show', $event) }}" class="block">
                            <img class="h-90 w-full object-cover" src="{{ $event->getFirstMediaUrl('event_posters') }}" alt="Poster {{ $event->title }}">
                            <div class="p-6">
                                <p class="text-sm text-blue-500 font-semibold">{{ $event->start_date->format('d M Y') }}</p>
                                <h2 class="text-2xl font-bold mt-2 text-gray-800 truncate">{{ $event->title }}</h2>
                                <div class="flex items-center mt-3 text-gray-600">
                                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span class="truncate">{{ $event->location }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="mt-12">
                {{ $events->links() }} 
            </div>
        @endif
    </div>

</body>
</html>
