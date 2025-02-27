<x-filament-widgets::widget>
    <div class="p-0">
        <!-- Include the external CSS file for the clock -->
        <link rel="stylesheet" href="{{ asset('css/clock.css') }}">
    
        <div class="clock-container">
            <div class="clock">
                <div class="clock__second" style="transform: rotate({{ 6 * (now()->second) }}deg);"></div>
                <div class="clock__minute" style="transform: rotate({{ 6 * (now()->minute) }}deg);"></div>
                <div class="clock__hour" style="transform: rotate({{ 30 * (now()->hour % 12) }}deg);"></div>
                <div class="clock__axis"></div>
                @for($i = 0; $i < 60; $i++)
                    <section class="clock__indicator"></section>
                @endfor
            </div>
            
            <div class="persian-date" id="persian-date">
                <!-- The Persian date will be inserted here by PHP -->
                {{ $formattedDate  }}
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
