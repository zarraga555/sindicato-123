@section('title')
    {{ __('Create new role') }}
@endsection
<div>
    <section class="flex flex-col gap-y-8 py-8 mt-14">
        <x-breadcrumb pageTitle="{{ __('Create new role') }}" breadcrumbMainUrl="{{ route('role.index') }}"
            breadcrumbMain="{{ __('Roles') }}" breadcrumbCurrent="{{ __('Create') }}">
        </x-breadcrumb>

        <!-- input1 -->
        <div>
            <label class="block text-sm font-medium leading-6 text-gray-950 dark:text-white" for="name">
                {{ __('Name') }}
                <!--<sup class="text-danger-600 dark:text-danger-400 font-medium">*</sup> -->
            </label>
            <div
                class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white dark:bg-white/5 focus-within:ring-2 ring-gray-950/10 dark:ring-white/20 focus-within:ring-primary-600 dark:focus-within:ring-primary-500 fi-fo-text-input overflow-hidden">
                <input
                    class="fi-input block w-full border-none py-1.5 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 dark:text-white dark:placeholder:text-gray-500 sm:text-sm sm:leading-6 bg-transparent ps-3 pe-3"
                    id="name" maxlength="255" type="text" wire:model.live="name">
            </div>
            @error('name')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror

            <br>
            <!-- Mostrar permisos agrupados por módulo -->
            <div class="mb-4">
                <h2 class="text-lg font-semibold">{{ __('Select the permissions for this role') }}:</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 gap-4">
                    @foreach ($modules as $module => $permissions)
                        <fieldset class="border border-gray-300 p-4 rounded-lg shadow-md">
                            <legend class="font-semibold text-lg text-gray-700">{{ $module }}</legend>
                            <div class="space-y-2 mt-2">
                                @foreach ($permissions as $permission)
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" wire:model="selectedPermissions"
                                            value="{{ $permission->id }}"
                                            class="rounded text-blue-500 focus:ring focus:ring-blue-300">
                                        <span class="text-gray-800">{{ ucfirst($permission->name) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </fieldset>
                    @endforeach
                </div>
                @error('permissions')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="fi-form-actions">
            <div class="fi-ac gap-3 flex flex-wrap items-center justify-start">

                <!-- Botón Guardar -->
                <button x-data="{
                    form: null,
                    isProcessing: false,
                    processingMessage: null,
                }" x-init="form = $el.closest('form');
                form?.addEventListener('form-processing-started', (event) => {
                    isProcessing = true;
                    processingMessage = event.detail.message;
                });
                form?.addEventListener('form-processing-finished', () => {
                    isProcessing = false;
                });"
                    x-bind:class="{ 'enabled:opacity-70 enabled:cursor-wait': isProcessing }"
                    class="fi-btn relative grid-flow-col items-center justify-center font-semibold transition duration-75 rounded-lg px-3 py-2 text-sm inline-grid shadow-sm bg-indigo-600 text-white hover:bg-custom-500 focus-visible:ring-2 focus-visible:ring-custom-500/50 dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50 gap-1.5 fi-ac-action fi-ac-btn-action"
                    type="submit" wire:loading.attr="disabled" x-bind:disabled="isProcessing" id="save-button"
                    wire:click="save">
                    <!-- Indicador de carga -->
                    <svg class="animate-spin fi-btn-icon h-5 w-5 text-white" wire:loading.delay.default=""
                        wire:target="create" x-show="isProcessing">
                        <path clip-rule="evenodd"
                            d="M12 19C15.866 19 19 15.866 19 12C19 8.13401 15.866 5 12 5C8.13401 5 5 8.13401 5 12C5 15.866 8.13401 19 12 19ZM12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"
                            fill-rule="evenodd" fill="currentColor" opacity="0.2"></path>
                        <path d="M2 12C2 6.47715 6.47715 2 12 2V5C8.13401 5 5 8.13401 5 12H2Z" fill="currentColor">
                        </path>
                    </svg>

                    <!-- Etiquetas de texto -->
                    <span x-show="!isProcessing" class="fi-btn-label">Guardar</span>
                    <span x-show="isProcessing" x-text="processingMessage" class="fi-btn-label"
                        style="display: none;"></span>
                </button>

                <!-- Botón Guardar y Crear Otro -->
                <button wire:loading.attr="disabled" wire:click="saveAndCreateAnother"
                    class="fi-btn relative grid-flow-col items-center justify-center font-semibold transition duration-75 rounded-lg px-3 py-2 text-sm inline-grid shadow-sm bg-white text-gray-950 hover:bg-gray-50 dark:bg-white/5 dark:text-white dark:hover:bg-white/10 ring-1 ring-gray-950/10 dark:ring-white/20 gap-1.5 fi-ac-action fi-ac-btn-action"
                    id="create-another-button">
                    <!-- Indicador de carga -->
                    <svg class="animate-spin fi-btn-icon h-5 w-5 text-gray-400 dark:text-gray-500"
                        wire:loading.delay.default="" wire:target="createAnother">
                        <path clip-rule="evenodd"
                            d="M12 19C15.866 19 19 15.866 19 12C19 8.13401 15.866 5 12 5C8.13401 5 5 8.13401 5 12C5 15.866 8.13401 19 12 19ZM12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"
                            fill-rule="evenodd" fill="currentColor" opacity="0.2"></path>
                        <path d="M2 12C2 6.47715 6.47715 2 12 2V5C8.13401 5 5 8.13401 5 12H2Z" fill="currentColor">
                        </path>
                    </svg>

                    <span class="fi-btn-label">Guardar y crear otro</span>
                </button>

                <!-- Botón Cancelar -->
                <button x-on:click="document.referrer ? window.history.back() : (window.location.href = '/admin/units')"
                    class="fi-btn relative grid-flow-col items-center justify-center font-semibold transition duration-75 rounded-lg px-3 py-2 text-sm inline-grid shadow-sm bg-white text-gray-950 hover:bg-gray-50 dark:bg-white/5 dark:text-white dark:hover:bg-white/10 ring-1 ring-gray-950/10 dark:ring-white/20 gap-1.5 fi-ac-action fi-ac-btn-action"
                    id="cancel-button">
                    <span class="fi-btn-label">Cancelar</span>
                </button>

            </div>
        </div>
        <div>
            <!-- Mensaje de éxito -->
            @if (session()->has('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                        <svg class="fill-current h-6 w-6 text-green-500" role="button"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <title>Cerrar</title>
                            <path
                                d="M14.348 5.652a.5.5 0 00-.707 0L10 9.293 6.36 5.652a.5.5 0 10-.707.707L9.293 10l-3.64 3.641a.5.5 0 10.707.707L10 10.707l3.641 3.64a.5.5 0 00.707-.707L10.707 10l3.641-3.641a.5.5 0 000-.707z" />
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
                                d="M14.348 5.652a.5.5 0 00-.707 0L10 9.293 6.36 5.652a.5.5 0 10-.707.707L9.293 10l-3.64 3.641a.5.5 0 10.707.707L10 10.707l3.641 3.64a.5.5 0 00.707-.707L10.707 10l3.641-3.641a.5.5 0 000-.707z" />
                        </svg>
                    </span>
                </div>
            @endif
        </div>
    </section>
</div>
