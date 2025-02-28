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

        Filament::serving(function () {
            Filament::registerNavigationGroups([
                NavigationGroup::make()
                    ->label('مدیریت کاربران'),
                
                NavigationGroup::make()
                    ->label('اطلاعات پایه'),
                
                NavigationGroup::make()
                    ->label('پذیرش'),
                
                NavigationGroup::make()
                    ->label('امور مالی'),
                
                NavigationGroup::make()
                    ->label('آزمایشگاه'),
                
                NavigationGroup::make()
                    ->label('مدیریت فنی'),
                
                NavigationGroup::make()
                    ->label('جوابدهی'),
                
                NavigationGroup::make()
                    ->label('مشتریان'),
                
                NavigationGroup::make()
                    ->label('مدیریت صورتحساب'),
                
                NavigationGroup::make()
                    ->label('مدیریت پیام رسانی'),
            ]);
        });
    }
}
