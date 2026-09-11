<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __('Welcome back, Admin!') }}
                </div>
            </div>
            <button onclick="getLocation()" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded"
                type="button">Click Me!
            </button>

            <script>
                function getLocation() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(sendPositionToServer, showError);
                    } else {
                        alert("Geolocation is not supported by this browser.");
                    }
                }

                function sendPositionToServer(position) {
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;
                    console.log("latitude", latitude);
                    console.log("longitude", longitude);
                }
                fetch('/save-location', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Laravel CSRF token
                        },
                        body: JSON.stringify({
                            latitude: latitude,
                            longitude: longitude
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Location saved:', data);
                        // Handle success (e.g., redirect or display a message)
                    })
                    .catch(error => {
                        console.error('Error saving location:', error);
                    });

                function showError(error) {
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            alert("User denied the request for Geolocation.");
                            break;
                        case error.POSITION_UNAVAILABLE:
                            alert("Location information is unavailable.");
                            break;
                        case error.TIMEOUT:
                            alert("The request to get user location timed out.");
                            break;
                        case error.UNKNOWN_ERROR:
                            alert("An unknown error occurred.");
                            break;
                    }
                }
            </script>
            {{-- <script>
                function getLocation() {
                    navigator.geolocation.getCurrentPosition(

                        (position) => {
                            // Success callback: position object is available here
                            console.log("Success:", position);
                        },
                        (error) => {
                            // Error callback: handle the error here
                            console.error("Error:", error.message);
                            console.error("Code:", error.code);
                            switch (error.code) {
                                case error.PERMISSION_DENIED:
                                    console.error("User denied the request for geolocation.");
                                    break;
                                case error.POSITION_UNAVAILABLE:
                                    console.error("Location information is unavailable.");
                                    break;
                                case error.TIMEOUT:
                                    console.error("The request to get user location timed out.");
                                    break;
                                case error.UNKNOWN_ERROR:
                                    console.error("An unknown error occurred.");
                                    break;
                            }
                        }, {
                            // Optional options
                            enableHighAccuracy: true,
                            timeout: 5000,
                            maximumAge: 0
                        }
                    );
                };
            </script> --}}
        </div>

    </div>
</x-app-layout>
