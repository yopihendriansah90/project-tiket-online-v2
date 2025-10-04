<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Dashboard') }}
                </h2>
                <p class="text-sm text-gray-600">Hai, {{ $user->name }} — kelola pesanan dan tiketmu di sini.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('events.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold text-white bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 transition">
                    Cari Event
                </a>
                <a href="{{ route('profile.edit') }}" class="px-3 py-2 text-sm font-medium text-purple-600 border border-purple-600 rounded-lg hover:bg-purple-50 transition">
                    Pengaturan
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Grid utama -->
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
                <!-- Kolom konten -->
                <div class="xl:col-span-8 space-y-6">
                    <!-- KPI Stats -->
                    @include('dashboard.partials.stats')

                    <!-- Tabs -->
                    <div x-data="{ tab: '{{ request('tab', 'overview') }}' }" class="bg-white rounded-lg shadow">
                        <div class="border-b border-gray-200 px-4 sm:px-6">
                            <nav class="-mb-px flex space-x-6 overflow-x-auto" aria-label="Tabs">
                                <button type="button"
                                        @click="tab = 'overview'"
                                        :class="tab === 'overview' ? 'border-purple-600 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                    Overview
                                </button>
                                <button type="button"
                                        @click="tab = 'orders'"
                                        :class="tab === 'orders' ? 'border-purple-600 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                    Pesanan
                                </button>
                                <button type="button"
                                        @click="tab = 'tickets'"
                                        :class="tab === 'tickets' ? 'border-purple-600 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                    Tiket Saya
                                </button>
                                <button type="button"
                                        @click="tab = 'payments'"
                                        :class="tab === 'payments' ? 'border-purple-600 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                    Pembayaran
                                </button>
                                <button type="button"
                                        @click="tab = 'notifications'"
                                        :class="tab === 'notifications' ? 'border-purple-600 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                    Notifikasi
                                </button>
                                <button type="button"
                                        @click="tab = 'favorites'"
                                        :class="tab === 'favorites' ? 'border-purple-600 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                    Favorit
                                </button>
                            </nav>
                        </div>

                        <div class="p-6 space-y-6">
                            <!-- Overview -->
                            <section x-show="tab === 'overview'" x-cloak>
                                @include('dashboard.partials.tasks')
                                @include('dashboard.partials.tickets')
                                @include('dashboard.partials.orders')
                                @include('dashboard.partials.recommended')

                            </section>

                            <!-- Pesanan -->
                            <section x-show="tab === 'orders'" x-cloak>
                                @livewire('orders-table')
                            </section>

                            <!-- Tiket Saya -->
                            <section x-show="tab === 'tickets'" x-cloak>
                                @livewire('tickets-list')
                            </section>

                            <!-- Pembayaran -->
                            <section x-show="tab === 'payments'" x-cloak>
                                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                    <div class="p-6 text-gray-700">
                                        <p class="mb-3">Status & riwayat pembayaran akan muncul di sini.</p>
                                        {{-- Livewire komponen pembayaran --}}
                                    </div>
                                </div>
                            </section>

                            <!-- Notifikasi -->
                            <section x-show="tab === 'notifications'" x-cloak>
                                @livewire('tasks-list')
                            </section>

                            <!-- Favorit -->
                            <section x-show="tab === 'favorites'" x-cloak>
                                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                    <div class="p-6 text-gray-700">
                                        <p class="mb-3">Event favorit akan muncul di sini.</p>
                                        {{-- Placeholder untuk daftar event diikuti/favorit --}}
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <aside class="xl:col-span-4">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $user->email }}</p>
                                </div>
                            </div>
                            <div class="mt-4 grid grid-cols-2 gap-3">
                                <a href="{{ route('profile.edit') }}" class="px-3 py-2 text-sm font-medium text-purple-600 border border-purple-600 rounded-lg hover:bg-purple-50 transition">
                                    Profil
                                </a>
                                <a href="{{ route('events.index') }}" class="px-3 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 transition">
                                    Jelajahi Event
                                </a>
                            </div>
                            <hr class="my-4">
                            <ul class="space-y-2 text-sm text-gray-700">
                                <li>Pesanan pending: <span class="font-semibold text-purple-700">{{ $stats['pending'] }}</span></li>
                                <li>Pesanan paid: <span class="font-semibold text-purple-700">{{ $stats['paid'] }}</span></li>
                                <li>Tiket aktif: <span class="font-semibold text-purple-700">{{ $stats['active_tickets'] }}</span></li>
                                <li>Tugas: <span class="font-semibold text-purple-700">{{ $stats['tasks'] }}</span></li>
                            </ul>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>

</x-app-layout>
