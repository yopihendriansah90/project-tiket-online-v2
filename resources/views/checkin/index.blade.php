<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Check-in Panitia
        </h2>
        <p class="text-sm text-gray-600">Scan QR atau masukkan token secara manual untuk verifikasi peserta.</p>
      </div>
      <div class="flex items-center gap-2">
        <button id="toggleScannerBtn" type="button" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold text-white bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 transition">
          Mulai Scanner
        </button>
      </div>
    </div>
  </x-slot>

  <div class="py-8">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white shadow-sm sm:rounded-lg">
        <div class="p-6 space-y-6">

          <!-- Scanner Panel -->
          <div class="rounded-lg border border-gray-200 p-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Scanner Kamera</h3>
            <div id="qrReader" class="w-full max-w-md aspect-square bg-gray-50 rounded-lg border border-dashed border-gray-300 flex items-center justify-center text-gray-400">
              <span class="text-sm">Scanner belum aktif. Klik "Mulai Scanner".</span>
            </div>
            <p class="mt-2 text-xs text-gray-500">Pastikan izin kamera diaktifkan pada browser perangkat panitia.</p>
          </div>

          <!-- Manual Verification Form -->
          <div class="rounded-lg border border-gray-200 p-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Verifikasi Manual</h3>
            <form id="verifyForm" method="POST" action="{{ route('checkin.verify') }}" class="space-y-4">
              @csrf
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label for="token" class="block text-sm font-medium text-gray-700">Token Peserta</label>
                  <input id="token" name="token" type="text" class="mt-1 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500" placeholder="Tempel token hasil scan atau masukkan manual" required>
                </div>
                <div>
                  <label for="gate" class="block text-sm font-medium text-gray-700">Gate/Pos (opsional)</label>
                  <input id="gate" name="gate" type="text" class="mt-1 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500" placeholder="Misal: Gate A / Pos 1">
                </div>
              </div>
              <div class="flex items-center gap-3">
                <button id="submitBtn" type="submit" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold text-white bg-purple-600 hover:bg-purple-700 transition">
                  Verifikasi
                </button>
                <span id="loadingBadge" class="hidden text-xs text-gray-600">Memproses...</span>
              </div>
            </form>
          </div>

          <!-- Result Panel -->
          <div class="rounded-lg border border-gray-200 p-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Hasil</h3>
            <div id="resultBox" class="hidden">
              <div id="resultStatus" class="text-sm font-semibold mb-2"></div>
              <div id="resultDetail" class="text-sm text-gray-700"></div>
            </div>
            <div id="resultEmpty" class="text-sm text-gray-500">
              Belum ada hasil. Lakukan scan atau verifikasi manual.
            </div>
          </div>

          <div class="rounded-lg bg-purple-50 p-4">
            <p class="text-xs text-gray-700">
              Catatan: QR berisi token unik untuk setiap peserta. Sistem akan menolak check-in ulang dan menampilkan waktu serta gate sebelumnya.
            </p>
          </div>

        </div>
      </div>
    </div>
  </div>

  @push('scripts')
  <script src="https://unpkg.com/html5-qrcode" defer></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const toggleBtn = document.getElementById('toggleScannerBtn');
      const readerEl = document.getElementById('qrReader');
      const formEl = document.getElementById('verifyForm');
      const tokenEl = document.getElementById('token');
      const gateEl = document.getElementById('gate');
      const submitBtn = document.getElementById('submitBtn');
      const loadingBadge = document.getElementById('loadingBadge');
      const resultBox = document.getElementById('resultBox');
      const resultStatus = document.getElementById('resultStatus');
      const resultDetail = document.getElementById('resultDetail');
      const resultEmpty = document.getElementById('resultEmpty');

      let scanner = null;
      let scannerActive = false;

      function showResult(ok, message, detailHtml) {
        resultEmpty.classList.add('hidden');
        resultBox.classList.remove('hidden');
        resultStatus.textContent = message;
        resultStatus.className = 'text-sm font-semibold mb-2 ' + (ok ? 'text-green-700' : 'text-red-700');
        resultDetail.innerHTML = detailHtml || '';
      }

      function resetResult() {
        resultBox.classList.add('hidden');
        resultEmpty.classList.remove('hidden');
        resultStatus.textContent = '';
        resultDetail.innerHTML = '';
      }

      async function verifyToken(token, gate) {
        loadingBadge.classList.remove('hidden');
        submitBtn.disabled = true;

        try {
          const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
          const resp = await fetch(formEl.action, {
            method: 'POST',
            headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/json',
              ...(csrf ? { 'X-CSRF-TOKEN': csrf } : {})
            },
            body: JSON.stringify({ token, gate })
          });

          const data = await resp.json().catch(() => ({}));

          if (resp.ok && data.ok) {
            const a = data.attendee || {};
            showResult(true, data.message || 'Check-in berhasil.', `
              <ul class="text-sm space-y-1">
                <li><span class="font-medium">Nama:</span> ${a.name || '-'}</li>
                <li><span class="font-medium">HP:</span> ${a.phone || '-'}</li>
                <li><span class="font-medium">Kursi:</span> ${a.seat || '-'}</li>
                <li><span class="font-medium">Event:</span> ${a.event || '-'}</li>
              </ul>
            `);
          } else {
            const msg = (data && data.message) ? data.message : 'Verifikasi gagal.';
            const a = data.attendee || {};
            showResult(false, msg, `
              <ul class="text-sm space-y-1">
                <li><span class="font-medium">Nama:</span> ${a.name || '-'}</li>
                <li><span class="font-medium">HP:</span> ${a.phone || '-'}</li>
                <li><span class="font-medium">Kursi:</span> ${a.seat || '-'}</li>
                <li><span class="font-medium">Event:</span> ${a.event || '-'}</li>
              </ul>
            `);
          }
        } catch (e) {
          showResult(false, 'Terjadi kesalahan jaringan.', '');
        } finally {
          loadingBadge.classList.add('hidden');
          submitBtn.disabled = false;
        }
      }

      formEl.addEventListener('submit', function(e) {
        e.preventDefault();
        const token = tokenEl.value.trim();
        const gate = gateEl.value.trim();
        if (!token) {
          resetResult();
          showResult(false, 'Token wajib diisi.', '');
          return;
        }
        verifyToken(token, gate);
      });

      toggleBtn.addEventListener('click', async function() {
        try {
          if (!scannerActive) {
            if (!window.Html5Qrcode) {
              alert('Library scanner belum siap. Coba lagi sebentar.');
              return;
            }
            scanner = new Html5Qrcode('qrReader');
            const onScanSuccess = (decodedText) => {
              tokenEl.value = decodedText;
              verifyToken(decodedText, gateEl.value.trim());
            };
            const config = { fps: 10, qrbox: { width: 250, height: 250 } };
            await scanner.start({ facingMode: 'environment' }, config, onScanSuccess);
            scannerActive = true;
            toggleBtn.textContent = 'Hentikan Scanner';
            readerEl.classList.remove('text-gray-400');
          } else {
            await scanner.stop();
            await scanner.clear();
            scannerActive = false;
            toggleBtn.textContent = 'Mulai Scanner';
            readerEl.classList.add('text-gray-400');
          }
        } catch (err) {
          alert('Gagal mengakses kamera: ' + (err?.message || err));
        }
      });
    });
  </script>
  @endpush
</x-app-layout>