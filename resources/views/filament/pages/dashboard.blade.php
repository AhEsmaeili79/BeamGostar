{{-- resources/views/filament/pages/dashboard.blade.php --}}

<x-filament::page>
    <h1>Welcome to your dashboard!</h1>

    <div class="my-8">
        <h2 class="text-xl font-bold">Latest Ads</h2>

        <div class="grid grid-cols-3 gap-6 mt-6">
            @foreach($ads as $ad)
                <div class="bg-white p-4 shadow rounded-md">
                    @if($ad->image)
                        <p>Image URL: {{ asset('storage/' . $ad->image) }}</p> <!-- Debugging line -->
                        <img src="{{ asset('storage/' . $ad->image) }}" alt="{{ $ad->title }}" class="w-full h-32 object-cover rounded-md mb-4">
                    @endif
                    <h3 class="font-semibold text-lg">{{ $ad->title }}</h3>
                    <p class="text-sm text-gray-600 mt-2">{{ Str::limit($ad->description, 100) }}</p>
                    @if($ad->url)
                        <a href="{{ $ad->url }}" class="text-blue-600 mt-2 inline-block">Learn More</a>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</x-filament::page>
