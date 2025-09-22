<nav class="bg-blue-500 p-4">
    <div class="flex justify-between items-center">
        <a href="{{ url('/') }}" class="text-white text-xl font-bold">TechSubs</a>
        
        <div class="space-x-4">
            @auth
                <!-- Menus for logged in users -->
                <a href="{{ url('/services') }}" class="text-white hover:text-gray-200">Services</a>
                <a href="{{ url('/subscriptions') }}" class="text-white hover:text-gray-200">Subscriptions</a>
                
                <!-- Logout Button -->
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-white hover:text-gray-200 bg-transparent border-0 cursor-pointer">
                        Logout
                    </button>
                </form>
            @else
                <!-- Buttons for non-logged users -->
                <a href="{{ route('login') }}" class="text-white hover:text-gray-200">Login</a>
                <a href="{{ route('register') }}" class="text-white hover:text-gray-200">Register</a>
            @endauth
        </div>
    </div>
</nav>
