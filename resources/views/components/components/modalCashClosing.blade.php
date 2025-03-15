<x-confirmation-modal wire:model="modal_cash_closing">
    <x-slot name="title">
        {{ __('Ticketing') }}
    </x-slot>

    <x-slot name="content">
        <div class="grid grid-cols-2 gap-4">
            {{-- Columna de Billetes --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-800">{{ __('Bills') }}</h3>
                <div class="mt-2 space-y-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('Bs.200') }}</label>
                        <x-input type="number" wire:model.live="_200" class="w-full" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('Bs.100') }}</label>
                        <x-input type="number" wire:model.live="_100" class="w-full" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('Bs.50') }}</label>
                        <x-input type="number" wire:model.live="_50" class="w-full" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('Bs.20') }}</label>
                        <x-input type="number" wire:model.live="_20" class="w-full" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('Bs.10') }}</label>
                        <x-input type="number" wire:model.live="_10" class="w-full" />
                    </div>
                </div>
            </div>

            {{-- Columna de Monedas --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-800">{{ __('Coins') }}</h3>
                <div class="mt-2 space-y-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('Bs.5') }}</label>
                        <x-input type="number" wire:model.live="_5" class="w-full" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('Bs.2') }}</label>
                        <x-input type="number" wire:model.live="_2" class="w-full" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('Bs.1') }}</label>
                        <x-input type="number" wire:model.live="_1" class="w-full" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('Bs.0.50') }}</label>
                        <x-input type="number" wire:model.live="_050" class="w-full" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('Bs.0.20') }}</label>
                        <x-input type="number" wire:model.live="_020" class="w-full" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('Bs.0.10') }}</label>
                        <x-input type="number" wire:model.live="_010" class="w-full" />
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <x-slot name="footer">
        <div class="w-full flex flex-wrap items-center justify-between text-xl">
            <b
                style="color: {{ $sum_ticketing > $cashOnHand ? 'green' : ($sum_ticketing < $cashOnHand ? 'red' : 'black') }}">
                {{ __('Ticketing') }}: Bs. {{ number_format($sum_ticketing, 2)  }}
            </b>
            <b>{{ __('Cash on Hand') }}: Bs. {{ number_format($cashOnHand, 2) }}</b>
        </div>

        <div class="mt-4 flex items-center justify-end space-x-2 w-full">
            <x-secondary-button wire:click="$toggle('modal_cash_closing')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ml-2" wire:click="cashClosing" wire:loading.attr="disabled">
                {{__('Cash closing')}}
            </x-button>
        </div>
    </x-slot>
</x-confirmation-modal>
