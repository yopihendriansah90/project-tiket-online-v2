<x-app-layout>
    @php
        $title = 'Status Pembayaran - ' . ($order->invoice_number ?? 'Order');
        $poster = fn($evt) => $evt->getFirstMediaUrl('event_posters') ?: ('https://via.placeholder.com/400x250/6366f1/ffffff?text=🎭+' . urlencode($evt->title));
    @endphp

    <!-- Breadcrumb -->
    <nav class="bg-white py-4 border-b border-gray-200">
        <div class="container mx-auto px-4">
            <div class="flex items-center space-x-2 text-sm">
                <a href="{{ route('home') }}" class="text-purple-600 hover:text-purple-700">Home</a>
                <span class="text-gray-400">→</span>
                <a href="{{ route('events.index') }}" class="text-purple-600 hover:text-purple-700">Events</a>
                <span class="text-gray-400">→</span>
                <span class="text-gray-600">Status Pembayaran</span>
                <span class="text-gray-400">→</span>
                <span class="text-gray-600">{{ $order->invoice_number }}</span>
            </div>
        </div>
    </nav>

    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-purple-50">
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-6xl mx-auto">
                @if(session('success'))
                    <div class="mb-6 rounded-xl border border-green-200 bg-green-50 text-green-800 px-4 py-3 text-sm">
                        ✅ {{ session('success') }}
                    </div>
                @endif

                <!-- Hero Notice -->
                <div class="bg-white rounded-2xl shadow-xl border border-purple-100 overflow-hidden">
                    <div class="p-6 sm:p-8">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-blue-500 text-white flex items-center justify-center text-2xl">💳</div>
                            <div class="flex-1">
                                <h1 class="text-2xl sm:text-3xl font-display font-extrabold text-gray-900">Bukti Transfer Dikirim</h1>
                                <p class="text-gray-600 mt-2">
                                    Terima kasih! Bukti transfer Anda telah kami terima dan saat ini menunggu verifikasi admin.
                                    Proses verifikasi maksimal 1x24 jam. Notifikasi akan dikirim setelah verifikasi selesai.
                                </p>
                            </div>
                        </div>

                        <!-- Ringkasan Pembayaran -->
                        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-2">
                                <div class="bg-gradient-to-br from-purple-50 to-blue-50 rounded-xl border border-purple-100 p-5 sm:p-6">
                                    <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                                        <span class="mr-2">🧾</span> Ringkasan Order
                                    </h2>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                                        <div class="flex items-center justify-between">
                                            <span class="text-gray-600">Invoice</span>
                                            <span class="font-semibold">{{ $order->invoice_number }}</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-gray-600">Total</span>
                                            <span class="font-semibold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-gray-600">Status Order</span>
                                            <span class="font-semibold capitalize">{{ $order->status }}</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-gray-600">Metode</span>
                                            <span class="font-semibold">{{ $order->payment_method === 'manual_transfer' ? 'Transfer Manual' : ($order->payment_method ?? '-') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="bg-white rounded-xl border border-gray-200 p-5">
                                    <h3 class="text-base font-semibold text-gray-800 mb-3 flex items-center"><span class="mr-2">🧩</span>Status Pembayaran</h3>
                                    @if($latestPayment)
                                        <div class="space-y-2 text-sm">
                                            <div class="flex items-center justify-between">
                                                <span class="text-gray-600">Metode</span>
                                                <span class="font-semibold">{{ $latestPayment->channel_label }}</span>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-gray-600">Nominal</span>
                                                <span class="font-semibold">Rp {{ number_format($latestPayment->amount, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-gray-600">Waktu Upload</span>
                                                <span class="font-semibold">{{ $latestPayment->created_at->format('d M Y, H:i') }}</span>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-gray-600">Status</span>
                                                <span class="font-semibold capitalize">{{ $latestPayment->status }}</span>
                                            </div>
                                        </div>

                                        @php $proofUrl = method_exists($latestPayment, 'proof_url') ? $latestPayment->proof_url : null; @endphp
                                        @if($proofUrl)
                                            <div class="mt-4">
                                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Bukti Transfer:</h4>
                                                <a href="{{ $proofUrl }}" target="_blank" class="block">
                                                    <img src="{{ $proofUrl }}" alt="Bukti Transfer" class="w-full rounded-lg border object-cover max-h-56 hover:opacity-95 transition">
                                                </a>
                                                <p class="text-xs text-gray-500 mt-1">Klik gambar untuk melihat ukuran penuh.</p>
                                            </div>
                                        @endif
                                    @else
                                        <p class="text-sm text-gray-600">Belum ada data pembayaran tercatat untuk order ini.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rekomendasi Event Lain -->
                <div class="mt-12">
                    <h2 class="text-2xl font-display font-bold text-gray-900 mb-6 text-center">✨ Rekomendasi Event Lain</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @forelse($recommended as $rec)
                            <div class="bg-white rounded-2xl shadow-lg overflow-hidden card-hover border border-gray-100">
                                <div class="relative">
                                    <img class="w-full h-40 object-cover" src="{{ $poster($rec) }}" alt="Poster {{ $rec->title }}">
                                    <div class="absolute top-2 right-2 bg-white/90 text-purple-600 px-2 py-1 rounded-full text-xs font-bold">
                                        📅 {{ \Illuminate\Support\Carbon::parse($rec->start_date)->format('d M') }}
                                    </div>
                                </div>
                                <div class="p-4">
                                    <h4 class="font-bold text-gray-800 mb-2 truncate">{{ $rec->title }}</h4>
                                    <p class="text-sm text-gray-600 mb-3 truncate">{{ $rec->location }}</p>
                                    <div class="flex justify-between items-center">
                                        <span class="text-purple-600 font-medium text-sm">
                                            {{ $rec->is_online ? '🌐 Online' : '📍 Offline' }}
                                        </span>
                                        <a href="{{ route('events.show', $rec->slug) }}" class="text-purple-600 hover:text-purple-700 font-medium text-sm">
                                            Lihat →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full">
                                <div class="bg-white rounded-xl border p-6 text-center text-gray-600">
                                    Belum ada rekomendasi tersedia saat ini.
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>