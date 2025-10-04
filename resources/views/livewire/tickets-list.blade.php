<div class="space-y-4">
    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-100 p-4">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
            <div class="md:col-span-3">
                <label for="searchTickets" class="block text-xs font-medium text-gray-600 mb-1">Cari Event</label>
                <input id="searchTickets"
                       type="text"
                       placeholder="Ketik judul event..."
                       wire:model.live.debounce.500ms="search"
                       class="w-full rounded-md border-gray-300 focus:border-purple-600 focus:ring-purple-600 text-sm" />
            </div>
            <div class="md:col-span-2">
                <label for="perPageTickets" class="block text-xs font-medium text-gray-600 mb-1">Kartu per halaman</label>
                <select id="perPageTickets"
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

    <!-- Tickets grid -->
    <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-100 p-4 sm:p-6">
        @if ($items->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach ($items as $item)
                    @php
                        $ticket = optional($item->ticket);
                        $event = optional($ticket->event);
                        $order = optional($item->order);
                        $isPaid = ($order->status ?? '') === 'paid';
                        $badgeClasses = $event && $event->is_online
                            ? 'bg-blue-50 text-blue-700'
                            : 'bg-emerald-50 text-emerald-700';
                        // If attendees relation has seat info you could show it (placeholder if available)
                        $firstAttendee = $item->attendees->first();
                        $seatInfo = $firstAttendee ? $firstAttendee->seat_display : null;
                    @endphp

                    <div class="rounded-lg ring-1 ring-gray-100 p-4 hover:shadow-sm transition flex flex-col">
                        <div class="flex items-start justify-between">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <h4 class="text-sm font-semibold text-gray-800 truncate" aria-label="Judul event">
                                        {{ $event->title ?? 'Event tidak tersedia' }}
                                    </h4>
                                    @if ($event)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold {{ $badgeClasses }}">
                                            {{ $event->is_online ? 'Online' : 'Offline' }}
                                        </span>
                                    @endif
                                </div>
                                <p class="mt-1 text-xs text-gray-600 truncate">
                                    {{ $event->location ?? '-' }}
                                </p>
                                @if (!empty($event?->start_date) && !empty($event?->end_date))
                                    <p class="mt-1 text-xs text-gray-600">
                                        {{ \Carbon\Carbon::parse($event->start_date)->translatedFormat('d M Y H:i') }}
                                        &ndash;
                                        {{ \Carbon\Carbon::parse($event->end_date)->translatedFormat('d M Y H:i') }}
                                    </p>
                                @endif
                                @if ($seatInfo)
                                    <p class="mt-1 text-xs text-gray-600">
                                        {{ $seatInfo }}
                                    </p>
                                @endif
                                <p class="mt-1 text-xs text-gray-600">
                                    Qty: <span class="font-medium text-gray-800">{{ $item->quantity }}</span>
                                </p>
                            </div>
                            <div class="ml-3 text-right">
                                @php
                                    $orderBadge = $isPaid ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700';
                                    $orderLabel = $isPaid ? 'Paid' : 'Pending';
                                    $orderIcon = $isPaid ? '✅' : '⏳';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-medium {{ $orderBadge }}">
                                    <span class="mr-1">{{ $orderIcon }}</span>
                                    {{ $orderLabel }}
                                </span>
                                <div class="mt-1 text-[11px] text-gray-500">Order #{{ $order->id }}</div>
                            </div>
                        </div>

                        <div class="mt-3 flex items-center gap-2">
                            @if ($event)
                                <a href="{{ route('events.show', $event->slug ?? ($event->id ?? '')) }}"
                                   class="inline-flex items-center px-3 py-2 rounded-md text-xs sm:text-sm font-semibold text-white bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-600"
                                   aria-label="Lihat detail event {{ $event->title ?? '' }}">
                                    <span class="mr-1">🔍</span>
                                    Detail Event
                                </a>
                            @endif

                            @if ($isPaid)
                                <a href="{{ route('etickets.show', ['orderItem' => $item->id]) }}"
                                   class="inline-flex items-center px-3 py-2 rounded-md text-xs sm:text-sm font-semibold text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-600"
                                   aria-label="Unduh E-ticket untuk order item {{ $item->id }}">
                                    <span class="mr-1">📥</span>
                                    Unduh E-ticket
                                </a>
                            @else
                                <a href="{{ route('payments.create', ['order' => $order->id]) }}"
                                   class="inline-flex items-center px-3 py-2 rounded-md text-xs sm:text-sm font-semibold text-white bg-amber-500 hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500"
                                   aria-label="Lanjutkan pembayaran untuk order {{ $order->id }}">
                                    <span class="mr-1">💳</span>
                                    Bayar Sekarang
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $items->links() }}
            </div>
        @else
            <div class="flex items-center justify-between rounded-lg border border-dashed border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center mr-3">
                        <span class="text-gray-500">🎫</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">Belum ada tiket mendatang</p>
                        <p class="text-xs text-gray-600">Cari event menarik dan pesan tiket sekarang.</p>
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