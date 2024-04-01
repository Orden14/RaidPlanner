import { Calendar } from "@fullcalendar/core";
import interactionPlugin from "@fullcalendar/interaction";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from "@fullcalendar/list";

import "./index.css";

document.addEventListener("DOMContentLoaded", () => {
    let calendarEl = document.getElementById("calendar-holder");

    let viewportHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
    let calendarHeight = 0.8 * viewportHeight;

    let { eventsUrl } = calendarEl.dataset;

    let calendar = new Calendar(calendarEl, {
        locale: 'fr',
        firstDay: 1,
        slotLabelFormat: { hour: '2-digit', minute: '2-digit', hour12: false },
        eventTimeFormat: { hour: '2-digit', minute: '2-digit', hour12: false },
        allDaySlot: false,
        slotMinTime: '09:00',
        slotMaxTime: '24:00',
        editable: true,
        contentHeight: calendarHeight,
        eventSources: [
            {
                url: eventsUrl,
                method: "POST",
                extraParams: {
                    filters: JSON.stringify({}),
                },
                failure: () => {
                    alert("There was an error while fetching the planning!")
                },
            },
        ],
        headerToolbar: {
            left: "prev,next today",
            center: "title",
            right: "dayGridMonth,timeGridWeek,timeGridDay,listWeek"
        },
        initialView: "timeGridWeek",
        navLinks: true,
        plugins: [ interactionPlugin, dayGridPlugin, timeGridPlugin, listPlugin ],
        timeZone: "UTC",
    })

    calendar.render()
})
