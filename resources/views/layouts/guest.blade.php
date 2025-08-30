<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body class="bg-gray-100 font-sans text-gray-900 antialiased h-screen overflow-hidden">

    <nav class="bg-blue-500 px-4 py-3">
        <div class="flex items-center justify-between">
            <a href="{{ url('/') }}" class="text-white font-bold text-xl">TechSubs</a>

            <div class="space-x-4">
                @if (request()->routeIs('login'))
                    <a href="{{ route('register') }}" class="text-white hover:text-gray-200">Register</a>
                @elseif (request()->routeIs('register'))
                    <a href="{{ route('login') }}" class="text-white hover:text-gray-200">Login</a>
                @endif
            </div>
        </div>
    </nav>

    <div class="min-h-screen flex flex-col justify-center items-center pt-12 pb-8">
        <div class="w-full sm:max-w-md bg-white shadow-md overflow-hidden sm:rounded-lg px-6 py-6">
            {{ $slot }}
        </div>
    </div>
    
</body>
</html>
