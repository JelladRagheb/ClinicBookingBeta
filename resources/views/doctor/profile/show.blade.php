<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $profile->user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Sidebar Info -->
                <div class="md:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6 text-gray-900 text-center">
                            <div
                                class="w-32 h-32 bg-gray-200 rounded-full mx-auto mb-4 flex items-center justify-center">
                                <span class="text-3xl text-gray-500">{{ substr($profile->user->name, 0, 1) }}</span>
                            </div>
                            <h3 class="text-xl font-bold">{{ $profile->user->name }}</h3>
                            <div class="mt-2">
                                @foreach ($profile->specialties as $specialty)
                                    <span
                                        class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mr-1">{{ $specialty->translated_name }}</span>
                                @endforeach
                            </div>
                            <div class="mt-4 text-left">
                                <p class="text-sm text-gray-600"><strong class="text-gray-900">Experience:</strong>
                                    {{ $profile->experience_years }} years</p>
                                <p class="text-sm text-gray-600 mt-1"><strong class="text-gray-900">Fee:</strong>
                                    ${{ number_format($profile->consultation_fee, 2) }}</p>
                                <p class="text-sm text-gray-600 mt-1"><strong class="text-gray-900">Walk-ins:</strong>
                                    {{ $profile->accepts_walk_ins ? 'Yes' : 'No' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="md:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-bold mb-3">About Dr. {{ $profile->user->name }}</h3>
                            <p class="text-gray-700 leading-relaxed">
                                {{ $profile->translated_bio ?: 'No biography available.' }}
                            </p>
                        </div>
                    </div>

                    <!-- Calendar/Booking -->
                    @auth
                        @if (auth()->user()->hasRole('patient'))
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                                <div class="p-6 text-gray-900">
                                    <h3 class="text-lg font-bold mb-3">Book an Appointment</h3>
                                    <div id="calendar" data-doctor-id="{{ $profile->id }}" class="h-[600px]"></div>
                                </div>
                            </div>
                            @push('scripts')
                                @vite(['resources/js/calendar-patient.js'])
                            @endpush
                        @endif
                    @else
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                            <div class="p-6 text-gray-900 text-center">
                                <p><a href="{{ route('login') }}" class="text-primary-600 hover:underline">Login</a> to
                                    book an appointment.</p>
                            </div>
                        </div>
                    @endauth
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
