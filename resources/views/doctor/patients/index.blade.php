<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Patients') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Search & Actions -->
                    <div class="flex justify-between items-center mb-6">
                        <form method="GET" action="{{ route('doctor.patients.index') }}"
                            class="flex gap-2 w-full max-w-md">
                            <x-text-input type="text" name="search" placeholder="Search by name or email..."
                                class="w-full" :value="$search" />
                            <x-primary-button>Search</x-primary-button>
                        </form>
                        <!-- Maybe add 'Add New Patient' if creating consultations for new patients is allowed? -->
                    </div>

                    <!-- Patients Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Patient</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Contact</th>
                                    {{-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Visit</th> --}}
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($patients as $patient)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full"
                                                        src="https://ui-avatars.com/api/?name={{ urlencode($patient->user->full_name) }}&background=random"
                                                        alt="">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $patient->user->full_name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $patient->age }} years •
                                                        {{ ucfirst($patient->gender) }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $patient->user->email }}</div>
                                            <div class="text-sm text-gray-500">{{ $patient->user->phone ?? 'N/A' }}
                                            </div>
                                        </td>
                                        {{-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $patient->appointments->where('status', 'completed')->last()?->appointment_date->format('M d, Y') ?? 'Never' }}
                                        </td> --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('doctor.patients.show', $patient->id) }}"
                                                class="text-primary-600 hover:text-primary-900 font-bold">View
                                                Details</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-gray-500 italic">
                                            No patients found. Patients will appear here once they book an appointment
                                            with you.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $patients->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
