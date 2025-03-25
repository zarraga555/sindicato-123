@section('title')
    {{ __('Company Settings') }}
@endsection
<x-app-layout>
    <div>
        <section class="flex flex-col gap-y-8 py-8 mt-14">
            <x-breadcrumb pageTitle="{{ __('Company Settings') }}" breadcrumbMainUrl="{{ route('settings.company') }}"
                breadcrumbMain="    {{ __('Settings') }}" breadcrumbCurrent="    {{ __('Information') }}">
            </x-breadcrumb>
            <div class="mt-10 sm:mt-0">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px-0">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">{{ __('Company Settings') }}</h3>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ __('Manage and update your company information, including name, address, contact and custom settings.') }}
                            </p>
                        </div>
                    </div>
                    <div class="mt-5 md:col-span-2 md:mt-0">
                        <form action="{{ route('settings.company.update') }}" method="POST">
                            @csrf
                            <div class="overflow-hidden shadow sm:rounded-md">
                                <div class="bg-white px-4 py-5 sm:p-6">
                                    <div class="grid grid-cols-3 gap-6"> <!-- Cambié a 3 columnas -->
                                        @php
                                            $fields = [
                                                'EMPRESA_NOMBRE' => 'Company Name',
                                                'EMPRESA_REFERENCIA' => 'Company address',
                                                'EMPRESA_CODIGO_POSTAL' => 'Postal Code',
                                                'EMPRESA_CIUDAD' => 'City',
                                                'EMPRESA_ESTADO' => 'State',
                                                'EMPRESA_PAIS' => 'Country',
                                                'EMPRESA_TELEFONO' => 'Phone Number',
                                                'EMPRESA_CORREO' => 'Email Address',
                                            ];
                                        @endphp

                                        @foreach ($fields as $name => $label)
                                            <div class="col-span-1">
                                                <!-- Aquí ajusté para que ocupe 1 columna de las 3 disponibles -->
                                                <label for="{{ $name }}"
                                                    class="block text-sm font-medium text-gray-700">{{ __($label) }}</label>
                                                <input type="text" name="{{ $name }}"
                                                    id="{{ $name }}"
                                                    value="{{ old($name, env(strtoupper($name))) }}"
                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                    placeholder="{{ __('Enter ' . $label) }}">
                                                @error($name)
                                                    <span class="invalid-{{ $name }}" role="alert">
                                                        <strong class="text-red-500">{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
                                    <button type="submit"
                                        class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                        {{ __('Save Changes') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </section>
    </div>
</x-app-layout>
