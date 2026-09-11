<div>
    <!-- The biggest battle is the war against ignorance. - Mustafa Kemal Atatürk -->
</div>

@push('scripts')
    <script>
        window.Echo.private(`voice-call.user.${userId}`).listen('.SignalingEvent', (e) => {
            console.log('Incoming signaling data:', e);
        });
        if (navigator.mediaDevices == 'undefined') {
            navigator.mediaDevices = {};
        }
        if (navigator.mediaDevices) {
            const stream = await navigator.mediaDevices.getUserMedia({
                audio: true
            });
        }

        const peerConnection = new RTCPeerConnection({
            iceServers: [{
                urls: 'stun:stun.l.google.com:19302',
            }, ],
        });
        peerConnection.ontrack = function(event) {
            console.log('Track added:', event.track);
            const remoteAudio = document.createElement('audio');
            remoteAudio.srcObject = event.streams[0];
            remoteAudio.play();
        };
        stream.getTracks().forEach((track) => {
            peerConnection.addTrack(track, stream);
        });
        //30 second timeout if not answered
        // const ringTimer = setTimeout(() => {
        //     hangUpCall();
        //     console.log("Call ended: No answer from the other party.");
        // }, 30000);
        //clearTimeout(ringTimer); i need to add this so it removes timer if answer
    </script>
@endpush
