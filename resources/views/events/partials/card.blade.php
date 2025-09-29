<div class="group bg-white rounded-xl shadow-md overflow-hidden flex flex-col h-full transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
    <a href="{{ route('events.show', $event) }}" class="flex flex-col flex-grow">
        <div class="relative">
            <img class="h-56 w-full object-cover" src="{{ $event->getFirstMediaUrl('event_posters') }}" alt="Poster {{ $event->title }}">
            <div class="absolute inset-0 bg-black bg-opacity-20 group-hover:bg-opacity-10 transition-all duration-300"></div>
        </div>
        <div class="p-5 flex flex-col flex-grow">
            <p class="text-sm font-semibold text-indigo-600 uppercase tracking-wider">{{ $event->start_date->format('d M Y') }}</p>
            <h2 class="text-xl font-bold text-gray-900 mt-1 truncate">{{ $event->title }}</h2>
            
            <div class="flex items-center mt-3 text-gray-600">
                <svg class="h-5 w-5 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span class="text-sm truncate">{{ $event->location }}</span>
            </div>

            <!-- Price Info -->
            @if($event->tickets->count() > 0)
            <div class="flex items-center mt-4 text-gray-800">
                <svg class="h-5 w-5 mr-2 flex-shrink-0 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM12.38 5.62a1 1 0 00-1.41 0L10 6.59 8.03 4.62a1 1 0 00-1.41 1.41L8.59 8 6.62 9.97a1 1 0 001.41 1.41L10 9.41l1.97 1.97a1 1 0 001.41-1.41L11.41 8l1.97-1.97a1 1 0 000-1.41z" />
                </svg>
                <span class="text-sm font-semibold">Mulai dari <span class="font-bold text-green-600">Rp {{ number_format($event->tickets->min('price'), 0, ',', '.') }}</span></span>
            </div>
            @endif

            <!-- Spacer -->
            <div class="flex-grow"></div>

            <!-- CTA Button -->
            <div class="mt-6">
                <div class="w-full text-center px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow-md transition-all duration-300 group-hover:bg-indigo-700">
                    Lihat Detail
                </div>
            </div>
        </div>
    </a>
</div>
