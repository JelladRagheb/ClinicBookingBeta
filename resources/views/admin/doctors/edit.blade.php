<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-200 leading-tight">
            {{ __('Edit Doctor: ') . $doctor->full_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('admin.doctors.update', $doctor) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Basic Info -->
                            <div class="space-y-4 border-r border-slate-200 dark:border-slate-700 pr-0 md:pr-6">
                                <h3 class="text-lg font-medium text-slate-900 dark:text-white">Account Details</h3>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="first_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">First Name</label>
                                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $doctor->first_name) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-slate-900 dark:border-slate-600 dark:text-white" required>
                                        @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="last_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Last Name</label>
                                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $doctor->last_name) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-slate-900 dark:border-slate-600 dark:text-white" required>
                                        @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Email Address</label>
                                    <input type="email" name="email" id="email" value="{{ old('email', $doctor->email) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-slate-900 dark:border-slate-600 dark:text-white" required>
                                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="phone" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Phone</label>
                                    <input type="text" name="phone" id="phone" value="{{ old('phone', $doctor->phone) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-slate-900 dark:border-slate-600 dark:text-white" required>
                                    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div class="pt-4 mt-4 border-t border-slate-200 dark:border-slate-700">
                                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-2">Change Password (leave blank to keep current)</p>
                                    <div>
                                        <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">New Password</label>
                                        <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-slate-900 dark:border-slate-600 dark:text-white">
                                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="mt-3">
                                        <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Confirm Password</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-slate-900 dark:border-slate-600 dark:text-white">
                                    </div>
                                </div>
                            </div>

                            <!-- Professional Info -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium text-slate-900 dark:text-white">Professional Details</h3>

                                <div>
                                    <label for="license_number" class="block text-sm font-medium text-slate-700 dark:text-slate-300">License Number</label>
                                    <input type="text" name="license_number" id="license_number" value="{{ old('license_number', optional($doctor->doctorProfile)->license_number ?? '') }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-slate-900 dark:border-slate-600 dark:text-white" required>
                                    @error('license_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="experience_years" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Years Experience</label>
                                        <input type="number" name="experience_years" id="experience_years" value="{{ old('experience_years', optional($doctor->doctorProfile)->experience_years ?? 0) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-slate-900 dark:border-slate-600 dark:text-white" required>
                                        @error('experience_years') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="consultation_fee" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Consultation Fee</label>
                                        <input type="number" step="0.01" name="consultation_fee" id="consultation_fee" value="{{ old('consultation_fee', optional($doctor->doctorProfile)->consultation_fee ?? 0) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-slate-900 dark:border-slate-600 dark:text-white" required>
                                        @error('consultation_fee') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Specialties</label>
                                    <div class="space-y-2 max-h-40 overflow-y-auto p-2 border border-slate-200 dark:border-slate-700 rounded-md">
                                        @php
                                            $doctorSpecialties = $doctor->doctorProfile ? $doctor->doctorProfile->specialties->pluck('id')->toArray() : [];
                                        @endphp
                                        @foreach($specialties as $specialty)
                                            <div class="flex items-center">
                                                <input id="specialty_{{ $specialty->id }}" name="specialties[]" type="checkbox" value="{{ $specialty->id }}" class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-slate-300 rounded" {{ in_array($specialty->id, old('specialties', $doctorSpecialties)) ? 'checked' : '' }}>
                                                <label for="specialty_{{ $specialty->id }}" class="ml-2 block text-sm text-slate-900 dark:text-slate-300">
                                                    {{ collect($specialty->name)->first() }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('specialties') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="bio_en" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Biography (EN)</label>
                                    <textarea name="bio_en" id="bio_en" rows="3" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-slate-900 dark:border-slate-600 dark:text-white">{{ old('bio_en', optional($doctor->doctorProfile)->bio['en'] ?? '') }}</textarea>
                                    @error('bio_en') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div class="mt-4">
                                    <label for="is_active" class="flex items-center cursor-pointer">
                                        <input type="checkbox" name="is_active" id="is_active" value="1" class="rounded border-slate-300 text-primary-600 shadow-sm focus:ring-primary-500" {{ old('is_active', $doctor->is_active) ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-slate-600 dark:text-slate-400">Account is Active</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-6 border-t border-slate-200 dark:border-slate-700">
                            <a href="{{ route('admin.doctors.index') }}" class="px-4 py-2 bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200 rounded-md hover:bg-slate-300 dark:hover:bg-slate-600 transition">Cancel</a>
                            <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
