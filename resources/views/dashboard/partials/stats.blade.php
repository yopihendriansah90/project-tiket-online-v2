<div aria-labelledby="kpi-title" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
    <!-- Pending Orders -->
    <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-100 p-4">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500">Pesanan Pending</p>
                <p class="mt-2 text-2xl font-bold text-purple-700" aria-label="Jumlah pesanan pending">
                    {{ isset($stats['pending']) ? number_format($stats['pending']) : 0 }}
                </p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                <span class="text-purple-600">⏳</span>
            </div>
        </div>
        <div class="mt-3 h-1.5 rounded-full bg-purple-50">
            <div class="h-1.5 rounded-full bg-gradient-to-r from-purple-600 to-blue-600"
                 style="width: {{ isset($stats['pending']) && ($stats['pending'] + ($stats['paid'] ?? 0) + ($stats['tasks'] ?? 0) + ($stats['active_tickets'] ?? 0)) > 0
                    ? min(100, round(($stats['pending'] / max(1, ($stats['pending'] + ($stats['paid'] ?? 0) + ($stats['tasks'] ?? 0) + ($stats['active_tickets'] ?? 0))) ) * 100))
                    : 0 }}%">
            </div>
        </div>
    </div>

    <!-- Paid Orders -->
    <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-100 p-4">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500">Pesanan Paid</p>
                <p class="mt-2 text-2xl font-bold text-blue-700" aria-label="Jumlah pesanan paid">
                    {{ isset($stats['paid']) ? number_format($stats['paid']) : 0 }}
                </p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                <span class="text-blue-600">✅</span>
            </div>
        </div>
        <div class="mt-3 h-1.5 rounded-full bg-blue-50">
            <div class="h-1.5 rounded-full bg-gradient-to-r from-blue-600 to-purple-600"
                 style="width: {{ isset($stats['paid']) && ($stats['pending'] + ($stats['paid'] ?? 0) + ($stats['tasks'] ?? 0) + ($stats['active_tickets'] ?? 0)) > 0
                    ? min(100, round(($stats['paid'] / max(1, ($stats['pending'] + ($stats['paid'] ?? 0) + ($stats['tasks'] ?? 0) + ($stats['active_tickets'] ?? 0))) ) * 100))
                    : 0 }}%">
            </div>
        </div>
    </div>

    <!-- Active Tickets -->
    <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-100 p-4">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500">Tiket Aktif</p>
                <p class="mt-2 text-2xl font-bold text-emerald-700" aria-label="Jumlah tiket aktif">
                    {{ isset($stats['active_tickets']) ? number_format($stats['active_tickets']) : 0 }}
                </p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center">
                <span class="text-emerald-600">🎫</span>
            </div>
        </div>
        <div class="mt-3 h-1.5 rounded-full bg-emerald-50">
            <div class="h-1.5 rounded-full bg-gradient-to-r from-emerald-600 to-blue-600"
                 style="width: {{ isset($stats['active_tickets']) && ($stats['pending'] + ($stats['paid'] ?? 0) + ($stats['tasks'] ?? 0) + ($stats['active_tickets'] ?? 0)) > 0
                    ? min(100, round(($stats['active_tickets'] / max(1, ($stats['pending'] + ($stats['paid'] ?? 0) + ($stats['tasks'] ?? 0) + ($stats['active_tickets'] ?? 0))) ) * 100))
                    : 0 }}%">
            </div>
        </div>
    </div>

    <!-- Tasks -->
    <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-100 p-4">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500">Tugas</p>
                <p class="mt-2 text-2xl font-bold text-amber-700" aria-label="Jumlah tugas">
                    {{ isset($stats['tasks']) ? number_format($stats['tasks']) : 0 }}
                </p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center">
                <span class="text-amber-600">📌</span>
            </div>
        </div>
        <div class="mt-3 h-1.5 rounded-full bg-amber-50">
            <div class="h-1.5 rounded-full bg-gradient-to-r from-amber-500 to-purple-600"
                 style="width: {{ isset($stats['tasks']) && ($stats['pending'] + ($stats['paid'] ?? 0) + ($stats['tasks'] ?? 0) + ($stats['active_tickets'] ?? 0)) > 0
                    ? min(100, round(($stats['tasks'] / max(1, ($stats['pending'] + ($stats['paid'] ?? 0) + ($stats['tasks'] ?? 0) + ($stats['active_tickets'] ?? 0))) ) * 100))
                    : 0 }}%">
            </div>
        </div>
    </div>
</div>