@section('title')
Editar Cuenta Bancaria
@endsection
<div>
    <section class="flex flex-col gap-y-8 py-8 mt-14">
        <x-breadcrumb
            pageTitle="Editar Cuenta Bancaria"
            breadcrumbMainUrl="{{ route('accountLetters.index') }}"
            breadcrumbMain="Cuentas Bancarias"
            breadcrumbCurrent="Editar"
        >
            @can('eliminar cuentas bancarias')
            <!-- Contenido dentro del slot, como el botón de creación -->
            <a href="#" wire:click="openDelete"
               class="fi-btn bg-red-500 text-white hover:bg-custom-500 rounded-lg px-3 py-2 text-sm font-semibold inline-flex items-center shadow-sm transition duration-75">
                {{ __('Borrar') }}
            </a>
            @endcan
        </x-breadcrumb>

        <div class="grid grid-cols-2 gap-4">
            <!-- Multas -->
            <div>
                <label class="block text-sm font-medium leading-6 text-gray-950 dark:text-white" for="account_name">
                    {{__('Account Name')}}
                    <!--<sup class="text-danger-600 dark:text-danger-400 font-medium">*</sup> -->
                </label>
                <div
                    class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white dark:bg-white/5 focus-within:ring-2 ring-gray-950/10 dark:ring-white/20 focus-within:ring-primary-600 dark:focus-within:ring-primary-500 fi-fo-text-input overflow-hidden">
                    <input
                        class="fi-input block w-full border-none py-1.5 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 dark:text-white dark:placeholder:text-gray-500 sm:text-sm sm:leading-6 bg-transparent ps-3 pe-3"
                        id="input1"
                        maxlength="255"
                        type="text"
                        wire:model.live="account_name">
                </div>
                @error('account_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium leading-6 text-gray-950 dark:text-white" for="bank_name">
                    {{__('Bank Name')}}
                    <sup class="text-danger-600 dark:text-danger-400 font-medium">*</sup>
                </label>

                <div
                    class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white dark:bg-white/5 focus-within:ring-2 ring-gray-950/10 dark:ring-white/20 focus-within:ring-primary-600 dark:focus-within:ring-primary-500 fi-fo-text-input overflow-hidden">
                    <input
                        class="fi-input block w-full border-none py-1.5 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 dark:text-white dark:placeholder:text-gray-500 sm:text-sm sm:leading-6 bg-transparent ps-3 pe-3"
                        id="bank_name"
                        maxlength="255"
                        type="text"
                        wire:model.live="bank_name">
                </div>
                @error('bank_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium leading-6 text-gray-950 dark:text-white" for="account_number">
                    {{__('Account Number')}}
                    <sup class="text-danger-600 dark:text-danger-400 font-medium">*</sup>
                </label>
                <div
                    class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white dark:bg-white/5 focus-within:ring-2 ring-gray-950/10 dark:ring-white/20 focus-within:ring-primary-600 dark:focus-within:ring-primary-500 fi-fo-text-input overflow-hidden">
                    <input
                        class="fi-input block w-full border-none py-1.5 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 dark:text-white dark:placeholder:text-gray-500 sm:text-sm sm:leading-6 bg-transparent ps-3 pe-3"
                        id="account_number"
                        maxlength="255"
                        type="numeric"
                        wire:model.live="account_number">
                </div>
                @error('account_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <div class="mb-4">
                    <label for="currency_type"
                           class="block text-sm font-medium text-gray-900 dark:text-gray-400">
                        {{__('Currency Type')}}
                    </label>
                    <select id="currency_type" name="currency_type" wire:model.live="currency_type"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            @disabled($verificationTransactions)>
                        <option value=" ">{{__('Select an option')}}</option>
                        <option value="Bs">Bolivianos (Bs.)</option>
                        <option value="$us">Dolares ($us.)</option>
                    </select>
                    @error('currency_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Segundo input -->
            <div>
                <div class="mb-4">
                    <label for="account_type"
                           class="block text-sm font-medium text-gray-900 dark:text-gray-400">
                        {{__('Bank Account Type')}}
                    </label>
                    <select id="account_type" name="account_type" wire:model.live="account_type"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value=" ">{{__('Select an option')}}</option>
                        <option value="Savings bank">{{__('Savings bank')}}</option>
                        <option value="Checking account">{{__('Checking account')}}</option>
                    </select>
                    @error('account_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium leading-6 text-gray-950 dark:text-white"
                       for="initial_account_amount">
                    {{__('Opening Amount')}}
                    <sup class="text-danger-600 dark:text-danger-400 font-medium">*</sup>
                </label>
                <div
                    class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white dark:bg-white/5 focus-within:ring-2 ring-gray-950/10 dark:ring-white/20 focus-within:ring-primary-600 dark:focus-within:ring-primary-500 fi-fo-text-input overflow-hidden">
                    <input
                        class="fi-input block w-full border-none py-1.5 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 dark:text-white dark:placeholder:text-gray-500 sm:text-sm sm:leading-6 bg-transparent ps-3 pe-3"
                        id="initial_account_amount"
                        maxlength="255"
                        type="numeric"
                        wire:model.live="initial_account_amount"
                        @disabled($verificationTransactions)>
                </div>
                @error('initial_account_amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="fi-form-actions">
            <div class="fi-ac gap-3 flex flex-wrap items-center justify-start">

                <!-- Botón Guardar -->
                <button
                    x-bind:id="$id('key-bindings')"
                    x-mousetrap.global.mod-s="document.getElementById($el.id).click()"
                    x-data="{
                form: null,
                isProcessing: false,
                processingMessage: null,
            }"
                    x-init="
                form = $el.closest('form');
                form?.addEventListener('form-processing-started', (event) => {
                    isProcessing = true;
                    processingMessage = event.detail.message;
                });
                form?.addEventListener('form-processing-finished', () => {
                    isProcessing = false;
                });
            "
                    x-bind:class="{ 'enabled:opacity-70 enabled:cursor-wait': isProcessing }"
                    class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-primary fi-color-primary fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-indigo-600 text-white hover:bg-custom-500 focus-visible:ring-custom-500/50 dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50 fi-ac-action fi-ac-btn-action"
                    type="submit"
                    wire:loading.attr="disabled"
                    x-bind:disabled="isProcessing"
                    wire:click="update"
                >
                    <!-- Spinner de carga -->
                    <svg
                        fill="none"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                        class="animate-spin fi-btn-icon transition duration-75 h-5 w-5 text-white"
                        wire:loading.delay.default=""
                        wire:target="save"
                    >
                        <path
                            clip-rule="evenodd"
                            d="M12 19C15.866 19 19 15.866 19 12C19 8.13401 15.866 5 12 5C8.13401 5 5 8.13401 5 12C5 15.866 8.13401 19 12 19ZM12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"
                            fill-rule="evenodd"
                            fill="currentColor"
                            opacity="0.2"
                        ></path>
                        <path
                            d="M2 12C2 6.47715 6.47715 2 12 2V5C8.13401 5 5 8.13401 5 12H2Z"
                            fill="currentColor"
                        ></path>
                    </svg>

                    <!-- Texto del botón -->
                    <span x-show="!isProcessing" class="fi-btn-label">Guardar cambios</span>
                    <span x-show="isProcessing" x-text="processingMessage" class="fi-btn-label"
                          style="display: none;"></span>
                </button>

                <!-- Botón Cancelar -->
                <button
                    class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-btn-color-gray fi-color-gray fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-white text-gray-950 hover:bg-gray-50 dark:bg-white/5 dark:text-white dark:hover:bg-white/10 ring-1 ring-gray-950/10 dark:ring-white/20"
                    type="button"
                    wire:loading.attr="disabled"
                    x-on:click="document.referrer ? window.history.back() : (window.location.href = '/admin/categories')"
                >
                    <span class="fi-btn-label">Cancelar</span>
                </button>
            </div>
        </div>
    </section>
    @include('components.components.modalDelete')
</div>
