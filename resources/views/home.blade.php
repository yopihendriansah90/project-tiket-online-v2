@extends('layouts.app')

@section('content')

<!-- Hero Section -->
<div class="bg-white">
    <div class="relative isolate pt-14">
        <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80" aria-hidden="true">
            <div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
        </div>
        <div class="py-24 sm:py-32">
            <div class="container mx-auto px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl">Temukan Pengalaman Tak Terlupakan Anda</h1>
                    <p class="mt-6 text-lg leading-8 text-gray-600">Pesan tiket untuk konser musik, seminar, pertunjukan teater, dan ribuan event menarik lainnya di seluruh Indonesia.</p>
                    <div class="mt-10 flex items-center justify-center gap-x-6">
                        <a href="{{ route('events.index') }}" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Jelajahi Semua Event</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Featured Events Section -->
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-16">

    <!-- Popular Events -->
    <div class="mb-16">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900">Event Terpopuler</h2>
            <a href="{{ route('events.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500">Lihat Semua &rarr;</a>
        </div>
        @if($popularEvents->isEmpty())
            <p class="text-gray-500">Belum ada event populer saat ini.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($popularEvents as $event)
                    @include('events.partials.card', ['event' => $event])
                @endforeach
            </div>
        @endif
    </div>

    <!-- Latest Events -->
    <div>
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900">Event Terbaru</h2>
            <a href="{{ route('events.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500">Lihat Semua &rarr;</a>
        </div>
        @if($latestEvents->isEmpty())
            <p class="text-gray-500">Belum ada event terbaru saat ini.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($latestEvents as $event)
                    @include('events.partials.card', ['event' => $event])
                @endforeach
            </div>
        @endif
    </div>

</div>

@endsection
