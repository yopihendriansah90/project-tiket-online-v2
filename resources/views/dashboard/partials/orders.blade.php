<div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-100">
    <div class="px-4 sm:px-6 py-4 border-b border-gray-100">
        <h3 class="text-base font-semibold text-gray-800" id="orders-title">Pesanan Terbaru</h3>
        <p class="mt-1 text-sm text-gray-600">Ringkasan 5 pesanan terakhir dan status pembayarannya.</p>
    </div>

    <div class="p-4 sm:p-6">
        @php
            $recentOrders = isset($orders) ? $orders->take(5) : collect();
        @endphp

        @if ($recentOrders->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                Invoice
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                Event
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                Status Pesanan
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                Status Pembayaran
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach ($recentOrders as $order)
                            @php
                                $firstItem = optional($order->items->first());
                                $ticket = optional($firstItem->ticket);
                                $event = optional($ticket->event);
                                $isPaid = ($order->status ?? '') === 'paid';
                                $hasSubmittedPayment = isset($order->payments) && $order->payments->contains(fn($p) => $p->status === 'submitted');

                                $paymentText = $isPaid
                                    ? 'Lunas'
                                    : ($hasSubmittedPayment ? 'Menunggu verifikasi' : 'Belum ada bukti');
                                $paymentBadgeClasses = $isPaid
                                    ? 'bg-emerald-50 text-emerald-700'
                                    : ($hasSubmittedPayment ? 'bg-indigo-50 text-indigo-700' : 'bg-amber-50 text-amber-700');
                            @endphp

                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-800" aria-label="Nomor invoice">
                                        {{ $order->invoice_number ?? 'N/A' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        ID: {{ $order->id }}
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm font-medium text-gray-800 truncate" aria-label="Judul event">
                                        {{ $event->title ?? 'Event tidak tersedia' }}
                                    </div>
                                    <div class="text-xs text-gray-600 truncate">
                                        {{ $event->location ?? '-' }}
                                    </div>
                                    @if (!empty($event->start_date))
                                        <div class="text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($event->start_date)->translatedFormat('d M Y') }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $orderBadge = $isPaid ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700';
                                        $orderLabel = $isPaid ? 'Paid' : 'Pending';
                                        $orderIcon = $isPaid ? '✅' : '⏳';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium {{ $orderBadge }}">
                                        <span class="mr-1">{{ $orderIcon }}</span>
                                        {{ $orderLabel }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium {{ $paymentBadgeClasses }}">
                                        @if ($isPaid)
                                            <span class="mr-1">💳</span>
                                        @elseif ($hasSubmittedPayment)
                                            <span class="mr-1">🕒</span>
                                        @else
                                            <span class="mr-1">📄</span>
                                        @endif
                                        {{ $paymentText }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        @if (!$isPaid)
                                            @if ($hasSubmittedPayment)
                                                <a href="{{ route('payments.submitted', ['order' => $order->id]) }}"
                                                   class="inline-flex items-center px-3 py-2 rounded-md text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                                                    <span class="mr-1">🔎</span>
                                                    Status Pembayaran
                                                </a>
                                            @else
                                                <a href="{{ route('payments.create', ['order' => $order->id]) }}"
                                                   class="inline-flex items-center px-3 py-2 rounded-md text-xs font-semibold text-white bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-600">
                                                    <span class="mr-1">💳</span>
                                                    Lanjutkan Pembayaran
                                                </a>
                                            @endif
                                        @endif
                                        <a href="{{ route('events.show', $event->slug ?? ($event->id ?? '')) }}"
                                           class="inline-flex items-center px-3 py-2 rounded-md text-xs font-semibold text-purple-600 border border-purple-600 hover:bg-purple-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-600">
                                            <span class="mr-1">🔍</span>
                                            Detail Event
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex items-center justify-between">
                <p class="text-xs text-gray-600">Menampilkan {{ $recentOrders->count() }} dari {{ $orders->count() }} pesanan terbaru.</p>
                <button type="button"
                        class="px-3 py-2 text-xs font-semibold text-purple-600 border border-purple-600 rounded-md hover:bg-purple-50"
                        title="Halaman daftar pesanan akan tersedia segera"
                        disabled>
                    Lihat semua pesanan
                </button>
            </div>
        @else
            <div class="flex items-center justify-between rounded-lg border border-dashed border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center mr-3">
                        <span class="text-gray-500">🧾</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">Belum ada pesanan</p>
                        <p class="text-xs text-gray-600">Mulai jelajahi event dan lakukan pemesanan.</p>
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