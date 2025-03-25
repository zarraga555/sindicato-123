@php
    $links = [
        [
            'name' => 'Dashboard',
            'icon' => 'fa-solid fa-gauge-high',
            'route' => 'dashboard',
            'active' => request()->routeIs('dashboard'),
            'can' => null, // No requiere permiso
        ],
        [
            'name' => 'User Management',
            'icon' => 'fa-solid fa-users',
            'can' => ['ver usuarios', 'ver roles'], // Lista de permisos
            'submenu' => [
                [
                    'name' => 'Users',
                    'route' => 'user.index',
                    'icon' => 'fa-solid fa-arrow-right',
                    'active' => request()->routeIs('user.index'),
                    'permission' => 'ver usuarios',
                ],
                [
                    'name' => 'Roles',
                    'route' => 'role.index',
                    'icon' => 'fa-solid fa-arrow-right',
                    'active' => request()->routeIs('role.index'),
                    'permission' => 'ver roles',
                ],
            ],
        ],
        [
            'header' => 'Finance',
        ],
        [
            'name' => 'Bank accounts',
            'icon' => 'fa-solid fa-building-columns',
            'route' => 'accountLetters.index',
            'active' => request()->routeIs('accountLetters.index'),
            'can' => ['ver cuentas bancarias'],
        ],
        [
            'name' => 'Cash Drawers',
            'icon' => 'fa-solid fa-sack-dollar',
            'route' => 'cashDrawer.index',
            'active' => request()->routeIs('cashDrawer.index'),
            'can' => null, // No requiere permiso,
        ],
        [
            'name' => 'Loans',
            'icon' => 'fa-solid fa-money-bill',
            'can' => ['ver prestamos', 'ver cobro cuotas', 'ver ceuntas incobrables'], // Lista de permisos
            'submenu' => [
                [
                    'name' => 'Loans',
                    'route' => 'loans.index',
                    'icon' => 'fa-solid fa-arrow-right',
                    'active' => request()->routeIs('loans.index'),
                    'permission' => 'ver prestamos',
                ],
                [
                    'name' => 'Collection of Dues',
                    'route' => 'collectionDues.index',
                    'icon' => 'fa-solid fa-arrow-right',
                    'active' => request()->routeIs('collectionDues.index'),
                    'permission' => 'ver cobro cuotas',
                ],
                [
                    'name' => 'Uncollectible accounts',
                    'route' => 'uncollectibleAccounts.index',
                    'icon' => 'fa-solid fa-arrow-right',
                    'active' => request()->routeIs('uncollectibleAccounts.index'),
                    'permission' => 'ver cuentas incobrables',
                ],
            ],
        ],
        [
            'name' => 'Income',
            'icon' => 'fa-solid fa-piggy-bank',
            'can' => ['ver ingresos', 'ver otros ingresos', 'ver item ingreso'], // Lista de permisos
            'submenu' => [
                [
                    'name' => 'Income from vehicles',
                    'route' => 'income.index',
                    'icon' => 'fa-solid fa-arrow-right',
                    'active' => request()->routeIs('income.index'),
                    'permission' => 'ver ingresos',
                ],
                [
                    'name' => 'Other Income',
                    'route' => 'otherIncome.index',
                    'icon' => 'fa-solid fa-arrow-right',
                    'active' => request()->routeIs('otherIncome.index'),
                    'permission' => 'ver otros ingresos',
                ],
                [
                    'name' => 'Accounts receivable',
                    'route' => 'accountsReceivable.index',
                    'icon' => 'fa-solid fa-arrow-right',
                    'active' => request()->routeIs('accountsReceivable.index'),
                    'permission' => null, // No requiere permiso
                ],
                [
                    'name' => 'Income Categories',
                    'route' => 'incomeCategories.index',
                    'icon' => 'fa-solid fa-arrow-right',
                    'active' => request()->routeIs('incomeCategories.index'),
                    'permission' => 'ver item ingreso',
                ],
            ],
        ],
        [
            'name' => 'Expense',
            'icon' => 'fas fa-hand-holding-dollar',
            'can' => ['ver egreso', 'ver item egreso'], // Lista de permisos
            'submenu' => [
                [
                    'name' => 'Expense',
                    'route' => 'expense.index',
                    'icon' => 'fa-solid fa-arrow-right',
                    'active' => request()->routeIs('expense.index'),
                    'permission' => 'ver egreso',
                ],
                [
                    'name' => 'Expense Categories',
                    'route' => 'expenseCategories.index',
                    'icon' => 'fa-solid fa-arrow-right',
                    'active' => request()->routeIs('expenseCategories.index'),
                    'permission' => 'ver item egreso',
                ],
            ],
        ],
        [
            'name' => 'Casher',
            'icon' => 'fa-solid fa-cash-register',
            'route' => 'cashRegister.index',
            'active' => request()->routeIs('cashRegister.index'),
            'can' => null, // No requiere permiso
        ],
        [
            'name' => 'Reports',
            'icon' => 'fa-solid fa-chart-bar',
            'can' => ['ver reportes'], // Lista de permisos
            'submenu' => [
                [
                    'name' => "Today's Report",
                    'route' => 'today.report',
                    'icon' => 'fa-solid fa-arrow-right',
                    'active' => request()->routeIs('today.report'),
                    'permission' => null,
                ],
                [
                    'name' => 'Income Report',
                    'route' => 'income.report',
                    'icon' => 'fa-solid fa-arrow-right',
                    'active' => request()->routeIs('income.report'),
                    'permission' => null,
                ],
                [
                    'name' => 'Expense Report',
                    'route' => 'expense.report',
                    'icon' => 'fa-solid fa-arrow-right',
                    'active' => request()->routeIs('expense.report'),
                    'permission' => null,
                ],
            ],
        ],
        [
            'header' => 'Settings',
        ],
        [
            'name' => 'Settings',
            'icon' => 'fa-solid fa-gear',
            'can' => null, // No requiere permiso
            'submenu' => [
                [
                    'name' => 'Company Settings',
                    'route' => 'settings.company',
                    'icon' => 'fa-solid fa-arrow-right',
                    'active' => request()->routeIs('settings.company'),
                    'permission' => null,
                ],
                [
                    'name' => 'Email Settings',
                    'route' => 'settings.email',
                    'icon' => 'fa-solid fa-arrow-right',
                    'active' => request()->routeIs('settings.email'),
                    'permission' => null,
                ],
            ],
        ],
        [
            'header' => 'Profile',
        ],
        [
            'name' => 'Profile',
            'icon' => 'fa-solid fa-user',
            'route' => 'profile.show',
            'active' => request()->routeIs('profile.show'),
            'can' => null,
        ],
    ];
