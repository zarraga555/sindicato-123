@section('title')
    {{ __('Edit collateral') }}
@endsection
<div>
    <section class="flex flex-col gap-y-8 py-8 mt-14">
        <x-breadcrumb pageTitle="{{ __('Edit collateral') }}" breadcrumbMainUrl="{{ route('collateral.index') }}"
            breadcrumbMain="{{ __('Collaterals') }}" breadcrumbCurrent="{{ __('Edit') }}">
            @if (!$showButtonDelete)
                @can('eliminar prestamos')
                    <!-- Contenido dentro del slot, como el botón de creación -->
                    <a href="#" wire:click="openDelete"
                        class="fi-btn bg-red-500 text-white hover:bg-custom-500 rounded-lg px-3 py-2 text-sm font-semibold inline-flex items-center shadow-sm transition duration-75">
                        {{ __('Delete') }}
                    </a>
                @endcan
            @endif
        </x-breadcrumb>
        @include('components.components.messagesFlash')
        @include('components.components.modalDelete')

        <!-- Personal Information -->
        <fieldset class="border border-gray-300 rounded-md p-3">
            <legend class="text-sm font-medium text-gray-700">{{ __('Personal Information') }}</legend>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <!-- Vehicle number -->
                <div>
                    <label class="block text-sm font-medium leading-6 text-gray-950 dark:text-white" for="vehicle_id">
                        {{ __('Vehicle number') }}
                    </label>
                    <div
                        class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white dark:bg-white/5 focus-within:ring-2 ring-gray-950/10 dark:ring-white/20 focus-within:ring-primary-600 dark:focus-within:ring-primary-500 fi-fo-text-input overflow-hidden">
                        <input
                            class="fi-input block w-full border-none py-1.5 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 dark:text-white dark:placeholder:text-gray-500 sm:text-sm sm:leading-6 bg-transparent ps-3 pe-3"
                            id="vehicle_id" type="number" wire:model.live="vehicle_id">
                    </div>
                    @error('vehicle_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <!-- Select a user -->
                <div>
                    <label for="user_type" class="block text-sm font-medium text-gray-900 dark:text-gray-400">
                        {{ __('Select a user') }}
                    </label>
                    <select id="user_type" name="user_type" wire:model="user_type"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">{{ __('Select an option') }}</option>
                        <option value="partner">{{ __('Partner') }}</option>
                        <option value="driver">{{ __('Driver') }}</option>
                    </select>
                    @error('user_type')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <!-- Name (span 2 columns) -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium leading-6 text-gray-950 dark:text-white"
                        for="driver_partner_name">
                        {{ __('Name') }}
                    </label>
                    <div
                        class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white dark:bg-white/5 focus-within:ring-2 ring-gray-950/10 dark:ring-white/20 focus-within:ring-primary-600 dark:focus-within:ring-primary-500 fi-fo-text-input overflow-hidden">
                        <input
                            class="fi-input block w-full border-none py-1.5 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 dark:text-white dark:placeholder:text-gray-500 sm:text-sm sm:leading-6 bg-transparent ps-3 pe-3"
                            id="driver_partner_name" maxlength="255" type="text"
                            wire:model.live="driver_partner_name">
                    </div>
                    @error('driver_partner_name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <br>
            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium leading-6 text-gray-950 dark:text-white">
                    {{ __('Description') }}
                </label>
                <div
                    class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white dark:bg-white/5 focus-within:ring-2 ring-gray-950/10 dark:ring-white/20 focus-within:ring-primary-600 dark:focus-within:ring-primary-500 fi-fo-text-input overflow-hidden">
                    <textarea id="description" name="description" wire:model.live="description"
                        class="fi-input block w-full border-none py-1.5 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 dark:text-white dark:placeholder:text-gray-500 sm:text-sm sm:leading-6 bg-transparent ps-3 pe-3"
                        rows="2"></textarea>
                </div>
                @error('description')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </fieldset>

        <!-- Collateral information -->
        <fieldset class="border border-gray-300 rounded-md p-3">
            <legend class="text-sm font-medium text-gray-700">{{ __('Collect Information') }}</legend>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <!-- Start date -->
                <div>
                    <label class="block text-sm font-medium leading-6 text-gray-950 dark:text-white" for="start_date">
                        {{ __('Date') }}
                    </label>
                    <div
                        class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white dark:bg-white/5 focus-within:ring-2 ring-gray-950/10 dark:ring-white/20 focus-within:ring-primary-600 dark:focus-within:ring-primary-500 fi-fo-text-input overflow-hidden">
                        <input
                            class="fi-input block w-full border-none py-1.5 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 dark:text-white dark:placeholder:text-gray-500 sm:text-sm sm:leading-6 bg-transparent ps-3 pe-3"
                            id="start_date" type="date" wire:model.live="start_date"
                            {{ $showInputs ? 'disabled' : '' }}>
                    </div>
                    @error('start_date')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <!-- Payment frecuency -->
                <div>
                    <div class="mb-4">
                        <label for="payment_frequency"
                            class="block text-sm font-medium text-gray-900 dark:text-gray-400">
                            {{ __('Payment frecuency') }}
                        </label>
                        <select id="payment_frequency" name="bank_id" wire:model="payment_frequency" {{ $showInputs ? 'disabled' : '' }}
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="">{{ __('Select an option') }}</option>
                            <option value="weekly">{{ __('Weekly') }}</option>
                            <option value="monthly">{{ __('Monthly') }}</option>
                        </select>
                        @error('payment_frequency')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <!-- Number instalments -->
                <div>
                    <label class="block text-sm font-medium leading-6 text-gray-950 dark:text-white" for="instalments">
                        {{ __('Number instalments') }}
                    </label>
                    <div
                        class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white dark:bg-white/5 focus-within:ring-2 ring-gray-950/10 dark:ring-white/20 focus-within:ring-primary-600 dark:focus-within:ring-primary-500 fi-fo-text-input overflow-hidden">
                        <input
                            class="fi-input block w-full border-none py-1.5 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 dark:text-white dark:placeholder:text-gray-500 sm:text-sm sm:leading-6 bg-transparent ps-3 pe-3"
                            id="instalments" type="number" wire:model.live="instalments"
                            {{ $showInputs ? 'disabled' : '' }}>
                    </div>
                    @error('instalments')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <!-- amount -->
                <div>
                    <label class="block text-sm font-medium leading-6 text-gray-950 dark:text-white" for="amount">
                        {{ __('Amount') }}
                    </label>
                    <div
                        class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white dark:bg-white/5 focus-within:ring-2 ring-gray-950/10 dark:ring-white/20 focus-within:ring-primary-600 dark:focus-within:ring-primary-500 fi-fo-text-input overflow-hidden">
                        <input
                            class="fi-input block w-full border-none py-1.5 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 dark:text-white dark:placeholder:text-gray-500 sm:text-sm sm:leading-6 bg-transparent ps-3 pe-3"
                            id="amount" type="number" step="0.01" onblur="formatDecimals(this)"
                            wire:model.live="amount"
                            {{ $showInputs ? 'disabled' : '' }}>
                    </div>
                    @error('amount')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="fi-form-actions">
                <div class="fi-ac gap-3 flex flex-wrap items-center justify-start">
    
                    <!-- Botón Guardar -->
                    <button x-bind:id="$id('key-bindings')"
                        x-mousetrap.global.mod-s="document.getElementById($el.id).click()" x-data="{
                            form: null,
                            isProcessing: false,
                            processingMessage: null,
                        }"
                        x-init="form = $el.closest('form');
                        form?.addEventListener('form-processing-started', (event) => {
                            isProcessing = true;
                            processingMessage = event.detail.message;
                        });
                        form?.addEventListener('form-processing-finished', () => {
                            isProcessing = false;
                        });" x-bind:class="{ 'enabled:opacity-70 enabled:cursor-wait': isProcessing }"
                        class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-primary fi-color-primary fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-indigo-600 text-white hover:bg-custom-500 focus-visible:ring-custom-500/50 dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50 fi-ac-action fi-ac-btn-action"
                        type="submit" wire:loading.attr="disabled" x-bind:disabled="isProcessing" wire:click="update">
                        <!-- Spinner de carga -->
                        <svg fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                            class="animate-spin fi-btn-icon transition duration-75 h-5 w-5 text-white"
                            wire:loading.delay.default="" wire:target="save">
                            <path clip-rule="evenodd"
                                d="M12 19C15.866 19 19 15.866 19 12C19 8.13401 15.866 5 12 5C8.13401 5 5 8.13401 5 12C5 15.866 8.13401 19 12 19ZM12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"
                                fill-rule="evenodd" fill="currentColor" opacity="0.2"></path>
                            <path d="M2 12C2 6.47715 6.47715 2 12 2V5C8.13401 5 5 8.13401 5 12H2Z" fill="currentColor">
                            </path>
                        </svg>
    
                        <!-- Texto del botón -->
                        <span x-show="!isProcessing" class="fi-btn-label">Guardar cambios</span>
                        <span x-show="isProcessing" x-text="processingMessage" class="fi-btn-label"
                            style="display: none;"></span>
                    </button>
    
                    <!-- Botón Cancelar -->
                    <button
                        class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-btn-color-gray fi-color-gray fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-white text-gray-950 hover:bg-gray-50 dark:bg-white/5 dark:text-white dark:hover:bg-white/10 ring-1 ring-gray-950/10 dark:ring-white/20"
                        type="button" wire:loading.attr="disabled"
                        x-on:click="document.referrer ? window.history.back() : (window.location.href = '/admin/categories')">
                        <span class="fi-btn-label">Cancelar</span>
                    </button>
                </div>
            </div>
        </fieldset>
    </section>
</div>
