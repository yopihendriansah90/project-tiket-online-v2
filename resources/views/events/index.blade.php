@extends('layouts.app')

@php
$title = 'Semua Event';
@endphp

@section('content')

<!-- Main Content Section -->
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-16">
    
    <!-- Section Header -->
    <header class="text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-gray-900">Semua Event</h1>
        <p class="mt-3 max-w-2xl mx-auto text-lg text-gray-500">
            Gunakan filter untuk menemukan event yang paling sesuai untuk Anda.
        </p>
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
                @include('events.partials.card', ['event' => $event])
            @endforeach
        </div>

        <div class="mt-12">
            {{ $events->appends(request()->query())->links() }} 
        </div>
    @endif
</div>

@endsection