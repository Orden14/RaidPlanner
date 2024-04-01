import {Calendar} from "@fullcalendar/core";
import interactionPlugin from "@fullcalendar/interaction";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from "@fullcalendar/list";
import {addHours} from 'date-fns';
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
        customButtons: {
            newGuildEvent: {
                text: "",
                click: function() {
                    $('#newGuildEventModal').modal('show');
                }
            }
        },
        headerToolbar: {
            left: "prev,next today",
            center: "title",
            right: "newGuildEvent dayGridMonth,timeGridWeek,timeGridDay,listWeek"
        },
        buttonText: {
            month: "Mois",
            today: "Aujourd'hui",
            week: "Semaine",
            day: "Jour",
            list: "Liste",
        },
        initialView: initialView,
        navLinks: true,
        dateClick: function(info) {
            // Open the modal
            $('#newGuildEventModal').modal('show');

            let startDate = new Date(info.dateStr);
            let endDate = addHours(startDate, 2);
            let localDate = new Date(endDate.getTime() - endDate.getTimezoneOffset() * 60000);
            let endStr = localDate.toISOString().split('.')[0];

            $('#newGuildEventModal input[name="guild_event[start]"]').val(info.dateStr)
            $('#newGuildEventModal input[name="guild_event[end]"]').val(endStr)

        },

        plugins: [ interactionPlugin, dayGridPlugin, timeGridPlugin, listPlugin ],
        timeZone: "Europe/Paris",

    })

    calendar.render()
    document.querySelector('.fc-newGuildEvent-button').innerHTML = '<i class="bi bi-calendar-plus"></i>'
})
