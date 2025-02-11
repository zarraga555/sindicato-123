<x-confirmation-modal wire:model="showRevert">
    <x-slot name="title">
        <span class="text-yellow-500 text-2xl">
            <i class="fas fa-exclamation-triangle"></i> <!-- Icono de advertencia -->
        </span>
        Revertir a Cuenta Cobrable
    </x-slot>

    <x-slot name="content">
        <p class="text-sm text-gray-500 mt-4">
            Está a punto de revertir esta cuenta a cobrable. Esto permitirá que se pueda incluir en los cobros de cuotas y se refleje en las estadísticas de pagos.
            <br>
            Tenga en cuenta que esta acción no puede deshacerse fácilmente. Asegúrese de que la cuenta cumple con los requisitos para ser considerada como cobrable antes de proceder.
        </p>
    </x-slot>

    <x-slot name="footer">
        <!-- Botón de Cancelar -->
        <x-secondary-button wire:click="$toggle('showRevert')" wire:loading.attr="disabled">
            Cancelar
        </x-secondary-button>

        <!-- Botón de Confirmar -->
        <x-button class="ml-2" wire:click="revertToCollectible()" wire:loading.attr="disabled">
            Confirmar Reversión
        </x-button>
    </x-slot>
</x-confirmation-modal>
