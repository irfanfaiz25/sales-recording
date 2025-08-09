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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="font-sans antialiased bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 min-h-screen">
    <div class="min-h-screen fade-in py-4">
        {{-- navigation --}}
        <nav
            class="max-w-7xl mx-auto bg-gradient-to-r from-neutral-900/70 via-neutral-700/70 to-neutral-800/70 backdrop-blur-xl shadow-2xl rounded-2xl sticky top-4 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <div class="flex">

                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('sales.index') }}"
                                class="text-2xl font-bold text-white hover:text-gray-300 transition-colors duration-300 transform hover:scale-105">
                                Sales Recording
                            </a>
                        </div>

                        {{-- navigation links --}}
                        <div class="hidden space-x-2 sm:-my-px sm:ml-10 sm:flex items-center">
                            <a href="{{ route('sales.index') }}"
                                class="{{ request()->routeIs('sales.*') ? 'text-white' : 'text-blue-200 hover:text-white' }} inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-300 ease-in-out transform hover:scale-105 border-2 border-transparent">
                                <i class="fa fa-dolly mr-2"></i>
                                Penjualan
                            </a>
                            <a href="{{ route('payments.index') }}"
                                class="{{ request()->routeIs('payments.*') ? 'text-white' : 'text-blue-200 hover:text-white' }} inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-300 ease-in-out transform hover:scale-105 border-2 border-transparent">
                                <i class="fa fa-money-bills mr-2"></i>
                                Pembayaran
                            </a>
                            <a href="{{ route('items.index') }}"
                                class="{{ request()->routeIs('items.*') ? 'text-white' : 'text-blue-200 hover:text-white' }} inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-300 ease-in-out transform hover:scale-105 border-2 border-transparent">
                                <i class="fa fa-clipboard-list mr-2"></i>
                                Item
                            </a>
                            <a href="{{ route('users.index') }}"
                                class="{{ request()->routeIs('users.*') ? 'text-white' : 'text-blue-200 hover:text-white' }} inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-300 ease-in-out transform hover:scale-105 border-2 border-transparent">
                                <i class="fa fa-users mr-2"></i>
                                User
                            </a>
                        </div>
                    </div>

                    <div
                        class="h-7 w-7 flex justify-center items-center bg-gray-100 text-gray-600 text-sm rounded-full">
                        <i class="fa fa-user"></i>
                    </div>

                    {{-- mobile menu button --}}
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button type="button"
                            class="bg-white bg-opacity-20 inline-flex items-center justify-center p-3 rounded-xl text-white hover:bg-white hover:bg-opacity-30 focus:outline-none focus:ring-4 focus:ring-white focus:ring-opacity-50 transition-all duration-300 transform hover:scale-110"
                            x-data="{ open: false }" @click="open = !open">
                            <span class="sr-only">Open main menu</span>
                            <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- mobile menu --}}
            <div class="sm:hidden bg-white bg-opacity-10 backdrop-blur-lg" x-data="{ open: false }" x-show="open"
                x-transition>
                <div class="pt-4 pb-4 space-y-2 px-4">
                    <a href="{{ route('sales.index') }}"
                        class="{{ request()->routeIs('sales.*') ? 'bg-white bg-opacity-20 text-white' : 'text-gray-300 hover:bg-white hover:bg-opacity-10 hover:text-white' }} flex items-center px-4 py-3 rounded-xl text-base font-semibold transition-all duration-300">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z">
                            </path>
                        </svg>
                        Penjualan
                    </a>
                    <a href="{{ route('payments.index') }}"
                        class="{{ request()->routeIs('payments.*') ? 'bg-white bg-opacity-20 text-white' : 'text-gray-300 hover:bg-white hover:bg-opacity-10 hover:text-white' }} flex items-center px-4 py-3 rounded-xl text-base font-semibold transition-all duration-300">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z">
                            </path>
                        </svg>
                        Pembayaran
                    </a>
                    <a href="{{ route('items.index') }}"
                        class="{{ request()->routeIs('items.*') ? 'bg-white bg-opacity-20 text-white' : 'text-gray-300 hover:bg-white hover:bg-opacity-10 hover:text-white' }} flex items-center px-4 py-3 rounded-xl text-base font-semibold transition-all duration-300">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 2L3 7v11a1 1 0 001 1h12a1 1 0 001-1V7l-7-5zM6 9a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Item
                    </a>
                    <a href="{{ route('users.index') }}"
                        class="{{ request()->routeIs('users.*') ? 'bg-white bg-opacity-20 text-white' : 'text-gray-300 hover:bg-white hover:bg-opacity-10 hover:text-white' }} flex items-center px-4 py-3 rounded-xl text-base font-semibold transition-all duration-300">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z">
                            </path>
                        </svg>
                        User
                    </a>
                </div>
            </div>
        </nav>

        {{-- page content --}}
        <main class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div
                    class="bg-white bg-opacity-80 backdrop-blur-sm rounded-3xl shadow-2xl p-8 border border-white border-opacity-20">
                    {{ $slot }}
                </div>
            </div>
        </main>
    </div>

    @livewireScripts

    {{-- datatables JS --}}
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    {{-- <script src="https://unpkg.com/@popperjs/core@2"></script> --}}

    @stack('scripts')
</body>

</html>
