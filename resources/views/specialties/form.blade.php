<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($specialty) ? __('Edit Specialty') : __('Add New Specialty') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form
                        action="{{ isset($specialty) ? route('specialties.update', $specialty) : route('specialties.store') }}"
                        method="POST">
                        @csrf
                        @if (isset($specialty))
                            @method('PUT')
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div class="mb-4">
                                <x-input-label for="name_en" :value="__('Name (English)')" />
                                <x-text-input id="name_en" class="block mt-1 w-full" type="text" name="name[en]"
                                    :value="old('name.en', $specialty->name['en'] ?? '')" required autofocus />
                                <x-input-error :messages="$errors->get('name.en')" class="mt-2" />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="slug" :value="__('Slug')" />
                                <x-text-input id="slug" class="block mt-1 w-full" type="text" name="slug"
                                    :value="old('slug', $specialty->slug ?? '')" required />
                                <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                            </div>

                            <input type="hidden" name="name[fr]" value="{{ $specialty->name['fr'] ?? '' }}">
                            <input type="hidden" name="name[ar]" value="{{ $specialty->name['ar'] ?? '' }}">

                            <div class="mb-4">
                                <x-input-label for="icon" :value="__('Icon Class (FontAwesome/Heroicons)')" />
                                <x-text-input id="icon" class="block mt-1 w-full" type="text" name="icon"
                                    :value="old('icon', $specialty->icon ?? '')" placeholder="fa-solid fa-heart" />
                                <x-input-error :messages="$errors->get('icon')" class="mt-2" />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="is_active" :value="__('Status')" />
                                <select id="is_active" name="is_active"
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                                    <option value="1"
                                        {{ old('is_active', $specialty->is_active ?? 1) == 1 ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="0"
                                        {{ old('is_active', $specialty->is_active ?? 1) == 0 ? 'selected' : '' }}>
                                        Inactive</option>
                                </select>
                            </div>

                            <div class="col-span-1 md:col-span-2">
                                <x-input-label for="description" :value="__('Description')" />
                                <textarea id="description" name="description[en]"
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full"
                                    rows="3">{{ old('description.en', $specialty->description['en'] ?? '') }}</textarea>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('specialties.index') }}"
                                class="text-gray-600 hover:text-gray-900 mr-4">Cancel</a>
                            <x-primary-button>
                                {{ isset($specialty) ? __('Update Specialty') : __('Create Specialty') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
