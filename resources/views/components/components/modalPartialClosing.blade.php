<x-confirmation-modal wire:model="modal_partial_closing">
    <x-slot name="title">
      {{__('Partial Closing')}}
    </x-slot>

    <x-slot name="content">
        {{__('Are you sure you want to perform a partial closing? If you perform a partial close, you will still be able to complete your close later. This will allow you to generate the ticketing to record all cash entered.')}}
    </x-slot>

    <x-slot name="footer">
        <x-secondary-button wire:click="$toggle('modal_partial_closing')" wire:loading.attr="disabled">
            {{__('Cancel')}}
        </x-secondary-button>

        <x-button class="ml-2" wire:click="partialClosing" wire:loading.attr="disabled">
            {{__('Partial Closing')}}
        </x-button>
    </x-slot>
</x-confirmation-modal>
