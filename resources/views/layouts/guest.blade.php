<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="alternate icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    @fonts

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">

    <div class="min-h-screen flex flex-col items-center justify-center px-4">
        <div class="w-full max-w-sm">
            <div class="text-center mb-8">
                <img src="/favicon.svg" alt="Brava CMS" class="w-12 h-12 mx-auto">
                <h1 class="mt-4 text-xl font-semibold" style="color: var(--heading-text)">{{ config('app.name', 'Brava CMS') }}</h1>
            </div>

            <div class="card">
                <div class="card-body">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>

</body>
</html>
