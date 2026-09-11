<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Prescription Details') }} #{{ $prescription->prescription_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Patient</h3>
                            <p class="text-lg font-bold">{{ $prescription->patient->user->full_name }}</p>
                            <p class="text-sm text-gray-600">Email: {{ $prescription->patient->user->email }}</p>
                        </div>
                        <div class="text-right">
                            <h3 class="text-sm font-medium text-gray-500">Doctor</h3>
                            <p class="text-lg font-bold">Dr. {{ $prescription->doctor->user->full_name }}</p>
                            <p class="text-sm text-gray-600">Date: {{ $prescription->issued_date->format('d M Y') }}</p>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 py-6">
                        <h3 class="text-lg font-bold mb-4">Medications</h3>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Name</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Dosage</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Frequency</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($prescription->items as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->medication_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->dosage }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->frequency }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($prescription->notes)
                        <div class="bg-gray-50 p-4 rounded mt-6">
                            <h4 class="text-sm font-bold text-gray-700 mb-2">Notes/Instructions:</h4>
                            <p class="text-gray-600">{{ $prescription->notes }}</p>
                        </div>
                    @endif

                    <div class="mt-8 text-center no-print">
                        <button onclick="window.print()"
                            class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700">
                            Print Prescription
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
