<x-dialog-modal wire:model="modalExpense">
    <x-slot name="title">
        {{ __('New Expense') }}
    </x-slot>

    <x-slot name="content">
        <div class="w-full">
            <div class="mx-auto w-full max-w-7xl">
                @include('components.components.expenseForm')
            </div>
        </div>
    </x-slot>

    <x-slot name="footer">
        <x-secondary-button wire:click="$toggle('modalExpense')" wire:loading.attr="disabled">
            {{ __('Cancel') }}
        </x-secondary-button>
        <x-button class="ml-2" wire:click="saveExpense" wire:loading.attr="disabled">
            {{ __('Save') }}
        </x-button>
    </x-slot>
</x-dialog-modal>
