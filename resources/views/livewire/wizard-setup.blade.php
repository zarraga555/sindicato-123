<div>
    <div class="flex justify-center items-center min-h-screen bg-gray-100">
        <div class="bg-white shadow-lg rounded-lg p-6 w-full max-w-4xl">
            <!-- Wizard Steps -->
            <ol class="flex justify-between items-center w-full mb-6">
                <!-- Step 1: User Info -->
                <li class="flex flex-col items-center">
                <span class="flex items-center justify-center w-10 h-10 border rounded-full text-lg font-bold
                            @if($step == 1) text-white bg-blue-600 border-blue-600 @else text-gray-500 border-gray-500 @endif">
                    1
                </span>
                    <h3 class="font-medium text-sm mt-2
                          @if($step == 1) text-blue-600 @else text-gray-500 @endif">
                        {{__('User Info')}}
                    </h3>
                </li>
                <div class="w-16 h-1 bg-gray-300"></div>
                <!-- Step 2: Payment Info -->
                <li class="flex flex-col items-center">
                <span class="flex items-center justify-center w-10 h-10 border rounded-full text-lg font-bold
                            @if($step == 2) text-white bg-blue-600 border-blue-600 @else text-gray-500 border-gray-500 @endif">
                    2
                </span>
                    <h3 class="font-medium text-sm mt-2
                          @if($step == 2) text-blue-600 @else text-gray-500 @endif">
                        {{__('Organization information')}}
                    </h3>
                </li>
            </ol>

            <!-- Formulario de pasos -->
            <div class="mt-6">
                @if ($step == 1)
                <div class="grid grid-cols-2 gap-4">
                    <div class="mb-5">
                        <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre Completo <strong
                                class="text-red-500">*</strong></label>
                        <input type="text" id="nombre" name="nombre"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                               placeholder="Ingresa tu nombre"
                               wire:model="nombre" required>
                    </div>
                    <div class="mb-5">
                        <label for="email" class="block text-sm font-medium text-gray-700">Correo electronico <strong
                                class="text-red-500">*</strong></label>
                        <input type="email" id="email" name="email"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                               placeholder="Ingresa tu correo electronico"
                               wire:model="email" required>
                    </div>
                    <div class="mb-5">
                        <label for="password" class="block text-sm font-medium text-gray-700">Constraseña <strong
                                class="text-red-500">*</strong></label>
                        <input type="password" id="password" name="password"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                               placeholder="Escribe tu contraseña"
                               wire:model="password" required>
                    </div>
                    <div class="mb-5">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar
                            Contraseña<strong class="text-red-500">*</strong></label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                               placeholder="Vuelve a escribir tu contraseña"
                               wire:model="password_confirmation" required>
                    </div>
                </div>
                @elseif ($step == 2)
                <div class="grid grid-cols-2 gap-4">
                    <div class="mb-5">
                        <label for="empresa" class="block text-sm font-medium text-gray-700">Nombre de la
                            Organizacion <strong class="text-red-500">*</strong> </label>
                        <input type="text" id="empresa" name="empresa"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                               placeholder=""
                               wire:model="empresa" required>
                    </div>
                    <div class="mb-5">
                        <label for="contacto" class="block text-sm font-medium text-gray-700">Numero de Telefono</label>
                        <input type="number" id="contacto" name="contacto"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                               placeholder=""
                               wire:model="contacto">
                    </div>
                    <div class="mb-5">
                        <label for="referencia" class="block text-sm font-medium text-gray-700">Direccion</label>
                        <input type="text" id="referencia" name="referencia"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                               placeholder=""
                               wire:model="referencia">
                    </div>
                    <div class="mb-5">
                        <label for="ciudad" class="block text-sm font-medium text-gray-700">Ciudad</label>
                        <input type="text" id="ciudad" name="ciudad"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                               placeholder=""
                               wire:model="ciudad">
                    </div>
                    <div class="mb-5">
                        <label for="estado" class="block text-sm font-medium text-gray-700">Estado/Departamento</label>
                        <input type="text" id="estado" name="estado"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                               placeholder=""
                               wire:model="estado">
                    </div>
                    <div class="mb-5">
                        <label for="pais" class="block text-sm font-medium text-gray-700">Pais</label>
                        <input type="text" id="pais" name="pais"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                               placeholder=""
                               wire:model="pais">
                    </div>
                    <div class="mb-5">
                        <label for="logo" class="block text-sm font-medium text-gray-700">Subir Logotipo</label>
                        <input type="file" id="logo" name="logo"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                               accept="image/*"
                               wire:model="logo">
                    </div>
                    <div class="mb-5">
                        <label for="moviles" class="block text-sm font-medium text-gray-700">Cantidad de moviles</label>
                        <input type="number" id="moviles" name="moviles"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                               placeholder=""
                               wire:model="moviles">
                    </div>
                </div>
                @endif
            </div>

            <!-- Botones para navegar entre los pasos -->
            <div class="mt-6 flex justify-between">
                @if ($step > 1)
                <button class="px-4 py-2 bg-gray-300 text-black rounded-md shadow" wire:click="prevStep">Atrás</button>
                @endif
                @if ($step < 2)
                <button class="px-4 py-2 bg-blue-600 text-white rounded-md shadow" wire:click="nextStep">Siguiente
                </button>
                @else
                <button class="px-4 py-2 bg-green-600 text-white rounded-md shadow" wire:click="save">Guardar</button>
                @endif
            </div>
        </div>
    </div>
    <div>
        <!-- Mensaje de éxito -->
        @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
             role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg"
                     viewBox="0 0 20 20">
                    <title>Cerrar</title>
                    <path
                        d="M14.348 5.652a.5.5 0 00-.707 0L10 9.293 6.36 5.652a.5.5 0 10-.707.707L9.293 10l-3.64 3.641a.5.5 0 10.707.707L10 10.707l3.641 3.64a.5.5 0 00.707-.707L10.707 10l3.641-3.641a.5.5 0 000-.707z"/>
                </svg>
            </span>
        </div>
        @endif

        <!-- Mensaje de error -->
        @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg"
                     viewBox="0 0 20 20">
                    <title>Cerrar</title>
                    <path
                        d="M14.348 5.652a.5.5 0 00-.707 0L10 9.293 6.36 5.652a.5.5 0 10-.707.707L9.293 10l-3.64 3.641a.5.5 0 10.707.707L10 10.707l3.641 3.64a.5.5 0 00.707-.707L10.707 10l3.641-3.641a.5.5 0 000-.707z"/>
                </svg>
            </span>
        </div>
        @endif
    </div>
</div>


