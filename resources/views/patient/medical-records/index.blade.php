<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Medical Records') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ activeTab: 'history' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Tabs Navigation -->
            <div class="border-b border-gray-200 mb-6 bg-white rounded-t-lg px-4">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button @click="activeTab = 'history'"
                        :class="{ 'border-primary-500 text-primary-600': activeTab === 'history', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'history' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Medical History
                    </button>
                    <button @click="activeTab = 'consultations'"
                        :class="{ 'border-primary-500 text-primary-600': activeTab === 'consultations', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'consultations' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Consultation Reports
                    </button>
                    <button @click="activeTab = 'prescriptions'"
                        :class="{ 'border-primary-500 text-primary-600': activeTab === 'prescriptions', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'prescriptions' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Prescriptions
                    </button>
                </nav>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-b-lg p-6">

                <!-- Medical History Tab -->
                <div x-show="activeTab === 'history'">
                    <h3 class="text-lg font-bold mb-4">Medical History</h3>
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
                                        Details</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
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
                                            <div class="text-xs">{{ $history->description }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($history->is_active)
                                                <span class="text-green-600 text-xs font-bold">Active</span>
                                            @else
                                                <span class="text-gray-400 text-xs">Inactive</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No medical
                                            history on record.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Consultations Tab -->
                <div x-show="activeTab === 'consultations'" x-cloak>
                    <h3 class="text-lg font-bold mb-4">Consultation Reports</h3>
                    <div class="space-y-4">
                        @forelse($patient->consultations as $consultation)
                            <div class="border border-gray-200 rounded p-4 hover:bg-gray-50 transition">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-sm text-gray-500 mb-1">
                                            {{ $consultation->created_at->format('F d, Y') }}</p>
                                        <h4 class="font-bold text-gray-900 text-lg">{{ $consultation->diagnosis }}</h4>
                                        <p class="text-sm text-gray-600 mt-1">Dr.
                                            {{ $consultation->doctor->user->full_name }}</p>

                                        @if ($consultation->treatment_plan)
                                            <div class="mt-2 text-sm text-gray-700 bg-gray-50 p-2 rounded">
                                                <strong>Plan:</strong> {{ $consultation->treatment_plan }}
                                            </div>
                                        @endif
                                    </div>
                                    <a href="{{ route('consultations.show', $consultation->id) }}"
                                        class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                                        View Full Report
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 italic">No consultation reports available.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Prescriptions Tab -->
                <div x-show="activeTab === 'prescriptions'" x-cloak>
                    <h3 class="text-lg font-bold mb-4">Prescriptions</h3>
                    <div class="grid gap-4 grid-cols-1 md:grid-cols-2">
                        @forelse($patient->prescriptions as $prescription)
                            <div class="border border-gray-200 rounded p-4 hover:shadow-md transition bg-white">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-xs font-bold bg-gray-100 text-gray-600 px-2 py-1 rounded">
                                        #{{ $prescription->prescription_number }}
                                    </span>
                                    <span
                                        class="text-xs text-gray-500">{{ $prescription->issued_date->format('M d, Y') }}</span>
                                </div>
                                <div class="mb-3">
                                    <p class="text-sm text-gray-600">Dr. {{ $prescription->doctor->user->full_name }}
                                    </p>
                                    <div class="mt-2 text-sm">
                                        <strong>Meds:</strong>
                                        {{ $prescription->items->pluck('medication_name')->join(', ') }}
                                    </div>
                                </div>
                                <a href="{{ route('prescriptions.show', $prescription->id) }}"
                                    class="block text-center bg-primary-50 text-primary-700 py-2 rounded text-sm hover:bg-primary-100">
                                    View Prescription
                                </a>
                            </div>
                        @empty
                            <div class="col-span-2 text-center py-8 text-gray-500 italic">
                                No prescriptions found.
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
