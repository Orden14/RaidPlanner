import { Controller } from '@hotwired/stimulus'
import { confirm } from '../../javascript/components/confirm_modal'

export default class extends Controller {
    confirmRemove (event) {
        event.preventDefault()
        const slotUsername = this.element.dataset.attendanceUser
        const currentUsername = this.element.dataset.currentUser

        let message
        if (slotUsername === currentUsername) {
            message = 'Vous êtes sur le point de vous retirer de la liste des joueurs. Êtes-vous sûr de vouloir continuer ?'
        } else {
            message = 'Êtes-vous sûr de vouloir retirer ' + slotUsername + ' de la liste des joueurs ?'
        }

        confirm(this.element, 'Attention !', message)
    }
}
