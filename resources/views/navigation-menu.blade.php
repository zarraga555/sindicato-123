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
                              d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                    </svg>
                </button>
                <a href="/" class="flex ms-2 md:me-24">
                    <svg class="w-8 h-8 me-3 text-gray-800 dark:text-white" aria-hidden="true"
                         xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                         viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                              d="M4 4a2 2 0 0 0-2 2v9a1 1 0 0 0 1 1h.535a3.5 3.5 0 1 0 6.93 0h3.07a3.5 3.5 0 1 0 6.93 0H21a1 1 0 0 0 1-1v-4a.999.999 0 0 0-.106-.447l-2-4A1 1 0 0 0 19 6h-5a2 2 0 0 0-2-2H4Zm14.192 11.59.016.02a1.5 1.5 0 1 1-.016-.021Zm-10 0 .016.02a1.5 1.5 0 1 1-.016-.021Zm5.806-5.572v-2.02h4.396l1 2.02h-5.396Z"
                              clip-rule="evenodd"/>
                    </svg>
                    <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white">{{ config('app.name') }}</span>
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
                    <div
                        class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600"
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

                                    <x-dropdown-link href="{{ route('logout') }}"
                                                     @click.prevent="$root.submit();">
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
            @if (Gate::allows('ver usuarios', App\Models\Users::class) || Gate::allows('ver roles',
            Spatie\Permission\Models\Role::class))
            <li>
                <button type="button"
                        class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                        aria-controls="dropdown-example4" data-collapse-toggle="dropdown-example4">
                    <svg
                        class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                              d="M12 6a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Zm-1.5 8a4 4 0 0 0-4 4 2 2 0 0 0 2 2h7a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-3Zm6.82-3.096a5.51 5.51 0 0 0-2.797-6.293 3.5 3.5 0 1 1 2.796 6.292ZM19.5 18h.5a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-1.1a5.503 5.503 0 0 1-.471.762A5.998 5.998 0 0 1 19.5 18ZM4 7.5a3.5 3.5 0 0 1 5.477-2.889 5.5 5.5 0 0 0-2.796 6.293A3.501 3.501 0 0 1 4 7.5ZM7.1 12H6a4 4 0 0 0-4 4 2 2 0 0 0 2 2h.5a5.998 5.998 0 0 1 3.071-5.238A5.505 5.505 0 0 1 7.1 12Z"
                              clip-rule="evenodd"/>
                    </svg>
                    <span
                        class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">{{__('User Management')}}</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="m1 1 4 4 4-4"/>
                    </svg>

                </button>
                <ul id="dropdown-example4" class="hidden py-2 space-y-2">
                    @can('ver usuarios')
                    <li>
                        <a href="{{ route('user.index') }}"
                           class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white mr-3 ml-3" aria-hidden="true"
                                 xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                 viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M19 12H5m14 0-4 4m4-4-4-4"/>
                            </svg>

                            {{__('Users')}}</a>
                    </li>
                    @endcan
                    @can('ver roles', Spatie\Permission\Models\Role::class)
                    <li>
                        <a href="{{ route('role.index') }}"
                           class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white mr-3 ml-3" aria-hidden="true"
                                 xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                 viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M19 12H5m14 0-4 4m4-4-4-4"/>
                            </svg>
                            {{__('Roles')}}</a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endif

            @can('ver cuentas bancarias')
            <li>
                <a href="{{ route('accountLetters.index') }}"
                   class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                    <svg
                        class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                              d="M12 14a3 3 0 0 1 3-3h4a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2h-4a3 3 0 0 1-3-3Zm3-1a1 1 0 1 0 0 2h4v-2h-4Z"
                              clip-rule="evenodd"/>
                        <path fill-rule="evenodd"
                              d="M12.293 3.293a1 1 0 0 1 1.414 0L16.414 6h-2.828l-1.293-1.293a1 1 0 0 1 0-1.414ZM12.414 6 9.707 3.293a1 1 0 0 0-1.414 0L5.586 6h6.828ZM4.586 7l-.056.055A2 2 0 0 0 3 9v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2h-4a5 5 0 0 1 0-10h4a2 2 0 0 0-1.53-1.945L17.414 7H4.586Z"
                              clip-rule="evenodd"/>
                    </svg>

                    <span class="flex-1 ms-3 whitespace-nowrap">Cuentas Bancarias</span>
                    <!--                    <span
                                            class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-300">Pro</span> -->
                </a>
            </li>
            @endcan

            <!-- || Gate::allows('ver cuentas incobrables') || Gate::allows('ver cuotas')-->
            @if (Gate::allows('ver prestamos') )
            <li>
                <button type="button"
                        class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                        aria-controls="dropdown-example5" data-collapse-toggle="dropdown-example5">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                         viewBox="0 0 600 602.2" enable-background="new 0 0 600 602.2" xml:space="preserve"
                         class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white">
                          <path
                              d="M122.2,234c-61.8,0-112-50.2-112-112s50.5-112,112-112c61.8,0,112,50.2,112,112S184.1,234,122.2,234z M122.2,31.2  c-49.9,0-90.7,40.8-90.7,90.7s40.8,90.7,90.7,90.7s90.7-40.8,90.7-90.7S172.5,31.2,122.2,31.2z M395.4,426  c20.2-25.8,16.7-63.4-10.2-84.5c-25.8-20.2-64.3-15.6-84.5,10.2s-16.7,63.4,10.2,84.5C336.7,456.2,375.2,451.6,395.4,426z   M322.5,398.4c-8.1-6.5-10.4-16.5-5.1-27.4l-8.1-6.5l7.2-9.3l7.2,5.6c3.3-4.2,9.3-7.7,13.7-10.9l7.9,11.1c-2.6,1.2-7,4.6-11.8,10.7  c-3.9,5.1-4.4,9.8-1.4,12.3c3,2.3,7.7,0.9,16.3-3.7c12.1-7,20.4-9.1,28.6-2.6c6.5,6.7,7.7,17.6,3.3,27.6l8.1,6.5l-7.2,9.3l-7.2-5.6  c-3.3,4.2-11.8,10.7-15.1,13l-7.9-11.1c4.4-1.4,11.4-6,15.3-11.1c4.9-6.3,5.1-10.9,1.2-14.2c-3-2.3-6.7-2.1-15.6,2.8  C341.3,401.6,330.7,404.9,322.5,398.4z M110.4,143.8c-3.2-1.9-5.1-5.4-5.1-9.2V60.9c0-5.9,4.9-10.5,10.5-10.5s10.5,4.9,10.5,10.5  v55.9l51.8-28.3c5.1-2.7,11.6-0.8,14.3,4.3c2.7,5.1,0.8,11.6-4.3,14.3l-67.5,36.7C119.3,144.9,113.9,145.7,110.4,143.8z   M451.4,186.6l-35.1,39.5l-37.8-29.5l-93.8,120.3c-1.9-1.2-7.4-3.7-7.9-4.4c-20-9.3-46.9-8.4-66.4,1.6L7.4,427.4v150.5L97.3,534  c40.4,19,86.6,14.2,123.8-8.8l87.8,68.5l26.2-33.7l20.7,18.3l236.8-265.9L451.4,186.6z M329.3,540.5c-17-8.8-38.1-6-52,7.2  l-69.9-54.8c-15.3,12.3-33.9,18.8-52.9,18.8c-8.6,0-17-1.4-25.3-3.9c-4.2-1.4-6.5-5.8-5.3-10c1.4-4.2,5.8-6.5,10-5.3  c20.7,6.5,43.2,2.8,60.6-9.8l0,0c4.4-3.3,9.1-7.2,13.9-12.5l21.4-25.3l61.8,48.5c10.7,7.4,23.2,5.6,30.2-2.8  c7.4-10.7,5.6-23.5-2.8-30.2l-111-89.4c-3.3-2.6-3.7-7.4-1.2-10.7c2.6-3.3,7.4-3.7,10.7-1.2l34.1,27.4L359,248.4  c16.5,8.6,36.7,6,50.4-6.3l64.8,50.4c-8.8,17-5.8,37.8,7.4,51.8L329.3,540.5z M365.5,520.8l162.1-208.1L434,239.8  c14.9,6.5,32.5,4.4,45.5-5.8l61.3,54.6c-9.8,16.3-8.4,37.4,3.9,52L379.6,526.1C375.2,523.8,370.6,521.9,365.5,520.8z"/>
                    </svg>

                    <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">{{__('Loans')}}</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="m1 1 4 4 4-4"/>
                    </svg>
                </button>
                <ul id="dropdown-example5" class="hidden py-2 space-y-2">
                    @can('ver prestamos')
                    <li>
                        <a href="{{ route('loans.index') }}"
                           class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white mr-3 ml-3" aria-hidden="true"
                                 xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                 viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M19 12H5m14 0-4 4m4-4-4-4"/>
                            </svg>
                            {{__('Loans')}}</a>
                    </li>
                    @endcan

                    <li>
                        <a href="{{ route('collectionDues.index') }}"
                           class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white mr-3 ml-3" aria-hidden="true"
                                 xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                 viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M19 12H5m14 0-4 4m4-4-4-4"/>
                            </svg>
                            {{__('Collection of Dues')}}</a>
                    </li>

                    <li>
                        <a href="{{ route('uncollectibleAccounts.index') }}"
                           class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white mr-3 ml-3" aria-hidden="true"
                                 xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                 viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M19 12H5m14 0-4 4m4-4-4-4"/>
                            </svg>
                            {{__('Uncollectible accounts')}}</a>
                    </li>

                </ul>
            </li>
            @endif

            @if (Gate::allows('ver ingresos') || Gate::allows('ver otros ingresos') || Gate::allows('ver otros
            ingresos'))
            <li>
                <button type="button"
                        class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                        aria-controls="dropdown-example" data-collapse-toggle="dropdown-example">
                    <svg
                        class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                              d="M7 6a2 2 0 0 1 2-2h11a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2h-2v-4a3 3 0 0 0-3-3H7V6Z"
                              clip-rule="evenodd"/>
                        <path fill-rule="evenodd"
                              d="M2 11a2 2 0 0 1 2-2h11a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-7Zm7.5 1a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5Z"
                              clip-rule="evenodd"/>
                        <path d="M10.5 14.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z"/>
                    </svg>

                    <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">{{__('Income')}}</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="m1 1 4 4 4-4"/>
                    </svg>
                </button>
                <ul id="dropdown-example" class="hidden py-2 space-y-2">
                    @can('ver ingresos')
                    <li>
                        <a href="{{ route('income.index') }}"
                           class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white mr-3 ml-3" aria-hidden="true"
                                 xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                 viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M19 12H5m14 0-4 4m4-4-4-4"/>
                            </svg>
                            {{__('Income from vehicles')}}</a>
                    </li>
                    @endcan
                    @can('ver otros ingresos')
                    <li>
                        <a href="{{ route('otherIncome.index') }}"
                           class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white mr-3 ml-3" aria-hidden="true"
                                 xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                 viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M19 12H5m14 0-4 4m4-4-4-4"/>
                            </svg>
                            {{__('Other Income')}}</a>
                    </li>
                    @endcan
                    @can('ver item ingreso')
                    <li>
                        <a href="{{ route('incomeCategories.index') }}"
                           class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white mr-3 ml-3" aria-hidden="true"
                                 xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                 viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M19 12H5m14 0-4 4m4-4-4-4"/>
                            </svg>
                            {{__('Income Categories')}}</a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endif
            @if (Gate::allows('ver egreso') || Gate::allows('ver item egreso'))
            <li>
                <button type="button"
                        class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                        aria-controls="dropdown-example2" data-collapse-toggle="dropdown-example2">
                    <svg
                        class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 21">
                        <path
                            d="M15 12a1 1 0 0 0 .962-.726l2-7A1 1 0 0 0 17 3H3.77L3.175.745A1 1 0 0 0 2.208 0H1a1 1 0 0 0 0 2h.438l.6 2.255v.019l2 7 .746 2.986A3 3 0 1 0 9 17a2.966 2.966 0 0 0-.184-1h2.368c-.118.32-.18.659-.184 1a3 3 0 1 0 3-3H6.78l-.5-2H15Z"/>
                    </svg>
                    <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">{{__('Expense')}}</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="m1 1 4 4 4-4"/>
                    </svg>
                </button>
                <ul id="dropdown-example2" class="hidden py-2 space-y-2">
                    @can('ver egreso')
                    <li>
                        <a href="{{ route('expense.index') }}"
                           class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white mr-3 ml-3" aria-hidden="true"
                                 xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                 viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M19 12H5m14 0-4 4m4-4-4-4"/>
                            </svg>
                            {{__('Expense')}}</a>
                    </li>
                    @endcan
                    @can('ver item egreso')
                    <li>
                        <a href="{{ route('expenseCategories.index') }}"
                           class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white mr-3 ml-3" aria-hidden="true"
                                 xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                 viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M19 12H5m14 0-4 4m4-4-4-4"/>
                            </svg>

                            {{__('Expense Categories')}}</a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endif
            @if (Gate::allows('ver reportes'))
            <li>
                <button type="button"
                        class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                        aria-controls="dropdown-reports" data-collapse-toggle="dropdown-reports">
                    <svg
                        class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                              d="M9 7V2.221a2 2 0 0 0-.5.365L4.586 6.5a2 2 0 0 0-.365.5H9Zm2 0V2h7a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V9h5a2 2 0 0 0 2-2Zm-1 9a1 1 0 1 0-2 0v2a1 1 0 1 0 2 0v-2Zm2-5a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1Zm4 4a1 1 0 1 0-2 0v3a1 1 0 1 0 2 0v-3Z"
                              clip-rule="evenodd"/>
                    </svg>

                    <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">{{__('Reports')}}</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="m1 1 4 4 4-4"/>
                    </svg>
                </button>
                <ul id="dropdown-reports" class="hidden py-2 space-y-2">
                    <li>
                        <a href="{{ route('today.report') }}"
                           class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg  group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white mr-3 ml-3" aria-hidden="true"
                                 xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                 viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M19 12H5m14 0-4 4m4-4-4-4"/>
                            </svg>
                            {{__("Today's Report")}}</a>
                    </li>
                    <!--
                    <li>
                        <a href="#"
                           class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                            {{__('Vehicle Report')}}</a>
                    </li>
                    -->
                    <li>
                        <a href="{{ route('income.report') }}"
                           class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white mr-3 ml-3" aria-hidden="true"
                                 xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                 viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M19 12H5m14 0-4 4m4-4-4-4"/>
                            </svg>
                            {{__('Income Report')}}</a>
                    </li>
                    <li>
                        <a href="{{ route('expense.report') }}"
                           class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white mr-3 ml-3" aria-hidden="true"
                                 xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                 viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M19 12H5m14 0-4 4m4-4-4-4"/>
                            </svg>
                            {{__('Expense Report')}}</a>
                    </li>
                    <!--
                    <li>
                        <a href="#}"
                           class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                           {{__('Loan Report')}}</a>
                    </li>
                    -->
                </ul>
            </li>
            @endif
            <!--
                        <li>
                            <a href="#"
                               class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                <svg
                                    class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                                    <path
                                        d="M17 5.923A1 1 0 0 0 16 5h-3V4a4 4 0 1 0-8 0v1H2a1 1 0 0 0-1 .923L.086 17.846A2 2 0 0 0 2.08 20h13.84a2 2 0 0 0 1.994-2.153L17 5.923ZM7 9a1 1 0 0 1-2 0V7h2v2Zm0-5a2 2 0 1 1 4 0v1H7V4Zm6 5a1 1 0 1 1-2 0V7h2v2Z"/>
                                </svg>
                                <span class="flex-1 ms-3 whitespace-nowrap">Products</span>
                            </a>
                        </li>

                        <li>
                            <a href="#"
                               class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                <svg
                                    class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M1 8h11m0 0L8 4m4 4-4 4m4-11h3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-3"/>
                                </svg>
                                <span class="flex-1 ms-3 whitespace-nowrap">Sign In</span>
                            </a>
                        </li>

                        <li>
                            <a href="#"
                               class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                <svg
                                    class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5 5V.13a2.96 2.96 0 0 0-1.293.749L.879 3.707A2.96 2.96 0 0 0 .13 5H5Z"/>
                                    <path
                                        d="M6.737 11.061a2.961 2.961 0 0 1 .81-1.515l6.117-6.116A4.839 4.839 0 0 1 16 2.141V2a1.97 1.97 0 0 0-1.933-2H7v5a2 2 0 0 1-2 2H0v11a1.969 1.969 0 0 0 1.933 2h12.134A1.97 1.97 0 0 0 16 18v-3.093l-1.546 1.546c-.413.413-.94.695-1.513.81l-3.4.679a2.947 2.947 0 0 1-1.85-.227 2.96 2.96 0 0 1-1.635-3.257l.681-3.397Z"/>
                                    <path
                                        d="M8.961 16a.93.93 0 0 0 .189-.019l3.4-.679a.961.961 0 0 0 .49-.263l6.118-6.117a2.884 2.884 0 0 0-4.079-4.078l-6.117 6.117a.96.96 0 0 0-.263.491l-.679 3.4A.961.961 0 0 0 8.961 16Zm7.477-9.8a.958.958 0 0 1 .68-.281.961.961 0 0 1 .682 1.644l-.315.315-1.36-1.36.313-.318Zm-5.911 5.911 4.236-4.236 1.359 1.359-4.236 4.237-1.7.339.341-1.699Z"/>
                                </svg>
                                <span class="flex-1 ms-3 whitespace-nowrap">Sign Up</span>
                            </a>
                        </li>
                    -->
        </ul>
    </div>
</aside>
