<div class="space-y-3">
    <div class="flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Preview Bukti Pembayaran</h3>
        <div class="flex items-center gap-2">
            @isset($paymentId)
                <form method="POST" action="{{ route('payments.admin.verify', ['payment' => $paymentId]) }}">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 rounded-md text-white bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-500 dark:hover:bg-emerald-600 border border-emerald-700 dark:border-emerald-600 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 dark:focus:ring-emerald-300 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-gray-900 transition">
                        Verifikasi
                    </button>
                </form>
            @endisset
            <a href="{{ $url }}" target="_blank" class="px-3 py-1.5 rounded-md text-white bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 border border-indigo-700 dark:border-indigo-600 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 dark:focus:ring-indigo-300 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-gray-900 transition">
                Buka Penuh
            </a>
        </div>
    </div>

    <div id="previewCanvas" class="relative overflow-auto rounded-lg border border-gray-200 bg-gray-50" style="max-height: 420px;">
        <img id="previewImage"
             src="{{ $url }}"
             alt="Bukti Pembayaran"
             class="block select-none"
             style="user-select: none; max-width: none; height: auto;">
    </div>

    <p class="text-xs text-gray-500">Gulir untuk melihat bukti pembayaran. Klik "Buka Penuh" untuk membuka gambar asli.</p>
</div>

<script>
/**
 * Preview menggunakan scroll bawaan container (overflow-auto).
 * Tidak ada kontrol zoom; tombol "Buka Penuh" tetap tersedia.
 */
</script>