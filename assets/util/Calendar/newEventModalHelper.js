export function setModalDates(startDate, endDate) {
    let localDate = new Date(startDate.getTime() - startDate.getTimezoneOffset() * 60000)
    let startStr = localDate.toISOString().split('.')[0]
    localDate = new Date(endDate.getTime() - endDate.getTimezoneOffset() * 60000)
    let endStr = localDate.toISOString().split('.')[0]

    $('#newGuildEventModal input[name="guild_event[start]"]').val(startStr)
    $('#newGuildEventModal input[name="guild_event[end]"]').val(endStr)
}
