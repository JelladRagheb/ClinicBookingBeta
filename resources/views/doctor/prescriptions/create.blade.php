<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Prescription') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('prescriptions.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <x-input-label for="patient_profile_id" :value="__('Select Patient')" />
                            <select id="patient_profile_id" name="patient_profile_id"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                                @foreach ($patients as $p)
                                    <option value="{{ $p->id }}"
                                        {{ isset($patient) && $patient->id == $p->id ? 'selected' : '' }}>
                                        {{ $p->user->name }} ({{ $p->user->email }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('patient_profile_id')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Medications</h3>
                            <div id="medication-list">
                                <!-- Initial Item -->
                                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4 medication-item border-b pb-4">
                                    <div class="col-span-2">
                                        <x-input-label :value="__('Medication Name')" />
                                        <x-text-input name="items[0][medication_name]" class="block mt-1 w-full"
                                            type="text" required placeholder="e.g. Paracetamol" />
                                    </div>
                                    <div>
                                        <x-input-label :value="__('Dosage')" />
                                        <x-text-input name="items[0][dosage]" class="block mt-1 w-full" type="text"
                                            required placeholder="500mg" />
                                    </div>
                                    <div>
                                        <x-input-label :value="__('Frequency')" />
                                        <x-text-input name="items[0][frequency]" class="block mt-1 w-full"
                                            type="text" required placeholder="3x Daily" />
                                    </div>
                                    <div class="flex items-end">
                                        <button type="button" class="text-red-500 hover:text-red-700 remove-item"
                                            style="display:none;">Remove</button>
                                    </div>
                                </div>
                            </div>

                            <button type="button" id="add-medication"
                                class="text-indigo-600 hover:text-indigo-900 font-medium">
                                + Add Another Parameter
                            </button>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="notes" :value="__('Notes/Instructions')" />
                            <textarea id="notes" name="notes"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full"
                                rows="3"></textarea>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Issue Prescription') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let itemIndex = 1;
            const container = document.getElementById('medication-list');
            const addButton = document.getElementById('add-medication');

            addButton.addEventListener('click', function() {
                const template = container.querySelector('.medication-item').cloneNode(true);

                // Clear inputs
                template.querySelectorAll('input').forEach(input => {
                    input.value = '';
                    input.name = input.name.replace(/\[\d+\]/, `[${itemIndex}]`);
                });

                // Show remove button
                template.querySelector('.remove-item').style.display = 'block';

                // Add remove listener
                template.querySelector('.remove-item').addEventListener('click', function() {
                    template.remove();
                });

                container.appendChild(template);
                itemIndex++;
            });
        });
    </script>
</x-app-layout>
