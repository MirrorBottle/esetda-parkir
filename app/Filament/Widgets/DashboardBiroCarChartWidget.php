<?php

namespace App\Filament\Widgets;

use App\Models\Biro;
use App\Models\Car;
use Filament\Widgets\ChartWidget;

class DashboardBiroCarChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Grafik Kendaraan per Biro';


    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    
    protected function getData(): array
    {
        // Get all unique Biros
        $biros = Biro::select('id', 'name')
            ->get()
            ->toArray();
            
        // Get counts for each car type within each Biro
        $operational = $this->getCountsByType('operasional', $biros);
        $personal = $this->getCountsByType('pribadi', $biros);
        $duty = $this->getCountsByType('dinas', $biros);
        $others = $this->getCountsByType('lainnya', $biros);
        return [
            'datasets' => [
                [
                    'label' => 'Operasional',
                    'data' => $operational,
                    'backgroundColor' => '#2A7EFF',
                    'borderWidth' => 0,
                ],
                [
                    'label' => 'Pribadi',
                    'data' => $personal,
                    'backgroundColor' => '#FB2D37',
                    'borderWidth' => 0,
                ],
                [
                    'label' => 'Dinas',
                    'data' => $duty,
                    'backgroundColor' => '#00C950',
                    'borderWidth' => 0,
                ],
                [
                    'label' => 'Lainnya',
                    'data' => $others,
                    'backgroundColor' => '#FE9B01',
                    'borderWidth' => 0,
                ],
            ],
            'labels' => collect($biros)->pluck('name')->toArray(),
        ];
    }
    
    protected function getCountsByType(string $type, array $biros): array
    {
        $counts = [];
        foreach ($biros as $biro) {
            $count = Car::whereHas('employee', function ($query) use ($biro) {
                    $query->where('biro_id', $biro['id']);
                })
                ->where('type', $type)
                ->count();
            $counts[] = $count;
        }
        
        return $counts;
    }

    protected function getType(): string
    {
        return 'bar'; // Using bar chart type
    }
    
    protected function getOptions(): array
    {
        return [
            'scales' => [
                'x' => [
                    'stacked' => true,
                ],
                'y' => [
                    'stacked' => true,
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
                'tooltip' => [
                    'mode' => 'index',
                    'intersect' => false,
                ],
            ],
        ];
    }
}
