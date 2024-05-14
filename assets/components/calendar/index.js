import {Calendar} from "@fullcalendar/core";
import interactionPlugin from "@fullcalendar/interaction";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from "@fullcalendar/list";
import {addHours, setHours} from 'date-fns';
import {setModalDates, setModalDatesForDateClick} from "../../util/Calendar/new_event_modal_helper";

document.addEventListener("DOMContentLoaded", () => {
    let calendarEl = document.getElementById("calendar-holder")

    let viewportHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight
    let calendarHeight = 0.8 * viewportHeight

    let initialView = window.innerWidth < 600 ? 'listWeek' : 'timeGridWeek'

    let {eventsUrl} = calendarEl.dataset;

    let touchStartTime = null;
    let touchEndTime = null;

    let calendar = new Calendar(calendarEl, {
        locale: 'fr',
        firstDay: 1,
        slotLabelFormat: {hour: '2-digit', minute: '2-digit', hour12: false},
        eventTimeFormat: {hour: '2-digit', minute: '2-digit', hour12: false},
        allDaySlot: false,
        slotMinTime: '09:00',
        slotMaxTime: '24:00',
        editable: false,
        contentHeight: calendarHeight,
        nowIndicator: true,
        eventSources: [
            {
                url: eventsUrl,
                method: "POST",
                extraParams: {
                    filters: JSON.stringify({}),
                },
                failure: () => {
                    console.log("There was an error while fetching the planning!")
                },
            },
        ],
        customButtons: {
            newGuildEvent: {
                text: "",
                click: function () {
                    $('#manageGuildEventModal').modal('show');

                    let date = new Date()
                    date.setSeconds(0)
                    date.setMinutes(0)
                    let startDate = setHours(date, 21)
                    let endDate = addHours(startDate, 2)

                    setModalDates(startDate, endDate)
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

        dateClick: function (info) {
            if (window.matchMedia("(pointer: fine)").matches || touchEndTime - touchStartTime >= 400) {
                $('#newGuildEventModal').modal('show')

                setModalDatesForDateClick(info)
            }
        },

        plugins: [interactionPlugin, dayGridPlugin, timeGridPlugin, listPlugin],
        timeZone: "Europe/Paris",
    })

    if (window.matchMedia("(pointer: coarse)").matches) {
        calendarEl.addEventListener('touchstart', function () {
            touchStartTime = new Date().getTime();
        });

        calendarEl.addEventListener('touchend', function () {
            touchEndTime = new Date().getTime();
        });
    }

    calendar.render()

    let button = document.querySelector('.fc-newGuildEvent-button');
    let addEventIcon = document.createElement('i');
    addEventIcon.className = 'bi bi-calendar-plus';
    button.appendChild(addEventIcon);
})
