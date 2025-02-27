<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;
use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\App;

class AnalogClockWidget extends Widget
{
    // Set the view for the widget
    protected static string $view = 'filament.widgets.analog-clock-widget';

    /**
     * Get data to pass to the view.
     *
     * @return array
     */
    protected function getViewData(): array
    {
        $currentTime = Carbon::now()->format('H:i:s'); // Get current time in H:i:s format
        $locale = App::getLocale(); // Get the current application language

        if ($locale === 'fa') {
            // If the language is Persian, use the Jalali calendar
            $formattedDate = Jalalian::now()->format('%A, %d %B %Y');
        } else {
            // Default to Gregorian if the language is not Persian
            $formattedDate = Carbon::now()->translatedFormat('l, d F Y');
        }

        return [
            'currentTime' => $currentTime,
            'formattedDate' => $formattedDate,
        ];
    }

    /**
     * Define the column span for the widget.
     *
     * @return int
     */
    public function getColumnSpan(): int
    {
        return 2; // Span 2 columns
    }
}
