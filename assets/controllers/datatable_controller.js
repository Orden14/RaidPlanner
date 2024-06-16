import {Controller} from "stimulus"

export default class extends Controller {
    initialize() {
        $(this.element).DataTable({
            language: {
                "lengthMenu": "Afficher _MENU_ résultat par page",
                "zeroRecords": "Aucun résultat trouvé",
                "info": "Page _PAGE_ sur _PAGES_",
                "infoEmpty": "Aucun résultat trouvé",
                "infoFiltered": "(filtré à partir de _MAX_ résultats)",
            }
        })
    }
}
