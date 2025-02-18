@section('title')
Nuevo Igreso
@endsection

<div>
    <section class="flex flex-col gap-y-8 py-8 mt-14">
        <x-breadcrumb
            pageTitle="Nuevo Ingreso"
            breadcrumbMainUrl="{{ route('otherIncome.index') }}"
            breadcrumbMain="Otros Ingresos"
            breadcrumbCurrent="Crear"
        >

            <!-- Contenido dentro del slot, como el botón de creación -->
            <a href="#" wire:click="addCashFlow"
               class="fi-btn bg-gray-600 text-white hover:bg-custom-500 rounded-lg px-3 py-2 text-sm font-semibold inline-flex items-center shadow-sm transition duration-75">
                {{ __('Agregar otro ingreso') }}
            </a>

        </x-breadcrumb>
        @include('components.components.messagesFlash')
        <div>
            <form>

                <!-- Nota informativa -->
                <div
                    x-data="{ show: true }"
                    x-init="setTimeout(() => show = false, 10000)"
                    x-show="show"
                    x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="opacity-0 scale-90"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-500"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-90"
                    class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 rounded-md mb-4" role="alert">
                    <p class="font-bold">Nota:</p>
                    <p>Si el ingreso está asociado a un número de móvil, ingresa el número correspondiente en el campo
                        "Móvil". De lo contrario, déjalo vacío.
                        <br> Selecciona la cuenta de la cual se ingresara el dinero. Si no seleccionas ninguna cuenta,
                        se utilizará automáticamente la primera cuenta bancaria registrada.</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Número de Móvil -->
                    <div>
                        <label for="input1" class="block text-sm font-medium text-gray-700">Número de Móvil</label>
                        <input type="text" id="input1" name="input1"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="Ingresa el móvil"
                               wire:model="vehicle_id">
                    </div>

                    <!-- Fecha de Registro -->
                    <div>
                        <label for="fecha_registro" class="block text-sm font-medium text-gray-700">Fecha de
                            Registro</label>
                        <input type="date" id="fecha_registro" name="fecha_registro"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               wire:model="fecha_registro">
                        @error('fecha_registro') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Cuenta Bancaria -->
                    <div>
                        <div class="mb-4">
                            <label for="bank_id"
                                   class="block text-sm font-medium text-gray-900 dark:text-gray-400">
                                Selecciona una Cuenta Bancaria
                            </label>
                            <select id="bank_id" name="bank_id" wire:model="bank_id"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="">Seleccione una opción</option>
                                @foreach($accountLetters as $item)
                                <option value="{{ $item->id }}"
                                        {{ $item->id === 1 ? 'selected' : '' }}>
                                    {{ $item->bank_name }} ({{ $item->account_number }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="grid gap-6">
                    @foreach($cashFlows as $index => $cashFlow)
                    <div class="grid gap-4 items-center"
                         style="grid-template-columns: repeat({{ $index > 0 ? 4 : 3 }}, minmax(0, 1fr));">
                        <!-- Input de Monto -->
                        <div>
                            <label for="input1-{{ $index }}"
                                   class="block text-sm font-medium text-gray-700">Monto</label>
                            <input type="text" id="input1-{{ $index }}" name="cashFlows[{{ $index }}][amount]"
                                   wire:model="cashFlows.{{ $index }}.amount"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   placeholder="Ingresa un monto">
                            @error("cashFlows.$index.amount") <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Input: Número de Serie -->
                        <div>
                            <label class="block text-sm font-medium text-gray-950 dark:text-white"
                                   for="hoja_semanal_serie">
                                Número de Serie
                                <!--<sup class="text-danger-600 dark:text-danger-400 font-medium">*</sup>-->
                            </label>
                            <input
                                class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                id="hoja_semanal_serie"
                                maxlength="255"
                                required
                                type="text"
                                wire:model="cashFlows.{{ $index }}.serie"
                                placeholder="Ingrese el número de serie">
                            @error("cashFlows.$index.serie") <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Select de Item -->
                        <div>
                            <label for="selectCashFlow-{{ $index }}" class="block text-sm font-medium text-gray-900">
                                Selecciona un item de ingreso
                            </label>
                            <select id="selectCashFlow-{{ $index }}" name="cashFlows[{{ $index }}][cashFlowId]"
                                    wire:model="cashFlows.{{ $index }}.cashFlowId"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="" disabled>Seleccione una opción</option>
                                @foreach($itemsCashFlows as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error("cashFlows.$index.cashFlowId") <span
                                class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Botón de Eliminar (solo si hay más de un elemento) -->
                        @if($index > 0)
                        <div class="flex justify-center">
                            <button type="button" wire:click="removeCashFlow({{ $index }})"
                                    class="bg-red-500 text-white hover:bg-red-600 rounded-lg px-3 py-2 text-sm font-semibold shadow-sm transition duration-75">
                                <span>Eliminar</span>
                            </button>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>

            </form>


            <div class="fi-form-actions mt-4">
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
        </div>
    </section>
