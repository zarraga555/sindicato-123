@section('title')
Nuevo Ingresos por movilidad(Senanal)
@endsection

<div>
    <section class="flex flex-col gap-y-8 py-8 mt-14">
        <x-breadcrumb
            pageTitle="Ingreso por Movil"
            breadcrumbMainUrl="{{ route('income.index') }}"
            breadcrumbMain="Ingresos por Moviles"
            breadcrumbCurrent="Crear"
        >
            <!-- Mostrar el total en pantalla -->
            <p>Total: <span id="total">0.00</span></p>
        </x-breadcrumb>
        @include('components.components.messagesFlash')
        <div class="grid flex-1 auto-cols-fr gap-y-8">

            <!-- Pruebas -->
            <fieldset class="border border-gray-300 rounded-md p-3">
                <legend class="text-sm font-medium text-gray-700"> Hojas de Rutas</legend>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <!-- Input 1: Numero de Movil -->
                    <div>
                        <label for="movil" class="block text-sm font-medium text-gray-700">Numero de Movil</label>
                        <input type="text" id="movil" name="movil"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="Ingrese el numero de movil a registrar"
                               maxlength="255"
                               required="required"
                               wire:model="movil">
                    </div>
                    @error('movil') <span class="text-danger">{{ $message }}</span> @enderror

                    <!-- Input 2: Venta Hoja -->
                    <div>
                        <label for="amount_hoja_semanal" class="block text-sm font-medium text-gray-700">Venta de
                            Hoja</label>
                        <input type="number" id="amount_hoja_semanal" name="amount_hoja_semanal"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="Ingresa un monto"
                               maxlength="255"
                               required="required"
                               wire:model="amount_hoja_semanal">
                    </div>
                    @error('hoja_semanal_serie') <span class="text-danger">{{ $message }}</span> @enderror

                    <!-- Input 3: Numero de Serie -->
                    <div>
                        <label for="hoja_semanal_serie" class="block text-sm font-medium text-gray-700">Numero de Serie
                            <sup class="text-danger-600 dark:text-danger-400 font-medium">*</sup></label>
                        <input type="text" id="hoja_semanal_serie" name="hoja_semanal_serie"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="Ingrese el numero de serie"
                               maxlength="255"
                               required="required"
                               wire:model="hoja_semanal_serie">
                    </div>
                    @error('hoja_semanal_serie') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <br>
                <div class="grid grid-cols-2 gap-4">
                    <!-- Venta Hoja Domingo -->
                    <div>
                        <label for="amount_hoja_domingo" class="block text-sm font-medium text-gray-700">Venta de Hoja
                            Domingo</label>
                        <input type="number" id="amount_hoja_domingo" name="amount_hoja_domingo"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="Ingresa un monto"
                               maxlength="255"
                               wire:model="amount_hoja_domingo">
                    </div>
                    @error('amount_hoja_domingo') <span class="text-danger">{{ $message }}</span> @enderror

                    <!-- Numero Series Domingo -->
                    <div>
                        <label for="hoja_domingo_serie" class="block text-sm font-medium text-gray-700">Numero de
                            Serie</label>
                        <input type="text" id="hoja_domingo_serie" name="hoja_domingo_serie"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="Ingrese el numero de serie"
                               maxlength="255"
                               wire:model="hoja_domingo_serie">
                    </div>
                    @error('hoja_domingo_serie') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </fieldset>

            <fieldset class="border border-gray-300 rounded-md p-3">
                <legend class="text-sm font-medium text-gray-700">Otros Cobros</legend>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Input 1: Multas -->
                    <div>
                        <label for="multas" class="block text-sm font-medium text-gray-700">Multas</label>
                        <input type="number" id="multas" name="multas"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="Ingresa un monto"
                               wire:model="multas">
                    </div>
                    @error('multas') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    <!-- Input 2: Lavado -->
                    <div>
                        <label for="lavado_auto" class="block text-sm font-medium text-gray-700">Lavado</label>
                        <input type="number" id="lavado_auto" name="lavado_auto"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="Ingresa un monto"
                               wire:model="lavado_auto">
                    </div>
                    @error('lavado_auto') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    <!-- Input 3: Aporte para Seguro -->
                    <div>
                        <label for="aporte_seguro" class="block text-sm font-medium text-gray-700">Aporte para
                            seguro</label>
                        <input type="number" id="aporte_seguro" name="aporte_seguro"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="Ingresa un monto"
                               wire:model="aporte_seguro">
                    </div>
                    @error('aporte_seguro') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </fieldset>

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
    </section>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            function calcularSuma() {
                let total = 0;
                document.querySelectorAll('[type="number"]').forEach(input => {
                    const valor = parseFloat(input.value) || 0;
                    total += valor;
                });

                // Muestra el total en un campo o en la consola
                console.log("Total:", total);
                document.getElementById("total").textContent = total.toFixed(2);
            }

            // Agregar el evento input a todos los inputs numéricos
            document.querySelectorAll('[type="number"]').forEach(input => {
                input.addEventListener("input", calcularSuma);
            });

            // Llamamos a la función una vez al cargar la página
            calcularSuma();
        });
    </script>

</div>
