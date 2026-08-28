<!DOCTYPE html>
<html lang="en" class="antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FleetGo System</title>
    <!-- Preconnect to CDNs to speed up loading -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://unpkg.com">

    <!-- Premium Fonts: Outfit for body, Montserrat for Logo -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Montserrat:wght@800;900&display=swap" rel="stylesheet">
    
    <!-- Alpine.js for Mobile Menu -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Ionicons for beautiful simple icons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    
    <!-- Flatpickr for premium date inputs -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    <script>
        // Check local storage or system preference on load to prevent FOUC
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-800 flex h-screen overflow-hidden transition-colors duration-300 bg-white dark:bg-gray-900" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Overlay -->
    <div x-show="sidebarOpen" class="fixed inset-0 z-20 bg-black/50 md:hidden" @click="sidebarOpen = false" x-transition.opacity></div>

    <!-- Sidebar (Desktop & Mobile) -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-30 w-64 bg-primary-900 text-white flex flex-col transition-transform duration-300 md:relative md:translate-x-0 shrink-0">
        <div class="h-24 flex items-center justify-between px-8">
            <h1 class="text-3xl font-extrabold tracking-tight logo-font">Fleet<span class="text-blue-400">Go</span></h1>
            <!-- Close button for mobile -->
            <button @click="sidebarOpen = false" class="md:hidden text-gray-300 hover:text-white">
                <ion-icon name="close-outline" class="text-3xl"></ion-icon>
            </button>
        </div>
        
        <nav class="flex-1 pl-4 mt-6 space-y-2 overflow-y-auto">
            @php
                $role = auth()->user()->role ?? 'driver';
                $prefix = '/' . $role;
            @endphp
            
            <div class="mb-4 px-4 text-xs font-semibold text-blue-300 uppercase tracking-wider">Overview</div>
            
            <a href="{{ $prefix }}/dashboard" class="flex items-center px-5 py-3.5 text-sm font-medium transition-colors rounded-l-full {{ request()->is($role.'/dashboard') ? 'sidebar-link-active' : 'text-gray-300 hover:text-white hover:bg-primary-800' }}">
                <ion-icon name="grid-outline" class="text-xl mr-4"></ion-icon>
                Dashboard
            </a>
            
            <a href="{{ $prefix }}/trips" class="flex items-center px-5 py-3.5 text-sm font-medium transition-colors rounded-l-full {{ request()->is($role.'/trips*') ? 'sidebar-link-active' : 'text-gray-300 hover:text-white hover:bg-primary-800' }}">
                <ion-icon name="car-outline" class="text-xl mr-4"></ion-icon>
                Trips
            </a>
            
            @if($role === 'admin' || $role === 'manager')
            <a href="{{ $prefix }}/reports" class="flex items-center px-5 py-3.5 text-sm font-medium transition-colors rounded-l-full {{ request()->is($role.'/reports*') ? 'sidebar-link-active' : 'text-gray-300 hover:text-white hover:bg-primary-800' }}">
                <ion-icon name="document-text-outline" class="text-xl mr-4"></ion-icon>
                Reports
            </a>
            @endif
            
            @if($role === 'admin')
            <div class="mt-8 mb-4 px-4 text-xs font-semibold text-blue-300 uppercase tracking-wider">Management</div>
            <a href="{{ $prefix }}/users" class="flex items-center px-5 py-3.5 text-sm font-medium transition-colors rounded-l-full {{ request()->is($role.'/users*') ? 'sidebar-link-active' : 'text-gray-300 hover:text-white hover:bg-primary-800' }}">
                <ion-icon name="people-outline" class="text-xl mr-4"></ion-icon>
                Users
            </a>
            <a href="{{ $prefix }}/vehicles" class="flex items-center px-5 py-3.5 text-sm font-medium transition-colors rounded-l-full {{ request()->is($role.'/vehicles*') ? 'sidebar-link-active' : 'text-gray-300 hover:text-white hover:bg-primary-800' }}">
                <ion-icon name="bus-outline" class="text-xl mr-4"></ion-icon>
                Vehicles
            </a>
            @endif

        </nav>
        
        <!-- Bottom Logout -->
        <div class="p-4 mt-auto mb-6 pl-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center w-full px-5 py-3.5 text-sm font-medium text-red-300 hover:text-red-100 transition-colors rounded-l-full hover:bg-red-900/30">
                    <ion-icon name="log-out-outline" class="text-xl mr-4"></ion-icon>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col overflow-hidden relative">
        <!-- Top Nav -->
        <header class="h-24 flex items-center justify-between px-6 lg:px-10 bg-transparent z-10 w-full">
            
            <div class="flex items-center flex-1">
                <!-- Mobile Menu Button -->
                <button @click="sidebarOpen = true" class="md:hidden text-gray-600 dark:text-gray-300 hover:text-primary-900 focus:outline-none mr-4">
                    <ion-icon name="menu-outline" class="text-3xl"></ion-icon>
                </button>
                
                @if($role === 'admin' || $role === 'manager')
                <!-- Search and Filter (Desktop) -->
                <form action="{{ route($role . '.trips') }}" method="GET" class="hidden lg:flex items-center space-x-4 max-w-xl w-full">
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400">
                            <ion-icon name="search-outline" class="text-xl"></ion-icon>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-full w-full pl-12 p-2.5 shadow-sm focus:ring-2 focus:ring-primary-500 focus:outline-none" placeholder="Search trips, drivers, or vehicles...">
                    </div>
                    
                    <div x-data="{ open: false }" class="relative shrink-0" @click.outside="open = false">
                        <button type="button" @click="open = !open" class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 px-5 py-2.5 rounded-full text-sm font-semibold shadow-sm hover:text-primary-600 flex items-center">
                            <ion-icon name="options-outline" class="text-xl mr-2"></ion-icon> Filter
                        </button>
                        <div x-show="open" x-transition class="absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 p-4 z-50">
                            <div class="mb-4" x-data="{ selected: '{{ request('status') }}', text: 'All Statuses' }" x-init="if('{{ request('status') }}' === 'active') text = 'Active (On Trip)'; if('{{ request('status') }}' === 'completed') text = 'Completed';">
                                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Status</label>
                                <input type="hidden" name="status" :value="selected">
                                <div class="relative">
                                    <button type="button" @click="$refs.statusDropdown.classList.toggle('hidden')" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-700 rounded-xl text-left flex justify-between items-center focus:ring-2 focus:ring-primary-500 text-sm">
                                        <span x-text="text" class="text-gray-900 dark:text-white"></span>
                                        <ion-icon name="chevron-down-outline" class="text-gray-400"></ion-icon>
                                    </button>
                                    <div x-ref="statusDropdown" class="hidden absolute w-full mt-1 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-xl z-[60]">
                                        <div @click="selected = ''; text = 'All Statuses'; $refs.statusDropdown.classList.add('hidden')" class="px-4 py-2.5 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer text-sm text-gray-900 dark:text-white border-b border-gray-50 dark:border-gray-700/50">All Statuses</div>
                                        <div @click="selected = 'active'; text = 'Active (On Trip)'; $refs.statusDropdown.classList.add('hidden')" class="px-4 py-2.5 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer text-sm text-gray-900 dark:text-white border-b border-gray-50 dark:border-gray-700/50">Active (On Trip)</div>
                                        <div @click="selected = 'completed'; text = 'Completed'; $refs.statusDropdown.classList.add('hidden')" class="px-4 py-2.5 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer text-sm text-gray-900 dark:text-white border-b border-gray-50 dark:border-gray-700/50">Completed</div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="w-full py-2 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-lg text-sm transition-colors">Apply Filters</button>
                        </div>
                    </div>
                </form>
                @endif
            </div>
            
            <div class="flex items-center space-x-4 ml-auto">
                <!-- Theme Toggle -->
                <button id="theme-toggle" class="w-10 h-10 rounded-full bg-white dark:bg-gray-800 shadow-sm text-gray-500 dark:text-gray-300 hover:text-primary-900 flex items-center justify-center transition-transform hover:scale-105">
                    <ion-icon name="moon-outline" class="text-xl"></ion-icon>
                </button>
                
                <div class="flex items-center space-x-3 bg-white dark:bg-gray-800 px-4 py-2 rounded-full shadow-sm cursor-default hover:shadow-md transition-shadow">
                    <div class="w-9 h-9 rounded-full bg-primary-100 flex items-center justify-center text-primary-900 font-bold">
                        {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                    </div>
                    <!-- Greeting -->
                    <div class="hidden md:block">
                        <h2 class="text-sm font-bold text-gray-900 dark:text-white leading-tight">Hello, {{ explode(' ', auth()->user()->name)[0] ?? 'User' }}</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400 capitalize">{{ auth()->user()->role ?? 'Role' }}</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto p-6 lg:p-10 pt-0">
            @if($role === 'admin' || $role === 'manager')
            <!-- Mobile Search (Visible only on small screens) -->
            <form action="{{ route($role . '.trips') }}" method="GET" class="lg:hidden flex items-center space-x-2 mb-6 relative">
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400">
                        <ion-icon name="search-outline"></ion-icon>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-full w-full pl-10 p-2.5 shadow-sm focus:ring-2 focus:ring-primary-500 focus:outline-none" placeholder="Search...">
                </div>
                <div x-data="{ open: false }" @click.outside="open = false">
                    <button type="button" @click="open = !open" class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 p-2.5 rounded-full shadow-sm hover:text-primary-600">
                        <ion-icon name="options-outline" class="text-xl"></ion-icon>
                    </button>
                    <div x-show="open" x-transition class="absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 p-4 z-50">
                        <div class="mb-4" x-data="{ selected: '{{ request('status') }}', text: 'All Statuses' }" x-init="if('{{ request('status') }}' === 'active') text = 'Active (On Trip)'; if('{{ request('status') }}' === 'completed') text = 'Completed';">
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Status</label>
                            <input type="hidden" name="status" :value="selected">
                            <div class="relative">
                                <button type="button" @click="$refs.mobileStatusDropdown.classList.toggle('hidden')" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-700 rounded-xl text-left flex justify-between items-center focus:ring-2 focus:ring-primary-500 text-sm">
                                    <span x-text="text" class="text-gray-900 dark:text-white"></span>
                                    <ion-icon name="chevron-down-outline" class="text-gray-400"></ion-icon>
                                </button>
                                <div x-ref="mobileStatusDropdown" class="hidden absolute w-full mt-1 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-xl z-[60]">
                                    <div @click="selected = ''; text = 'All Statuses'; $refs.mobileStatusDropdown.classList.add('hidden')" class="px-4 py-2.5 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer text-sm text-gray-900 dark:text-white border-b border-gray-50 dark:border-gray-700/50">All Statuses</div>
                                    <div @click="selected = 'active'; text = 'Active (On Trip)'; $refs.mobileStatusDropdown.classList.add('hidden')" class="px-4 py-2.5 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer text-sm text-gray-900 dark:text-white border-b border-gray-50 dark:border-gray-700/50">Active (On Trip)</div>
                                    <div @click="selected = 'completed'; text = 'Completed'; $refs.mobileStatusDropdown.classList.add('hidden')" class="px-4 py-2.5 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer text-sm text-gray-900 dark:text-white border-b border-gray-50 dark:border-gray-700/50">Completed</div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="w-full py-2 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-lg text-sm transition-colors">Apply Filters</button>
                    </div>
                </div>
            </form>
            @endif

            @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-xl border border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800 flex items-center shadow-sm">
                <ion-icon name="checkmark-circle" class="text-xl mr-3"></ion-icon>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 p-4 bg-red-100 text-red-800 rounded-xl border border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800 flex items-center shadow-sm">
                <ion-icon name="alert-circle" class="text-xl mr-3"></ion-icon>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggleBtn = document.getElementById('theme-toggle');
            if (themeToggleBtn) {
                themeToggleBtn.addEventListener('click', function() {
                    if (document.documentElement.classList.contains('dark')) {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('color-theme', 'light');
                    } else {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('color-theme', 'dark');
                    }
                });
            }
        });
    </script>
</body>
</html>
