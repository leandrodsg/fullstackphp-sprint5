<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechSubs</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-blue-500 p-4">
        <div class="flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-white text-xl font-bold">TechSubs</a>
            <div class="space-x-4">
                <a href="{{ url('/services') }}" class="text-white hover:text-gray-200">Services</a>
                <a href="{{ url('/subscriptions') }}" class="text-white hover:text-gray-200">Subscriptions</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-4">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        @yield('content')
    </div>
</body>
</html>