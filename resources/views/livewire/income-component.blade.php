@section('title')
Ingresos por movilidad(Senanal)
@endsection
<div>
    <section class="flex flex-col gap-y-8 py-8 mt-14">
        <x-breadcrumb
            pageTitle="Ingresos por Moviles"
            breadcrumbMainUrl="{{ route('income.index') }}"
            breadcrumbMain="Ingresos por Moviles"
            breadcrumbCurrent="Listado"
        >
            @can('crear ingresos')
            <!-- Contenido dentro del slot, como el botón de creación -->
            <a href="{{ route('income.create') }}"
               class="fi-btn bg-orange-500 text-white hover:bg-custom-500 rounded-lg px-3 py-2 text-sm font-semibold inline-flex items-center shadow-sm transition duration-75">
                {{ __('Nuevo ingreso') }}
            </a>
            @endcan
        </x-breadcrumb>
        @include('components.components.messagesFlash')
        <!--Table-->
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
                                  d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </div>
                    <input type="text" id="table-search"
                           class="block pt-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                           placeholder="{{ __('Search for items') }}"
                           wire:model.debounce.500ms="search"
                           x-on:keyup="if ($event.key === 'Enter') { $wire.$refresh() }"
                    >
                </div>

                <!-- Contenedor del total -->
                <div class="mt-4 md:mt-0 md:mr-6">
                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-300">
                        {{ __('Total Income') }}:
                        <span id="total">
                    {{ $incomes->first()? ($incomes->first()->banks ? $incomes->first()->banks->currency_type.'.' : '') : '' }}
                    {{ number_format($totalIncome, 2) }}
                </span>
                    </p>
                </div>
            </div>
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3 date-column">
                        {{ __('Fecha') }}
                    </th>
                    <th scope="col" class="px-6 py-3 vehicle-column">
                        {{ __('Movil') }}
                    </th>
                    <th scope="col" class="px-6 py-3 bank-column">
                        {{ __('Cuenta Bancaria') }}
                    </th>
                    <th scope="col" class="px-6 py-3 item-column">
                        {{ __('Item') }}
                    </th>
                    <th scope="col" class="px-6 py-3 amount-column">
                        {{ __('Monto') }}
                    </th>
                    @can('editar ingresos')
                    <th scope="col" class="px-6 py-3 action-column">
                        Action
                    </th>
                    @endcan
                </tr>
                </thead>
                <tbody>
                @forelse($incomes as $income)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td scope="row"
                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white date-column">
                        {{ \Carbon\Carbon::parse($income->registration_date)->format('d-m-Y H:i:s') }}
                    </td>
                    <td class="px-6 py-4 vehicle-column">
                        {{ $income->vehicle_id ? str_pad($income->vehicle_id,3,0, STR_PAD_LEFT) : 'Sin movil asociado'
                        }}
                    </td>
                    <td class="px-6 py-4 bank-column">
                        {{ $income->banks ? $income->banks->bank_name : 'Sin banco asociado' }}
                    </td>
                    <td class="px-6 py-4 item-column">
                        {{ $income->itemsCashFlow->name }}
                    </td>
                    <td class="px-6 py-4 amount-column">
                        {{ $income->banks ? $income->banks->currency_type.'.' : '' }} {{ number_format($income->amount,
                        2) }}
                    </td>
                    @can('editar ingresos')
                    <td class="px-6 py-4 action-column">
                        <a href="{{ route('income.edit', $income->id) }}"
                           class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                    </td>
                    @endcan
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                        {{__('No records were found for today. You can create one now to start recording
                        information.')}}
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
            <!-- Paginación -->
            <div class="p-4">
                {{ $incomes->links() }}
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
