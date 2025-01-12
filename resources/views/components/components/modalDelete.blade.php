<x-confirmation-modal wire:model="confirmingUserDeletion">
        <x-slot name="title">
            Eliminar registro
        </x-slot>

        <x-slot name="content">
            ¿Está seguro de que desea eliminar este registro? Una vez eliminada el registro, todos sus recursos y datos
            se eliminarán de forma permanente.
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('confirmingUserDeletion')" wire:loading.attr="disabled">
                Cancelar
            </x-secondary-button>

            <x-danger-button class="ml-2" wire:click="delete" wire:loading.attr="disabled">
                Eliminar registro
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>
