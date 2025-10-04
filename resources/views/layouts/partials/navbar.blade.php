<!-- Enhanced Navbar -->
<nav x-data="{ open: false }" class="bg-white/95 backdrop-blur-md shadow-lg sticky top-0 z-50 border-b border-purple-100">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Enhanced Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route('home') }}" class="flex items-center space-x-2 group">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-600 to-blue-600 rounded-xl flex items-center justify-center transform group-hover:scale-110 transition-all duration-300">
                        <span class="text-white font-bold text-xl">🎟️</span>
                    </div>
                    <span class="text-2xl font-display font-bold text-gradient">TiketIn</span>
                </a>
            </div>

            <!-- Enhanced Desktop Menu -->
            <div class="hidden lg:flex lg:items-center lg:space-x-8">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'text-purple-600' : '' }}">
                    <span class="flex items-center space-x-1">
                        <span>🏠</span>
                        <span>Home</span>
                    </span>
                </a>
                <a href="{{ route('events.index') }}" class="nav-link {{ request()->routeIs('events.*') ? 'text-purple-600' : '' }}">
                    <span class="flex items-center space-x-1">
                        <span>🎭</span>
                        <span>Events</span>
                    </span>
                </a>
                <a href="#categories" class="nav-link">
                    <span class="flex items-center space-x-1">
                        <span>📂</span>
                        <span>Kategori</span>
                    </span>
                </a>
                <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'text-purple-600' : '' }}">
                    <span class="flex items-center space-x-1">
                        <span>ℹ️</span>
                        <span>Tentang</span>
                    </span>
                </a>
                <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'text-purple-600' : '' }}">
                    <span class="flex items-center space-x-1">
                        <span>📞</span>
                        <span>Kontak</span>
                    </span>
                </a>
            </div>

            <!-- Enhanced Desktop Auth -->
            <div class="hidden lg:flex lg:items-center lg:space-x-4">
                @guest
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-purple-600 font-medium transition-all duration-300">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="btn-primary text-sm">
                        <span class="flex items-center space-x-2">
                            <span>✨</span>
                            <span>Daftar Gratis</span>
                        </span>
                    </a>
                @else
                    <!-- User Dropdown -->
                    <div x-data="{ userMenu: false }" class="relative">
                        <button @click="userMenu = !userMenu" class="flex items-center space-x-3 text-gray-700 hover:text-purple-600 transition-all duration-300">
                            <div class="w-8 h-8 bg-gradient-to-br from-purple-400 to-blue-400 rounded-full flex items-center justify-center">
                                <span class="text-white font-bold text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                            <span class="font-medium">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 transform transition-transform duration-200" :class="{ 'rotate-180': userMenu }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        
                        <div x-show="userMenu" @click.away="userMenu = false" x-transition class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-2">
                            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition-colors duration-200">
                                <span class="mr-3">📊</span>
                                <span>Dashboard</span>
                            </a>
                            <a href="{{ route('user.orders') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition-colors duration-200">
                                <span class="mr-3">🧾</span>
                                <span>Pesanan</span>
                            </a>
                            <a href="{{ route('user.tickets') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition-colors duration-200">
                                <span class="mr-3">🎟️</span>
                                <span>Tiket Saya</span>
                            </a>
                            <a href="{{ route('user.payments') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition-colors duration-200">
                                <span class="mr-3">💳</span>
                                <span>Pembayaran</span>
                            </a>
                            <a href="{{ route('user.notifications') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition-colors duration-200">
                                <span class="mr-3">🔔</span>
                                <span>Notifikasi</span>
                            </a>
                            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition-colors duration-200">
                                <span class="mr-3">⚙️</span>
                                <span>Pengaturan</span>
                            </a>
                            <hr class="my-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center w-full px-4 py-3 text-red-600 hover:bg-red-50 transition-colors duration-200">
                                    <span class="mr-3">🚪</span>
                                    <span>Keluar</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>

            <!-- Enhanced Mobile Menu Button -->
            <div class="flex items-center lg:hidden">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-xl text-gray-400 hover:text-purple-600 hover:bg-purple-50 focus:outline-none transition-all duration-300">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Enhanced Mobile Menu -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden lg:hidden bg-white border-t border-gray-100">
        <div class="px-4 py-6 space-y-4">
            <!-- Mobile Navigation Links -->
            <a href="{{ route('home') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition-all duration-300">
                <span>🏠</span>
                <span class="font-medium">Home</span>
            </a>
            <a href="{{ route('events.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition-all duration-300">
                <span>🎭</span>
                <span class="font-medium">Events</span>
            </a>
            <a href="#categories" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition-all duration-300">
                <span>📂</span>
                <span class="font-medium">Kategori</span>
            </a>
            <a href="{{ route('about') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition-all duration-300">
                <span>ℹ️</span>
                <span class="font-medium">Tentang</span>
            </a>
            <a href="{{ route('contact') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition-all duration-300">
                <span>📞</span>
                <span class="font-medium">Kontak</span>
            </a>
            
            <!-- Mobile Auth Section -->
            <div class="pt-4 border-t border-gray-100">
                @guest
                    <div class="space-y-3">
                        <a href="{{ route('login') }}" class="flex items-center justify-center w-full px-4 py-3 border-2 border-purple-600 text-purple-600 font-semibold rounded-xl hover:bg-purple-600 hover:text-white transition-all duration-300">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="btn-primary w-full justify-center">
                            <span class="flex items-center space-x-2">
                                <span>✨</span>
                                <span>Daftar Gratis</span>
                            </span>
                        </a>
                    </div>
                @else
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3 px-4 py-3 bg-purple-50 rounded-xl">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-blue-400 rounded-full flex items-center justify-center">
                                <span class="text-white font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                                <p class="text-sm text-gray-600">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                        
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition-all duration-300">
                            <span>📊</span>
                            <span class="font-medium">Dashboard</span>
                        </a>
                        <a href="{{ route('user.orders') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition-all duration-300">
                            <span>🧾</span>
                            <span class="font-medium">Pesanan</span>
                        </a>
                        <a href="{{ route('user.tickets') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition-all duration-300">
                            <span>🎟️</span>
                            <span class="font-medium">Tiket Saya</span>
                        </a>
                        <a href="{{ route('user.payments') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition-all duration-300">
                            <span>💳</span>
                            <span class="font-medium">Pembayaran</span>
                        </a>
                        <a href="{{ route('user.notifications') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition-all duration-300">
                            <span>🔔</span>
                            <span class="font-medium">Notifikasi</span>
                        </a>
                        <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition-all duration-300">
                            <span>⚙️</span>
                            <span class="font-medium">Pengaturan</span>
                        </a>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center space-x-3 w-full px-4 py-3 rounded-xl text-red-600 hover:bg-red-50 transition-all duration-300">
                                <span>🚪</span>
                                <span class="font-medium">Keluar</span>
                            </button>
                        </form>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>
