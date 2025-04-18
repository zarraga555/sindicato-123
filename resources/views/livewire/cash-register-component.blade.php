@section('title')
    {{ __('Cash register') }}
@endsection
<div>
    <section class="flex flex-col gap-y-8 py-8 mt-14">
        <x-breadcrumb pageTitle="{{ __('Cash register') }}" breadcrumbMainUrl="{{ route('uncollectibleAccounts.index') }}"
            breadcrumbMain="{{ __('Cash register') }}" breadcrumbCurrent="{{ __('List') }}">
            @can('ver otros ingresos')
                <!-- Contenido dentro del slot, como el botón de creación -->
                <a href="{{ route('otherIncome.create') }}"
                    class="fi-btn bg-orange-500 text-white hover:bg-custom-500 rounded-lg px-3 py-2 text-sm font-semibold inline-flex items-center shadow-sm transition duration-75">
                    {{ __('New entry') }}
                </a>
            @endcan
            @can('crear egreso')
                <!-- Contenido dentro del slot, como el botón de creación -->
                <a href="{{ route('expense.create') }}"
                    class="fi-btn bg-orange-500 text-white hover:bg-custom-500 rounded-lg px-3 py-2 text-sm font-semibold inline-flex items-center shadow-sm transition duration-75">
                    {{ __('New expense') }}
                </a>
            @endcan
            @can('crear ingresos')
                <!-- Contenido dentro del slot, como el botón de creación -->
                <a href="{{ route('income.create') }}"
                    class="fi-btn bg-orange-500 text-white hover:bg-custom-500 rounded-lg px-3 py-2 text-sm font-semibold inline-flex items-center shadow-sm transition duration-75">
                    {{ __('New income from vehicles') }}
                </a>
            @endcan
        </x-breadcrumb>
        @include('components.components.messagesFlash')
        @include('components.components.modalPartialClosing')
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
                        <th scope="col" class="px-6 py-3 date-column">
                            {{ __('Date') }}
                        </th>
                        {{-- <th scope="col" class="px-6 py-3 date-column">
                            {{ __('Vehicule') }}
                        </th> --}}
                        <th scope="col" class="px-6 py-3 amount-column">
                            {{ __('Detail') }}
                        </th>
                        <th scope="col" class="px-6 py-3 bank-column">
                            {{ __('Type') }}
                        </th>
                        <th scope="col" class="px-6 py-3 vehicle-column">
                            {{ __('Status') }}
                        </th>
                        <th scope="col" class="px-6 py-3 item-column">
                            {{ __('Total registration') }}
                        </th>
                        <th scope="col" class="px-6 py-3 amount-column">
                            {{ __('Cash on hand') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cashRegisters as $cashRegister)
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-wrap dark:text-white date-column">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($cashRegister->registration_date)->format('d-m-Y (H:i)') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 vehicle-column">
                                <div class="text-sm text-gray-900">
                                    @if ($cashRegister->vehicle_id != null)
                                        Movil: {{ $cashRegister->vehicle_id }} -
                                        {{ $cashRegister->itemsCashFlow->name }}
                                    @else
                                        {{ $cashRegister->itemsCashFlow->name }}
                                    @endif

                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-wrap bank-column text-gray-900">
                                {{ __($cashRegister->transaction_type_income_expense) }}
                            </td>
                            <td class="px-6 py-4 whitespace-wrap item-column text-gray-900">
                                {{ __($cashRegister->payment_status) }}
                                @if ($cashRegister->payment_type != null)
                                    <br>
                                    {{ '(' . __($cashRegister->payment_type) . ')' }}
                                @endif
                            </td>
                            <td class="px-6 py-4 amount-column text-gray-900">
                                <b>Bs.
                                    {{ $cashRegister->transaction_type_income_expense != 'income' ? '-' : '' }}{{ number_format($cashRegister->amount, 2) }}</b>
                            </td>
                            <td class="px-6 py-4 amount-column text-gray-900">
                                <b>Bs.
                                    {{ $cashRegister->transaction_type_income_expense != 'income' ? '-' : '' }}{{ $cashRegister->payment_type == 'cash' ? number_format($cashRegister->amount, 2) : 0.0 }}</b>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6"
                                class="px-6 py-4 text-center text-gray-500 dark:text-gray-400 font-medium">
                                {{ __('No records were found for today. You can create one now to start recording information.') }}
                            </td>
                        </tr>
                    @endforelse
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td colspan="4" class="px-6 py-4 amount-column text-gray-900 font-medium text-right">
                            <b>{{ __('Total Revenues') }}</b>
                        </td>
                        <td class="px-6 py-4 amount-column text-gray-900"><b>Bs. {{ number_format($totalRegistration,2) }}</b></td>
                        <td class="px-6 py-4 amount-column text-gray-900"><b>Bs. {{ number_format($cashOnHand,2) }}</b></td>
                    </tr>
                </tbody>
            </table>

            <div class="p-4 whitespace-nowrap align-text-center text-sm font-medium relative flex gap-2 justify-end">
                <a wire:click="openModalPartialClosing()" href="#"
                    class="focus:outline-none text-white text-sm py-2.5 px-5 rounded-md bg-gray-500 hover:bg-gray-600 hover:shadow-lg">
                    {{__('Partial Cash Closings')}}
                </a>
                <a wire:click="openModalCashClosing()" href="#"
                    class="focus:outline-none text-white text-sm py-2.5 px-5 rounded-md bg-green-500 hover:bg-green-600 hover:shadow-lg">
                    {{__('Cash closing')}}
                </a>
            </div>

            <!-- Paginación -->
            <div class="">
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
