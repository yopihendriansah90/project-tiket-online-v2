@extends('layouts.app')

@php
$title = 'Tentang Kami';
@endphp

@section('content')

<!-- Page Header -->
<div class="bg-white shadow-sm">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-4xl font-bold tracking-tight text-gray-900">Tentang TiketIn</h1>
        <p class="mt-4 text-lg text-gray-600">Mengenal lebih dekat misi, visi, dan tim di balik platform event terpercaya Anda.</p>
    </div>
</div>

<!-- Main Content -->
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-16">

    <!-- Mission and Vision Section -->
    <div class="grid md:grid-cols-2 gap-12 items-center">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Misi Kami</h2>
            <p class="mt-4 text-gray-600 leading-relaxed">
                Misi kami adalah untuk menghubungkan jutaan orang dengan pengalaman dan momen tak terlupakan melalui sebuah platform yang mudah diakses, aman, dan terpercaya. Kami percaya bahwa setiap event memiliki kekuatan untuk menginspirasi, dan kami berdedikasi untuk menjadi jembatan antara penyelenggara event dan para pencari pengalaman.
            </p>
            <h2 class="text-3xl font-bold text-gray-900 mt-8">Visi Kami</h2>
            <p class="mt-4 text-gray-600 leading-relaxed">
                Menjadi platform manajemen dan pemesanan tiket event nomor satu di Asia Tenggara, yang dikenal karena inovasi teknologi, pelayanan pelanggan yang luar biasa, dan kontribusi positif terhadap industri kreatif.
            </p>
        </div>
        <div class="rounded-lg overflow-hidden shadow-lg">
            <img src="https://images.unsplash.com/photo-1523580494863-6f3031224c94?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="Suasana event" class="w-full h-full object-cover">
        </div>
    </div>

    <!-- Team Section -->
    <div class="mt-24 text-center">
        <h2 class="text-3xl font-bold text-gray-900">Tim Profesional Kami</h2>
        <p class="mt-4 max-w-2xl mx-auto text-gray-600">Kami adalah sekelompok individu yang bersemangat tentang teknologi dan event.</p>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mt-12">
            <!-- Team Member 1 -->
            <div class="text-center">
                <img class="mx-auto h-32 w-32 rounded-full object-cover shadow-md" src="https://images.unsplash.com/photo-1560250097-0b93528c311a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=687&q=80" alt="Foto CEO">
                <h3 class="mt-4 text-xl font-semibold text-gray-900">Ahmad Subarjo</h3>
                <p class="text-indigo-600">Chief Executive Officer</p>
            </div>
            <!-- Team Member 2 -->
            <div class="text-center">
                <img class="mx-auto h-32 w-32 rounded-full object-cover shadow-md" src="https://images.unsplash.com/photo-1580489944761-15a19d654956?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=761&q=80" alt="Foto CTO">
                <h3 class="mt-4 text-xl font-semibold text-gray-900">Siti Nurbaya</h3>
                <p class="text-indigo-600">Chief Technology Officer</p>
            </div>
            <!-- Team Member 3 -->
            <div class="text-center">
                <img class="mx-auto h-32 w-32 rounded-full object-cover shadow-md" src="https://images.unsplash.com/photo-1599566150163-29194dcaad36?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=687&q=80" alt="Foto COO">
                <h3 class="mt-4 text-xl font-semibold text-gray-900">Budi Santoso</h3>
                <p class="text-indigo-600">Chief Operating Officer</p>
            </div>
            <!-- Team Member 4 -->
            <div class="text-center">
                <img class="mx-auto h-32 w-32 rounded-full object-cover shadow-md" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=764&q=80" alt="Foto CMO">
                <h3 class="mt-4 text-xl font-semibold text-gray-900">Rina Wulandari</h3>
                <p class="text-indigo-600">Chief Marketing Officer</p>
            </div>
        </div>
    </div>

</div>

@endsection
