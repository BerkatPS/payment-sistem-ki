<?php

namespace App\Filament\Admin\Widgets;
use App\Models\Payment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PaymentStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalPayments = Payment::completed()->sum('amount');
        $pendingPayments = Payment::pending()->count();
        $completedPayments = Payment::completed()->count();

        return [
            Stat::make('Total Pembayaran Selesai', 'Rp ' . number_format($totalPayments, 0, ',', '.'))
                ->description('Total nilai pembayaran yang berhasil')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Pembayaran Pending', $pendingPayments)
                ->description('Pembayaran yang menunggu konfirmasi')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            Stat::make('Pembayaran Selesai', $completedPayments)
                ->description('Pembayaran yang sudah dikonfirmasi')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        ];
    }
}
