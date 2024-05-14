import {Controller} from "stimulus"
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
                { width: '1%', targets: 0, className: 'min-mobile-p' },
                { width: '30%', targets: 1, orderable: false, className: 'min-mobile-p' },
                { width: '10%', targets: 2, className: 'min-mobile-p' },
                { width: '1%', targets: 3, orderable: false, className: 'min-mobile-p' },
                { width: '10%', targets: 4, className: 'min-mobile-p' },
                { width: '5%', targets: 5, orderable: false, className: 'min-tablet-l' },
                { width: '5%', targets: 6, orderable: false, className: 'min-tablet-l' },
                { width: '5%', targets: 7, orderable: false, className: 'min-tablet-l' },
                { width: '20%', targets: 8, orderable: false, className: 'min-mobile-p' },
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
            function(settings, data) {
                return filterRow(settings, data)
            }
        )

        $('#categoryFilter, #statusFilter, #specializationFilter').on('change', function () {
            table.draw()
        })

        // Allows the Status filter to be triggered on page load
        $('#statusFilter').trigger('change');
    }
}
