export function filterRow (settings, data) {
    const matchingByStatus = statusFilter(data[3].trim())
    const matchingBySpecialization = specializationFilter(data[0].trim())
    const matchingByCategory = categoryFilter(data[8].trim())

    return matchingByStatus && matchingBySpecialization && matchingByCategory
}

function statusFilter (rowStatusData) {
    const selectedStatus = $('#statusFilter').val()

    return selectedStatus.length === 0 ? true : selectedStatus.includes(rowStatusData)
}

function specializationFilter (rowSpecializationData) {
    const selectedSpecializations = $('#specializationFilter').val()

    return selectedSpecializations.length === 0 ? true : selectedSpecializations.includes(rowSpecializationData)
}

function categoryFilter (rowCategoriesData) {
    const selectedCategories = $('#categoryFilter').val()

    return selectedCategories.length === 0
        ? true
        : selectedCategories.every(
            function (category) {
                return rowCategoriesData.includes(category)
            }
        )
}
