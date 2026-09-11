<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-200 leading-tight">
            {{ __('Find Nearby Clinics') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="clinicSearch()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div>
                        <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100">Locate Clinics Near You</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Allow location access to find clinics sorted by nearest distance.</p>
                    </div>
                    <div>
                        <button @click="requestLocation" :disabled="loading" class="px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg flex items-center gap-2 transition-colors disabled:opacity-50">
                            <svg x-show="!loading" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <svg x-show="loading" class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-text="loading ? 'Locating...' : 'Use My Position'"></span>
                        </button>
                    </div>
                </div>
                <div x-show="error" class="mt-4 p-3 bg-red-100 text-red-700 rounded-md text-sm" x-text="error" style="display: none;"></div>
            </div>

            <!-- Map View -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 relative border border-slate-200 dark:border-slate-700">
                <div id="search-map" class="w-full h-[500px] z-10"></div>
                <!-- Overlay shown before map is loaded -->
                <div x-show="!mapInitialized" class="absolute inset-0 z-20 flex items-center justify-center bg-slate-100/80 dark:bg-slate-900/80 backdrop-blur-sm">
                    <p class="text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 px-6 py-3 rounded-full shadow-lg font-medium flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path>
                        </svg>
                        Click "Use My Position" to start
                    </p>
                </div>
            </div>

            <!-- Results List -->
            <div x-show="results.length > 0" style="display: none;">
                <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100 mb-4 tracking-tight">Nearby Clinics</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <template x-for="clinic in results" :key="clinic.id">
                        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition duration-200">
                            <div class="p-5">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-bold text-lg text-slate-900 dark:text-white" x-text="clinic.name.en || clinic.name"></h4>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-900/30 dark:text-primary-400" x-text="parseFloat(clinic.distance).toFixed(1) + ' km'"></span>
                                </div>
                                <p class="text-slate-500 dark:text-slate-400 text-sm mb-4 flex items-start gap-2">
                                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    </svg>
                                    <span x-text="clinic.address + (clinic.city ? ', ' + clinic.city : '')"></span>
                                </p>
                                <div class="flex justify-end gap-2 text-sm">
                                    <a :href="'/locations/' + clinic.id" class="px-3 py-1.5 font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200 transition-colors">Details</a>
                                    <button @click="panToClinic(clinic)" class="px-3 py-1.5 font-medium text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-md transition-colors">Show on Map</button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div x-show="searched && results.length === 0" style="display: none;" class="bg-white dark:bg-slate-800 p-8 rounded-lg border border-slate-200 dark:border-slate-700 text-center shadow-sm">
                <svg class="mx-auto h-12 w-12 text-slate-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100">No clinics found nearby</h3>
                <p class="text-slate-500 mt-1">Try expanding your search radius or choosing a different location.</p>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        maplibregl.setRTLTextPlugin(
            'https://unpkg.com/@mapbox/mapbox-gl-rtl-text@0.3.0/dist/mapbox-gl-rtl-text.js',
            true // Lazy load the plugin
        );
        document.addEventListener('alpine:init', () => {
            Alpine.data('clinicSearch', () => ({
                loading: false,
                searched: false,
                error: '',
                results: [],
                map: null,
                mapInitialized: true,
                markers: [],
                userMarker: null,

                init() {},

                initMap(lat, lng) {
                    if (this.map) {
                        this.map.flyTo({
                            center: [lng, lat],
                            zoom: 12
                        });
                    } else {
                        this.map = new maplibregl.Map({
                            container: 'search-map',
                            style: 'https://tiles.openfreemap.org/styles/liberty',
                            center: [lng, lat],
                            zoom: 12
                        });
                        this.map.addControl(new maplibregl.NavigationControl());
                        this.mapInitialized = true;
                    }

                    // Add/Update user marker
                    if (this.userMarker) {
                        this.userMarker.setLngLat([lng, lat]);
                    } else {
                        // Create a DOM element for user marker (blue dot)
                        const el = document.createElement('div');
                        el.className = 'w-4 h-4 bg-primary-500 rounded-full border-2 border-white shadow-md relative';
                        const pulse = document.createElement('div');
                        pulse.className = 'absolute -inset-2 bg-primary-500 rounded-full animate-ping opacity-75';
                        el.appendChild(pulse);

                        this.userMarker = new maplibregl.Marker({
                                element: el
                            })
                            .setLngLat([lng, lat])
                            .setPopup(new maplibregl.Popup({
                                offset: 10
                            }).setText('You are here'))
                            .addTo(this.map);
                    }
                },

                requestLocation() {
                    this.loading = true;
                    this.error = '';

                    if (!navigator.geolocation) {
                        this.error = 'Geolocation is not supported by your browser.';
                        this.loading = false;
                        return;
                    }

                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;

                            this.initMap(lat, lng);
                            this.fetchClinics(lat, lng);
                        },
                        (err) => {
                            this.loading = false;
                            this.error = 'Failed to get location: ' + err.message + '. Please ensure location permissions are granted.';
                        }, {
                            enableHighAccuracy: true,
                            timeout: 5000,
                            maximumAge: 0
                        }
                    );
                },

                fetchClinics(lat, lng) {
                    // Adjust radius logically if needed
                    const radius = 50;
                    fetch(`/locations/search?lat=${lat}&lng=${lng}&radius=${radius}`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => {
                            if (!response.ok) throw new Error('Network response was not ok');
                            return response.json();
                        })
                        .then(data => {
                            this.results = data;
                            this.searched = true;
                            this.loading = false;
                            this.addClinicMarkers();
                        })
                        .catch(err => {
                            this.error = 'Error fetching clinics: ' + err.message;
                            this.loading = false;
                        });
                },

                addClinicMarkers() {
                    // Clear old markers
                    this.markers.forEach(m => m.remove());
                    this.markers = [];

                    const bounds = new maplibregl.LngLatBounds();
                    // Include user location in bounds
                    if (this.userMarker) {
                        bounds.extend(this.userMarker.getLngLat());
                    }

                    this.results.forEach(clinic => {
                        if (clinic.latitude && clinic.longitude) {
                            // Create custom marker element for clinic
                            const el = document.createElement('div');
                            el.innerHTML = `<svg class="w-8 h-8 text-red-500 drop-shadow-md" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path></svg>`;
                            el.className = 'cursor-pointer hover:scale-110 transition-transform origin-bottom';

                            const popupHTML = `
                                <div class="px-2 py-1">
                                    <h5 class="font-bold text-sm mb-1">${clinic.name.en || clinic.name}</h5>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-2 whitespace-nowrap">${clinic.city || ''}</p>
                                    <span class="inline-block px-2 py-1 bg-primary-100 text-primary-800 dark:bg-primary-900/40 dark:text-primary-300 text-[10px] rounded-full font-semibold">${parseFloat(clinic.distance).toFixed(1)} km away</span>
                                </div>
                            `;

                            const marker = new maplibregl.Marker({
                                    element: el,
                                    anchor: 'bottom'
                                })
                                .setLngLat([clinic.longitude, clinic.latitude])
                                .setPopup(new maplibregl.Popup({
                                    offset: 25,
                                    closeButton: false
                                }).setHTML(popupHTML))
                                .addTo(this.map);

                            this.markers.push(marker);
                            bounds.extend([clinic.longitude, clinic.latitude]);
                        }
                    });

                    // Fit map to bounds if we found clinics
                    if (this.results.length > 0) {
                        this.map.fitBounds(bounds, {
                            padding: 50,
                            maxZoom: 14
                        });
                    }
                },

                panToClinic(clinic) {
                    if (clinic.latitude && clinic.longitude && this.map) {
                        this.map.flyTo({
                            center: [clinic.longitude, clinic.latitude],
                            zoom: 15,
                            essential: true
                        });

                        // Find and open popup
                        const foundMarker = this.markers.find(m => m.getLngLat().lng === parseFloat(clinic.longitude) && m.getLngLat().lat === parseFloat(clinic.latitude));
                        if (foundMarker) {
                            foundMarker.togglePopup();
                        }
                    }
                }
            }));
        });
    </script>
    @endpush
</x-app-layout>