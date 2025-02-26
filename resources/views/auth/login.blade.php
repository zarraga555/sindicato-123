@section('title')
    {{ __('Login') }}
@endsection
<x-guest-layout>
    <!-- component -->
    <div class="h-screen flex flex-col lg:flex-row">
        <!-- Left Section (Will be hidden in small screens) -->
        <div
            class="hidden lg:flex w-full lg:w-1/2 bg-gradient-to-tr from-blue-800 to-purple-700 justify-around items-center p-6">
            <div>
                <h1 class="text-white font-bold text-4xl font-sans">Sintrak</h1>
                <p class="text-white mt-1">{{ env('EMPRESA_NOMBRE') }}</p>
                <a href="https://zarraga.dev/" target="_blank"
                    class="block w-full sm:w-28 bg-white text-indigo-800 mt-4 py-2 rounded-2xl font-bold mb-2 text-center">
                    {{ __('Read More') }}
                </a>
            </div>
        </div>

        <!-- Right Section (Always visible) -->
        <div class="flex w-full lg:w-1/2 justify-center items-center bg-white p-6">

            <form method="POST" action="{{ route('login') }}" class="bg-white w-full max-w-sm">
                <x-validation-errors class="mb-4" />

                @session('status')
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ $value }}
                    </div>
                @endsession

                @csrf
                <h1 class="text-gray-800 font-bold text-2xl mb-1">{{ __('Welcome back!') }}</h1>
                <p class="text-sm font-normal text-gray-600 mb-7">{{ __('Please enter your credentials to continue.') }}
                </p>
                <div>
                    <x-label for="email" value="{{ __('Email') }}" />
                    <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                        required autofocus autocomplete="username" />
                </div>

                <div class="mt-4">
                    <x-label for="password" value="{{ __('Password') }}" />
                    <x-input id="password" class="block mt-1 w-full" type="password" name="password" required
                        autocomplete="current-password" />
                </div>

                <div class="block mt-4">
                    <label for="remember_me" class="flex items-center">
                        <x-checkbox id="remember_me" name="remember" />
                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-4">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                    <x-button class="ms-4">
                        {{ __('Log in') }}
                    </x-button>
                </div>
            </form>

        </div>
    </div>
</x-guest-layout>
