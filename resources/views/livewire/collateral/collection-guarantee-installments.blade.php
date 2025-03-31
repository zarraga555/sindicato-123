@section('title')
    {{ __('Collection of guarantee installments') }}
@endsection
<div>
    <section class="flex flex-col gap-y-8 py-8 mt-14">
        <x-breadcrumb pageTitle="{{ __('Collection of guarantee installments') }}"
            breadcrumbMainUrl="{{ route('uncollectibleAccounts.index') }}" breadcrumbMain="{{ __('Collaterals') }}"
            breadcrumbCurrent="{{ __('List') }}">
        </x-breadcrumb>
        @include('components.components.messagesFlash')
        @include('components.components.modalPaymentDues')
        <!--Table-->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <div class="pb-4 bg-white dark:bg-gray-900">
                <label for="table-search" class="sr-only">Search</label>
                <div class="relative mt-4 ml-4">
                    <div class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                    </div>
                    <input type="text" id="table-search"
                        class="block pt-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="{{ __('Search for items') }}" wire:model.debounce.500ms="search">
                </div>
            </div>
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            {{ __('Vehicle') }}
                        </th>
                        <th scope="col" class="px-6 py-3">
                            {{ __('Partner or driver') }}
                        </th>
                        <th scope="col" class="px-6 py-3">
                            {{ __('Remaining installments') }}
                        </th>
                        <th scope="col" class="px-6 py-3">
                            {{ __('Status') }}
                        </th>
                        <th scope="col" class="px-6 py-3">
                            {{ __('Amount') }}
                        </th>
                        @can('editar prestamos')
                            <th scope="col" class="px-6 py-3">
                                {{ __('Actions') }}
                            </th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @forelse($collaterals as $collateral)
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $collateral->vehicle_id ? str_pad($collateral->vehicle_id, 3, 0, STR_PAD_LEFT) : 'Sin movil asociado' }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $collateral->driver_partner_name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $collateral->instalments }} {{ __('Fees') }}
                            </td>
                            <td class="px-6 py-4">
                                {{ __($collateral->status) }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $collateral->cash_flows_id ? $collateral->cashFlows->banks->currency_type . '.' : 'Bs.' }}
                                {{ number_format($collateral->amount, 2) }}
                            </td>
                            <!--Revertir cuentas incobrables -->
                            @can('cobrar cobro cuotas de garantia')
                                <td class="px-6 py-4">
                                    <a href="#" wire:click="openModalPayment({{$collateral->id}})" wire:loading.attr="disabled"
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                        {{ __('Pay fee') }}</a>
                                </td>
                            @endcan
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                {{ __('No data found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <!-- Paginación -->
            <div class="mt-4">
                {{ $collaterals->links() }}
            </div>
        </div>
    </section>
</div>
