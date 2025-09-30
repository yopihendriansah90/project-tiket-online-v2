@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="relative md:grid md:grid-cols-5">
                <!-- Poster Section (Left) -->
                <div class="md:col-span-2 md:sticky md:top-0">
                    <div class="relative w-full" style="padding-top: 125%;"> <!-- 5:4 ratio -->
                        <div class="absolute top-0 left-0 w-full h-full bg-cover bg-center" style="background-image: url('{{ $event->getFirstMediaUrl('event_posters') }}');">
                        </div>
                    </div>
                </div>

                <!-- Info Section (Right) -->
                <div class="md:col-span-3">
                    <div class="p-8">
                        <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $event->title }}</h1>
                        <p class="text-gray-600 mb-6">Diselenggarakan oleh: {{ $event->user->name ?? 'Panitia' }}</p>
                        
                        <!-- Details -->
                        <div class="bg-gray-100 p-6 rounded-lg mb-6">
                            <h3 class="text-xl font-bold mb-4">Detail</h3>
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <svg class="h-6 w-6 text-gray-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>{{ $event->start_date->format('d M Y, H:i') }} - {{ $event->end_date->format('d M Y, H:i') }}</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="h-6 w-6 text-gray-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span>{{ $event->location }}</span>
                                </div>
                            </div>
                        </div>

                        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Deskripsi Event</h2>
                        <div class="prose max-w-none text-gray-700 mb-8">
                            {!! $event->description !!}
                        </div>

                        <!-- Ticket Selection Component -->
                        <div>
                            <h2 class="text-2xl font-bold text-center text-gray-900 mb-6">Pilih Tiket Anda</h2>
                            @livewire('ticket-selector', ['event' => $event])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection