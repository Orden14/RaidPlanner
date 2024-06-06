import {Controller} from '@hotwired/stimulus'
import {confirm} from "../javascript/components/confirm_modal"

export default class extends Controller {
    confirmDeletion(event) {
        event.preventDefault()
        confirm(this.element, 'Attention ! ', 'Êtes-vous sûr de vouloir procéder à la suppression ?')
    }
}
