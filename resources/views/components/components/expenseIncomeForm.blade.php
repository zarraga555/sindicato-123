<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <!-- Input: Numero de Movil -->
    <div>
        <label for="movil" class="block text-sm font-medium text-gray-700">Numero de Movil</label>
        <input type="text" id="movil" name="movil"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
               placeholder="Ingrese el numero de movil a registrar"
               maxlength="255"
               required="required"
               wire:model="movil">
    </div>
    @error('movil') <span class="text-danger">{{ $message }}</span> @enderror
    <!-- Input: Items -->
    <div>
        <label for="cashFlowId" class="block text-sm font-medium text-gray-900">
            @if($mode == 'income'){{__('Item de ingreso')}}@else {{__('Item de egreso')}} @endif
            <sup class="text-danger-600 dark:text-danger-400 font-medium">*</sup>
        </label>
        <select id="cashFlowId" name="cashFlowId"
                wire:model="itemCashFlowId"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            <option value="">{{__('Select an option')}}</option>
            @foreach($items as $item)
            <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
        @error("cashFlowId") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <!-- Input: Número de Serie -->
    <div>
        <label class="block text-sm font-medium text-gray-950 dark:text-white" for="receipt_number">
            {{__('Receipt number')}}
        </label>
        <input
            class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5"
            id="receipt_number"
            maxlength="255"
            type="text"
            wire:model="receipt_number">
        @error("receipt_number") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <!-- Input: Monto -->
    <div>
        <label class="block text-sm font-medium leading-6 text-gray-950 dark:text-white" for="amount">
            {{__('Amount')}}
            <sup class="text-danger-600 dark:text-danger-400 font-medium">*</sup>
        </label>
        <div
            class="flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white dark:bg-white/5 focus-within:ring-2 ring-gray-950/10 dark:ring-white/20 focus-within:ring-primary-600 dark:focus-within:ring-primary-500 overflow-hidden">
            <input
                class="block w-full border-none py-1.5 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 dark:text-white dark:placeholder:text-gray-500 sm:text-sm sm:leading-6 bg-transparent ps-3 pe-3"
                id="amount"
                type="number"
                onblur="formatDecimals(this)"
                wire:model.live="amount">
        </div>
    </div>
</div>
<script>
    function formatDecimals(input) {
        // Asegúrate de que el valor no esté vacío y agrega dos decimales
        if (input.value) {
            input.value = parseFloat(input.value).toFixed(2);
        }
    }
</script>
