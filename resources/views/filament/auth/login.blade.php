<!-- resources/views/vendor/filament/auth/login.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background-image: url('{{ $background_url }}');
            background-size: cover;
            background-position: center;
            height: 100vh;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="min-h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="space-y-8">
            <div>
                <h2 class="text-center text-3xl font-bold tracking-tight text-gray-900">
                    {{ __('Login') }}
                </h2>
            </div>
            <div>
                @livewire('filament.auth.login-form')
            </div>
        </div>
    </div>
</body>
</html>
