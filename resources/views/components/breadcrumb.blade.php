<div>
    <header class="fi-header flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <!-- Breadcrumbs -->
            <nav class="fi-breadcrumbs mb-2 hidden sm:block">
                <ol class="fi-breadcrumbs-list flex flex-wrap items-center gap-x-2">
                    <li class="fi-breadcrumbs-item flex items-center gap-x-2">
                        <a href="{{ $breadcrumbMainUrl }}"
                           class="fi-breadcrumbs-item-label text-sm font-medium text-gray-500 dark:text-gray-400 transition duration-75 hover:text-gray-700 dark:hover:text-gray-200">
                            {{ $breadcrumbMain }}
                        </a>
                    </li>
                    <li class="fi-breadcrumbs-item flex items-center gap-x-2">
                        <svg
                            class="fi-breadcrumbs-item-separator flex h-5 w-5 text-gray-400 dark:text-gray-500 rtl:hidden"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            aria-hidden="true">
                            <path fill-rule="evenodd"
                                  d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"
                                  clip-rule="evenodd"></path>
                        </svg>
                        <span class="fi-breadcrumbs-item-label text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ $breadcrumbCurrent }}
                    </span>
                    </li>
                </ol>
            </nav>

            <h1 class="fi-header-heading text-2xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-3xl">
                {{ $pageTitle }}
            </h1>
        </div>

        <!-- Espacio para el botón de creación o contenido personalizado -->
        <div class="flex items-center gap-3 sm:mt-7">
            {{ $slot }}
        </div>
    </header>
</div>
