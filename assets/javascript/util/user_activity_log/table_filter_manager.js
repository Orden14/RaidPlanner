export function filterRow (settings, data) {
    return logTypeFilter(data[1].trim())
}

function logTypeFilter (rowLogTypeData) {
    const selectedLogType = $('#logTypeFilter').val()

    return selectedLogType.length === 0 ? true : selectedLogType.includes(rowLogTypeData)
}
