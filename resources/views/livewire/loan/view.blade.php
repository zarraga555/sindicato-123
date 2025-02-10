@section('title')
{{ __('messages.loan_of', ['vehicle' => $this->loan->vehicle_id]) }}
@endsection
<div>
    <section class="flex flex-col gap-y-8 py-8 mt-14">
        <x-breadcrumb
            pageTitle="{{ __('messages.loan_of', ['vehicle' => $this->loan->vehicle_id]) }}"
            breadcrumbMainUrl="{{ route('loans.index') }}"
            breadcrumbMain="{{__('Loans')}}"
            breadcrumbCurrent="{{__('Information')}}"
        >
            <!-- Botón Cancelar -->
            <a href="{{route('loans.index')}}"
               class="fi-btn relative grid-flow-col items-center justify-center font-bold transition duration-75 rounded-lg px-3 py-2 text-sm inline-grid shadow-sm bg-white text-gray-950 hover:bg-gray-50 dark:bg-white/5 dark:text-white dark:hover:bg-white/10 ring-1 ring-gray-950/10 dark:ring-white/20 gap-1.5 fi-ac-action fi-ac-btn-action"
               id="cancel-button"
            >
                <span class="fi-btn-label">Cancelar</span>
            </a>
            <!-- Contenido dentro del slot, como el botón de creación -->
            <a href="#"
               class="fi-btn bg-orange-500 text-white hover:bg-custom-500 rounded-lg px-3 py-2 text-sm font-bold inline-flex items-center shadow-sm transition duration-75">
                {{ __('Generate PDF') }}
            </a>
        </x-breadcrumb>
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Vehicule -->
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-white" for="account_name">
                    {{__('vehicle')}}
                </label>
                <div class="mt-1 text-gray-900 dark:text-gray-200">
                    {{ $this->loan->vehicle_id }}
                </div>
            </div>

            <!-- User Type -->
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-white" for="bank_name">
                    {{__('User type')}}
                </label>
                <div class="mt-1 text-gray-900 dark:text-gray-200">
                    {{__($this->loan->user_type)}}
                </div>
            </div>

            <!-- Full Name -->
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-white" for="account_number">
                    {{__('Full Name')}}
                </label>
                <div class="mt-1 text-gray-900 dark:text-gray-200">
                    {{$this->loan->driver_partner_name}}
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-white" for="account_type">
                    {{__('Description')}}
                </label>
                <div class="mt-1 text-gray-900 dark:text-gray-200">
                    {{$this->loan->description}}
                </div>
            </div>

            <!-- Loan Start date -->
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-white" for="initial_account_amount">
                    {{__('Loan start date')}}
                </label>
                <div class="mt-1 text-gray-900 dark:text-gray-200">
                    <span id="fechaLocal">{{$this->loan->loan_start_date}}</span>
                </div>
            </div>

            <!-- Payment frecuency -->
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-white" for="created_by">
                    {{__('Payment frecuency')}}
                </label>
                <div class="mt-1 text-gray-900 dark:text-gray-200">
                    {{__($this->loan->payment_frequency)}}
                </div>
            </div>

            <!-- How do you wish to collect interest earned? -->
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-white" for="created_by">
                    {{__('Collection of interest earned')}}
                </label>
                <div class="mt-1 text-gray-900 dark:text-gray-200">
                    {{ $this->loan->interest_payment_method === 'separate' ? __('Charge in a separate installment') :
                    __('Charge together with the quotas') }}
                </div>
            </div>

            <!-- Number instalments -->
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-white" for="created_by">
                    {{__('Number instalments')}}
                </label>
                <div class="mt-1 text-gray-900 dark:text-gray-200">
                    {{$this->loan->numberInstalments}} {{__('Fees')}}
                </div>
            </div>

            <!-- Loan amount -->
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-white" for="created_by">
                    {{__('Loan amount')}}
                </label>
                <div class="mt-1 text-gray-900 dark:text-gray-200">
                    {{$this->currency}}. {{$this->loan->amountLoan}}
                </div>
            </div>

            <!-- Interest rate -->
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-white" for="created_by">
                    {{__('Interest rate')}}
                </label>
                <div class="mt-1 text-gray-900 dark:text-gray-200">
                    {{$this->loan->interest_rate}}%
                </div>
            </div>

            <!-- Payment frecuency -->
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-white" for="created_by">
                    {{__('Total debt receivable')}}
                </label>
                <div class="mt-1 text-gray-900 dark:text-gray-200">
                    {{$this->currency}}. {{$this->loan->total_debt}}
                </div>
            </div>
            <!-- Loan status -->
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-white" for="created_by">
                    {{__('Loan status')}}
                </label>
                <div class="mt-1 text-gray-900 dark:text-gray-200">
                    {{__($this->loan->debtStatus)}}
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
                    {{ __('Payment Status') }}
                </th>
                <th scope="col" class="px-6 py-3">
                    {{ __('Amount') }}
                </th>
                <th scope="col" class="px-6 py-3">
                    {{ __('Remarks') }}
                </th>
                <th scope="col" class="px-6 py-3">
                    {{__('attachment')}}
                </th>
            </tr>
            </thead>
            <tbody>
            @forelse($fees as $fee)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ $fee->instalmentNumber}}
                </td>
                <td class="px-6 py-4">
                    {{ $fee->datePayment ? \Carbon\Carbon::parse($fee->datePayment)->format('d-m-Y')
                    : 'Fecha no disponible' }}
                </td>
                <td class="px-6 py-4">
                    {{ __($fee->paymentStatus) }}
                </td>
                <td class="px-6 py-4">
                    {{$this->currency}}. {{ $fee->amount }}
                </td>
                <td class="px-6 py-4">
                    {{$fee->description}}
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
                    {{__('No fees were found for this loan.')}}
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
        <!-- Paginación -->
        <div class="p-4">
            {{ $fees->links() }}
        </div>
    </section>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let fechaUTC = "{{ $this->loan->loan_start_date }}"; // Fecha en formato UTC desde Laravel
            let fechaLocal = new Date(fechaUTC); // Convertirla a la zona horaria del usuario

            let formato = fechaLocal.toLocaleString(navigator.language, {
                timeZone: Intl.DateTimeFormat().resolvedOptions().timeZone
            });

            document.getElementById("fechaLocal").innerText = formato;
        });
    </script>
</div>
