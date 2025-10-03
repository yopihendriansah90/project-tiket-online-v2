<footer class="bg-gray-900 text-gray-400">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Kolom 1: Logo & Tagline -->
            <div class="mb-8 md:mb-0">
                <a href="{{ route('home') }}" class="flex items-center space-x-2 group mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-600 to-blue-600 rounded-xl flex items-center justify-center">
                        <span class="text-white font-bold text-xl">🎟️</span>
                    </div>
                    <span class="text-2xl font-display font-bold text-white">TiketIn</span>
                </a>
                <p class="text-sm">Platform terpercaya untuk menemukan dan memesan tiket event terbaik di Indonesia.</p>
            </div>

            <!-- Kolom 2: Navigasi -->
            <div>
                <h3 class="text-white font-semibold tracking-wider uppercase mb-4">Jelajahi</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a></li>
                    <li><a href="{{ route('events.index') }}" class="hover:text-white transition-colors">Events</a></li>
                    <li><a href="#categories" class="hover:text-white transition-colors">Kategori</a></li>
                </ul>
            </div>

            <!-- Kolom 3: Perusahaan -->
            <div>
                <h3 class="text-white font-semibold tracking-wider uppercase mb-4">Perusahaan</h3>
                <ul class="space-y-2">
                    <li><a href="#" class="hover:text-white transition-colors">Tentang Kami</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Kontak</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Syarat & Ketentuan</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Kebijakan Privasi</a></li>
                </ul>
            </div>

            <!-- Kolom 4: Media Sosial -->
            <div>
                <h3 class="text-white font-semibold tracking-wider uppercase mb-4">Ikuti Kami</h3>
                <div class="flex space-x-4">
                    <a href="#" class="hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" /></svg>
                    </a>
                    <a href="#" class="hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.71v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" /></svg>
                    </a>
                    <a href="#" class="hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.024.06 1.378.06 3.808s-.012 2.784-.06 3.808c-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.024.048-1.378.06-3.808.06s-2.784-.013-3.808-.06c-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.048-1.024-.06-1.378-.06-3.808s.012-2.784.06-3.808c.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 016.345 2.525c.636-.247 1.363-.416 2.427-.465C9.793 2.013 10.147 2 12.315 2zm0 1.623c-2.387 0-2.71.01-3.66.052a3.288 3.288 0 00-1.18.283 3.288 3.288 0 00-1.15.748 3.288 3.288 0 00-.748 1.15 3.288 3.288 0 00-.283 1.18c-.042.95-.052 1.273-.052 3.66s.01 2.71.052 3.66c.053.46.162.88.283 1.18a3.288 3.288 0 00.748 1.15 3.288 3.288 0 001.15.748 3.288 3.288 0 001.18.283c.95.042 1.273.052 3.66.052s2.71-.01 3.66-.052a3.288 3.288 0 001.18-.283 3.288 3.288 0 001.15-.748 3.288 3.288 0 00.748-1.15 3.288 3.288 0 00.283-1.18c.042-.95.052-1.273.052-3.66s-.01-2.71-.052-3.66a3.288 3.288 0 00-.283-1.18 3.288 3.288 0 00-.748-1.15 3.288 3.288 0 00-1.15-.748 3.288 3.288 0 00-1.18-.283c-.95-.042-1.273-.052-3.66-.052zM12 6.865a5.135 5.135 0 100 10.27 5.135 5.135 0 000-10.27zm0 1.623a3.512 3.512 0 110 7.024 3.512 3.512 0 010-7.024zM16.338 5.402a1.23 1.23 0 100 2.46 1.23 1.23 0 000-2.46z" clip-rule="evenodd" /></svg>
                    </a>
                </div>
            </div>
        </div>
        <!-- Copyright -->
        <div class="bg-gray-950/50 border-t border-gray-800">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 text-center text-sm">
                <p>&copy; {{ date('Y') }} TiketIn. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>
