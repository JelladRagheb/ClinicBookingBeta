import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";
import $ from "jquery";

document.addEventListener("DOMContentLoaded", function () {
    const calendarEl = document.getElementById("calendar");
    if (!calendarEl) return;

    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    const userId = document
        .querySelector('meta[name="user-id"]')
        .getAttribute("content");

    // Optional: Get doctor ID if we are viewing a specific doctor's calendar to book
    // This could be passed via dataset on the calendar element
    const targetDoctorId = calendarEl.dataset.doctorId;
    const appointmentType = calendarEl.dataset.appointmentType;
    const urlParams = new URLSearchParams(window.location.search);
    const targetDate = urlParams.get("date");
    const targetEventId = urlParams.get("event_id");

    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
        initialView: targetDate ? "timeGridDay" : "timeGridWeek",
        initialDate: targetDate || undefined,
        selectable: true, // Allow selecting slots to book
        editable: false, // Patients shouldn't drag/drop doctor's events
        slotDuration: "00:15:00",
        slotMinTime: "08:00:00",
        slotMaxTime: "18:00:00",
        headerToolbar: {
            left: "prev,next today",
            center: "title",
            right: "dayGridMonth,timeGridWeek",
        },
        events: function (info, successCallback, failureCallback) {
            const params = {
                start: info.startStr,
                end: info.endStr,
            };
            if (targetDoctorId) {
                params.doctor_id = targetDoctorId; // Backend needs to support this filter
            }

            $.ajax({
                url: "/events",
                type: "GET",
                dataType: "json",
                data: params,
                success: function (response) {
                    successCallback(response);
                },
                error: function (xhr) {
                    console.error("Error loading events", xhr.responseText);
                    failureCallback(xhr);
                },
            });
        },
        select: function (info) {
            // Patient booking flow
            const title = prompt("Reason for Appointment:");
            if (title) {
                // Determine doctor ID: either from context (targetDoctorId) or defaults
                const doctorId =
                    targetDoctorId || prompt("Enter Doctor ID (Debug):"); // In real app, this should be context-aware

                if (!doctorId) {
                    alert("Doctor ID required to book.");
                    calendar.unselect();
                    return;
                }
                prompt("Appointment Type:");
                const startTime = prompt("Start Time:", info.startStr);
                const endTime = prompt("End Time:", info.endStr);
                // console.log("color", appointmentType);
                $.ajax({
                    url: "/create-schedule",
                    type: "POST",
                    dataType: "json",
                    headers: { "X-CSRF-TOKEN": csrfToken },
                    data: {
                        doctor_id: doctorId,
                        title: title,
                        start: startTime,
                        end: endTime,
                        appointment_type: appointmentType,
                        // color: appointmentType.color,
                        // color: "#28a745", // Green for patient booking
                    },
                    success: function (response) {
                        calendar.addEvent(response.schedule);
                        alert("Appointment requested!");
                    },
                    error: function (xhr) {
                        console.error(
                            "Error creating booking",
                            xhr.responseText,
                        );
                        alert("Error booking appointment.");
                    },
                });
            }
            calendar.unselect();
        },
        eventClick: function (info) {
            const event = info.event;
            // Check if this event belongs to the patient (if extendedProps are populated)
            if (event.extendedProps.patient_id == userId) {
                // Show Description Modal First
                const description = `Appointment Details:\n\nTitle/Reason: ${event.title}\nStart: ${event.startStr}\nEnd: ${event.endStr || "N/A"}`;
                alert(description);

                // Prompt for action
                let action = prompt(
                    "Type 'cancel' to delete, or 'reschedule' to change time:",
                    "cancel",
                );
                if (!action) return;
                action = action.toLowerCase();

                if (action === "cancel") {
                    if (confirm("Are you sure you want to cancel?")) {
                        $.ajax({
                            url: "/schedule/" + event.id,
                            type: "DELETE",
                            dataType: "json",
                            headers: { "X-CSRF-TOKEN": csrfToken },
                            success: function () {
                                event.remove();
                                alert("Appointment cancelled.");
                            },
                            error: function (xhr) {
                                console.error(
                                    "Error cancelling",
                                    xhr.responseText,
                                );
                                alert("Error cancelling appointment.");
                            },
                        });
                    }
                } else if (action === "reschedule") {
                    // Simple reschedule flow
                    const newStart = prompt(
                        "New Start Time (YYYY-MM-DD HH:mm):",
                        event.startStr,
                    );
                    const newEnd = prompt(
                        "New End Time (YYYY-MM-DD HH:mm):",
                        event.endStr,
                    );

                    if (newStart && newEnd) {
                        $.ajax({
                            url: "/schedule/" + event.id,
                            type: "PUT",
                            dataType: "json",
                            headers: { "X-CSRF-TOKEN": csrfToken },
                            data: {
                                title: event.title,
                                start: newStart,
                                end: newEnd,
                            },
                            success: function (updated) {
                                // Reload page or refetch events
                                calendar.refetchEvents();
                                alert("Appointment rescheduled.");
                            },
                            error: function (xhr) {
                                console.error(
                                    "Error rescheduling",
                                    xhr.responseText,
                                );
                                alert("Error rescheduling appointment.");
                            },
                        });
                    }
                }
            } else {
                alert("This slot is taken.");
            }
        },
    });

    calendar.render();

    // If navigated from notification, show the specific event details
    if (targetEventId) {
        $.ajax({
            url: "/schedule/" + targetEventId,
            type: "GET",
            dataType: "json",
            success: function (response) {
                if (response.schedule) {
                    const sch = response.schedule;
                    alert(
                        `Target Appointment:\n\nReason: ${sch.title}\nStart: ${sch.start}\nEnd: ${sch.end}`,
                    );
                }
            },
        });
    }

    // Listen to the global Custom Alpine Event for notifications to silently update the calendar
    window.addEventListener("notify", function (e) {
        // Only refresh events, no more alerts since global toast handles UX
        calendar.refetchEvents();
    });
});
