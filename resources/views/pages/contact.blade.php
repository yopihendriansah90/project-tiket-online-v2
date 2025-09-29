@extends('layouts.app')

@php
$title = 'Hubungi Kami';
@endphp

@section('content')

<!-- Page Header -->
<div class="bg-white shadow-sm">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12 text-center">
        <h1 class="text-4xl font-bold tracking-tight text-gray-900">Punya Pertanyaan?</h1>
        <p class="mt-4 text-lg text-gray-600">Kami siap membantu Anda. Hubungi kami melalui formulir di bawah atau lihat jawaban dari pertanyaan umum.</p>
    </div>
</div>

<!-- Main Content -->
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-16">

    <div class="grid md:grid-cols-2 gap-16">
        <!-- Contact Form Section -->
        <div>
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Kirim Pesan</h2>
            <form action="#" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <div class="mt-1">
                        <input type="text" name="name" id="name" autocomplete="name" class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700">Pesan Anda</label>
                    <div class="mt-1">
                        <textarea id="message" name="message" rows="4" class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>
                </div>
                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Kirim Pesan
                    </button>
                </div>
            </form>
        </div>

        <!-- FAQ Section -->
        <div>
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Pertanyaan Umum (FAQ)</h2>
            <div class="space-y-4">
                <!-- FAQ 1 -->
                <details class="p-4 border rounded-lg bg-gray-50" open>
                    <summary class="font-semibold cursor-pointer">Bagaimana cara membeli tiket?</summary>
                    <p class="mt-2 text-gray-600">
                        Pilih event yang Anda inginkan, klik tombol "Pesan Tiket", pilih jumlah tiket, lalu lanjutkan ke pembayaran. E-tiket akan dikirimkan ke email Anda setelah pembayaran berhasil.
                    </p>
                </details>
                <!-- FAQ 2 -->
                <details class="p-4 border rounded-lg bg-gray-50">
                    <summary class="font-semibold cursor-pointer">Metode pembayaran apa saja yang diterima?</summary>
                    <p class="mt-2 text-gray-600">
                        Kami menerima berbagai metode pembayaran, termasuk transfer bank, kartu kredit (Visa, MasterCard), dan dompet digital (GoPay, OVO, Dana).
                    </p>
                </details>
                <!-- FAQ 3 -->
                <details class="p-4 border rounded-lg bg-gray-50">
                    <summary class="font-semibold cursor-pointer">Apakah saya bisa melakukan refund tiket?</summary>
                    <p class="mt-2 text-gray-600">
                        Kebijakan refund bergantung pada masing-masing penyelenggara event. Silakan cek syarat dan ketentuan yang tertera pada halaman detail event sebelum membeli.
                    </p>
                </details>
                <!-- FAQ 4 -->
                <details class="p-4 border rounded-lg bg-gray-50">
                    <summary class="font-semibold cursor-pointer">Di mana saya bisa melihat tiket yang sudah saya beli?</summary>
                    <p class="mt-2 text-gray-600">
                        Jika Anda memiliki akun, Anda bisa masuk dan melihat semua riwayat pembelian tiket Anda di halaman "Tiket Saya". Selain itu, e-tiket juga selalu kami kirimkan ke alamat email yang Anda gunakan saat transaksi.
                    </p>
                </details>
            </div>
        </div>
    </div>

</div>

@endsection
