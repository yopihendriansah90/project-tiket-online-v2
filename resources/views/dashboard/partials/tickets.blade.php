<div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-100">
    <div class="px-4 sm:px-6 py-4 border-b border-gray-100">
        <h3 class="text-base font-semibold text-gray-800" id="upcoming-title">Tiket Mendatang</h3>
        <p class="mt-1 text-sm text-gray-600">Lihat event yang akan kamu hadiri dan akses e-ticket.</p>
    </div>

    <div class="p-4 sm:p-6">
        @if (!empty($upcomingEvents) && count($upcomingEvents) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-2 gap-4">
                @foreach ($upcomingEvents as $event)
                    <div class="rounded-lg ring-1 ring-gray-100 p-4 hover:shadow-sm transition">
                        <div class="flex items-start justify-between">
                            <div class="min-w-0">
                                <p class="text-xs text-gray-500">{{ $event->is_online ? 'Online' : 'Offline' }}</p>
                                <h4 class="text-sm font-semibold text-gray-800 truncate" aria-label="Judul event">{{ $event->title }}</h4>
                                <p class="mt-1 text-xs text-gray-600">{{ $event->location }}</p>
                                <p class="mt-1 text-xs text-gray-600">
                                    {{ \Carbon\Carbon::parse($event->start_date)->translatedFormat('d M Y H:i') }}
                                    &ndash;
                                    {{ \Carbon\Carbon::parse($event->end_date)->translatedFormat('d M Y H:i') }}
                                </p>
                            </div>
                            <div class="ml-3">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium {{ $event->is_online ? 'bg-blue-50 text-blue-700' : 'bg-emerald-50 text-emerald-700' }}">
                                    {{ $event->is_online ? 'Online' : 'Offline' }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-3 flex items-center gap-2">
                            <a href="{{ route('events.show', $event->slug) }}"
                               class="inline-flex items-center px-3 py-2 rounded-md text-sm font-semibold text-white bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-600"
                               aria-label="Lihat detail event {{ $event->title }}">
                                <span class="mr-1">🔍</span>
                                Lihat Detail
                            </a>
                            <a href="{{ route('user.tickets') }}"
                               class="inline-flex items-center px-3 py-2 rounded-md text-sm font-semibold text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-600"
                               aria-label="Kelola E-ticket kamu">
                                <span class="mr-1">📥</span>
                                Kelola E-ticket
                            </a>
                        </div>
                    </div>
                @endforeach
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