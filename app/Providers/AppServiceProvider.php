<?php

namespace App\Providers;

use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Language switch configuration
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['fa', 'en'])
                ->visible(outsidePanels: true);  // Set to true if you want to show it outside the panels
        });
        app()->setLocale('fa');
        
        // Register navigation groups and items with translated labels
        Filament::serving(function () {
            Filament::registerNavigationGroups([
                NavigationGroup::make()
                    ->label(__('اطلاعات پایه')) // Translated label
                    ->icon('heroicon-s-shopping-cart')
                    ->collapsed() // Keep the group collapsed
                    ->items([
                        NavigationItem::make(__('مدیریت زمان آنالیز'))->url('/path1'), // Translated item label
                        NavigationItem::make(__('Item 2'))->url('/path2'), // Translated item label
                    ]),
                
                NavigationGroup::make()
                    ->label(__('پذیرش')) // Translated label
                    ->icon('heroicon-s-cog')
                    ->collapsed() // Keep the group collapsed
                    ->items([
                        NavigationItem::make(__('Item 3'))->url('/path3'), // Translated item label
                        NavigationItem::make(__('Item 4'))->url('/path4'), // Translated item label
                    ]),

                NavigationGroup::make()
                    ->label(__('مدیریت کابران')) // Translated label
                    ->icon('heroicon-o-users')
                    ->collapsed() // Keep the group collapsed
                    ->items([
                        NavigationItem::make(__('Item 5'))->url('/path5'), // Translated item label
                    ]),

                NavigationGroup::make()
                    ->label(__('امور مالی')) // Translated label
                    ->icon('heroicon-s-credit-card')
                    ->collapsed() // Keep the group collapsed
                    ->items([
                        NavigationItem::make(__('Item 6'))->url('/path6'), // Translated item label
                        NavigationItem::make(__('Item 7'))->url('/path7'), // Translated item label
                    ]),
            ]);
        });
    }
}
