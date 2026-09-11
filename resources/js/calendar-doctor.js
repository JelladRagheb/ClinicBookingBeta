import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";
import $ from "jquery";

window.Calendar = Calendar;
window.dayGridPlugin = dayGridPlugin;
window.timeGridPlugin = timeGridPlugin;
window.interactionPlugin = interactionPlugin;

document.addEventListener("DOMContentLoaded", function () {
    const calendarEl = document.getElementById("calendar");
    if (!calendarEl) return;

    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    const userId = document
        .querySelector('meta[name="user-id"]')
        .getAttribute("content");

    const urlParams = new URLSearchParams(window.location.search);
    const targetDate = urlParams.get("date");
    const targetEventId = urlParams.get("event_id");

    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
        initialView: targetDate ? "timeGridDay" : "dayGridMonth",
        initialDate: targetDate || undefined,
        selectable: true,
        editable: true,
        eventResizableFromStart: true,
        slotDuration: "00:15:00",
        slotLabelFormat: {
            hour: "2-digit",
            minute: "2-digit",
            hour12: false,
        },
        slotMinTime: "08:00:00",
        slotMaxTime: "18:00:00",
        locale: "fr",
        timeZone: "local",
        headerToolbar: {
            left: "prev,next today",
            center: "title",
            right: "dayGridMonth,timeGridWeek,timeGridDay",
        },
        events: function (info, successCallback, failureCallback) {
            $.ajax({
                url: "/events",
                type: "GET",
                dataType: "json",
                data: {
                    start: info.startStr,
                    end: info.endStr,
                },
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
            const title = prompt("Event Title:");
            if (title) {
                const color = prompt("Color hex (e.g. #ff0000):", "#3788d8");
                $.ajax({
                    url: "/create-schedule",
                    type: "POST",
                    dataType: "json",
                    headers: { "X-CSRF-TOKEN": csrfToken },
                    data: {
                        doctor_id: userId,
                        title: title,
                        start: info.startStr,
                        end: info.endStr,
                        color: color,
                    },
                    success: function (response) {
                        calendar.addEvent(response.schedule);
                    },
                    error: function (xhr) {
                        console.error("Error creating event", xhr.responseText);
                        alert("Error creating event.");
                    },
                });
            }
            calendar.unselect();
        },
        eventClick: function (info) {
            const event = info.event;

            // Show Description Alert First
            const description = `Appointment Details:\n\nTitle/Reason: ${event.title}\nStart: ${event.startStr}\nEnd: ${event.endStr || "N/A"}`;
            if (
                !confirm(
                    `${description}\n\nDo you want to edit this appointment?`,
                )
            ) {
                return;
            }

            const newTitle = prompt("Edit Title:", event.title);
            if (newTitle === null) return;

            const newColor = prompt("Edit Color (hex):", event.backgroundColor);

            $.ajax({
                url: "/schedule/" + event.id,
                type: "PUT",
                dataType: "json",
                headers: { "X-CSRF-TOKEN": csrfToken },
                data: {
                    title: newTitle ?? event.title,
                    start: event.startStr,
                    end: event.endStr,
                    color: newColor ?? event.backgroundColor,
                },
                success: function (updated) {
                    event.setProp("title", updated.schedule.title);
                    if (updated.schedule.color) {
                        event.setProp(
                            "backgroundColor",
                            updated.schedule.color,
                        );
                        event.setProp("borderColor", updated.schedule.color);
                    }
                    if (confirm("Delete this event?")) {
                        deleteEvent(event);
                    }
                },
                error: function (xhr) {
                    console.error("Error updating event", xhr.responseText);
                    alert("Error updating event.");
                },
            });
        },
        eventDrop: function (info) {
            updateEvent(info.event);
        },
        eventResize: function (info) {
            updateEvent(info.event);
        },
    });

    function updateEvent(event) {
        $.ajax({
            url: "/schedule/" + event.id,
            type: "PUT",
            dataType: "json",
            headers: { "X-CSRF-TOKEN": csrfToken },
            data: {
                title: event.title,
                start: event.startStr,
                end: event.endStr,
                color: event.backgroundColor,
            },
            success: function (updated) {
                if (updated.color) {
                    event.setProp("backgroundColor", updated.color);
                }
            },
            error: function (xhr) {
                console.error("Error updating (drag/resize)", xhr.responseText);
                event.revert();
            },
        });
    }

    function deleteEvent(event) {
        $.ajax({
            url: "/schedule/" + event.id,
            type: "DELETE",
            dataType: "json",
            headers: { "X-CSRF-TOKEN": csrfToken },
            success: function () {
                event.remove();
            },
            error: function (xhr) {
                console.error("Error deleting event", xhr.responseText);
                alert("Error deleting event.");
            },
        });
    }

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

    window.addEventListener("notify", function (e) {
        calendar.refetchEvents();
    });
});
