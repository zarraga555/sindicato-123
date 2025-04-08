<div class="grid gap-6">
  @foreach ($cashFlows as $index => $cashFlow)
      <div class="grid gap-4 items-center"
          style="grid-template-columns: repeat(4, minmax(0, 1fr));">
          <!-- Monto -->
          <div>
              <label for="input1-{{ $index }}" class="block text-sm font-medium text-gray-700">{{__('Amount')}}</label>
              <input type="text" id="input1-{{ $index }}" name="cashFlows[{{ $index }}][amount]"
                  wire:model="cashFlows.{{ $index }}.amount"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  placeholder="{{ __('Enter an amount') }}">
              @error("cashFlows.$index.amount")
                  <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
          </div>
          <!-- Número de Serie -->
          <div>
              <label class="block text-sm font-medium text-gray-950 dark:text-white">
                  {{ __('Serial Number') }}
              </label>
              <input type="text" maxlength="255" required
                  wire:model="cashFlows.{{ $index }}.serie"
                  class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  placeholder="{{ __('Enter the serial number') }}">
              @error("cashFlows.$index.serie")
                  <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
          </div>
          <!-- Select Ítem -->
          <div>
              <label for="selectCashFlow-{{ $index }}" class="block text-sm font-medium text-gray-900">
                  {{ __('Expense item') }}
              </label>
              <select id="selectCashFlow-{{ $index }}" name="cashFlows[{{ $index }}][cashFlowId]"
                  wire:model="cashFlows.{{ $index }}.cashFlowId"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                  <option value="" disabled>{{ __('Select an option') }}</option>
                  @foreach ($itemsCashFlows as $item)
                      <option value="{{ $item->id }}">{{ $item->name }}</option>
                  @endforeach
              </select>
              @error("cashFlows.$index.cashFlowId")
                  <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
          </div>
          <!-- Botón: Agregar o Eliminar -->
          <div class="flex justify-center items-end h-full pt-6">
              @if ($index === 0)
                  <button type="button" wire:click="addCashFlow"
                      class="bg-green-500 text-white hover:bg-green-600 rounded-lg px-3 py-2 text-sm font-semibold shadow-sm transition duration-75">
                      {{__('Add another expense')}}
                  </button>
              @else
                  <button type="button" wire:click="removeCashFlow({{ $index }})"
                      class="bg-red-500 text-white hover:bg-red-600 rounded-lg px-3 py-2 text-sm font-semibold shadow-sm transition duration-75">
                      {{ __('Delete') }}
                  </button>
              @endif
          </div>
      </div>
  @endforeach
</div>