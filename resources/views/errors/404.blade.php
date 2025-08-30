<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Page Not Found</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans antialiased">

    <nav class="bg-blue-500 py-4 px-6">
        <div class="flex items-center justify-between">
            <a href="{{ url('/') }}" class="text-white font-bold text-xl">TechSubs</a>
        </div>
    </nav>

    <main class="flex items-center justify-center h-[calc(100vh-80px)]">
        <div class="text-center">
            <h1 class="text-6xl text-gray-900 font-extrabold mb-3">404</h1>
            <p class="text-lg text-gray-600 mb-6">Page not found</p>
            <button
                onclick="history.back()"
                class="bg-blue-500 text-white px-5 py-2 rounded hover:bg-blue-600"
            >
                Back
            </button>
        </div>
    </main>

</body>
</html>