<div class="group bg-white rounded-2xl shadow-lg overflow-hidden transform transition-all duration-500 hover:shadow-2xl hover:-translate-y-2 hover:rotate-1 card-interactive">
    <a href="{{ route('events.show', $event) }}" class="block h-full">
        <div class="relative overflow-hidden">
            <img class="h-64 w-full object-cover transition-transform duration-500 group-hover:scale-110"
                 src="{{ $event->getFirstMediaUrl('event_posters') ?: 'https://via.placeholder.com/400x300/6366f1/ffffff?text=🎭+Event' }}"
                 alt="Poster {{ $event->title }}"
                 loading="lazy">
            
            <!-- Badges Overlay -->
            <div class="absolute top-4 left-4 flex flex-col space-y-2">
                @if(isset($showPopular) && $showPopular)
                    <div class="badge-popular animate-pulse">
                        🔥 Trending
                    </div>
                @endif
                
                @if($event->tickets->where('quantity', '<=', 50)->count() > 0)
                    <div class="badge-limited">
                        📍 Terbatas!
                    </div>
                @endif
                
                @if($event->is_online)
                    <div class="bg-blue-500 text-white px-2 py-1 rounded-full text-xs font-bold">
                        🌐 Online
                    </div>
                @endif
            </div>
            
            <!-- Date Badge -->
            <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm text-purple-600 px-3 py-2 rounded-xl font-bold text-sm shadow-lg">
                📅 {{ $event->start_date->format('d M') }}
            </div>
            
            <!-- Gradient overlay for better text readability -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            
            <!-- Quick preview on hover -->
            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <div class="bg-white/20 backdrop-blur-md text-white px-6 py-3 rounded-xl font-bold border border-white/30">
                    👀 Lihat Detail
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <!-- Event Title -->
            <h2 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-purple-600 transition-colors duration-300 line-clamp-2">
                {{ $event->title }}
            </h2>
            
            <!-- Location with enhanced styling -->
            <div class="flex items-center text-gray-600 mb-4">
                <svg class="h-5 w-5 mr-2 text-purple-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="text-sm truncate">{{ $event->location }}</span>
            </div>
            
            <!-- Event Time -->
            <div class="flex items-center text-gray-600 mb-4">
                <svg class="h-5 w-5 mr-2 text-blue-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-sm">{{ $event->start_date->format('H:i') }} WIB</span>
            </div>
            
            <!-- Price and Attendees Info -->
            @if($event->tickets->count() > 0)
            <div class="flex items-center justify-between mb-4">
                <div>
                    <span class="text-2xl font-bold text-green-600">Rp {{ number_format($event->tickets->min('price'), 0, ',', '.') }}</span>
                    <span class="text-gray-500 text-sm">/tiket</span>
                </div>
                
                <!-- Mock attendees count for social proof -->
                <div class="flex items-center text-sm text-gray-600 bg-gray-100 px-3 py-1 rounded-full">
                    <span class="mr-1">👥</span>
                    <span class="font-semibold">{{ rand(50, 500) }}+</span>
                    <span class="ml-1">hadir</span>
                </div>
            </div>
            @endif
            
            <!-- Rating (mock data for now) -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= 4)
                            <span class="text-yellow-400 text-sm">⭐</span>
                        @else
                            <span class="text-gray-300 text-sm">☆</span>
                        @endif
                    @endfor
                    <span class="text-gray-600 text-sm ml-1">({{ rand(10, 100) }})</span>
                </div>
                
                <!-- Organizer badge -->
                <div class="flex items-center text-xs text-gray-500 bg-purple-100 px-2 py-1 rounded-full">
                    <span class="mr-1">✅</span>
                    <span>Verified</span>
                </div>
            </div>
            
            <!-- Enhanced CTA Button -->
            <button class="w-full bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl group-hover:shadow-purple">
                <span class="flex items-center justify-center space-x-2">
                    <span>🎟️</span>
                    <span>Beli Tiket</span>
                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </span>
            </button>
        </div>
    </a>
</div>
