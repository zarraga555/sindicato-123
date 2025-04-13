@section('title')
    {{ __('Cash Drawers') }}
@endsection
<div>
    <section class="flex flex-col gap-y-8 py-8 mt-14">
        <x-breadcrumb pageTitle="{{ __('Cash Drawers') }}" breadcrumbMainUrl="{{ route('uncollectibleAccounts.index') }}"
            breadcrumbMain="{{ __('Cash Drawers') }}" breadcrumbCurrent="{{ __('List') }}">
        </x-breadcrumb>
        @include('components.components.messagesFlash')
        @include('components.components.modalCashClosing')
        <!-- Tabla -->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <div class="pb-4 bg-white dark:bg-gray-900 px-4 flex justify-between items-center flex-wrap">
                <!-- Contenedor del input -->
                <div class="relative mt-4">
                    <label for="table-search" class="sr-only">Search</label>
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                    </div>
                    <input type="text" id="table-search"
                        class="block pt-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="{{ __('Search for items') }}" wire:model.debounce.500ms="search"
                        x-on:keyup="if ($event.key === 'Enter') { $wire.$refresh() }">
                </div>
            </div>
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3 vehicle-column">
                            {{ __('Opening date') }}
                        </th>
                        <th scope="col" class="px-6 py-3 date-column">
                            {{ __('Closing date') }}
                        </th>
                        <th scope="col" class="px-6 py-3 bank-column">
                            {{ __('Total registration') }}
                        </th>
                        <th scope="col" class="px-6 py-3 item-column">
                            {{ __('Total declared') }}
                        </th>
                        <th scope="col" class="px-6 py-3 amount-column">
                            {{ __('Difference') }}
                        </th>
                        <th scope="col" class="px-6 py-3 amount-column">
                            {{ __('Status') }}
                        </th>
                        <th scope="col" class="px-6 py-3 amount-column">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cashRegisters as $cashRegister)
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-wrap dark:text-white vehicle-column">
                                {{ \Carbon\Carbon::parse($cashRegister->start_time)->locale(app()->getLocale())->translatedFormat('d M Y - h:i') }}
                            </td>
                            <td class="px-6 py-4 date-column">
                                {{ $cashRegister->end_time
                                    ? \Carbon\Carbon::parse($cashRegister->end_time)->locale(app()->getLocale())->translatedFormat('d M Y - h:i')
                                    : __('Unclosed cash register') }}
                            </td>
                            <td class="px-6 py-4 whitespace-wrap bank-column">
                                {{ number_format($cashRegister->total_calculated, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-wrap item-column">
                                {{ number_format($cashRegister->total_declared, 2) }}
                            </td>
                            <td class="px-6 py-4 amount-column">
                                {{ number_format($cashRegister->difference, 2) }}
                            </td>
                            <td class="px-6 py-4 amount-column">
                                {{ __($cashRegister->status) }}
                            </td>
                            <td class="px-6 py-4 amount-column">
                                @if ($cashRegister->status === 'parcial')
                                    <a href="#" wire:click="openModalCashClosing({{ $cashRegister->id }})"
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                        {{ __('Close') }}
                                    </a>|
                                @endif
                                <a href="{{ route('cashDrawer.show', $cashRegister->id) }}"
                                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                    {{ __('View') }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                {{ __('No records were found for today. You can create one now to start recording information.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <!-- Paginación -->
            <div class="p-4">
                {{ $cashRegisters->links() }}
            </div>
        </div>

        <!-- CSS personalizado para controlar la visibilidad en dispositivos móviles y de escritorio -->
        <style>
            /* En pantallas grandes, se muestran todas las columnas */
            @media (min-width: 640px) {

                .date-column,
                .bank-column,
                .item-column,
                .amount-column,
                .action-column {
                    display: table-cell;
                }
            }

            /* En pantallas pequeñas, ocultar las columnas no necesarias */
            @media (max-width: 639px) {

                .date-column,
                .bank-column {
                    display: none;
                }

                .vehicle-column,
                .item-column,
                .amount-column,
                .action-column {
                    display: table-cell;
                }
            }
        </style>
    </section>
</div>
