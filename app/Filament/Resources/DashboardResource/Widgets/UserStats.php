<?php

namespace App\Filament\Resources\DashboardResource\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\User;

class UserStats extends ChartWidget
{
    protected static ?string $heading = 'User Stats';

    protected function getData(): array
    {
        $userCounts = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Users Registered',
                    'data' => $userCounts->pluck('count')->toArray(),
                ],
            ],
            'labels' => $userCounts->pluck('date')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bubble';
    }
}
