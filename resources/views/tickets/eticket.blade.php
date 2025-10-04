<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          E-ticket
        </h2>
        <p class="text-sm text-gray-600">Tunjukkan QR ini saat masuk venue atau check-in online.</p>
      </div>
      <div class="flex items-center gap-2">
        <a href="{{ route('user.tickets') }}" class="px-3 py-2 text-sm font-medium text-purple-600 border border-purple-600 rounded-lg hover:bg-purple-50 transition">
          Kembali ke Tiket Saya
        </a>
        <button type="button" onclick="window.print()" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold text-white bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 transition">
          Cetak / Simpan PDF
        </button>
      </div>
    </div>
  </x-slot>

  <div class="py-8">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white shadow-sm sm:rounded-lg">
        <div class="p-6 space-y-6">
          <div class="flex items-start justify-between">
            <div class="min-w-0">
              <h3 class="text-lg font-semibold text-gray-900">{{ $event->title ?? 'Event' }}</h3>
              <p class="mt-1 text-sm text-gray-600">{{ $event->location ?? '-' }}</p>
              @if(!empty($event?->start_date) && !empty($event?->end_date))
              <p class="mt-1 text-sm text-gray-600">
                {{ \Carbon\Carbon::parse($event->start_date)->translatedFormat('d M Y H:i') }}
                &ndash;
                {{ \Carbon\Carbon::parse($event->end_date)->translatedFormat('d M Y H:i') }}
              </p>
              @endif
            </div>
            <div class="ml-3">
              @php $badgeClasses = ($event && $event->is_online) ? 'bg-blue-50 text-blue-700' : 'bg-emerald-50 text-emerald-700'; @endphp
              <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium {{ $badgeClasses }}">
                {{ ($event && $event->is_online) ? 'Online' : 'Offline' }}
              </span>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-3">
              <div>
                <h4 class="text-sm font-semibold text-gray-800">Info Order</h4>
                <ul class="mt-1 text-sm text-gray-700">
                  <li>Invoice: <span class="font-medium">{{ $orderItem->order->invoice_number ?? ('#' . $orderItem->order->id) }}</span></li>
                  <li>Order ID: <span class="font-medium">{{ $orderItem->order->id }}</span></li>
                  <li>Atas Nama: <span class="font-medium">{{ $user->name }}</span> ({{ $user->email }})</li>
                </ul>
              </div>
              <div>
                <h4 class="text-sm font-semibold text-gray-800">Detail Tiket</h4>
                <ul class="mt-1 text-sm text-gray-700">
                  <li>Jenis Tiket: <span class="font-medium">{{ $ticket->name ?? $orderItem->ticket_name }}</span></li>
                  <li>Jumlah: <span class="font-medium">{{ $orderItem->quantity }}</span></li>
                  @if(!empty($ticket?->seat_area))
                  <li>Area Kursi: <span class="font-medium">{{ $ticket->seat_area }}</span></li>
                  @endif
                </ul>
              </div>
              <div>
                <h4 class="text-sm font-semibold text-gray-800">Peserta</h4>
                @if($attendees->count() > 0)
                <ul class="mt-1 text-sm text-gray-700 space-y-1">
                  @foreach($attendees as $a)
                  <li class="flex justify-between">
                    <span>{{ $a->name }} ({{ $a->email }})</span>
                    <span class="font-medium">{{ $a->seat_display }}</span>
                  </li>
                  @endforeach
                </ul>
                @else
                <p class="mt-1 text-sm text-gray-600">General Admission — kursi tidak bernomor.</p>
                @endif
              </div>
            </div>
            <div class="flex flex-col items-center justify-center">
              <div class="rounded-lg ring-1 ring-gray-200 p-4">
                <img src="{{ $qrUrl }}" alt="QR E-ticket" class="w-60 h-60 object-contain" />
              </div>
              <p class="mt-2 text-xs text-gray-500">Data QR: <span class="font-mono break-all">{{ $qrData }}</span></p>
              <p class="mt-1 text-xs text-gray-500">Jangan bagikan QR kepada orang lain.</p>
            </div>
          </div>

          <div class="mt-6 rounded-lg bg-purple-50 p-4">
            <p class="text-xs text-gray-700">
              Catatan: Bawalah identitas yang sesuai dengan nama pada tiket. Untuk event offline, panitia berhak meminta verifikasi tambahan. Untuk event online, QR digunakan saat proses check-in/akses konten.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

</x-app-layout>