@endphp


<nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start rtl:justify-end">
                <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar"
                    type="button"
                    class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd" fill-rule="evenodd"
                            d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                        </path>
                    </svg>
                </button>
                <a href="/" class="flex ms-2 md:me-24">
                    <svg class="w-8 h-8 me-3 text-gray-800 dark:text-white" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                            d="M4 4a2 2 0 0 0-2 2v9a1 1 0 0 0 1 1h.535a3.5 3.5 0 1 0 6.93 0h3.07a3.5 3.5 0 1 0 6.93 0H21a1 1 0 0 0 1-1v-4a.999.999 0 0 0-.106-.447l-2-4A1 1 0 0 0 19 6h-5a2 2 0 0 0-2-2H4Zm14.192 11.59.016.02a1.5 1.5 0 1 1-.016-.021Zm-10 0 .016.02a1.5 1.5 0 1 1-.016-.021Zm5.806-5.572v-2.02h4.396l1 2.02h-5.396Z"
                            clip-rule="evenodd" />
                    </svg>
                    <span
                        class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white">{{ config('app.name') }}</span>
                </a>
            </div>
            <div class="flex items-center">
                <div class="flex items-center ms-3">
                    <div>
                        <button type="button"
                            class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
                            aria-expanded="false" data-dropdown-toggle="dropdown-user">
                            <span class="sr-only">Open user menu</span>
                            <img class="w-8 h-8 rounded-full"
                                src="https://flowbite.com/docs/images/people/profile-picture-5.jpg" alt="user photo">
                        </button>
                    </div>
                    <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600"
                        id="dropdown-user">
                        <div class="px-4 py-3" role="none">
                            <p class="text-sm text-gray-900 dark:text-white" role="none">
                                {{ Auth::user()->name }}
                            </p>
                            <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300" role="none">
                                {{ Auth::user()->email }}
                            </p>
                        </div>
                        <ul class="py-1" role="none">
                            <!--<li>
                                <a href="#"
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                   role="menuitem">Dashboard</a>
                            </li>
                            <li>
                                <a href="#"
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                   role="menuitem">Settings</a>
                            </li>
                            <li>
                                <a href="#"
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                   role="menuitem">Earnings</a>
                            </li> -->
                            <li>
                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf

                                    <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
    aria-label="Sidebar">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
        <ul class="space-y-2 font-medium">
            @foreach ($links as $link)
                @php
                    // Si hay permisos, verificar si el usuario tiene al menos uno
                    $hasPermission = true;
                    if (isset($link['can'])) {
                        $hasPermission = false;
                        foreach ((array) $link['can'] as $permission) {
                            if (Gate::allows($permission)) {
                                $hasPermission = true;
                                break;
                            }
                        }
                    }
                @endphp

                @if ($hasPermission)
                    @isset($link['header'])
                        <div class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase dark:text-gray-400">
                            {{ __($link['header']) }}
                        </div>
                    @else
                        @if (isset($link['submenu']))
                            <li>
                                <button type="button"
                                    class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                                    data-collapse-toggle="dropdown-{{ Str::slug($link['name']) }}">
                                    <i
                                        class="{{ $link['icon'] }} w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                                    <span class="flex-1 ms-3 text-left whitespace-nowrap">{{ __($link['name']) }}</span>
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 10 6">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 4 4 4-4" />
                                    </svg>
                                </button>
                                <ul id="dropdown-{{ Str::slug($link['name']) }}" class="hidden py-2 space-y-2">
                                    @foreach ($link['submenu'] as $submenu)
                                        @if (Gate::allows($submenu['permission']))
                                            <li>
                                                <a href="{{ route($submenu['route']) }}"
                                                    class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700
                                                    {{ $submenu['active'] ? 'bg-gray-200 dark:bg-gray-600' : '' }}">
                                                    <i
                                                        class="{{ $submenu['icon'] }} w-6 h-6 text-gray-800 dark:text-white mr-3 ml-3"></i>
                                                    {{ __($submenu['name']) }}
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            <li>
                                <a href="{{ route($link['route']) }}"
                                    class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 
                                {{ $link['active'] ? 'bg-gray-200 dark:bg-gray-600' : '' }}">
                                    <i class="{{ $link['icon'] }} w-5 h-5 text-gray-500 dark:text-gray-400"></i>
                                    <span class="ms-3">{{ __($link['name']) }}</span>
                                </a>
                            </li>
                        @endif
                    @endisset
                @endif
            @endforeach
            <li>
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf

                    <a href="{{ route('logout') }}"
                        class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                        @click.prevent="$root.submit();">
                        <span class="w-6 h-6 inline-flex justify-center items-center">
                            <i class="fa-solid fa-right-from-bracket text-gray-800"></i>
                        </span>
                        <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">
                            {{ __('Log Out') }}
                        </span>
                    </a>

                </form>
            </li>
        </ul>
    </div>

</aside>
