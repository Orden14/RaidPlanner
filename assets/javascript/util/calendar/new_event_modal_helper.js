import {addHours, setHours} from "date-fns";

export function setModalDates(startDate, endDate) {
    $('#manageGuildEventModal input[name="guild_event[start]"]').val(formatDate(startDate))
    $('#manageGuildEventModal input[name="guild_event[end]"]').val(formatDate(endDate))
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

function formatDate(date) {
    return date.getFullYear() + '-' +
        ('0' + (date.getMonth()+1)).slice(-2) + '-' +
        ('0' + date.getDate()).slice(-2) + ' ' +
        ('0' + date.getHours()).slice(-2) + ':' +
        ('0' + date.getMinutes()).slice(-2);
}
