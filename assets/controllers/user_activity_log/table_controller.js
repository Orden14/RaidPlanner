import {Controller} from "stimulus"
import {filterRow} from "../../util/user_activity_log/table_filter_manager";

export default class extends Controller {
    initialize() {
        let table = $(this.element).DataTable({
            responsive: true,
            language: {
                "lengthMenu": "Afficher _MENU_ logs par page",
                "zeroRecords": "Aucun log trouvé",
                "info": "Page _PAGE_ sur _PAGES_",
                "infoEmpty": "Aucun log trouvé",
                "infoFiltered": "(filtré à partir de _MAX_ logs)",
            },
            order: [[0, 'desc']]
        })

        $.fn.dataTable.ext.search.push(
            function(settings, data) {
                return filterRow(settings, data)
            }
        )

        $('#logTypeFilter').on('change', function () {
            table.draw()
        })
    }
}
