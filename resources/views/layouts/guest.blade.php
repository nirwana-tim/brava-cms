<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Brava CMS') }}</title>

    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="alternate icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    @fonts

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white text-slate-800 min-h-screen">

    <div class="min-h-screen flex py-5 px-3 sm:px-4 lg:px-6 gap-4 sm:gap-6 lg:gap-8 bg-white">
        {{-- Left: Brand image --}}
        <div class="hidden lg:flex lg:w-1/2 relative rounded-3xl overflow-hidden shadow-lg">
            <img src="/assets/login-real.webp" alt="BRAVA" class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0 flex flex-col justify-between p-10"
                style="background: linear-gradient(180deg, rgba(19,36,125,0.55) 0%, rgba(19,36,125,0) 30%, rgba(19,36,125,0) 60%, rgba(19,36,125,0.78) 100%);">

                {{-- Brand top-left --}}
                <div class="flex items-center gap-3">
                    <img src="/favicon.svg" alt="BRAVA" class="w-9 h-9 object-contain" style="filter: brightness(0) invert(1);">
                    <span class="font-bold italic text-white tracking-tight" style="font-size: 25px; line-height: 1.2;">BRAVA</span>
                </div>

                {{-- Bottom quote --}}
                <div class="max-w-md">
                    <p class="text-white" style="opacity: 0.7; font-weight: 500; font-size: 20px; line-height: 1.4;">// Growing Stronger, Together.</p>
                    <blockquote class="mt-3 text-white italic leading-snug"
                        style="font-size: 32px;">
                        &ldquo;Every contribution matters. Together, we build, earn trust, and drive BRAVA toward a stronger future.&rdquo;
                    </blockquote>
                </div>
            </div>
        </div>

        {{-- Right: Auth form --}}
        <div class="flex-1 flex flex-col">
            <div class="flex-1 flex items-center justify-center p-6 sm:p-8 lg:p-12">
                {{ $slot }}
            </div>
        </div>
    </div>

</body>
</html>