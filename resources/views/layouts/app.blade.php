<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <title>{{ config('app.name') }}</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body class="bg-gray-100 font-sans">
    <div>
        @include('layouts.navigation')

        @if (isset($header))
            <div class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-6">
                    {{ $header }}
                </div>
            </div>
        @endif

        <main class="py-12">
            <div class="max-w-7xl mx-auto px-4">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>