<!DOCTYPE html>
<html dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true', sidebarOpen: false }" :class="{ 'dark': darkMode }">
{{-- <html lang="{{ str_replace('_', '-', app()->getLocale()) }}"class="light" x-data="{ sideBarOpen: false, darkMode: localStorage.getItem('darkMode') === 'true' }"
:class="{ 'dark': darkMode }"> --}}

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ auth()->id() }}">
    <script src="https://unpkg.com/maplibre-gl@3.0.0/dist/maplibre-gl.js"></script>
    <link href="https://unpkg.com/maplibre-gl/dist/maplibre-gl.css" rel="stylesheet" />
    <title>{{ config('app.name', 'ClinicBooking') }}@yield('title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <!-- Maplibre GL -->
    <l ink href="https://unpkg.com/maplibre-gl/dist/maplibre-gl.css" rel="stylesheet" />
    <script src="https://unpkg.com/maplibre-gl/dist/maplibre-gl.js"></script>

    <!-- Maplibre GL Leaflet  -->
    <script src="https://unpkg.com/@maplibre/maplibre-gl-leaflet/leaflet-maplibre-gl.js"></script>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

{{-- <body
    class="font-sans antialiased bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 transition-colors duration-300"> --}}

<body class="bg-grey-100 dark:bg-grey-900">

    <x-theme-toggle />
    @auth
    <div class="min-h-screen flex h-screen overflow-hidden">
        @yield('sidebar')
        {{-- <div x-data="{ sidebarOpen: false }" @mouseover="sidebarOpen = true" @mouseleave="sidebarOpen = false">
                <aside :class="{ 'w-64': sidebarOpen, 'w-16': !sidebarOpen }"
                    class="fixed top-0 left-0 h-full bg-gray-800 transition-all duration-300 ease-in-out">
                    <!-- Links and content with dynamic opacity based on sidebarOpen state -->
                </aside>

                <div :class="{ 'ml-64': sidebarOpen, 'ml-16': !sidebarOpen }"
                    class="transition-all duration-300 ease-in-out">
                    <!-- Main content -->
                </div>
            </div> --}}
        <!-- Sidebar -->

        <aside
            class="transform top-0 left-0 w-64 bg-white dark:bg-slate-800 fixed h-full transition-transform duration-300 ease-in-out z-30 border-r border-slate-200 dark:border-slate-700 flex flex-col "
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0 lg:static'">
            <!-- Logo -->
            <div class="h-16 flex items-center justify-center border-b border-slate-100 dark:border-slate-700">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <x-clinic-logo class="w-8 h-8 text-primary-600" />
                    <span
                        class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-primary-600 to-primary-400">ClinicBook</span>
                </a>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="home">
                    {{ __('Dashboard') }}
                </x-nav-link>
                <x-nav-link :href="route('messages')" :active="request()->routeIs('messages*')" icon="chat" alpineBadge="unreadMessagesCount"
                    x-data="messageManager()" x-init="init()">
                    {{ __('Messages') }}
                </x-nav-link>
                @if (auth()->user()->hasRole('doctor'))
                <x-nav-link :href="route('doctor.dashboard')" :active="request()->routeIs('doctor.dashboard')" icon="home">
                    {{ __('Doctor Dashboard') }}
                </x-nav-link>
                <x-nav-link :href="route('doctor.profile.edit')" :active="request()->routeIs('doctor.profile.edit')" icon="user">
                    {{ __('My Profile') }}
                </x-nav-link>
                <x-nav-link href="{{ route('schedule.index') }}" icon="calendar">
                    {{ __('My Schedule') }}
                </x-nav-link>
                <x-nav-link :href="route('doctor.patients.index')" :active="request()->routeIs('doctor.patients.index')" icon="users">
                    {{ __('My Patients') }}
                </x-nav-link>
                {{-- <x-nav-link :href="route('prescriptions.create')" :active="request()->routeIs('prescriptions.create')" icon="document-text">
                            {{ __('Issue Prescription') }}
                </x-nav-link> --}}
                @endif

                @if (auth()->user()->hasRole('patient'))
                <x-nav-link :href="route('patient.dashboard')" :active="request()->routeIs('patient.dashboard')" icon="home">
                    {{ __('Patient Dashboard') }}
                </x-nav-link>
                <x-nav-link :href="route('schedule.index')" :active="request()->routeIs('schedule.index')" icon="calendar">
                    {{ __('Appointments') }}
                </x-nav-link>
                <x-nav-link :href="route('locations.search')" :active="request()->routeIs('locations.search')" icon="map">
                    {{ __('Find Clinics') }}
                </x-nav-link>
                <x-nav-link :href="route('medical-history.index')" :active="request()->routeIs('medical-history.index')" icon="clipboard-list">
                    {{ __('Medical Records') }}
                </x-nav-link>
                @endif

                @if (auth()->user()->isAdmin())
                <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" icon="home">
                    {{ __('Admin Dashboard') }}
                </x-nav-link>
                <x-nav-link :href="route('admin.doctors.index')" :active="request()->routeIs('admin.doctors.*')" icon="users">
                    {{ __('Manage Doctors') }}
                </x-nav-link>
                <x-nav-link :href="route('locations.index')" :active="request()->routeIs('locations.index')" icon="building-office">
                    {{ __('Locations') }}
                </x-nav-link>
                <x-nav-link :href="route('specialties.index')" :active="request()->routeIs('specialties.index')" icon="academic-cap">
                    {{ __('Specialties') }}
                </x-nav-link>
                @endif

                @if (auth()->user()->hasRole('receptionist'))
                <x-nav-link :href="route('receptionist.dashboard')" :active="request()->routeIs('receptionist.dashboard')" icon="home">
                    {{ __('Receptionist Dashboard') }}
                </x-nav-link>
                <x-nav-link href="#" icon="calendar">
                    {{ __('Appointments') }}
                </x-nav-link>
                <x-nav-link href="#" icon="users">
                    {{ __('Patients') }}
                </x-nav-link>
                @endif

                @if (auth()->user()->hasRole('pharmacist'))
                <x-nav-link :href="route('pharmacist.dashboard')" :active="request()->routeIs('pharmacist.dashboard')" icon="home">
                    {{ __('Pharmacist Dashboard') }}
                </x-nav-link>
                <x-nav-link href="#" icon="archive-box">
                    {{ __('Prescriptions') }}
                </x-nav-link>
                @endif

                @if (auth()->user()->hasRole('lab_technician'))
                <x-nav-link :href="route('lab.dashboard')" :active="request()->routeIs('lab.dashboard')" icon="home">
                    {{ __('Lab Dashboard') }}
                </x-nav-link>
                <x-nav-link href="#" icon="beaker">
                    {{ __('Lab Results') }}
                </x-nav-link>
                @endif
            </nav>

            <!-- User Menu (Mobile Sidebar Footer) -->
            <div class="p-4 border-t border-slate-100 dark:border-slate-700">
                <div class="flex items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->full_name) }}&background=0ea5e9&color=fff"
                        alt="Avatar" class="w-10 h-10 rounded-full">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate">{{ auth()->user()->full_name }}</p>
                        <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            @yield('content')
            <!-- Top Navbar -->
            <header
                class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-700 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 z-20 sticky top-0">
                <button @click="sidebarOpen = !sidebarOpen"
                    class="lg:hidden p-2 rounded-md text-slate-400 hover:text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700 focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div class="flex-1 flex justify-end items-center gap-4">
                    <!-- Dark Mode Toggle -->
                    <button @click="toggleDarkMode()"
                        class="p-2 rounded-full text-slate-400 hover:text-primary-500 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                        <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                            </path>
                        </svg>
                        <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </button>

                    <!-- Notifications Dropdown -->
                    <div class="relative" x-data="notificationsDropdown({{ auth()->id() }})" @notify.window="handleNewNotification($event.detail)"
                        @click.away="open = false">
                        <button @click="toggle"
                            class="relative p-2 rounded-full text-slate-400 hover:text-primary-500 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors focus:outline-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                </path>
                            </svg>
                            <!-- Unread Badge -->
                            <span x-show="unreadCount > 0" x-cloak
                                class="absolute top-1.5 right-1.5 block h-2 w-2 rounded-full ring-2 ring-white dark:ring-slate-800 bg-red-400"></span>
                        </button>

                        <div x-show="open" x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-80 rounded-lg shadow-lg bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 focus:outline-none z-50 overflow-hidden"
                            style="display: none;">

                            <div
                                class="px-4 py-3 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
                                <h3 class="text-sm font-semibold text-slate-800 dark:text-white">Notifications</h3>
                                <button @click="markAllAsRead" x-show="unreadCount > 0"
                                    class="text-xs text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">Mark
                                    all read</button>
                            </div>

                            <div class="max-h-80 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-700">
                                <template x-for="notification in notifications" :key="notification.id">
                                    <div @click="markAsRead(notification.id, notification.action_url)"
                                        class="block px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors cursor-pointer"
                                        :class="{ 'bg-blue-50/50 dark:bg-blue-900/10': !notification.read_at }">
                                        <p class="text-sm text-slate-800 dark:text-slate-200"
                                            x-text="notification.message.en || 'You have a new notification.'"></p>
                                        <p class="text-xs text-slate-500 mt-1"
                                            x-text="new Date(notification.created_at).toLocaleString()"></p>
                                    </div>
                                </template>

                                <div x-show="notifications.length === 0"
                                    class="px-4 py-6 text-center text-sm text-slate-500 dark:text-slate-400">
                                    No new notifications
                                </div>
                            </div>

                            <a :href="rolePrefix ? '/' + rolePrefix.split('.')[0] + '/notifications' : '#'"
                                class="block bg-slate-50 dark:bg-slate-700/50 px-4 py-3 text-center text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-700 transition-colors border-t border-slate-100 dark:border-slate-700">
                                View all notifications
                            </a>
                        </div>
                    </div>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center gap-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:text-slate-900 focus:outline-none transition-colors">
                            <span>{{ auth()->user()->first_name }}</span>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 ring-1 ring-black ring-opacity-5 focus:outline-none"
                            style="display: none;">
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700">Your
                                Profile</a>
                            <a href="#"
                                class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700">Settings</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">Sign
                                    out</button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto bg-slate-50 dark:bg-slate-900 p-4 sm:p-6 lg:p-8">
                @if (isset($header))
                <header class="mb-6">
                    {{ $header }}
                </header>
                @endif
                {{ $slot }}
                {{-- @yield('content') --}}
            </main>
        </div>
    </div>
    @else
    <div class="min-h-screen flex flex-col justify-center items-center bg-slate-50 dark:bg-slate-900 p-4">
        <div
            class="w-full sm:max-w-md px-6 py-8 bg-white dark:bg-slate-800 shadow-xl overflow-hidden sm:rounded-2xl border border-slate-100 dark:border-slate-700">
            <div class="mb-8 text-center">
                <div class="flex justify-center mb-4">
                    <x-clinic-logo class="w-14 h-14 text-primary-600" />
                </div>
                <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Welcome Back</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Please sign in to access your account</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Email
                        Address</label>
                    <input id="email"
                        class="block w-full mt-1.5 rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-900/50 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors shadow-sm"
                        type="email" name="email" required autofocus placeholder="you@example.com" />
                    @error('email')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password"
                        class="block text-sm font-medium text-slate-700 dark:text-slate-300">Password</label>
                    <input id="password"
                        class="block w-full mt-1.5 rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-900/50 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors shadow-sm"
                        type="password" name="password" required autocomplete="current-password"
                        placeholder="••••••••" />
                    @error('password')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer">
                        <input id="remember_me" type="checkbox"
                            class="rounded border-slate-300 text-primary-600 shadow-sm focus:ring-primary-500 dark:border-slate-600 dark:bg-slate-800"
                            name="remember">
                        <span class="ml-2 text-sm text-slate-600 dark:text-slate-400">Remember me</span>
                    </label>

                    @if (Route::has('password.request'))
                    <a class="text-sm text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 font-medium"
                        href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                    @endif
                </div>

                <button type="submit"
                    class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200 transform hover:scale-[1.02]">
                    Sign in
                </button>

                <div class="relative mt-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-200 dark:border-slate-700"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white dark:bg-slate-800 text-slate-500">Don't have an account?</span>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('register') }}"
                        class="w-full flex items-center justify-center px-4 py-2 border border-slate-300 dark:border-slate-600 shadow-sm text-sm font-medium rounded-lg text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                        Create new account
                    </a>
                </div>
            </form>
        </div>
    </div>
    @endauth

    <!-- Global Toast Container -->
    <div x-data="toastManager()" x-init="initEcho({{ auth()->id() }})" @notify.window="addToast($event.detail)"
        class="fixed bottom-4 right-4 z-[100] flex flex-col gap-2 pointer-events-none">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="toast.show" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="transform opacity-0 translate-y-4"
                x-transition:enter-end="transform opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="transform opacity-100"
                x-transition:leave-end="transform opacity-0 -translate-y-2 pointer-events-none"
                class="pointer-events-auto w-80 max-w-full bg-white dark:bg-slate-800 shadow-xl rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden flex">
                <div class="flex items-center justify-center w-12 bg-primary-500">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="p-3">
                    <p class="text-sm font-medium text-slate-800 dark:text-slate-200" x-text="toast.message"></p>
                </div>
                <button @click="removeToast(toast.id)"
                    class="ml-auto p-2 text-slate-400 hover:text-slate-500 focus:outline-none">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </template>
    </div>

    @auth
    <!-- Notifications Alpine & Echo Scripts -->
    <script>
        // Alpine configuration for UI logic
        document.addEventListener('alpine:init', () => {
            // Dropdown Component
            Alpine.data('notificationsDropdown', (userId) => ({
                open: false,
                unreadCount: 0,
                notifications: [],
                rolePrefix: '{{ auth()->user() && auth()->user()->roles->count() > 0 ? auth()->user()->roles[0]->name : '
                ' }}', // Basic hack, cleaner rolePrefix should be assigned properly via dashboard prefix

                init() {
                    // Fetch initial data
                    this.fetchLatest();

                    // Assign generic role prefix mapping here since Blade variables act funny
                    const prefixMap = {
                        'doctor': 'doctor',
                        'patient': 'patient',
                        'clinic_admin': 'admin',
                        'super_admin': 'admin'
                    };
                    if (prefixMap[this.rolePrefix]) {
                        this.rolePrefix = prefixMap[this.rolePrefix];
                    }
                },

                toggle() {
                    this.open = !this.open;
                    if (this.open && this.unreadCount > 0) {
                        // Mark as read when opening (Optional UX choice)
                        // this.markAllAsRead();
                    }
                },

                fetchLatest() {
                    fetch('/api/notifications/latest', {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            // Defensive checks to prevent JS crashes
                            this.unreadCount = data.unread_count || 0;
                            this.notifications = Array.isArray(data.notifications) ? data
                                .notifications : [];
                        })
                        .catch(error => {
                            console.error('Failed to fetch notifications:', error);
                            // Maintain empty state instead of crashing
                            this.notifications = [];
                        });
                },

                handleNewNotification(payload) {
                    this.unreadCount++;
                    this.fetchLatest(); // Refetch to show it at the top
                },

                markAsRead(id, url) {
                    fetch('/api/notifications/read/' + id, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        }
                    }).then(() => {
                        this.fetchLatest();
                        if (url) window.location.href = url;
                    });
                },

                markAllAsRead() {
                    fetch('/api/notifications/read', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        }
                    }).then(() => {
                        this.unreadCount = 0;
                        this.fetchLatest();
                    });
                }
            }));

            // Toast Component
            Alpine.data('toastManager', () => ({
                toasts: [],
                initEcho(userId) {
                    window.addEventListener('load', () => {
                        if (userId && window.Echo) {
                            // General Notifications
                            window.Echo.private("App.Models.User." + userId).listen(
                                "AppointmentNotification",
                                (e) => {
                                    console.log("Global Notification received:", e.message);
                                    window.dispatchEvent(new CustomEvent('notify', {
                                        detail: e
                                    }));
                                }
                            );

                            // Message Notifications
                            window.Echo.private("App.Models.User." + userId).listen(
                                ".MessageSent",
                                (e) => {
                                    console.log("New Message Notification received:", e);
                                    // Show toast
                                    window.dispatchEvent(new CustomEvent('notify', {
                                        detail: {
                                            message: `New message from ${e.senderName}: ${e.message.substring(0, 50)}...`
                                        }
                                    }));
                                    // Signal refresh to message count
                                    window.dispatchEvent(new CustomEvent('new-message', {
                                        detail: e
                                    }));
                                }
                            );
                        } else {
                            console.warn('Echo not initialized for global notifications');
                        }
                    });
                },
                addToast(payload) {
                    const id = Date.now();
                    const message = payload.message || payload; // Depending on event structure
                    this.toasts.push({
                        id,
                        message,
                        show: true
                    });

                    // Auto remove after 5s
                    setTimeout(() => {
                        this.removeToast(id);
                    }, 5000);
                },
                removeToast(id) {
                    const toast = this.toasts.find(t => t.id === id);
                    if (toast) {
                        toast.show = false;
                        setTimeout(() => {
                            this.toasts = this.toasts.filter(t => t.id !== id);
                        }, 300); // Wait for animation
                    }
                }
            }));

            // Message Manager (Unread Count)
            Alpine.data('messageManager', () => ({
                unreadMessagesCount: {{ auth()->user()->unreadMessagesCount() }},
                init() {
                    window.addEventListener('new-message', () => {
                        this.unreadMessagesCount++;
                    });
                }
            }));
        });
    </script>
    @endauth
    @stack('scripts')
</body>

</html>