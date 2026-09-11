import Echo from "laravel-echo";

import Pusher from "pusher-js";
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "reverb",
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: window.location.hostname,
    //     ? import.meta.env.VITE_REVERB_HOST
    //     : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
    // wsPort: import.meta.env.REVERB_PORT ?? 80,
    // wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
    // forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? "http") === "http",
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    // forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? "https") === "https",
    // authEndpoint: "/api/broadcasting/auth",
    // auth: {
    //     headers: {
    //         Authorization: `Bearer ${localStorage.getItem("token")}`,
    //     },
    // },
    enabledTransports: ["ws", "wss"],
    disableStats: true,
    forceTLS: false,
});
