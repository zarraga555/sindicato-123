@section('title')
    {{ __('messages.collateral_of', ['vehicle' => $this->collateral->vehicle_id]) }}
@endsection
<div>
    <section class="flex flex-col gap-y-8 py-8 mt-14">
        <x-breadcrumb pageTitle="{{ __('messages.collateral_of', ['vehicle' => $this->collateral->vehicle_id]) }}"
            breadcrumbMainUrl="{{ route('collateral.index') }}" breadcrumbMain="{{ __('Collaterals') }}"
            breadcrumbCurrent="{{ __('Information') }}">
            <!-- Botón Cancelar -->
            <a href="{{ route('collateral.index') }}"
                class="fi-btn relative grid-flow-col items-center justify-center font-bold transition duration-75 rounded-lg px-3 py-2 text-sm inline-grid shadow-sm bg-white text-gray-950 hover:bg-gray-50 dark:bg-white/5 dark:text-white dark:hover:bg-white/10 ring-1 ring-gray-950/10 dark:ring-white/20 gap-1.5 fi-ac-action fi-ac-btn-action"
                id="cancel-button">
                <span class="fi-btn-label">{{ __('Back') }}</span>
            </a>
            <!-- Contenido dentro del slot, como el botón de creación -->
            <a href="#" wire:click="generatePDF"
                class="fi-btn bg-orange-500 text-white hover:bg-custom-500 rounded-lg px-3 py-2 text-sm font-bold inline-flex items-center shadow-sm transition duration-75">
                {{ __('Generate PDF') }}
            </a>
        </x-breadcrumb>
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Vehicule -->
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-white" for="account_name">
                    {{ __('vehicle') }}
                </label>
                <div class="mt-1 text-gray-900 dark:text-gray-200">
                    {{ $this->collateral->vehicle_id }}
                </div>
            </div>

            <!-- User Type -->
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-white" for="bank_name">
                    {{ __('User type') }}
                </label>
                <div class="mt-1 text-gray-900 dark:text-gray-200">
                    {{ __($this->collateral->user_type) }}
                </div>
            </div>

            <!-- Full Name -->
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-white" for="account_number">
                    {{ __('Full Name') }}
                </label>
                <div class="mt-1 text-gray-900 dark:text-gray-200">
                    {{ $this->collateral->driver_partner_name }}
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-white" for="account_type">
                    {{ __('Description') }}
                </label>
                <div class="mt-1 text-gray-900 dark:text-gray-200">
                    {{ $this->collateral->description }}
                </div>
            </div>

            <!-- collateral Start date -->
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-white" for="initial_account_amount">
                    {{ __('Date') }}
                </label>
                <div class="mt-1 text-gray-900 dark:text-gray-200">
                    <span id="fechaLocal">{{ $this->collateral->start_date }}</span>
                </div>
            </div>

            <!-- Payment frecuency -->
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-white" for="created_by">
                    {{ __('Payment frequency') }}
                </label>
                <div class="mt-1 text-gray-900 dark:text-gray-200">
                    {{ __($this->collateral->payment_frequency) }}
                </div>
            </div>

            <!-- Number instalments -->
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-white" for="created_by">
                    {{ __('Number of Installments') }}
                </label>
                <div class="mt-1 text-gray-900 dark:text-gray-200">
                    {{ $this->collateral->interest_payment_method === 'separate' ? $collateral->instalments + 1 : $collateral->instalments }}
                    {{ __('Fees') }}
                </div>
            </div>

            <!-- Amount -->
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-white" for="created_by">
                    {{ __('Amount') }}
                </label>
                <div class="mt-1 text-gray-900 dark:text-gray-200">
                    Bs. {{ $this->collateral->amount }}
                </div>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-white" for="created_by">
                    {{ __('Status') }}
                </label>
                <div class="mt-1 text-gray-900 dark:text-gray-200">
                    {{ __($this->collateral->status) }}
                </div>
            </div>
        </div>
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        {{ __('Instalment Number') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('Date Payment') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('Status') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('Amount') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('Remarks') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('attachment') }}
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($fees as $fee)
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $fee->instalmentNumber }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $fee->datePayment ? \Carbon\Carbon::parse($fee->datePayment)->format('d-m-Y') : 'Fecha no disponible' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ __($fee->paymentStatus) }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $this->currency ?? 'Bs' }}. {{ $fee->amount }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $fee->description }}
                        </td>
                        <td class="px-6 py-4">
                            <!-- Mostrar mensaje de error si no hay archivo disponible -->
                            @if (session()->has('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <!-- Verificar si el archivo está disponible para este fee -->
                            @if ($fee->attachment && Storage::exists($fee->attachment))
                                <button wire:click="downloadFile({{ $fee->id }})" class="btn btn-primary">
                                    {{ __('Download resource') }}
                                </button>
                            @else
                                <p>{{ __('No downloadable resource available.') }}</p>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                            {{ __('No fees were found for this collateral.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>

</div>
