<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-200 leading-tight">
            {{ isset($location) ? __('Edit Location') : __('Add New Location') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col md:flex-row gap-6">
            <!-- Form Section -->
            <div class="w-full md:w-1/2 bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ isset($location) ? route('locations.update', $location) : route('locations.store') }}" method="POST">
                        @csrf
                        @if (isset($location))
                        @method('PUT')
                        @endif

                        <div class="mb-4">
                            <label for="name_en" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Name (English)</label>
                            <input type="text" name="name[en]" id="name_en" value="{{ old('name.en', $location->name['en'] ?? '') }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-slate-900 dark:border-slate-600 dark:text-white" required>
                            @error('name.en') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="code" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Code</label>
                                <input type="text" name="code" id="code" value="{{ old('code', $location->code ?? '') }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-slate-900 dark:border-slate-600 dark:text-white" required>
                                @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="city" class="block text-sm font-medium text-slate-700 dark:text-slate-300">City</label>
                                <input type="text" name="city" id="city" value="{{ old('city', $location->city ?? '') }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-slate-900 dark:border-slate-600 dark:text-white">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="address" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Address</label>
                            <textarea name="address" id="address" rows="2" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-slate-900 dark:border-slate-600 dark:text-white">{{ old('address', $location->address ?? '') }}</textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="latitude" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Latitude</label>
                                <input type="number" step="any" name="latitude" id="latitude" value="{{ old('latitude', $location->latitude ?? '36.8065') }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-slate-900 dark:border-slate-600 dark:text-white" required readonly>
                                @error('latitude') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="longitude" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Longitude</label>
                                <input type="number" step="any" name="longitude" id="longitude" value="{{ old('longitude', $location->longitude ?? '10.1815') }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-slate-900 dark:border-slate-600 dark:text-white" required readonly>
                                @error('longitude') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="is_active" class="flex items-center cursor-pointer">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" id="is_active" value="1" class="rounded border-slate-300 text-primary-600 shadow-sm focus:ring-primary-500" {{ old('is_active', $location->is_active ?? true) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-slate-600 dark:text-slate-400">Is Active</span>
                            </label>
                        </div>

                        <div class="flex justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-700">
                            <a href="{{ route('locations.index') }}" class="px-4 py-2 bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200 rounded-md hover:bg-slate-300 dark:hover:bg-slate-600 transition">Cancel</a>
                            <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition">Save Location</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Map Section -->
            <div class="w-full md:w-1/2 bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg min-h-[400px] flex flex-col">
                <div class="p-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/80">
                    <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300">Pin Location on Map</h4>
                    <p class="text-xs text-slate-500">Drag the marker or click on the map to accurately set the coordinates.</p>
                </div>
                <!-- z-index fix to prevent map overflowing tailwind dropdowns if present -->
                <div id="map" class="flex-1 w-full z-10" style="min-height: 400px;"></div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initial coords (Tunis default)
            const initialLat = parseFloat(document.getElementById('latitude').value) || 36.8065;
            const initialLng = parseFloat(document.getElementById('longitude').value) || 10.1815;
            maplibregl.setRTLTextPlugin(
                'https://unpkg.com/@mapbox/mapbox-gl-rtl-text@0.3.0/dist/mapbox-gl-rtl-text.js',
                true // Lazy load the plugin
            );
            // Initialize OpenFreeMap / MapLibre GL
            const map = new maplibregl.Map({
                container: 'map',
                style: 'https://tiles.openfreemap.org/styles/liberty', // OpenFreeMap Liberty style
                center: [initialLng, initialLat],
                zoom: 13
            });

            // Add navigation control (zoom in/out)
            map.addControl(new maplibregl.NavigationControl());

            // Initialize draggable marker
            const marker = new maplibregl.Marker({
                    draggable: true,
                    color: "#0ea5e9" // primary-500
                })
                .setLngLat([initialLng, initialLat])
                .addTo(map);

            function updateInputs() {
                const lngLat = marker.getLngLat();
                document.getElementById('latitude').value = lngLat.lat.toFixed(6);
                document.getElementById('longitude').value = lngLat.lng.toFixed(6);
            }

            // Listen to marker drag event
            marker.on('dragend', updateInputs);

            // Listen to map click to move marker
            map.on('click', function(e) {
                marker.setLngLat(e.lngLat);
                updateInputs();
            });
        });
    </script>
    @endpush
</x-app-layout>