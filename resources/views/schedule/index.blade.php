<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-200 leading-tight">
            {{ __('Calendar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-slate-900 dark:text-slate-100">
                    <div id="calendar" style="width: 100%; height: 85vh;"></div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        @if(auth()->check() && auth()->user()->hasRole('doctor'))
            @vite(['resources/js/calendar-doctor.js'])
        @elseif(auth()->check() && auth()->user()->hasRole('patient'))
            @vite(['resources/js/calendar-patient.js'])
        @endif
    @endpush
</x-app-layout>
