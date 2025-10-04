<x-app-layout>
@php
$title = $event->title . ' - TiketIn';
@endphp

    <!-- Breadcrumb -->
    <nav class="bg-white py-4 border-b border-gray-200">
        <div class="container mx-auto px-4">
            <div class="flex items-center space-x-2 text-sm">
                <a href="{{ route('home') }}" class="text-purple-600 hover:text-purple-700">Home</a>
                <span class="text-gray-400">→</span>
                <a href="{{ route('events.index') }}" class="text-purple-600 hover:text-purple-700">Events</a>
                <span class="text-gray-400">→</span>
                <span class="text-gray-600 truncate">{{ $event->title }}</span>
            </div>
        </div>
    </nav>

    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-purple-50">
        <div class="container mx-auto px-4 py-8">
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100">
                <div class="lg:grid lg:grid-cols-5 lg:gap-0">
                    <!-- Enhanced Poster Section -->
                    <div class="lg:col-span-2 relative p-6 lg:p-12">
                        <div class="mx-auto max-w-[520px] w-full">
                            <div class="mx-auto max-w-[480px]">
                                <div class="relative aspect-[4/5] lg:min-h-[600px] rounded-2xl overflow-hidden ring-1 ring-purple-100 shadow-xl">
                                <img class="w-full h-full object-cover"
                                     src="{{ $event->getFirstMediaUrl('event_posters') ?: 'https://via.placeholder.com/400x500/6366f1/ffffff?text=🎭+' . urlencode($event->title) }}"
                                     alt="Poster {{ $event->title }}">

                                <!-- Gradient overlay -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-black/10 to-transparent pointer-events-none"></div>
                                
                                <!-- Status Badges -->
                                <div class="absolute top-6 left-6 space-y-2">
                                    @if($event->is_online)
                                        <div class="bg-blue-500 text-white px-4 py-2 rounded-xl font-bold text-sm shadow-lg">
                                            🌐 Event Online
                                        </div>
                                    @endif
                                    
                                    @if($event->tickets->where('quantity', '<=', 50)->count() > 0)
                                        <div class="badge-limited">
                                            📍 Tiket Terbatas!
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Share Button -->
                                <div class="absolute top-6 right-6">
                                    <button data-share class="bg-white/90 backdrop-blur-sm text-purple-600 p-3 rounded-full shadow-lg hover:bg-white transition-all duration-300">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                                        </svg>
                                    </button>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Info Section -->
                    <div class="lg:col-span-3">
                        <div class="p-8 lg:p-12">
                            <!-- Event Header -->
                            <div class="mb-8">
                                <div class="flex flex-wrap items-center gap-3 mb-4">
                                    <span class="bg-gradient-to-r from-purple-600 to-blue-600 text-white px-4 py-2 rounded-full text-sm font-bold">
                                        📅 {{ $event->start_date->format('d M Y') }}
                                    </span>
                                    <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-sm font-medium">
                                        ⏰ {{ $event->start_date->format('H:i') }} WIB
                                    </span>
                                    @if($event->has_numbered_seats)
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-medium">
                                            🪑 Kursi Bernomor
                                        </span>
                                    @endif
                                </div>
                                
                                <h1 class="text-3xl lg:text-5xl font-display font-extrabold text-gray-900 mb-4 leading-tight">
                                    {{ $event->title }}
                                </h1>
                                
                                <div class="flex items-center space-x-4 text-gray-600 mb-6">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gradient-to-br from-purple-400 to-blue-400 rounded-full flex items-center justify-center mr-2">
                                            <span class="text-white font-bold text-sm">{{ substr($event->user->name ?? 'A', 0, 1) }}</span>
                                        </div>
                                        <span class="font-medium">{{ $event->user->name ?? 'Event Organizer' }}</span>
                                        <span class="ml-2 text-green-500">✅</span>
                                    </div>
                                </div>

                                <!-- Rating and Social Proof -->
                                <div class="flex items-center space-x-6 mb-8">
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= 4)
                                                <span class="text-yellow-400 text-lg">⭐</span>
                                            @else
                                                <span class="text-gray-300 text-lg">☆</span>
                                            @endif
                                        @endfor
                                        <span class="text-gray-600 ml-2 font-medium">4.2 ({{ rand(50, 200) }} ulasan)</span>
                                    </div>
                                    
                                    <div class="flex items-center text-purple-600 font-medium">
                                        <span class="mr-1">👥</span>
                                        <span>{{ rand(100, 1000) }}+ tertarik</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Enhanced Details Card -->
                            <div class="bg-gradient-to-br from-purple-50 to-blue-50 p-8 rounded-2xl mb-8 border border-purple-100">
                                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                                    <span class="mr-2">📋</span>
                                    Detail Event
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="flex items-start space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center flex-shrink-0">
                                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">Waktu</p>
                                            <p class="text-gray-600">{{ $event->start_date->format('d M Y, H:i') }}</p>
                                            <p class="text-gray-600">s/d {{ $event->end_date->format('d M Y, H:i') }}</p>
                                            <p class="text-sm text-purple-600 font-medium mt-1">
                                                ⏱️ {{ $event->start_date->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-start space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-teal-500 rounded-full flex items-center justify-center flex-shrink-0">
                                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">Lokasi</p>
                                            <p class="text-gray-600">{{ $event->location }}</p>
                                            @if($event->is_online)
                                                <p class="text-sm text-blue-600 font-medium mt-1">
                                                    🌐 Link akan dikirim via email
                                                </p>
                                            @else
                                                <button class="text-sm text-purple-600 hover:text-purple-700 font-medium mt-1">
                                                    🗺️ Lihat di Maps
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Description Section -->
                            <div class="mb-8">
                                <h2 class="text-2xl font-display font-bold text-gray-800 mb-6 flex items-center">
                                    <span class="mr-2">📖</span>
                                    Tentang Event Ini
                                </h2>
                                <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                                    {!! $event->description !!}
                                </div>
                            </div>

                            <!-- Event Highlights -->
                            <div class="mb-8">
                                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                                    <span class="mr-2">✨</span>
                                    Highlights
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="flex items-center space-x-3 p-4 bg-white rounded-xl border border-gray-200">
                                        <span class="text-2xl">🎯</span>
                                        <div>
                                            <p class="font-semibold text-gray-800">Event Berkualitas</p>
                                            <p class="text-sm text-gray-600">Diorganisir oleh professional</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3 p-4 bg-white rounded-xl border border-gray-200">
                                        <span class="text-2xl">🔒</span>
                                        <div>
                                            <p class="font-semibold text-gray-800">Pembayaran Aman</p>
                                            <p class="text-sm text-gray-600">SSL encrypted & verified</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3 p-4 bg-white rounded-xl border border-gray-200">
                                        <span class="text-2xl">📱</span>
                                        <div>
                                            <p class="font-semibold text-gray-800">E-Tiket Instant</p>
                                            <p class="text-sm text-gray-600">Langsung ke email Anda</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3 p-4 bg-white rounded-xl border border-gray-200">
                                        <span class="text-2xl">💯</span>
                                        <div>
                                            <p class="font-semibold text-gray-800">Garansi Uang Kembali</p>
                                            <p class="text-sm text-gray-600">Jika event dibatalkan</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Ticket Selection Component -->
                            <div id="beli-tiket" class="mb-8">
                                @if(session('success'))
                                    <div class="mb-4 rounded-xl border border-green-200 bg-green-50 text-green-800 px-4 py-3 text-sm">
                                        ✅ {{ session('success') }}
                                    </div>
                                @endif
                                @if($errors->any())
                                    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 text-red-800 px-4 py-3 text-sm">
                                        <ul class="list-disc pl-5 space-y-1">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="bg-white/80 backdrop-blur border border-purple-100 rounded-2xl shadow-xl overflow-hidden">
                                    <div class="p-6 sm:p-8">
                                        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                                            <span class="mr-2">🎟️</span>
                                            Beli Tiket
                                        </h3>

                                        <form method="POST" action="{{ route('orders.store') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 items-end">
                                            @csrf
                                            <input type="hidden" name="event_id" value="{{ $event->id }}">

                                            <div>
                                                <label for="ticket_id" class="block text-sm font-medium text-gray-700 mb-1">Jenis Tiket</label>
                                                <select id="ticket_id" name="ticket_id" required class="w-full rounded-xl border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                                    @forelse($event->tickets as $ticket)
                                                        @php $stock = $ticketStock[$ticket->id] ?? $ticket->available_stock; @endphp
                                                        <option value="{{ $ticket->id }}" data-price="{{ $ticket->price }}" data-stock="{{ $stock }}" {{ $stock <= 0 ? 'disabled' : '' }}>
                                                            {{ $ticket->name }} — Rp {{ number_format($ticket->price, 0, ',', '.') }} {{ $stock <= 0 ? '(Habis)' : '' }}
                                                        </option>
                                                    @empty
                                                        <option disabled>Belum ada tiket tersedia</option>
                                                    @endforelse
                                                </select>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    Harga: <span id="priceDisplay">-</span>
                                                    · Stok: <span id="stockDisplay">-</span>
                                                </p>
                                            </div>

                                            <div>
                                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                                                <input type="number" id="quantity" name="quantity" min="1" value="1" class="w-full rounded-xl border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                                <p class="text-xs text-gray-500 mt-1">Subtotal: <span id="subtotalDisplay">-</span></p>
                                            </div>

                                            <div>
                                                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition">
                                                    🛒 Beli Tiket
                                                </button>
                                                <p class="text-[11px] text-gray-500 mt-2">Pembayaran dilakukan nanti. Order akan berstatus "pending".</p>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Event Organizer Info -->
                            <div class="bg-gradient-to-r from-gray-50 to-purple-50 p-8 rounded-2xl border border-gray-200">
                                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                                    <span class="mr-2">👥</span>
                                    Tentang Penyelenggara
                                </h3>
                                <div class="flex items-start space-x-4">
                                    <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-blue-400 rounded-full flex items-center justify-center">
                                        <span class="text-white font-bold text-xl">{{ substr($event->user->name ?? 'A', 0, 1) }}</span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <h4 class="font-bold text-gray-800">{{ $event->user->name ?? 'Event Organizer' }}</h4>
                                            <span class="text-green-500">✅</span>
                                            <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded-full text-xs font-medium">Verified</span>
                                        </div>
                                        <p class="text-gray-600 mb-4">Organizer profesional dengan pengalaman menyelenggarakan {{ rand(10, 50) }}+ event sukses.</p>
                                        
                                        <div class="flex items-center space-x-4 text-sm text-gray-600">
                                            <div class="flex items-center">
                                                <span class="mr-1">⭐</span>
                                                <span>4.8 rating</span>
                                            </div>
                                            <div class="flex items-center">
                                                <span class="mr-1">🎪</span>
                                                <span>{{ rand(20, 100) }} event</span>
                                            </div>
                                            <div class="flex items-center">
                                                <span class="mr-1">👥</span>
                                                <span>{{ rand(1000, 5000) }}+ attendees</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Related Events -->
            <div class="mt-16">
                <h3 class="text-2xl font-display font-bold text-gray-800 mb-8 text-center">
                    🔗 Event <span class="text-gradient">Serupa</span>
                </h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Mock related events for now -->
                    @for($i = 1; $i <= 4; $i++)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden card-hover">
                        <div class="relative">
                            <div class="h-48 bg-gradient-to-br from-purple-400 to-blue-500 flex items-center justify-center">
                                <span class="text-white text-4xl">🎭</span>
                            </div>
                            <div class="absolute top-3 right-3 bg-white/90 text-purple-600 px-2 py-1 rounded-full text-xs font-bold">
                                📅 {{ now()->addDays($i * 7)->format('d M') }}
                            </div>
                        </div>
                        <div class="p-4">
                            <h4 class="font-bold text-gray-800 mb-2">Event Serupa {{ $i }}</h4>
                            <p class="text-sm text-gray-600 mb-3">{{ $event->location }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-green-600 font-bold">Rp {{ number_format(rand(50000, 200000), 0, ',', '.') }}</span>
                                <button class="text-purple-600 hover:text-purple-700 font-medium text-sm">
                                    Lihat →
                                </button>
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    // Enhanced interaction for event details
    document.addEventListener('DOMContentLoaded', function() {
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Share functionality
        document.querySelector('[data-share]')?.addEventListener('click', function() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ $event->title }}',
                    text: 'Lihat event menarik ini di TiketIn!',
                    url: window.location.href
                });
            } else {
                // Fallback copy to clipboard
                navigator.clipboard.writeText(window.location.href).then(() => {
                    alert('Link event berhasil disalin!');
                });
            }
        });

        // Ticket form dynamic pricing and stock
        const ticketSelect = document.getElementById('ticket_id');
        const quantityInput = document.getElementById('quantity');
        const priceDisplay = document.getElementById('priceDisplay');
        const stockDisplay = document.getElementById('stockDisplay');
        const subtotalDisplay = document.getElementById('subtotalDisplay');

        function formatRupiah(number) {
            try {
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(number);
            } catch (e) {
                return 'Rp ' + (number || 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }
        }

        function updateUI() {
            if (!ticketSelect) return;
            const opt = ticketSelect.selectedOptions[0];
            if (!opt) return;

            const price = parseInt(opt.dataset.price || '0', 10);
            const stock = parseInt(opt.dataset.stock || '0', 10);

            // Update max quantity based on stock
            if (quantityInput) {
                const min = 1;
                const max = Math.max(1, stock);
                quantityInput.min = String(min);
                quantityInput.max = String(max);
                const current = parseInt(quantityInput.value || '1', 10);
                if (current > max) {
                    quantityInput.value = String(max);
                } else if (current < min) {
                    quantityInput.value = String(min);
                }
            }

            // Update displays
            if (priceDisplay) priceDisplay.textContent = formatRupiah(price);
            if (stockDisplay) stockDisplay.textContent = stock > 0 ? stock + ' tersedia' : 'Habis';
            if (subtotalDisplay && quantityInput) {
                const qty = parseInt(quantityInput.value || '1', 10);
                subtotalDisplay.textContent = formatRupiah(price * qty);
            }
        }

        ticketSelect?.addEventListener('change', updateUI);
        quantityInput?.addEventListener('input', updateUI);

        // Initialize on load
        updateUI();
    });
    </script>
    @endpush
</x-app-layout>