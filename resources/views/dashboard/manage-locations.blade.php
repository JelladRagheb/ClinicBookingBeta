<x-app-layout>
    <div>
        <h1>Manage Locations</h1>
        <table>
            <thead>
                <tr>
                    <th>IP Address</th>
                    <th>Country</th>
                    <th>City</th>
                    <th>Region</th>
                    <th>Postal Code</th>
                    <th>Timezone</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <!-- No surplus words or unnecessary actions. - Marcus Aurelius -->
    </div>
    {{-- <div id="map" style="width: 100%; height: 500px"></div>
    <script>
   maplibregl.setRTLTextPlugin(
            'https://unpkg.com/@mapbox/mapbox-gl-rtl-text@0.3.0/dist/mapbox-gl-rtl-text.js',
            true // Lazy load the plugin
        );
        const map = new maplibregl.Map({
            style: 'https://tiles.openfreemap.org/styles/liberty',
            center: [10.1658, 36.8190],
            zoom: 8,
            container: 'map',
        })
    </script> --}}
    <div id="map" style="width: 100%; height: 400px; margin-left: 8px; padding-left: 44px; box-sizing: border-box;">
    </div>
    <script>
        const map = L.map('map').setView([35.6575947,
            10.8905521
        ], 15)

        L.maplibreGL({
            style: 'https://tiles.openfreemap.org/styles/liberty',
        }).addTo(map)
    </script>
    {{-- @dd($locations) --}}
</x-app-layout>