import {Controller} from '@hotwired/stimulus'

export default class extends Controller {
    confirmRemove(event) {
        event.preventDefault()
        const slotUsername = this.element.dataset.attendanceUser;
        const currentUsername = this.element.dataset.currentUser;

        let message;
        if (slotUsername === currentUsername) {
            message = 'Vous êtes sur le point de vous retirer de la liste des joueurs. Êtes-vous sûr de vouloir continuer ?'
        } else {
            message = 'Êtes-vous sûr de vouloir retirer ' + slotUsername + ' de la liste des joueurs ?'
        }

        $.confirm({
            icon: 'bi bi-exclamation-triangle-fill',
            theme: 'supervan',
            title: 'Enlever un joueur',
            content: message,
            type: 'red',
            typeAnimated: true,
            buttons: {
                confirm: {
                    text: 'Confirmer',
                    action: () => {
                        $(this.element).trigger('submit')
                    }
                },
                cancel: {
                    text: 'Annuler'
                }
            }
        })
    }
}
