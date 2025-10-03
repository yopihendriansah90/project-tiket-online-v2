<x-app-layout>
    <!-- Enhanced Hero Section -->
    <section class="hero-section-dark text-white relative overflow-hidden min-h-screen flex items-center">
        <div class="container mx-auto px-4 py-16 text-center relative z-10">
            <div class="animate-fade-in">
                <h1 class="text-4xl sm:text-5xl md:text-7xl font-display font-extrabold leading-tight mb-6">
                    <span class="block">Wujudkan</span>
                    <span class="text-yellow-400 block">Pengalaman</span>
                    <span class="block">Tak Terlupakan</span>
                </h1>
                
                <p class="text-lg sm:text-xl md:text-2xl mb-8 max-w-3xl mx-auto opacity-90 leading-relaxed">
                    Temukan dan pesan tiket event terbaik di Indonesia. Dari konser musik hingga workshop, semua ada di sini.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-8">
                    <a href="{{ route('events.index') }}" class="btn-cta text-lg">
                        <span class="flex items-center space-x-2">
                            <span>🎟️</span>
                            <span>Jelajahi Event Sekarang</span>
                        </span>
                    </a>
                    <a href="#categories" class="btn-secondary text-white border-white hover:bg-white hover:text-purple-600 text-lg">
                        <span class="flex items-center space-x-2">
                            <span>📂</span>
                            <span>Lihat Kategori</span>
                        </span>
                    </a>
                </div>
                
                <!-- Trust indicators -->
                <div class="flex flex-col sm:flex-row justify-center items-center space-y-2 sm:space-y-0 sm:space-x-8 text-sm opacity-80">
                    <div class="flex items-center space-x-2">
                        <span>🔒</span>
                        <span>Pembayaran 100% Aman</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span>⚡</span>
                        <span>E-Tiket Instant</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span>✅</span>
                        <span>Garansi Uang Kembali</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Scroll indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce z-10">
            <a href="#features" class="text-white opacity-75 hover:opacity-100 transition-opacity duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
            </a>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-display font-bold text-gray-800 mb-4">
                    Mengapa Memilih <span class="text-gradient">TiketIn</span>?
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Platform terpercaya untuk semua kebutuhan tiket event Anda
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-8 rounded-2xl bg-white card-hover">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-blue-500 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-white text-2xl">⚡</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Pemesanan Instant</h3>
                    <p class="text-gray-600">E-tiket langsung terkirim ke email Anda dalam hitungan detik setelah pembayaran</p>
                </div>
                
                <div class="text-center p-8 rounded-2xl bg-white card-hover">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-500 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-white text-2xl">🔒</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-4">100% Aman</h3>
                    <p class="text-gray-600">Transaksi dilindungi dengan enkripsi SSL dan jaminan keamanan data pribadi</p>
                </div>
                
                <div class="text-center p-8 rounded-2xl bg-white card-hover">
                    <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-white text-2xl">🎯</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Mudah & Cepat</h3>
                    <p class="text-gray-600">Interface yang user-friendly dan proses checkout yang sederhana dalam 3 langkah</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest Events Section -->
    <section id="latest-events" class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16 animate-slide-up">
                <h2 class="text-3xl md:text-4xl font-display font-bold text-gray-800 mb-4">
                    🔥 Event <span class="text-gradient">Terbaru</span>
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Jangan sampai terlewat! Event-event menarik yang baru saja dibuka
                </p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @forelse ($latestEvents as $index => $event)
                    <div class="animate-slide-up" style="animation-delay: {{ $index * 0.1 }}s;">
                        @include('events.partials.card', ['event' => $event])
                    </div>
                @empty
                    <div class="col-span-4 text-center py-16">
                        <div class="text-6xl mb-4">🎭</div>
                        <p class="text-xl text-gray-500 mb-4">Belum ada event terbaru</p>
                        <p class="text-gray-400">Pantau terus untuk update event menarik!</p>
                    </div>
                @endforelse
            </div>
            
            @if($latestEvents->isNotEmpty())
            <div class="text-center mt-12">
                <a href="{{ route('events.index') }}" class="btn-primary text-lg">
                    <span class="flex items-center space-x-2">
                        <span>Lihat Semua Event</span>
                        <span>→</span>
                    </span>
                </a>
            </div>
            @endif
        </div>
    </section>

    <!-- Popular Events Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-display font-bold text-gray-800 mb-4">
                    🌟 Event <span class="text-gradient">Terpopuler</span>
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Event favorit yang paling banyak dipilih oleh komunitas
                </p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @forelse ($popularEvents as $index => $event)
                    <div class="animate-slide-up" style="animation-delay: {{ $index * 0.1 }}s;">
                        @include('events.partials.card', ['event' => $event, 'showPopular' => true])
                    </div>
                @empty
                    <div class="col-span-4 text-center py-16">
                        <div class="text-6xl mb-4">⭐</div>
                        <p class="text-xl text-gray-500 mb-4">Belum ada event populer</p>
                        <p class="text-gray-400">Event populer akan muncul berdasarkan jumlah peserta</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section id="categories" class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-display font-bold text-gray-800 mb-4">
                    📂 Jelajahi <span class="text-gradient">Kategori</span>
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Temukan event sesuai dengan minat dan passion Anda
                </p>
            </div>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-6">
                @php
                $categories = [
                    ['icon' => '🎵', 'name' => 'Musik', 'count' => 24, 'color' => 'from-pink-500 to-red-500'],
                    ['icon' => '🎭', 'name' => 'Theater', 'count' => 12, 'color' => 'from-purple-500 to-blue-500'],
                    ['icon' => '💼', 'name' => 'Bisnis', 'count' => 18, 'color' => 'from-blue-500 to-indigo-500'],
                    ['icon' => '🎨', 'name' => 'Seni', 'count' => 15, 'color' => 'from-green-500 to-teal-500'],
                    ['icon' => '🏃', 'name' => 'Olahraga', 'count' => 20, 'color' => 'from-orange-500 to-red-500'],
                    ['icon' => '🍳', 'name' => 'Kuliner', 'count' => 9, 'color' => 'from-yellow-500 to-orange-500'],
                ];
                @endphp
                
                @foreach($categories as $index => $category)
                <div class="card-interactive p-6 bg-white rounded-2xl shadow-lg text-center animate-scale-in" style="animation-delay: {{ $index * 0.1 }}s;">
                    <div class="w-16 h-16 bg-gradient-to-br {{ $category['color'] }} rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                        {{ $category['icon'] }}
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">{{ $category['name'] }}</h3>
                    <p class="text-sm text-gray-600">{{ $category['count'] }} Event</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="text-4xl font-bold text-purple-600 mb-2">1000+</div>
                    <p class="text-gray-600">Event Berhasil</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-blue-600 mb-2">50K+</div>
                    <p class="text-gray-600">Pelanggan Puas</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-green-600 mb-2">500+</div>
                    <p class="text-gray-600">Event Organizer</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-yellow-600 mb-2">24/7</div>
                    <p class="text-gray-600">Customer Support</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-display font-bold text-gray-800 mb-4">
                    💬 Apa Kata <span class="text-gradient">Pelanggan</span>
                </h2>
                <p class="text-lg text-gray-600">Testimoni dari ribuan pengguna yang puas</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @php
                $testimonials = [
                    ['name' => 'Sarah Johnson', 'event' => 'Coldplay Concert', 'text' => 'Proses pembelian sangat mudah dan tiket langsung terkirim! Recommended banget.', 'rating' => 5],
                    ['name' => 'Budi Santoso', 'event' => 'Workshop Digital Marketing', 'text' => 'Customer service responsif dan helpful. Event yang saya hadiri berkualitas tinggi.', 'rating' => 5],
                    ['name' => 'Maya Putri', 'event' => 'Food Festival Jakarta', 'text' => 'Harga transparan, tidak ada biaya tersembunyi. Interface websitenya juga user-friendly.', 'rating' => 5],
                ];
                @endphp
                
                @foreach($testimonials as $index => $testimonial)
                <div class="bg-white p-8 rounded-2xl shadow-lg card-hover animate-slide-up" style="animation-delay: {{ $index * 0.2 }}s;">
                    <div class="flex items-center mb-4">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="text-yellow-400 text-lg">⭐</span>
                        @endfor
                    </div>
                    <p class="text-gray-700 mb-6 italic">"{{ $testimonial['text'] }}"</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-blue-400 rounded-full flex items-center justify-center mr-4">
                            <span class="text-white font-bold">{{ substr($testimonial['name'], 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $testimonial['name'] }}</p>
                            <p class="text-sm text-gray-600">{{ $testimonial['event'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Enhanced Call to Action Section -->
    <section class="hero-section-dark text-white relative overflow-hidden">
        <div class="container mx-auto px-4 py-20 text-center relative z-10">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl md:text-5xl font-display font-bold mb-6">
                    🚀 Siap untuk <span class="text-yellow-400">Petualangan</span> Baru?
                </h2>
                <p class="text-xl md:text-2xl mb-8 opacity-90 leading-relaxed">
                    Bergabunglah dengan komunitas event lover terbesar di Indonesia
                </p>
                
                @guest
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="{{ route('register') }}" class="btn-cta text-lg">
                        <span class="flex items-center space-x-2">
                            <span>✨</span>
                            <span>Daftar Gratis Sekarang</span>
                        </span>
                    </a>
                    <a href="{{ route('events.index') }}" class="btn-secondary text-white border-white hover:bg-white hover:text-purple-600 text-lg">
                        <span class="flex items-center space-x-2">
                            <span>👀</span>
                            <span>Lihat Event Dulu</span>
                        </span>
                    </a>
                </div>
                
                <p class="mt-6 text-sm opacity-75">
                    🎁 Dapatkan diskon 10% untuk pembelian tiket pertama Anda!
                </p>
                @else
                <div class="text-center">
                    <a href="{{ route('events.index') }}" class="btn-cta text-lg">
                        <span class="flex items-center space-x-2">
                            <span>🎟️</span>
                            <span>Jelajahi Event Sekarang</span>
                        </span>
                    </a>
                </div>
                @endguest
            </div>
        </div>
    </section>
</x-app-layout>