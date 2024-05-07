import {Controller} from "stimulus"
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
                "infoFiltered": "(filtré à partir de _MAX_ builds)",
            },
            columnDefs: [
                { width: '1%', targets: 0 },
                { width: '30%', targets: 1, orderable: false },
                { width: '10%', targets: 2 },
                { width: '1%', targets: 3, orderable: false },
                { width: '10%', targets: 4 },
                { width: '5%', targets: 5, orderable: true},
                { width: '5%', targets: 6, orderable: true},
                { width: '20%', targets: 7, orderable: false },
            ],
            order: [[2, 'desc']]
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

        $('#statusFilter').trigger('change');
    }
}
