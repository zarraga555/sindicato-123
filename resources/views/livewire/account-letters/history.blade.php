@section('title')
{{__('Banking Transaction History')}}
@endsection

<div>
    <section class="flex flex-col gap-y-8 py-8 mt-14">
        <x-breadcrumb
            pageTitle="{{__('Banking Transaction History')}} ({{$nameLabel}})"
            breadcrumbMainUrl="{{ route('accountLetters.index') }}"
            breadcrumbMain="{{__('Bank accounts')}}"
            breadcrumbCurrent="{{__('History')}}"
        >
            <!-- Botón Cancelar -->
            <a href="{{route('accountLetters.index')}}"
                class="fi-btn relative grid-flow-col items-center justify-center font-semibold transition duration-75 rounded-lg px-3 py-2 text-sm inline-grid shadow-sm bg-white text-gray-950 hover:bg-gray-50 dark:bg-white/5 dark:text-white dark:hover:bg-white/10 ring-1 ring-gray-950/10 dark:ring-white/20 gap-1.5 fi-ac-action fi-ac-btn-action"
                id="cancel-button"
            >
                <span class="fi-btn-label">Cancelar</span>
            </a>
            <!-- Contenido dentro del slot, como el botón de creación -->
            <a href="#"
               class="fi-btn bg-orange-500 text-white hover:bg-custom-500 rounded-lg px-3 py-2 text-sm font-semibold inline-flex items-center shadow-sm transition duration-75">
                {{ __('Generate PDF') }}
            </a>
        </x-breadcrumb>

        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Account Name -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-white" for="account_name">
                    {{__('Account Name')}}
                </label>
                <div class="mt-1 text-gray-900 dark:text-gray-200">
                    {{ $account_name }}
                </div>
            </div>

            <!-- Bank Name -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-white" for="bank_name">
                    {{__('Bank Name')}}
                </label>
                <div class="mt-1 text-gray-900 dark:text-gray-200">
                    {{ $bank_name }}
                </div>
            </div>

            <!-- Account Number -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-white" for="account_number">
                    {{__('Account Number')}}
                </label>
                <div class="mt-1 text-gray-900 dark:text-gray-200">
                    {{ $account_number }}
                </div>
            </div>

            <!-- Bank Account Type -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-white" for="account_type">
                    {{__('Bank Account Type')}}
                </label>
                <div class="mt-1 text-gray-900 dark:text-gray-200">
                    {{ $account_type }}
                </div>
            </div>

            <!-- Opening Amount -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-white" for="initial_account_amount">
                    {{__('Balance available')}}
                </label>
                <div class="mt-1 text-gray-900 dark:text-gray-200">
                    {{ $currency_type }}. {{ number_format($initial_account_amount, 2) }}
                </div>
            </div>

            <!-- Created By -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-white" for="created_by">
                    {{__('Created By')}}
                </label>
                <div class="mt-1 text-gray-900 dark:text-gray-200">
                    {{ $created_by }}
                </div>
            </div>
        </div>

        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    {{ __('Fecha') }}
                </th>
                <th scope="col" class="px-6 py-3">
                    {{ __('vehicle') }}
                </th>
                <th scope="col" class="px-6 py-3">
                    {{ __('Detail') }}
                </th>
                <th scope="col" class="px-6 py-3">
                    {{ __('Transaction type') }}
                </th>
                <th scope="col" class="px-6 py-3">
                    {{ __('Amount') }}
                </th>
                <th scope="col" class="px-6 py-3">
                    {{__('Created By')}}
                </th>
            </tr>
            </thead>
            <tbody>
            @forelse($transactions as $transaction)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ $transaction->created_at ? \Carbon\Carbon::parse($transaction->created_at)->format('d-m-Y H:i:s') : 'Fecha no disponible' }}
                </td>
                <td class="px-6 py-4">
                    {{ $transaction->vehicle_id ? str_pad($transaction->vehicle_id,3,0, STR_PAD_LEFT) : 'Sin movil asociado'
                    }}
                </td>
                <td class="px-6 py-4">
                    {{ $transaction->detail ? $transaction->detail : 'Sin detalle' }} <br>
                    <span><strong>{{__('Remarks')}}:</strong></span>
                     <br>
                    {{ $transaction->description ?: 'Sin Observaciones' }}

                </td>
                <td class="px-6 py-4">
                    {{ $transaction->itemsCashFlow?->name ?? __($transaction->type_transaction) }}
                </td>
                <td class="px-6 py-4">
                    {{ $transaction->banks ? $transaction->banks->currency_type.'.' : '' }} {{
                    number_format($transaction->amount, 2) }}

                </td>
                <td class="px-6 py-4">
                    {{$transaction->users->name}}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                    No se encontraron registros. Cree un un registro para empezar.
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
        <!-- Paginación -->
        <div class="p-4">
            {{ $transactions->links() }}
        </div>
    </section>
</div>
