@if($step === 'select')
<div x-data="{ showSuccessAnimation: false }" class="space-y-6">
    <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
        <div class="text-center mb-8">
            <h3 class="text-2xl font-display font-bold text-gray-800 mb-2">
                🎫 Pilih Tiket Anda
            </h3>
            <p class="text-gray-600">Klik untuk menambah atau mengurangi jumlah tiket</p>
        </div>
        
        @if($tickets->isEmpty())
            <div class="text-center py-16">
                <div class="text-6xl mb-6">🎭</div>
                <h4 class="text-xl font-bold text-gray-800 mb-2">Tiket Belum Tersedia</h4>
                <p class="text-gray-500 text-lg mb-4">Tiket untuk event ini sedang dalam persiapan.</p>
                <p class="text-sm text-gray-400">Pantau terus untuk update terbaru!</p>
                
                <!-- Email notification signup -->
                <div class="mt-8 max-w-md mx-auto">
                    <div class="flex">
                        <input type="email" placeholder="Email Anda" class="input-enhanced flex-1 rounded-r-none">
                        <button class="btn-primary rounded-l-none">
                            <span>🔔</span>
                            <span class="hidden sm:inline ml-1">Beritahu Saya</span>
                        </button>
                    </div>
                </div>
            </div>
        @else
            <div class="space-y-6">
                @foreach($tickets as $ticket)
                    @php
                        $stockAvailableLocal = isset($availableStock[$ticket->id]) ? $availableStock[$ticket->id] : $ticket->quantity;
                        $isLowStock = $stockAvailableLocal <= 10;
                        $isSelected = $quantities[$ticket->id] > 0;
                    @endphp
                    
                    <div class="border-2 rounded-2xl p-6 transition-all duration-300 {{ $isSelected ? 'border-purple-500 bg-purple-50 shadow-lg transform scale-105' : 'border-gray-200 hover:border-purple-300 hover:shadow-md' }}" wire:key="ticket-{{ $ticket->id }}">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                            <div class="flex-1">
                                <!-- Ticket Name with Badges -->
                                <div class="flex items-center flex-wrap gap-2 mb-3">
                                    <h4 class="text-xl font-bold text-gray-800">{{ $ticket->name }}</h4>
                                    
                                    @if($ticket->name === 'VIP' || str_contains(strtolower($ticket->name), 'vip'))
                                        <span class="badge-success text-xs">
                                            👑 Premium
                                        </span>
                                    @endif
                                    
                                    @if($isLowStock)
                                        <span class="badge-limited">
                                            ⚠️ Terbatas!
                                        </span>
                                    @endif
                                </div>
                                
                                <!-- Price Display -->
                                <div class="flex items-center space-x-3 mb-3">
                                    <span class="text-3xl font-bold text-green-600">
                                        Rp {{ number_format($ticket->price, 0, ',', '.') }}
                                    </span>
                                    @if(rand(0, 1)) <!-- Mock original price for discount effect -->
                                        <span class="text-lg text-gray-400 line-through">
                                            Rp {{ number_format($ticket->price * 1.2, 0, ',', '.') }}
                                        </span>
                                        <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs font-bold">
                                            HEMAT 17%
                                        </span>
                                    @endif
                                </div>
                                
                                <!-- Stock Indicator with Visual Appeal -->
                                <div class="flex items-center space-x-4 mb-4">
                                    @if($isLowStock)
                                        <span class="flex items-center text-red-600 font-semibold animate-pulse">
                                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 18.5c-.77.833.192 2.5 1.732 2.5z"/>
                                            </svg>
                                            Hanya {{ $stockAvailableLocal }} tiket tersisa!
                                        </span>
                                    @elseif($stockAvailableLocal <= 50)
                                        <span class="flex items-center text-orange-600 font-semibold">
                                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5l-5-5h5v-5a7.5 7.5 0 1 0-15 0v5"/>
                                            </svg>
                                            {{ $stockAvailableLocal }} tiket tersisa
                                        </span>
                                    @else
                                        <span class="flex items-center text-green-600 font-medium">
                                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            {{ $stockAvailableLocal }} tiket tersedia
                                        </span>
                                    @endif
                                    
                                    <!-- Popularity indicator -->
                                    <span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                        📈 {{ rand(20, 80) }}% dipilih
                                    </span>
                                </div>
                                
                                <!-- Ticket Benefits/Features -->
                                @if($ticket->name === 'VIP' || str_contains(strtolower($ticket->name), 'vip'))
                                <div class="text-sm text-gray-600 space-y-1">
                                    <div class="flex items-center"><span class="mr-2">✅</span> Kursi terdepan</div>
                                    <div class="flex items-center"><span class="mr-2">✅</span> Welcome drink gratis</div>
                                    <div class="flex items-center"><span class="mr-2">✅</span> Meet & greet</div>
                                </div>
                                @endif
                            </div>
                            
                            <!-- Enhanced Quantity Selector -->
                            <div class="flex items-center space-x-4 lg:ml-6">
                                <button type="button"
                                        wire:click="decrement({{ $ticket->id }})"
                                        wire:loading.attr="disabled"
                                        class="quantity-btn quantity-btn-minus"
                                        @disabled($quantities[$ticket->id] == 0)>
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4"/>
                                    </svg>
                                </button>
                                
                                <div class="quantity-display">
                                    {{ $quantities[$ticket->id] }}
                                </div>
                                
                                <button type="button"
                                        wire:click="increment({{ $ticket->id }})"
                                        wire:loading.attr="disabled"
                                        class="quantity-btn quantity-btn-plus"
                                        @disabled($quantities[$ticket->id] >= $stockAvailableLocal)>
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        @if($isSelected)
                        <div class="mt-4 p-4 bg-purple-100 rounded-xl">
                            <div class="flex items-center justify-between">
                                <span class="text-purple-700 font-medium">
                                    {{ $quantities[$ticket->id] }} x {{ $ticket->name }}
                                </span>
                                <span class="text-lg font-bold text-purple-600">
                                    Rp {{ number_format($quantities[$ticket->id] * $ticket->price, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                        @endif
                    </div>
                @endforeach
            </div>

            @if($totalPrice > 0)
                <div class="mt-8 p-8 bg-gradient-to-r from-purple-50 to-blue-50 rounded-2xl border-2 border-dashed border-purple-300">
                    <!-- Order Summary -->
                    <div class="text-center mb-6">
                        <h4 class="text-xl font-bold text-gray-800 mb-2">📋 Ringkasan Pesanan</h4>
                        <p class="text-gray-600">Periksa kembali pesanan Anda sebelum melanjutkan</p>
                    </div>
                    
                    <!-- Selected tickets summary -->
                    <div class="space-y-2 mb-6">
                        @foreach($tickets as $ticket)
                            @if($quantities[$ticket->id] > 0)
                            <div class="flex justify-between items-center py-2 border-b border-purple-200">
                                <span class="text-gray-700">{{ $quantities[$ticket->id] }}x {{ $ticket->name }}</span>
                                <span class="font-semibold text-gray-800">Rp {{ number_format($quantities[$ticket->id] * $ticket->price, 0, ',', '.') }}</span>
                            </div>
                            @endif
                        @endforeach
                    </div>
                    
                    <!-- Total Price Display -->
                    <div class="flex justify-between items-center mb-6 p-4 bg-white rounded-xl shadow-sm">
                        <span class="text-xl font-semibold text-gray-700">Total Pembayaran:</span>
                        <span class="text-3xl font-bold text-purple-600">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>
                    
                    <!-- Savings Display (mock) -->
                    @if($totalPrice > 100000)
                    <div class="text-center mb-6">
                        <span class="bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-bold">
                            💰 Anda hemat Rp {{ number_format($totalPrice * 0.1, 0, ',', '.') }} dari harga normal!
                        </span>
                    </div>
                    @endif
                    
                    <!-- Enhanced CTA Button -->
                    <button wire:click="proceedToAttendees"
                            class="w-full btn-cta text-xl py-4 shadow-gold hover:shadow-2xl">
                        <span class="flex items-center justify-center space-x-3">
                            <span>🧾</span>
                            <span>Lanjutkan Isi Data Peserta</span>
                            <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </span>
                    </button>
                    
                    <!-- Trust Indicators -->
                    <div class="flex flex-col sm:flex-row justify-center items-center space-y-2 sm:space-y-0 sm:space-x-8 mt-6 text-sm text-gray-600">
                        <div class="flex items-center">
                            <span class="mr-2">🔒</span>
                            <span>Pembayaran 100% Aman</span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-2">✅</span>
                            <span>Garansi Uang Kembali</span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-2">📱</span>
                            <span>E-Tiket Instant</span>
                        </div>
                    </div>
                    
                    <!-- Payment Methods Preview -->
                    <div class="mt-6 p-4 bg-white rounded-xl">
                        <p class="text-sm text-gray-600 text-center mb-3">Metode pembayaran yang tersedia:</p>
                        <div class="flex justify-center items-center space-x-4">
                            <div class="w-12 h-8 bg-gradient-to-r from-blue-600 to-blue-800 rounded flex items-center justify-center">
                                <span class="text-white text-xs font-bold">💳</span>
                            </div>
                            <div class="w-12 h-8 bg-gradient-to-r from-green-600 to-green-800 rounded flex items-center justify-center">
                                <span class="text-white text-xs font-bold">💰</span>
                            </div>
                            <div class="w-12 h-8 bg-gradient-to-r from-purple-600 to-purple-800 rounded flex items-center justify-center">
                                <span class="text-white text-xs font-bold">🏦</span>
                            </div>
                            <span class="text-xs text-gray-500">& lainnya</span>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty State when no tickets selected -->
                <div class="text-center py-12 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-300">
                    <div class="text-4xl mb-4">🎟️</div>
                    <p class="text-lg font-medium text-gray-600 mb-2">Pilih tiket untuk melanjutkan</p>
                    <p class="text-sm text-gray-500">Klik tombol + untuk menambah tiket ke keranjang</p>
                </div>
            @endif
        @endif
    </div>
    
    <!-- Success Animation Modal -->
    <div x-show="showSuccessAnimation"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-90"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-90"
         @click.away="showSuccessAnimation = false"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
         style="display: none;">
        <div class="bg-white rounded-2xl p-8 shadow-2xl max-w-md mx-4 text-center">
            <div class="text-6xl mb-4 animate-bounce">🎉</div>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">Berhasil!</h3>
            <p class="text-gray-600 mb-6">Tiket berhasil ditambahkan ke keranjang. Anda akan diarahkan ke halaman pembayaran.</p>
            <button @click="showSuccessAnimation = false" class="btn-primary">
                Tutup
            </button>
        </div>
    </div>
    
    <!-- Floating notifications for stock updates -->
    <div x-data="{ showStockAlert: false }" class="fixed bottom-4 right-4 z-40">
        <div x-show="showStockAlert"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-y-full opacity-0"
             x-transition:enter-end="translate-y-0 opacity-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-y-0 opacity-100"
             x-transition:leave-end="translate-y-full opacity-0"
             class="bg-orange-500 text-white p-4 rounded-xl shadow-xl max-w-sm"
             style="display: none;">
            <div class="flex items-center">
                <span class="text-2xl mr-3">⚠️</span>
                <div>
                    <p class="font-bold">Stok Terbatas!</p>
                    <p class="text-sm">Tiket ini hampir habis, pesan sekarang!</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endif

@if($step === 'attendees')
<div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
    <div class="text-center mb-8">
        <h3 class="text-2xl font-display font-bold text-gray-800">👥 Isi Data Peserta</h3>
        <p class="text-gray-600">Masukkan nama dan nomor HP untuk setiap tiket yang dibeli</p>
    </div>

    @foreach($tickets as $ticket)
        @php $qty = (int)($quantities[$ticket->id] ?? 0); @endphp
        @if($qty > 0)
            <div class="mb-8">
                <h4 class="text-lg font-bold text-gray-800 mb-3">{{ $ticket->name }} — {{ $qty }} peserta</h4>
                <div class="space-y-4">
                    @for($i = 0; $i < $qty; $i++)
                        <div wire:key="attendee-{{ $ticket->id }}-{{ $i }}" class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 rounded-xl border border-gray-200 bg-gray-50">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Peserta #{{ $i+1 }}</label>
                                <input type="text"
                                       class="w-full rounded-xl border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                       wire:model.lazy="attendeesData.{{ $ticket->id }}.{{ $i }}.name"
                                       placeholder="Nama lengkap">
                                @error("attendeesData.$ticket->id.$i.name")
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP</label>
                                <input type="text"
                                       class="w-full rounded-xl border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                       wire:model.lazy="attendeesData.{{ $ticket->id }}.{{ $i }}.phone"
                                       placeholder="Contoh: 0812xxxxxx">
                                @error("attendeesData.$ticket->id.$i.phone")
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        @endif
    @endforeach

    <div class="flex flex-col sm:flex-row justify-between gap-3 mt-4">
        <button type="button"
                wire:click="backToSelect"
                class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl border border-gray-300 text-gray-700 bg-white hover:bg-gray-50">
            ← Kembali
        </button>
        <button type="button"
                wire:click="submitAttendees"
                class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-purple-600 to-blue-600 text-white font-semibold shadow-lg hover:opacity-95">
            Lanjutkan ke Pembayaran →
        </button>
    </div>
</div>
@endif

@script
<script>
    // Listen for Livewire events
    Livewire.on('stock-exceeded', (event) => {
        // Show toast notification
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-red-500 text-white p-4 rounded-xl shadow-xl z-50 animate-slide-in-left';
        toast.innerHTML = `
            <div class="flex items-center">
                <span class="text-2xl mr-3">⚠️</span>
                <div>
                    <p class="font-bold">Stok Tidak Mencukupi!</p>
                    <p class="text-sm">${event.message}</p>
                </div>
            </div>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 5000);
    });
    
    Livewire.on('error', (event) => {
        alert('Error: ' + event.message);
    });
</script>
@endscript