<div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-100">
    <div class="px-4 sm:px-6 py-4 border-b border-gray-100">
        <h3 class="text-base font-semibold text-gray-800" id="tasks-title">Tugas & Notifikasi</h3>
        <p class="mt-1 text-sm text-gray-600">Prioritaskan penyelesaian pembayaran dan unggah bukti untuk pesanan pending.</p>
    </div>

    <div class="p-4 sm:p-6">
        @if (!empty($tasks) && count($tasks) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($tasks as $task)
                    @php
                        $isWaitingVerify = ($task['type'] ?? '') === 'Menunggu verifikasi';
                        $badgeClasses = $isWaitingVerify
                            ? 'bg-indigo-50 text-indigo-700'
                            : 'bg-amber-50 text-amber-700';
                        $icon = $isWaitingVerify ? '🕒' : '📄';
                    @endphp

                    <div class="rounded-lg ring-1 ring-gray-100 p-4 hover:shadow-sm transition">
                        <div class="flex items-start justify-between">
                            <div class="min-w-0">
                                <p class="text-xs text-gray-500">Invoice</p>
                                <p class="text-sm font-semibold text-gray-800 truncate" aria-label="Nomor invoice">
                                    {{ $task['invoice'] ?? 'N/A' }}
                                </p>
                                <p class="mt-2 inline-flex items-center text-xs font-medium px-2.5 py-1 rounded-lg {{ $badgeClasses }}">
                                    <span class="mr-1">{{ $icon }}</span>
                                    {{ $task['type'] ?? 'Tindakan diperlukan' }}
                                </p>
                            </div>
                            <div class="ml-3">
                                <a href="{{ $task['cta_route'] ?? '#' }}"
                                   class="inline-flex items-center px-3 py-2 rounded-md text-sm font-semibold text-white bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-600"
                                   aria-label="{{ ($task['cta_label'] ?? 'Lanjutkan') }} untuk invoice {{ $task['invoice'] ?? '' }}">
                                    <span class="mr-1">➡️</span>
                                    {{ $task['cta_label'] ?? 'Lanjutkan' }}
                                </a>
                            </div>
                        </div>

                        <div class="mt-3 flex items-center text-xs text-gray-500">
                            <span class="mr-2">ID Pesanan:</span>
                            <span class="font-medium text-gray-700">{{ $task['order_id'] ?? '-' }}</span>
                        </div>
                    </div>
                @endforeach
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