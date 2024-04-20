import {Controller} from "stimulus"
import $ from 'jquery'
import 'datatables.net-bs5'
import 'datatables.net-select-bs5'
import { filterRow } from "../../util/build/table_filter_manager"

export default class extends Controller {
    initialize() {
        let table = $(this.element).DataTable({
            responsive: true,
            language: {
                "lengthMenu": "Afficher _MENU_ builds par page",
                "zeroRecords": "Aucun build trouvé",
                "info": "Page _PAGE_ sur _PAGES_",
                "infoEmpty": "Aucun build trouvé",
                "infoFiltered": "(filtré à partir de _MAX_ builds total)",
            },
            columnDefs: [
                { width: '1%', targets: 0 },
                { width: '30%', targets: 1, orderable: false },
                { width: '1%', targets: 2, orderable: false },
                { width: '15%', targets: 3 },
                { width: '47%', targets: 4, orderable: false },
            ],
        })

        $(table.rows().nodes()).on('click', function () {
            let url = $(this).data('url')
            if (url) {
                window.location.href = url
            }
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
