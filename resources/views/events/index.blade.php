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
                        <a href="#events-section" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Jelajahi Event</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Section -->
<div id="events-section" class="container mx-auto px-4 sm:px-6 lg:px-8 py-16">
    
    <!-- Section Header -->
    <header class="text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-bold tracking-tight text-gray-900">Event Terbaru</h2>
    </header>

    <!-- Search and Filter Controls -->
    <div class="mb-8">
        <form action="{{ route('events.index') }}" method="GET">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="relative flex-grow">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="text" name="search" id="search" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Cari nama event..." value="{{ request('search') }}">
                </div>
                <div class="flex items-center gap-4">
                    <select id="sort" name="sort" class="block w-full md:w-auto pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="latest" @selected(request('sort') == 'latest')>Terbaru</option>
                        <option value="soonest" @selected(request('sort') == 'soonest')>Tanggal Terdekat</option>
                        <option value="cheapest" @selected(request('sort') == 'cheapest')>Harga Termurah</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Filter</button>
                </div>
            </div>
        </form>
    </div>

    @if($events->isEmpty())
        <div class="text-center py-20 px-6 bg-white rounded-lg shadow-md">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2z" />
            </svg>
            <h2 class="mt-4 text-xl font-semibold text-gray-800">Tidak Ada Event Ditemukan</h2>
            <p class="mt-2 text-gray-500">Coba ubah kata kunci pencarian Anda atau hapus filter untuk melihat lebih banyak hasil.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @foreach($events as $event)
                <div class="group bg-white rounded-xl shadow-md overflow-hidden flex flex-col h-full transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <a href="{{ route('events.show', $event) }}" class="flex flex-col flex-grow">
                        <div class="relative">
                            <img class="h-56 w-full object-cover" src="{{ $event->getFirstMediaUrl('event_posters') }}" alt="Poster {{ $event->title }}">
                            <div class="absolute inset-0 bg-black bg-opacity-20 group-hover:bg-opacity-10 transition-all duration-300"></div>
                        </div>
                        <div class="p-5 flex flex-col flex-grow">
                            <p class="text-sm font-semibold text-indigo-600 uppercase tracking-wider">{{ $event->start_date->format('d M Y') }}</p>
                            <h2 class="text-xl font-bold text-gray-900 mt-1 truncate">{{ $event->title }}</h2>
                            
                            <div class="flex items-center mt-3 text-gray-600">
                                <svg class="h-5 w-5 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span class="text-sm truncate">{{ $event->location }}</span>
                            </div>

                            <!-- Price Info -->
                            @if($event->tickets->count() > 0)
                            <div class="flex items-center mt-4 text-gray-800">
                                <svg class="h-5 w-5 mr-2 flex-shrink-0 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM12.38 5.62a1 1 0 00-1.41 0L10 6.59 8.03 4.62a1 1 0 00-1.41 1.41L8.59 8 6.62 9.97a1 1 0 001.41 1.41L10 9.41l1.97 1.97a1 1 0 001.41-1.41L11.41 8l1.97-1.97a1 1 0 000-1.41z" />
                                </svg>
                                <span class="text-sm font-semibold">Mulai dari <span class="font-bold text-green-600">Rp {{ number_format($event->tickets->min('price'), 0, ',', '.') }}</span></span>
                            </div>
                            @endif

                            <!-- Spacer -->
                            <div class="flex-grow"></div>

                            <!-- CTA Button -->
                            <div class="mt-6">
                                <div class="w-full text-center px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow-md transition-all duration-300 group-hover:bg-indigo-700">
                                    Lihat Detail
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <div class="mt-12">
            {{ $events->appends(request()->query())->links() }} 
        </div>
    @endif
</div>

@endsection
