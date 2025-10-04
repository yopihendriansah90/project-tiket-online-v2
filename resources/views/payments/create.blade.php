<x-app-layout>
    @php
        $title = 'Pembayaran - ' . ($order->invoice_number ?? 'Order');
        $accounts = [
            // Bank transfer
            'bri' => [
                'type' => 'bank',
                'label' => 'BRI',
                'number' => '0023-456-7890',
                'name' => 'PT TiketIn Indonesia',
            ],
            'mandiri' => [
                'type' => 'bank',
                'label' => 'Mandiri',
                'number' => '123-00-9876543-1',
                'name' => 'PT TiketIn Indonesia',
            ],
            'bca' => [
                'type' => 'bank',
                'label' => 'BCA',
                'number' => '1234567890',
                'name' => 'PT TiketIn Indonesia',
            ],
            // E-wallet
            'dana' => [
                'type' => 'ewallet',
                'label' => 'DANA',
                'number' => '0812-0000-1111',
                'name' => 'TiketIn ID',
            ],
            'ovo' => [
                'type' => 'ewallet',
                'label' => 'OVO',
                'number' => '0812-2222-3333',
                'name' => 'TiketIn ID',
            ],
            'gopay' => [
                'type' => 'ewallet',
                'label' => 'GoPay',
                'number' => '0813-4444-5555',
                'name' => 'TiketIn ID',
            ],
            'shopeepay' => [
                'type' => 'ewallet',
                'label' => 'ShopeePay',
                'number' => '0813-6666-7777',
                'name' => 'TiketIn ID',
            ],
        ];
        $defaultChannel = old('channel', 'bri');
    @endphp

    <!-- Breadcrumb -->
    <nav class="bg-white py-4 border-b border-gray-200">
        <div class="container mx-auto px-4">
            <div class="flex items-center space-x-2 text-sm">
                <a href="{{ route('home') }}" class="text-purple-600 hover:text-purple-700">Home</a>
                <span class="text-gray-400">→</span>
                <span class="text-gray-600">Pembayaran</span>
                <span class="text-gray-400">→</span>
                <span class="text-gray-600">{{ $order->invoice_number }}</span>
            </div>
        </div>
    </nav>

    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-purple-50">
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-6xl mx-auto">
                @if(session('success'))
                    <div class="mb-6 rounded-xl border border-green-200 bg-green-50 text-green-800 px-4 py-3 text-sm">
                        ✅ {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 text-red-800 px-4 py-3 text-sm">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(isset($latestPayment) && $latestPayment)
                    @if($latestPayment->status === 'submitted')
                        <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 text-amber-800 px-4 py-3 text-sm">
                            ⏳ Bukti transfer sudah diterima dan menunggu verifikasi admin.
                        </div>
                    @elseif($latestPayment->status === 'verified')
                        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 text-green-800 px-4 py-3 text-sm">
                            ✅ Pembayaran Anda telah terverifikasi. Terima kasih!
                        </div>
                    @elseif($latestPayment->status === 'rejected')
                        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 text-red-800 px-4 py-3 text-sm">
                            ❌ Bukti transfer ditolak. Silakan unggah ulang dengan bukti yang jelas.
                        </div>
                    @endif
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Instruksi Pembayaran -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-2xl shadow-xl border border-purple-100 overflow-hidden">
                            <div class="p-6 sm:p-8">
                                <h2 class="text-2xl font-display font-extrabold text-gray-900 mb-2">Pembayaran Transfer Manual</h2>
                                <p class="text-gray-600 mb-6">Silakan lakukan transfer sesuai jumlah tagihan ke salah satu rekening/e-wallet berikut, kemudian unggah bukti transfer pada form di samping.</p>

                                <!-- Pilih Channel -->
                                <div class="mb-6">
                                    <label for="channel" class="block text-sm font-medium text-gray-700 mb-2">Pilih Metode Transfer</label>
                                    <select id="channel" name="channel" form="payment-form" class="w-full sm:w-80 rounded-xl border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                        @foreach($channels as $key => $label)
                                            <option value="{{ $key }}" {{ $defaultChannel === $key ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Detail Rekening/E-Wallet -->
                                <div id="accountCard" class="bg-gradient-to-br from-purple-50 to-blue-50 border border-purple-100 rounded-2xl p-5 sm:p-6">
                                    <div class="flex items-start sm:items-center justify-between gap-4 flex-col sm:flex-row">
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-purple-600 font-semibold mb-1" id="accountType">Bank Transfer</p>
                                            <h3 class="text-xl font-bold text-gray-800" id="accountLabel">BRI</h3>
                                            <p class="text-gray-700 mt-1">
                                                No. Rek: <span class="font-mono font-semibold" id="accountNumber">-</span>
                                            </p>
                                            <p class="text-gray-600 text-sm">
                                                Atas Nama: <span id="accountName">-</span>
                                            </p>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <button id="copyNumberBtn" type="button" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white text-purple-700 border border-purple-200 hover:bg-purple-50 transition">
                                                📋 Salin No.
                                            </button>
                                            <a href="#upload" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-purple-600 text-white hover:bg-purple-700 transition">
                                                ⬆️ Upload Bukti
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tips -->
                                <ul class="mt-6 text-sm text-gray-600 list-disc pl-5 space-y-1">
                                    <li>Pastikan nominal yang ditransfer sama persis dengan total tagihan.</li>
                                    <li>Jika menggunakan e-wallet, pastikan akun Anda terverifikasi agar limit mencukupi.</li>
                                    <li>Proses verifikasi manual membutuhkan waktu maksimal 1x24 jam.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Ringkasan Order + Upload Bukti -->
                    <div>
                        <div class="bg-white rounded-2xl shadow-xl border border-purple-100 overflow-hidden">
                            <div class="p-6 sm:p-7">
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Ringkasan Order</h3>
                                <div class="space-y-2 text-sm">
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600">No. Invoice</span>
                                        <span class="font-semibold">{{ $order->invoice_number }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600">Total</span>
                                        <span class="font-semibold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600">Status</span>
                                        <span class="font-semibold capitalize">{{ $order->status }}</span>
                                    </div>
                                </div>

                                <hr class="my-5">

                                <h4 id="upload" class="text-base font-semibold text-gray-800 mb-3">Upload Bukti Transfer</h4>

                                <form id="payment-form" method="POST" action="{{ route('payments.store', ['order' => $order->id]) }}" enctype="multipart/form-data" class="space-y-4">
                                    @csrf
                                    <input type="hidden" name="channel" value="{{ $defaultChannel }}">

                                    <div>
                                        <label for="proof" class="block text-sm font-medium text-gray-700 mb-1">Bukti Transfer (JPG/PNG/WebP/PDF, maks 5MB)</label>
                                        <input id="proof" name="proof" type="file" accept=".jpg,.jpeg,.png,.webp,.pdf" required class="block w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-600 file:text-white hover:file:bg-purple-700 border-gray-300 rounded-xl">
                                    </div>

                                    <div>
                                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan (opsional)</label>
                                        <textarea id="notes" name="notes" rows="3" class="w-full rounded-xl border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500" placeholder="Contoh: Sudah transfer via {{ strtoupper($defaultChannel) }} a.n. John Doe"></textarea>
                                    </div>

                                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition">
                                        Kirim Bukti Transfer
                                    </button>

                                    <p class="text-[11px] text-gray-500 mt-1">
                                        Setelah bukti terkirim, status pembayaran akan menjadi "submitted" dan menunggu verifikasi admin.
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const accounts = @json($accounts);
            const channelSelect = document.getElementById('channel');
            const hiddenChannel = document.querySelector('form#payment-form input[name="channel"]');

            const accountType = document.getElementById('accountType');
            const accountLabel = document.getElementById('accountLabel');
            const accountNumber = document.getElementById('accountNumber');
            const accountName = document.getElementById('accountName');
            const copyBtn = document.getElementById('copyNumberBtn');

            function setAccountUI(key) {
                const data = accounts[key];
                if (!data) return;
                accountType.textContent = data.type === 'bank' ? 'Bank Transfer' : 'E-Wallet';
                accountLabel.textContent = data.label;
                accountNumber.textContent = data.number;
                accountName.textContent = data.name;
                if (hiddenChannel) hiddenChannel.value = key;
            }

            channelSelect?.addEventListener('change', (e) => {
                setAccountUI(e.target.value);
            });

            copyBtn?.addEventListener('click', () => {
                const text = accountNumber?.textContent?.trim() || '';
                if (!text) return;
                navigator.clipboard.writeText(text).then(() => {
                    copyBtn.textContent = '✅ Disalin';
                    setTimeout(() => copyBtn.textContent = '📋 Salin No.', 1500);
                });
            });

            // init
            setAccountUI('{{ $defaultChannel }}');
        });
    </script>
    @endpush
</x-app-layout>