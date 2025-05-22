<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class PaymentChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Pembayaran Per Bulan';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = [];
        $labels = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labels[] = $date->format('M Y');

            $amount = Payment::whereYear('payment_date', $date->year)
                ->whereMonth('payment_date', $date->month)
                ->where('status', 'completed')
                ->sum('amount');

            $data[] = $amount;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Pembayaran (Rp)',
                    'data' => $data,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 2,
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}

