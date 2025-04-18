<div class="grid grid-cols-4 gap-4">
    <!-- input1 -->
    <div>
        <label class="block text-sm font-medium leading-6 text-gray-950 dark:text-white" for="input1">
            {{__('Name of the New Income Item')}}
        </label>
        <div
            class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white dark:bg-white/5 focus-within:ring-2 ring-gray-950/10 dark:ring-white/20 focus-within:ring-primary-600 dark:focus-within:ring-primary-500 fi-fo-text-input overflow-hidden">
            <input
                class="fi-input block w-full border-none py-1.5 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 dark:text-white dark:placeholder:text-gray-500 sm:text-sm sm:leading-6 bg-transparent ps-3 pe-3"
                id="input1" maxlength="255" type="text" wire:model="name">
        </div>
    </div>

    <!-- amount -->
    <div>
        <label class="block text-sm font-medium leading-6 text-gray-950 dark:text-white" for="amount">
            {{__('Amount')}}
        </label>
        <div
            class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white dark:bg-white/5 focus-within:ring-2 ring-gray-950/10 dark:ring-white/20 focus-within:ring-primary-600 dark:focus-within:ring-primary-500 fi-fo-text-input overflow-hidden">
            <input
                class="fi-input block w-full border-none py-1.5 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 dark:text-white dark:placeholder:text-gray-500 sm:text-sm sm:leading-6 bg-transparent ps-3 pe-3"
                id="amount" type="number" step="0.01" wire:model="amount">
        </div>
    </div>

    <!-- pending_payment -->
    <div class="flex items-center mt-8">
        <input id="pending_payment" type="checkbox" wire:model.live="pending_payment"
            class="h-6 w-6 text-primary-600 border-gray-300 rounded dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-primary-500 dark:checked:border-primary-500 me-3">
        <label for="pending_payment" class="text-base font-medium text-gray-950 dark:text-white">
            {{__('Pending Payment')}}
        </label>
    </div>
</div>
