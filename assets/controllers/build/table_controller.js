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
                let selectedCategories = $('#categoryFilter').val()
                if (selectedCategories.length === 0) {
                    return true
                }

                let rowCategories = data[3].split(' ');

                return selectedCategories.every(function (category) {
                    return rowCategories.includes(category)
                })
            }
        )

        $('#categoryFilter').on('change', function () {
            table.draw()
        })
    }
}
