<?php

namespace App\Filament\Widgets;

use App\Models\Analyze;
use App\Models\Personnel;
use App\Models\Customers;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\App;

class CustomerCountWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array
    {
        // Check if the user has the "مشتری" role
        if (auth()->check() && auth()->user()->hasRole('مشتریان')) {
            // Return an empty array to hide the widget if the user has the "مشتری" role
            return [];
        }
        

        // Otherwise, show the stats
        $locale = App::getLocale();

        return [
            Stat::make($locale === 'fa' ? 'تعداد مشتریان' : 'Total Customers', Customers::count())
                ->icon('heroicon-o-users')
                ->color('success')
                ->extraAttributes(['class' => 'w-full text-center', 'style' => 'font-size: 24px;']),

            Stat::make($locale === 'fa' ? 'تعداد پرسنل' : 'Total Personnel', Personnel::count())
                ->icon('heroicon-o-user')
                ->color('success')
                ->extraAttributes(['class' => 'w-full text-center', 'style' => 'font-size: 24px;']),
                
            Stat::make($locale === 'fa' ? 'تعداد آنالیز' : 'Total Analyze', Analyze::count())
                ->icon('heroicon-o-chart-pie')
                ->color('success')
                ->extraAttributes(['class' => 'w-full text-center', 'style' => 'font-size: 24px;']),
        ];
    }
}
