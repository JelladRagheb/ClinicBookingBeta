<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('New Consultation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex gap-6">

                <!-- Main Form -->
                <div class="flex-1">
                    <form method="POST" action="{{ route('consultations.store') }}">
                        @csrf
                        <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                        <input type="hidden" name="patient_profile_id" value="{{ $patient->id }}">

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                            <h3 class="text-lg font-bold mb-4">Clinical Notes</h3>

                            <!-- Chief Complaint -->
                            <div class="mb-4">
                                <x-input-label for="chief_complaint" value="{{ __('Chief Complaint') }}" />
                                <x-text-input id="chief_complaint" class="block mt-1 w-full" type="text"
                                    name="chief_complaint" required autofocus
                                    placeholder="e.g. Severe headache, fever" />
                                <x-input-error :messages="$errors->get('chief_complaint')" class="mt-2" />
                            </div>

                            <!-- Diagnosis -->
                            <div class="mb-4">
                                <x-input-label for="diagnosis" value="{{ __('Diagnosis') }}" />
                                <textarea id="diagnosis" name="diagnosis" rows="2"
                                    class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50"
                                    required placeholder="Medical diagnosis"></textarea>
                                <x-input-error :messages="$errors->get('diagnosis')" class="mt-2" />
                            </div>

                            <!-- Treatment Plan -->
                            <div class="mb-4">
                                <x-input-label for="treatment_plan" value="{{ __('Treatment Plan') }}" />
                                <textarea id="treatment_plan" name="treatment_plan" rows="4"
                                    class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50"
                                    placeholder="Medications, lifestyle changes, etc."></textarea>
                                <x-input-error :messages="$errors->get('treatment_plan')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Vitals (Optional) -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                            <h3 class="text-lg font-bold mb-4">Vital Signs (Current)</h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div>
                                    <x-input-label for="temp" value="{{ __('Temp (°C)') }}" />
                                    <x-text-input id="temp" name="vital_signs[temperature_celsius]"
                                        class="block mt-1 w-full" type="number" step="0.1" />
                                </div>
                                <div>
                                    <x-input-label for="bp_sys" value="{{ __('BP (Sys)') }}" />
                                    <x-text-input id="bp_sys" name="vital_signs[blood_pressure_systolic]"
                                        class="block mt-1 w-full" type="number" placeholder="120" />
                                </div>
                                <div>
                                    <x-input-label for="bp_dia" value="{{ __('BP (Dia)') }}" />
                                    <x-text-input id="bp_dia" name="vital_signs[blood_pressure_diastolic]"
                                        class="block mt-1 w-full" type="number" placeholder="80" />
                                </div>
                                <div>
                                    <x-input-label for="hr" value="{{ __('Heart Rate') }}" />
                                    <x-text-input id="hr" name="vital_signs[heart_rate_bpm]"
                                        class="block mt-1 w-full" type="number" />
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end gap-4">
                            <a href="{{ route('doctor.dashboard') }}"
                                class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">Cancel</a>
                            <x-primary-button>
                                {{ __('Finish Consultation') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>

                <!-- Sidebar: Patient Context -->
                <div class="w-1/3 space-y-6 hidden lg:block">
                    <!-- Patient Info -->
                    <div class="bg-gray-50 overflow-hidden shadow-sm sm:rounded-lg p-4">
                        <h4 class="font-bold text-gray-700 mb-2">Patient Info</h4>
                        <div class="flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($patient->user->full_name) }}&size=64"
                                class="w-12 h-12 rounded-full">
                            <div>
                                <p class="font-bold">{{ $patient->user->full_name }}</p>
                                <p class="text-xs text-gray-500">{{ $patient->age }} yo •
                                    {{ ucfirst($patient->gender) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Recent History -->
                    <div class="bg-gray-50 overflow-hidden shadow-sm sm:rounded-lg p-4">
                        <h4 class="font-bold text-gray-700 mb-2">Active Conditions</h4>
                        <ul class="text-sm space-y-1">
                            @forelse($patient->medicalHistories->where('is_active', true) as $history)
                                <li class="text-red-600">• {{ $history->title }}</li>
                            @empty
                                <li class="text-gray-500 italic">None</li>
                            @endforelse
                        </ul>
                    </div>

                    <!-- Past Consultations -->
                    <div class="bg-gray-50 overflow-hidden shadow-sm sm:rounded-lg p-4">
                        <h4 class="font-bold text-gray-700 mb-2">Last Visit</h4>
                        @if ($pastConsultations->first())
                            <div class="text-sm">
                                <p class="font-medium">{{ $pastConsultations->first()->created_at->format('M d, Y') }}
                                </p>
                                <p class="text-gray-600">{{ $pastConsultations->first()->diagnosis }}</p>
                            </div>
                        @else
                            <p class="text-sm text-gray-500 italic">No previous visits.</p>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
