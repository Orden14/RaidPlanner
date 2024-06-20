import { Calendar } from '@fullcalendar/core'
import interactionPlugin from '@fullcalendar/interaction'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import listPlugin from '@fullcalendar/list'
import { addHours, setHours } from 'date-fns'
import { setModalDates, setModalDatesForDateClick } from '../../util/calendar/new_event_modal_helper'

let headerToolbarOptions

$(document).ready(function () {
    const calendarEl = $('#calendar-holder')

    const viewportHeight = $(window).height()
    const calendarHeight = 0.8 * viewportHeight

    const initialView = $(window).width() < 600 ? 'listWeek' : 'timeGridWeek'

    const { eventsUrl } = calendarEl.data()

    let touchStartTime = null
    let touchEndTime = null

    const calendar = new Calendar(calendarEl[0], {
        locale: 'fr',
        firstDay: 1,
        slotLabelFormat: { hour: '2-digit', minute: '2-digit', hour12: false },
        eventTimeFormat: { hour: '2-digit', minute: '2-digit', hour12: false },
        allDaySlot: false,
        slotMinTime: '09:00',
        slotMaxTime: '24:00',
        editable: false,
        contentHeight: calendarHeight,
        nowIndicator: true,
        customButtons: {
            newGuildEvent: {
                text: '',
                click: function () {
                    $('#manageGuildEventModal').modal('show')

                    const date = new Date()
                    date.setSeconds(0)
                    date.setMinutes(0)
                    const startDate = setHours(date, 21)
                    const endDate = addHours(startDate, 2)

                    setModalDates(startDate, endDate)
                }
            }
        },
        headerToolbar: headerToolbarOptions,
        buttonText: {
            month: 'Mois',
            today: "Aujourd'hui",
            week: 'Semaine',
            day: 'Jour',
            list: 'Liste'
        },
        initialView,
        navLinks: true,
        eventContent: function (arg) {
            let container

            if (arg.view.type !== 'dayGridMonth' && arg.view.type !== 'listWeek') {
                container = $('<div></div>')
                const title = $('<div class="fw-bold mb-2"></div>').html(arg.event.title)
                container.append(title)

                const eventDetails = $(
                    '<div></div>').html(`${arg.event.extendedProps.guildRaid ? 'GRAID' : arg.event.extendedProps.eventType}
                    
                    <br>${arg.event.extendedProps.playerCount}/${arg.event.extendedProps.maxSlots}`
                )

                container.append(eventDetails)
            } else if (arg.view.type === 'dayGridMonth') {
                container = $('<div></div>')

                const title = `
                    <div class="fc-daygrid-event-dot d-inline-flex" style="border-color: ${arg.event.backgroundColor};"></div>
                    <span class="fc-event-time">${arg.event.start.getHours()}:${arg.event.start.getMinutes()}</span>
                    <span class="fc-event-title">${arg.event.title}</span>
                `
                container.append(title)
            } else {
                container = $('<a></a>').attr('href', '/event/show/' + arg.event.extendedProps.eventId)
                const title = `
                    ${arg.event.extendedProps.guildRaid ? 'GRAID' : arg.event.extendedProps.eventType} -
                    ${arg.event.title}
                    ${arg.event.extendedProps.playerCount}/${arg.event.extendedProps.maxSlots}
                `

                container.append(title)
            }

            return { domNodes: [container.get(0)] }
        },
        dateClick: function (info) {
            if (window.matchMedia('(pointer: fine)').matches || touchEndTime - touchStartTime >= 400) {
                $('#manageGuildEventModal').modal('show')

                setModalDatesForDateClick(info)
            }
        },
        eventSources: [
            {
                url: eventsUrl,
                method: 'POST',
                extraParams: {
                    filters: JSON.stringify({})
                }
            }
        ],

        plugins: [interactionPlugin, dayGridPlugin, timeGridPlugin, listPlugin],
        timeZone: 'Europe/Paris'
    })

    if (window.matchMedia('(pointer: coarse)').matches) {
        calendarEl.on('touchstart', function () {
            touchStartTime = new Date().getTime()
        })

        calendarEl.on('touchend', function () {
            touchEndTime = new Date().getTime()
        })
    }

    calendar.render()

    const button = $('.fc-newGuildEvent-button')
    const addEventIcon = $('<i></i>').addClass('bi bi-calendar-plus')
    button.append(addEventIcon)
})

if (window.matchMedia('(max-width: 600px)').matches) {
    headerToolbarOptions = {
        left: 'prev,next today',
        center: 'title',
        right: 'newGuildEvent timeGridWeek,timeGridDay,listWeek'
    }
} else {
    headerToolbarOptions = {
        left: 'prev,next today',
        center: 'title',
        right: 'newGuildEvent dayGridMonth,timeGridWeek,timeGridDay,listWeek'
    }
}
