<?php

// app/Filament/Pages/Dashboard.php

namespace App\Filament\Pages;

use App\Models\Ad;
use Filament\Pages\Dashboard as Page;
use Illuminate\Contracts\View\View;
use App\Filament\Widgets\AnalogClockWidget;

class Dashboard extends Page
{
    public $ads;

    public function mount()
    {
        // Fetch a set of ads to display on the dashboard (adjust the query as needed)
        $this->ads = Ad::latest()->take(5)->get(); // Fetch the latest 5 ads (or change the logic as necessary)
    }

    /**
     * Render the page and pass the ads to the view.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render(): View
    {
        return view('filament.pages.dashboard', [
            'ads' => $this->ads,
        ]);
    }

    public function getWidgets(): array
    {
        return [
            AnalogClockWidget::class,
        ];
    }
}
