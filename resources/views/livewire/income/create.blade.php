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

        <div class="grid flex-1 auto-cols-fr gap-y-8">

            <!-- Pruebas -->
            <fieldset class="border border-gray-300 rounded-md p-3">
                <legend class="text-sm font-medium text-gray-700"> Hojas de Rutas</legend>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <!-- Input 1: Numero de Movil -->
                    <div>
                        <label class="block text-sm font-medium leading-6 text-gray-950 dark:text-white" for="movil">
                            Numero de Movil
                            <sup class="text-danger-600 dark:text-danger-400 font-medium">*</sup>
                        </label>
                        <div
                            class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white dark:bg-white/5 focus-within:ring-2 ring-gray-950/10 dark:ring-white/20 focus-within:ring-primary-600 dark:focus-within:ring-primary-500 fi-fo-text-input overflow-hidden">
                            <input
                                class="fi-input block w-full border-none py-1.5 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 dark:text-white dark:placeholder:text-gray-500 sm:text-sm sm:leading-6 bg-transparent ps-3 pe-3"
                                id="movil"
                                maxlength="255"
                                required="required"
                                type="text"
                                wire:model="movil"
                                placeholder="Ingrese el numero de movil a registrar">
                        </div>
                        @error('movil') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <!-- Input 2: Venta Hoja -->
                    <div>
                        <label class="block text-sm font-medium leading-6 text-gray-950 dark:text-white" for="amount_hoja_semanal">
                            Venta de Hoja
                            <sup class="text-danger-600 dark:text-danger-400 font-medium">*</sup>
                        </label>
                        <div
                            class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white dark:bg-white/5 focus-within:ring-2 ring-gray-950/10 dark:ring-white/20 focus-within:ring-primary-600 dark:focus-within:ring-primary-500 fi-fo-text-input overflow-hidden">
                            <input
                                class="fi-input block w-full border-none py-1.5 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 dark:text-white dark:placeholder:text-gray-500 sm:text-sm sm:leading-6 bg-transparent ps-3 pe-3"
                                id="amount_hoja_semanal"
                                maxlength="255"
                                required="required"
                                type="number"
                                wire:model="amount_hoja_semanal"
                                placeholder="Ingresa un monto">
                        </div>
                        @error('amount_hoja_semanal') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Input 3: Numero de Serie -->
                    <div>
                        <label class="block text-sm font-medium leading-6 text-gray-950 dark:text-white" for="hoja_semanal_serie">
                            Numero de Serie
                            <sup class="text-danger-600 dark:text-danger-400 font-medium">*</sup>
                        </label>
                        <div
                            class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white dark:bg-white/5 focus-within:ring-2 ring-gray-950/10 dark:ring-white/20 focus-within:ring-primary-600 dark:focus-within:ring-primary-500 fi-fo-text-input overflow-hidden">
                            <input
                                class="fi-input block w-full border-none py-1.5 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 dark:text-white dark:placeholder:text-gray-500 sm:text-sm sm:leading-6 bg-transparent ps-3 pe-3"
                                id="hoja_semanal_serie"
                                maxlength="255"
                                required
                                type="text"
                                wire:model="hoja_semanal_serie"
                                placeholder="Ingrese el numero de serie">
                        </div>
                        @error('hoja_semanal_serie') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                <br>
                <div class="grid grid-cols-2 gap-4">
                    <!-- Venta Hoja Domingo -->
                    <div>
                        <label class="block text-sm font-medium leading-6 text-gray-950 dark:text-white" for="amount_hoja_domingo">
                            Venta de Hoja Domingo
                            <!--<sup class="text-danger-600 dark:text-danger-400 font-medium">*</sup>-->
                        </label>
                        <div
                            class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white dark:bg-white/5 focus-within:ring-2 ring-gray-950/10 dark:ring-white/20 focus-within:ring-primary-600 dark:focus-within:ring-primary-500 fi-fo-text-input overflow-hidden">
                            <input
                                class="fi-input block w-full border-none py-1.5 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 dark:text-white dark:placeholder:text-gray-500 sm:text-sm sm:leading-6 bg-transparent ps-3 pe-3"
                                id="amount_hoja_domingo"
                                type="number"
                                wire:model="amount_hoja_domingo"
                                placeholder="Ingresa un monto">
                            @error('amount_hoja_domingo') <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Numero Series Domingo -->
                    <div>
                        <label class="block text-sm font-medium leading-6 text-gray-950 dark:text-white" for="hoja_domingo_serie">
                            Numero de Serie
                            <!--<sup class="text-danger-600 dark:text-danger-400 font-medium">*</sup>-->
                        </label>
                        <div
                            class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white dark:bg-white/5 focus-within:ring-2 ring-gray-950/10 dark:ring-white/20 focus-within:ring-primary-600 dark:focus-within:ring-primary-500 fi-fo-text-input overflow-hidden">
                            <input
                                class="fi-input block w-full border-none py-1.5 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 dark:text-white dark:placeholder:text-gray-500 sm:text-sm sm:leading-6 bg-transparent ps-3 pe-3"
                                id="hoja_domingo_serie"
                                maxlength="255"
                                type="text"
                                wire:model="hoja_domingo_serie"
                                placeholder="Ingrese el numero de serie">
                        </div>
                        @error('hoja_domingo_serie') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
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
