import {Controller} from "stimulus"
import $ from 'jquery'
import 'datatables.net-bs5'
import 'datatables.net-select-bs5'
import { filterRow } from "../../util/build/table_filter_manager"

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
                return filterRow(settings, data)
            }
        )

        $('#categoryFilter, #statusFilter, #specializationFilter').on('change', function () {
            table.draw()
        })
    }
}
