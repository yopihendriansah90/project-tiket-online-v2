<div class="space-y-4">
    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-100 p-4">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
            <div class="md:col-span-3">
                <label for="searchTasks" class="block text-xs font-medium text-gray-600 mb-1">Cari Invoice</label>
                <input id="searchTasks"
                       type="text"
                       placeholder="Ketik nomor invoice..."
                       wire:model.live.debounce.500ms="search"
                       class="w-full rounded-md border-gray-300 focus:border-purple-600 focus:ring-purple-600 text-sm" />
            </div>
            <div class="md:col-span-2">
                <label for="perPageTasks" class="block text-xs font-medium text-gray-600 mb-1">Item per halaman</label>
                <select id="perPageTasks"
                        wire:model.live="perPage"
                        class="w-full rounded-md border-gray-300 focus:border-purple-600 focus:ring-purple-600 text-sm">
                    <option value="4">4</option>
                    <option value="8">8</option>
                    <option value="12">12</option>
                    <option value="16">16</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Tasks list -->
    <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-100 p-4 sm:p-6">
        @if ($orders->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-2 gap-4">
                @foreach ($orders as $order)
                    @php
                        $hasSubmittedPayment = $order->payments->contains(fn($p) => $p->status === 'submitted');
                        $badgeClasses = $hasSubmittedPayment
                            ? 'bg-indigo-50 text-indigo-700'
                            : 'bg-amber-50 text-amber-700';
                        $icon = $hasSubmittedPayment ? '🕒' : '📄';
                        $typeText = $hasSubmittedPayment ? 'Menunggu verifikasi' : 'Perlu pembayaran/unggah bukti';
                        $ctaRoute = $hasSubmittedPayment
                            ? route('payments.submitted', ['order' => $order->id])
                            : route('payments.create', ['order' => $order->id]);
                        $ctaLabel = $hasSubmittedPayment ? 'Lihat status pembayaran' : 'Lanjutkan pembayaran';
                    @endphp

                    <div class="rounded-lg ring-1 ring-gray-100 p-4 hover:shadow-sm transition">
                        <div class="flex items-start justify-between">
                            <div class="min-w-0">
                                <p class="text-xs text-gray-500">Invoice</p>
                                <p class="text-sm font-semibold text-gray-800 truncate" aria-label="Nomor invoice">
                                    {{ $order->invoice_number ?? 'N/A' }}
                                </p>
                                <p class="mt-2 inline-flex items-center text-xs font-medium px-2.5 py-1 rounded-lg {{ $badgeClasses }}">
                                    <span class="mr-1">{{ $icon }}</span>
                                    {{ $typeText }}
                                </p>
                            </div>
                            <div class="ml-3">
                                <a href="{{ $ctaRoute }}"
                                   class="inline-flex items-center px-3 py-2 rounded-md text-sm font-semibold text-white bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-600"
                                   aria-label="{{ $ctaLabel }} untuk invoice {{ $order->invoice_number ?? '' }}">
                                    <span class="mr-1">➡️</span>
                                    {{ $ctaLabel }}
                                </a>
                            </div>
                        </div>

                        <div class="mt-3 flex items-center text-xs text-gray-500">
                            <span class="mr-2">ID Pesanan:</span>
                            <span class="font-medium text-gray-700">{{ $order->id }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        @else
            <div class="flex items-center justify-between rounded-lg border border-dashed border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center mr-3">
                        <span class="text-gray-500">✅</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">Tidak ada tugas saat ini</p>
                        <p class="text-xs text-gray-600">Semua pesanan dan pembayaranmu dalam keadaan aman.</p>
                    </div>
                </div>
                <a href="{{ route('events.index') }}"
                   class="text-sm font-semibold text-purple-600 hover:text-purple-700">
                    Cari Event
                </a>
            </div>
        @endif
    </div>
</div>