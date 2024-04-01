import { Calendar } from "@fullcalendar/core";
import interactionPlugin from "@fullcalendar/interaction";
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from "@fullcalendar/list";

import "./index.css";

document.addEventListener("DOMContentLoaded", () => {
    let calendarEl = document.getElementById("calendar-holder")

    let viewportHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight
    let calendarHeight = 0.8 * viewportHeight

    let initialView = window.innerWidth < 600 ? 'listWeek' : 'timeGridWeek'

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
            right: "timeGridWeek,timeGridDay,listWeek"
        },
        buttonText: {
            today: "Aujourd\'hui",
            week: "Semaine",
            day: "Jour",
            list: "Liste",
        },
        initialView: initialView,
        navLinks: true,
        plugins: [ interactionPlugin, timeGridPlugin, listPlugin ],
        timeZone: "Europe/Paris",
    })

    calendar.render()
})
