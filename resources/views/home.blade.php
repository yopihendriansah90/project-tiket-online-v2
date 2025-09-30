@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="bg-gray-900 text-white">
        <div class="container mx-auto px-4 py-32 text-center">
            <h1 class="text-4xl md:text-6xl font-bold leading-tight mb-4">Temukan dan Pesan Tiket Event Anda</h1>
            <p class="text-lg md:text-xl mb-8">Jelajahi berbagai event menarik, dari konser musik hingga seminar, semua dalam satu tempat.</p>
            <a href="#latest-events" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-8 rounded-full">Lihat Event</a>
        </div>
    </section>

    <!-- Latest Events Section -->
    <section id="latest-events" class="py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Event Terbaru</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @forelse ($latestEvents as $event)
                    @include('events.partials.card', ['event' => $event])
                @empty
                    <p class="text-center col-span-4">Tidak ada event terbaru saat ini.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Popular Events Section -->
    <section class="bg-gray-100 py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Event Terpopuler</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @forelse ($popularEvents as $event)
                    @include('events.partials.card', ['event' => $event])
                @empty
                    <p class="text-center col-span-4">Tidak ada event populer saat ini.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="bg-blue-700 text-white">
        <div class="container mx-auto px-4 py-20 text-center">
            <h2 class="text-3xl font-bold mb-4">Siap untuk Memulai?</h2>
            <p class="text-xl mb-8">Daftar sekarang dan jangan lewatkan event favorit Anda.</p>
            <a href="{{ route('filament.admin.auth.login') }}" class="bg-white text-blue-700 font-bold py-3 px-8 rounded-full hover:bg-gray-200">Login Sekarang</a>
        </div>
    </section>
@endsection