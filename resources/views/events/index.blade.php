<x-app-layout>
    {{-- New Search Hero Section --}}
    <section class="hero-section-dark py-20">
        <div class="container mx-auto px-4 text-center relative z-10">
            <div class="max-w-3xl mx-auto">
                <h1 class="text-4xl md:text-6xl font-display font-extrabold mb-6 text-white">
                    Jelajahi <span class="text-yellow-400">Event</span> Terbaik
                </h1>
                <p class="text-lg md:text-xl mb-8 text-white/80">
                    Dari konser musik hingga workshop bisnis, temukan pengalaman tak terlupakan di sini.
                </p>

                <form action="{{ route('events.index') }}" method="GET" class="bg-white/10 backdrop-blur-lg p-6 rounded-2xl shadow-lg border border-white/20">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="relative flex-grow">
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text"
                                   name="search"
                                   class="w-full bg-white/10 text-white placeholder-gray-300 border-0 rounded-lg pl-11 py-3 focus:ring-2 focus:ring-purple-400"
                                   placeholder="Cari nama event, lokasi, atau artis..."
                                   value="{{ request('search') }}">
                        </div>
                        <button type="submit" class="btn-primary py-3">
                            <span class="flex items-center justify-center space-x-2">
                                <span>🔍</span>
                                <span>Cari Event</span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    {{-- Results Section --}}
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            {{-- Sort and Results Summary --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                <div class="text-sm text-gray-600">
                    @if($events->total() > 0)
                        Menampilkan <span class="font-bold text-gray-800">{{ $events->firstItem() }}-{{ $events->lastItem() }}</span> dari <span class="font-bold text-gray-800">{{ $events->total() }}</span> event.
                    @endif
                    @if(request('search'))
                        Hasil untuk "<span class="font-bold text-purple-600">{{ request('search') }}</span>".
                    @endif
                </div>

                <div class="flex items-center gap-4">
                    @if(request('search') || request('sort'))
                        <a href="{{ route('events.index') }}" class="text-sm text-purple-600 hover:text-purple-800 font-medium">
                            &times; Hapus Filter
                        </a>
                    @endif
                    <form action="{{ route('events.index') }}" method="GET">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <select name="sort" id="sort" class="border-gray-300 rounded-lg shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50 text-sm" onchange="this.form.submit()">
                            <option value="latest" @selected(request('sort', 'latest') == 'latest')>Urutkan: Terbaru</option>
                            <option value="soonest" @selected(request('sort') == 'soonest')>Urutkan: Tanggal Terdekat</option>
                            <option value="cheapest" @selected(request('sort') == 'cheapest')>Urutkan: Harga Termurah</option>
                        </select>
                    </form>
                </div>
            </div>

            {{-- Events Grid --}}
            @if($events->isEmpty())
                <div class="text-center py-20 px-8 bg-white rounded-2xl shadow-xl border border-gray-100 max-w-2xl mx-auto">
                    <div class="text-8xl mb-6">🎭</div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Oops! Tidak Ada Event Ditemukan</h2>
                    <p class="text-lg text-gray-600 mb-8">
                        Tidak ada event yang sesuai dengan filter Anda. Coba kata kunci lain.
                    </p>
                    <a href="{{ route('events.index') }}" class="btn-primary inline-flex">
                        <span class="flex items-center space-x-2">
                            <span>👀</span>
                            <span>Lihat Semua Event</span>
                        </span>
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 mb-16">
                    @foreach($events as $index => $event)
                        <div class="animate-scale-in" style="animation-delay: {{ ($index % 12) * 0.05 }}s;">
                            @include('events.partials.card', ['event' => $event])
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="flex justify-center">
                    {{ $events->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </section>
</x-app-layout>