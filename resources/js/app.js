import "./bootstrap";
// FullCalendar imports removed
import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;
// Echo and Pusher are initialized in resources/js/bootstrap.js via echo.js

// var pusher = new Pusher(
//     import.meta.env.VITE_PUSHER_APP_KEY,
//     import.meta.env.VITE_PUSHER_APP_CLUSTER,
//     {
//         encrypted: true,
//     },
// );
// pusher
//     .subscribe("notifications")
//     .bind("App\Events\AppointmentNotification", function (data) {
//         console.log(data);
//     });
// window.Calendar = Calendar;
// window.dayGridPlugin = dayGridPlugin;
// window.timeGridPlugin = timeGridPlugin;
// window.listPlugin = listPlugin;
// window.interactionPlugin = interactionPlugin;
// resources/js/app.js (or similar)

// On page load, check user preference from local storage or system settings
function applyTheme() {
    const savedTheme = localStorage.getItem("theme");
    const systemPrefersDark = window.matchMedia(
        "(prefers-color-scheme: dark)",
    ).matches;

    if (savedTheme === "dark" || (!savedTheme && systemPrefersDark)) {
        document.documentElement.classList.add("dark");
    } else {
        document.documentElement.classList.remove("dark");
    }
}

// Function to toggle the theme
window.toggleDarkMode = function () {
    if (document.documentElement.classList.contains("dark")) {
        document.documentElement.classList.remove("dark");
        localStorage.setItem("theme", "light");
    } else {
        document.documentElement.classList.add("dark");
        localStorage.setItem("theme", "dark");
    }
};

// Apply theme immediately
applyTheme();

// Optional: watch for system theme changes
window
    .matchMedia("(prefers-color-scheme: dark)")
    .addEventListener("change", (e) => {
        // Only re-apply if the user hasn't set a manual preference
        if (!localStorage.getItem("theme")) {
            applyTheme();
        }
    });

// FullCalendar logic moved to calendar-doctor.js and calendar-patient.js
