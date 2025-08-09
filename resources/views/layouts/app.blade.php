<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Sales Recording' }} - Sales Recording App</title>

    {{-- fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    {{-- datatables css --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

    {{-- icon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

    {{-- Jquery --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="font-sans antialiased bg-gray-50 min-h-screen">
    <div class="flex h-screen" x-data="{ sidebarOpen: false }">

        {{-- Sidebar --}}
        <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-xl transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0"
            :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">

            {{-- Sidebar Header --}}
            <div class="flex items-center justify-between h-16 px-6 bg-gray-900">
                <h1 class="text-xl font-bold text-white truncate">
                    Sales Recording
                </h1>
                <button @click="sidebarOpen = false" class="lg:hidden text-gray-300 hover:text-white">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            {{-- Navigation Menu --}}
            <nav class="mt-8 px-4">
                <div class="space-y-2">
                    {{-- Dashboard --}}
                    @role('admin')
                        <a href="{{ route('dashboard.index') }}"
                            class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard.index') ? 'bg-gray-900 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                            <i
                                class="fas fa-tachometer-alt mr-3 text-lg {{ request()->routeIs('dashboard.index') ? 'text-white' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                            <span>Dashboard</span>
                            @if (request()->routeIs('dashboard'))
                                <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                            @endif
                        </a>
                    @endrole

                    {{-- Sales --}}
                    <a href="{{ route('sales.index') }}"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('sales.*') ? 'bg-gray-900 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                        <i
                            class="fas fa-money-bill-transfer mr-3 text-lg {{ request()->routeIs('sales.*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                        <span>Penjualan</span>
                        @if (request()->routeIs('sales.*'))
                            <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                        @endif
                    </a>

                    {{-- Payments --}}
                    <a href="{{ route('payments.index') }}"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('payments.*') ? 'bg-gray-900 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                        <i
                            class="fas fa-credit-card mr-3 text-lg {{ request()->routeIs('payments.*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                        <span>Pembayaran</span>
                        @if (request()->routeIs('payments.*'))
                            <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                        @endif
                    </a>

                    <div class="border-t border-gray-200 my-4"></div>

                    {{-- Master Data Dropdown --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="group flex items-center justify-between w-full px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs(['items.*', 'users.*']) ? 'bg-gray-900 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                            <div class="flex items-center">
                                <i
                                    class="fas fa-database mr-3 text-lg {{ request()->routeIs(['items.*', 'users.*']) ? 'text-white' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                                <span>Master Data</span>
                            </div>
                            <i class="fas fa-chevron-down text-sm transition-transform duration-200"
                                :class="{ 'transform rotate-180': open }"></i>
                        </button>

                        <div x-show="open" x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute left-0 right-0 mt-1 pl-8 space-y-2">

                            {{-- Items Submenu --}}
                            <a href="{{ route('items.index') }}"
                                class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('items.*') ? 'bg-gray-900 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                <i
                                    class="fas fa-box mr-3 text-lg {{ request()->routeIs('items.*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                                <span>Item</span>
                                @if (request()->routeIs('items.*'))
                                    <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                                @endif
                            </a>

                            @role('admin')
                                {{-- Users Submenu --}}
                                <a href="{{ route('users.index') }}"
                                    class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('users.*') ? 'bg-gray-900 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                    <i
                                        class="fas fa-users mr-3 text-lg {{ request()->routeIs('users.*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                                    <span>User</span>
                                    @if (request()->routeIs('users.*'))
                                        <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                                    @endif
                                </a>
                            @endrole
                        </div>
                    </div>
                </div>
            </nav>

            {{-- User Profile Section --}}
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200">
                <div class="flex items-center space-x-3 p-3 rounded-lg bg-gray-50">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-gray-600 text-sm"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">
                            {{ Auth::user()->name }}
                        </p>
                        <p class="text-xs text-gray-500 truncate">
                            {{ Auth::user()->email }}
                        </p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200 cursor-pointer">
                            <i class="fas fa-sign-out-alt text-lg"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Sidebar Overlay (Mobile) --}}
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click="sidebarOpen = false"
            class="fixed inset-0 bg-gray-600 bg-opacity-75 lg:hidden z-40">
        </div>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col lg:ml-0">
            {{-- Top Header --}}
            <header class="bg-white shadow-sm border-b border-gray-200 lg:hidden">
                <div class="flex items-center justify-between h-16 px-6">
                    {{-- Mobile menu button --}}
                    <button @click="sidebarOpen = true"
                        class="p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        <i class="fas fa-bars text-lg"></i>
                    </button>

                    {{-- Page Title --}}
                    <div class="flex-1 flex items-center">
                        <h2 class="text-xl font-semibold text-gray-900 ml-4">
                            {{ $title ?? 'Dashboard' }}
                        </h2>
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto bg-gray-50">
                <div class="p-6">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        {{ $slot }}
                    </div>
                </div>
            </main>
        </div>
    </div>

    @livewireScripts

    {{-- datatables JS --}}
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    @stack('scripts')
</body>

</html>
