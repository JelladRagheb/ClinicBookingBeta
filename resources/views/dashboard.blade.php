<x-app-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-slate-800 dark:text-white leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <div class="flex items-center gap-2">
                <span class="text-sm text-slate-500 dark:text-slate-400">
                    {{ now()->format('l, F j, Y') }}
                </span>
            </div>
        </div>

        @if (auth()->user()->isPatient())
            <!-- Patient Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Quick Stats -->
                <div
                    class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-primary-50 dark:bg-primary-900/20 rounded-xl">
                            <svg class="w-8 h-8 text-primary-600 dark:text-primary-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Upcoming Appointments</p>
                            <h3 class="text-2xl font-bold text-slate-800 dark:text-white">0</h3>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl">
                            <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Medical Records</p>
                            <h3 class="text-2xl font-bold text-slate-800 dark:text-white">0</h3>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-primary-600 to-primary-500 p-6 rounded-2xl shadow-lg shadow-primary-500/20 text-white">
                    <h3 class="text-lg font-bold mb-2">Book Appointment</h3>
                    <p class="text-primary-100 text-sm mb-4">Find a doctor and schedule your visit.</p>
                    <button
                        class="w-full py-2 bg-white text-primary-600 font-bold rounded-lg hover:bg-primary-50 transition-colors">
                        Book Now
                    </button>
                </div>
            </div>

            <!-- Recent Appointments Table Placeholder -->
            <div
                class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
                <div class="p-6 border-b border-slate-100 dark:border-slate-700">
                    <h3 class="font-bold text-lg text-slate-800 dark:text-white">Recent Activity</h3>
                </div>
                <div class="p-6 text-center text-slate-500 dark:text-slate-400 py-12">
                    No appointments found.
                </div>
            </div>
        @elseif(auth()->user()->isDoctor())
            <!-- Doctor Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Doctor Stats -->
                <div
                    class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Today's Visits</p>
                            <h3 class="text-2xl font-bold text-slate-800 dark:text-white">0</h3>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Admin Dashboard -->
            <div
                class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                <p class="text-slate-600 dark:text-slate-300">Welcome, Admin! System statistics will appear here.</p>
            </div>
        @endif
    </div>
</x-app-layout>
