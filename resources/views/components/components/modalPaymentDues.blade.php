<x-dialog-modal wire:model="showPaymentModal">
    <x-slot name="title">
        Cobrar cuota
    </x-slot>

    <x-slot name="content">
        <!-- Nota con información de la cuota -->
        <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50" role="alert">
            <p><strong>Deudor:</strong> {{ $debtorName }}</p>
            <p><strong>Cuota pendiente:</strong>Nro:{{ $instalmentNumber }} : {{ $currency }}. {{ $pendingAmount }}
            </p>
            <p><strong>Fecha límite de pago:</strong> {{ $dueDate }}</p>
        </div>

        <!-- Fecha de Registro -->
        <div class="mb-4">
            <x-label for="date" :value="__('Date of registration')" />
            <x-input type="date" id="date" wire:model="date" class="w-full" />
            <x-input-error for="date" />
        </div>

        <!-- Monto a cobrar -->
        <div class="mb-4">
            <x-label for="amount_paid">
                {{ __('Amount receivable') }} <span class="text-red-500">*</span>
            </x-label>

            <x-input type="number" id="amount_paid" wire:model="amount_paid"
                class="w-full border-gray-300 focus:border-blue-500" step="0.01" required autofocus />

            <x-input-error for="amount_paid" class="text-red-500 text-sm mt-1" />
        </div>

        <!-- Descripción -->
        <div class="mb-4">
            <x-label for="description" :value="__('Description')" />
            <textarea id="description" wire:model.live="description"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5"
                rows="3"></textarea>
            <x-input-error for="description" />
        </div>

        <!-- Archivo adjunto -->
        <div class="mb-4">
            <x-label for="attachment" :value="__('File')" />
            <x-input type="file" id="attachment" wire:model.live="attachment" class="w-full" />
            <x-input-error for="attachment" />
        </div>
    </x-slot>

    <x-slot name="footer">
        <x-secondary-button wire:click="$toggle('showPaymentModal')" wire:loading.attr="disabled">
            {{ __('Cancel') }}
        </x-secondary-button>
        <x-button class="ml-2" wire:click="paymentDues" wire:loading.attr="disabled">
            {{ __('Pay fee') }}
        </x-button>
    </x-slot>
</x-dialog-modal>
