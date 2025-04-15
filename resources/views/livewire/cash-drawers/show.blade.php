@section('title')
    {{ __('Cash closing') }}
@endsection
<div>
    <section class="flex flex-col gap-y-8 py-8 mt-14">
        <x-breadcrumb pageTitle="{{ __('Cash closing') }}" breadcrumbMainUrl="{{ route('cashDrawer.index') }}"
            breadcrumbMain="{{ __('Cash Drawers') }}" breadcrumbCurrent="{{ __('Information') }}">
            <!-- Botón Cancelar -->
            <a href="{{ route('cashDrawer.index') }}"
                class="fi-btn relative grid-flow-col items-center justify-center font-bold transition duration-75 rounded-lg px-3 py-2 text-sm inline-grid shadow-sm bg-white text-gray-950 hover:bg-gray-50 dark:bg-white/5 dark:text-white dark:hover:bg-white/10 ring-1 ring-gray-950/10 dark:ring-white/20 gap-1.5 fi-ac-action fi-ac-btn-action"
                id="cancel-button">
                <span class="fi-btn-label">{{ __('Back') }}</span>
            </a>
            <a href="#" wire:click="generateSimplePDF"
                class="fi-btn bg-orange-500 text-white hover:bg-custom-500 rounded-lg px-3 py-2 text-sm font-bold inline-flex items-center shadow-sm transition duration-75">
                {{ __('Generate PDF') }}
            </a>
            <a href="#" wire:click="generateReport"
                class="fi-btn bg-orange-500 text-white hover:bg-custom-500 rounded-lg px-3 py-2 text-sm font-bold inline-flex items-center shadow-sm transition duration-75">
                {{ __('Generate Detailed PDF') }}
            </a>
        </x-breadcrumb>
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- User -->
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-white" for="account_name">
                    {{ __('User') }}
                </label>
                <div class="mt-1 text-gray-900 dark:text-gray-200">
                    {{ $this->cashDrawer->user->name }}
                </div>
            </div>

            <!-- Start Time -->
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-white" for="bank_name">
                    {{ __('Date of open cash') }}
                </label>
                <div class="mt-1 text-gray-900 dark:text-gray-200">
                    <span id="fechaLocal">{{ $this->cashDrawer->start_time }}</span>
                </div>
            </div>

            <!-- End Time -->
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-white" for="account_number">
                    {{ __('Cash closing date') }}
                </label>
                <div class="mt-1 text-gray-900 dark:text-gray-200">
                    <span id="fechaLocal">{{ $this->cashDrawer->end_time ?? __('Unclosed cash register')}}</span>
                </div>
            </div>

            <!-- final_money -->
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-white" for="account_type">
                    {{ __('Amount recorded by the system') }}
                </label>
                <div class="mt-1 text-gray-900 dark:text-gray-200">
                    {{ $this->currency }}. 
                    {{ $cashDrawer->final_money > 0 ? $cashDrawer->final_money : \App\Models\CashFlow::totalRecordedByTheSystem($this->cashDrawer->id) }}
                </div>
            </div>

            <!-- total_calculated -->
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-white" for="initial_account_amount">
                    {{ __('Total amount entered into the system') }}
                </label>
                <div class="mt-1 text-gray-900 dark:text-gray-200">
                    {{ $this->currency }}. {{ $this->cashDrawer->total_calculated }}
                </div>
            </div>

            <!-- total_declared -->
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-white" for="created_by">
                    {{ __('Total amount declared by the user') }}
                </label>
                <div class="mt-1 text-gray-900 dark:text-gray-200">
                    {{ $this->currency }}. {{ $this->cashDrawer->total_calculated }}
                </div>
            </div>

            <!-- Difference -->
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-white" for="created_by">
                    {{ __('Difference') }}
                </label>
                <div class="mt-1 text-gray-900 dark:text-gray-200">
                    {{ $this->currency }}. {{ __($this->cashDrawer->difference) }}
                </div>
            </div>
            <!-- Difference -->
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-white" for="created_by">
                    {{ __('Cash status') }}
                </label>
                <div class="mt-1 text-gray-900 dark:text-gray-200">
                    {{ __($this->cashDrawer->status) }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-white">
                    {{ __('Banknoting of the amount declared in cash') }}
                </label>
                <div class="mt-1 text-gray-900 dark:text-gray-200">
                    @php
                        $denominations = json_decode($this->cashDrawer->denominations, true);
                    @endphp

                    <ul>
                        @if ($denominations && is_array($denominations))
                            @foreach ($denominations as $denomination => $quantity)
                                @if ($quantity)
                                    <li><strong>{{ $this->currency }}.
                                            {{ str_replace('_', '', $denomination) }}:</strong> {{ $quantity }}
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <!-- Tabla -->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
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
                        <td class="px-6 py-4 amount-column text-gray-900"><b>Bs.
                                {{ number_format($totalRegistration, 2) }}</b></td>
                        <td class="px-6 py-4 amount-column text-gray-900"><b>Bs.
                                {{ number_format($cashOnHand, 2) }}</b>
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- Paginación -->
            <div class="">
                {{ $cashRegisters->links() }}
            </div>
        </div>
    </section>
</div>
