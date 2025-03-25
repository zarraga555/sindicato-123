<x-confirmation-modal wire:model="confirmingPayment">
    <x-slot name="title">
        {{ __('Charge Record') }}
    </x-slot>

    <x-slot name="content">
        {{ __('Are you sure you want to charge this record? Once charged, the payment will be processed permanently.') }}
    </x-slot>

    <x-slot name="footer">
        <x-secondary-button wire:click="$toggle('confirmingPayment')" wire:loading.attr="disabled">
            {{ __('Cancel') }}
        </x-secondary-button>

        <x-button class="ml-2 bg-green-500" wire:click="chargeCash" wire:loading.attr="disabled">
            {{ __('Charge in Cash') }}
        </x-button>

        <x-button class="ml-2" wire:click="chargeQR" wire:loading.attr="disabled">
            {{ __('Charge via QR') }}
        </x-button>
    </x-slot>
</x-confirmation-modal>
