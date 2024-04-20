import $ from "jquery";

export function filterRow (settings, data) {
    let matchingByStatus = statusFilter(data[0].trim())
    let matchingBySpecialization = specializationFilter(data[1].trim())
    let matchingByCategory = categoryFilter(data[3].trim())

    return matchingByStatus && matchingBySpecialization && matchingByCategory
}

function statusFilter (rowStatusData) {
    let selectedStatus = $('#statusFilter').val()

    return selectedStatus.length === 0 ? true : selectedStatus.every(
        function (status) { return rowStatusData === status }
    )
}

function specializationFilter (rowSpecializationData) {
    let selectedSpecializations = $('#specializationFilter').val()

    return selectedSpecializations.length === 0 ? true : selectedSpecializations.every(
        function (specialization) { return rowSpecializationData === specialization }
    )
}

function categoryFilter (rowCategoriesData) {
    let selectedCategories = $('#categoryFilter').val()

    return selectedCategories.length === 0 ? true : selectedCategories.every(
        function (category) { return rowCategoriesData.includes(category) }
    )
}
