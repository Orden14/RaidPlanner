import { addHours, format, setHours } from 'date-fns'

export function setModalDates (startDate, endDate) {
    $('#manageGuildEventModal input[name="guild_event[start]"]').val(format(startDate, 'yyyy-MM-dd HH:mm'))
    $('#manageGuildEventModal input[name="guild_event[end]"]').val(format(endDate, 'yyyy-MM-dd HH:mm'))
}

export function setModalDatesForDateClick (info) {
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
