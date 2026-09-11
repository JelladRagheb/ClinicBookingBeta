<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Professional Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('doctor.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div class="col-span-1 md:col-span-2">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                            </div>

                            <div class="mb-4">
                                <x-input-label for="phone" :value="__('Phone Number')" />
                                <x-text-input id="phone" class="block mt-1 w-full" type="text"
                                    name="phone" :value="old('phone', auth()->user()->phone ?? '')" />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>

                            <div class="col-span-1 md:col-span-2 mt-4">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Professional Information</h3>
                            </div>

                            <div class="mb-4">
                                <x-input-label for="license_number" :value="__('License Number')" />
                                <x-text-input id="license_number" class="block mt-1 w-full" type="text"
                                    name="license_number" :value="old('license_number', $profile->license_number ?? '')" required />
                                <x-input-error :messages="$errors->get('license_number')" class="mt-2" />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="experience_years" :value="__('Experience (Years)')" />
                                <x-text-input id="experience_years" class="block mt-1 w-full" type="number"
                                    name="experience_years" :value="old('experience_years', $profile->experience_years ?? '')" min="0" />
                                <x-input-error :messages="$errors->get('experience_years')" class="mt-2" />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="consultation_fee" :value="__('Consultation Fee ($)')" />
                                <x-text-input id="consultation_fee" class="block mt-1 w-full" type="number"
                                    step="0.01" name="consultation_fee" :value="old('consultation_fee', $profile->consultation_fee ?? '')" min="0" />
                                <x-input-error :messages="$errors->get('consultation_fee')" class="mt-2" />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="accepts_walk_ins" :value="__('Accepts Walk-ins?')" />
                                <select id="accepts_walk_ins" name="accepts_walk_ins"
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                                    <option value="1"
                                        {{ old('accepts_walk_ins', $profile->accepts_walk_ins ?? 0) == 1 ? 'selected' : '' }}>
                                        Yes</option>
                                    <option value="0"
                                        {{ old('accepts_walk_ins', $profile->accepts_walk_ins ?? 0) == 0 ? 'selected' : '' }}>
                                        No</option>
                                </select>
                            </div>

                            <div class="col-span-1 md:col-span-2">
                                <x-input-label for="specialties" :value="__('Specialties')" />
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-2">
                                    @foreach ($specialties as $specialty)
                                        <div class="flex items-center">
                                            <input id="specialty_{{ $specialty->id }}" name="specialties[]"
                                                value="{{ $specialty->id }}" type="checkbox"
                                                class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500"
                                                {{ in_array($specialty->id, old('specialties', $profile->specialties->pluck('id')->toArray())) ? 'checked' : '' }}>
                                            <label for="specialty_{{ $specialty->id }}"
                                                class="ml-2 text-sm font-medium text-gray-900">{{ $specialty->translated_name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                                <x-input-error :messages="$errors->get('specialties')" class="mt-2" />
                            </div>

                            <div class="col-span-1 md:col-span-2">
                                <x-input-label for="bio" :value="__('Bio/Description')" />
                                <textarea id="bio" name="bio[en]"
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full"
                                    rows="4">{{ old('bio.en', $profile->bio['en'] ?? '') }}</textarea>
                                <x-input-error :messages="$errors->get('bio.en')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Save Changes') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
