import {addHours, setHours} from "date-fns";

export function setModalDates(startDate, endDate) {
    let localDate = new Date(startDate.getTime() - startDate.getTimezoneOffset() * 60000)
    let startStr = localDate.toISOString().split('.')[0]
    localDate = new Date(endDate.getTime() - endDate.getTimezoneOffset() * 60000)
    let endStr = localDate.toISOString().split('.')[0]

    $('#newGuildEventModal input[name="guild_event[start]"]').val(startStr)
    $('#newGuildEventModal input[name="guild_event[end]"]').val(endStr)
}

export function setModalDatesForDateClick(info) {
    let startDate = new Date(info.dateStr)
    let endDate

    if (info.dateStr.length === 10) {
        startDate = setHours(startDate, 21)
        endDate = addHours(startDate, 2)
    } else {
        endDate = addHours(startDate, 2)
    }

    setModalDates(startDate, endDate)
}