import {Calendar} from "@fullcalendar/core";
import interactionPlugin from "@fullcalendar/interaction";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from "@fullcalendar/list";
import {addHours, setHours} from 'date-fns';
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

                    let date = new Date()
                    date.setSeconds(0)
                    date.setMinutes(0)
                    let startDate = setHours(date, 21)
                    let endDate = addHours(startDate, 2);

                    let localDate = new Date(startDate.getTime() - startDate.getTimezoneOffset() * 60000)
                    let startStr = localDate.toISOString().split('.')[0]
                    localDate = new Date(endDate.getTime() - endDate.getTimezoneOffset() * 60000)
                    let endStr = localDate.toISOString().split('.')[0]

                    $('#newGuildEventModal input[name="guild_event[start]"]').val(startStr)
                    $('#newGuildEventModal input[name="guild_event[end]"]').val(endStr)
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
            $('#newGuildEventModal').modal('show')

            let startDate = new Date(info.dateStr)
            let endDate

            if (info.dateStr.length === 10) {
                startDate = setHours(startDate, 21)
                endDate = addHours(startDate, 2)
            } else {
                endDate = addHours(startDate, 2)
            }

            let localDate = new Date(startDate.getTime() - startDate.getTimezoneOffset() * 60000)
            let startStr = localDate.toISOString().split('.')[0]
            localDate = new Date(endDate.getTime() - endDate.getTimezoneOffset() * 60000)
            let endStr = localDate.toISOString().split('.')[0]

            $('#newGuildEventModal input[name="guild_event[start]"]').val(startStr)
            $('#newGuildEventModal input[name="guild_event[end]"]').val(endStr)
        },

        plugins: [ interactionPlugin, dayGridPlugin, timeGridPlugin, listPlugin ],
        timeZone: "Europe/Paris",

    })

    calendar.render()

    let button = document.querySelector('.fc-newGuildEvent-button');
    let addEventIcon = document.createElement('i');
    addEventIcon.className = 'bi bi-calendar-plus';
    button.appendChild(addEventIcon);
})
