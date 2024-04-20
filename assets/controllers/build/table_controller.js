import {Controller} from "stimulus"
import $ from 'jquery'
import 'datatables.net-bs5'
import 'datatables.net-select-bs5'

export default class extends Controller {
    initialize() {
        let table = $(this.element).DataTable({
            language: {
                "lengthMenu": "Afficher _MENU_ builds par page",
                "zeroRecords": "Aucun build trouvé",
                "info": "Page _PAGE_ sur _PAGES_",
                "infoEmpty": "Aucun build trouvé",
                "infoFiltered": "(filtré à partir de _MAX_ builds total)",
            },
            select: true,
        })

        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                let selectedStatus = $('#statusFilter').val()
                let selectedCategories = $('#categoryFilter').val()

                if (selectedStatus === 0 && selectedCategories.length === 0) {
                    return true
                }

                let rowStatus = data[0].trim()
                let rowCategories = data[3].trim()

                let matchingByStatus = selectedStatus.length === 0 ? true : selectedStatus.every(
                    function (status) { return rowStatus === status }
                )

                let matchingByCategory = selectedCategories.length === 0 ? true : selectedCategories.every(
                    function (category) { return rowCategories.includes(category) }
                )

                return matchingByStatus && matchingByCategory
            }
        )

        $('#categoryFilter, #statusFilter').on('change', function () {
            table.draw()
        })
    }
}
