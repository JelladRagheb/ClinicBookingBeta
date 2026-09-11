<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Patient Details') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ activeTab: 'overview' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Patient Header Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 flex flex-col md:flex-row items-center gap-6">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($patient->user->full_name) }}&background=random&size=128"
                        alt="" class="w-24 h-24 rounded-full">
                    <div class="flex-1 text-center md:text-left">
                        <h3 class="text-2xl font-bold">{{ $patient->user->full_name }}</h3>
                        <div class="text-sm text-gray-500 mt-1 space-y-1">
                            <p>DOB: {{ $patient->date_of_birth ? $patient->date_of_birth->format('M d, Y') : 'N/A' }}
                                ({{ $patient->date_of_birth ? $patient->date_of_birth->age . ' years' : 'N/A' }})</p>
                            <p>Gender: {{ ucfirst($patient->gender) }}</p>
                            <p>Phone: {{ $patient->user->phone ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        {{-- <a href="{{ route('consultations.create', ['patient_id' => $patient->id]) }}" class="bg-primary-600 text-white px-4 py-2 rounded hover:bg-primary-700">
                            Start Consultation
                        </a> --}}
                        <!-- Usually consultation is linked to an appointment, but maybe walk-in or ad-hoc? -->
                    </div>
                </div>
            </div>

            <!-- Tabs Navigation -->
            <div class="border-b border-gray-200 mb-6">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button @click="activeTab = 'overview'"
                        :class="{ 'border-primary-500 text-primary-600': activeTab === 'overview', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'overview' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Overview
                    </button>
                    <button @click="activeTab = 'history'"
                        :class="{ 'border-primary-500 text-primary-600': activeTab === 'history', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'history' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Medical History
                    </button>
                    <button @click="activeTab = 'consultations'"
                        :class="{ 'border-primary-500 text-primary-600': activeTab === 'consultations', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'consultations' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Consultations
                    </button>
                    <button @click="activeTab = 'prescriptions'"
                        :class="{ 'border-primary-500 text-primary-600': activeTab === 'prescriptions', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'prescriptions' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Prescriptions
                    </button>
                </nav>
            </div>

            <!-- Tab Contents -->
            <div>
                <!-- Overview Tab -->
                <div x-show="activeTab === 'overview'" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Vitals Summary (Placeholder) -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <h4 class="text-lg font-bold mb-4">Recent Vitals</h4>
                            <p class="text-gray-500 italic">No recent vitals recorded.</p>
                        </div>

                        <!-- Active Conditions -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <h4 class="text-lg font-bold mb-4">Active Conditions</h4>
                            @forelse($patient->medicalHistories->where('category', 'chronic_condition')->where('is_active', true) as $history)
                                <div class="mb-2 p-2 bg-red-50 text-red-700 rounded border border-red-200">
                                    <strong>{{ $history->title }}</strong>
                                    <span class="text-sm block">{{ $history->description }}</span>
                                </div>
                            @empty
                                <p class="text-gray-500 italic">No active conditions.</p>
                            @endforelse
                        </div>

                        <!-- Allergies -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <h4 class="text-lg font-bold mb-4">Allergies</h4>
                            @forelse($patient->medicalHistories->where('category', 'allergy')->where('is_active', true) as $history)
                                <div class="mb-2 p-2 bg-orange-50 text-orange-700 rounded border border-orange-200">
                                    <strong>{{ $history->title }}</strong>
                                    <span class="text-sm block">{{ $history->description }}</span>
                                </div>
                            @empty
                                <p class="text-gray-500 italic">No known allergies.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Medical History Tab -->
                <div x-show="activeTab === 'history'" x-cloak>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h4 class="text-lg font-bold">Medical History</h4>
                            <!-- Add Button Trigger Modal -->
                            <button @click="$dispatch('open-modal', 'add-medical-history')"
                                class="bg-gray-800 text-white px-4 py-2 rounded text-sm hover:bg-gray-700">
                                + Add Record
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Category</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Title/Description</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($patient->medicalHistories as $history)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $history->recorded_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $history->category === 'allergy'
                                                        ? 'bg-orange-100 text-orange-800'
                                                        : ($history->category === 'chronic_condition'
                                                            ? 'bg-red-100 text-red-800'
                                                            : 'bg-gray-100 text-gray-800') }}">
                                                    {{ ucfirst(str_replace('_', ' ', $history->category)) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                <div class="font-bold text-gray-900">{{ $history->title }}</div>
                                                <div class="text-xs">{{ Str::limit($history->description, 50) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <form action="{{ route('medical-history.destroy', $history->id) }}"
                                                    method="POST" onsubmit="return confirm('Are you sure?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">No history
                                                recorded.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Consultations Tab -->
                <div x-show="activeTab === 'consultations'" x-cloak>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h4 class="text-lg font-bold mb-4">Past Consultations</h4>
                        <ul class="space-y-4">
                            @forelse($patient->consultations as $consultation)
                                <li class="border border-gray-200 rounded p-4 hover:bg-gray-50 transition">
                                    <div class="flex justify-between">
                                        <div>
                                            <span
                                                class="text-sm text-gray-500">{{ $consultation->created_at->format('M d, Y H:i') }}</span>
                                            <h5 class="font-bold text-lg text-primary-600">
                                                <a href="{{ route('consultations.show', $consultation->id) }}">
                                                    {{ $consultation->diagnosis }}
                                                </a>
                                            </h5>
                                            <p class="text-sm text-gray-600">Dr.
                                                {{ $consultation->doctor->user->full_name }}</p>
                                        </div>
                                        <div class="text-right">
                                            <a href="{{ route('consultations.show', $consultation->id) }}"
                                                class="text-primary-600 hover:underline text-sm">View Details</a>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <p class="text-gray-500 italic">No previous consultations.</p>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <!-- Prescriptions Tab -->
                <div x-show="activeTab === 'prescriptions'" x-cloak>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h4 class="text-lg font-bold">Prescriptions</h4>
                            <a href="{{ route('prescriptions.create', ['patient_id' => $patient->id]) }}"
                                class="bg-gray-800 text-white px-4 py-2 rounded text-sm hover:bg-gray-700">
                                + New Prescription
                            </a>
                        </div>
                        <ul class="space-y-4">
                            @forelse($patient->prescriptions as $prescription)
                                <li class="border border-gray-200 rounded p-4 hover:bg-gray-50 transition">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <span
                                                class="text-sm text-gray-500">{{ $prescription->issued_date->format('M d, Y') }}</span>
                                            <h5 class="font-bold text-gray-900">
                                                #{{ $prescription->prescription_number }}</h5>
                                            <p class="text-sm text-gray-600">{{ $prescription->items_count }} Items
                                            </p>
                                        </div>
                                        <div>
                                            <span
                                                class="px-2 py-1 text-xs rounded-full {{ $prescription->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst($prescription->status) }}
                                            </span>
                                            <a href="{{ route('prescriptions.show', $prescription->id) }}"
                                                class="ml-4 text-primary-600 hover:underline text-sm">View</a>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <p class="text-gray-500 italic">No prescriptions found.</p>
                            @endforelse
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal for Adding Medical History -->
    {{-- <x-modal name="add-medical-history" :show="$errors->hasBag('default')" focusable>
        <form method="POST" action="{{ route('medical-history.store') }}" class="p-6">
            @csrf
            <input type="hidden" name="patient_profile_id" value="{{ $patient->id }}">

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Add Medical History Record') }}
            </h2>

            <div class="mt-6">
                <!-- Category -->
                <div class="mb-4">
                    <x-input-label for="category" value="{{ __('Category') }}" />
                    <select id="category" name="category"
                        class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50">
                        <option value="allergy">Allergy</option>
                        <option value="chronic_condition">Chronic Condition</option>
                        <option value="surgery">Surgery</option>
                        <option value="family_history">Family History</option>
                        <option value="other">Other</option>
                    </select>
                    <x-input-error :messages="$errors->get('category')" class="mt-2" />
                </div>

                <!-- Title -->
                <div class="mb-4">
                    <x-input-label for="title" value="{{ __('Title (e.g. Penicillin, Diabetes)') }}" />
                    <x-text-input id="title" class="block mt-1 w-full" type="text" name="title"
                        :value="old('title')" required />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <x-input-label for="description" value="{{ __('Description/Notes') }}" />
                    <textarea id="description" name="description" rows="3"
                        class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50"></textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <!-- Is Active -->
                <div class="block mt-4">
                    <label for="is_active" class="inline-flex items-center">
                        <input id="is_active" type="checkbox"
                            class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500"
                            name="is_active" value="1" checked>
                        <span class="ml-2 text-sm text-gray-600">{{ __('Is Active/Current?') }}</span>
                    </label>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-primary-button class="ml-3">
                    {{ __('Save Record') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal> --}}

</x-app-layout>
