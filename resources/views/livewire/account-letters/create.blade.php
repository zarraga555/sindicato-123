@section('title')
{{__('Create Bank Account')}}
@endsection

<div>
    <section class="flex flex-col gap-y-8 py-8 mt-14">
        <x-breadcrumb
            pageTitle="{{__('Create Bank Account')}}"
            breadcrumbMainUrl="{{ route('accountLetters.index') }}"
            breadcrumbMain="{{__('Bank Accounts')}}"
            breadcrumbCurrent="{{__('Create')}}"
        >
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
                        id="input1"
                        maxlength="255"
                        type="text"
                        wire:model.live="bank_name">
                </div>
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
                        id="input1"
                        maxlength="255"
                        type="numeric"
                        wire:model.live="account_number">
                </div>
            </div>

            <div>
                <div class="mb-4">
                    <label for="currency_type"
                           class="block text-sm font-medium text-gray-900 dark:text-gray-400">
                        {{__('Currency Type')}}
                    </label>
                    <select id="currency_type" name="currency_type" wire:model.live="currency_type"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value=" ">{{__('Select an option')}}</option>
                        <option value="Bs">Bolivianos (Bs.)</option>
                        <option value="$us">Dolares ($us.)</option>
                    </select>
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
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium leading-6 text-gray-950 dark:text-white" for="initial_account_amount">
                    {{__('Opening Amount')}}
                    <sup class="text-danger-600 dark:text-danger-400 font-medium">*</sup>
                </label>
                <div
                    class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white dark:bg-white/5 focus-within:ring-2 ring-gray-950/10 dark:ring-white/20 focus-within:ring-primary-600 dark:focus-within:ring-primary-500 fi-fo-text-input overflow-hidden">
                    <input
                        class="fi-input block w-full border-none py-1.5 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 dark:text-white dark:placeholder:text-gray-500 sm:text-sm sm:leading-6 bg-transparent ps-3 pe-3"
                        id="input1"
                        maxlength="255"
                        type="numeric"
                        wire:model.live="initial_account_amount">
                </div>
            </div>
        </div>
        <div class="fi-form-actions">
            <div class="fi-ac gap-3 flex flex-wrap items-center justify-start">

                <!-- Botón Guardar -->
                <button
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
                    class="fi-btn relative grid-flow-col items-center justify-center font-semibold transition duration-75 rounded-lg px-3 py-2 text-sm inline-grid shadow-sm bg-indigo-600 text-white hover:bg-custom-500 focus-visible:ring-2 focus-visible:ring-custom-500/50 dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50 gap-1.5 fi-ac-action fi-ac-btn-action"
                    type="submit"
                    wire:loading.attr="disabled"
                    x-bind:disabled="isProcessing"
                    id="save-button"
                    wire:click="save"
                >
                    <!-- Indicador de carga -->
                    <svg
                        class="animate-spin fi-btn-icon h-5 w-5 text-white"
                        wire:loading.delay.default=""
                        wire:target="create"
                        x-show="isProcessing"
                    >
                        <path clip-rule="evenodd"
                              d="M12 19C15.866 19 19 15.866 19 12C19 8.13401 15.866 5 12 5C8.13401 5 5 8.13401 5 12C5 15.866 8.13401 19 12 19ZM12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"
                              fill-rule="evenodd" fill="currentColor" opacity="0.2"></path>
                        <path d="M2 12C2 6.47715 6.47715 2 12 2V5C8.13401 5 5 8.13401 5 12H2Z"
                              fill="currentColor"></path>
                    </svg>

                    <!-- Etiquetas de texto -->
                    <span x-show="!isProcessing" class="fi-btn-label">Guardar</span>
                    <span x-show="isProcessing" x-text="processingMessage" class="fi-btn-label"
                          style="display: none;"></span>
                </button>

                <!-- Botón Guardar y Crear Otro -->
                <button
                    wire:loading.attr="disabled"
                    wire:click="saveAndCreateAnother"
                    class="fi-btn relative grid-flow-col items-center justify-center font-semibold transition duration-75 rounded-lg px-3 py-2 text-sm inline-grid shadow-sm bg-white text-gray-950 hover:bg-gray-50 dark:bg-white/5 dark:text-white dark:hover:bg-white/10 ring-1 ring-gray-950/10 dark:ring-white/20 gap-1.5 fi-ac-action fi-ac-btn-action"
                    id="create-another-button"
                >
                    <!-- Indicador de carga -->
                    <svg
                        class="animate-spin fi-btn-icon h-5 w-5 text-gray-400 dark:text-gray-500"
                        wire:loading.delay.default=""
                        wire:target="createAnother"
                    >
                        <path clip-rule="evenodd"
                              d="M12 19C15.866 19 19 15.866 19 12C19 8.13401 15.866 5 12 5C8.13401 5 5 8.13401 5 12C5 15.866 8.13401 19 12 19ZM12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"
                              fill-rule="evenodd" fill="currentColor" opacity="0.2"></path>
                        <path d="M2 12C2 6.47715 6.47715 2 12 2V5C8.13401 5 5 8.13401 5 12H2Z"
                              fill="currentColor"></path>
                    </svg>

                    <span class="fi-btn-label">Guardar y crear otro</span>
                </button>

                <!-- Botón Cancelar -->
                <button
                    x-on:click="document.referrer ? window.history.back() : (window.location.href = '/admin/units')"
                    class="fi-btn relative grid-flow-col items-center justify-center font-semibold transition duration-75 rounded-lg px-3 py-2 text-sm inline-grid shadow-sm bg-white text-gray-950 hover:bg-gray-50 dark:bg-white/5 dark:text-white dark:hover:bg-white/10 ring-1 ring-gray-950/10 dark:ring-white/20 gap-1.5 fi-ac-action fi-ac-btn-action"
                    id="cancel-button"
                >
                    <span class="fi-btn-label">Cancelar</span>
                </button>

            </div>
        </div>
    </section>
</div>
