@section('title')
Items de Ingresos
@endsection
<div>
    <section class="flex flex-col gap-y-8 py-8 mt-14">
        <x-breadcrumb
            pageTitle="Items de Ingresos"
            breadcrumbMainUrl="{{ route('incomeCategories.index') }}"
            breadcrumbMain="Items de Ingresos"
            breadcrumbCurrent="Listado"
        >
            @can('crear item ingreso')
            <!-- Contenido dentro del slot, como el botón de creación -->
            <a href="{{ route('incomeCategories.create') }}"
               class="fi-btn bg-orange-500 text-white hover:bg-custom-500 rounded-lg px-3 py-2 text-sm font-semibold inline-flex items-center shadow-sm transition duration-75">
                {{ __('Nuevo Item de Ingreso') }}
            </a>
            @endif
        </x-breadcrumb>
        @include('components.components.messagesFlash')
        <!--Table-->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <div class="pb-4 bg-white dark:bg-gray-900">
                <label for="table-search" class="sr-only">Search</label>
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
                        {{ __('Type Item') }}
                    </th>
                    @can('editar item ingreso')
                    <th scope="col" class="px-6 py-3">
                        Action
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
                    <td class="px-6 py-4">
                        {{ $incomeCategorie->type_income_expense }}
                    </td>
                    @can('editar item ingreso')
                    <td class="px-6 py-4">
                        <a href="{{ route('incomeCategories.edit', $incomeCategorie->id) }}"
                           class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                    </td>
                    @endcan
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                        No se encontraron registros. Cree un una Cuenta Bancaria para empezar.
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
