<?php

namespace App\Providers;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Observers\UserObserver;
use App\Models\Customers;
use App\Observers\CustomerObserver;
use App\Models\Personnel;
use App\Observers\PersonnelObserver;
use Filament\Navigation\NavigationItem;
class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['fa', 'en'])
                ->visible(outsidePanels: true);  
        });
        app()->setLocale('fa');

        User::observe(UserObserver::class);
        Customers::observe(CustomerObserver::class);
        Personnel::observe(PersonnelObserver::class);

        // Filament::serving(function () {
        //     Filament::registerNavigationGroups([
        //         NavigationGroup::make()
        //             ->label('مدیریت کاربران')
        //             ->icon('heroicon-s-user-group'),
        //             // Example icon
                
        //         NavigationGroup::make()
        //             ->label('اطلاعات پایه')
        //             ->icon('heroicon-s-circle-stack'), // Example icon
                
        //         NavigationGroup::make()
        //             ->label('پذیرش')
        //             ->icon('heroicon-s-clipboard'), // Example icon
                
        //         NavigationGroup::make()
        //             ->label('امور مالی')
        //             ->icon('heroicon-s-credit-card'), // Example icon
                
        //         NavigationGroup::make()
        //             ->label('آزمایشگاه')
        //             ->icon('heroicon-s-beaker'), // Example icon
                
        //         NavigationGroup::make()
        //             ->label('مدیریت فنی')
        //             ->icon('heroicon-s-cog'), // Example icon
                
        //         NavigationGroup::make()
        //             ->label('جوابدهی')
        //             ->icon('heroicon-s-chat'), // Example icon (change to whatever fits best)
                
        //         NavigationGroup::make()
        //             ->label('مشتریان')
        //             ->icon('heroicon-s-users'), // Example icon
                
        //         NavigationGroup::make()
        //             ->label('مدیریت صورتحساب')
        //             ->icon('heroicon-s-credit-card'), // Example icon (can be adjusted)
                
        //         NavigationGroup::make()
        //             ->label('مدیریت پیام رسانی')
        //             ->icon('heroicon-s-chat-bubble'), // Example icon (can be adjusted)
        //     ]);
        // });
    }
}
