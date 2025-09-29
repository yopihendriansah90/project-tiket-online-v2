<div>
    <div class="bg-white rounded-lg shadow-md p-6">
        @if($tickets->isEmpty())
            <p class="text-center text-gray-500">Tiket untuk event ini belum tersedia.</p>
        @else
            <div class="space-y-4">
                @foreach($tickets as $ticket)
                    <div class="flex items-center justify-between p-3 border rounded-lg {{ $quantities[$ticket->id] > 0 ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                        <div>
                            <h4 class="text-base font-bold text-gray-800">{{ $ticket->name }}</h4>
                            <p class="text-lg font-semibold text-blue-600">Rp {{ number_format($ticket->price, 0, ',', '.') }}</p>
                            <p class="text-sm text-gray-500">Sisa tiket: {{ $ticket->quantity }}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button wire:click="decrement({{ $ticket->id }})" 
                                    class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-200 text-gray-600 hover:bg-gray-300 disabled:opacity-50"
                                    {{ $quantities[$ticket->id] == 0 ? 'disabled' : '' }}>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                            </button>
                            <span class="text-lg font-bold w-5 text-center">{{ $quantities[$ticket->id] }}</span>
                            <button wire:click="increment({{ $ticket->id }})" 
                                    class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-200 text-gray-600 hover:bg-gray-300 disabled:opacity-50"
                                    {{ $quantities[$ticket->id] >= $ticket->quantity ? 'disabled' : '' }}>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($totalPrice > 0)
                <div class="mt-4 pt-4 border-t-2 border-dashed">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-lg font-semibold text-gray-700">Total Harga:</span>
                        <span class="text-2xl font-bold text-blue-600">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>
                    <button class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-300 text-base">
                        Pesan Sekarang
                    </button>
                </div>
            @endif
        @endif
    </div>
</div>