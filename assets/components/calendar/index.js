import { Calendar } from "@fullcalendar/core";
import interactionPlugin from "@fullcalendar/interaction";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from "@fullcalendar/list";
import { addHours, formatISO } from 'date-fns';
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
            right: "dayGridMonth,timeGridWeek,timeGridDay,listWeek"
        },
        buttonText: {
            month: "Mois",
            today: "Aujourd\'hui",
            week: "Semaine",
            day: "Jour",
            list: "Liste",
        },
        initialView: initialView,
        navLinks: true,
        dateClick: function(info) {
            // Open the modal
            $('#newGuildEventModal').modal('show');

            // Parse info.dateStr into a Date object
            let startDate = new Date(info.dateStr);

            // Add 2 hours to the Date object using date-fns
            let endDate = addHours(startDate, 2);

            // Create a new Date object without the timezone offset
            let localDate = new Date(endDate.getTime() - endDate.getTimezoneOffset() * 60000);

            // Format the updated Date object back into a string
            let endStr = localDate.toISOString().split('.')[0];

            console.log(info.dateStr)
            console.log(endStr)

            $('#newGuildEventModal input[name="guild_event[start]"]').val(info.dateStr)
            $('#newGuildEventModal input[name="guild_event[end]"]').val(endStr)

        },

        plugins: [ interactionPlugin, dayGridPlugin, timeGridPlugin, listPlugin ],
        timeZone: "Europe/Paris",

    })

    calendar.render()
})
