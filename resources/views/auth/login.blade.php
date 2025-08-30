<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Sign in to TechSubs</h2>
        <p class="mt-1 text-sm text-gray-600">Welcome back!</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input
                id="email"
                type="email"
                name="email"
                class="block w-full mt-2"
                :value="old('email')"
                required
                autocomplete="username"
                autofocus
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-5">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input
                id="password"
                type="password"
                name="password"
                class="block w-full mt-2"
                required
                autocomplete="current-password"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input
                    id="remember_me"
                    type="checkbox"
                    name="remember"
                    class="text-blue-600 rounded border-gray-300 shadow-sm focus:ring-blue-500"
                >
                <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex justify-between items-center mt-6">
            @if (Route::has('password.request'))
                <a
                    class="text-sm text-blue-600 hover:text-blue-900 underline focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    href="{{ route('password.request') }}"
                >
                    {{ __('Forgot password?') }}
                </a>
            @endif

            <x-primary-button class="ml-4">
                {{ __('Sign In') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
