<?php

namespace App\Providers;

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
        Filament::serving(function () {
            Filament::registerNavigationGroups([
                NavigationGroup::make()
                    ->label('اطلاعات پایه')
                    ->icon('heroicon-s-shopping-cart')
                    ->collapsed() // This will keep the group collapsed
                    ->items([
                        NavigationItem::make('مدیریت زمان آنالیز')->url('/path1'),
                        NavigationItem::make('Item 2')->url('/path2'),
                    ]),
                NavigationGroup::make()
                    ->label('پذیرش')
                    ->icon('heroicon-s-cog')
                    ->collapsed() // This will keep the group collapsed
                    ->items([
                        NavigationItem::make('Item 3')->url('/path3'),
                        NavigationItem::make('Item 4')->url('/path4'),
                    ]),
                NavigationGroup::make()
                    ->label('مدیریت کابران')
                    ->icon('heroicon-o-users')
                    ->collapsed() // This will keep the group collapsed
                    ->items([
                        NavigationItem::make('Item 5')->url('/path5'),
                    ]),
                NavigationGroup::make()
                    ->label('امور مالی')
                    ->icon('heroicon-s-credit-card')
                    ->collapsed() // This will keep the group collapsed
                    ->items([
                        NavigationItem::make('Item 6')->url('/path6'),
                        NavigationItem::make('Item 7')->url('/path7'),
                    ]),
            ]);
        });
    }
}
