<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerAnalysis;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Morilog\Jalali\Jalalian;

class CustomerAnalysisChart extends ChartWidget
{
    protected static ?string $heading = null;
    protected static ?string $maxHeight = '400px';

    // Override the canView method to prevent the widget from showing for specific roles
    public static function canView(): bool
    {
        // Return false if the user has the 'مشتریان' role
        return !Auth::check() || !Auth::user()->hasRole('مشتریان');
    }

    // Set the heading dynamically based on the language
    public function getHeading(): ?string
    {
        return Lang::get('widget.heading');
    }
    protected function getData(): array
    {
        // Query to get the count of CustomerAnalysis records grouped by date
        $query = CustomerAnalysis::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'));
    
        $data = $query->get();
    
        // Prepare the chart data
        $labels = $data->pluck('date')->map(function ($date) {
            return Jalalian::forge($date)->format('Y-m-d'); // Convert to Jalali date
        })->toArray();
    
        $values = $data->pluck('count')->toArray();
    
        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => Lang::get('widget.heading'), // Translate the label here as well
                    'data' => $values,
                    'fill' => false, // To make the chart just a line (without filling under it)
                    'borderColor' => 'rgba(75, 192, 192, 1)', // Line color
                    'borderWidth' => 2,
                    'tension' => 0.1, // Smooth curve (0 for sharp corners)
                ],
            ],
        ];
    }
    

    public function getColumnSpan(): int
    {
        return 2; // Each widget takes 1 column
    }

    protected function getFilters(): ?array
    {
        return [
            'today' => Lang::get('widget.today'),
            'week' => Lang::get('widget.week'),
            'month' => Lang::get('widget.month'),
            'year' => Lang::get('widget.year'),
        ];
    }

    protected function getType(): string
    {
        return 'line'; // Display as a line chart
    }
}
