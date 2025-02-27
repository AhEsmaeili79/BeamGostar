<?php

namespace App\Providers;

use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Observers\UserObserver;
use App\Models\Customers;
use App\Observers\CustomerObserver;
use App\Models\Personnel;
use App\Observers\PersonnelObserver;
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
    }
}
