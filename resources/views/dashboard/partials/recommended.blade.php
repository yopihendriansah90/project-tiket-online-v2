<div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-100">
    <div class="px-4 sm:px-6 py-4 border-b border-gray-100">
        <h3 class="text-base font-semibold text-gray-800" id="recommended-title">Rekomendasi Event</h3>
        <p class="mt-1 text-sm text-gray-600">Poster event pilihan untukmu — tampilan simetris dan menarik.</p>
    </div>

    <div class="p-4 sm:p-6">
        @if (!empty($recommended) && $recommended->count() > 0)
            <!-- Grid simetris: 1 / 2 / 4 kolom -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($recommended as $event)
                    @php
                        $poster = method_exists($event, 'getFirstMediaUrl')
                            ? $event->getFirstMediaUrl('event_posters')
                            : null;

                        $posterUrl = $poster ?: 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?q=80&auto=format&fit=crop&w=800&h=1000';

                        $badgeClasses = $event->is_online
                            ? 'bg-blue-500/90 text-white'
                            : 'bg-emerald-500/90 text-white';
                    @endphp

                    <div class="group relative rounded-2xl overflow-hidden ring-1 ring-gray-100 bg-white hover:shadow transition">
                        <!-- Area Poster (tinggi tetap untuk simetri) -->
                        <div class="relative h-64 w-full overflow-hidden">
                            <img
                                src="{{ $posterUrl }}"
                                alt="Poster event {{ $event->title }}"
                                class="h-full w-full object-cover transform group-hover:scale-105 transition duration-500"
                                loading="lazy"
                            />
                            <!-- Overlay gradient bawah untuk kontras teks -->
                            <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>

                            <!-- Badge Online/Offline -->
                            <span class="absolute top-3 left-3 inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-semibold {{ $badgeClasses }}">
                                {{ $event->is_online ? 'Online' : 'Offline' }}
                            </span>
                        </div>

                        <!-- Konten teks -->
                        <div class="p-4">
                            <h4 class="text-sm sm:text-base font-semibold text-gray-900 truncate" aria-label="Judul event">
                                {{ $event->title }}
                            </h4>
                            <p class="mt-1 text-xs text-gray-600 truncate">{{ $event->location }}</p>
                            <p class="mt-1 text-xs text-gray-600">
                                {{ \Carbon\Carbon::parse($event->start_date)->translatedFormat('d M Y H:i') }}
                                &ndash;
                                {{ \Carbon\Carbon::parse($event->end_date)->translatedFormat('d M Y H:i') }}
                            </p>

                            <div class="mt-3 flex items-center gap-2">
                                <a href="{{ route('events.show', $event->slug) }}"
                                   class="inline-flex items-center px-3 py-2 rounded-md text-xs sm:text-sm font-semibold text-white bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-600"
                                   aria-label="Lihat detail event {{ $event->title }}">
                                    <span class="mr-1">🔍</span>
                                    Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="flex items-center justify-between rounded-lg border border-dashed border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center mr-3">
                        <span class="text-gray-500">🎭</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">Belum ada rekomendasi saat ini</p>
                        <p class="text-xs text-gray-600">Jelajahi event populer dan temukan yang kamu suka.</p>
                    </div>
                </div>
                <a href="{{ route('events.index') }}"
                   class="text-sm font-semibold text-purple-600 hover:text-purple-700">
                    Jelajahi Event
                </a>
            </div>
        @endif
    </div>
</div>