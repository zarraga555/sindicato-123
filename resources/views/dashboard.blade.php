@section('title')
{{__('Dashboard')}}
@endsection
<x-app-layout>
    <section class="flex flex-col gap-y-8 py-8 mt-14">
        <x-breadcrumb
            pageTitle="{{__('Dashboard')}}"
            breadcrumbMainUrl="{{ route('dashboard') }}"
            breadcrumbMain="{{__('Dashboard')}}"
            breadcrumbCurrent="{{__('Information')}}"
        >
        </x-breadcrumb>

        <div class="p-4 sm:p-6 lg:p-8">
            <!-- Card for User Information -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <div class="flex items-center space-x-4">
                    <!-- Avatar (Optional) -->
                    <img class="h-12 w-12 rounded-full"
                         src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random"
                         alt="User Avatar">

                    <!-- User Information -->
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">¡Bienvenido de nuevo, {{ Auth::user()->name
                            }}!</h2>
                        <p class="text-gray-600">Correo electrónico: {{ Auth::user()->email }}</p>
                    </div>
                </div>

                <!-- Buttons for Profile and Logout -->
                <div class="mt-4 flex space-x-4">
                    <!-- Button to go to Profile -->
                    <a href="{{ route('profile.show') }}"
                       class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                        Ir a mi perfil
                    </a>

                    <!-- Button to logout -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-colors">
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
