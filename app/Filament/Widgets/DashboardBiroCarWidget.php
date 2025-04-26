<?php

namespace App\Filament\Widgets;

use App\Models\Car;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class DashboardBiroCarWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $carTypesList = ['dinas', 'operasional', 'pribadi', 'lainnya'];

        $carsByType = collect($carTypesList)->mapWithKeys(function ($type) {
            return [$type => 0];
        })->toArray();

        $typeCounts = Car::selectRaw('type, COUNT(*) as count')
            ->whereIn('type', $carTypesList)
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();

        $carsByType = array_merge($carsByType, $typeCounts);

        return [
            Stat::make('Kendaraan Operasional', $carsByType['operasional'])
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('info'),
            Stat::make('Kendaraan Pribadi', $carsByType['pribadi'])
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('danger'),
            Stat::make('Kendaraan Dinas', $carsByType['dinas'])
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            Stat::make('Kendaraan Lainnya', $carsByType['lainnya'])
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('warning'),
        ];
    }
}
