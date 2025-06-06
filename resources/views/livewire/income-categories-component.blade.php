@section('title')
{{__('Income Items')}}
@endsection
<div>
    <section class="flex flex-col gap-y-8 py-8 mt-14">
        <x-breadcrumb
            pageTitle="{{__('Income Items')}}"
            breadcrumbMainUrl="{{ route('incomeCategories.index') }}"
            breadcrumbMain="{{__('Income Items')}}"
            breadcrumbCurrent="{{__('List')}}"
        >
            @can('crear item ingreso')
            <!-- Contenido dentro del slot, como el botón de creación -->
            <a href="{{ route('incomeCategories.create') }}"
               class="fi-btn bg-orange-500 text-white hover:bg-custom-500 rounded-lg px-3 py-2 text-sm font-semibold inline-flex items-center shadow-sm transition duration-75">
                {{ __('Create New Income Item') }}
            </a>
            @endif
        </x-breadcrumb>
        @include('components.components.messagesFlash')
        <!--Table-->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <div class="pb-4 bg-white dark:bg-gray-900">
                <label for="table-search" class="sr-only">{{__('Search')}}</label>
                <div class="relative mt-4 ml-4">
                    <div class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </div>
                    <input type="text" id="table-search"
                           class="block pt-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                           placeholder="{{ __('Search for items') }}"
                           wire:model.debounce.500ms="search"
                           x-on:keyup="if ($event.key === 'Enter') { $wire.$refresh() }"
                    >
                </div>
            </div>
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        {{ __('Name') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('Amount') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('Pending payment') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('Type Item') }}
                    </th>
                    @can('editar item ingreso')
                    <th scope="col" class="px-6 py-3">
                        {{__('Action')}}
                    </th>
                    @endcan
                </tr>
                </thead>
                <tbody>
                @forelse($incomeCategories as $incomeCategorie)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $incomeCategorie->name }}
                    </td>
                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        Bs. {{ number_format($incomeCategorie->amount, 2) }}
                    </td>
                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        @if ($incomeCategorie->pending_payment)
                        <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-green-900 dark:text-green-300">{{__('Yes')}}</span>
                        @else
                        <span class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-red-900 dark:text-red-300">{{__('No')}}</span>
                        @endif
                    </td>
                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ __($incomeCategorie->type_income_expense) }}
                    </td>
                    @can('editar item ingreso')
                    <td class="px-6 py-4">
                        <a href="{{ route('incomeCategories.edit', $incomeCategorie->id) }}"
                           class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{__('Edit')}}</a>
                    </td>
                    @endcan
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                        {{__('No records found. Please create a Bank Account to get started.')}}
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
            <!-- Paginación -->
            <div class="p-4">
                {{ $incomeCategories->links() }}
            </div>
        </div>
    </section>
</div>
