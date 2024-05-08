export function filterRow (settings, data) {
    let matchingByStatus = statusFilter(data[3].trim())
    let matchingBySpecialization = specializationFilter(data[0].trim())
    let matchingByCategory = categoryFilter(data[7].trim())

    return matchingByStatus && matchingBySpecialization && matchingByCategory
}

function statusFilter (rowStatusData) {
    let selectedStatus = $('#statusFilter').val()

    return selectedStatus.length === 0 ? true : selectedStatus.includes(rowStatusData)
}

function specializationFilter (rowSpecializationData) {
    let selectedSpecializations = $('#specializationFilter').val()

    return selectedSpecializations.length === 0 ? true : selectedSpecializations.includes(rowSpecializationData)
}

function categoryFilter (rowCategoriesData) {
    let selectedCategories = $('#categoryFilter').val()

    return selectedCategories.length === 0 ? true : selectedCategories.every(
        function (category) { return rowCategoriesData.includes(category) }
    )
}
