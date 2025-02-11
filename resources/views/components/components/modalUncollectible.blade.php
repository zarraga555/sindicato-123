<!-- 🔹 Modal -->
        <div x-data="{ show: @entangle('showModal') }">
            <div x-show="show" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-md p-6 w-80 max-w-sm">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Buscar Préstamo</h2>

                    <!-- 🔹 Mostrar mensajes de error -->
                    @if (session()->has('error'))
                    <div class="bg-red-100 text-red-700 p-3 mb-4 rounded-lg"
                         x-data="{ show: true }"
                         x-init="setTimeout(() => show = false, 3000)"
                         x-show="show"
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0 scale-90"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-500"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-90"
                    >
                        {{ session('error') }}
                    </div>
                    @endif

                    <!-- 🔹 Input de búsqueda -->
                    <div class="flex mb-4">
                        <input wire:model.defer="searchMobile" type="text" placeholder="Ingrese número de móvil"
                               class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-gray-700 placeholder-gray-400">
                        <button wire:click="searchLoan"
                                class="ml-2 p-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none">
                            🔍
                        </button>
                    </div>

                    <!-- 🔹 Información del préstamo -->
                    @if ($loanInfo)
                    <div class="text-sm text-gray-700 mb-4">
                        <p><strong>Cliente:</strong> {{ $loanInfo->driver_partner_name }}</p>
                        <p><strong>Monto Deuda:</strong> {{ number_format($loanInfo->amountLoan, 2) }} Bs.</p>
                    </div>

                    <!-- 🔹 Mostrar mensaje de incobrable y ocultar botón -->
                    @if ($loanInfo->incobrable)
                        <p class="text-sm text-red-600">Este préstamo ya es una cuenta incobrable.</p>
                    @else
                        <!-- 🔹 Confirmar como incobrable -->
                        <button wire:click="markAsUncollectible"
                                class="w-full py-2 text-white bg-red-600 hover:bg-red-700 rounded-lg focus:outline-none transition-all">
                            Marcar como Incobrable
                        </button>
                    @endif
                    @endif

                    <!-- 🔹 Cancelar -->
                    <button @click="show = false" wire:click="closeModal"
                            class="mt-4 w-full py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg focus:outline-none transition-all">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
