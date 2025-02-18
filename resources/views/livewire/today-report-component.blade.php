@section('title')
{{__("Today's Report")}}
@endsection
<div>
    <section class="flex flex-col gap-y-8 py-8 mt-14">
        <x-breadcrumb
            pageTitle="{{ __('Today\'s Report') }}"
            breadcrumbMainUrl="{{ route('today.report') }}"
            breadcrumbMain="{{ __('Today\'s Report') }}"
            breadcrumbCurrent="{{__('List')}}"
        >
            <!-- Contenido dentro del slot, como el botón de creación -->
            <a href="#" wire:click="generatePDF"
               class="fi-btn bg-orange-500 text-white hover:bg-custom-500 rounded-lg px-3 py-2 text-sm font-semibold inline-flex items-center shadow-sm transition duration-75">
                {{ __('Generar PDF') }}
            </a>

        </x-breadcrumb>
        <!-- Filters -->
        <fieldset class="border border-gray-300 rounded-md p-3">
            <legend class="text-sm font-medium text-gray-700">{{ __('Filters') }}</legend>

            <div class="flex flex-col space-y-4">
                <div class="flex items-center space-x-6">
                    <label class="flex items-center cursor-pointer">
                        <input wire:model.live="dateCheck" type="radio" value="unic"
                               class="form-radio text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Fecha única</span>
                    </label>
                    <label class="flex items-center cursor-pointer">
                        <input wire:model.live="dateCheck" type="radio" value="range"
                               class="form-radio text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Rango de fechas</span>
                    </label>
                </div>

                <div class="flex items-center space-x-4 flex-wrap">
                    <div class="w-auto">
                        <label class="block text-sm text-gray-700">
                            {{ $dateCheck == 'unic' ? 'Fecha' : 'Fecha inicio' }}
                        </label>
                        <input wire:model.live="reportDateFrom" type="date"
                               class="block w-[137px] px-2 py-1 rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-gray-700">
                    </div>

                    @if ($dateCheck == 'range')
                    <div class="w-auto">
                        <label class="block text-sm text-gray-700">Fecha final</label>
                        <input wire:model.live="reportDateTo" type="date"
                               class="block w-[137px] px-2 py-1 rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-gray-700">
                    </div>
                    @endif

                    {{-- Filtro por item --}}
                    <div class="w-auto">
                        <label class="block text-sm text-gray-700">{{ __('Filtrar por Item') }}</label>
                        <select wire:model.live="selectedItem"
                                class="block w-48 px-2 py-1 rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-gray-700">
                            <option value="">{{ __('Todos los Items') }}</option>
                            @foreach(\App\Models\ItemsCashFlow::all() as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filtro por Número de Móvil --}}
                    <div class="w-auto">
                        <label class="block text-sm text-gray-700">{{ __('Filtrar por Número de Móvil') }}</label>
                        <input wire:model.live="vehicleId" type="number" placeholder="Ej. 123"
                               class="block w-32 px-2 py-1 rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-gray-700">
                    </div>
                </div>
            </div>
        </fieldset>
        <!--Table-->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        {{ __('Date') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('Vehicle') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('Bank Account') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('Item') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('Transaction type') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('Amount') }}
                    </th>
                </tr>
                </thead>
                <tbody>
                @if($items != null)
                @forelse($items as $item)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ \Carbon\Carbon::parse($item->registration_date)->format('d-m-Y H:i:s') }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $item->vehicle_id ? str_pad($item->vehicle_id,3,0, STR_PAD_LEFT) : 'Sin movil asociado'
                        }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $item->banks ? $item->banks->bank_name : 'Sin banco asociado' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $item->itemsCashFlow->name?? __($item->type_transaction) }}
                    </td>
                    <td class="px-6 py-4">
                        {{ __($item->transaction_type_income_expense) }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $item->banks ? $item->banks->currency_type.'.' : '' }} {{
                        number_format($item->amount, 2) }}

                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                        {{ __('No records found. Select other dates.') }}
                    </td>
                </tr>
                @endforelse
                @endif
                </tbody>
            </table>

            <!-- Paginación -->
            <div class="p-4">
                {{ $items->links() }}
            </div>

        </div>
    </section>
    <script>
        window.addEventListener('openPdf', event => {
            window.open(event.detail.url, '_blank');
        });
    </script>
</div>
