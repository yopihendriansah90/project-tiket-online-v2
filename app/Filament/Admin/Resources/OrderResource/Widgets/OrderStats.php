<?php

namespace App\Filament\Admin\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStats extends BaseWidget
{
    protected function getStats(): array
    {
        $paidRevenue = Order::where('status', 'paid')->sum('total_price');
        $pendingOrders = Order::where('status', 'pending')->count();
        $ordersToday = Order::whereDate('created_at', today())->count();

        return [
            Stat::make('Total Pendapatan (Lunas)', 'Rp ' . number_format($paidRevenue, 0, ',', '.'))
                ->description('Total dari semua pesanan yang sudah lunas')
                ->color('success')
                ->icon('heroicon-o-banknotes'),

            Stat::make('Pesanan Menunggu Pembayaran', $pendingOrders)
                ->description('Jumlah pesanan yang belum dibayar')
                ->color('warning')
                ->icon('heroicon-o-clock'),

            Stat::make('Total Pesanan Hari Ini', $ordersToday)
                ->description('Semua status pesanan hari ini')
                ->color('info')
                ->icon('heroicon-o-calendar-days'),
        ];
    }
}
