import {Controller} from "stimulus"
import 'datatables.net-bs5'
import 'datatables.net-select-bs5'

export default class extends Controller {
    initialize() {
        let table = $(this.element).DataTable({
            responsive: true,
            language: {
                "lengthMenu": "Afficher _MENU_ résultat par page",
                "zeroRecords": "Aucun résultat trouvé",
                "info": "Page _PAGE_ sur _PAGES_",
                "infoEmpty": "Aucun résultat trouvé",
                "infoFiltered": "(filtré à partir de _MAX_ résultats)",
            },
        })
    }
}
